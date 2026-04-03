# Plantilla: Registros DNS esperados — gano.digital (2026)

> **⚠️ INSTRUCCIÓN DE SEGURIDAD**
> Esta plantilla vive en el repositorio con **placeholders** (`[...]`).
> Diego debe copiar los valores reales desde el panel de GoDaddy **solo en una copia local**
> o en documentación interna (no compartida públicamente).
> **Nunca** pegar IPs, tokens de verificación ni datos de panel en issues, PRs ni commits.

---

## Cómo usar esta plantilla

1. Abrir el panel DNS de GoDaddy (Mis productos → Dominios → gano.digital → Administrar DNS).
2. Copiar esta plantilla a un documento local o a Notion/Obsidian privado.
3. Reemplazar cada `[PLACEHOLDER]` con el valor real del panel.
4. Verificar propagación con: `python scripts/check_dns_https_gano.py`

Ver también: [`memory/ops/dns-https-godaddy-runbook-2026.md`](dns-https-godaddy-runbook-2026.md)

---

## Tabla 1 — Apex (`@`) — dominio raíz `gano.digital`

| Tipo  | Nombre | Valor                          | TTL     | Notas                                                                 |
|-------|--------|-------------------------------|---------|-----------------------------------------------------------------------|
| A     | @      | `[IP_HOSTING_GODADDY]`        | 600 s   | IP del servidor Managed WordPress (ej. 160.153.x.x). Rellenar offline. |
| AAAA  | @      | `[IPv6_HOSTING_GODADDY]`      | 600 s   | Solo si GoDaddy asigna IPv6 al plan Managed WP. Omitir si no aplica. |
| TXT   | @      | `[TOKEN_VERIFICACION_GOOGLE]` | 3600 s  | Google Search Console / dominio GoDaddy. Rellenar con token real offline. |
| TXT   | @      | `v=spf1 include:secureserver.net ~all` | 3600 s | SPF básico para correo GoDaddy. Ajustar si se usa otro proveedor de correo. |
| MX    | @      | `[MX_PRIORIDAD] [MX_SERVIDOR_GODADDY]` | 3600 s | Registros MX del correo GoDaddy. Rellenar con valores del panel. |

> **Nota apex vs www:** En Managed WordPress de GoDaddy se recomienda apuntar `@` con registro **A**
> a la IP del hosting. Si GoDaddy usa DNS proxy/CDN, el valor puede ser diferente al expuesto en el panel.

---

## Tabla 2 — Subdominio `www` — `www.gano.digital`

| Tipo  | Nombre | Valor                    | TTL    | Notas                                                                        |
|-------|--------|--------------------------|--------|------------------------------------------------------------------------------|
| CNAME | www    | `@`                      | 3600 s | Redirige www al apex. Opción estándar si GoDaddy lo permite para Managed WP. |
| A     | www    | `[IP_HOSTING_GODADDY]`   | 600 s  | **Alternativa** si CNAME a @ no está disponible: misma IP que el apex.       |

> **Nota CNAME vs A para www:** Algunos paneles GoDaddy no permiten CNAME cuando el apex tiene registros A/MX;
> en ese caso usar registro A con la misma IP. Verificar en el panel cuál aplica.

---

## Tabla 3 — Subdominio adicional `my.gano.digital` (futuro)

| Tipo  | Nombre | Valor                  | TTL    | Notas                                                  |
|-------|--------|------------------------|--------|--------------------------------------------------------|
| A     | my     | `[IP_SUBDOMINIO]`      | 600 s  | Portal cliente. Pendiente de activar (Fase 4+).        |
| CNAME | support| `[CNAME_SOPORTE]`      | 3600 s | Subdominio de soporte (FreeScout u otro). Planificado. |

---

## Verificación rápida

```bash
# DNS apex
dig A gano.digital +short

# DNS www
dig CNAME www.gano.digital +short
dig A www.gano.digital +short

# HTTPS (apex y www)
python scripts/check_dns_https_gano.py
```

---

## Referencias

- Runbook completo: [`memory/ops/dns-https-godaddy-runbook-2026.md`](dns-https-godaddy-runbook-2026.md)
- Script de verificación: [`scripts/check_dns_https_gano.py`](../../scripts/check_dns_https_gano.py)
- Checklist HTTPS WordPress: [`memory/ops/https-wordpress-managed-checklist-2026.md`](https-wordpress-managed-checklist-2026.md)
- Panel DNS GoDaddy: <https://dcc.godaddy.com/manage/dns> (requiere login)
- Documentación pública GoDaddy: <https://www.godaddy.com/help/manage-dns-records-680>
