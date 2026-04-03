# Triage — Pull requests abiertas (Copilot) — 2026-04-02

**Repo:** `Gano-digital/Pilot` · **Total:** 35 PRs abiertas · **Todas en borrador** · Autor: **Copilot**

Este documento asiste la revisión humana y el orden de merge. Actualizar tras cada merge a `main`.

### Actualización 2026-04-02 (post-integración local CI)

- **`main` (repo):** `deploy.yml` usa secrets `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH` (sin host/usuario en claro). Nuevo `verify-patches.yml` (comparación MD5 Fase 1–3). `orchestrate-copilot-waves.yml` sin `{ pr: 49 }` en wave1 (README cubierto por #51).
- **Revisión:** añadidos [`.github/AGENT-REVIEW-CHECKLIST.md`](../../.github/AGENT-REVIEW-CHECKLIST.md) y *Definition of Done* en `copilot-bulk-assign.md`.
- **PR #47:** si el contenido ya está en `main`, cerrar la PR y enlazar el commit; evitar merge duplicado.

---

## 1. Estado global (proceso)

| Hallazgo | Acción |
|----------|--------|
| 100 % draft | Marcar **Ready for review** cuando CI objetivo esté verde; sin eso el equipo no puede fusionar en bloque. |
| `mergeable_state: dirty` (ej. #47) | **Actualizar rama** desde `main` o resolver conflictos antes de merge. |
| `mergeable_state: unstable` (varias) | Suele ser CI en curso o políticas de rama; revisar pestaña **Checks** en GitHub. |
| Status commit **Vercel failure** | A menudo **ruido** (plan Hobby vs org privada); no bloquea por sí solo el merge de PHP/docs si Actions Gano pasan. |
| Ramas con `base` distinto de último `main` | Tras merges, pedir **Update branch** en las PR restantes. |

**Feedback a agentes (Copilot):**

1. Tras abrir PR: comprobar **sin conflictos con `main`**; si `dirty`, ejecutar merge/rebase local o botón Update branch.
2. Separar PRs enormes: preferir **un issue → un PR pequeño** (ya alineado con `copilot-bulk-assign.md`).
3. Issues solo servidor/Elementor: **no** forzar cambio en repo; comentario en issue + cerrar PR vacío si aplica.

---

## 2. Orden de merge recomendado (MERGE-PLAYBOOK + riesgo)

### Fase A — Documentación y cola (bajo riesgo, desbloquea contexto)

| PR | Título (corto) | Notas |
|----|----------------|-------|
| #51 | README drift + enlaces | Solo `README.md`; buen candidato **primero** si no hay conflicto. |
| #49 | docs drift README (similar a #51) | **Revisar duplicación con #51** — quizá fusionar una y cerrar la otra. |
| #69 | Oleada 3 índice en TASKS | Solo `TASKS.md`; alinea con issue #54. |

### Fase B — CI / deploy (alto impacto, revisar antes)

| PR | Título | Notas |
|----|--------|-------|
| #47 | fix(CI) deploy secrets + verify-patches | **Prioridad seguridad** (quita credenciales en claro). `mergeable_state: dirty` → actualizar desde `main`. Alinear con patrón `sync_if_exists` del `deploy.yml` actual del repo para plugins opcionales. Verificar secrets: `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH`. |

### Fase C — Tema `gano-child` y plugins (orden sugerido)

| Rango PR | Tema |
|----------|------|
| #34 | Menú `primary` — base para header Elementor |
| #40 | Handle parent style rename |
| #41 | LCP MutationObserver |
| #45 | Tokens `--gano-gold` |
| #35–#38 | Hero, pilares, socio tecnológico, CTA iconos |
| #39 | Coming soon / borrador |
| #83 | a11y focus-visible + skip link |

### Fase D — SEO / MU / operación

| PR | Notas |
|----|-------|
| #42 | Rank Math / schema — revisar impacto con `gano-seo.php` |
| #43 | wp-file-manager — mucho es **verificación manual** en servidor |
| #44 | GSC — idem, parte panel |

### Fase E — Reseller / Woo / humo

| PR | Notas |
|----|-------|
| #46 | CTAs `shop-premium` + IDs Reseller — validar constantes con RCC reales |
| #48 | Sandbox reseller |
| #52 | Smoke test checkout |

### Fase F — Oleada 3 (docs + contenido + código)

| PR | Notas |
|----|-------|
| #70–#82 | Mayoría `memory/**/*.md` o research; merge por lotes tras revisión de duplicados |
| #78 | Toca PHP (`gano_faq_questions`) — revisar CI PHP + TruffleHog |

### Fase G — Auditoría / datos

| PR | Notas |
|----|-------|
| #50 | Placeholders USA — revisar que no rompa flujos |
| #53 | Footer/contacto wave3 |

---

## 3. Lista completa de PRs (# asc)

34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83

---

## 4. Hecho (2026-04-03)

- [x] **#69** fusionada (squash): subsección Oleada 3 en `TASKS.md` + enlaces brief / `tasks-wave3.json`.
- [x] **#51** fusionada (squash): README drift + secret `SSH` + `COPILOT-AGENT-QUEUE.md`.
- [ ] **#49** — *Update branch* falla por **conflicto con `main`**; README cubierto por #51. Valor residual: cambios en `.gano-skills/` si aún no están en `main` → PR pequeña nueva o cerrar #49.
- [ ] **#47** — *Update branch* falla por **conflicto**; resolver a mano (deploy secrets + alinear con `sync_if_exists` en `main`).

## 5. Siguiente acción inmediata (Diego / revisor)

1. **#47:** Resolver conflictos con `main`, revisar `deploy.yml` / `verify-patches.yml`, fusionar cuando CI verde.
2. **#49:** Cerrar o reemplazar por PR mínima solo skills.
3. Oleada homepage **#34–#40** (tema): marcar ready y revisar por orden MERGE-PLAYBOOK.
4. Resto oleada 3 docs: por lotes tras #47 estable.

---

## 6. Archivo legacy (Wompi)

Política: **archivado** — ver `memory/archive/README-ARCHIVO-WOMPI-Y-PASARELAS-LEGACY.md`. Las PRs que aún mencionen sync a `gano-wompi-integration` deben alinearse con **solo desplegar si existe** / prioridad Reseller.
