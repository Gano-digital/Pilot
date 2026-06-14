# Nota de seguridad — Permisos mínimos en workflows y forks (GitHub Actions)

**Fecha:** 2026-04. **Alcance:** repo `Gano-digital/Pilot` (público).
**Audiencia:** Diego, agentes (Copilot/Cursor/Claude) y cualquier colaborador que cree o revise workflows.

---

## 1. Contexto

El repositorio es **público** y cuenta con al menos un runner self-hosted
(`gano-godaddy-server`) que tiene acceso directo a producción WordPress.
En ese escenario los permisos mal configurados en workflows pueden exponer
secretos o permitir ejecución de código no revisado.

Este documento complementa:
- [`github-actions-audit-2026-04.md`](github-actions-audit-2026-04.md) — inventario y fallos ya corregidos.
- [`security-guardian-playbook-2026.md`](security-guardian-playbook-2026.md) — higiene operativa entre sesiones.
- [`../research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md`](../research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md) — cadena de suministro CI/CD.

---

## 2. La clave `permissions:` en workflows

### ¿Qué es?

`permissions:` controla los **permisos del `GITHUB_TOKEN`** para el job o el workflow completo.
Por defecto GitHub otorga permisos amplios (modo `permissive`) en muchos planes/repos;
declararlos explícitamente aplica **el principio de mínimo privilegio**.

### Configuración recomendada

**A nivel de workflow completo** (primera opción):

```yaml
permissions:
  contents: read        # solo lectura del repo
```

**Por job** (más granular cuando un step necesita más):

```yaml
jobs:
  deploy:
    permissions:
      contents: read
      id-token: write   # solo si el job usa OIDC
```

### Permisos más comunes en este repo

| Workflow | Permiso necesario | Clave sugerida |
|----------|-------------------|----------------|
| `php-lint-gano.yml` | Leer código | `contents: read` |
| `secret-scan.yml` | Leer código | `contents: read` |
| `labeler.yml` | Escribir etiquetas en PR | `pull-requests: write` |
| `seed-copilot-queue.yml` | Crear issues | `issues: write` |
| `deploy.yml` | Leer código + SSH externo | `contents: read` (SSH usa secret, no token) |

### Permisos que conviene evitar salvo necesidad

| Permiso | Riesgo |
|---------|--------|
| `contents: write` sin necesidad | Permite commits automatizados sin revisión |
| `actions: write` | Puede crear, cancelar o reactivar workflows |
| `packages: write` | Publica paquetes en GitHub Packages sin revisión |
| `secrets: write` | **Nunca** asignar a agentes externos; es gestionado por GitHub |

---

## 3. Riesgo de `pull_request_target`

### Qué hace

`pull_request_target` se ejecuta en el contexto de la **rama base** (main),
no de la rama del PR. Por eso **tiene acceso a secretos del repositorio**,
incluso en PRs de forks no confiables.

### Escenario de ataque

1. Un colaborador externo abre un PR desde su fork con código malicioso en el workflow o en un script llamado por el workflow.
2. Si el repo tiene un workflow con `on: pull_request_target`, ese código se ejecuta con los secretos de `main` en el runner.
3. El atacante puede exfiltrar `SSH_PRIVATE_KEY`, `GH_PAT`, `SERVER_HOST`, etc.

### Cuándo es seguro usar `pull_request_target`

Solo cuando el workflow **no ejecuta código del PR** (por ejemplo, solo lee metadatos del PR vía API o escribe etiquetas). En ese caso, asegurarse de:

```yaml
on:
  pull_request_target:
    types: [opened, labeled]

jobs:
  label:
    permissions:
      pull-requests: write   # solo lo necesario
    steps:
      - name: Label PR
        uses: actions/labeler@v6   # action oficial, no código del fork
        # NO hacer: actions/checkout con ref: ${{ github.event.pull_request.head.sha }}
```

> ⚠️ **Nunca** hacer `checkout` de la rama del PR dentro de un job `pull_request_target`
> a menos que sea en un entorno aislado (container sin acceso a secretos).

### Estado actual en este repo

