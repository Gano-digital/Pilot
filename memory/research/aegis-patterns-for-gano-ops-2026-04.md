# Patrones inspirados en AEGIS (sin integración de código)

**Referencia:** [Ouroboros1984/AEGIS](https://github.com/Ouroboros1984/AEGIS) — plataforma open source AGPL-3.0 (detección, SOAR, honeypots, dashboard FastAPI/Next.js).  
**Este documento:** qué ideas **reutilizamos conceptualmente** en **Gano Digital** sin copiar ni embeber el proyecto (licencia distinta, alcance distinto: vitrina WordPress + Reseller, no SIEM enterprise).

---

## Qué **no** hacemos aquí

- No clonamos AEGIS dentro de `Pilot` como submódulo de producción.
- No mezclamos AGPL con el stack del sitio público sin revisión legal.
- No prometemos “reemplazar Wazuh” en el roadmap actual de Gano.

---

## Patrones útiles para nuestro **Ops Hub** y procesos

| Idea AEGIS (descripción pública README) | Adaptación Gano |
|----------------------------------------|-----------------|
| **Pipeline por capas** (rápido → correlación → análisis profundo) | Triage de trabajo: `TASKS.md` (macro) → issues GitHub → cola `dispatch-queue.json` → ejecución agente. |
| **Guardrails** (auto / requiere aprobación / nunca auto) | Reglas humanas: deploy y `force_remove` wp-file-manager solo con decisión; PRs protegidos; Copilot no mergea sin revisión. |
| **Audit trail** (decisión + modelo + coste) | Campo `audit_log` reservado en `progress.json`; ampliar con entradas `{t, actor, action}` desde workflows o scripts cuando se automatice. |
| **Dashboard en tiempo casi real** | WebSocket en AEGIS; nosotros **polling** 2 min + regeneración en CI al cambiar fuentes. |
| **Módulos Surface / Response / Phantom** | Analogía interna: **Progreso** (TASKS) / **Acciones** (Actions) / **Riesgo** (Dependabot, issue #29 servidor). |
| **Playbooks deterministas** | Nuestros workflows numerados (04, 05, 08, 12…) + README de workflows como “playbook”. |
| **Honeypot / deception** | No aplicable al MVP del Ops Hub; Wordfence + hardening ya en fases 1–2. |

---

## Si en el futuro se quisiera **integración técnica**

Evaluar en orden:

1. **Instancia AEGIS aparte** (Docker en VPS o homelab) solo para infra de soporte, no en el mismo contenedor que WordPress.
2. **Webhooks** desde AEGIS hacia issues o Slack — fuera del repo `Pilot`.
3. **API interna** con autenticación — nunca claves en el Ops Hub estático.

---

## Fuentes

- [AEGIS — README / arquitectura](https://github.com/Ouroboros1984/AEGIS) (fork de diego1128256-cmd/AEGIS).
