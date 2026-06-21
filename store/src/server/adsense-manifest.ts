import { existsSync, readFileSync } from "node:fs";
import { normalize, resolve, sep } from "node:path";

export interface AdSenseRuntimeConfig {
	publisherId: string | null;
	scriptHtml: string;
	adsTxt: string | null;
	appAdsTxt: string | null;
}

interface AdSenseTextFileManifest {
	enabled: boolean;
	content: string;
}

interface AdSenseRuntimeManifest {
	version: 1;
	enabled: boolean;
	publisherId: string | null;
	scriptSnippet: string;
	adsTxt: AdSenseTextFileManifest;
	appAdsTxt: AdSenseTextFileManifest;
}

const ADSENSE_MANIFEST_FILENAME = "airo-adsense.json";
const ADSENSE_PUBLISHER_ID_PATTERN = /^ca-pub-\d{16}$/;

export const EMPTY_ADSENSE_RUNTIME_CONFIG: AdSenseRuntimeConfig = {
	publisherId: null,
	scriptHtml: "",
	adsTxt: null,
	appAdsTxt: null,
};

export function buildCanonicalAdSenseScript(publisherId: string): string {
	if (!ADSENSE_PUBLISHER_ID_PATTERN.test(publisherId)) return "";
	return `<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=${publisherId}" crossorigin="anonymous"></script>`;
}

export function loadAdSenseRuntimeConfig(baseDir: string): AdSenseRuntimeConfig {
	const manifestPath = resolveRuntimeManifestPath(baseDir);
	if (manifestPath === null) return EMPTY_ADSENSE_RUNTIME_CONFIG;
	if (!existsSync(manifestPath)) return EMPTY_ADSENSE_RUNTIME_CONFIG;

	try {
		const parsed = JSON.parse(readFileSync(manifestPath, "utf8")) as unknown;
		return normalizeRuntimeManifest(parsed);
	} catch (error) {
		console.error("adsense.manifest.load_failed", {
			error: error instanceof Error ? error.message : String(error),
		});
		return EMPTY_ADSENSE_RUNTIME_CONFIG;
	}
}

function resolveRuntimeManifestPath(baseDir: string): string | null {
	if (baseDir.includes("\0")) return null;
	if (baseDir.split(/[\\/]+/).includes("..")) return null;
	const normalizedBaseDir = normalize(baseDir);

	const resolvedBaseDir = resolve(normalizedBaseDir);
	const resolvedManifestPath = resolve(resolvedBaseDir, ADSENSE_MANIFEST_FILENAME);
	const basePrefix = resolvedBaseDir.endsWith(sep) ? resolvedBaseDir : `${resolvedBaseDir}${sep}`;
	if (resolvedManifestPath !== resolvedBaseDir && !resolvedManifestPath.startsWith(basePrefix)) {
		return null;
	}
	return resolvedManifestPath;
}

export function resolveAdSenseTextFile(
	config: AdSenseRuntimeConfig,
	key: "adsTxt" | "appAdsTxt",
): string | null {
	return config[key];
}

function normalizeRuntimeManifest(value: unknown): AdSenseRuntimeConfig {
	if (!isRuntimeManifestShape(value)) return EMPTY_ADSENSE_RUNTIME_CONFIG;

	if (!value.enabled) return EMPTY_ADSENSE_RUNTIME_CONFIG;

	const publisherId = isValidPublisherId(value.publisherId) ? value.publisherId : null;
	return {
		publisherId,
		scriptHtml: publisherId ? buildCanonicalAdSenseScript(publisherId) : "",
		adsTxt: normalizeTextFile(value.adsTxt),
		appAdsTxt: normalizeTextFile(value.appAdsTxt),
	};
}

function isRuntimeManifestShape(value: unknown): value is AdSenseRuntimeManifest {
	if (typeof value !== "object" || value === null) return false;
	const candidate = value as Partial<AdSenseRuntimeManifest>;
	return (
		candidate.version === 1 &&
		typeof candidate.enabled === "boolean" &&
		(candidate.publisherId === null || typeof candidate.publisherId === "string") &&
		typeof candidate.scriptSnippet === "string" &&
		isTextFileManifest(candidate.adsTxt) &&
		isTextFileManifest(candidate.appAdsTxt)
	);
}

function isTextFileManifest(value: unknown): value is AdSenseTextFileManifest {
	if (typeof value !== "object" || value === null) return false;
	const candidate = value as Partial<AdSenseTextFileManifest>;
	return typeof candidate.enabled === "boolean" && typeof candidate.content === "string";
}

function isValidPublisherId(value: string | null): value is string {
	return typeof value === "string" && ADSENSE_PUBLISHER_ID_PATTERN.test(value);
}

function normalizeTextFile(value: AdSenseTextFileManifest): string | null {
	if (!value.enabled) return null;
	const content = value.content.trim();
	return content ? value.content : null;
}
