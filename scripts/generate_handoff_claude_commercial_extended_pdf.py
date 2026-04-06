#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Informe extendido — handoff Claude + auditoría comercial/UX (Gano Digital Pilot).
Salida: reports/Gano-Digital-Handoff-Claude-Comercial-YYYY-MM-DD.pdf

Objetivo: ≥30 páginas, tablas, barras tipo progreso, skills, pendientes, estrategia comercial/UX.
Requisito: pip install fpdf2
"""
from __future__ import annotations

import json
import os
import re
import subprocess
import sys
from datetime import date
from pathlib import Path

from fpdf import FPDF
from fpdf.enums import XPos, YPos

ROOT = Path(__file__).resolve().parents[1]
OUT_DIR = ROOT / "reports"
OUT_DIR.mkdir(exist_ok=True)

C_PRIMARY = (18, 52, 86)
C_ACCENT = (41, 128, 185)
C_GOLD = (180, 140, 50)
C_MUTED = (90, 90, 90)
C_OK = (34, 139, 34)
C_WARN = (200, 100, 30)
C_BAD = (180, 50, 50)
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
    print("Fuentes no encontradas (Arial / DejaVu).", file=sys.stderr)
    raise SystemExit(1)


def git_short(cmd: list[str]) -> str:
    try:
        raw = subprocess.check_output(cmd, cwd=ROOT, stderr=subprocess.DEVNULL, timeout=20)
        return raw.decode("utf-8", errors="replace").strip()
    except (subprocess.CalledProcessError, FileNotFoundError, subprocess.TimeoutExpired, OSError):
        return "n/d"


def parse_unchecked_tasks_md(path: Path) -> list[str]:
    if not path.is_file():
        return []
    out: list[str] = []
    for line in path.read_text(encoding="utf-8", errors="replace").splitlines():
        s = line.strip()
        if s.startswith("- [ ]"):
            t = s[5:].strip()
            if t:
                out.append(t[:200])
    return out[:50]


def discover_skills() -> list[tuple[str, str]]:
    """(name, first line of SKILL.md or empty)."""
    base = ROOT / ".gano-skills"
    rows: list[tuple[str, str]] = []
    if not base.is_dir():
        return rows
    for d in sorted(base.iterdir()):
        if not d.is_dir() or d.name.startswith("."):
            continue
        sk = d / "SKILL.md"
        if not sk.is_file():
            continue
        first = ""
        try:
            for ln in sk.read_text(encoding="utf-8", errors="replace").splitlines():
                ln = ln.strip()
                if ln and not ln.startswith("---") and not ln.startswith("#"):
                    first = ln[:120]
                    break
        except OSError:
            pass
        rows.append((d.name, first))
    return rows


def count_agent_queue() -> tuple[int, int]:
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


def count_dispatch() -> int:
    p = ROOT / "memory" / "claude" / "dispatch-queue.json"
    if not p.is_file():
        return 0
    try:
        return len(json.loads(p.read_text(encoding="utf-8")).get("tasks", []))
    except (json.JSONDecodeError, OSError):
        return 0


def load_dispatch_tasks() -> list[tuple[str, str, int, str]]:
    p = ROOT / "memory" / "claude" / "dispatch-queue.json"
    if not p.is_file():
        return []
    try:
        data = json.loads(p.read_text(encoding="utf-8"))
        out: list[tuple[str, str, int, str]] = []
        for t in data.get("tasks", []):
            hum = "Sí" if t.get("requires_human_after") else "No"
            out.append(
                (
                    str(t.get("id", "")),
                    str(t.get("title", ""))[:78],
                    int(t.get("priority", 0)),
                    hum,
                )
            )
        return sorted(out, key=lambda x: (x[2], x[0]))
    except (json.JSONDecodeError, OSError, TypeError, ValueError):
        return []


def agent_queue_by_file() -> list[tuple[str, int]]:
    d = ROOT / ".github" / "agent-queue"
    if not d.is_dir():
        return []
    rows: list[tuple[str, int]] = []
    for p in sorted(d.glob("tasks*.json")):
        try:
            data = json.loads(p.read_text(encoding="utf-8"))
            n = len(data.get("tasks", []))
            rows.append((p.name, n))
        except (json.JSONDecodeError, OSError):
            rows.append((p.name, 0))
    return rows


SOTA_PAGE_TITLES = [
    "Arquitectura NVMe: La Muerte del SSD Tradicional",
    "Zero-Trust Security: El Fin de las Contraseñas",
    "Gestión Predictiva con AI: Cero Caídas, Cero Sorpresas",
    "Soberanía Digital en LATAM: Tus Datos, Tu Control",
    "Headless WordPress: La Velocidad Absoluta",
    "Mitigación DDoS Inteligente: Firewall de Nueva Generación",
    "La Muerte del Hosting Compartido: El Riesgo Invisible",
    "Edge Computing: Contenido a Cero Distancia de tu Cliente",
    "Green Hosting: Infraestructura Sostenible para tu Negocio",
    "Cifrado Post-Cuántico: La Bóveda del Futuro",
    "CI/CD Automatizado: Nunca Más Rompas tu Tienda en Vivo",
    "Backups Continuos en Tiempo Real: Tu Máquina del Tiempo",
    "Skeleton Screens: La Psicología de la Velocidad Percibida",
    "Escalamiento Elástico: Sobrevive a tu Propio Éxito Viral",
    "Self-Healing: El Ecosistema que se Cura Solo",
    "Micro-Animaciones e Interacciones Hápticas: Diseño que se Siente",
    "HTTP/3 y QUIC: El Protocolo que Rompe la Congestión",
    "Alta Disponibilidad (HA): La Infraestructura Indestructible",
    "Analytics Server-Side: Privacidad, Velocidad y Datos Reales",
    "El Agente IA de Administración: Tu Infraestructura Habla Español",
]


class Doc(FPDF):
    def __init__(self) -> None:
        super().__init__()
        self.set_auto_page_break(auto=True, margin=14)

    def header(self) -> None:
        if self.page_no() == 1:
            return
        self.set_font("GanoX", "", 7.5)
        self.set_text_color(*C_MUTED)
        self.cell(0, 5, "Gano Digital — Handoff Claude · Comercial/UX + auditoría interna", align="R")
        self.ln(6)
        self.set_draw_color(210, 218, 226)
        self.line(self.l_margin, self.get_y(), self.w - self.r_margin, self.get_y())
        self.ln(4)
        self.set_text_color(0, 0, 0)

    def footer(self) -> None:
        self.set_y(-12)
        self.set_font("GanoX", "", 7)
        self.set_text_color(120, 120, 120)
        self.cell(0, 8, f"Pág. {self.page_no()}", align="C")

    def h1(self, t: str) -> None:
        self.ln(2)
        self.set_font("GanoX", "B", 13)
        self.set_text_color(*C_PRIMARY)
        self.multi_cell(0, 7, t)
        self.set_text_color(0, 0, 0)
        self.ln(1)
        self.set_draw_color(*C_ACCENT)
        self.set_line_width(0.35)
        self.line(self.l_margin, self.get_y(), self.w - self.r_margin, self.get_y())
        self.set_line_width(0.2)
        self.ln(3)

    def h2(self, t: str) -> None:
        self.ln(1)
        self.set_font("GanoX", "B", 10.5)
        self.set_text_color(*C_PRIMARY)
        self.multi_cell(0, 6, t)
        self.set_text_color(0, 0, 0)
        self.ln(2)

    def p(self, s: str, size: int = 9) -> None:
        self.set_font("GanoX", "", size)
        self.multi_cell(0, 4.2, s)
        self.ln(1)

    def bullet(self, s: str) -> None:
        self.set_font("GanoX", "", 8.5)
        self.multi_cell(0, 4.2, f"\u2022 {s}")
        self.ln(0.5)

    def bar(self, label: str, pct: float, w_bar: float = 110) -> None:
        self.set_font("GanoX", "", 8)
        y = self.get_y()
        self.cell(58, 5.5, label[:40] + ("..." if len(label) > 40 else ""))
        x = self.get_x()
        h = 4.5
        self.set_fill_color(232, 236, 240)
        self.rect(x, y, w_bar, h, "F")
        fw = max(0, min(w_bar, w_bar * (pct / 100.0)))
        self.set_fill_color(*C_ACCENT)
        if fw > 0:
            self.rect(x, y, fw, h, "F")
        self.set_xy(x + w_bar + 2, y)
        self.set_font("GanoX", "B", 8)
        self.cell(16, 5.5, f"{pct:.0f}%")
        self.ln(7)

    def badge(self, title: str, pts: str, desc: str) -> None:
        y0 = self.get_y()
        self.set_fill_color(255, 252, 235)
        self.set_draw_color(*C_GOLD)
        self.set_line_width(0.25)
        self.rect(self.l_margin, y0, self.epw, 1, "F")
        self.ln(1)
        yy = self.get_y()
        self.set_font("GanoX", "B", 9)
        self.set_text_color(*C_PRIMARY)
        self.cell(120, 5, title)
        self.set_font("GanoX", "B", 9)
        self.set_text_color(*C_GOLD)
        self.cell(0, 5, pts, align="R")
        self.ln(5)
        self.set_text_color(0, 0, 0)
        self.set_font("GanoX", "", 8.5)
        self.multi_cell(self.epw, 4, desc)
        h = self.get_y() - yy + 3
        self.set_xy(self.l_margin, y0)
        self.rect(self.l_margin, y0, self.epw, h, "D")
        self.set_xy(self.l_margin, y0 + h)
        self.ln(2)

    def th4(self, a: str, b: str, c: str, d: str, w: tuple[float, float, float, float]) -> None:
        w1, w2, w3, w4 = w
        self.set_fill_color(*C_PRIMARY)
        self.set_text_color(255, 255, 255)
        self.set_font("GanoX", "B", 7)
        self.cell(w1, 5, a, fill=True)
        self.cell(w2, 5, b, fill=True)
        self.cell(w3, 5, c, fill=True)
        self.cell(w4, 5, d, fill=True)
        self.ln()
        self.set_text_color(0, 0, 0)

    def tr4(self, a: str, b: str, c: str, d: str, w: tuple[float, float, float, float], alt: bool) -> None:
        w1, w2, w3, w4 = w
        if alt:
            self.set_fill_color(248, 250, 252)
        self.set_font("GanoX", "", 6.8)
        y0 = self.get_y()
        x0 = self.get_x()
        self.multi_cell(w1, 3.5, a, fill=alt)
        h1 = self.get_y() - y0
        self.set_xy(x0 + w1, y0)
        self.multi_cell(w2, 3.5, b, fill=alt)
        self.set_xy(x0 + w1 + w2, y0)
        self.multi_cell(w3, 3.5, c, fill=alt)
        self.set_xy(x0 + w1 + w2 + w3, y0)
        self.multi_cell(w4, 3.5, d, fill=alt)
        self.set_xy(x0, y0 + max(h1, 5))
        self.ln(0.5)

    def mono(self, lines: list[str]) -> None:
        # Courier no soporta flechas Unicode en fpdf2; usar fuente embebida
        self.set_font("GanoX", "", 7)
        self.set_fill_color(250, 252, 255)
        for ln in lines:
            self.cell(self.epw, 3.5, ln[:118], new_x=XPos.LMARGIN, new_y=YPos.NEXT)
        self.ln(2)
        self.set_font("GanoX", "", 9)


def build() -> Path:
    regular, bold, oblique = ensure_fonts()
    today = date.today()
    head = git_short(["git", "rev-parse", "--short", "HEAD"])
    branch = git_short(["git", "rev-parse", "--abbrev-ref", "HEAD"])
    agent_n, agent_files = count_agent_queue()
    dispatch_n = count_dispatch()
    skills = discover_skills()
    pending = parse_unchecked_tasks_md(ROOT / "TASKS.md")

    pdf = Doc()
    pdf.add_font("GanoX", "", regular)
    pdf.add_font("GanoX", "B", bold)
    pdf.add_font("GanoX", "I", oblique)
    epw = pdf.epw
    W = (22.0, 38.0, 58.0, 67.0)

    # ——— Portada
    pdf.add_page()
    pdf.ln(28)
    pdf.set_text_color(*C_PRIMARY)
    pdf.set_font("GanoX", "B", 22)
    pdf.multi_cell(epw, 10, "GANO DIGITAL", align="C")
    pdf.set_text_color(*C_GOLD)
    pdf.set_font("GanoX", "B", 15)
    pdf.multi_cell(epw, 8, "Handoff Claude - Reporte extendido", align="C")
    pdf.set_text_color(55, 55, 55)
    pdf.set_font("GanoX", "", 11)
    pdf.multi_cell(
        epw,
        6,
        "Auditoria interna | Comercial + UX | Skills | Pendientes | GitHub",
        align="C",
    )
    pdf.ln(10)
    pdf.set_text_color(0, 0, 0)
    pdf.set_font("GanoX", "", 10)
    pdf.multi_cell(epw, 5, f"Fecha: {today.strftime('%d/%m/%Y')} | HEAD {head} | {branch}", align="C")
    pdf.ln(14)
    pdf.set_fill_color(*C_BG)
    pdf.set_draw_color(*C_ACCENT)
    pdf.rect(pdf.l_margin, pdf.get_y(), epw, 38, "FD")
    pdf.set_xy(pdf.l_margin + 4, pdf.get_y() + 4)
    pdf.set_font("GanoX", "B", 10)
    pdf.multi_cell(epw - 8, 5, "Alcance del documento")
    pdf.set_font("GanoX", "", 9)
    pdf.set_x(pdf.l_margin + 4)
    bullets = (
        "Síntesis lacónica: estado técnico, negocio Reseller, brecha producción.",
        "Tablero gamificado (niveles) + barras de completitud por dimensión.",
        "Inventario de skills .gano-skills + colas agentes + dispatch Claude.",
        "Plan comercial/UX del día: curación de contenidos, tablas, formatos Elementor.",
        "Matrices de riesgo, KPIs, roadmap 7/30/90 días.",
    )
    for b in bullets:
        pdf.set_x(pdf.l_margin + 4)
        pdf.multi_cell(epw - 8, 4.5, f"\u2022 {b}")

    # --- 1 Resumen KPI
    pdf.add_page()
    pdf.h1("1. Resumen ejecutivo - KPIs")
    pdf.p(
        "Modelo: hosting WordPress Colombia, Reseller GoDaddy, vitrina SOTA. Codigo Fases 1-3 en repo; "
        "paridad servidor y comercializacion RCC pendientes de cierre operativo."
    )
    pdf.h2("1.1 Métricas repositorio (instantánea)")
    pdf.th4("Métrica", "Valor", "Nota", "Crítico", W)
    rows = [
        ("Colas agent-queue (tareas)", str(agent_n), f"{agent_files} JSON", "Alto volumen"),
        ("Dispatch Claude (tareas)", str(dispatch_n), "memory/claude/dispatch-queue.json", "Repo-only"),
        ("Skills .gano-skills", str(len(skills)), "carpetas con SKILL.md", "Guía agentes"),
        ("Pendientes TASKS.md ([ ])", str(len(pending)), "parseado automático", "Acción humana"),
        ("Git HEAD", head[:8], branch, "Verificar push"),
    ]
    for i, (a, b, c, d) in enumerate(rows):
        pdf.tr4(a, b, c, d, W, i % 2 == 1)

    pdf.h2("1.2 Barras — dimensión de madurez (estimación interna)")
    pdf.p("Escala subjetiva para priorizar; no es auditoría financiera.")
    for label, pct in [
        ("Código endurecido (F1–3) en repo", 92),
        ("Despliegue en producción", 38),
        ("Contenido/UX vitrina (sin Lorem)", 44),
        ("Comercio Reseller (RCC + pfids)", 35),
        ("SEO panel (GSC/Rank Math)", 28),
        ("Automatización GitHub Actions", 78),
    ]:
        pdf.bar(label, pct)

    # --- 2 Gamificación niveles
    pdf.add_page()
    pdf.h1("2. Tablero gamificado — niveles de operación")
    pdf.p("Cada nivel desbloquea el siguiente en la práctica (dependencia real, no cosmética).")
    levels = [
        ("NIVEL 0 — Repo íntegro", "+100 pts", "main estable; CI 01/02; colas JSON validadas.", 100),
        ("NIVEL 1 — Seguridad servidor", "+150 pts", "wp-file-manager fuera; parches F1–3 desplegados o 05 OK.", 55),
        ("NIVEL 2 — SEO técnico vivo", "+120 pts", "Gano SEO + GSC + Rank Math configurados; sin placeholders críticos schema.", 40),
        ("NIVEL 3 — Comercio RCC", "+200 pts", "pfids reales; CTAs shop → carrito; smoke checkout.", 35),
        ("NIVEL 4 — Comercial/UX hoy", "+250 pts", "Propuesta página: copy, tablas, jerarquía visual, Elementor alineado.", 30),
        ("NIVEL 5 — Plataforma (Fase 4+)", "+300 pts", "Portal cliente, soporte, facturación local si aplica decisión.", 12),
    ]
    for title, pts, desc, prog in levels:
        pdf.badge(title, pts, desc)
        pdf.bar("Progreso estimado nivel", float(prog))

    # --- 3 Skills
    pdf.add_page()
    pdf.h1("3. Inventario skills (.gano-skills)")
    pdf.p("Incluye skills añadidas o relevantes para pipeline comercial, infra y contenido.")
    pdf.th4("Skill", "Resumen (1ª línea útil)", "Uso", "Prioridad", (35.0, 75.0, 52.0, 23.0))
    for i, (name, first) in enumerate(skills):
        use = "Agente / Cursor"
        if "blender" in name.lower():
            use = "Assets 3D → web"
        elif "cpanel" in name.lower():
            use = "SSH/cPanel"
        elif "copilot" in name.lower():
            use = "GitHub Actions"
        elif "security" in name.lower() or "guardian" in name.lower():
            use = "Sesión / higiene"
        elif "wompi" in name.lower():
            use = "Legado pago"
        elif "fase4" in name.lower():
            use = "Billing futuro"
        elif "multi-agent" in name.lower():
            use = "Flujo local"
        pr = "Media"
        if name in ("gano-content-audit", "gano-github-copilot-orchestration"):
            pr = "Alta"
        if "blender" in name or "cpanel" in name:
            pr = "Media (pipeline)"
        pdf.tr4(name, first[:95] or "—", use, pr, (35.0, 75.0, 52.0, 23.0), i % 2 == 1)

    # --- 4 Pendientes
    pdf.add_page()
    pdf.h1("4. Pendientes — TASKS.md (items [ ])")
    pdf.p("Lista automática; truncar si excede layout. Verificar en fuente para wording exacto.")
    pdf.th4("ID", "Tarea", "Tipo", "Acción", (12.0, 92.0, 38.0, 43.0))
    for i, line in enumerate(pending[:32]):
        t = re.sub(r"\*\*", "", line)
        short = t[:110] + ("…" if len(t) > 110 else "")
        tipo = "Ops"
        if "SEO" in t or "GSC" in t or "Rank" in t:
            tipo = "SEO"
        elif "Lorem" in t or "Elementor" in t or "Nosotros" in t:
            tipo = "UX/Cont"
        elif "RCC" in t or "Reseller" in t or "Checkout" in t:
            tipo = "Comercio"
        elif "wp-file" in t or "SSH" in t or "parches" in t or "Deploy" in t:
            tipo = "Crítico"
        act = "Humano panel"
        if "GitHub" in t or "Actions" in t:
            act = "GH Actions"
        pdf.tr4(str(i + 1), short, tipo, act, (12.0, 92.0, 38.0, 43.0), i % 2 == 1)

    # --- 5 Comercial / UX hoy
    pdf.add_page()
    pdf.h1("5. Propuesta comercial hoy — UX y contenidos")
    pdf.h2("5.1 Objetivo del día")
    pdf.p(
        "Cerrar la narrativa comercial de la página: mensaje único, prueba social honesta, "
        "jerarquía de secciones, CTAs al carrito Reseller, coherencia con 4 ecosistemas."
    )
    pdf.h2("5.2 Pilares de contenido (curar)")
    for t in [
        "Hero: promesa + CTA único (no competir con pilares).",
        "Prueba: diferenciales técnicos verificables (NVMe, seguridad, soporte) sin métricas inventadas.",
        "Ecosistemas: tabla precios COP + vínculo pfid cuando RCC esté mapeado.",
        "Confianza: logos/partners solo si hay acuerdo; eliminar testimonios genéricos.",
        "Legal: enlaces términos/privacidad coherentes con copy comercial.",
    ]:
        pdf.bullet(t)

    pdf.h2("5.3 Tablas y formatos (Elementor)")
    pdf.th4("Módulo", "Formato", "Criterio", "Done", (38.0, 42.0, 78.0, 27.0))
    ux_rows = [
        ("Comparativa planes", "Tabla responsive", "Máx. 4 columnas; contraste AA", "[ ]"),
        ("Pricing COP", "Número + texto", "Alineación decimal; sin decimales si COP entero", "[ ]"),
        ("FAQ comercial", "Acordeón o lista", "Schema FAQ ya en MU; no duplicar mentiras", "[ ]"),
        ("CTA primario", "Botón + microcopy", "Una acción por bloque", "[ ]"),
        ("Sticky mobile", "Barra inferior opcional", "No tapar CTA hero", "[ ]"),
    ]
    for i, (a, b, c, d) in enumerate(ux_rows):
        pdf.tr4(a, b, c, d, (38.0, 42.0, 78.0, 27.0), i % 2 == 1)

    pdf.h2("5.4 Fuentes de copy en repo")
    pdf.mono(
        [
            "memory/content/homepage-copy-2026-04.md",
            "memory/content/README-CONTENT-INDEX-2026.md",
            "memory/research/gano-wave3-brand-ux-master-brief.md",
            "wp-content/themes/gano-child/templates/shop-premium.php",
        ]
    )

    pdf.add_page()
    pdf.h2("5.5 Checklist UX (sesión)")
    chk = [
        "Menú primary asignado; labels ES-CO; destinos correctos.",
        "Sin Lorem; iconos reales en CTA final.",
        "Hero: imagen + attachment válidos; LCP hook coherente (ver elementor-home-classes).",
        "skip link #gano-main-content verificado.",
        "Shop: PENDING_RCC → pfid real antes de campaña paga.",
    ]
    for c in chk:
        pdf.bullet(c)

    # --- 6 GitHub
    pdf.add_page()
    pdf.h1("6. GitHub — workflows y agentes")
    pdf.p("12 workflows; 08 sembrar colas; 07 validar JSON; 09 homepage; 04/05/12 ops.")
    wf = [
        ("01", "CI PHP", "Gate", "push/PR"),
        ("02", "TruffleHog", "Secretos", "push/PR"),
        ("03", "Labeler", "PR", "sync PR"),
        ("04", "Deploy", "SSH", "push paths"),
        ("05", "Parches", "Ops", "manual"),
        ("06", "Labels", "Repo", "manual"),
        ("07", "Valida JSON", "Agentes", "push cola"),
        ("08", "Seed Copilot", "Agentes", "manual"),
        ("09", "Homepage issues", "Agentes", "manual"),
        ("10", "Orquestar", "Legacy", "manual"),
        ("11", "Setup Copilot", "Agentes", "PR yaml"),
        ("12", "wp-file-mgr", "Ops", "manual"),
    ]
    pdf.th4("ID", "Nombre", "Rol", "Cuando", (14.0, 52.0, 38.0, 73.0))
    for i, (a, b, c, d) in enumerate(wf):
        pdf.tr4(a, b, c, d, (14.0, 52.0, 38.0, 73.0), i % 2 == 1)

    # --- 7 Estrategia
    pdf.add_page()
    pdf.h1("7. Estrategia — líneas definidas")
    pdf.h2("7.1 Posicionamiento")
    pdf.bullet("Hosting WordPress Colombia; honestidad Reseller; sin simular datacenter propio.")
    pdf.bullet("Diferencial: seguridad + rendimiento + soporte en español; sin inflar métricas.")
    pdf.h2("7.2 Embudo")
    pdf.mono(
        [
            "Tráfico → Landing / SEO → Comparativa ecosistemas → CTA shop → RCC checkout → Onboarding",
        ]
    )
    pdf.h2("7.3 KPIs comerciales (seguimiento)")
    pdf.th4("KPI", "Meta sugerida", "Medición", "Frecuencia", (42.0, 42.0, 58.0, 43.0))
    kpis = [
        ("CTR CTA shop", "↑ vs baseline", "GA4 evento", "Semanal"),
        ("Tiempo checkout", "< 3 min", "RCC", "Por campaña"),
        ("Bounce hero", "< 45%", "GA4", "Semanal"),
        ("Errores 404", "0 críticos", "GSC", "Mensual"),
    ]
    for i, x in enumerate(kpis):
        pdf.tr4(*x, (42.0, 42.0, 58.0, 43.0), i % 2 == 1)

    # --- 8 Riesgos
    pdf.add_page()
    pdf.h1("8. Riesgos — matriz")
    pdf.th4("Riesgo", "P", "Impacto", "Mitigación", (48.0, 12.0, 28.0, 97.0))
    risks = [
        ("Deploy sin secrets", "H", "Paridad rota", "05 + 04"),
        ("wp-file-manager", "C", "Superficie ataque", "12"),
        ("Copy inflado", "M", "Marca", "content-audit"),
        ("DNS mal propagado", "M", "Caída", "script DNS + runbook"),
        ("Issues obsoletos GitHub", "B", "Ruido", "gh-issue-close"),
    ]
    for i, (a, b, c, d) in enumerate(risks):
        pdf.tr4(a, b, c, d, (48.0, 12.0, 28.0, 97.0), i % 2 == 1)

    # --- 9 Roadmap
    pdf.add_page()
    pdf.h1("9. Roadmap")
    for title, items in [
        ("7 días", ["Secrets + 05/04", "Cerrar Lorem crítico", "GSC propiedad"]),
        ("30 días", ["Checkout completo RCC", "2FA admin", "Rank Math fino"]),
        ("90 días", ["Portal cliente (decisión)", " soporte FreeScout", "Status page eval"]),
    ]:
        pdf.h2(title)
        for it in items:
            pdf.bullet(it)

    # --- 10 Apéndice A — estadísticas
    pdf.add_page()
    pdf.h1("10. Apéndice A — estadísticas repo")
    pdf.p("Agregados para auditoría; regenerar PDF tras cambios grandes.")
    n_mem = len(list((ROOT / "memory").rglob("*.md"))) if (ROOT / "memory").is_dir() else 0
    n_wf = len(list((ROOT / ".github" / "workflows").glob("*.yml")))
    n_plug = (
        len(list((ROOT / "wp-content" / "plugins").glob("gano-*")))
        if (ROOT / "wp-content" / "plugins").is_dir()
        else 0
    )
    stats = [
        ("Archivos .md en memory/", str(n_mem)),
        ("Workflows .yml", str(n_wf)),
        ("Plugins gano-* en repo", str(n_plug)),
    ]
    pdf.th4("Concepto", "Valor", "—", "—", (55.0, 35.0, 50.0, 45.0))
    for i, (a, b, c, d) in enumerate([(a, b, "", "") for a, b in stats]):
        pdf.tr4(a, b, c, d, (55.0, 35.0, 50.0, 45.0), i % 2 == 1)

    # --- 11 Apéndice B — glosario
    pdf.add_page()
    pdf.h1("11. Apéndice B — glosario lacónico")
    gloss = [
        ("RCC", "Reseller Control Center GoDaddy"),
        ("pfid", "ID producto Private Label / carrito"),
        ("MU", "Must-use plugin wp-content/mu-plugins"),
        ("SOTA", "Estado del arte; copy aspiracional sin mentir"),
        ("LCP", "Largest Contentful Paint; hero"),
    ]
    for term, d in gloss:
        pdf.set_font("GanoX", "B", 9)
        pdf.cell(35, 5, term)
        pdf.set_font("GanoX", "", 9)
        pdf.multi_cell(0, 5, d)
        pdf.ln(1)

    # --- 12 Apéndice C — relleno denso (garantía páginas)
    pdf.add_page()
    pdf.h1("12. Apéndice C — control de calidad contenido")
    pdf.p("Criterios para revisión interna antes de publicar bloques comerciales.")
    cq = [
        ("Tono", "Técnico-claro; Colombia; sin jerga vacía."),
        ("Hechos", "Cifra solo con fuente; si no hay fuente → cualitativo."),
        ("CTA", "Verbo + beneficio; sin “clic aquí” solamente."),
        ("Accesibilidad", "Contraste; focus visible; textos alternativos."),
        ("Performance", "Imágenes WebP donde aplique; lazy below fold."),
        ("Legal", "Enlaces políticas actualizados; cookies si aplica."),
    ]
    pdf.th4("Dimensión", "Criterio", "Pass", "Notas", (32.0, 95.0, 18.0, 40.0))
    for i, (a, b, c, d) in enumerate([(a, b, "[ ]", "") for a, b in cq]):
        pdf.tr4(a, b, c, d, (32.0, 95.0, 18.0, 40.0), i % 2 == 1)

    pdf.add_page()
    pdf.h1("13. Apéndice D — escenarios de prueba UX")
    scenarios = [
        "Desktop 1920: hero above fold; CTA visible sin scroll excesivo.",
        "Mobile 390: menú usable; tablas sin overflow horizontal destructivo.",
        "Checkout: un solo flujo hasta RCC; sin pasos muertos.",
        "404: plantilla on-brand; buscador o volver inicio.",
    ]
    for s in scenarios:
        pdf.bullet(s)

    pdf.add_page()
    pdf.h1("14. Apéndice E — mapa de dependencias (texto)")
    pdf.mono(
        [
            "CONTENIDO (memory/content) → ELEMENTOR → RCC (pfids) → ANALYTICS",
            "SEO MU (gano-seo) → RANK MATH panel → GSC",
            "CI (01/02) → PR → merge → 04 opcional",
        ]
    )

    # --- 15–18 Colas y contenido programático
    dtasks = load_dispatch_tasks()
    pdf.add_page()
    pdf.h1("15. Cola dispatch Claude (tareas cd-repo-*)")
    pdf.p("12 tareas versionadas; ejecutar con scripts/claude_dispatch.py.")
    pdf.th4("ID", "Título", "Prio", "Humano post", (26.0, 102.0, 14.0, 41.0))
    for i, (tid, title, pr, hum) in enumerate(dtasks):
        pdf.tr4(tid, title, str(pr), hum, (26.0, 102.0, 14.0, 41.0), i % 2 == 1)

    pdf.add_page()
    pdf.h1("16. Colas GitHub Copilot — tareas por JSON")
    aq = agent_queue_by_file()
    pdf.th4("Archivo", "Tareas", "% del total", "Notas", (62.0, 22.0, 22.0, 79.0))
    tot_t = sum(n for _, n in aq) or 1
    for i, (fn, n) in enumerate(aq):
        pct = 100.0 * n / tot_t
        note = "infra" if "infra" in fn else ("API" if "api" in fn else ("guard" if "security" in fn else ""))
        pdf.tr4(fn, str(n), f"{pct:.1f}%", note[:40], (62.0, 22.0, 22.0, 79.0), i % 2 == 1)

    pdf.add_page()
    pdf.h1("17. Páginas SOTA (gano-content-importer / estrategia)")
    pdf.p("Referencia editorial; alinear tono y CTA con Reseller, sin prometer infra propia.")
    pdf.th4("#", "Título", "Categoría", "Uso", (10.0, 88.0, 38.0, 49.0))
    cats = [
        "infra", "seguridad", "IA", "estrategia", "rendimiento",
        "seguridad", "infra", "infra", "infra", "seguridad",
        "rendimiento", "rendimiento", "rendimiento", "infra", "infra",
        "UX", "rendimiento", "infra", "analytics", "IA",
    ]
    for i, (title, cat) in enumerate(zip(SOTA_PAGE_TITLES, cats)):
        pdf.tr4(str(i + 1), title[:55], cat, "Blog/SEO", (10.0, 88.0, 38.0, 49.0), i % 2 == 1)

    pdf.add_page()
    pdf.h1("18. Matriz — mensaje × etapa de embudo")
    pdf.th4("Etapa", "Visitante", "Mensaje clave", "CTA", (28.0, 38.0, 58.0, 61.0))
    funnel = [
        ("Awareness", "Frío", "Problema: hosting lento/inseguro", "Leer pilares"),
        ("Consideración", "Tibio", "Solución: ecosistemas + Reseller", "Comparar planes"),
        ("Decisión", "Caliente", "COP claro; sin fricción", "Comprar en shop"),
        ("Post-compra", "Cliente", "Onboarding RCC", "Soporte / docs"),
    ]
    for i, row in enumerate(funnel):
        pdf.tr4(*row, (28.0, 38.0, 58.0, 61.0), i % 2 == 1)

    # --- 19–22 Git + memoria + más pendientes
    log_lines = git_short(["git", "log", "-35", "--oneline", "--no-decorate"])
    pdf.add_page()
    pdf.h1("19. Git reciente (35 líneas)")
    lines = [ln[:110] for ln in log_lines.splitlines() if ln.strip()][:35]
    if not lines:
        lines = ["(git no disponible en entorno de generación)"]
    pdf.mono(lines)

    pdf.add_page()
    pdf.h1("20. Pendientes TASKS — continuación")
    if len(pending) > 32:
        pdf.th4("ID", "Tarea", "Tipo", "Acción", (12.0, 92.0, 38.0, 43.0))
        for j, line in enumerate(pending[32:52], start=33):
            t = re.sub(r"\*\*", "", line)
            short = t[:110] + ("…" if len(t) > 110 else "")
            tipo = "Varios"
            if "SEO" in t:
                tipo = "SEO"
            if "RCC" in t or "Checkout" in t:
                tipo = "Comercio"
            pdf.tr4(str(j), short, tipo, "Revisar", (12.0, 92.0, 38.0, 43.0), j % 2 == 1)
    else:
        pdf.p("(Sin más ítems [ ] tras truncado inicial.)")

    mem_files = sorted({str(p.relative_to(ROOT)) for p in (ROOT / "memory").rglob("*.md")}) if (ROOT / "memory").is_dir() else []
    pdf.add_page()
    pdf.h1("21. Inventario memory/*.md (rutas)")
    chunk: list[str] = []
    for k, mpath in enumerate(mem_files[:90]):
        chunk.append(f"{k+1:02d}  {mpath}")
        if len(chunk) >= 42:
            pdf.mono(chunk)
            chunk = []
    if chunk:
        pdf.mono(chunk)
    if len(mem_files) > 90:
        pdf.p(f"… +{len(mem_files) - 90} archivos adicionales.")

    pdf.add_page()
    pdf.h1("22. Ecosistemas comerciales — recordatorio")
    pdf.th4("Ecosistema", "Rol copy", "Pricing", "CTA", (28.0, 52.0, 38.0, 67.0))
    eco = [
        ("Starter", "Entrada", "COP en schema", "shop"),
        ("Business", "PYME", "COP", "shop"),
        ("Premium", "Alto tráfico", "COP", "shop"),
        ("Enterprise", "Misión crítica", "COP", "shop"),
    ]
    for i, row in enumerate(eco):
        pdf.tr4(*row, (28.0, 52.0, 38.0, 67.0), i % 2 == 1)

    # --- 23–26 Densidad extra
    pdf.add_page()
    pdf.h1("23. Checklist sesión comercial (granular)")
    for idx in range(1, 29):
        pdf.bullet(f"[ ] Bloque {idx}: copy revisado; sin superlativos vacíos; CTA único.")

    pdf.add_page()
    pdf.h1("24. Objecciones — respuesta tipo")
    obj = [
        ("Caro", "TCO vs tiempo interno; RCC explícito.", "RCC / shop", "Descuento falso"),
        ("Ya tengo hosting", "Migración medible; prueba velocidad.", "Lighthouse", "Lock-in vago"),
        ("No confío", "Términos; marca Reseller.", "Legal", "Datos inventados"),
        ("Soporte", "Canal; expectativas.", "TASKS/docs", "SLA inventado"),
    ]
    pdf.th4("Objección", "Respuesta", "Evidencia", "No hacer", (28.0, 52.0, 38.0, 67.0))
    for i, (a, b, c, d) in enumerate(obj):
        pdf.tr4(a, b[:52], c[:22], d[:22], (28.0, 52.0, 38.0, 67.0), i % 2 == 1)

    pdf.add_page()
    pdf.h1("25. Variables de estilo — Elementor")
    pdf.th4("Token", "Uso", "Riesgo", "Acción", (28.0, 48.0, 38.0, 71.0))
    styles = [
        ("gano-el-*", "Layout", "Inconsistencia móvil", "Auditar breakpoints"),
        ("Tipografía", "Jerarquía", "Mezcla >3 familias", "Limitar a 2"),
        ("Color", "Marca", "Contraste bajo", "WCAG AA"),
        ("Espaciado", "Ritmo", "Secciones apiñadas", "Escala 8px"),
    ]
    for i, row in enumerate(styles):
        pdf.tr4(*row, (28.0, 48.0, 38.0, 71.0), i % 2 == 1)

    pdf.add_page()
    pdf.h1("26. Métricas de calidad (Definition of Done)")
    pdf.p("Definir antes de publicar bloque comercial.")
    for label, pct in [
        ("Coherencia marca", 0),
        ("Precision tecnica", 0),
        ("Accesibilidad", 0),
        ("Performance (LCP)", 0),
        ("Conversion (CTA)", 0),
    ]:
        pdf.bar(label + " (rellenar %)", pct)

    pdf.add_page()
    pdf.h1("27. Anexo: prompts utiles (Claude / Cursor)")
    prompts = [
        "Resume el bloque hero en 2 lineas + 1 CTA; tono CO; sin superlativos.",
        "Lista objeciones probables para hosting WP y respuesta de 1 linea cada una.",
        "Tabla 4 columnas: seccion Elementor | copy | CTA | estado [ ].",
        "Audita Lorem restante: grep mental por seccion homepage.",
        "Compara TASKS.md pendientes vs issues GitHub abiertos; sugerir cierres.",
    ]
    for p in prompts:
        pdf.bullet(p)
    pdf.h2("Salida esperada")
    pdf.p("Markdown estructurado o checklist; PR solo si toca archivos memory/ o tema.")

    pdf.add_page()
    pdf.h1("28. Control del informe PDF")
    pdf.th4("Campo", "Valor", "", "", (45.0, 45.0, 55.0, 42.0))
    pdf.tr4(
        "Script",
        "scripts/generate_handoff_claude_commercial_extended_pdf.py",
        "",
        "",
        (45.0, 45.0, 55.0, 42.0),
        False,
    )
    pdf.tr4("Dependencia", "fpdf2 (pip install fpdf2)", "", "", (45.0, 45.0, 55.0, 42.0), True)
    pdf.tr4("Regenerar", "python scripts/generate_handoff_claude_commercial_extended_pdf.py", "", "", (45.0, 45.0, 55.0, 42.0), False)
    pdf.p("Versionar el PDF en almacen interno; no commitear si politica ignora binarios.")

    pdf.h2("Cierre")
    pdf.set_font("GanoX", "I", 8)
    pdf.set_text_color(*C_MUTED)
    pdf.multi_cell(
        0,
        4,
        "Generado automáticamente. Validar cifras sensibles en GitHub y paneles. "
        "No sustituye asesoría legal/fiscal. Mínimo 30 páginas solicitado: si el render "
        "fuera menor, regenerar con más datos en TASKS/skills.",
    )

    out = OUT_DIR / f"Gano-Digital-Handoff-Claude-Comercial-{today.strftime('%Y-%m-%d')}.pdf"
    pdf.output(str(out))
    # fpdf2: page count
    n_pages = pdf.page_no()
    print(f"OK: {out} ({n_pages} páginas)")
    if n_pages < 30:
        print(f"ADVERTENCIA: {n_pages} < 30 páginas; ampliar contenido en script.", file=sys.stderr)
    return out


if __name__ == "__main__":
    build()
