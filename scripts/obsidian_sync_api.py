#!/usr/bin/env python3
"""
🌌 Gano Digital — Obsidian Local REST API Synchronizer
Mantiene la constelación sincronizada en tiempo real con Obsidian
"""

import requests
import json
import os
import sys
from datetime import datetime
from pathlib import Path
from typing import Dict, Any, Optional
from urllib.parse import urlparse

_REQUEST_TIMEOUT = 10  # segundos

class ObsidianSync:
    """Cliente para sincronizar archivos con Obsidian via Local REST API"""

    def __init__(self, api_key: str, port: int = 27124, use_https: bool = True):
        """
        Inicializar conexión con Obsidian Local REST API

        Args:
            api_key: Token de autorización de Obsidian Local REST API
            port: Puerto de la API (default 27124 para HTTPS)
            use_https: Usar HTTPS (default True)
        """
        self.api_key = api_key
        scheme = "https" if use_https else "http"
        self.base_url = f"{scheme}://localhost:{port}"
        self.headers = {
            "Authorization": f"Bearer {api_key}",
            "Content-Type": "application/json"
        }
        # Omitir verificación SSL solo para conexiones locales (cert auto-firmado).
        parsed = urlparse(self.base_url)
        local_hosts = {"localhost", "127.0.0.1", "::1"}
        if parsed.hostname not in local_hosts:
            raise ValueError(
                f"base_url apunta a host no-local ({parsed.hostname}). "
                "No se puede omitir verificación TLS."
            )
        self.verify_ssl = False

    def test_connection(self) -> bool:
        """Verificar conexión con Obsidian"""
        try:
            response = requests.get(
                f"{self.base_url}/vault/",
                headers=self.headers,
                verify=self.verify_ssl,
                timeout=_REQUEST_TIMEOUT,
            )
            return response.status_code == 200
        except requests.Timeout:
            print("❌ Timeout conectando a Obsidian")
            return False
        except Exception as e:
            print(f"❌ Error de conexión: {e}")
            return False

    def get_vault_info(self) -> Dict[str, Any]:
        """Obtener información del vault"""
        try:
            response = requests.get(
                f"{self.base_url}/vault/",
                headers=self.headers,
                verify=self.verify_ssl,
                timeout=_REQUEST_TIMEOUT,
            )
            response.raise_for_status()
            return response.json()
        except Exception as e:
            print(f"❌ Error obteniendo vault info: {e}")
            return {}

    def read_file(self, path: str) -> Optional[str]:
        """
        Leer archivo del vault

        Args:
            path: Ruta del archivo (ej: "memory/constellation/00-PROYECTO-CONSTELACION-INDICE.md")

        Returns:
            Contenido del archivo o None si falla
        """
        try:
            # Encodeamos el path para la URL
            encoded_path = path.replace(" ", "%20")
            response = requests.get(
                f"{self.base_url}/vault/abstract/file/{encoded_path}",
                headers=self.headers,
                verify=self.verify_ssl,
                timeout=_REQUEST_TIMEOUT,
            )
            response.raise_for_status()
            return response.text
        except Exception as e:
            print(f"❌ Error leyendo {path}: {e}")
            return None

    def write_file(self, path: str, content: str, overwrite: bool = True) -> bool:
        """
        Escribir/actualizar archivo en el vault

        Args:
            path: Ruta del archivo
            content: Contenido a escribir
            overwrite: Sobrescribir si existe

        Returns:
            True si fue exitoso
        """
        try:
            # Encodeamos el path
            encoded_path = path.replace(" ", "%20")

            payload = {
                "path": path,
                "content": content,
                "overwrite": overwrite
            }

            response = requests.post(
                f"{self.base_url}/vault/create",
                headers=self.headers,
                json=payload,
                verify=self.verify_ssl,
                timeout=_REQUEST_TIMEOUT,
            )

            if response.status_code in [200, 201]:
                print(f"✅ Archivo escrito: {path}")
                return True
            else:
                print(f"⚠️ Status {response.status_code}: {response.text}")
                return False

        except Exception as e:
            print(f"❌ Error escribiendo {path}: {e}")
            return False

    def update_file(self, path: str, content: str) -> bool:
        """Actualizar archivo existente"""
        return self.write_file(path, content, overwrite=True)

    def list_files(self, folder: str = "") -> list:
        """Listar archivos en una carpeta"""
        try:
            response = requests.get(
                f"{self.base_url}/vault/",
                headers=self.headers,
                verify=self.verify_ssl,
                timeout=_REQUEST_TIMEOUT,
            )
            response.raise_for_status()
            return response.json().get("files", [])
        except Exception as e:
            print(f"❌ Error listando archivos: {e}")
            return []

    def sync_metric_update(self, metric_name: str, value: Any, timestamp: bool = True) -> bool:
        """
        Sincronizar actualización de métrica

        Args:
            metric_name: Nombre de la métrica (ej: "PFID_Progress")
            value: Nuevo valor
            timestamp: Agregar timestamp de actualización

        Returns:
            True si fue exitoso
        """
        metrics_file = "memory/constellation/06-METRICAS-PROGRESO.md"
        content = self.read_file(metrics_file)

        if not content:
            print(f"❌ No se pudo leer {metrics_file}")
            return False

        # Buscar y reemplazar la métrica
        # Formato esperado: | **Métrica** | Valor |
        import re

        timestamp_str = f" (actualizado {datetime.now().strftime('%Y-%m-%d %H:%M')})" if timestamp else ""

        # Ejemplo de patrón (adaptable según estructura)
        pattern = rf"(\| \*\*{metric_name}\*\* \| )([^|]*?)(\|)"
        replacement = rf"\1{value}{timestamp_str}\3"

        new_content = re.sub(pattern, replacement, content)

        if new_content != content:
            return self.update_file(metrics_file, new_content)
        else:
            print(f"⚠️ Métrica {metric_name} no encontrada o no cambió")
            return False

    def create_status_report(self) -> bool:
        """Crear reporte de estado diario"""
        report_content = f"""# 📊 Reporte de Estado — {datetime.now().strftime('%Y-%m-%d %H:%M')}

## Síntesis Operativa

**Fase 4 Progress**: En ejecución
**Última sincronización**: {datetime.now().isoformat()}
**Sistema**: ✅ Online (Obsidian Local REST API conectado)

## Métricas Clave (Snapshot)
- PFID Mappings: 0/5
- Test Pass Rate: Pending
- Blockers Activos: 1
- Go-Live Target: Apr 20, 2026

## Próximas Acciones
1. Diego activa Antigravity (Apr 7)
2. RCC Audit ejecutado (Apr 8)
3. PFID mappings iniciados (Apr 9)

---
*Sincronizado via Obsidian Local REST API*
"""

        return self.write_file(
            f"memory/constellation/STATUS-REPORTES/reporte-{datetime.now().strftime('%Y-%m-%d')}.md",
            report_content
        )


def main():
    """Script principal para pruebas"""

    api_key = os.environ.get("OBSIDIAN_API_KEY")
    if not api_key:
        print("❌ Variable de entorno OBSIDIAN_API_KEY no definida.")
        sys.exit(1)

    # Inicializar cliente
    sync = ObsidianSync(api_key)

    print("🔍 Verificando conexión con Obsidian...")
    if not sync.test_connection():
        print("❌ No se pudo conectar a Obsidian Local REST API")
        return

    print("✅ Conectado a Obsidian")

    # Obtener info del vault
    print("\n📂 Información del vault:")
    vault_info = sync.get_vault_info()
    print(json.dumps(vault_info, indent=2))

    # Listar archivos de la constelación
    print("\n📚 Archivos de constelación:")
    files = sync.list_files()
    for f in files:
        print(f"  - {f}")

    # Crear reporte de prueba
    print("\n📝 Creando reporte de prueba...")
    sync.create_status_report()

    print("\n✅ Sincronización completada")


if __name__ == "__main__":
    main()
