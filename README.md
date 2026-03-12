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
   | `GEMINI_API_KEY` | your [Google Gemini API key](https://aistudio.google.com/app/apikey) |
   | `DEPLOY_PATH` | path on server, e.g. `~/public_html/pilot` |

3. See `security/godaddy-ssh-setup.md` for the full step-by-step guide.

### Manual deploy

You can also trigger a deploy manually from  
**GitHub → Actions → Build & Deploy to GoDaddy → Run workflow**.
