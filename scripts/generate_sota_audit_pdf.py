#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Genera un PDF de auditoría SOTA con estado real del repo y producción.

Salida:
  reports/Gano-Digital-Auditoria-SOTA-YYYY-MM-DD.pdf
"""

from __future__ import annotations

import os
import ssl
import subprocess
import sys
import urllib.error
import urllib.request
from datetime import date
from pathlib import Path

from fpdf import FPDF

ROOT = Path(__file__).resolve().parents[1]
OUT_DIR = ROOT / "reports"
OUT_DIR.mkdir(exist_ok=True)
FONT = "SotaAudit"


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
    print("Fuentes no encontradas (Arial/DejaVu).", file=sys.stderr)
    raise SystemExit(1)


def sh(cmd: list[str]) -> str:
    try:
        return subprocess.check_output(cmd, cwd=ROOT, text=True, stderr=subprocess.STDOUT, timeout=30).strip()
    except subprocess.CalledProcessError as exc:
        return f"ERROR({exc.returncode}): {exc.output.strip()}"
    except (FileNotFoundError, subprocess.TimeoutExpired) as exc:
        return f"ERROR: {exc}"


def http_status(url: str) -> str:
    req = urllib.request.Request(url, headers={"User-Agent": "Gano-SOTA-Audit/1.0"})
    ctx = ssl.create_default_context()
    try:
        with urllib.request.urlopen(req, timeout=20, context=ctx) as resp:
            return f"{resp.status}"
    except urllib.error.HTTPError as exc:
        return f"{exc.code}"
    except Exception as exc:  # noqa: BLE001
        return f"ERR: {type(exc).__name__}"


class Pdf(FPDF):
    def __init__(self) -> None:
        super().__init__(unit="mm", format="A4")
        self.set_auto_page_break(auto=True, margin=14)
        self.set_margins(12, 12, 12)

    def h1(self, t: str) -> None:
        self.set_font(FONT, "B", 14)
        self.set_text_color(18, 52, 86)
        self.multi_cell(0, 7, t)
        self.set_text_color(0, 0, 0)
        self.ln(1)

    def h2(self, t: str) -> None:
        self.set_font(FONT, "B", 11)
        self.set_text_color(41, 88, 120)
        self.multi_cell(0, 6, t)
        self.set_text_color(0, 0, 0)

    def p(self, t: str) -> None:
        self.set_font(FONT, "", 9)
        self.multi_cell(self.epw, 4.8, _safe_text(t))
        self.ln(0.4)

    def bullet(self, t: str) -> None:
        self.set_font(FONT, "", 9)
        self.multi_cell(self.epw, 4.6, f"- {_safe_text(t)}")
        self.ln(0.2)


def _safe_text(text: str, max_len: int = 260) -> str:
    clean = "".join(ch if ch.isprintable() else " " for ch in text)
    clean = clean.replace("\t", " ").replace("\r", " ").replace("\n", " ")
    clean = " ".join(clean.split())
    return clean[:max_len]


def build() -> Path:
    regular, bold, oblique = ensure_fonts()
    pdf = Pdf()
    pdf.add_font(FONT, "", regular)
    pdf.add_font(FONT, "B", bold)
    pdf.add_font(FONT, "I", oblique)

    today = date.today().isoformat()
    head = sh(["git", "rev-parse", "--short", "HEAD"])
    branch = sh(["git", "rev-parse", "--abbrev-ref", "HEAD"])
    status_short = sh(["git", "status", "--short"])

    lint_targets = [
        "wp-content/themes/gano-child/functions.php",
        "wp-content/themes/gano-child/templates/shop-premium.php",
        "wp-content/themes/gano-child/templates/page-seo-landing.php",
        "wp-content/themes/gano-child/templates/page-diagnostico-digital.php",
    ]
    lint_results: list[str] = []
    for target in lint_targets:
        out = sh(["php", "-l", target])
        lint_results.append(f"{target}: {'OK' if 'No syntax errors' in out else out[:140]}")

    urls = [
        "https://gano.digital/",
        "https://gano.digital/ecosistemas/",
        "https://gano.digital/contacto/",
        "https://gano.digital/shop-premium/",
        "https://gano.digital/diagnostico-digital/",
    ]
    http_results = [(u, http_status(u)) for u in urls]

    pdf.add_page()
    pdf.h1("Gano Digital — Auditoría SOTA de implementación")
    pdf.p(f"Fecha: {today}")
    pdf.p(f"Repo: Gano-digital/Pilot | Rama: {branch} | HEAD: {head}")

    pdf.h2("1) Verificación de implementación (evidencia)")
    pdf.bullet("Se ejecutó inspección de estado git local.")
    pdf.bullet("Se ejecutó lint PHP sobre archivos SOTA críticos.")
    pdf.bullet("Se validó estado HTTP de páginas clave en producción.")

    pdf.h2("2) Resultado técnico local (repo)")
    for line in lint_results:
        pdf.bullet(line)

    pdf.h2("3) Resultado en producción (gano.digital)")
    for url, code in http_results:
        pdf.bullet(f"{code}  {url}")

    pdf.h2("4) Hallazgos y brechas")
    prod_shop = dict(http_results).get("https://gano.digital/shop-premium/", "")
    prod_diag = dict(http_results).get("https://gano.digital/diagnostico-digital/", "")
    if prod_shop == "404" or prod_diag == "404":
        pdf.bullet("Brecha crítica: páginas SOTA aún no publicadas en producción (404).")
    pdf.bullet("El repo contiene integración SOTA y catálogo canónico; falta despliegue y publicación en wp-admin.")
    pdf.bullet("Antes de cierre comercial: ejecutar 04 Deploy, 05 Verify patches y smoke test RCC checkout.")

    pdf.h2("5) Pendiente inmediato recomendado")
    pdf.bullet("Configurar acceso SSH operativo (variables/local o secrets CI).")
    pdf.bullet("Desplegar theme actualizado y crear/asignar templates SOTA faltantes.")
    pdf.bullet("Validar estados active/pending/coming-soon en shop-premium y CTAs de dominio/VPS.")
    pdf.bullet("Documentar resultado final en TASKS.md + activeContext + progress.")

    pdf.add_page()
    pdf.h2("Anexo: git status --short")
    if not status_short:
        pdf.p("Working tree limpio.")
    else:
        for row in status_short.splitlines()[:180]:
            pdf.bullet(row)

    out = OUT_DIR / f"Gano-Digital-Auditoria-SOTA-{today}.pdf"
    pdf.output(str(out))
    return out


if __name__ == "__main__":
    path = build()
    print(path)
