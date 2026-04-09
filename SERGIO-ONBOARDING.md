# 👋 Bienvenida Sergio — Guía de Onboarding

> **Para:** Sergio Arias (`sergioeancontact-alt`)
> **Rol:** Front-end Developer — Fase 3.5 Arcana (CSS/UX/Design)
> **Fecha:** 2026-04-09

---

## 1️⃣ Antes de Comenzar

Tienes un **folio sensible** (`00-FOLIO-SENSIBLE-CREDENCIALES.md`) con:
- Credenciales SSH, BD, cPanel
- GitHub tokens y configuración
- Setup local (Laragon/WordPress)

**Lee primero ese documento** — la información aquí asume que ya tienes los accesos configurados.

---

## 2️⃣ Estructura del Proyecto en 3 Minutos

```
Gano.digital/
├── wp-content/themes/gano-child/        ← 🎯 Aquí trabajas (CSS, templates)
│   ├── style.css                        ← Design tokens + componentes
│   ├── functions.php                    ← Enqueue JS/CSS, CSP headers
│   ├── templates/                       ← Plantillas PHP (page-*, single-*)
│   └── assets/
│       ├── images/                      ← hero-datacenter.jpg (tu PR #172)
│       └── js/                          ← GSAP animations, scroll reveals
├── wp-content/mu-plugins/               ← Seguridad + SEO (NO toques)
├── wp-content/plugins/gano-*/           ← Plugins custom (Reseller, Fase 3)
├── CLAUDE.md                            ← Contexto del proyecto (para IA)
├── TASKS.md                             ← Estado por fases + checklist
├── SERGIO-ONBOARDING.md                 ← Este archivo
├── README.md                            ← Info general
└── .github/
    ├── DEV-COORDINATION.md              ← Flujo: local → PR → deploy
    ├── DESIGN-TOKENS-GUIDE.md           ← Cómo usar CSS tokens (NUEVO)
    ├── workflows/                       ← CI/CD (deploys automáticos)
    └── ISSUE_TEMPLATE/                  ← Plantillas para reportes
```

**Lo importante para ti:**
- **Modificas:** `wp-content/themes/gano-child/` (todo lo visual)
- **Nunca toques:** MU-plugins, `wp-config.php`, secrets
- **Refiérete a:** `TASKS.md` (qué hay que hacer), `DEV-COORDINATION.md` (flujo integrado)

---

## 3️⃣ Setup Local en 10 Minutos

### Prerequisitos
- **Laragon** (Windows) O **Docker** (Mac/Linux)
- **Git** instalado
- **SSH key** configurada (ver folio sensible)

### Pasos

```bash
# 1. Clonar repo
cd ~/projects
git clone https://github.com/Gano-digital/Pilot.git gano-local
cd gano-local

# 2. Inicializar submódulos
git submodule update --init

# 3. Copiar configuración local
cp wp-config-sample.php wp-config.php

# 4. Abrir wp-config.php y editar:
# - DB_NAME: gano_local
# - DB_USER: root (Laragon) u otro según tu setup
# - DB_PASSWORD: '' (vacío en local)
# - DB_HOST: localhost:3306

# 5. En Laragon: crear BD "gano_local"
# O por terminal:
mysql -u root -e "CREATE DATABASE gano_local;"

# 6. Acceder a WordPress
# URL: http://gano.local/wp-admin
# (Laragon mapea automáticamente; si no, añade a hosts)
```

**Primera vez en WP Admin:**
```
1. Plugin → Instalar:
   - gano-phase3-content ← Activa (crea páginas)
   - gano-content-importer ← Activa (crea 20 SOTA), luego desactiva
2. Ver homepage en http://gano.local
3. Abrir wp-content/themes/gano-child/style.css en tu editor
```

---

## 4️⃣ Tu Workflow — Branch → Commit → PR

### Paso 1: Crear rama feature
```bash
# Desde main (siempre)
git checkout main
git pull origin main

# Nueva rama (nombre descriptivo)
git checkout -b arcana/nombre-feature
# Ejemplos: arcana/wave4-animations, arcana/fix-hero-spacing
```

