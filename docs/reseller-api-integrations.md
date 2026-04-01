# GoDaddy Reseller API — Integration & Development Opportunities

Built on the foundation of the [GoDaddy Reseller API integration](https://github.com/Gano-digital/Pilot/pull/6) already in this repository, the following areas represent concrete features, automations, and products that can be developed next.

---

## 1. Domain Portfolio Dashboard

A web UI (React, this same app) that connects to the GoDaddy Reseller API to display and manage all registered domains in one place.

**Capabilities:**
- List all domains with status, expiry date, and auto-renewal flag
- Filter and sort by TLD, status, or expiry date
- Trigger manual renewal or toggle auto-renewal from the dashboard
- Export the portfolio to CSV for reporting

**API endpoints already wired:** `GET /v1/domains?statuses=ACTIVE`

---

## 2. Automated DNS Provisioning on Deploy

Extend the existing CI/CD pipeline to automatically configure DNS records every time a new environment is deployed — eliminating manual DNS edits.

**Capabilities:**
- Create or update A / CNAME records pointing to the deployed server IP after each push
- Environment profiles: production points to `160.153.0.23`, staging to a separate IP
- Rollback support: revert DNS to previous values if post-deploy health check fails
- Subdomain creation per feature branch (e.g., `feature-auth.gano.digital`)

**API endpoints:** `PUT /v1/domains/{domain}/records/A/@`, `POST /v1/domains/{domain}/records`

---

## 3. Blue-Green / Canary Deployments via DNS

Use DNS-based traffic routing to switch between two identical production environments with zero downtime.

**Capabilities:**
- Maintain two server instances (blue/green); flip the A record to switch traffic
- Gradual rollout: update a weighted CNAME (where supported) to route a percentage of traffic to the new version
- Instant rollback by re-pointing the A record to the previous server

**API endpoints:** `PUT /v1/domains/{domain}/records/A/@`

---

## 4. Domain Availability Checker & Registration Tool

Extend the existing `check-domain` workflow into a full interactive search and purchase tool.

**Capabilities:**
- Real-time bulk availability check across multiple TLDs (`.com`, `.digital`, `.mx`, etc.)
- Price comparison across TLDs
- TLD suggestion engine: given a keyword, propose available domain variants
- One-click registration of an available domain directly from the UI

**API endpoints:** `GET /v1/domains/available`, `POST /v1/domains/purchase`

---

## 5. Email Infrastructure Automation

Auto-provision the DNS records required to operate professional email (MX, SPF, DKIM, DMARC) whenever a new domain or environment is set up.

**Capabilities:**
- Templates for popular email providers: Google Workspace, Zoho Mail, Microsoft 365
- Apply a provider template to a domain in a single workflow dispatch
- Verify all required records are live after provisioning
- SPF/DMARC report parsing and alerting

**API endpoints:** `POST /v1/domains/{domain}/records` (MX, TXT)

---

## 6. Multi-Tenant Client Domain Management (Reseller Use Case)

Build a white-label portal where Gano Digital clients can manage their own domains without accessing GoDaddy directly.

**Capabilities:**
- Client sign-up flow that registers a domain on their behalf via the Reseller API
- Per-client DNS management panel (add/edit/delete records)
- Automated invoicing integration (Stripe / Conekta) triggered on domain registration or renewal
- Domain transfer workflow: accept transfer codes and track progress

**API endpoints:** `POST /v1/domains/purchase`, `GET /v1/domains/{domain}`, DNS record CRUD

---

## 7. Domain Expiry Monitoring & Alerts

A GitHub Actions scheduled workflow (or standalone Node.js service) that monitors all domains for upcoming expiry and sends notifications.

**Capabilities:**
- Daily cron job that queries all active domains and flags those expiring within 30 / 7 / 1 days
- Slack / email alerts with direct renewal links
- Auto-renew domains below a configurable expiry threshold
- Dashboard widget showing upcoming renewals in priority order

**API endpoints:** `GET /v1/domains?statuses=ACTIVE`, `POST /v1/domains/{domain}/renew`

---

## 8. DNS Change Auditing & Security Monitoring

Track every DNS record modification to detect unauthorized changes or misconfigurations.

**Capabilities:**
- Snapshot DNS state to a file or database after each successful deploy
- Diff snapshots to detect unexpected record changes between runs
- Alert on changes not triggered by the CI/CD pipeline
- DNSSEC status checking

**API endpoints:** `GET /v1/domains/{domain}/records`

---

## 9. Python Script Generator Extension (ties back to core app)

Extend the existing Gemini AI script generator to produce domain-management automation scripts powered by the GoDaddy API.

**Capabilities:**
- New prompt template: "Generate a Python script that checks domain availability and registers the cheapest available TLD"
- Prompt option: "Generate a Python script that syncs DNS records from a YAML config file to GoDaddy"
- Prompt option: "Generate a Python script that audits all domains for expiry and emails a report"
- Generated scripts use the `requests` library with the same credential pattern already established in `services/godaddyService.ts`

---

## 10. WordPress / GoDaddy Hosting Integration

Combine the existing WP hosting setup with the Reseller API for fully automated site provisioning.

**Capabilities:**
- When a new WordPress multisite sub-site is created, automatically create the corresponding subdomain DNS record
- Auto-configure DNS for WP plugins that require custom records (SendGrid, Mailchimp, Yoast)
- Provision a new GoDaddy sub-account + domain for each client website
- Post-migration DNS cutover script that updates A + MX records atomically

**API endpoints:** Full DNS CRUD + `POST /v1/domains/purchase`

---

## Summary Table

| # | Feature | Complexity | API Endpoints Used | Value |
|---|---------|------------|-------------------|-------|
| 1 | Domain Portfolio Dashboard | Medium | `GET /v1/domains` | High |
| 2 | Automated DNS on Deploy | Low | `PUT /v1/domains/{d}/records/A/@` | High |
| 3 | Blue-Green Deployments via DNS | Medium | `PUT /v1/domains/{d}/records/A/@` | High |
| 4 | Domain Availability & Registration | Medium | `GET /v1/domains/available`, `POST /v1/domains/purchase` | Medium |
| 5 | Email Infrastructure Automation | Low | `POST /v1/domains/{d}/records` (MX, TXT) | High |
| 6 | Multi-Tenant Client Portal | High | Full CRUD + purchase | High |
| 7 | Expiry Monitoring & Alerts | Low | `GET /v1/domains`, `POST /v1/domains/{d}/renew` | Medium |
| 8 | DNS Change Auditing | Low | `GET /v1/domains/{d}/records` | Medium |
| 9 | Python Script Generator Extension | Low | N/A (prompts only) | Medium |
| 10 | WordPress / Hosting Integration | High | Full CRUD + purchase | High |

Items **2, 5, 7, and 8** have the best value-to-complexity ratio and can be built directly on top of the existing workflows already in this repository.
