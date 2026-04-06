#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Reporte compacto de estado del proyecto Gano Digital (Pilot) — PDF denso, pocas páginas.
Incluye: resuelto recientemente, pendientes, % por pilar y avance global, sin huecos grandes.

Salida: reports/Gano-Digital-Reporte-Status-Proyecto-YYYY-MM-DD.pdf
Requisito: pip install fpdf2
"""
from __future__ import annotations

import os
import subprocess
import sys
from datetime import date
from pathlib import Path

from fpdf import FPDF

ROOT = Path(__file__).resolve().parents[1]
OUT_DIR = ROOT / "reports"
OUT_DIR.mkdir(exist_ok=True)
FONT = "GanoStatus"


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


def git_head() -> str:
    try:
        return subprocess.check_output(
            ["git", "rev-parse", "--short", "HEAD"],
            cwd=ROOT,
            stderr=subprocess.DEVNULL,
            timeout=10,
        ).decode().strip()
    except (subprocess.CalledProcessError, FileNotFoundError, OSError):
        return "n/d"


class CompactPDF(FPDF):
    def __init__(self) -> None:
        super().__init__(unit="mm", format="A4")
        self.set_auto_page_break(auto=True, margin=14)
        self.set_margins(12, 12, 12)

    def header(self) -> None:
        if self.page_no() == 1:
            return
        self.set_font(FONT, "", 8)
        self.set_text_color(90, 90, 90)
        self.cell(0, 4, "Gano Digital | Reporte status proyecto | Pilot (main)", align="R")
        self.ln(5)
        self.set_draw_color(210, 210, 210)
        self.line(self.l_margin, self.get_y(), self.w - self.r_margin, self.get_y())
        self.ln(4)
        self.set_text_color(0, 0, 0)

    def footer(self) -> None:
        self.set_y(-12)
        self.set_font(FONT, "", 8)
        self.set_text_color(120, 120, 120)
        self.cell(0, 8, f"Pág. {self.page_no()}", align="C")

    def h1(self, t: str) -> None:
        self.ln(2)
        self.set_font(FONT, "B", 12)
        self.set_text_color(18, 52, 86)
        self.multi_cell(0, 5.5, t)
        self.set_text_color(0, 0, 0)
        self.ln(1)

    def h2(self, t: str) -> None:
        self.set_font(FONT, "B", 10)
        self.set_text_color(41, 88, 120)
        self.multi_cell(0, 5, t)
        self.set_text_color(0, 0, 0)
        self.ln(0.5)

    def p(self, text: str, lh: float = 4.8) -> None:
        self.set_font(FONT, "", 9)
        self.multi_cell(0, lh, text)
        self.ln(1)

    def bullet(self, text: str) -> None:
        self.set_font(FONT, "", 9)
        self.multi_cell(0, 4.6, f"\u2022 {text}")
        self.ln(0.3)

    def bar(self, label: str, pct: float, w_bar: float = 100) -> None:
        y = self.get_y()
        self.set_font(FONT, "", 8)
        self.cell(52, 4.5, label[:38])
        x = self.get_x()
        h = 4
        self.set_fill_color(235, 238, 242)
        self.rect(x, y, w_bar, h, "F")
        fw = max(0.0, min(w_bar, w_bar * pct / 100.0))
        self.set_fill_color(41, 128, 185)
        if fw > 0:
            self.rect(x, y, fw, h, "F")
        self.set_xy(x + w_bar + 2, y)
        self.set_font(FONT, "B", 8)
        self.cell(14, 4.5, f"{pct:.0f}%")
        self.ln(6)


def build() -> Path:
    regular, bold, oblique = ensure_fonts()
    pdf = CompactPDF()
    pdf.add_font(FONT, "", regular)
    pdf.add_font(FONT, "B", bold)
    pdf.add_font(FONT, "I", oblique)

    today = date.today()
    meses = (
        "",
        "enero",
        "febrero",
        "marzo",
        "abril",
        "mayo",
        "junio",
        "julio",
        "agosto",
        "septiembre",
        "octubre",
        "noviembre",
        "diciembre",
    )
    fecha = f"{today.day} de {meses[today.month]} de {today.year}"
    commit = git_head()

    # — Portada compacta
    pdf.add_page()
    epw = pdf.epw
    pdf.set_font(FONT, "B", 18)
    pdf.set_text_color(18, 52, 86)
    pdf.ln(22)
    pdf.set_x(pdf.l_margin)
    pdf.multi_cell(epw, 8, "Gano Digital", align="C")
    pdf.set_font(FONT, "B", 13)
    pdf.set_text_color(50, 50, 50)
    pdf.set_x(pdf.l_margin)
    pdf.multi_cell(epw, 6, "Reporte de status del proyecto", align="C")
    pdf.set_font(FONT, "", 10)
    pdf.ln(4)
    pdf.set_x(pdf.l_margin)
    pdf.multi_cell(epw, 5, f"Repositorio: Gano-digital/Pilot | Rama: main | Commit: {commit}", align="C")
    pdf.set_font(FONT, "I", 9)
    pdf.set_x(pdf.l_margin)
    pdf.multi_cell(epw, 5, fecha, align="C")
    pdf.set_text_color(0, 0, 0)
    pdf.ln(10)
    pdf.set_font(FONT, "", 9)
    pdf.p(
        "Documento denso: logros recientes, brecha pendiente, porcentajes por pilar y lista priorizada. "
        "Los % son estimaciones de madurez para comunicación interna (no métricas financieras)."
    )

    # — Página 2: avance + pilares
    pdf.add_page()
    pdf.h1("1. Avance global (estimado)")
    pdf.p(
        "Fórmula: promedio ponderado — Código en Git (25%), Paridad servidor/producción (25%), "
        "Contenido y SEO vivo (20%), Comercio Fase 4 Reseller (15%), Gobierno GitHub/proceso (15%)."
    )
    pdf.set_font(FONT, "B", 10)
    pdf.set_text_color(180, 90, 30)
    pdf.multi_cell(0, 5, "Avance global del programa: ~47 %")
    pdf.set_text_color(0, 0, 0)
    pdf.ln(2)
    pdf.h2("Barras por pilar")
    # Barras elegidas para que el promedio ponderado (25/25/20/15/15) ≈ 47 %
    pdf.bar("Código F1–F3 + tooling en repo", 90)
    pdf.bar("Servidor: deploy, parches, wp-file-manager", 15)
    pdf.bar("Contenido Elementor + copy en vivo", 24)
    pdf.bar("Fase 4: RCC, checkout Reseller, pruebas", 20)
    pdf.bar("GitHub: CI, colas, Projects, documentación ops", 88)

    pdf.h1("2. Resuelto o consolidado (abr–abr 2026)")
    pdf.bullet(
        "Fases 1–3 en repositorio: seguridad base, CSP/rate limit, MU gano-seo + CWV en gano-child (TASKS.md completado en git)."
    )
    pdf.bullet(
        "Oleadas Copilot: consolidación PRs en main; documentación oleada 4, infra DNS/HTTPS y runbooks en memory/ops/."
    )
    pdf.bullet(
        "GitHub Pilot: ruleset Gano.digital activo; colas JSON validadas (07); workflows 01–13 documentados; sin remoto GitLab."
    )
    pdf.bullet(
        "Dependabot: PRs de actions/esbuild/claude-agent-sdk/vitest fusionados; tests SDK Vitest 4 + mocks GSDEventStream/GSDTools."
    )
    pdf.bullet(
        "Corrección sintaxis PHP en gano-content-importer (strings DDoS); playbook Projects @Gano.digital (RACI, DoR/DoD, Area/Source)."
    )
    pdf.bullet(
        "Workflow 13 opcional (añadir issues [agent]/copilot al tablero); tooling Cursor: extensiones, EditorConfig, skill MCP."
    )
    pdf.bullet("Copilot org: instrucciones personalizadas en GitHub; referencia skill MCP Claude en .gano-skills/_reference-claude-official/.")
    pdf.bullet("Estado GitHub: 0 PRs abiertos (referencia al momento de generar este PDF).")

    pdf.h1("3. Pendiente de implementación (priorizado)")
    rows = [
        ("P0", "Secrets SSH + SERVER_HOST/USER/DEPLOY_PATH; ejecutar 05 Verificar parches o 04 Deploy."),
        ("P0", "Eliminar wp-file-manager en producción (workflow 12 o manual); validar alerta gano-security."),
        ("P1", "Sincronizar F1–F3 al servidor (child, MU-plugins; wp-config por canal seguro fuera de git)."),
        ("P1", "Menú primary + homepage: aplicar memory/content/homepage-copy y clases Elementor en wp-admin."),
        ("P1", "Gano SEO + Rank Math + Google Search Console (propiedad gano.digital)."),
        ("P1", "Quitar Lorem/testimonios no verificados; página Nosotros real; RCC/pfids y prueba checkout Reseller."),
        ("P2", "Cerrar issues GitHub obsoletos ya cubiertos por main; triage Dependabot (alerta moderada)."),
        ("P2", "Sembrar 08: tasks-api-integrations-research o tasks-security-guardian si aplica; 09 homepage si faltan 7 issues."),
        ("P2", "Variable GANO_PROJECT_URL + PAT para workflow 13 si se desea auto-añadir al Project."),
        ("P3", "Fase 5: status page, chat IA con LLM, afiliados (TASKS Someday)."),
    ]
    pdf.set_font(FONT, "B", 8)
    pdf.cell(12, 4, "Pri.")
    pdf.cell(176, 4, "Tarea")
    pdf.ln(5)
    pdf.set_font(FONT, "", 8)
    for pr, tx in rows:
        y0 = pdf.get_y()
        pdf.set_xy(pdf.l_margin, y0)
        pdf.multi_cell(14, 4, pr)
        h1 = pdf.get_y() - y0
        pdf.set_xy(pdf.l_margin + 14, y0)
        pdf.multi_cell(pdf.w - pdf.l_margin - pdf.r_margin - 14, 4, tx)
        h2 = pdf.get_y() - y0
        pdf.set_y(y0 + max(h1, h2, 4) + 0.5)

    # — Página 3: riesgos + próximos pasos + refs (sin bloques vacíos)
    pdf.add_page()
    pdf.h1("4. Riesgos y dependencias")
    pdf.bullet("Drift: código en main no reflejado en hosting hasta deploy o SFTP manual.")
    pdf.bullet("Vercel: check en PR puede fallar (org privada + plan Hobby); CI TruffleHog/php-lint pueden estar verdes.")
    pdf.bullet("Trabajo solo Elementor no versionado: documentar en issues y status updates del Project @Gano.digital.")

    pdf.h1("5. Próximos 30 días (recomendado)")
    pdf.bullet("Semana 1: secrets + 05/04 + 12 wp-file-manager.")
    pdf.bullet("Semana 2–3: homepage + SEO panel + GSC.")
    pdf.bullet("Semana 3–4: RCC/checkout end-to-end + cierre issues; status update semanal en Project.")

    pdf.h1("6. Referencias en repo")
    pdf.set_font(FONT, "", 8)
    refs = (
        "TASKS.md | .github/DEV-COORDINATION.md | memory/ops/github-projects-gano-digital-playbook-2026-04.md | "
        ".github/workflows/README.md | memory/ops/github-github-ops-sota-2026-04.md | CLAUDE.md"
    )
    pdf.multi_cell(0, 4, refs)
    pdf.ln(2)
    pdf.set_font(FONT, "I", 7)
    pdf.set_text_color(80, 80, 80)
    pdf.multi_cell(
        0,
        3.5,
        f"Generado por scripts/generate_project_status_pdf.py | {fecha} | Regenerar: python scripts/generate_project_status_pdf.py",
    )

    out = OUT_DIR / f"Gano-Digital-Reporte-Status-Proyecto-{today.strftime('%Y-%m-%d')}.pdf"
    pdf.output(str(out))
    return out


if __name__ == "__main__":
    print(build())