### Paso 2: Editar en local + test
```bash
# Edita en Laragon (hot reload en navegador)
# Ejemplo: wp-content/themes/gano-child/style.css
#         wp-content/themes/gano-child/templates/page-contacto.php

# Verifica en navegador: http://gano.local
# CSS tokens: abre DevTools → buscaVariables (--gano-*)
```

### Paso 3: Commit atómico
```bash
# Añade solo archivos relevantes (NO wp-config local)
git add wp-content/themes/gano-child/style.css
git add wp-content/themes/gano-child/templates/page-contacto.php

# Mensaje claro
git commit -m "feat(hero): overlay oscuro + text-shadow fuerte"
# O: "refactor(contacto): mover inline styles a CSS"
# O: "fix(woo): hardcoded #000 → var(--gano-dark)"
```

### Paso 4: Push y PR
```bash
# Push a tu rama
git push origin arcana/nombre-feature

# GitHub sugiere crear PR (recibirás notificación)
# O manualmente: https://github.com/Gano-digital/Pilot/pull/new/arcana/nombre-feature
```

### Paso 5: CI checks + Review
- **GitHub Actions** corre automáticamente:
  - `02 · CI · Escaneo secretos` — No hay credenciales? ✅
  - `03 · PR · Etiquetas automáticas` — Asigna área
- **Diego revisa** → te da feedback O aprueba
- **Tú mergeas** cuando tengas aprobación

### Paso 6: Deploy automático
- Merge a `main` → `04 · Deploy workflow` corre
- rsync a servidor GoDaddy
- wp cache flush
- Site verifica HTTP 200
- **LIVE en 30 segundos** ✅

---

## 5️⃣ Design Tokens — Referencia Rápida

**NUNCA hardcodees colores, espacios, fuentes.**

### Dónde están los tokens
`wp-content/themes/gano-child/style.css` — líneas 1–150 (`:root`)

### Categorías principales

**Colores:**
```css
--gano-dark:        #0F172A;
--gano-white:       #FFFFFF;
--gano-purple:      #8B5CF6;       /* Nuevo Kinetic Monolith */
--gano-indigo:      #6366F1;
--gano-gray-400:    #9CA3AF;
```

**Espacios (padding/margin):**
```css
--gano-space-xs:    0.5rem;    (8px)
--gano-space-sm:    1rem;      (16px)
--gano-space-md:    1.5rem;    (24px)
--gano-space-lg:    2rem;      (32px)
--gano-space-xl:    3rem;      (48px)
```

**Tipografía:**
```css
--gano-font-heading:     'Plus Jakarta Sans', sans-serif;
--gano-font-body:        'Inter', sans-serif;
--gano-fs-xs:            0.75rem;   (12px)
--gano-fs-sm:            0.875rem;  (14px)
--gano-fs-base:          1rem;      (16px)
--gano-fs-lg:            1.125rem;  (18px)
--gano-fs-4xl:           2.25rem;   (36px)
```

**Radio (border-radius):**
```css
--gano-radius-sm:        0.25rem;   (4px)
--gano-radius-md:        0.5rem;    (8px)
--gano-radius-lg:        1rem;      (16px)
--gano-radius-pill:      9999px;    (fully rounded)
```

### Cómo usarlos

❌ **MALO:**
```css
.product-badge {
  color: #000;
  border-radius: 50px;
  font-size: 11px;
}
```

✅ **CORRECTO:**
```css
.product-badge {
  color: var(--gano-dark);
  border-radius: var(--gano-radius-pill);
  font-size: var(--gano-fs-xs);
}
```

### Si falta un token
1. **Busca en `:root`** — probablemente esté con otro nombre
2. **Pregunta en PR** — Diego o Claude pueden ayudarte
3. **NO crees nuevos** sin coordinar (mantener paleta uniforme)

**Documento completo:** [DESIGN-TOKENS-GUIDE.md](.github/DESIGN-TOKENS-GUIDE.md) (en repo)

---

## 6️⃣ Fases y Contexto — Dónde Encajas

### Fase 3.5 — SOTA + Arcana (donde estás)

**SOTA = State-of-the-art:** 20 páginas de contenido estratégico + animaciones GSAP

