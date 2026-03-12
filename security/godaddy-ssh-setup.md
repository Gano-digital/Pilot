# GoDaddy SSH + CI/CD Setup Guide

> **Current status:**  
> ✅ SSH connection to the server — **working**  
> ⚠️ GoDaddy CI/CD Integration — **Desactivado** → needs one click (Step 4 below)  
> ⚠️ GitHub Secrets — not yet added (Step 5 below)

---

## Your hosting configuration

| Setting | Value |
|---------|-------|
| **SSH/SFTP hostname** | `kfw.f71.myftpupload.com` |
| **Server IP** | `160.153.0.23` |
| **WordPress version** | 6.9.1 |
| **PHP version** | 8.3 |
| **CI/CD integration** | ⚠️ Disabled — click **Habilitar** (Step 4) |

---

## Step 1 — Enable SSH in GoDaddy cPanel ✅ Done

1. Log in to **[my.godaddy.com](https://my.godaddy.com)**.
2. Go to **My Products → Web Hosting → Manage**.
3. In cPanel, search for **"SSH Access"** (or find it under *Security*).
4. Click **Enable SSH Access** if it is shown as disabled.
5. Wait 1-2 minutes for the change to take effect.

> **Note:** SSH may not be available on all GoDaddy shared hosting plans.  
> If you don't see the SSH Access option, contact GoDaddy support and ask  
> them to enable SSH/Terminal access for your account.

---

## Step 2 — Generate an SSH key pair ✅ Done

Run this on your local machine (not on the server):

```bash
ssh-keygen -t ed25519 -a 100 \
  -f ~/.ssh/godaddy_deploy \
  -C "github-actions-deploy-$(date +%Y%m%d)"
```

This creates two files:
- `~/.ssh/godaddy_deploy` — **private key** (keep secret, never commit)
- `~/.ssh/godaddy_deploy.pub` — **public key** (safe to share)

---

## Step 3 — Upload the public key to GoDaddy ✅ Done

### Option A — via cPanel SSH Access manager

1. In cPanel → **SSH Access** → **Manage SSH Keys**.
2. Click **Import Key**.
3. Paste the contents of `~/.ssh/godaddy_deploy.pub` into the *Public Key* field.
4. Give it a name (e.g. `github-actions`).
5. Click **Import**.
6. Back on the *Manage SSH Keys* page, click **Authorize** next to the new key.

### Option B — via GoDaddy Terminal (if available)

```bash
# Connect using password authentication first (one-time setup):
ssh YOUR_CPANEL_USER@kfw.f71.myftpupload.com

# Then add your public key:
mkdir -p ~/.ssh && chmod 700 ~/.ssh
echo "PASTE_PUBLIC_KEY_HERE" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

---

## Step 4 — Enable CI/CD Integration in GoDaddy ⚠️ ACTION NEEDED

Your hosting dashboard shows **"Integración CI/CD: Desactivado"**.

1. In your GoDaddy hosting dashboard, find **Integración CI/CD**.
2. Click **Habilitar** (Enable).
3. This allows GitHub Actions to connect to the server via the SSH/SFTP endpoint.

> ⚠️ **This is the most important step remaining.** Without it, the
> deployment workflow (`Build & Deploy`) will be blocked even though SSH
> works from your local machine.

---

## Step 5 — Add GitHub Secrets ⚠️ ACTION NEEDED

Go to your GitHub repository → **Settings → Secrets and variables → Actions → New repository secret**.

Add **all** of these secrets:

| Secret name | Value | Used by |
|-------------|-------|---------|
| `SERVER_HOST` | `kfw.f71.myftpupload.com` | deploy + audit |
| `SERVER_USER` | your GoDaddy cPanel username | deploy + audit |
| `PRIVATE_KEY` | full contents of `~/.ssh/godaddy_deploy` (the **private** key) | deploy + audit |
| `GEMINI_API_KEY` | your Google Gemini API key — see **[What is the Gemini API key?](#what-is-the-gemini-api-key)** below | deploy (baked into build) |
| `DEPLOY_PATH` | path on server to deploy to, **without trailing slash** (e.g. `~/public_html` or `~/public_html/pilot`) | deploy |
| `FTP_HOST` | `kfw.f71.myftpupload.com` | FTP security deploy |
| `FTP_USER` | your GoDaddy cPanel / FTP username | FTP security deploy |
| `FTP_PASSWORD` | your GoDaddy cPanel / FTP password | FTP security deploy |

> **Note about `DEPLOY_PATH`:** WordPress 6.9.1 is already running in `~/public_html`.
> To avoid conflicts, deploy the Pilot app to `~/public_html/pilot` so both
> coexist at `yourdomain.com/pilot/`.

---

## Step 6 — Test the SSH connection locally ✅ Done

```bash
ssh -i ~/.ssh/godaddy_deploy \
    -o StrictHostKeyChecking=accept-new \
    YOUR_CPANEL_USER@kfw.f71.myftpupload.com
```

You should see a shell prompt. Type `exit` to disconnect.

---

## Step 7 — Trigger the deployment workflow ⚠️ After Step 4 & 5

Once Steps 4 and 5 are complete:

1. Push any commit to the `main` branch, **or** go to:  
   **GitHub → Actions → Build & Deploy to GoDaddy → Run workflow**.
2. Watch the `build-and-deploy` job. It will:
   - Install Node.js dependencies
   - Build the Vite app (embedding `GEMINI_API_KEY`)
   - `rsync` the `dist/` folder to `DEPLOY_PATH` on the server via SSH
3. The Security Audit workflow also now runs end-to-end:  
   **GitHub → Actions → Security Audit → Run workflow**.

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| `Connection refused` on port 22 | SSH is still disabled in cPanel — repeat Step 1, wait 5 min |
| `Permission denied (publickey)` | Public key not authorized in cPanel — repeat Step 3 |
| `SECRET MISSING: SERVER_HOST` warning in Actions | Add the `SERVER_HOST` secret (Step 5) |
| `Integración CI/CD: Desactivado` | Enable CI/CD integration in GoDaddy hosting dashboard (Step 4) |
| SSH connects but audit fails | Run `security/ssh-hardening.sh --dry-run` on the server first |
| FTP deploy fails | Verify `FTP_HOST`, `FTP_USER`, `FTP_PASSWORD` secrets are correct |

---

## Security notes

- The private key in `PRIVATE_KEY` is used **only inside GitHub Actions** — it is never exposed in logs.
- Rotate the SSH key every 90 days: generate a new pair, update the secret, remove the old authorized key from GoDaddy.
- Once SSH is working, run `security/ssh-hardening.sh` on the server to lock down the SSH directory permissions.

---

## What is the Gemini API key?

### Why does the app need it?

The Pilot app uses **Google Gemini AI** (specifically the `gemini-2.5-flash` model) to automatically generate Python scripts. Every time a user clicks "Generate Python Script" inside the app, it calls Google's AI service. The `GEMINI_API_KEY` is the credential that identifies your account and authorises those AI requests.

The build step in GitHub Actions embeds the key into the compiled app files at build time (via Vite's `import.meta.env`). Without the key the build succeeds but the app will show an error at runtime whenever it tries to call the AI.

### Is it free?

Yes — Google offers a **free tier** for the Gemini API with a generous monthly quota, more than enough for personal or small-team use. No credit card is required; only a Google account (e.g., Gmail) is needed.

### How to get your key (takes ~2 minutes)

1. Open **[https://aistudio.google.com/app/apikey](https://aistudio.google.com/app/apikey)** in your browser.
2. Sign in with your Google account if prompted.
3. Click **"Create API key"** (or **"Crear clave de API"** if the page is in Spanish).
4. Select **"Create API key in new project"** — Google creates the project automatically.
5. Your new key appears on screen (it starts with `AIzaSy…`). **Copy it immediately** — you will not see the full value again after you navigate away.

### Where to put the key

| Context | Where to add it |
|---------|-----------------|
| **GitHub Actions (deploy)** | GitHub → Settings → Secrets and variables → Actions → **New repository secret** → name: `GEMINI_API_KEY`, value: paste the key |
| **Running the app locally** | Create a `.env.local` file in the project root and add the line `GEMINI_API_KEY=AIzaSy…` (this file is in `.gitignore` and is never committed) |

> **Security note:** Never paste your API key directly in the source code or commit it to Git.
> The `.env.local` file is excluded from version control by `.gitignore` automatically.
