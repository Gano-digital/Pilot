# How to Enable SSH Access on GoDaddy Shared Hosting

> **Why this guide?**  
> The automated security audit workflow requires SSH access to the GoDaddy server.  
> By default GoDaddy **disables SSH**. You must enable it via cPanel and store the  
> connection details as GitHub Secrets before the `server-audit` job can run.

---

## What you have (from your hosting dashboard)

| Setting | Value |
|---------|-------|
| **SSH/SFTP hostname** | `kfw.f71.myftpupload.com` |
| **Server IP** | `160.153.0.23` |
| **WordPress version** | 6.9.1 |
| **PHP version** | 8.3 |
| **CI/CD integration** | ⚠️ Disabled — see Step 4 |

---

## Step 1 — Enable SSH in GoDaddy cPanel

1. Log in to **[my.godaddy.com](https://my.godaddy.com)**.
2. Go to **My Products → Web Hosting → Manage**.
3. In cPanel, search for **"SSH Access"** (or find it under *Security*).
4. Click **Enable SSH Access** if it is shown as disabled.
5. Wait 1-2 minutes for the change to take effect.

> **Note:** SSH may not be available on all GoDaddy shared hosting plans.  
> If you don't see the SSH Access option, contact GoDaddy support and ask  
> them to enable SSH/Terminal access for your account.

---

## Step 2 — Generate an SSH key pair

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

## Step 3 — Upload the public key to GoDaddy

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

## Step 4 — Enable CI/CD Integration in GoDaddy

Your hosting dashboard shows **"Integración CI/CD: Desactivado"**.

1. In GoDaddy cPanel → **Hosting Configuration** (the same screen from your screenshot).
2. Find **Integración CI/CD** → click **Habilitar** (Enable).
3. This allows GitHub Actions to connect to the server via the SFTP/SSH endpoint.

---

## Step 5 — Add GitHub Secrets

Go to your GitHub repository → **Settings → Secrets and variables → Actions → New repository secret**.

Add these three secrets:

| Secret name | Value |
|-------------|-------|
| `SERVER_HOST` | `kfw.f71.myftpupload.com` |
| `SERVER_USER` | your GoDaddy cPanel username |
| `PRIVATE_KEY` | full contents of `~/.ssh/godaddy_deploy` (the **private** key) |

For FTP-based deployment, also add:

| Secret name | Value |
|-------------|-------|
| `FTP_HOST` | `kfw.f71.myftpupload.com` |
| `FTP_USER` | your GoDaddy cPanel / FTP username |
| `FTP_PASSWORD` | your GoDaddy cPanel / FTP password |
| `FTP_REMOTE_DIR` | `/` or `/public_html/` (the web root on the server) |

---

## Step 6 — Test the SSH connection locally

```bash
ssh -i ~/.ssh/godaddy_deploy \
    -o StrictHostKeyChecking=accept-new \
    YOUR_CPANEL_USER@kfw.f71.myftpupload.com
```

You should see a shell prompt. Type `exit` to disconnect.

---

## Step 7 — Re-run the security audit workflow

1. Go to **GitHub → Actions → Security Audit**.
2. Click **Run workflow** → **Run workflow**.
3. The `server-audit` job's first step (`Check SSH prerequisites and probe connectivity`) will now pass and all audit steps will execute.

---

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
