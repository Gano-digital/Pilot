# Memoria — Gano Digital

## Yo
Diego — Fundador de Gano Digital. Proyecto: construir gano.digital como proveedor
real de hosting WordPress en Colombia. Workspace: esta carpeta raíz del proyecto.

## Proyecto actual
| Nombre | Qué es |
|--------|--------|
| **gano.digital** | Sitio web de hosting WordPress en Colombia (WP + Elementor + WooCommerce; checkout vía **GoDaddy Reseller**) |
| **Plan Maestro** | Auditoría integral + roadmap de 5 fases generado en Marzo 2026 |

→ Detalles completos: `memory/projects/gano-digital.md`

## Stack tecnológico
| Componente | Detalle |
|-----------|---------|
| **CMS** | WordPress + Elementor + Royal Elementor Addons |
| **E-commerce** | WooCommerce (moneda COP, zona Bogotá) |
| **Pago / checkout cliente** | **GoDaddy Reseller Store** (carrito marca blanca, facturación del lado Reseller). Sin priorizar pasarelas locales ajenas al ecosistema GoDaddy. |
| **Seguridad** | Wordfence + MU Plugin gano-security.php |
| **SEO** | Rank Math |
| **Tema** | Hello Elementor → gano-child |
| **UX** | Chat IA (gano-chat.js) + Quiz Soberanía Digital |
| **Hosting actual** | **Managed WordPress Deluxe** (plan activo del servidor donde corre gano.digital) |

## Infraestructura del servidor (Managed WordPress Deluxe)
Plan actual donde está alojado gano.digital. Relevante para saber qué tenemos disponible:
- 20 GB NVMe total storage
- CDN incluido (hasta 2x más rápido)
- Protección DDoS
- Staging site disponible ← útil para probar cambios antes de subir a producción
- Subdomains permitidos ✅ — my.gano.digital, support.gano.digital pueden crearse en este mismo plan
- **Implicación Fase 4**: El staging site permite probar cambios de vitrina, Reseller y plugins antes de producción. Los subdomains van en este mismo plan cuando apliquen.

## Términos clave
| Término | Significado |
|---------|-------------|
| **PM** | Plan Maestro (documento en esta carpeta) |
| **fases** | Las 5 fases del roadmap de desarrollo |
| **plugins de fase** | gano-phase1..7 — instaladores del sitio (ver nota crítica) |
| **MU plugin** | gano-security.php en /mu-plugins/ — seguridad base |
| **Reseller** | Programa GoDaddy Reseller: catálogo, checkout y cobro al cliente final (ver `TASKS.md` Fase 4). |
| **ecosistemas** | Los 4 planes de hosting de Gano Digital |
| **skills** | Scripts de guía en .gano-skills/ |

## ⚠️ NOTA CRÍTICA — Plugins de fase
Los plugins `gano-phase1-installer`, `gano-phase2-business`, `gano-phase3-content`,
`gano-phase6-catalog`, `gano-phase7-activator` **NO deben eliminarse** hasta que:
1. Cada uno haya sido **activado en WordPress** (ejecuta el instalador)
2. Se confirme que su contenido/configuración aparece en el sitio
Estos plugins **despliegan el sitio web**. Eliminarlos antes = perder la instalación.
Solo `wp-file-manager` debe eliminarse inmediatamente (es un plugin de riesgo diferente).

→ Detalles: `memory/notes/plugins-de-fase.md`

## Nuevos plugins (Diego adelantado)
| Plugin | Qué hace | Fase |
|--------|----------|------|
| **gano-reseller-enhancements** | Filtros para GoDaddy Reseller Store: override de precios (ACF), meta campos técnicos, bundle handler para SKUs 3-year, redirección a checkout | 4+ |
| **gano-content-importer** | **One-shot installer**: Crea 20 páginas SOTA como borradores (draft) con contenido estratégico + featured images. Se elimina tras activación. | 3.5 |

### 📄 Las 20 Páginas SOTA (gano-content-importer)
Plugin que instala contenido de posicionamiento estratégico. Cada página incluye hook-box, innovación SOTA, quote, y CTA. Categorías: infraestructura, seguridad, inteligencia-artificial, estrategia, rendimiento.

1. Arquitectura NVMe: La Muerte del SSD Tradicional
2. Zero-Trust Security: El Fin de las Contraseñas
3. Gestión Predictiva con AI: Cero Caídas, Cero Sorpresas
4. Soberanía Digital en LATAM: Tus Datos, Tu Control
5. Headless WordPress: La Velocidad Absoluta
6. Mitigación DDoS Inteligente: Firewall de Nueva Generación
7. La Muerte del Hosting Compartido: El Riesgo Invisible
8. Edge Computing: Contenido a Cero Distancia de tu Cliente
9. Green Hosting: Infraestructura Sostenible para tu Negocio
10. Cifrado Post-Cuántico: La Bóveda del Futuro
11. CI/CD Automatizado: Nunca Más Rompas tu Tienda en Vivo
12. Backups Continuos en Tiempo Real: Tu Máquina del Tiempo
13. Skeleton Screens: La Psicología de la Velocidad Percibida
14. Escalamiento Elástico: Sobrevive a tu Propio Éxito Viral
15. Self-Healing: El Ecosistema que se Cura Solo
16. Micro-Animaciones e Interacciones Hápticas: Diseño que se Siente
17. HTTP/3 y QUIC: El Protocolo que Rompe la Congestión
18. Alta Disponibilidad (HA): La Infraestructura Indestructible
19. Analytics Server-Side: Privacidad, Velocidad y Datos Reales
20. El Agente IA de Administración: Tu Infraestructura Habla Español

## Dónde nos quedamos (Marzo 2026)

