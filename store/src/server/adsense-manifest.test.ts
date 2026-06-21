import { mkdirSync, mkdtempSync, rmSync, writeFileSync } from "node:fs";
import { tmpdir } from "node:os";
import { join } from "node:path";
import { afterEach, describe, expect, it, vi } from "vitest";

import {
	EMPTY_ADSENSE_RUNTIME_CONFIG,
	buildCanonicalAdSenseScript,
	loadAdSenseRuntimeConfig,
	resolveAdSenseTextFile,
} from "./adsense-manifest";

const publisherId = "ca-pub-1234567890123456";
const canonicalScript = `<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=${publisherId}" crossorigin="anonymous"></script>`;

let tempDir: string | null = null;

function makeTempDir(): string {
	tempDir = mkdtempSync(join(tmpdir(), "adsense-manifest-"));
	return tempDir;
}

function writeManifest(baseDir: string, value: unknown): void {
	writeFileSync(join(baseDir, "airo-adsense.json"), JSON.stringify(value), "utf8");
}

afterEach(() => {
	if (tempDir) rmSync(tempDir, { recursive: true, force: true });
	tempDir = null;
	vi.restoreAllMocks();
});

describe("AdSense runtime manifest", () => {
	it("returns disabled config when the runtime manifest is missing", () => {
		expect(loadAdSenseRuntimeConfig(makeTempDir())).toEqual(EMPTY_ADSENSE_RUNTIME_CONFIG);
	});

	it("returns disabled config when the runtime directory uses traversal segments", () => {
		const rootDir = makeTempDir();
		const childDir = join(rootDir, "child");
		mkdirSync(childDir);
		writeManifest(rootDir, {
			version: 1,
			enabled: true,
			publisherId,
			scriptSnippet: canonicalScript,
			adsTxt: { enabled: true, content: "google.com, pub-123, DIRECT, f08c47fec0942fa0" },
			appAdsTxt: { enabled: false, content: "" },
		});

		expect(loadAdSenseRuntimeConfig(`${childDir}/..`)).toEqual(EMPTY_ADSENSE_RUNTIME_CONFIG);
	});

	it("returns disabled config when the runtime manifest is malformed", () => {
		const consoleErrorSpy = vi.spyOn(console, "error").mockImplementation(() => {});
		const dir = makeTempDir();
		writeFileSync(join(dir, "airo-adsense.json"), "{not-json", "utf8");

		expect(loadAdSenseRuntimeConfig(dir)).toEqual(EMPTY_ADSENSE_RUNTIME_CONFIG);
		expect(consoleErrorSpy).toHaveBeenCalledWith(
			"adsense.manifest.load_failed",
			expect.objectContaining({ error: expect.any(String) }),
		);
	});

	it("returns disabled config when the runtime manifest shape is invalid", () => {
		const dir = makeTempDir();
		writeManifest(dir, {
			version: 2,
			enabled: true,
			publisherId,
			scriptSnippet: canonicalScript,
			adsTxt: { enabled: true, content: "google.com, pub-123, DIRECT, f08c47fec0942fa0" },
			appAdsTxt: { enabled: true, content: "google.com, pub-456, DIRECT, f08c47fec0942fa0" },
		});

		expect(loadAdSenseRuntimeConfig(dir)).toEqual(EMPTY_ADSENSE_RUNTIME_CONFIG);
	});

	it("returns disabled config when the runtime manifest enabled flag is missing", () => {
		const dir = makeTempDir();
		writeManifest(dir, {
			version: 1,
			publisherId,
			scriptSnippet: canonicalScript,
			adsTxt: { enabled: true, content: "google.com, pub-123, DIRECT, f08c47fec0942fa0" },
			appAdsTxt: { enabled: true, content: "app.example.com, pub-123, DIRECT" },
		});

		expect(loadAdSenseRuntimeConfig(dir)).toEqual(EMPTY_ADSENSE_RUNTIME_CONFIG);
	});

	it("builds the canonical script from publisher ID instead of trusting stored scriptSnippet", () => {
		const dir = makeTempDir();
		writeManifest(dir, {
			version: 1,
			enabled: true,
			publisherId,
			scriptSnippet: "<script src=\"https://example.com/not-trusted.js\"></script>",
			adsTxt: { enabled: false, content: "" },
			appAdsTxt: { enabled: false, content: "" },
		});

		expect(loadAdSenseRuntimeConfig(dir)).toEqual({
			publisherId,
			scriptHtml: canonicalScript,
			adsTxt: null,
			appAdsTxt: null,
		});
		expect(buildCanonicalAdSenseScript(publisherId)).toBe(canonicalScript);
	});

	it("returns disabled config when Display Ads are globally disabled", () => {
		const dir = makeTempDir();
		writeManifest(dir, {
			version: 1,
			enabled: false,
			publisherId,
			scriptSnippet: canonicalScript,
			adsTxt: { enabled: true, content: "google.com, pub-123, DIRECT, f08c47fec0942fa0" },
			appAdsTxt: { enabled: true, content: "app.example.com, pub-123, DIRECT" },
		});

		expect(loadAdSenseRuntimeConfig(dir)).toEqual(EMPTY_ADSENSE_RUNTIME_CONFIG);
	});

	it("disables script output when publisher ID is invalid", () => {
		const dir = makeTempDir();
		writeManifest(dir, {
			version: 1,
			enabled: true,
			publisherId: "ca-pub-123",
			scriptSnippet: canonicalScript,
			adsTxt: { enabled: false, content: "" },
			appAdsTxt: { enabled: false, content: "" },
		});

		expect(loadAdSenseRuntimeConfig(dir)).toEqual(EMPTY_ADSENSE_RUNTIME_CONFIG);
	});

	it("returns enabled text files and omits disabled text files", () => {
		const dir = makeTempDir();
		writeManifest(dir, {
			version: 1,
			enabled: true,
			publisherId: null,
			scriptSnippet: "",
			adsTxt: {
				enabled: true,
				content: "google.com, pub-123, DIRECT, f08c47fec0942fa0",
			},
			appAdsTxt: { enabled: false, content: "draft app ads content" },
		});

		const config = loadAdSenseRuntimeConfig(dir);
		expect(resolveAdSenseTextFile(config, "adsTxt")).toBe(
			"google.com, pub-123, DIRECT, f08c47fec0942fa0",
		);
		expect(resolveAdSenseTextFile(config, "appAdsTxt")).toBeNull();
		expect(config).toEqual({
			publisherId: null,
			scriptHtml: "",
			adsTxt: "google.com, pub-123, DIRECT, f08c47fec0942fa0",
			appAdsTxt: null,
		});
	});
});
