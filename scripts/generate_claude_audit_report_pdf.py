#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Auditoría integral de estado de desarrollo — Gano Digital (Pilot).
Salida: reports/Gano-Digital-Auditoria-Desarrollo-YYYY-MM-DD.pdf

Enfoque: datos actuales del repo (abril 2026), workflows 01–12, memory/claude,
cola dispatch, consolidación PRs, brecha producción. Para handoff a Claude u otros agentes.

Requisito: pip install fpdf2
"""
from __future__ import annotations

import json
import os
import subprocess
import sys
from datetime import date
from pathlib import Path

from fpdf import FPDF
from fpdf.enums import XPos, YPos

ROOT = Path(__file__).resolve().parents[1]
OUT_DIR = ROOT / "reports"
OUT_DIR.mkdir(exist_ok=True)

# Paleta marca / legibilidad
C_PRIMARY = (18, 52, 86)
C_ACCENT = (41, 128, 185)
C_GOLD = (180, 140, 50)
C_MUTED = (90, 90, 90)
C_OK = (34, 139, 34)
C_WARN = (200, 120, 0)
C_BG = (245, 248, 252)


def ensure_fonts() -> tuple[Path, Path, Path]:
    candidates: list[tuple[Path, Path, Path]] = []
    windir = os.environ.get("WINDIR")
    if windir:
        fd = Path(windir) / "Fonts"
        candidates.append((fd / "arial.ttf", fd / "arialbd.ttf", fd / "ariali.ttf"))
    candidates.extend(
        [
            (
                Path("/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf"),
                Path("/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf"),
                Path("/usr/share/fonts/truetype/dejavu/DejaVuSans-Oblique.ttf"),
            ),
            (
                Path("/Library/Fonts/Arial.ttf"),
                Path("/Library/Fonts/Arial Bold.ttf"),
                Path("/Library/Fonts/Arial Italic.ttf"),
            ),
        ]
    )
    for regular, bold, oblique in candidates:
        if regular.exists() and bold.exists() and oblique.exists():
            return regular, bold, oblique
    print("Fuentes no encontradas (Arial Windows / DejaVu Linux).", file=sys.stderr)
    raise SystemExit(1)


def git_short(cmd: list[str]) -> str:
    try:
        return (
            subprocess.check_output(cmd, cwd=ROOT, stderr=subprocess.DEVNULL, text=True, timeout=15)
            .strip()
        )
    except (subprocess.CalledProcessError, FileNotFoundError, subprocess.TimeoutExpired):
        return "(n/d)"


def git_log_oneline(n: int = 18) -> list[str]:
    raw = git_short(["git", "log", f"-{n}", "--oneline", "--no-decorate"])
    if raw == "(n/d)":
        return []
    return [ln.strip() for ln in raw.splitlines() if ln.strip()]


def count_tasks_agent_queue() -> tuple[int, int]:
    """(total tareas, archivos cola)."""
    d = ROOT / ".github" / "agent-queue"
    if not d.is_dir():
        return 0, 0
    files = list(d.glob("tasks*.json"))
    total = 0
    for p in files:
        try:
            data = json.loads(p.read_text(encoding="utf-8"))
            total += len(data.get("tasks", []))
        except (json.JSONDecodeError, OSError):
            pass
    return total, len(files)


def count_dispatch_tasks() -> int:
    p = ROOT / "memory" / "claude" / "dispatch-queue.json"
    if not p.is_file():
        return 0
    try:
        data = json.loads(p.read_text(encoding="utf-8"))
        return len(data.get("tasks", []))
    except (json.JSONDecodeError, OSError):
        return 0


def load_dispatch_rows() -> list[tuple[str, int, str, str]]:
    """id, priority, humano Sí/No, title."""
    p = ROOT / "memory" / "claude" / "dispatch-queue.json"
    if not p.is_file():
        return []
    try:
        data = json.loads(p.read_text(encoding="utf-8"))
        out: list[tuple[str, int, str, str]] = []
        for t in data.get("tasks", []):
            tid = str(t.get("id", ""))
            pr = int(t.get("priority", 0))
            hum = "Sí" if t.get("requires_human_after") else "No"
            title = str(t.get("title", ""))[:95]
            out.append((tid, pr, hum, title))
        out.sort(key=lambda x: (x[1], x[0]))
        return out
    except (json.JSONDecodeError, OSError, TypeError, ValueError):
        return []


def count_memory_md() -> int:
    memory = ROOT / "memory"
    if not memory.is_dir():
        return 0
    return len(list(memory.rglob("*.md")))


class AuditPDF(FPDF):
    def __init__(self) -> None:
        super().__init__()
        self.set_auto_page_break(auto=True, margin=16)

    def header(self) -> None:
        if self.page_no() == 1:
            return
        self.set_font("GanoAudit", "", 8)
        self.set_text_color(*C_MUTED)
        self.cell(0, 6, "Gano Digital — Auditoría de desarrollo y continuidad (Pilot)", align="R")
        self.ln(8)
        self.set_draw_color(210, 218, 226)
        self.line(self.l_margin, self.get_y(), self.w - self.r_margin, self.get_y())
        self.ln(5)
        self.set_text_color(0, 0, 0)

    def footer(self) -> None:
        self.set_y(-14)
        self.set_font("GanoAudit", "", 8)
        self.set_text_color(130, 130, 130)
        self.cell(0, 8, f"Página {self.page_no()}", align="C")

    def h1(self, t: str) -> None:
        self.ln(3)
        self.set_font("GanoAudit", "B", 15)
        self.set_text_color(*C_PRIMARY)
        self.multi_cell(0, 8, t)
        self.set_text_color(0, 0, 0)
        self.ln(2)
        self.set_draw_color(*C_ACCENT)
        self.set_line_width(0.4)
        self.line(self.l_margin, self.get_y(), self.w - self.r_margin, self.get_y())
        self.set_line_width(0.2)
        self.ln(4)

    def h2(self, t: str) -> None:
        self.ln(2)
        self.set_font("GanoAudit", "B", 12)
        self.set_text_color(*C_PRIMARY)
        self.multi_cell(0, 7, t)
        self.set_text_color(0, 0, 0)
        self.ln(2)

    def p(self, text: str) -> None:
        self.set_font("GanoAudit", "", 10)
        self.multi_cell(0, 5.2, text)
        self.ln(2)

    def bullet(self, text: str) -> None:
        self.set_font("GanoAudit", "", 10)
        self.multi_cell(0, 5.2, f"\u2022 {text}")
        self.ln(0.5)

    def callout(self, title: str, body: str) -> None:
        y0 = self.get_y()
        self.set_fill_color(*C_BG)
        self.set_draw_color(200, 210, 220)
        self.rect(self.l_margin, y0, self.epw, 1, "F")
        self.ln(1)
        yy = self.get_y()
        self.set_font("GanoAudit", "B", 9)
        self.set_text_color(*C_PRIMARY)
        self.multi_cell(self.epw, 5, title)
        self.set_text_color(0, 0, 0)
        self.set_font("GanoAudit", "", 9)
        self.multi_cell(self.epw, 5, body)
        h = self.get_y() - yy + 4
        self.set_xy(self.l_margin, y0)
        self.set_draw_color(*C_ACCENT)
        self.set_line_width(0.3)
        self.rect(self.l_margin, y0, self.epw, h, "D")
        self.set_line_width(0.2)
        self.set_xy(self.l_margin, y0 + h)
        self.ln(2)

    def bar(self, label: str, pct: float, w_bar: float = 115) -> None:
        self.set_font("GanoAudit", "", 9)
        y = self.get_y()
        self.cell(62, 6, label[:48] + ("..." if len(label) > 48 else ""))
        x = self.get_x()
        h = 5
        self.set_fill_color(232, 236, 240)
        self.rect(x, y, w_bar, h, "F")
        fw = max(0, min(w_bar, w_bar * (pct / 100.0)))
        self.set_fill_color(*C_ACCENT)
        if fw > 0:
            self.rect(x, y, fw, h, "F")
        self.set_xy(x + w_bar + 3, y)
        self.set_font("GanoAudit", "B", 9)
        self.cell(18, 6, f"{pct:.0f} %")
        self.ln(8)

    def table3_header(self, a: str, b: str, c: str, w1: float, w2: float, w3: float) -> None:
        self.set_fill_color(*C_PRIMARY)
        self.set_text_color(255, 255, 255)
        self.set_font("GanoAudit", "B", 8)
        self.cell(w1, 6, a, fill=True)
        self.cell(w2, 6, b, fill=True)
        self.cell(w3, 6, c, fill=True)
        self.ln()
        self.set_text_color(0, 0, 0)

    def table3_row(self, a: str, b: str, c: str, w1: float, w2: float, w3: float, alt: bool) -> None:
        if alt:
            self.set_fill_color(248, 250, 252)
        self.set_font("GanoAudit", "", 7.5)
        y0 = self.get_y()
        x0 = self.get_x()
        self.multi_cell(w1, 4, a, fill=alt)
        h1 = self.get_y() - y0
        self.set_xy(x0 + w1, y0)
        self.multi_cell(w2, 4, b, fill=alt)
        h2 = self.get_y() - y0
        self.set_xy(x0 + w1 + w2, y0)
        self.multi_cell(w3, 4, c, fill=alt)
        h3 = self.get_y() - y0
        self.set_xy(x0, y0 + max(h1, h2, h3, 5))
        self.ln(0.5)

    def mono_block(self, lines: list[str]) -> None:
        self.set_font("Courier", "", 7.5)
        self.set_fill_color(250, 252, 255)
        for ln in lines:
            self.cell(
                self.epw,
                3.8,
                ln[:110],
                new_x=XPos.LMARGIN,
                new_y=YPos.NEXT,
            )
        self.ln(2)
        self.set_font("GanoAudit", "", 10)

    def table4_header(self, a: str, b: str, c: str, d: str, w: tuple[float, float, float, float]) -> None:
        w1, w2, w3, w4 = w
        self.set_fill_color(*C_PRIMARY)
        self.set_text_color(255, 255, 255)
        self.set_font("GanoAudit", "B", 7)
        self.cell(w1, 5.5, a, fill=True)
        self.cell(w2, 5.5, b, fill=True)
        self.cell(w3, 5.5, c, fill=True)
        self.cell(w4, 5.5, d, fill=True)
        self.ln()
        self.set_text_color(0, 0, 0)

    def table4_row(self, a: str, b: str, c: str, d: str, w: tuple[float, float, float, float], alt: bool) -> None:
        w1, w2, w3, w4 = w
        if alt:
            self.set_fill_color(248, 250, 252)
        self.set_font("GanoAudit", "", 6.5)
        y0 = self.get_y()
        x0 = self.get_x()
        self.multi_cell(w1, 3.6, a, fill=alt)
        h1 = self.get_y() - y0
        self.set_xy(x0 + w1, y0)
        self.multi_cell(w2, 3.6, b, fill=alt)
        self.set_xy(x0 + w1 + w2, y0)
        self.multi_cell(w3, 3.6, c, fill=alt)
        self.set_xy(x0 + w1 + w2 + w3, y0)
        self.multi_cell(w4, 3.6, d, fill=alt)
        h2 = self.get_y() - y0
        self.set_xy(x0, y0 + max(h1, h2, 5))
        self.ln(0.5)


WORKFLOWS = [
    ("01", "CI", "Sintaxis PHP (Gano)", "php-lint-gano.yml"),
    ("02", "CI", "Escaneo secretos TruffleHog", "secret-scan.yml"),
    ("03", "PR", "Etiquetas automáticas", "labeler.yml"),
    ("04", "Deploy", "Producción rsync SSH", "deploy.yml"),
    ("05", "Ops", "Verificar parches MD5", "verify-patches.yml"),
    ("06", "Repo", "Crear etiquetas GitHub", "setup-repo-labels.yml"),
    ("07", "Agentes", "Validar cola JSON", "validate-agent-queue.yml"),
    ("08", "Agentes", "Sembrar cola Copilot", "seed-copilot-queue.yml"),
    ("09", "Agentes", "Sembrar issues homepage", "seed-homepage-issues.yml"),
    ("10", "Agentes", "Orquestar oleadas", "orchestrate-copilot-waves.yml"),
    ("11", "Agentes", "Setup pasos Copilot", "copilot-setup-steps.yml"),
    ("12", "Ops", "Eliminar wp-file-manager SSH", "verify-remove-wp-file-manager.yml"),
]


def build_pdf() -> Path:
    regular, bold, oblique = ensure_fonts()
    today = date.today()
    head = git_short(["git", "rev-parse", "--short", "HEAD"])
    branch = git_short(["git", "rev-parse", "--abbrev-ref", "HEAD"])
    agent_tasks, agent_files = count_tasks_agent_queue()
    dispatch_n = count_dispatch_tasks()
    mem_md = count_memory_md()
    log_lines = git_log_oneline(16)
    dispatch_rows = load_dispatch_rows()

    pdf = AuditPDF()
    pdf.add_font("GanoAudit", "", regular)
    pdf.add_font("GanoAudit", "B", bold)
    pdf.add_font("GanoAudit", "I", oblique)

    # ——— Portada
    pdf.add_page()
    epw = pdf.epw

    def cover(txt: str, h: float = 7, align: str = "C", size: int = 11, style: str = "") -> None:
        pdf.set_x(pdf.l_margin)
        pdf.set_font("GanoAudit", style, size)
        pdf.multi_cell(epw, h, txt, align=align)

    pdf.ln(36)
    pdf.set_text_color(*C_PRIMARY)
    cover("GANO DIGITAL", 11, "C", 24, "B")
    pdf.set_text_color(*C_GOLD)
    cover("Auditoría integral de desarrollo", 9, "C", 16, "B")
    pdf.set_text_color(55, 55, 55)
    cover("Estado del repositorio Pilot + guías para continuidad (Claude / equipo)", 6, "C", 12)
    pdf.ln(14)
    pdf.set_text_color(0, 0, 0)
    cover(f"Fecha del informe: {today.strftime('%d/%m/%Y')}", 6, "C", 11)
    cover(f"Git HEAD: {head}  |  Rama: {branch}  |  Remoto principal: origin (GitHub)", 6, "C", 10)
    pdf.ln(18)
    pdf.set_fill_color(*C_BG)
    pdf.set_draw_color(*C_ACCENT)
    pdf.rect(pdf.l_margin, pdf.get_y(), epw, 42, "FD")
    pdf.set_xy(pdf.l_margin + 4, pdf.get_y() + 4)
    pdf.set_font("GanoAudit", "B", 10)
    pdf.multi_cell(epw - 8, 5, "Lectura rápida — qué cubre este PDF")
    pdf.set_font("GanoAudit", "", 9)
    pdf.set_x(pdf.l_margin + 4)
    bullets = (
        "Síntesis del progreso en código (Fases 1–3) vs pendientes en servidor y negocio.",
        "Automatización GitHub Actions (12 workflows) y colas de agentes (Copilot + Claude dispatch).",
        "Eventos recientes: consolidación masiva de PRs (abr 2026), documentación memory/claude, integración VS Code.",
        "Tablas y diagramas guía para priorizar: deploy, seguridad, RCC/Reseller, contenido Elementor.",
    )
    for b in bullets:
        pdf.set_x(pdf.l_margin + 4)
        pdf.multi_cell(epw - 8, 4.5, f"\u2022 {b}")
    pdf.set_y(pdf.get_y() + 8)

    pdf.set_font("GanoAudit", "I", 8)
    pdf.set_text_color(*C_MUTED)
    cover(
        "Fuente: TASKS.md, memory/, .github/, wp-content (tema y MU-plugins). "
        "Regenerar: python scripts/generate_claude_audit_report_pdf.py",
        4,
        "C",
        8,
        "I",
    )
    pdf.set_text_color(0, 0, 0)

    # ——— Resumen ejecutivo + indicadores
    pdf.add_page()
    pdf.h1("1. Resumen ejecutivo")
    pdf.p(
        "El proyecto gano.digital combina WordPress, Elementor, WooCommerce (COP) y una estrategia de "
        "comercio basada en GoDaddy Reseller (RCC, pfids, carrito marca blanca). El repositorio Pilot en GitHub "
        "concentra el tema hijo gano-child, plugins gano-*, MU-plugins de seguridad y SEO, scripts Python de "
        "validación y documentación extensa bajo memory/. Las fases 1 a 3 están implementadas en código en main; "
        "el valor en producción depende del despliegue, la eliminación de plugins de riesgo y la configuración "
        "en paneles (SEO, GSC, Rank Math) más el contenido real en Elementor."
    )
    pdf.callout(
        "Estado abril 2026 (hitos recientes)",
        "Oleada masiva de PRs generados por Copilot fusionada en main con squash (2026-04-03). "
        "Carpeta memory/claude/ documenta historial, pendientes y FAQ. Cola dispatch (12 tareas) y scripts "
        "claude_dispatch.py integran el flujo con Cursor/VS Code (.vscode/tasks.json). Workflow 12 alineado para "
        "eliminación remota de wp-file-manager.",
    )

    pdf.h2("1.1 Indicadores del repositorio (instantánea)")
    w1, w2, w3 = 38.0, 52.0, 95.0
    pdf.table3_header("Métrica", "Valor", "Nota", w1, w2, w3)
    rows = [
        ("Archivos .md en memory/", str(mem_md), "Documentación operativa, research, sesiones"),
        ("Tareas cola Copilot (JSON)", f"{agent_tasks} en {agent_files} archivos", "tasks.json + wave2 + wave3"),
        ("Tareas cola Claude dispatch", str(dispatch_n), "memory/claude/dispatch-queue.json"),
        ("Workflows GitHub Actions", "12", "Prefijos 01–12 numerados en sidebar"),
        ("Último commit (HEAD)", head, "Ver tabla de historial en sección 3"),
    ]
    for i, (a, b, c) in enumerate(rows):
        pdf.table3_row(a, b, c, w1, w2, w3, i % 2 == 1)
    pdf.ln(3)

    pdf.h2("1.2 Progreso por dimensión (estimación para planificación)")
    pdf.p(
        "Porcentajes orientativos: código en repo vs validación en vivo vs comercio Reseller. No son métricas financieras."
    )
    pdf.bar("Fase 1 — Parches críticos (código repo)", 100)
    pdf.bar("Fase 2 — Hardening (código repo)", 100)
    pdf.bar("Fase 3 — SEO técnico + CWV (código repo)", 100)
    pdf.bar("Fase 3 — Configuración viva (Gano SEO, GSC, Rank Math)", 32)
    pdf.bar("Fase 4 — RCC, pfids, checkout Reseller end-to-end", 28)
    pdf.bar("Contenido homepage (Elementor + copy real)", 35)
    pdf.bar("Automatización CI/CD y colas agentes (GitHub + local)", 88)

    # ——— Línea de tiempo y diagrama
    pdf.add_page()
    pdf.h1("2. Línea de tiempo y flujo operativo")
    pdf.h2("2.1 Hechos clave (abril 2026)")
    for t in [
        "2026-04-01 — Sesión Cursor: organización local, scripts de análisis, higiene SSH (memory/sessions).",
        "2026-04-02 — Triage Copilot, CI, labeler, progreso consolidado en documentación.",
        "2026-04-03 — Consolidación PRs: squash merge masivo a main; cierre #49/#84/#85; conflictos resueltos en #52 (pfid) y #76 (CSS+a11y).",
        "2026-04-03 — Skill gano-github-copilot-orchestration actualizada; workflow 12 unificado; DEV-COORDINATION y TASKS alineados.",
        "Post-04-03 — memory/claude/ (README + historial + pendientes + FAQ + dispatch-queue + VS Code tasks).",
    ]:
        pdf.bullet(t)

    pdf.h2("2.2 Diagrama guía — De commit a producción")
    pdf.mono_block(
        [
            "  [ desarrollador ]",
            "        |  git push main (rutas gano-child / gano-* / mu-plugins )",
            "        v",
            "  +-------------+     secrets: SSH, SERVER_HOST, SERVER_USER, DEPLOY_PATH",
            "  | 04 Deploy   |------------------------------->  Servidor WordPress",
            "  +-------------+",
            "        |",
            "  +-------------+     manual: Run workflow",
            "  | 05 Verificar|---->  diff MD5 repo vs servidor",
            "  +-------------+",
            "        |",
            "  +-------------+     opcional force_remove",
            "  | 12 wp-fm    |---->  eliminar wp-file-manager por SSH/WP-CLI",
            "  +-------------+",
        ]
    )
    pdf.p(
        "Los workflows 08–11 gobiernan issues y Copilot; no despliegan código por sí solos. "
        "La cola Claude (dispatch) vive en el workspace y no requiere GitHub API."
    )

    pdf.h2("2.3 Diagrama guía — Colas de trabajo paralelas")
    pdf.mono_block(
        [
            "   .github/agent-queue/          memory/claude/",
            "   tasks.json  --------+        dispatch-queue.json",
            "   tasks-wave2.json      |              |",
            "   tasks-wave3.json      v              v",
            "                    [ 08 Seed ]   claude_dispatch.py",
            "                         |              |",
            "                         v              v",
            "                 Issues GitHub     Tareas en repo",
            "                 + Copilot         (Cursor / Claude)",
        ]
    )

    # ——— Git
    pdf.add_page()
    pdf.h1("3. Historial Git reciente (origin/main)")
    pdf.p("Últimas entradas --oneline (truncado a 16). Actualizar regenerando el PDF.")
    pdf.set_font("Courier", "", 7)
    for ln in log_lines[:16]:
        pdf.cell(0, 3.6, ln[:118], new_x=XPos.LMARGIN, new_y=YPos.NEXT)
    pdf.ln(2)
    pdf.set_font("GanoAudit", "", 10)
    pdf.bullet("Remoto GitHub: Gano-digital/Pilot (integración CI, Copilot).")
    pdf.bullet("Remoto GitLab presente en algunos clones; fuente de verdad operativa acordada: GitHub.")

    # ——— Workflows
    pdf.add_page()
    pdf.h1("4. GitHub Actions — inventario 01 a 12")
    pdf.table3_header("#", "Fase", "Nombre / archivo", 12.0, 22.0, 151.0)
    for i, (num, phase, name, yml) in enumerate(WORKFLOWS):
        pdf.table3_row(num, phase, f"{name} — {yml}", 12.0, 22.0, 151.0, i % 2 == 1)
    pdf.ln(3)
    pdf.callout(
        "Cuándo ejecutar (recordatorio)",
        "04/05/12: operación servidor tras configurar secrets. 08: nuevos issues desde JSON. 09: solo si faltan issues homepage-fixplan. "
        "10: solo si vuelve a haber lote oleada 1 pendiente de merge automático. 06: si faltan etiquetas area:*.",
    )

    # ——— Arquitectura documental
    pdf.add_page()
    pdf.h1("5. Arquitectura documental (memory/ + clave .github)")
    pdf.mono_block(
        [
            "memory/",
            "  claude/           <- Contexto Claude: historial, pendientes, FAQ, dispatch",
            "  commerce/         <- (pendiente) guías RCC / pfid",
            "  content/          <- Copy homepage, Elementor, wave3",
            "  notes/            <- plugins-de-fase, nota Diego",
            "  ops/              <- (pendiente) checklists operativos",
            "  projects/         <- visión proyecto gano-digital",
            "  research/         <- fase4, wave3 brief, competencia",
            "  sessions/         <- reportes de sesión fechados",
            "",
            ".github/",
            "  workflows/        <- 12 YAML numerados",
            "  agent-queue/      <- JSON oleadas Copilot",
            "  prompts/          <- copilot-bulk-assign.md",
            "  DEV-COORDINATION  <- sync git/servidor",
        ]
    )

    # ——— Pendientes
    pdf.add_page()
    pdf.h1("6. Pendientes priorizados (síntesis TASKS.md)")
    pdf.h2("6.1 Crítico — servidor y seguridad")
    for t in [
        "Sincronizar Fases 1–3: secrets GitHub + 04 Deploy o SFTP; wp-config solo por canal seguro (no en git).",
        "Eliminar wp-file-manager: workflow 12 o manual; verificar alerta MU security desaparecida.",
    ]:
        pdf.bullet(t)
    pdf.h2("6.2 Alta — paneles y visibilidad")
    for t in [
        "Gano SEO (wp-admin): datos empresa digital, cobertura Colombia.",
        "Google Search Console + Rank Math wizard (modelo servicios digitales).",
        "RCC: pfids reales en constantes GANO_PFID_* (functions.php); prueba checkout marca blanca.",
        "Elementor: menú primary, Lorem fuera, hero y CTAs según memory/content/.",
    ]:
        pdf.bullet(t)
    pdf.h2("6.3 Media y operación GitHub")
    for t in [
        "Cerrar issues abiertos ya cubiertos por main (revisión manual).",
        "2FA Wordfence; plugins de fase solo tras confirmación explícita de Diego.",
        "Rotación de tokens y remotes sin credenciales en URL.",
    ]:
        pdf.bullet(t)

    pdf.add_page()
    pdf.h1("7. Cola Claude dispatch (detalle)")
    pdf.p(
        "Doce tareas versionadas en memory/claude/dispatch-queue.json. Prioridad numérica menor = más urgente. "
        "'Humano' indica que el cierre requiere acción en panel o RCC tras el trabajo en repo."
    )
    pdf.p(
        "Ejecución: python scripts/claude_dispatch.py next | list | show <id> | complete <id>. "
        "VS Code/Cursor: Tasks Run Task (Gano: …) o Ctrl+Shift+Alt+N (next), +L (listar), +V (validar JSON)."
    )
    wq = (24.0, 12.0, 14.0, 133.0)
    pdf.table4_header("ID", "Pri.", "Humano", "Título / objetivo", wq)
    if not dispatch_rows:
        pdf.p("(No se pudo cargar dispatch-queue.json — verificar ruta memory/claude/).")
    for i, (tid, pr, hum, title) in enumerate(dispatch_rows):
        pdf.table4_row(tid, str(pr), hum, title, wq, i % 2 == 1)
    pdf.ln(2)

    pdf.h2("7.1 Scripts Python relacionados")
    for t in [
        "scripts/claude_dispatch.py — orquestación local de la cola.",
        "scripts/validate_claude_dispatch.py — valida schema del JSON dispatch.",
        "scripts/validate_agent_queue.py — cola Copilot (.github/agent-queue/).",
        "scripts/generate_claude_audit_report_pdf.py — genera este PDF.",
        "scripts/generate_board_report_pdf.py — reporte ejecutivo junta (legacy/paralelo).",
    ]:
        pdf.bullet(t)

    pdf.add_page()
    pdf.h1("8. Consolidación PRs Copilot (2026-04-03)")
    pdf.p(
        "Resumen alineado con memory/sessions/2026-04-03-consolidacion-prs-copilot.md. "
        "Tras esta oleada, la rama main integra smoke reseller, visual tokens wave3, shop con GANO_PFID_* y utilidades a11y/tipografía."
    )
    pdf.table3_header("PR / tema", "Resultado", "Nota", 22.0, 28.0, 133.0)
    pr_rows = [
        ("#49, #84, #85", "Cerrados sin merge", "Duplicado, WIP vacío u obsoleto frente a otros PRs."),
        ("#52 shop-premium", "Merge + conflicto resuelto", "Modelo pfid GANO_PFID_* de main; precios COP 1.2M / 1.8M Ultimate/Security."),
        ("#76 style.css", "Merge + conflicto resuelto", "Conviven utilidades tipográficas wave3 y bloque a11y (#83)."),
        ("Resto oleada", "Squash merge a main", "Orden según MERGE-PLAYBOOK; CI PHP + TruffleHog en rutas Gano."),
    ]
    for i, (a, b, c) in enumerate(pr_rows):
        pdf.table3_row(a, b, c, 22.0, 28.0, 133.0, i % 2 == 1)
    pdf.ln(2)
    pdf.callout(
        "Implicación para issues abiertos",
        "Puede quedar trabajo reflejado en main pero issues aún abiertos en GitHub. "
        "Revisión manual + cierre con comentario o guía gh-issue-close (dispatch cd-repo-003).",
    )

    pdf.add_page()
    pdf.h1("9. Matriz fase — artefactos en repositorio")
    pdf.p("Referencia rápida de dónde vive cada bloque de trabajo aprobado en main (rutas relativas al repo).")
    pdf.table3_header("Fase", "Ámbito", "Archivos / carpetas representativos", 16.0, 32.0, 135.0)
    matrix = [
        ("1", "Críticos", "wp-config.php (no git; patrón endurecido), mu-plugins/gano-security.php, gano-chat.js, functions.php chat"),
        ("2", "Hardening", "gano-security.php CSP + headers, functions.php rate limit REST"),
        ("3", "SEO / CWV", "mu-plugins/gano-seo.php, gano-child/functions.php, style.css, templates/page-seo-landing.php, seo-pages/"),
        ("3+", "Home / shop UI", "gano-child/templates/shop-premium.php, memory/content/* copy y clases Elementor"),
        ("GH", "Automatización", ".github/workflows/*.yml (12), agent-queue/*.json, prompts/, DEV-COORDINATION.md"),
        ("IA", "Continuidad Claude", "memory/claude/*, scripts/claude_dispatch.py, .vscode/tasks.json"),
    ]
    for i, (a, b, c) in enumerate(matrix):
        pdf.table3_row(a, b, c, 16.0, 32.0, 135.0, i % 2 == 1)
    pdf.ln(2)
    pdf.h2("9.1 Skills .gano-skills/ (orientación agentes)")
    for t in [
        "gano-github-copilot-orchestration — Actions 01–12, colas, prompts bulk, estado post-merge.",
        "gano-multi-agent-local-workflow — Cursor, Claude Code, Antigravity, rutas, sin secretos en URL.",
        "gano-wp-security, gano-content-audit, gano-fase4-plataforma — según alcance de la tarea.",
    ]:
        pdf.bullet(t)

    pdf.ln(2)
    pdf.h2("9.2 Tooling opcional incorporado (opt-in, no runtime)")
    for t in [
        "Graphify local (sin hooks): skill .gano-skills/gano-graphify-local/ + docs tools/graphify/README.md",
        "Agent Orchestrator (AO) opcional: skill .gano-skills/gano-agent-orchestrator-local/ + docs tools/agent-orchestrator/README.md",
        "ML-SSD (Apple) como submodule: vendor/ml-ssd + skill .gano-skills/gano-ml-ssd/ + docs tools/ml-ssd/README.md",
    ]:
        pdf.bullet(t)

    pdf.add_page()
    pdf.h1("10. Riesgos y mitigaciones")
    pdf.table3_header("Riesgo", "Impacto", "Mitigación", 38.0, 35.0, 112.0)
    risks = [
        (
            "Brecha deploy",
            "Alto",
            "05 Verificar parches; checklist archivos; staging si existe en hosting.",
        ),
        (
            "wp-file-manager en prod",
            "Crítico",
            "Workflow 12 o eliminación manual inmediata.",
        ),
        (
            "PENDING_RCC en checkout",
            "Alto",
            "Mapeo RCC + pruebas en sandbox antes de campañas.",
        ),
        (
            "Issues obsoletos abiertos",
            "Medio",
            "gh-issue-close-guide (dispatch) + filtros por label.",
        ),
    ]
    for i, (a, b, c) in enumerate(risks):
        pdf.table3_row(a, b, c, 38.0, 35.0, 112.0, i % 2 == 1)

    pdf.add_page()
    pdf.h1("11. Próximos pasos sugeridos")
    pdf.h2("Ventana 7 días")
    for t in [
        "Configurar secrets y primer 04 Deploy + 05 verificación.",
        "Ejecutar o planificar 12 si el plugin de riesgo aún existe en servidor.",
        "Completar 2–3 tareas dispatch de mayor prioridad (documentación RCC y checklists).",
    ]:
        pdf.bullet(t)
    pdf.h2("Ventana 30 días")
    for t in [
        "SEO/GSC/Rank Math operativos; copy homepage sin Lorem.",
        "Flujo compra Reseller verificado con pfids reales.",
        "Higiene issues GitHub y cierre de drift documentado en obsolete-copilot-tasks.md si aplica.",
    ]:
        pdf.bullet(t)

    pdf.add_page()
    pdf.h1("12. Apéndice — referencias de archivo")
    pdf.p(
        "TASKS.md; CLAUDE.md; memory/claude/README.md; memory/sessions/2026-04-03-consolidacion-prs-copilot.md; "
        ".github/DEV-COORDINATION.md; .github/workflows/README.md; .github/MERGE-PLAYBOOK.md; "
        "memory/notes/nota-diego-recomendaciones-2026-04.md; .gano-skills/gano-github-copilot-orchestration/SKILL.md"
    )
    pdf.set_font("GanoAudit", "I", 8)
    pdf.set_text_color(*C_MUTED)
    pdf.multi_cell(
        0,
        4,
        "Documento generado automáticamente. Validar cifras de GitHub en la web si se requiere precisión contractual.",
    )

    out = OUT_DIR / f"Gano-Digital-Auditoria-Desarrollo-{today.strftime('%Y-%m-%d')}.pdf"
    pdf.output(str(out))
    return out


if __name__ == "__main__":
    p = build_pdf()
    print(p)
