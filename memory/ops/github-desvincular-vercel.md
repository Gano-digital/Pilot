# Desvincular Vercel del repo (GitHub)

Este repositorio **no incluye** `vercel.json` ni workflows de Vercel. Si en PRs seguía apareciendo un check “Vercel”, viene de la **GitHub App** instalada en la organización o en el repo.

## Pasos (dueño del org o admin del repo)

1. GitHub → **Organización** o **repositorio** `Gano-digital/Pilot`.
2. **Settings** → **Integrations** → **GitHub Apps** (o **Installed GitHub Apps**).
3. Localizar **Vercel** → **Configure** → **Uninstall** o quitar solo este repositorio de la instalación.
4. Opcional: en [vercel.com](https://vercel.com) → proyecto vinculado → **Settings** → **Git** → disconnect repository.

Tras eso, los PRs dejan de mostrar el status check de Vercel.

## Nota

La URL `https://fnm.vercel.app` en `.gsd/bin/install.js` es el **instalador oficial de fnm** (Fast Node Manager), no el producto “deploy de Vercel”; no debe borrarse.
