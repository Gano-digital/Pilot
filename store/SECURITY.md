# Security Policy

## Supported Versions

| Version | Supported |
| ------- | --------- |
| latest (master) | :white_check_mark: |

## Reporting a Vulnerability

Please **do not** open a public GitHub issue for security vulnerabilities.

Report vulnerabilities by email to: **pymes@gano.digital**

Include:
- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (optional)

You will receive a response within **72 hours**.
We aim to patch confirmed vulnerabilities within **14 days**.

## Scope

In scope:
- Express API routes (/api/*)
- SSR rendering pipeline
- Domain search proxy
- AI chat endpoint
- Authentication / session handling (if added)

Out of scope:
- GoDaddy reseller infrastructure (report to GoDaddy directly)
- Denial-of-service via resource exhaustion (already rate-limited)
- Social engineering