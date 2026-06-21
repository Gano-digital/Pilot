import { defineConfig, type Plugin, type ViteDevServer } from "vite";
import react from "@vitejs/plugin-react";
import path from "path";
import { URL } from "node:url";
import sourceMapperPlugin from "./source-mapper/src/index";
import { devToolsPlugin } from "./dev-tools/src/vite-plugin";
import { fullStoryPlugin } from "./fullstory-plugin";
import { errorInterceptorPlugin } from "./dev-tools/src/vite-error-interceptor";
import { mediaVersionsPlugin } from "./dev-tools/src/vite-media-versions-plugin";
import { formatOverridesPlugin } from "./format-overrides-plugin";

function extractHostname(value: string): string {
	try {
		if (value.includes("://")) {
			return new URL(value).hostname;
		}
		return value;
	} catch {
		return value;
	}
}

function apiDevPlugin(): Plugin {
	return {
		name: "api-dev",
		apply: "serve",
		configureServer(server: ViteDevServer) {
			server.middlewares.use(async (req, res, next) => {
				if (!req.url?.startsWith("/api")) return next();
				try {
					const mod = await server.ssrLoadModule("/src/server/entry.ts");
					const handler = mod.default;
					handler(req, res, next);
				} catch (err) {
					if (err instanceof Error) server.ssrFixStacktrace(err);
					next(err);
				}
			});
		},
	};
}

const allowedHosts: string[] = [];
const corsOrigins: string[] = [];

if (process.env.FRONTEND_DOMAIN) {
	const frontendHost = extractHostname(process.env.FRONTEND_DOMAIN);
	allowedHosts.push(frontendHost);
	corsOrigins.push(`http://${frontendHost}`, `https://${frontendHost}`);
}
if (process.env.ALLOWED_ORIGINS) {
	const origins = process.env.ALLOWED_ORIGINS.split(",");
	allowedHosts.push(...origins.map(extractHostname));
	corsOrigins.push(...origins);
}
if (process.env.VITE_PARENT_ORIGIN) {
	allowedHosts.push(extractHostname(process.env.VITE_PARENT_ORIGIN));
	corsOrigins.push(process.env.VITE_PARENT_ORIGIN);
}
if (allowedHosts.length === 0) {
	allowedHosts.push("*");
}
if (corsOrigins.length === 0) {
	corsOrigins.push("*");
}

export default defineConfig(({ mode, isSsrBuild }) => ({
	envPrefix: ["VITE_", "SITE_"],

	plugins: [
		react({
			babel: {
				plugins: [sourceMapperPlugin],
			},
			}),
			apiDevPlugin(),
			formatOverridesPlugin(__dirname),
			...(mode === "development"
			? [
					devToolsPlugin() as Plugin,
					fullStoryPlugin(),
					errorInterceptorPlugin(),
					mediaVersionsPlugin() as Plugin,
				]
			: []),
	],

	resolve: {
		dedupe: ["react", "react-dom", "react-router-dom"],
		alias: {
			nothing: "/src/fallbacks/missingModule.ts",
			"@/api": path.resolve(__dirname, "./src/server/api"),
			"@": path.resolve(__dirname, "./src"),
		},
	},

	optimizeDeps: {
		include: ["react", "react-dom", "react-router-dom", "motion/react"],
	},

	ssr: {
		noExternal: isSsrBuild ? true : undefined,
	},

	server: {
		host: process.env.HOST || "0.0.0.0",
		port: parseInt(process.env.PORT || "5173"),
		strictPort: !!process.env.PORT,
		allowedHosts,
		cors: {
			origin: corsOrigins,
			credentials: true,
			methods: ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
			allowedHeaders: ["Content-Type", "Authorization", "Accept", "User-Agent"],
		},
		hmr: {
			overlay: false,
		},
		watch: {
			ignored: ["**/dist/**"],
		},
		// Pre-transform the entry chain on dev-server start so the FIRST iframe
		// request doesn't pay the full cold on-demand transpile cost. Paired with
		// the container's pre-start `vite optimize` (container-scripts/preview/
		// nomad_setup.sh), this shrinks the mount→IFRAME_READY window that the
		// builder's recovery logic waits on.
		warmup: {
			clientFiles: ["./src/main.tsx", "./src/App.tsx"],
		},
	},

	preview: {
		host: process.env.HOST || "0.0.0.0",
		port: parseInt(process.env.PORT || "5173"),
		strictPort: !!process.env.PORT,
		allowedHosts,
		cors: {
			origin: corsOrigins,
			credentials: true,
			methods: ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
			allowedHeaders: ["Content-Type", "Authorization", "Accept", "User-Agent"],
		},
	},

	build: isSsrBuild
		? {
				outDir: "dist",
				emptyOutDir: false,
				copyPublicDir: false,
				// Nunca emitir source maps en producción: evitan exponer el código
				// fuente legible del servidor. Explícito para que un cambio futuro no
				// reactive los maps por accidente al ajustar otras opciones de build.
				sourcemap: false,
				ssr: "src/server/entry.ts",
				rollupOptions: {
					output: {
						format: "es",
						entryFileNames: "server.bundle.mjs",
						chunkFileNames: "bin/[name]-[hash].js",
						banner: "import { createRequire } from 'module';\nconst require = createRequire(import.meta.url);",
					},
				},
			}
		: {
				outDir: "dist/client",
				emptyOutDir: true,
				copyPublicDir: true,
				// Sin source maps en el bundle de cliente: el JS minificado que llega
				// al navegador no debe venir acompañado de mapas que reconstruyan el
				// código fuente original. Explícito y a prueba de cambios accidentales.
				sourcemap: false,
				rollupOptions: {
					output: {
						manualChunks: {
							"react-vendor": ["react", "react-dom"],
							"radix-ui": [
								"@radix-ui/react-accordion",
								"@radix-ui/react-alert-dialog",
								"@radix-ui/react-aspect-ratio",
								"@radix-ui/react-avatar",
								"@radix-ui/react-checkbox",
								"@radix-ui/react-collapsible",
								"@radix-ui/react-context-menu",
								"@radix-ui/react-dialog",
								"@radix-ui/react-dropdown-menu",
								"@radix-ui/react-hover-card",
								"@radix-ui/react-label",
								"@radix-ui/react-menubar",
								"@radix-ui/react-navigation-menu",
								"@radix-ui/react-popover",
								"@radix-ui/react-progress",
								"@radix-ui/react-scroll-area",
								"@radix-ui/react-select",
								"@radix-ui/react-separator",
								"@radix-ui/react-slider",
								"@radix-ui/react-slot",
								"@radix-ui/react-switch",
								"@radix-ui/react-tabs",
								"@radix-ui/react-toast",
								"@radix-ui/react-toggle",
								"@radix-ui/react-toggle-group",
								"@radix-ui/react-tooltip",
							],
							query: ["@tanstack/react-query"],
						},
					},
				},
			},
}));
