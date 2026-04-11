#!/usr/bin/env python3
"""Imprime recordatorio de cierre de sesión (sin red ni borrado). Ver memory/ops/security-end-session-checklist.md"""
from __future__ import annotations

LINES = [
    "Gano Digital — recordatorio de seguridad (fin de sesión)",
    "",
    "1. Cerrá SSH / wp-cli / rsync a producción si seguían abiertos.",
    "2. git status: sin .env, .pem ni claves en staging accidental.",
    "3. git remote -v: sin https://TOKEN@github.com/...",
    "4. Portapapeles: si copiaste un token, pegá texto neutro encima.",
    "5. Bloqueá la pantalla (Win+L) si te vas del puesto.",
    "6. PC compartida: valorá gh auth logout o cerrar sesión en el navegador.",
    "",
    "Detalle: memory/ops/security-end-session-checklist.md",
]


def main() -> int:
    print("\n".join(LINES))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
