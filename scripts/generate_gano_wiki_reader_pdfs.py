#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Genera un paquete de PDFs de lectura/navegación para el Gano-Wiki (OneDrive local).

Objetivo:
  - Hilo humano (qué se hizo, fases, pendientes).
  - Manual optimizado para IA (cómo consumir el wiki sin romper políticas).
  - Mapas por dominio (índice, ángulos, pedagogía).
  - Sin volcar secretos: la bóveda solo como README/INVENTORY en texto.

Uso (desde la raíz del repo Pilot):
  python scripts/generate_gano_wiki_reader_pdfs.py

Opcional:
  set GANO_WIKI_ROOT=C:\\ruta\\Gano-Wiki

Requisito: pip install fpdf2
Salida: %GANO_WIKI_ROOT%\\exports\\pdf-readers\\Gano-Wiki-*.pdf
"""
from __future__ import annotations

import os
import re
import sys
from datetime import date
from pathlib import Path

from fpdf import FPDF

ROOT = Path(__file__).resolve().parents[1]
DEFAULT_WIKI = Path(os.environ.get("GANO_WIKI_ROOT", r"C:\Users\diego\OneDrive\Documentos\Gano-Wiki"))
OUT_REL = Path("exports") / "pdf-readers"

_ARIAL = Path(r"C:\Windows\Fonts\arial.ttf")
_ARIAL_BOLD = Path(r"C:\Windows\Fonts\arialbd.ttf")
_ARIAL_I = Path(r"C:\Windows\Fonts\ariali.ttf")
_DEJAVU = Path("/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf")
_DEJAVU_BOLD = Path("/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf")
_DEJAVU_I = Path("/usr/share/fonts/truetype/dejavu/DejaVuSans-Oblique.ttf")
FONT = "GanoWikiReader"


def ensure_fonts(pdf: FPDF) -> str:
    if _ARIAL.is_file() and _ARIAL_BOLD.is_file():
        pdf.add_font(FONT, "", str(_ARIAL))
        pdf.add_font(FONT, "B", str(_ARIAL_BOLD))
        pdf.add_font(FONT, "I", str(_ARIAL_I if _ARIAL_I.is_file() else _ARIAL))
        return FONT
    if _DEJAVU.is_file():
        pdf.add_font(FONT, "", str(_DEJAVU))
        pdf.add_font(FONT, "B", str(_DEJAVU_BOLD if _DEJAVU_BOLD.is_file() else _DEJAVU))
        pdf.add_font(FONT, "I", str(_DEJAVU_I if _DEJAVU_I.is_file() else _DEJAVU))
        return FONT
    print("Fuentes no encontradas (Arial / DejaVu).", file=sys.stderr)
    raise SystemExit(1)


def read_text(path: Path) -> str:
    if not path.is_file():
        return f"[Archivo no encontrado: {path}]\n"
    return path.read_text(encoding="utf-8", errors="replace")


def strip_frontmatter(md: str) -> str:
    if not md.startswith("---"):
        return md
    parts = md.split("---", 2)
    if len(parts) >= 3:
        return parts[2].lstrip("\n")
    return md


def strip_emojis_for_pdf(s: str) -> str:
    """Arial en Windows no incluye muchos emoji; sustituir por ASCII legible."""
    repl = {
        "\u2705": "[OK]",
        "\u26a0": "[!]",
        "\u23ed": ">>",
        "\u2b50": "*",
        "\U0001f7e1": "[PEND]",
        "\U0001f535": "[FASE]",
        "\U0001f3af": "[META]",
        "\U0001f9ed": "[DOC]",
        "\U0001f5c2": "[CARP]",
        "\U0001f3db": "[DOM]",
        "\U0001f916": "[IA]",
        "\U0001f6e1": "[SEC]",
        "\U0001f5a7": "[INF]",
        "\U0001f4b3": "[COM]",
        "\U0001f3a8": "[UX]",
        "\U0001f3ad": "[PED]",
        "\ufe0f": "",
        "\u200d": "",
    }
    for a, b in repl.items():
        s = s.replace(a, b)
    # Quitar emoji restantes del plano suplementario (Arial no los trae)
    return "".join(ch for ch in s if ord(ch) < 0x1F000 or ord(ch) > 0x1FFFF)


def md_to_plain(md: str, max_chars: int | None = None) -> str:
    body = strip_frontmatter(md)
    body = body.replace("\r\n", "\n")
    body = strip_emojis_for_pdf(body)
    # code fences -> plain
    body = re.sub(r"^```[\s\S]*?^```", "[bloque de código omitido en PDF]\n", body, flags=re.MULTILINE)
    body = re.sub(r"^#{1,6}\s*(.*)$", r"\n\1\n", body, flags=re.MULTILINE)
    body = re.sub(r"\[([^\]]+)\]\(([^)]+)\)", r"\1 (\2)", body)
    body = re.sub(r"^\s*[-*]\s+", "• ", body, flags=re.MULTILINE)
    body = re.sub(r"\n{3,}", "\n\n", body)
    if max_chars and len(body) > max_chars:
        body = body[: max_chars - 40] + "\n\n[… contenido truncado para tamaño de PDF …]\n"
    return body.strip() or "(vacío)"


class ReaderPDF(FPDF):
    def __init__(self, title: str) -> None:
        super().__init__(unit="mm", format="A4")
        self.doc_title = title
        self.set_auto_page_break(auto=True, margin=14)
        self.set_margins(14, 14, 14)
        self.font_main = FONT

    def header(self) -> None:
        if self.page_no() == 1:
            return
        self.set_font(self.font_main, "", 8)
        self.set_text_color(90, 90, 90)
        self.cell(0, 4, self.doc_title, align="R")
        self.ln(6)
        self.set_text_color(0, 0, 0)

    def footer(self) -> None:
        self.set_y(-12)
        self.set_font(self.font_main, "", 8)
        self.set_text_color(120, 120, 120)
        self.cell(0, 8, f"Pág. {self.page_no()}", align="C")

    def cover(self, main: str, subtitle: str) -> None:
        self.add_page()
        self.set_font(self.font_main, "B", 20)
        self.set_text_color(18, 52, 86)
        self.multi_cell(0, 9, main)
        self.ln(4)
        self.set_font(self.font_main, "", 11)
        self.set_text_color(60, 60, 60)
        self.multi_cell(0, 5.5, subtitle)
        self.ln(6)
        self.set_text_color(0, 0, 0)
        self.set_font(self.font_main, "", 9)
        self.multi_cell(0, 5, f"Generado: {date.today().isoformat()}\nGano-Wiki (local OneDrive) · Paquete de lectura")

    def section(self, heading: str, body: str) -> None:
        self.set_font(self.font_main, "B", 11)
        self.set_text_color(27, 79, 216)
        self.multi_cell(0, 5.5, heading)
        self.ln(1)
        self.set_text_color(0, 0, 0)
        self.body_text(body)

    def body_text(self, text: str) -> None:
        self.set_font(self.font_main, "", 9)
        chunks = re.split(r"\n\n+", text)
        for ch in chunks:
            ch = ch.strip()
            if not ch:
                continue
            self.multi_cell(0, 4.7, ch)
            self.ln(1)


def write_pdf(wiki: Path, out_dir: Path, stem: str, title: str, parts: list[tuple[str, str]]) -> Path:
    out_dir.mkdir(parents=True, exist_ok=True)
    pdf = ReaderPDF(title)
    pdf.font_main = ensure_fonts(pdf)
    pdf.cover("Gano-Wiki", title)
    pdf.add_page()
    for head, body in parts:
        if pdf.get_y() > 255:
            pdf.add_page()
        pdf.section(head, body)
    out = out_dir / f"{stem}-{date.today().isoformat()}.pdf"
    pdf.output(str(out))
    return out


def tail_changelog(changelog: str, max_lines: int = 120) -> str:
    lines = changelog.splitlines()
    if len(lines) <= max_lines:
        return changelog
    return "\n".join(
        ["[Últimas líneas del CHANGELOG — ver archivo completo en el wiki]\n"]
        + lines[-max_lines:]
    )


def main() -> None:
    wiki = DEFAULT_WIKI
    if not wiki.is_dir():
        print(f"No existe GANO_WIKI_ROOT: {wiki}", file=sys.stderr)
        raise SystemExit(1)
    out_dir = wiki / OUT_REL
    out_dir.mkdir(parents=True, exist_ok=True)

    status = read_text(wiki / "STATUS-AND-ROADMAP.md")
    readme = read_text(wiki / "README.md")
    changelog = read_text(wiki / "CHANGELOG.md")
    agents = read_text(wiki / "AGENTS.md")
    index = read_text(wiki / "INDEX.md")
    angles = read_text(wiki / "content-production" / "angles-library.md")
    marketing = read_text(wiki / "content-production" / "strategy" / "marketing-execution-plan-2026-04.md")
    super_skill = read_text(wiki / "skills" / "super-skill" / "gano-digital-master.md")
    ped_readme = read_text(wiki / "pedagogy" / "README.md")
    cross = read_text(wiki / "pedagogy" / "cross-domain-learnings.md")
    android = read_text(wiki / "pedagogy" / "case-studies" / "android-hardening-privacy-biomonitor-2026-03.md")
    vault_readme = read_text(wiki / "_secrets-index" / "README.md")
    vault_inv = read_text(wiki / "_secrets-index" / "INVENTORY.md")
    manifest = read_text(wiki / "ingest-manifest.md")
    schemas = read_text(wiki / "_schemas" / "article.yaml")

    generated: list[Path] = []

    generated.append(
        write_pdf(
            wiki,
            out_dir,
            "Gano-Wiki-00-Hilo-global-y-fases",
            "00 · Hilo global, fases y pendientes",
            [
                ("README (entrada)", md_to_plain(readme, 4500)),
                ("STATUS-AND-ROADMAP", md_to_plain(status, 12000)),
                ("CHANGELOG (cola)", md_to_plain(tail_changelog(changelog), 14000)),
            ],
        )
    )

    generated.append(
        write_pdf(
            wiki,
            out_dir,
            "Gano-Wiki-01-Manual-para-IA",
            "01 · Manual de consumo para IA (embedding-ready)",
            [
                ("AGENTS.md del wiki", md_to_plain(agents, 16000)),
                ("Super-skill (extracto)", md_to_plain(super_skill, 18000)),
                ("Frontmatter (_schemas/article.yaml)", md_to_plain(schemas, 8000)),
            ],
        )
    )

    generated.append(
        write_pdf(
            wiki,
            out_dir,
            "Gano-Wiki-02-Mapa-navegacion-INDEX",
            "02 · Mapa de navegación (INDEX + taxonomía)",
            [
                ("INDEX.md", md_to_plain(index, 22000)),
            ],
        )
    )

    generated.append(
        write_pdf(
            wiki,
            out_dir,
            "Gano-Wiki-03-Contenido-angulos-y-marketing",
            "03 · Ángulos de contenido y estrategia",
            [
                ("angles-library.md", md_to_plain(angles, 10000)),
                ("marketing-execution-plan", md_to_plain(marketing, 16000)),
            ],
        )
    )

    generated.append(
        write_pdf(
            wiki,
            out_dir,
            "Gano-Wiki-04-Pedagogia-y-casos",
            "04 · Pedagogía, casos y aprendizajes cruzados",
            [
                ("pedagogy/README", md_to_plain(ped_readme, 4000)),
                ("cross-domain-learnings", md_to_plain(cross, 10000)),
                ("Caso Android (síntesis)", md_to_plain(android, 16000)),
            ],
        )
    )

    man_head = manifest[:15000] + "\n\n[… manifest completo en Gano-Wiki/ingest-manifest.md …]\n"
    generated.append(
        write_pdf(
            wiki,
            out_dir,
            "Gano-Wiki-05-Ingest-manifest-resumen",
            "05 · Plan de ingestión (extracto)",
            [
                ("ingest-manifest.md (extracto)", md_to_plain(man_head, None)),
            ],
        )
    )

    generated.append(
        write_pdf(
            wiki,
            out_dir,
            "Gano-Wiki-06-Boveda-indice-sin-datos",
            "06 · Bóveda _secrets-index (solo índice, sin secretos)",
            [
                ("Política e inventario", md_to_plain(vault_readme + "\n\n" + vault_inv, 12000)),
            ],
        )
    )

    readme_pack = out_dir / "README.md"
    readme_pack.write_text(
        """# Paquete PDF — Gano-Wiki (lectura y estudio)

