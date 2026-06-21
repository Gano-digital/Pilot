import type { Request, Response } from "express";
import {
	SERVICES,
	CATEGORY_ORDER,
	formatPrice,
	billingLabel,
} from "../../../data/catalog.js";

/**
 * GET /llms-full.txt
 *
 * Genera, al vuelo y a partir de src/data/catalog.ts, un documento markdown
 * legible por LLMs con los 22 servicios completos. Se mantiene siempre
 * sincronizado con el catálogo (precios, descripciones, casos de uso).
 */
export default function handler(_req: Request, res: Response) {
	const lines: string[] = [];

	lines.push("# Gano Digital — Catálogo completo de servicios");
	lines.push("");
	lines.push(
		"> Estudio de infraestructura digital y reseller autorizado de GoDaddy en Colombia. " +
			"Hosting WordPress administrado, dominios, correo profesional, seguridad web y desarrollo a medida. " +
			"Ingeniería curada por expertos, facturación en pesos colombianos (COP) y soporte en español. " +
			"Contacto: WhatsApp +57 313 564 6123.",
	);
	lines.push("");

	for (const category of CATEGORY_ORDER) {
		const items = SERVICES.filter((s) => s.category === category);
		if (items.length === 0) continue;

		lines.push(`## ${category}`);
		lines.push("");

		for (const svc of items) {
			const price = `${formatPrice(svc.priceFrom, svc.currency)} ${billingLabel(svc.billingPeriod)}`.trim();
			lines.push(`### ${svc.name} — desde ${price}`);
			lines.push("");
			lines.push(svc.shortDescription);
			lines.push("");
			if (svc.longDescription) {
				lines.push(svc.longDescription);
				lines.push("");
			}
			if (svc.features?.length) {
				lines.push("**Incluye:** " + svc.features.join("; ") + ".");
				lines.push("");
			}
			if (svc.bestFor?.length) {
				lines.push("**Ideal para:** " + svc.bestFor.join("; ") + ".");
				lines.push("");
			}
		}
	}

	lines.push("## Contacto");
	lines.push("");
	lines.push("- WhatsApp: +57 313 564 6123");
	lines.push("- Facturación en pesos colombianos (COP). Soporte en español.");
	lines.push("- Catálogo en línea: https://gano.digital/catalogo");
	lines.push("");

	res
		.type("text/plain; charset=utf-8")
		.set("Cache-Control", "public, max-age=300, must-revalidate")
		.send(lines.join("\n"));
}
