#!/usr/bin/env python3
"""
Genera PDF de auditoría para desarrolladores (Gano Digital).
Ejecutar desde la raíz del repositorio:

  python scripts/generate_dev_audit_pdf.py

Salida: memory/audits/gano-digital-auditoria-desarrolladores-YYYY-MM.pdf
Requiere: pip install fpdf2
"""
from __future__ import annotations

from datetime import date
from pathlib import Path

from fpdf import FPDF

ROOT = Path(__file__).resolve().parents[1]
# TrueType con Unicode (es-ES); fallback Helvetica = solo ASCII en textos
_ARIAL = Path(r"C:\Windows\Fonts\arial.ttf")
_ARIAL_BOLD = Path(r"C:\Windows\Fonts\arialbd.ttf")
_DEJAVU = Path("/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf")
_DEJAVU_BOLD = Path("/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf")
FONT_DOC = "GanoDoc"


def setup_doc_fonts(pdf: FPDF) -> str:
    if _ARIAL.is_file():
        pdf.add_font(FONT_DOC, "", str(_ARIAL))
        pdf.add_font(FONT_DOC, "B", str(_ARIAL_BOLD if _ARIAL_BOLD.is_file() else _ARIAL))
        return FONT_DOC
    if _DEJAVU.is_file():
        pdf.add_font(FONT_DOC, "", str(_DEJAVU))
        pdf.add_font(FONT_DOC, "B", str(_DEJAVU_BOLD if _DEJAVU_BOLD.is_file() else _DEJAVU))
        return FONT_DOC
    return "Helvetica"
AUD = ROOT / "memory" / "audits"
CONST = ROOT / "memory" / "constellation" / "CONSTELACION-COSMICA.html"
PORTRAITS = ROOT / "memory" / "constellation" / "assets" / "portraits"
SFX_DIR = ROOT / "memory" / "constellation" / "sounds" / "agentcraft"
SKILLS_ROOT = ROOT / ".gano-skills"


def count_lines(p: Path) -> int:
    if not p.is_file():
        return 0
    return len(p.read_text(encoding="utf-8", errors="replace").splitlines())


def count_wavs() -> int:
    if not SFX_DIR.is_dir():
        return 0
    return len(list(SFX_DIR.glob("*.wav")))


def count_skills() -> int:
    if not SKILLS_ROOT.is_dir():
        return 0
    n = 0
    for p in SKILLS_ROOT.iterdir():
        if (
            p.is_dir()
            and not p.name.startswith("_")
            and (p / "SKILL.md").is_file()
        ):
            n += 1
    return n


class AuditPDF(FPDF):
    def footer(self) -> None:
        self.set_y(-15)
        f = getattr(self, "doc_font", "Helvetica")
        self.set_font(f, "", 8)
        self.cell(0, 10, f"Página {self.page_no()}", align="C")


def add_wrapped(pdf: FPDF, font: str, text: str, size: int = 10) -> None:
    pdf.set_font(font, "", size)
    pdf.multi_cell(pdf.epw, 5.5, text)