### Completado ✅ — Fases 1, 2 y 3

**Fase 1 — Parches críticos:** WP_DEBUG, endurecimiento de secretos/webhooks donde aplicaba el código histórico, nonce CSRF chat, alerta wp-file-manager
**Fase 2 — Hardening:** Rate limiting REST (429), almacenamiento seguro de claves donde hubo plugins de pago legacy, CSP enforced + 4 headers, CSRF troubleshooter
**Fase 3 — SEO/Performance:** Schema JSON-LD (Org+LocalBiz+Product+FAQ), MU plugin gano-seo.php, Core Web Vitals, template landing SEO, landing "hosting wordpress colombia"

→ Detalle completo: `TASKS.md`

### Próximos pasos — Fase 4 (comercialización dentro de GoDaddy)

**Prioridad:** lo que ya está o encaja en **GoDaddy** (Managed WordPress + **Reseller Store** + RCC). Ver **`TASKS.md`** (Fase 4 agilizada) y **`memory/projects/gano-digital.md`**.

Ejemplos de línea de trabajo actual: depurar catálogo en Reseller Control Center, mapear CTAs del mockup (`shop-premium.php`) al carrito Reseller, prueba de flujo de checkout marca blanca, soporte (FreeScout u otro) si se define.

Investigación más amplia (billing self-hosted, DIAN, etc.) vive en `memory/research/fase4-plataforma.md` como **referencia**, no como compromiso mientras el modelo activo sea Reseller en GoDaddy.

⚠️ Diego debe proveer: datos reales de contacto (NIT, teléfono) donde falten en copy/legal.

## Referencia Externa
| Documento | Contenido |
|-----------|----------|
| `godaddy-reseller-reference.md` | Estructura de pricing, paleta de color, componentes UI, layout patterns del sitio de GoDaddy (referencia para diseño). **NO contiene código/APIs**, solo estructura visual y messaging. |

## Skills disponibles
| Skill | Usar cuando |
|-------|-------------|
| `.gano-skills/gano-wp-security/` | Parches de seguridad WordPress / MU-plugins / tema |
| `.gano-skills/gano-wompi-fixer/` | **Legacy** — solo si en el entorno aún existe código/plugin antiguo de pasarela local; **no priorizar**; comercio actual = Reseller GoDaddy |
| `.gano-skills/gano-content-audit/` | Reemplazo de contenido placeholder, copy |
| `.gano-skills/gano-fase4-plataforma/` | WHMCS/Blesta, billing, portal cliente, DIAN, investigación Fase 4 |
| `.gano-skills/gano-multi-agent-local-workflow/` | Varios agentes (Cursor/Claude/Antigravity), ruta 1 vs backup, git sin secretos en URL |
| `.gano-skills/gano-github-copilot-orchestration/` | GitHub Pilot: workflows 01–12, cola `.github/agent-queue/`, **08/09/10** (semillas/orquestar), prompt bulk, estado post-merge abril 2026 en la skill |

## Archivos importantes
| Archivo | Qué es |
|---------|--------|
| `Gano Digital — Plan Maestro 2026.docx` | Auditoría + roadmap completo |
| `Auditoria de seguridad.pdf` | Análisis externo previo del sitio (145 páginas) |
| `wp-config.php` | Config principal — hardened (Fase 1) |
| `wp-content/mu-plugins/gano-security.php` | MU Plugin seguridad + CSP enforced (Fase 2) |
| `wp-content/mu-plugins/gano-seo.php` | **NUEVO** MU Plugin SEO — schema JSON-LD (Fase 3) |
| `wp-content/plugins/gano-wompi-integration/` | **Solo si está presente en el sitio** — legado; no es el eje comercial actual (Reseller) |
| `wp-content/themes/gano-child/functions.php` | Child theme — Core Web Vitals + rate limiting |
| `wp-content/themes/gano-child/templates/page-seo-landing.php` | **NUEVO** Template landing SEO (Fase 3) |
| `wp-content/themes/gano-child/seo-pages/` | **NUEVO** Contenido landing pages keywords |
| `memory/research/fase4-plataforma.md` | **NUEVO** Investigación completa Fase 4 |
| `memory/sessions/2026-04-01-reporte-cursor-descargas-y-herramientas.md` | **2026-04-01** Reporte de sesión Cursor: Descargas, `EJECUTAR_ANALISIS.ps1`, organización de folios, seguridad (`id_rsa` → `.ssh`). Continuidad para Claude. |
| `memory/claude/README.md` | **Contexto para Claude** — historial abril 2026, pendientes, FAQ; [`memory/claude/dispatch-queue.json`](memory/claude/dispatch-queue.json) + [`dispatch-prompt.md`](memory/claude/dispatch-prompt.md) cola ejecutable con `scripts/claude_dispatch.py`. |
| `.vscode/tasks.json` | Tareas **Run Task** para dispatch (`next`, `list`, `validate`, `show`, `complete`) y validación cola Copilot. |
| `scripts/generate_claude_audit_report_pdf.py` | **Informe PDF** de auditoría de desarrollo (estado repo, workflows, dispatch, PRs, riesgos). Salida: `reports/Gano-Digital-Auditoria-Desarrollo-YYYY-MM-DD.pdf` (`*.pdf` en gitignore). |

## Preferencias de Diego
- Comunicación en español
- Prefiere documentación en .docx para reportes formales
- Quiere cerrar la vitrina y el flujo comercial **dentro del ecosistema GoDaddy** (Reseller + hosting actual); investigaciones WHMCS/DIAN quedan como referencia, no como prioridad mientras Reseller cubra checkout
- No eliminar plugins de fase sin confirmación explícita
