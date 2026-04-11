#!/usr/bin/env python3
"""
Comprobación local DNS + HTTPS para gano.digital (sin credenciales).
Uso: python scripts/check_dns_https_gano.py
Exit 0 si resolución y GET HTTPS responden; 1 si falla resolución o TLS/HTTP severo.
"""
from __future__ import annotations

import socket
import ssl
import sys
import urllib.error
import urllib.request

HOSTS = ("gano.digital", "www.gano.digital")
USER_AGENT = "GanoDNSCheck/1.0 (+scripts/check_dns_https_gano.py)"


def resolve_ipv4(host: str) -> list[str]:
    try:
        infos = socket.getaddrinfo(host, None, socket.AF_INET, socket.SOCK_STREAM)
    except socket.gaierror as e:
        print(f"[DNS] {host}: ERROR {e}")
        return []
    ips = sorted({t[4][0] for t in infos})
    for ip in ips:
        print(f"[DNS] {host}: A -> {ip}")
    return ips


def check_https(host: str, timeout: float = 20.0) -> tuple[bool, str]:
    url = f"https://{host}/"
    ctx = ssl.create_default_context()
    req = urllib.request.Request(url, headers={"User-Agent": USER_AGENT})
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=timeout) as resp:
            code = resp.getcode()
            final = resp.geturl()
            return True, f"HTTP {code} final={final}"
    except urllib.error.HTTPError as e:
        return True, f"HTTP {e.code} (respuesta servidor; revisar redirección/contenido)"
    except Exception as e:
        return False, f"{type(e).__name__}: {e}"


def main() -> int:
    print("=== Gano Digital — check DNS / HTTPS ===\n")
    ok_dns = True
    for h in HOSTS:
        ips = resolve_ipv4(h)
        if not ips:
            ok_dns = False

    print()
    ok_https = True
    for h in HOSTS:
        ok, msg = check_https(h)
        tag = "OK " if ok else "FAIL"
        print(f"[HTTPS] {h}: {tag} {msg}")
        if not ok:
            ok_https = False

    print()
    if ok_dns and ok_https:
        print("Resumen: DNS resolvió y HTTPS respondió para al menos un intento por host probado.")
        return 0
    if not ok_dns:
        print("Resumen: falla resolución DNS en algún host — revisar registros A/CNAME y propagación.")
    if not ok_https:
        print("Resumen: falla HTTPS — revisar certificado, mixed content, o URL en hosting WordPress.")
    return 1


if __name__ == "__main__":
    sys.exit(main())
