import { describe, it, expect } from "vitest";
import { isSystemHost } from "./seo-host";

describe("isSystemHost", () => {
	it("flags Airo subdomains as system hosts", () => {
		expect(isSystemHost({ hostname: "xyz.airoapp.ai" })).toBe(true);
		expect(isSystemHost({ hostname: "abc.test-airoapp.ai" })).toBe(true);
		expect(isSystemHost({ hostname: "abc.dev-airoapp.ai" })).toBe(true);
		expect(isSystemHost({ hostname: "preview.dev-godaddy.com" })).toBe(true);
	});

	it("flags the apex airoapp.ai as a system host", () => {
		expect(isSystemHost({ hostname: "airoapp.ai" })).toBe(true);
	});

	it("does NOT flag look-alike hosts that lack the dot separator", () => {
		// endsWith(".airoapp.ai") guards against suffix-injection like
		// `evil-airoapp.ai`, which would otherwise be treated as a system
		// host and silently noindexed.
		expect(isSystemHost({ hostname: "evil-airoapp.ai" })).toBe(false);
		expect(isSystemHost({ hostname: "notairoapp.ai" })).toBe(false);
	});

	it("normalises case before matching", () => {
		expect(isSystemHost({ hostname: "XYZ.AiroApp.AI" })).toBe(true);
		expect(isSystemHost({ hostname: "Acme.COM" })).toBe(false);
	});

	it("treats empty/local hostnames as system hosts (dev safety)", () => {
		expect(isSystemHost({ hostname: "" })).toBe(true);
		expect(isSystemHost({ hostname: undefined })).toBe(true);
		expect(isSystemHost({ hostname: null })).toBe(true);
		expect(isSystemHost({ hostname: "localhost" })).toBe(true);
		expect(isSystemHost({ hostname: "127.0.0.1" })).toBe(true);
	});

	it("does NOT flag customer-attached domains as system hosts", () => {
		expect(isSystemHost({ hostname: "acme.com" })).toBe(false);
		expect(isSystemHost({ hostname: "shop.acme.co.uk" })).toBe(false);
		expect(isSystemHost({ hostname: "example.org" })).toBe(false);
	});
});
