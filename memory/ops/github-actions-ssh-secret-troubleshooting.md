# SSH en GitHub Actions — `error in libcrypto` / `ssh-add`

Afecta workflows que usan `webfactory/ssh-agent` con el secret **`SSH`** (p. ej. **04 Deploy**, **05 Verificar parches**, **12 wp-file-manager**).

## Qué significa

`Error loading key "(stdin)": error in libcrypto` indica que OpenSSL no puede interpretar el contenido del secret: clave vacía, truncada, con **passphrase** (el action no la pide), finales de línea **CRLF** pegados desde Windows, o formato PEM antiguo problemático en el runner.

## Cómo arreglarlo (recomendado: clave nueva solo para CI)

En tu máquina (PowerShell o Git Bash), **sin passphrase**:

```bash
ssh-keygen -t ed25519 -C "github-actions-gano" -N "" -f gano-ci-deploy -m PEM
```

1. **Public key** (`gano-ci-deploy.pub`): en el servidor, añádela a `~/.ssh/authorized_keys` del usuario que usa GitHub (`SERVER_USER`), o en cPanel “SSH Access” / authorized keys según tu hosting.
2. **Private key** (`gano-ci-deploy`, contenido completo): en GitHub → repo **Pilot** → **Settings → Secrets and variables → Actions** → edita **`SSH`** y pega:
   - Desde `-----BEGIN` hasta `-----END ... KEY-----`
   - Preferible pegar con **saltos de línea Unix** (si algo falla, abre el archivo en VS Code, abajo derecha “CRLF” → “LF”, guarda y copia de nuevo).

## Qué no pegar

- Archivos **.ppk** (PuTTY): conviértelos a OpenSSH (`puttygen` → Conversions → Export OpenSSH key) o genera una clave nueva como arriba.
- Claves con passphrase: el workflow no las desbloquea; usa `-N ""` al generar.

## Verificación local

```bash
ssh-add gano-ci-deploy
ssh -o BatchMode=yes SERVER_USER@SERVER_HOST "echo ok"
```

Si `ssh-add` falla en tu PC con el mismo PEM, el secret en GitHub tampoco funcionará.

## `Permission denied (publickey)` (cuando `ssh-add` ya funciona)

Si el workflow **04** pasa *Setup SSH Agent* pero falla en **rsync** o **ssh** con `Permission denied (publickey)`:

1. El secret **`SSH`** debe ser la **privada** que corresponde a la **misma** `.pub` que está en `~/.ssh/authorized_keys` del **`SERVER_USER`** en el host **`SERVER_HOST`**.
2. Compara **huellas** (fingerprint): en el log del job, el paso *Huella de clave en ssh-agent* ejecuta `ssh-add -l`; localmente, `ssh-keygen -lf ~/.ssh/tu_clave.pub` debe mostrar la **misma** huella que la entrada del agente en CI.
3. Verifica que `SERVER_USER` y `SERVER_HOST` en GitHub coincidan exactamente con el par `usuario@host` con el que pruebas en local.

## Secrets relacionados

- `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH` deben coincidir con el usuario que tiene la clave en `authorized_keys`.
