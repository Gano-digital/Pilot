#!/usr/bin/env python3
"""
🚀 Gano Digital — Live Monitor para Obsidian
Actualiza métricas y progreso en tiempo real
"""

import json
from datetime import datetime
from obsidian_sync_api import ObsidianSync

class GanoDigitalMonitor:
    """Monitor en tiempo real de Phase 4"""

    def __init__(self, api_key: str):
        self.sync = ObsidianSync(api_key)
        self.metrics = {
            "PFID_Progress": "0/5",
            "Test_Pass_Rate": "N/A",
            "Blockers": "1 🔴",
            "Phase4_Completion": "15%",
            "Production_Ready": "0%",
            "Go_Live_Target": "Apr 20, 2026"
        }

    def update_constellation_index(self):
        """Actualizar índice principal con métricas frescas"""
        index_file = "memory/constellation/00-PROYECTO-CONSTELACION-INDICE.md"
        content = self.sync.read_file(index_file)

        if not content:
            return False

        # Actualizar snapshot de métricas
        import re

        updates = {
            r"(\| \*\*Fase 4 completion\*\* \| )([^|]*?)(\|)": rf"\1{self.metrics['Phase4_Completion']}\3",
            r"(\| \*\*PFID mappings\*\* \| )([^|]*?)(\|)": rf"\1{self.metrics['PFID_Progress']}\3",
            r"(\| \*\*Blockers activos\*\* \| )([^|]*?)(\|)": rf"\1{self.metrics['Blockers']}\3",
        }

        new_content = content
        for pattern, replacement in updates.items():
            new_content = re.sub(pattern, replacement, new_content)

        # Agregar timestamp de actualización
        new_content = re.sub(
            r"(\*\*Última actualización\*\*:) [^\n]*",
            rf"\1 {datetime.now().strftime('%Y-%m-%d %H:%M')} UTC",
            new_content
        )

        return self.sync.update_file(index_file, new_content)

    def update_metrics_dashboard(self):
        """Actualizar dashboard de métricas"""
        metrics_file = "memory/constellation/06-METRICAS-PROGRESO.md"
        content = self.sync.read_file(metrics_file)

        if not content:
            return False

        import re

        # Actualizar timestamp
        new_content = re.sub(
            r"(\*\*Última actualización\*\*:) [^\n]*",
            rf"\1 {datetime.now().strftime('%Y-%m-%d %H:%M')} UTC",
            content
        )

        return self.sync.update_file(metrics_file, new_content)

    def log_daily_standup(self, agent_name: str, did: str, doing: str, blockers: str = "None", next_task: str = "TBD"):
        """Registrar standup diario de agente"""
        date_str = datetime.now().strftime('%Y-%m-%d')
        standup_path = f"memory/constellation/STANDUPS/{date_str}-{agent_name}.md"

        standup_content = f"""# Standup — {agent_name} — {date_str}

## Qué hice
{did}

## Qué estoy haciendo
{doing}

## Bloqueadores
{blockers}

## Próxima tarea
{next_task}

---
*Registrado: {datetime.now().isoformat()}*
"""

        return self.sync.write_file(standup_path, standup_content)

    def update_progress_tracker(self, phase: str, completion_pct: int, notes: str = ""):
        """Actualizar progreso de fase"""
        tracker_file = "memory/constellation/PROGRESS-TRACKER.md"

        # Leer o crear
        content = self.sync.read_file(tracker_file)
        if not content:
            content = "# 📈 Progress Tracker — Fase 4\n\n"

        # Agregar entrada
        entry = f"""## {datetime.now().strftime('%Y-%m-%d %H:%M')} — {phase}
**Completion**: {completion_pct}%
**Notes**: {notes}

"""

        new_content = content + entry

        return self.sync.update_file(tracker_file, new_content)

    def sync_risk_update(self, blocker_id: str, status: str, resolution_eta: str = ""):
        """Sincronizar actualización de riesgo/blocker"""
        risks_file = "memory/constellation/08-DEPENDENCIAS-RIESGOS.md"
        content = self.sync.read_file(risks_file)

        if not content:
            return False

        import re

        # Actualizar estado del blocker
        pattern = rf"(\*\*Estado\*\*: )([^\n]*?)(\n)"
        # Esto es un ejemplo simple; idealmente querrías parser más robusto

        timestamp = datetime.now().strftime('%Y-%m-%d %H:%M')
        update_note = f"\n**Última actualización**: {timestamp} — Status: {status}"

        if resolution_eta:
            update_note += f" | ETA: {resolution_eta}"

        # Agregar al final del blocker
        pattern = rf"({blocker_id}:[^#]*?)(---)"
        new_content = re.sub(pattern, rf"\1\n{update_note}\n\n\2", content)

        return self.sync.update_file(risks_file, new_content)

    def create_weekly_summary(self):
        """Crear resumen semanal"""
        week_num = datetime.now().isocalendar()[1]
        year = datetime.now().year

        summary_content = f"""# Resumen Semanal — Semana {week_num}, {year}

## Progreso
- [ ] Logros de esta semana (3-5 puntos)
- [ ] Hitos alcanzados
- [ ] Tareas vencidas

## Métricas
- PFID progress: {self.metrics['PFID_Progress']}
- Test pass rate: {self.metrics['Test_Pass_Rate']}
- Blocker count: {self.metrics['Blockers']}
- Cycle time average: TBD

## Bloqueadores Resueltos
- TBD

## Nuevos Riesgos
- TBD

## Prioridades Próxima Semana
- [ ] Tarea 1
- [ ] Tarea 2
- [ ] Tarea 3

## Decisiones Tomadas
- TBD

---
*Generado: {datetime.now().isoformat()}*
*Mantenedor: Claude + Diego*
"""

        return self.sync.write_file(
            f"memory/constellation/WEEKLY-SUMMARIES/semana-{week_num}-{year}.md",
            summary_content
        )

    def test_all_features(self):
        """Probar todas las funciones de sincronización"""
        print("🚀 Testing Obsidian Live Monitor...\n")

        # Test 1: Conexión
        print("1️⃣ Verificando conexión...")
        if not self.sync.test_connection():
            print("❌ No hay conexión")
            return False
        print("✅ Conectado\n")

        # Test 2: Actualizar índice
        print("2️⃣ Actualizando índice principal...")
        if self.update_constellation_index():
            print("✅ Índice actualizado\n")
        else:
            print("⚠️ No se actualizó índice\n")

        # Test 3: Actualizar métricas
        print("3️⃣ Actualizando dashboard de métricas...")
        if self.update_metrics_dashboard():
            print("✅ Métricas actualizadas\n")
        else:
            print("⚠️ No se actualizaron métricas\n")

        # Test 4: Log standup
        print("4️⃣ Registrando standup de prueba...")
        if self.log_daily_standup(
            "Claude",
            "Creé sistema de sincronización Obsidian",
            "Monitoreando Phase 4 en tiempo real",
            "None",
            "Integración con Antigravity"
        ):
            print("✅ Standup registrado\n")
        else:
            print("⚠️ No se registró standup\n")

        # Test 5: Progreso
        print("5️⃣ Actualizando tracker de progreso...")
        if self.update_progress_tracker(
            "Phase 4 Initialization",
            15,
            "Constelación creada + Sincronización Obsidian lista"
        ):
            print("✅ Progress tracker actualizado\n")
        else:
            print("⚠️ No se actualizó progress tracker\n")

        # Test 6: Resumen semanal
        print("6️⃣ Creando resumen semanal...")
        if self.create_weekly_summary():
            print("✅ Resumen semanal creado\n")
        else:
            print("⚠️ No se creó resumen\n")

        print("✅ Todos los tests completados")


def main():
    api_key = "1d3446a85589777fb01d0fae164ae8b458400ea58af0ab700a38d634eaf3c946"
    monitor = GanoDigitalMonitor(api_key)
    monitor.test_all_features()


if __name__ == "__main__":
    main()
