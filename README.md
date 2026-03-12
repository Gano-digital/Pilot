# Run and deploy your AI Studio app

This contains everything you need to run your app locally.

## Run Locally

**Prerequisites:**  Node.js


1. Install dependencies:
   `npm install`
2. Set the `GEMINI_API_KEY` in [.env.local](.env.local) to your Gemini API key
3. Run the app:
   `npm run dev`

## Deploy to GoDaddy (via GitHub Actions)

Every push to `main` automatically builds and deploys the app to your GoDaddy
server using the **Build & Deploy** workflow (`.github/workflows/deploy.yml`).

### One-time setup

1. **Enable CI/CD Integration** in the GoDaddy hosting dashboard:  
   Hosting Configuration → **Integración CI/CD** → **Habilitar**

2. **Add GitHub Secrets** (Settings → Secrets and variables → Actions):

   | Secret | Value |
   |--------|-------|
   | `SERVER_HOST` | `kfw.f71.myftpupload.com` |
   | `SERVER_USER` | your GoDaddy cPanel username |
   | `PRIVATE_KEY` | SSH private key authorized on the server |
   | `GEMINI_API_KEY` | your Google Gemini API key — see **[What is the Gemini API key?](#what-is-the-gemini-api-key)** below |
   | `DEPLOY_PATH` | path on server, e.g. `~/public_html/pilot` |

3. See `security/godaddy-ssh-setup.md` for the full step-by-step guide.

### Manual deploy

You can also trigger a deploy manually from  
**GitHub → Actions → Build & Deploy to GoDaddy → Run workflow**.

---

## What is the Gemini API key?

### Why does this app need it?

The Pilot app uses **Google Gemini AI** (specifically the `gemini-2.5-flash` model) to automatically generate Python scripts based on the built-in prompt. Every time you click the "Generate Python Script" button in the app, it sends a request to Google's AI service. The `GEMINI_API_KEY` is the authentication token that identifies your account and authorises those requests.

Without this key the app will display an error and no code will be generated.

### Is it free?

Yes — Google provides a **free tier** for Gemini API that is more than enough for personal or small-team use. You only need a Google account (Gmail is fine).

### How to get your key (takes ~2 minutes)

1. Open **[https://aistudio.google.com/app/apikey](https://aistudio.google.com/app/apikey)** in your browser.
2. Sign in with your Google account if prompted.
3. Click **"Create API key"** (or **"Crear clave de API"** if the page is in Spanish).
4. Select **"Create API key in new project"** — Google creates a project automatically.
5. Your new key appears on screen (it looks like `AIzaSy…`). **Copy it now** — you will not see it again in full.

### Where to put the key

| Use case | Where to add it |
|----------|-----------------|
| **Running locally** | Create a `.env.local` file in the project root and add the line `GEMINI_API_KEY=AIzaSy…` |
| **GitHub Actions / automatic deploy** | GitHub → Settings → Secrets and variables → Actions → **New repository secret** — name it `GEMINI_API_KEY`, paste the value |

> **Security note:** Never paste your API key directly into the source code or commit it to Git. The `.env.local` file is already listed in `.gitignore` so it is never uploaded.
