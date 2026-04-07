# Despliegue: Gano Ops Hub (subdominio y Pages)

## Opción A — GitHub Pages (recomendada para empezar)

1. En **Gano-digital/Pilot** → *Settings* → *Pages*.
2. *Build and deployment* → **Source: GitHub Actions**.
3. Ejecutar workflow **14 · Ops · Gano Ops Hub** (o esperar push que toque `TASKS.md`).
4. La URL aparece en el resumen del job *deploy* (típicamente `https://<org>.github.io/<repo>/`).

### Dominio personalizado `ops.gano.digital`

1. En *Pages* → *Custom domain* → `ops.gano.digital`.
2. En DNS (GoDaddy u otro), crear **CNAME** `ops` → `<usuario>.github.io` (o los registros que GitHub muestre para verificación).
3. Esperar propagación y HTTPS (GitHub emite certificado).

## Opción B — Mismo hosting Managed WordPress

Subir solo `index.html` + `data/progress.json` vía SFTP a una carpeta pública del subdominio **si** el plan permite subdominio y archivos estáticos fuera de WordPress. Suele ser más fricción que Pages.

## Opción C — Cloudflare Pages / Netlify

Conectar el repo y definir:

- **Build command:** `python scripts/generate_gano_ops_progress.py`
- **Publish directory:** `tools/gano-ops-hub/public`

(Instalar Python en el build image o usar imagen custom.)

## Notas

- **Vercel** en org privada puede chocar con plan Hobby; Pages en GitHub evita ese límite en muchos casos.
- No exponer secretos en el HTML: el dashboard solo enlaza a Actions públicos del repo.
- **Métricas del tablero GitHub Project** en el JSON público: solo agregados (conteos por Status, lista corta *In progress*). El secret `ADD_TO_PROJECT_PAT` del workflow **13** se reutiliza en el **14**; no se imprime en logs.
