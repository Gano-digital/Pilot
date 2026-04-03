#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Genera reporte ejecutivo PDF (junta laboral) — Gano Digital.
Salida: reports/Gano-Digital-Reporte-Estado-YYYY-MM.pdf

Requisito: pip install fpdf2

Fuentes: Arial en Windows (C:\\Windows\\Fonts) o DejaVu en Linux
(apt install fonts-dejavu-core). macOS: Arial en /Library/Fonts si existe.
"""
from __future__ import annotations

import os
import sys
from datetime import date
from pathlib import Path

from fpdf import FPDF

ROOT = Path(__file__).resolve().parents[1]
OUT_DIR = ROOT / "reports"
OUT_DIR.mkdir(exist_ok=True)

FONT_FAMILY = "GanoReport"


def ensure_fonts() -> tuple[Path, Path, Path]:
    """TTF regular, negrita, cursiva (español)."""
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
    print(
        "No se encontraron fuentes (Arial en Windows o DejaVu en Linux). "
        "Linux: sudo apt install fonts-dejavu-core",
        file=sys.stderr,
    )
    raise SystemExit(1)


class ReportPDF(FPDF):
    def header(self) -> None:
        if self.page_no() == 1:
            return
        self.set_font("GanoReport", "", 9)
        self.set_text_color(80, 80, 80)
        self.cell(0, 8, "Gano Digital — Reporte de estado (equipo desarrollo)", align="R")
        self.ln(10)
        self.set_draw_color(200, 200, 200)
        self.line(10, self.get_y(), 200, self.get_y())
        self.ln(6)
        self.set_text_color(0, 0, 0)

    def footer(self) -> None:
        self.set_y(-15)
        self.set_font("GanoReport", "", 8)
        self.set_text_color(120, 120, 120)
        self.cell(0, 10, f"Página {self.page_no()}", align="C")

    def section_title(self, title: str) -> None:
        self.ln(4)
        self.set_font("GanoReport", "B", 14)
        self.set_text_color(18, 52, 86)
        self.multi_cell(0, 8, title)
        self.set_text_color(0, 0, 0)
        self.ln(2)

    def body_text(self, text: str) -> None:
        self.set_font("GanoReport", "", 10)
        self.multi_cell(0, 5.5, text)
        self.ln(2)

    def bullet(self, text: str) -> None:
        self.set_font("GanoReport", "", 10)
        self.multi_cell(0, 5.5, f"\u2022 {text}")
        self.ln(0.5)

    def bar_h(self, label: str, pct: float, w_max: float = 120) -> None:
        self.set_font("GanoReport", "", 9)
        y = self.get_y()
        self.cell(55, 6, label)
        x = self.get_x()
        h = 5
        self.set_fill_color(230, 235, 240)
        self.rect(x, y, w_max, h, "F")
        fill_w = max(0, min(w_max, w_max * (pct / 100.0)))
        self.set_fill_color(41, 128, 185)
        if fill_w > 0:
            self.rect(x, y, fill_w, h, "F")
        self.set_xy(x + w_max + 4, y)
        self.set_font("GanoReport", "B", 9)
        self.cell(20, 6, f"{pct:.0f} %")
        self.ln(8)

    def table_row(self, col1: str, col2: str, w1: int = 28, w2: int = 152) -> None:
        self.set_font("GanoReport", "", 9)
        y0 = self.get_y()
        self.multi_cell(w1, 5, col1)
        h1 = self.get_y() - y0
        self.set_xy(10 + w1, y0)
        self.multi_cell(w2, 5, col2)
        h2 = self.get_y() - y0
        self.set_xy(10, y0 + max(h1, h2, 5))
        self.ln(1)


def build_pdf() -> Path:
    regular, bold, oblique = ensure_fonts()

    pdf = ReportPDF()
    pdf.set_auto_page_break(auto=True, margin=18)
    pdf.add_font("GanoReport", "", regular)
    pdf.add_font("GanoReport", "B", bold)
    pdf.add_font("GanoReport", "I", oblique)

    today = date.today()

    # — Portada (multi_cell + align C deja x al margen derecho; resetear x cada vez)
    pdf.add_page()
    epw = pdf.epw

    def cover_line(h: float, txt: str, align: str = "C") -> None:
        pdf.set_x(pdf.l_margin)
        pdf.multi_cell(epw, h, txt, align=align)

    pdf.set_font("GanoReport", "B", 22)
    pdf.set_text_color(18, 52, 86)
    pdf.ln(48)
    cover_line(12, "Gano Digital")
    pdf.set_font("GanoReport", "", 14)
    pdf.set_text_color(60, 60, 60)
    pdf.ln(6)
    cover_line(8, "Reporte integral de estado y plan de trabajo")
    pdf.ln(4)
    pdf.set_font("GanoReport", "I", 11)
    cover_line(7, "Sesión tipo junta laboral — Equipo de desarrollo")
    pdf.ln(20)
    pdf.set_font("GanoReport", "", 10)
    pdf.set_text_color(0, 0, 0)
    cover_line(6, f"Fecha del reporte: {today.strftime('%d de abril de %Y')}")
    cover_line(6, "Alcance: proyecto gano.digital (WordPress, marca blanca Reseller GoDaddy)")
    pdf.ln(30)
    pdf.set_font("GanoReport", "", 9)
    pdf.set_text_color(100, 100, 100)
    cover_line(
        5,
        "Documento elaborado a partir de TASKS.md, memoria de proyecto (memory/projects/) "
        "y roadmap acordado. Las cifras de GitHub reflejan el estado documentado en abril de 2026.",
    )

    # — Contenido
    pdf.add_page()
    pdf.section_title("1. Resumen ejecutivo")
    pdf.body_text(
        "Gano Digital es un proveedor de ecosistemas de hosting de alto rendimiento orientado a Colombia y LATAM, "
        "con vitrina WordPress + Elementor (enfoque visual SOTA / Kinetic Monolith) y modelo de marca blanca "
        "mediante GoDaddy Reseller (inventario, aprovisionamiento y pagos delegados). "
        "En el repositorio de trabajo se implementaron de forma sustancial las fases 1 a 3: seguridad base, "
        "hardening (CSP, rate limiting, headers), SEO técnico (JSON-LD, OG) y mejoras de Core Web Vitals. "
        "La prioridad operativa actual es cerrar la brecha entre código en Git y servidor en producción, "
        "sustituir contenido placeholder en la homepage y coordinar revisiones humanas sobre el trabajo "
        "generado por agentes en GitHub (Copilot) en varias oleadas de issues."
    )

    pdf.section_title("2. Línea base: qué hemos hecho desde el inicio")
    pdf.bullet("Fase 1 (parches críticos): wp-config endurecido, chat con nonce CSRF y saneamiento de entrada, alerta wp-file-manager.")
    pdf.bullet("Fase 2: rate limiting REST (429), CSP en modo enforced ajustado a Elementor/Woo, headers de seguridad adicionales.")
    pdf.bullet("Fase 3: MU plugin gano-seo (Organization/LocalBusiness digital, Product, FAQ, Breadcrumbs), OG/Twitter fallback, "
               "resource hints, preload LCP; optimizaciones CWV en gano-child (defer JS, limpieza head, emoji removal, hook LCP).")
    pdf.bullet("Estrategia comercial actualizada: pagos y facturación vía Reseller; retiro de pasarela local Wompi en el modelo objetivo.")
    pdf.bullet("GitHub Pilot: CI (PHP lint, secret scanning acotado), colas JSON para agentes, workflows de seed, documentación de merge y coordinación.")
    pdf.ln(2)

    pdf.section_title("3. Progreso por fases (vista ejecutiva)")
    pdf.body_text(
        "Barras = avance consolidado (diseño de trabajo: código en repo, configuración en vivo y negocio). "
        "Los porcentajes son estimación para comunicación interna, no mediciones contables."
    )
    pdf.bar_h("Fase 1 — Parches críticos (repo)", 100)
    pdf.bar_h("Fase 2 — Hardening", 100)
    pdf.bar_h("Fase 3 — SEO / CWV / schema (repo)", 100)
    pdf.bar_h("Fase 3 — Validación en vivo (panel, GSC, Rank Math)", 35)
    pdf.bar_h("Fase 4 — Reseller / catálogo / checkout", 25)
    pdf.bar_h("Fase 5 — Status page, IA conectada, afiliados", 10)

    pdf.section_title("4. Distribución del foco actual (descriptivo)")
    pdf.body_text("Reparto orientativo entre pilares del proyecto (no es imputación de horas).")
    for label, p in [
        ("Seguridad y estabilidad (F1–F2)", 22),
        ("SEO técnico y rendimiento (F3)", 24),
        ("Contenido / Elementor / copy", 28),
        ("Automatización GitHub + agentes", 16),
        ("Comercio Reseller / F4", 10),
    ]:
        pdf.bar_h(label, float(p))

    pdf.add_page()
    pdf.section_title("5. Capacidades actuales del equipo / stack")
    pdf.bullet("WordPress 6.x, Hello Elementor, gano-child con utilidades CSS (gano-el-*) y plantillas (p. ej. shop-premium / mockup SOTA).")
    pdf.bullet("MU plugins propios: seguridad + SEO con configuración parcialmente asistida desde wp-admin.")
    pdf.bullet("Front: GSAP/ScrollTrigger según diseño; chat y quiz; REST endurecido donde aplica.")
    pdf.bullet("Ingeniería y proceso: repo Pilot, validación de colas de tareas, prompts por oleada, playbooks de merge y coordinación dev.")
    pdf.ln(3)

    pdf.section_title("6. En desarrollo ahora (abril 2026)")
    pdf.bullet("Oleada 1 — issues #17–#33: homepage y fixplan técnico; numerosos PRs abiertos (muchos borrador) a la espera de revisión y merge.")
    pdf.bullet("Oleada 3 — issues #54–#68: marca, UX, comercial y activos (15 tareas) alineadas con brief maestro wave3 y tasks-wave3.json.")
    pdf.bullet("Workflows: Orchestrate Copilot waves (merge + seed), Seed homepage fixplan, validación JSON de colas — ejecución según permisos y dry-run.")
    pdf.bullet("Servidor: despliegue de child theme y MU plugins; menú primary; sustitución de Lorem según homepage-copy y clases Elementor documentadas.")
    pdf.ln(3)

    pdf.section_title("7. Tareas pendientes (prioridad)")
    pdf.set_font("GanoReport", "B", 9)
    pdf.cell(28, 6, "Prioridad")
    pdf.cell(152, 6, "Tarea")
    pdf.ln(7)
    pdf.set_font("GanoReport", "", 9)
    for pr, tx in [
        ("Crítico", "Subir al servidor real los archivos de Fases 1–3 (wp-config, MU plugins, child theme, JS)."),
        ("Crítico", "Eliminar wp-file-manager en producción tras desactivación en panel."),
        ("Alta", "Configurar Gano SEO, Google Search Console y Rank Math (perfil negocio digital)."),
        ("Alta", "Sustituir Lorem y placeholders; retirar testimonios o métricas no verificables."),
        ("Alta", "Depurar catálogo RCC; mapear CTAs del mockup al carrito Reseller; prueba de checkout."),
        ("Media", "2FA en wp-admin; ejecutar y archivar plugins de fase cuando el sitio refleje el contenido."),
        ("Operación", "Revisar y fusionar PRs oleada 1; usar prompts Copilot correctos por rango de issues; higiene de tokens/remotes."),
    ]:
        pdf.table_row(pr, tx)

    pdf.add_page()
    pdf.section_title("8. Obstáculos superados (referencia)")
    pdf.bullet("Superficie de ataque reducida: cabeceras, CSP, límites de uso en API de chat, configuración wp más estricta.")
    pdf.bullet("SEO técnico reutilizable: datos estructurados y mejoras de rendimiento en tema hijo sin depender solo de plugins de terceros.")
    pdf.bullet("Claridad operativa: modelo Reseller como eje de facturación; menos superficie de integración de pagos propia.")
    pdf.bullet("Escalabilidad del trabajo: colas de issues y documentación para agentes y humanos en paralelo.")
    pdf.ln(3)

    pdf.section_title("9. Obstáculos y riesgos pendientes")
    pdf.bullet("Brecha deploy: valor de negocio acumulado en Git no se materializa hasta despliegue y verificación en producción.")
    pdf.bullet("Cuello de revisión humana: acumulación de PRs en borrador retrasa cierre de oleada 1 y genera conflictos.")
    pdf.bullet("Contenido y cumplimiento: datos legales y de contacto reales deben provenir del negocio; los agentes no deben inventarlos.")
    pdf.bullet("Complejidad editorial en Elementor: sin sustitución sistemática de copy, la vitrina no refleja la calidad del código base.")
    pdf.bullet("Dependencia del proveedor Reseller: cambios de catálogo, precios o flujos de checkout requieren seguimiento activo.")
    pdf.ln(3)

    pdf.section_title("10. Indicadores GitHub (referencia abril 2026)")
    pdf.body_text(
        "Aproximadamente 17 issues oleada 1 abiertos; ~17 PRs abiertos (muchos draft); 15 issues oleada 3 (#54–#68). "
        "Actualizar estas cifras tras cada ciclo de merge y cierre."
    )
    pdf.bar_h("Normalización: oleada 1 pendiente de cierre", 100)
    pdf.bar_h("Oleada 3: progreso relativo del lote (inicio de ciclo)", 20)
    pdf.body_text(
        "La segunda barra es ilustrativa (20 %) para señalar que la oleada 3 está en fase inicial de ejecución en el equipo."
    )

    pdf.section_title("11. Próximos pasos recomendados (ventana ~30 días)")
    pdf.bullet("1) Despliegue a staging o producción con checklist de archivos críticos y smoke test.")
    pdf.bullet("2) Sesión dedicada de revisión de PRs (criterios en MERGE-PLAYBOOK) para destrabar oleada 1.")
    pdf.bullet("3) Completar acciones de panel: SEO, menús, copy real, eliminación de plugin de riesgo.")
    pdf.bullet("4) Una prueba punta a punta de compra desde la vitrina hasta confirmación en flujo Reseller.")
    pdf.bullet("5) Mantener prompts y asignaciones Copilot alineados con el rango de issues (oleada 1 vs 3).")

    pdf.ln(8)
    pdf.set_font("GanoReport", "I", 8)
    pdf.set_text_color(90, 90, 90)
    pdf.multi_cell(
        0,
        4,
        "Referencias: TASKS.md; memory/projects/gano-digital.md; .github/DEV-COORDINATION.md; "
        ".github/MERGE-PLAYBOOK.md; memory/research/gano-wave3-brand-ux-master-brief.md. "
        "Regenerar PDF: python scripts/generate_board_report_pdf.py",
    )

    out = OUT_DIR / f"Gano-Digital-Reporte-Estado-{today.strftime('%Y-%m')}.pdf"
    pdf.output(str(out))
    return out


if __name__ == "__main__":
    path = build_pdf()
    print(path)