**Arcana = Art direction (Magician/Guardian archetypes):** Hero visual, paleta Kinetic Monolith, UX mejorada

**Tus 5 PRs en orden:**
1. PR #167 — Wave 3 UX (skeleton loaders, form success) — **Mergear primero**
2. PR #168 — Contacto (inline styles → CSS)
3. PR #169 — WooCommerce (hardcoded values)
4. PR #170 — Art direction doc (referencia)
5. PR #172 — Hero image + overlay (visual hero)

**Plan:** Mergear en ese orden → deploy cada una → validate en vivo

Detalles completos: [`TASKS.md`](TASKS.md) § Fase 3.5

---

## 7️⃣ Comandos Útiles

```bash
# Estado local
git status
git log --oneline -10

# Sincronizar con origin
git fetch origin
git rebase origin/main          # Si origin se adelantó

# Revisar tus cambios antes de commit
git diff wp-content/themes/gano-child/style.css

# Ver qué está en main vs tu rama
git diff main..arcana/tu-rama

# Deshacer cambios locales (PELIGRO)
git checkout -- wp-content/themes/gano-child/style.css

# Ver ramas remotas
git branch -a
```

---

## 8️⃣ Reglas de Oro

### ✅ Haz
- Commit **pequeños y atómicos** (1 cambio lógico = 1 commit)
- Mensajes de commit **descriptivos** en español (ej: "feat(hero):", "fix(woo):")
- PR con **descripción y checklist** (template auto-completa)
- Test local en Laragon antes de push
- Rebase en `main` si hay conflictos (NO merge commits)

### ❌ NO hagas
- Hardcodear colores, espacios, fuentes (usa tokens)
- Commitar `wp-config.php`, `.env`, secretos
- Force push (`git push --force`) — coordina con Diego si necesitas
- Mergear tu propio PR sin aprobación
- Editar directamente en producción (siempre local → PR → deploy)

---

## 9️⃣ Pedir Ayuda

| Tema | Contactar |
|------|-----------|
| CSS / Design tokens | Claude Code (`/ask`) O PR review |
| Flujo git / ramas | Claude Code — `git` tips |
| Acceso servidor SSH | Diego (clave expirada, permisos) |
| Deploy o CI error | Claude Code — revisar logs en Actions |
| WP Admin o plugins | Diego (admin password, activaciones) |
| Duda arquitectura | CLAUDE.md + TASKS.md + Diego (meeting) |

---

## 🔟 Próximos Pasos

1. ✅ Lee el folio sensible (`00-FOLIO-SENSIBLE-CREDENCIALES.md`)
2. ✅ Setup local (Laragon + BD `gano_local`)
3. ✅ Verifica SSH: `ssh gano-godaddy "wp --version"`
4. ✅ Clona repo: `git clone https://github.com/Gano-digital/Pilot.git`
5. ✅ Crea rama: `git checkout -b arcana/test`
6. ✅ Hace cambio pequeño (ej: comentario en style.css)
7. ✅ Commit + push: `git push origin arcana/test`
8. ✅ Abre PR en GitHub → verifica CI pasa
9. ✅ Cierra PR (test) — no mergear
10. ✅ Listo para trabajar en PRs reales ✅

---

## 📚 Referencias Rápidas

| Documento | Para qué |
|-----------|----------|
| `CLAUDE.md` | Contexto general (plugins de fase, stack tech) |
| `TASKS.md` | Qué hay que hacer por fase + checklist |
| `DEV-COORDINATION.md` | Flujo: local → PR → deploy + secrets |
| `DESIGN-TOKENS-GUIDE.md` | Cómo usar CSS variables (NUEVO) |
| `.github/workflows/README.md` | Explicación de cada workflow CI |
| `.github/pull_request_template.md` | Plantilla PR (auto-completa en new PR) |

**Leelos en orden:** CLAUDE.md → TASKS.md → DEV-COORDINATION.md

---

## ✨ ¡Bienvenido al equipo!

Estás en Fase 3.5 — la más visual. Tu trabajo en CSS/UX es crítico para que gano.digital brille.

**Cualquier duda → escribe en PR, issue o GitHub Discussions.**

**Diego estará pendiente de tus primeros PRs.**

🚀 **¡Adelante!**