El workflow `labeler.yml` (03) usa `pull_request` (no `pull_request_target`),
lo que **limita el acceso a secretos** cuando el PR viene de un fork — comportamiento correcto.
Si en el futuro se necesita etiquetar forks, revisar esta sección antes de cambiar el trigger.

---

## 4. Secrets en inputs de `workflow_dispatch`

### El problema

`workflow_dispatch` permite disparar un workflow manualmente pasando **inputs** en la UI de GitHub o vía API. Estos inputs:

- Quedan **registrados en el log del run** (visibles para cualquiera con acceso de lectura al repo o a la pestaña Actions).
- Se almacenan en el historial de ejecuciones de Actions y pueden aparecer en **exportaciones de auditoría**.
- En repos públicos son visibles para **cualquier persona**.

### Regla

> **Nunca** pegar tokens, contraseñas, IPs privadas, claves SSH ni cualquier valor sensible como `input` de `workflow_dispatch`.

### Alternativa correcta

Guardar el secreto en **GitHub Secrets** (Settings → Secrets and variables → Actions) y referenciarlo en el workflow:

```yaml
on:
  workflow_dispatch:
    inputs:
      environment:
        description: "Entorno destino (staging/prod)"
        required: true
        default: "staging"
        type: choice
        options: [staging, prod]
      # ❌ MAL: pedir SSH_KEY o token como input
      # ✅ BIEN: usar ${{ secrets.SSH_PRIVATE_KEY }} en los steps

jobs:
  deploy:
    permissions:
      contents: read
    steps:
      - uses: actions/checkout@v4
      - name: Deploy via SSH
        env:
          SSH_KEY: ${{ secrets.SSH_PRIVATE_KEY }}   # correcto
          SERVER:  ${{ secrets.SERVER_HOST }}        # correcto
        run: |
          echo "$SSH_KEY" | ssh-add -
          # ...
```

### Checklist rápido para revisar inputs existentes

- [ ] Ningún input de `workflow_dispatch` tiene nombre o descripción que sugiera un secreto (ej. `token`, `password`, `api_key`, `ssh_key`).
- [ ] Los workflows que reciben un `environment` como input validan el valor contra una lista (`choice` o `if env == 'staging' || env == 'prod'`).
- [ ] Los logs no exponen variables de entorno cargadas desde secrets (usar `::add-mask::` si es necesario mostrar parte del valor).

---

## 5. Checklist de auditoría rápida por workflow

Antes de mergear cambios en `.github/workflows/`:

- [ ] ¿El workflow declara `permissions:` mínimos (top-level o por job)?
- [ ] Si usa `pull_request_target`, ¿evita el checkout del código del fork?
- [ ] ¿Ningún `workflow_dispatch` input pide valores que deberían ser secrets?
- [ ] ¿Las acciones de terceros están fijadas por versión semántica o SHA? (reducir riesgo de supply chain)
- [ ] En repos públicos con runner self-hosted: ¿solo se ejecuta código de la rama `main` revisada, no del fork?

---

## 6. Referencias

| Recurso | Descripción |
|---------|-------------|
| [GitHub Docs — Permisos del `GITHUB_TOKEN`](https://docs.github.com/en/actions/security-for-github-actions/security-guides/automatic-token-authentication#permissions-for-the-github_token) | Tabla oficial de permisos |
| [GitHub Docs — `pull_request_target` seguro](https://docs.github.com/en/actions/security-for-github-actions/security-guides/security-hardening-for-github-actions#understanding-the-risk-of-script-injections) | Inyección de scripts y forks |
| [GitHub Docs — Hardening for Actions](https://docs.github.com/en/actions/security-for-github-actions/security-guides/security-hardening-for-github-actions) | Guía completa de hardening |
| [`github-actions-audit-2026-04.md`](github-actions-audit-2026-04.md) | Auditoría de workflows Gano Digital (inventario + fallos) |
| [`security-guardian-playbook-2026.md`](security-guardian-playbook-2026.md) | Playbook del guardián de seguridad (sesiones + repo) |
| [`../research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md`](../research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md) | SOTA: cadena de suministro CI, runners self-hosted |

---

_Próxima revisión recomendada: al añadir un workflow nuevo o al cambiar la visibilidad del repo._
