# Run and deploy your AI Studio app

This contains everything you need to run your app locally.

## Run Locally

**Prerequisites:**  Node.js


1. Install dependencies:
   `npm install`
2. Copy `.env.example` to `.env.local` and fill in your keys:
   `cp .env.example .env.local`
3. Set at minimum `GEMINI_API_KEY` in `.env.local`
4. Run the app:
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

---

## GoDaddy Reseller API Integration

The repository includes a TypeScript service module (`services/godaddyService.ts`)
and a dedicated GitHub Actions workflow (`.github/workflows/godaddy-api.yml`)
for automating common domain and DNS tasks via the
[GoDaddy Reseller API](https://developer.godaddy.com).

### Setting up API credentials

1. Log in to the [GoDaddy Developer Portal](https://developer.godaddy.com).
2. Go to **API Keys → Create New API Key**.
3. The portal displays a combined key in the format `KEY_SECRET`.  
   Split it at the underscore and store each half as a separate GitHub secret:

   | Secret | Value |
   |--------|-------|
   | `GODADDY_API_KEY` | segment **before** the underscore (e.g. `3mM44YwfLcmKrY`) |
   | `GODADDY_API_SECRET` | segment **after** the underscore (e.g. `Kpamb4Uk1KT1RgFSaMHgPA`) |
   | `GODADDY_API_ENV` | `OTE` while testing · `PRODUCTION` for live credentials |
   | `GODADDY_DOMAIN` | your domain, e.g. `gano.digital` (optional, used in deploy verify step) |

   > **OTE vs PRODUCTION** – OTE is the GoDaddy sandbox environment. Use it
   > for all development and testing. Switch to `PRODUCTION` only with live
   > credentials obtained from the Developer Portal under a production environment.

### Available operations (manual workflow)

Trigger **GitHub → Actions → GoDaddy Domain & DNS Operations → Run workflow**
and choose one of:

| Operation | Description |
|-----------|-------------|
| `list-domains` | Show all domains in the account with status and expiry |
| `check-domain` | Check whether a domain name is available for registration |
| `list-dns` | List all DNS records for a domain |
| `update-a-record` | Point the root `A` record to a new IP address |

### Post-deploy DNS verification

When `GODADDY_API_KEY` and `GODADDY_API_SECRET` are configured, the
**Build & Deploy** workflow automatically queries the GoDaddy API after each
deployment to confirm the domain status and root `A` record.

### Using the service in code (server-side only)

```typescript
import {
  listDomains,
  checkDomainAvailability,
  getDnsRecords,
  replaceDnsRecords,
} from './services/godaddyService';

// List all active domains
const domains = await listDomains(['ACTIVE']);

// Check availability
const result = await checkDomainAvailability('example.com');
console.log(result.available, result.price, result.currency);

// Get A records for gano.digital
const aRecords = await getDnsRecords('gano.digital', 'A');

// Update the root A record
await replaceDnsRecords('gano.digital', 'A', '@', [
  { type: 'A', name: '@', data: '160.153.0.23', ttl: 600 },
]);
```

> ⚠️ **Security note** – `godaddyService.ts` reads credentials from
> `process.env`. It is intended for Node.js / CI-CD contexts only.
> Do **not** import it into browser-bundled code (e.g. React components),
> as that would expose your API credentials in the public JS bundle.

