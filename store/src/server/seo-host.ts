// Traefik routes unknown hosts to nowhere, so any request reaching this
// container that isn't on a system host (preview, published-without-
// custom-domain, dev, localhost) is a customer-attached domain. Customer
// domains get Allow + self-canonical; system hosts get Disallow + noindex.
export const SYSTEM_HOST_SUFFIXES = [
	".airoapp.ai",
	".test-airoapp.ai",
	".dev-airoapp.ai",
	".dev-godaddy.com",
];

export function isSystemHost(req: { hostname?: string | null }): boolean {
	const h = (req.hostname || "").toLowerCase();
	if (!h || h === "localhost" || h === "127.0.0.1") return true;
	return SYSTEM_HOST_SUFFIXES.some(
		(s) => h === s.replace(/^\./, "") || h.endsWith(s),
	);
}
