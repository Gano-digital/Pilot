# Uso del script `check_dns_https_gano.py`

**Script:** [`scripts/check_dns_https_gano.py`](../../scripts/check_dns_https_gano.py)  
**Propósito:** comprobar desde tu PC (sin credenciales) que `gano.digital` y `www.gano.digital` resuelven DNS y responden por HTTPS.

## Ejecución

```bash
python scripts/check_dns_https_gano.py
```

- **Exit 0:** resolución IPv4 y GET HTTPS OK para los hosts configurados en el script.
- **Exit 1:** fallo de DNS, TLS o HTTP severo.

## Qué interpretar

- Salida `[DNS]` lista registros A resueltos.
- Salida HTTPS indica código HTTP y URL final (redirecciones).
- No sustituye el panel de GoDaddy ni el hosting: solo valida el estado **visto desde el cliente** en el momento de la ejecución.

## Relación con otros documentos

- Runbook paso a paso: [`dns-https-godaddy-runbook-2026.md`](dns-https-godaddy-runbook-2026.md)
- Plantilla de registros esperados: [`dns-expected-records-template-2026.md`](dns-expected-records-template-2026.md)