Orden sugerido para **seguir el hilo** de lo construido:

1. **00-Hilo-global-y-fases** — Qué es el wiki, fases 1–5, estado y bitácora reciente.
2. **01-Manual-para-IA** — Cómo deben leerlo las IAs: AGENTS, super-skill, frontmatter.
3. **02-Mapa-navegacion-INDEX** — Catálogo por carpetas y archivos.
4. **03-Contenido-angulos-y-marketing** — Munición editorial + plan de marketing.
5. **04-Pedagogia-y-casos** — Caso Android pedagógico + patrones entre dominios.
6. **05-Ingest-manifest-resumen** — Backlog de fuentes (extracto).
7. **06-Boveda-indice-sin-datos** — Qué hay en `_secrets-index/` sin copiar credenciales al PDF.

**Optimizado para IA:** las IAs deben seguir siempre los `.md` con YAML; estos PDF son para **Diego** (lectura offline, tablet, impresión) y para **briefing rápido** a humanos.

**Regenerar:** desde `Pilot`:

```text
python scripts/generate_gano_wiki_reader_pdfs.py
```

Opcional: `set GANO_WIKI_ROOT=ruta\\Gano-Wiki`
""",
        encoding="utf-8",
    )

    print("PDFs generados en:", out_dir)
    for p in generated:
        print(" -", p.name)
    print("Léeme:", readme_pack)


if __name__ == "__main__":
    main()
