# Despliegue: Gano Ops Hub (subdominio y Pages)

## Estado configurado (Gano-digital/Pilot)

- **GitHub Pages** está **activo** con origen **Deploy from a branch** (legacy): rama **`gh-pages`**, carpeta **`/`** (raíz).
- **URL prevista:** [https://gano-digital.github.io/Pilot/](https://gano-digital.github.io/Pilot/)

### Repositorio privado y 404 en `github.io`

El repo **Pilot** es **privado**. En GitHub **sin** plan Team/Enterprise Cloud (Pages privado), **no se publica** un sitio público en `*.github.io` desde ese repo: los visitantes anónimos ven *“There isn't a GitHub Pages site here”* (404). Esto es una [limitación documentada por GitHub](https://docs.github.com/en/pages/getting-started-with-github-pages/troubleshooting-404-errors-for-github-pages-sites#repository).

**Para que el enlace sea público en `github.io` (elige una):**

1. **Hacer el repositorio público** — *Settings → General → Danger zone → Change repository visibility* (valorar si el código puede ser público).
2. **GitHub Team / Enterprise Cloud** — Pages desde repo privado con visibilidad del sitio configurable.
3. **Netlify o Cloudflare Pages** — Conectar el mismo repo (privado permitido en cuenta gratuita), rama **`gh-pages`**, sin comando de build (sitio ya estático), directorio de publicación **`/`**. Obtendrás una URL `*.netlify.app` o `*.pages.dev` **accesible para todos**.

**Para miembros del org** con Enterprise y Pages privado, la URL puede estar en un subdominio `*.pages.github.io`; revísala en *Settings → Pages → Visit site*.

---

## Actualizar el sitio en `gh-pages` (sin fusionar PR a `main`)

Desde la raíz del clon, con Python y git:

```powershell
pwsh -File scripts/publish-gano-ops-gh-pages.ps1
```

Regenera `progress.json`, sustituye el contenido de la rama `gh-pages` por `tools/gano-ops-hub/public/` y hace `push` a `origin`.

---

## Opción A — GitHub Actions (workflow **14**) cuando esté en `main`

Cuando [`.github/workflows/gano-ops-hub.yml`](../../.github/workflows/gano-ops-hub.yml) exista en la rama por defecto:

1. *Settings* → *Pages* → **Source: GitHub Actions** (sustituye al deploy desde rama, si quieres solo Actions).
2. Ejecutar **14 · Ops · Gano Ops Hub**.

Mientras el PR grande siga bloqueado por políticas del repo, la rama **`gh-pages`** + script anterior mantiene el Hub publicado.

---

## Dominio personalizado `ops.gano.digital`

1. En *Pages* (GitHub) o en el panel de Netlify/Cloudflare → *Custom domains* → `ops.gano.digital`.
2. En DNS (GoDaddy u otro), **CNAME** `ops` → el host que indique el proveedor (`<org>.github.io` o el de Netlify/Cloudflare).
3. Esperar propagación y HTTPS.

---

## Opción B — Mismo hosting Managed WordPress

Subir `index.html` + `data/progress.json` vía SFTP a una carpeta pública del subdominio si el plan lo permite.

## Opción C — Cloudflare Pages / Netlify (build con Python)

Si publicas desde **`main`** en lugar de `gh-pages`:

- **Build command:** `python scripts/generate_gano_ops_progress.py`
- **Publish directory:** `tools/gano-ops-hub/public`

---

## Notas

- **Netlify / Cloudflare Pages** suelen admitir repos privados en plan gratuito con cuota razonable (alternativa a GitHub Pages cuando el repo no es público).
- No exponer secretos en el HTML: el dashboard solo enlaza a recursos del repo; el JSON público no debe contener tokens (el generador no los escribe).
- **Métricas del tablero GitHub Project** en el JSON: solo agregados. El secret `ADD_TO_PROJECT_PAT` del workflow **13** alimenta el **14** en CI; no se imprime en logs.