def main() -> None:
    AUD.mkdir(parents=True, exist_ok=True)
    today = date.today().isoformat()
    lines_const = count_lines(CONST)
    n_wav = count_wavs()
    n_skills = count_skills()

    pdf = AuditPDF()
    pdf.doc_font = setup_doc_fonts(pdf)
    font = pdf.doc_font
    pdf.set_auto_page_break(auto=True, margin=18)

    # --- Portada
    pdf.add_page()
    pdf.set_font(font, "B", 22)
    pdf.cell(0, 14, "Gano Digital", new_x="LMARGIN", new_y="NEXT", align="C")
    pdf.set_font(font, "B", 14)
    pdf.cell(0, 10, "Auditoría técnica — Equipo de desarrollo", new_x="LMARGIN", new_y="NEXT", align="C")
    pdf.ln(6)
    pdf.set_font(font, "", 11)
    pdf.cell(0, 8, f"Fecha de generación: {today}", align="C", new_x="LMARGIN", new_y="NEXT")
    pdf.cell(0, 8, "Alcance: repositorio monorepo (WordPress + herramientas + Constellation)", align="C", new_x="LMARGIN", new_y="NEXT")
    pdf.ln(10)

    add_wrapped(
        pdf,
        font,
        "Este documento consolida capacidades recientes, métricas del mapa galáctico interactivo, "
        "activos visuales y sonoros integrados, reglas de trabajo en Cursor, y un extracto del roadmap "
        "pendiente. La fuente viva de tareas sigue siendo TASKS.md en la raíz del repositorio.",
        10,
    )
    pdf.ln(4)

    # --- Estadísticas
    pdf.set_font(font, "B", 13)
    pdf.cell(0, 9, "1. Estadísticas clave (repositorio)", new_x="LMARGIN", new_y="NEXT")
    pdf.set_font(font, "", 10)
    rows = [
        ("Líneas (aprox.) CONSTELACION-COSMICA.html", str(lines_const)),
        ("Muestras de audio HUD (WAV en sounds/agentcraft)", str(n_wav)),
        ("Skills documentadas (.gano-skills/*/SKILL.md)", str(n_skills)),
        ("Retratos facción (PNG)", "3 (Terran / Zerg / Protoss)"),
        ("Cuerpos orbitando (SYSTEMS + núcleo)", "1 estrella + 8 sistemas"),
    ]
    col_w = pdf.epw / 2
    for label, val in rows:
        pdf.cell(col_w, 7, label, border=1)
        pdf.cell(col_w, 7, val, border=1, new_x="LMARGIN", new_y="NEXT")
    pdf.ln(6)
    pdf.set_x(pdf.l_margin)

    # --- Capacidades nuevas
    pdf.set_font(font, "B", 13)
    pdf.cell(0, 9, "2. Capacidades nuevas (Constellation & procesos)", new_x="LMARGIN", new_y="NEXT")
    bullets = [
        "Mapa WebGL con órbitas, tooltip, panel tipo briefing (HUD híbrido RTS + diegético).",
        "Interacción: syncPointer en pointerdown/move/click; picks con proximidad; UI excluida del raycast.",
        "Audio: pools por facción (hover, selección/ataque, brief por estado) desde AgentCraft curado.",
        "Skill gano-starcraft1-assets-constellation + investigación memory/research/starcraft1-assets-sota.md.",
        "Regla Cursor .cursor/rules/102-constellation-and-gano-skills.mdc para alinear cambios con TASKS/CLAUDE.",
        "Referencia prensa Blizzard (sin redistribuir assets) en blizzard-press-reference.txt.",
    ]
    pdf.set_font(font, "", 10)
    for b in bullets:
        pdf.multi_cell(pdf.epw, 5.5, f"- {b}")
    pdf.ln(3)

    # --- Activos visuales
    pdf.set_font(font, "B", 13)
    pdf.cell(0, 9, "3. Activos visuales (retratos HUD)", new_x="LMARGIN", new_y="NEXT")
    add_wrapped(
        pdf,
        font,
        "Tres retratos estilo worker por facción (PNG pixel-art), alineados a taxonomía Terran / Zerg / Protoss. "
        "La animación en el panel usa CSS (portraitLive); no se incluyen secuencias GRP del juego original en el repo.",
        10,
    )
    pdf.ln(2)
    y0 = pdf.get_y()
    x = pdf.l_margin
    names = [
        "portrait-worker-terran.png",
        "portrait-worker-zerg.png",
        "portrait-worker-protoss.png",
    ]
    for i, fn in enumerate(names):
        path = PORTRAITS / fn
        if path.is_file():
            try:
                pdf.image(str(path), x=x + i * 58, y=y0, w=28)
            except Exception:
                pdf.set_xy(x, y0 + i * 3)
                pdf.set_font(font, "", 9)
                pdf.cell(50, 6, f"[{fn} no renderizado]")
    pdf.set_y(y0 + 34)
    pdf.ln(4)

    # --- Roadmap extracto
    pdf.set_font(font, "B", 13)
    pdf.cell(0, 9, "4. Roadmap pendiente (extracto - ver TASKS.md)", new_x="LMARGIN", new_y="NEXT")
    roadmap = [
        "Servidor: sincronizar parches F1-F3; eliminar wp-file-manager; SEO/GSC/Rank Math.",
        "Contenido: reemplazar placeholder; página Nosotros; limpieza plugins de fase tras validación.",
        "Fase 4 comercial: RCC Reseller, mapeo CTAs shop-premium, flujo checkout, soporte (FreeScout u otro).",
        "GitHub: colas opcionales (API ML+GoDaddy, security guardian) según TASKS.md.",
        "Someday: status page, chat IA real, afiliados.",
    ]
    pdf.set_font(font, "", 10)
    for r in roadmap:
        pdf.multi_cell(pdf.epw, 5.5, f"- {r}")
    pdf.ln(4)

    # --- Cierre
    pdf.set_font(font, "B", 13)
    pdf.cell(0, 9, "5. Cómo mantener viva esta auditoría", new_x="LMARGIN", new_y="NEXT")
    add_wrapped(
        pdf,
        font,
        "Regenerar este PDF tras hitos grandes: python scripts/generate_dev_audit_pdf.py. "
        "Actualizar TASKS.md y CLAUDE.md como fuentes de verdad. No commitear vendor/ ni .obsidian/ (gitignore).",
        10,
    )

    out = AUD / f"gano-digital-auditoria-desarrolladores-{today}.pdf"
    pdf.output(str(out))
    print(f"Escrito: {out.relative_to(ROOT)}")


if __name__ == "__main__":
    main()
