#!/usr/bin/env python
"""
SSH CLI — credenciales solo por variables de entorno (nunca en el archivo).

Variables requeridas:
  GANO_SSH_HOST   — hostname o IP
  GANO_SSH_USER   — usuario SSH/cPanel
  GANO_SSH_PASS   — contraseña (o usa clave, ver abajo)

Opcionales:
  GANO_SSH_PORT   — default 22
  GANO_SSH_KEY_PATH — ruta a clave privada; si está definida, se usa en lugar de contraseña

Uso: python ssh_cli.py "comando remoto"
"""

import os
import sys

import paramiko


def _require(name: str) -> str:
    v = os.environ.get(name, "").strip()
    if not v:
        print(
            f"Falta la variable de entorno {name}. "
            "Copia ssh_cli.example.py como referencia y exporta las variables antes de ejecutar.",
            file=sys.stderr,
        )
        sys.exit(1)
    return v


def ssh_execute(command: str) -> str:
    host = _require("GANO_SSH_HOST")
    user = _require("GANO_SSH_USER")
    port = int(os.environ.get("GANO_SSH_PORT", "22"))
    key_path = os.environ.get("GANO_SSH_KEY_PATH", "").strip()

    try:
        client = paramiko.SSHClient()
        client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

        if key_path:
            client.connect(
                hostname=host,
                port=port,
                username=user,
                key_filename=key_path,
                timeout=15,
            )
        else:
            password = _require("GANO_SSH_PASS")
            client.connect(hostname=host, port=port, username=user, password=password, timeout=15)

        stdin, stdout, stderr = client.exec_command(command)
        output = stdout.read().decode()
        error = stderr.read().decode()
        client.close()

        if error and "tput:" in error and "TERM" in error:
            error = ""
        if error and output.strip():
            return output.rstrip() + "\n[stderr]\n" + error.rstrip()
        if error:
            return f"[ERROR]\n{error}"
        return output

    except Exception as e:
        return f"[CONEXION ERROR] {e}"


def main() -> None:
    if len(sys.argv) < 2:
        print("Uso: python ssh_cli.py 'comando remoto'")
        sys.exit(1)
    command = " ".join(sys.argv[1:])
    print(ssh_execute(command))


if __name__ == "__main__":
    main()
