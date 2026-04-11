# Recordatorio — oleada API (ML + GoDaddy) + cierre PRs oleada 4 / infra

## 1. Herramienta API (tras el próximo push a `main`)

1. **Actions → 08 · Sembrar cola Copilot**  
   - `queue_file`: **`tasks-api-integrations-research.json`**  
   - `scope`: **`all`** (o `docs` / `coordination` por partes).
2. Asignar **GitHub Copilot** a los issues `api-*`.
3. Pegar el bloque **“API Mercado Libre + GoDaddy (research / oleada api-*)”** desde [`.github/prompts/copilot-bulk-assign.md`](../.github/prompts/copilot-bulk-assign.md).

Referencia: [`.github/COPILOT-AGENT-QUEUE.md`](../.github/COPILOT-AGENT-QUEUE.md) (entrada `tasks-api-integrations-research.json`).

---

## 2. PRs oleada Copilot (wave4 + infra) — consolidado 2026-04-03

En GitHub **Gano-digital/Pilot** se fusionaron (squash) los PR **#100–#113** (docs oleada 4 + infra DNS/HTTPS). El **#106** requirió **Update branch** una vez (`HEAD` desactualizado respecto de `main` tras merges previos).

**Checks:** revisar en GitHub que CI propio (PHP, secretos) esté en verde; CodeQL completó en verde donde aplica.

**Copia local:** tras fusionar este trabajo, ejecutá `git pull origin main` en otras máquinas si quedaron atrasadas.

---

_Ver `TASKS.md` (ítem “Cola API Mercado Libre + GoDaddy”) y `memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`._
