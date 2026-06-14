# Investigación SOTA — CI/CD, cadena de suministro y agentes (abr 2026)

**Propósito:** síntesis ejecutable para Gano Digital (WordPress + GitHub + hosting GoDaddy + runners self-hosted + agentes). Complementa [`sota-workflow-ops-parallel-2026-04.md`](sota-workflow-ops-parallel-2026-04.md) y [`sota-operativo-2026-04.md`](sota-operativo-2026-04.md) sin sustituirlos.

**Audiencia:** Diego, agentes (Cursor/Copilot/Claude).

---

## 1. Hallazgos externos (referencias oficiales / consenso 2025–2026)

| Tema | Síntesis SOTA | Enlace |
|------|----------------|--------|
| **Uso seguro de Actions** | Aislamiento, permisos mínimos del `GITHUB_TOKEN`, revisión de workflows; uso responsable de runners self-hosted. | [GitHub Docs — Secure use](https://docs.github.com/en/actions/reference/security/secure-use) |
| **OWASP Top 10:2025 — A03** | *Software Supply Chain Failures*: dependencias, pipelines, integraciones; trasciende “librería vulnerable”. | [OWASP A03:2025](https://owasp.org/Top10/2025/A03_2025-Software_Supply_Chain_Failures/) |
| **Runners self-hosted** | Tratar el host como **producción**: menor privilegio, aislamiento de red, evitar ejecutar jobs de repos **públicos** no confiables en el mismo runner que despliegues sensibles (riesgo de PR malicioso ejecutando código en el servidor). | Docs anteriores + práctica industria |
| **Cadena de suministro npm/PHP** | SBOM (CycloneDX/SPDX), SCA (OSV, NVD), Dependabot/renovate — alineado con mitigación Dependabot en `.gsd/sdk`. | OWASP A03; práctica CI |

---

## 2. Colisión con Pilot / Gano Digital

| Realidad repo | Lectura SOTA |
|---------------|--------------|
| **04 Deploy** en `self-hosted` (`gano-production`) | Correcto para **no quemar minutos** de GitHub-hosted; exige **hardening** del host (solo repos/org de confianza, etiquetas de runner acotadas, logs). |
| **Minutos Actions agotados** en jobs `ubuntu-latest` | Típico en org privada; opciones: **self-hosted** para más jobs, **repo público** (política de uso gratuito amplio para estándar — decisión de negocio), o **reducir frecuencia** de workflows no P0. |
| **Webhook `receive.php` + ZIP** | Cadena de suministro **custom**: el riesgo es clave HMAC débil o endpoint abusado; mitigar en servidor (rate limit, allowlist IP, secreto largo). |
| **Árbol WordPress + plugins** | A03: inventario de plugins, origen, actualización; “virtual patching” solo como puente. |
| **Agentes (Copilot/Cursor/Claude)** | SOTA pide **revisión humana** en merges sensibles, no credenciales en colas JSON, TruffleHog en rutas Gano. |

---

## 3. Checklist priorizada (sin bloquear vitrina)

### P0 — Seguridad borde CI

- [ ] Runners self-hosted: **solo** repositorios de la org de confianza; documentar quién reinicia el servicio (`gano-godaddy-server`).
- [ ] No reutilizar el mismo runner para **forks públicos** o PRs de terceros sin aislamiento.
- [ ] Tras intento de **repo público:** escanear **historial** con TruffleHog/gitleaks y rotar credenciales tocadas.

### P1 — Cadena de suministro

- [ ] Mantener **Dependabot/overrides** en herramientas Node (`.gsd/sdk`) como ya se hizo.
- [ ] Tabla viva de **plugins WP en producción** (nombre, versión, propietario de actualización) — puede vivir en `memory/ops/` o issue.

### P2 — Ingeniería posterior

- [ ] SBOM opcional para builds que generen artefactos (no prioritario mientras el producto sea WP gestionado en hosting).

---

## 4. Enlaces cruzados en el repo

| Documento | Relación |
|-----------|----------|
| `.github/DEV-COORDINATION.md` | Flujo git ↔ servidor |
| `memory/research/sota-workflow-ops-parallel-2026-04.md` | Carriles A/B/C |
| Redacción huella operativa (abr 2026) | Skills cPanel/QUICK_START + memoria — placeholders `<IP_SERVIDOR>` |
| [`investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md`](../ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md) | SSL, Installatron/Drupal vs WordPress, backups — complemento ops |
| `.gano-skills/gano-github-copilot-orchestration/SKILL.md` | Orquestación + sección SOTA 2026-04-09 |
| `.gano-skills/gano-github-ops/SKILL.md` | Ops GitHub + runners |

---

## 5. Transición a repositorio **público**

**Estado 2026-04-10:** `Gano-digital/Pilot` es **PUBLIC** (`gh repo view … --json visibility`). Sigue existiendo al menos un runner self-hosted **online** (`gano-godaddy-server`, etiquetas `self-hosted`, `gano-production`) — **riesgo crítico** hasta desregistrarlo o aislarlo en una VM sin acceso a WordPress producción.

**Orden recomendado de seguridad:** no mantener el mismo runner self-hosted en el **servidor de producción** con el repo **público** sin aislamiento: un PR/fork puede disparar workflows que ejecuten código en ese host.

1. **OK explícito del equipo** (legal/comercial/ops) sobre visibilidad pública del código. *(Hecho si el repo ya es público.)*
2. **TruffleHog / gitleaks** sobre **historial completo**; rotar cualquier secreto que haya tocado un commit.
3. **Runners self-hosted:** antes o en el mismo acto que el cambio a público — **desregistrar** el runner desde GitHub (y parar el servicio `actions.runner.*` en el host) **o** mover el deploy a **solo** webhook/SFTP sin job self-hosted en ese máquina.
4. Revisar **workflow 04**: si sigue en `runs-on: self-hosted`, tras desregistrar el runner los jobs fallarán hasta que se cambie a `ubuntu-latest` + otro canal de deploy, o se use runner **dedicado y aislado** (no producción WP).
5. **Secretos** en GitHub: revisar que ningún secret sea reutilizado en servicios críticos sin rotación post-público.

### Comandos útiles (CLI; requieren permisos en el repo u org)

Listar runners del repo **Pilot**:

```bash
gh api repos/Gano-digital/Pilot/actions/runners --jq '.runners[] | {id, name, status, os}'
```

Eliminar un runner por **id** (lo desvincula; en el servidor conviene parar el servicio del runner después):

```bash
gh api -X DELETE repos/Gano-digital/Pilot/actions/runners/RUNNER_ID
```

Si el runner está registrado a **nivel organización** (no repo), la ruta API es `orgs/ORG/actions/runners/...` — ver [documentación de GitHub](https://docs.github.com/en/rest/actions/self-hosted-runners).

---

## 6. Próximo repaso sugerido

**Ventana:** 30 días o al cambiar visibilidad del repo / rotación masiva de secretos.

**Trigger:** merge de cambios grandes en `.github/workflows/` o alta de runner nuevo.

_Actualizado: 2026-04-10 (Pilot público; runner self-hosted aún online — mitigar)._
