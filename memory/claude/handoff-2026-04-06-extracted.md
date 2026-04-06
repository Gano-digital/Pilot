# Handoff Claude — extracción PDF 2026-04-06



=== Página 1 ===
GANO DIGITAL
Handoff Claude - Reporte extendido
Auditoria interna | Comercial + UX | Skills | Pendientes | GitHub
Fecha: 06/04/2026 | HEAD 7c5b876f | main
Alcance del documento
• Síntesis lacónica: estado técnico, negocio Reseller, brecha producción.
• Tablero gamificado (niveles) + barras de completitud por dimensión.
• Inventario de skills .gano-skills + colas agentes + dispatch Claude.
• Plan comercial/UX del día: curación de contenidos, tablas, formatos Elementor.
• Matrices de riesgo, KPIs, roadmap 7/30/90 días.
Pág. 1

=== Página 2 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
1. Resumen ejecutivo - KPIs
Modelo:  hosting  WordPress  Colombia,  Reseller  GoDaddy,  vitrina  SOTA.  Codigo  Fases  1-3  en  repo;  paridad  servidor  y
comercializacion RCC pendientes de cierre operativo.
1.1 Métricas repositorio (instantánea)
Métrica Valor Nota Crítico
Colas
agent-queue
(tareas)
64 7 JSON Alto volumen
Dispatch  Claude
(tareas)
12 memory/claude/dispatch-queue.json Repo-only
Skills .gano-skills 10 carpetas con SKILL.md Guía agentes
Pendientes
TASKS.md ([ ])
29 parseado automático Acción humana
Git HEAD 7c5b876f main Verificar push
1.2 Barras — dimensión de madurez (estimación interna)
Escala subjetiva para priorizar; no es auditoría financiera.
Código endurecido (F1–3) en repo 92%
Despliegue en producción 38%
Contenido/UX vitrina (sin Lorem) 44%
Comercio Reseller (RCC + pfids) 35%
SEO panel (GSC/Rank Math) 28%
Automatización GitHub Actions 78%
Pág. 2

=== Página 3 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
2. Tablero gamificado — niveles de operación
Cada nivel desbloquea el siguiente en la práctica (dependencia real, no cosmética).
NIVEL 0 — Repo íntegro +100 pts
main estable; CI 01/02; colas JSON validadas.
Progreso estimado nivel 100%
NIVEL 1 — Seguridad servidor +150 pts
wp-file-manager fuera; parches F1–3 desplegados o 05 OK.
Progreso estimado nivel 55%
NIVEL 2 — SEO técnico vivo +120 pts
Gano SEO + GSC + Rank Math configurados; sin placeholders críticos schema.
Progreso estimado nivel 40%
NIVEL 3 — Comercio RCC +200 pts
pfids reales; CTAs shop → carrito; smoke checkout.
Progreso estimado nivel 35%
NIVEL 4 — Comercial/UX hoy +250 pts
Propuesta página: copy, tablas, jerarquía visual, Elementor alineado.
Progreso estimado nivel 30%
NIVEL 5 — Plataforma (Fase 4+) +300 pts
Portal cliente, soporte, facturación local si aplica decisión.
Progreso estimado nivel 12%
Pág. 3

=== Página 4 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
3. Inventario skills (.gano-skills)
Incluye skills añadidas o relevantes para pipeline comercial, infra y contenido.
Skill Resumen (1ª línea útil) Uso Prioridad
gano-blender-3d-assets-gener
ator
**Última actualización:** 2026-04-03 Assets 3D → web Media (pipeline)
gano-blender-to-website-pipeli
ne
**Última actualización:** 2026-04-03 Assets 3D → web Media (pipeline)
gano-content-audit name: gano-content-audit Agente / Cursor Alta
gano-cpanel-ssh-management **Última actualización:** 2026-04-03 SSH/cPanel Media (pipeline)
gano-fase4-plataforma name: gano-fase4-plataforma Billing futuro Media
gano-github-copilot-orchestrati
on
name: gano-github-copilot-orchestration GitHub Actions Alta
gano-multi-agent-local-workflo
w
name: gano-multi-agent-local-workflow Flujo local Media
gano-session-security-guardia
n
name: gano-session-security-guardian Sesión / higiene Media
gano-wompi-fixer name: gano-wompi-fixer Legado pago Media
gano-wp-security name: gano-wp-security Sesión / higiene Media
Pág. 4

=== Página 5 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
4. Pendientes — TASKS.md (items [ ])
Lista automática; truncar si excede layout. Verificar en fuente para wording exacto.
ID Tarea Tipo Acción
1 [CRÍTICO] Sincronizar parches Fases 1–3 con el servidor — Opciones: Crítico Humano panel
2 [CRÍTICO] Eliminar wp-file-manager del servidor — Ejecutar workflow automatizado: Crítico Humano panel
3 [ALTA]  Configurar  datos  SEO  (Empresa  Digital)  en  wp-admin  →  Ajustes  →  Gano
SEO:
SEO Humano panel
4 [ALTA] Configurar Google Search Console Ops Humano panel
5 [ALTA] Configurar Rank Math en WordPress SEO Humano panel
6 [ALTA] Reemplazar todo el Lorem ipsum y texto placeholder — Requiere acceso al
panel de Elementor
UX/Cont Humano panel
7 [ALTA] Eliminar testimonios falsos y métricas infladas Ops Humano panel
8 [ALTA] Crear página Nosotros real con manifiesto SOTA UX/Cont Humano panel
9 [MEDIA] Habilitar 2FA en wp-admin (Wordfence ya instalado) Ops Humano panel
10 [MEDIA] Ejecutar y limpiar plugins de fase (1, 2, 3, 6, 7) Ops Humano panel
11 Depurar Catálogo en GoDaddy Reseller Control Center: Comercio Humano panel
12 Mapeo de UI SOTA -> Reseller: Comercio Humano panel
13 Prueba de Flujo de Checkout: Comercio Humano panel
14 Instalar soporte/chat: FreeScout o similar para atención a cliente, ya que el soporte
inicial lo da el reselle…
Ops Humano panel
15 StatusPage (Upptime o Cachet) Ops Humano panel
16 Conectar Chat IA a LLM real (n8n / Make / API propia) Ops Humano panel
17 Programa de afiliados / sub-resellers Ops Humano panel
18 Cola  API  Mercado  Libre  +  GoDaddy  (research):
[`.github/agent-queue/tasks-api-integrations-research.json`](.git…
Ops Humano panel
19 Cola  Guardián  seguridad  (higiene  +  cierre  de  sesión):
[`.github/agent-queue/tasks-security-guardian.json`](.gi…
Ops Humano panel
20 Prompt  Copilot:  en  #54–#68  usa  el  bloque  “oleada  3”  en
[`.github/prompts/copilot-bulk-assign.md`](.github/prom…
Ops Humano panel
21 Actions  →  ejecutar  09  ·  Sembrar  issues  homepage  una  vez  (7  issues
`homepage-fixplan`)
Ops GH Actions
22 Actions → 08 · Sembrar cola Copilot (`all` o por ámbito) → asignar Copilot coding
agent en issues generados
Ops GH Actions
23 Rotación de tokens y limpieza de remotes con credenciales (al cierre del workflow
de despliegue)
Ops Humano panel
24 Menú principal asignado en wp-admin (y/o header Elementor) tras despliegue UX/Cont Humano panel
25 Sustituir  Lorem  /  placeholders  usando
`memory/content/homepage-copy-2026-04.md` como fuente
UX/Cont Humano panel
26 Hero: imagen + attachment coherente con diseño Ops Humano panel
27 Ajustes de layout: aplicar clases `gano-el-stack` / `gano-el-layer-*` + CTA final con
iconos reales
Ops Humano panel
28 Ejecutar  en  tu  PC:  `python  scripts/check_dns_https_gano.py`  y  comparar  con
plantilla/runbook tras cualquier ca…
Ops Humano panel
29 Aplicar registros y SSL en panel (humano) — la cola `tasks-infra-dns-ssl.json` sirve
para issues Copilot que d…
Ops GH Actions
Pág. 5

=== Página 6 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
5. Propuesta comercial hoy — UX y contenidos
5.1 Objetivo del día
Cerrar la narrativa comercial de la página: mensaje único, prueba social honesta, jerarquía de secciones, CTAs al carrito Reseller,
coherencia con 4 ecosistemas.
5.2 Pilares de contenido (curar)
• Hero: promesa + CTA único (no competir con pilares).
• Prueba: diferenciales técnicos verificables (NVMe, seguridad, soporte) sin métricas inventadas.
• Ecosistemas: tabla precios COP + vínculo pfid cuando RCC esté mapeado.
• Confianza: logos/partners solo si hay acuerdo; eliminar testimonios genéricos.
• Legal: enlaces términos/privacidad coherentes con copy comercial.
5.3 Tablas y formatos (Elementor)
Módulo Formato Criterio Done
Comparativa planes Tabla responsive Máx. 4 columnas; contraste AA [ ]
Pricing COP Número + texto Alineación decimal; sin decimales si COP entero [ ]
FAQ comercial Acordeón o lista Schema FAQ ya en MU; no duplicar mentiras [ ]
CTA primario Botón + microcopy Una acción por bloque [ ]
Sticky mobile Barra inferior opcional No tapar CTA hero [ ]
5.4 Fuentes de copy en repo
memory/content/homepage-copy-2026-04.md
memory/content/README-CONTENT-INDEX-2026.md
memory/research/gano-wave3-brand-ux-master-brief.md
wp-content/themes/gano-child/templates/shop-premium.php
Pág. 6

=== Página 7 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
5.5 Checklist UX (sesión)
• Menú primary asignado; labels ES-CO; destinos correctos.
• Sin Lorem; iconos reales en CTA final.
• Hero: imagen + attachment válidos; LCP hook coherente (ver elementor-home-classes).
• skip link #gano-main-content verificado.
• Shop: PENDING_RCC → pfid real antes de campaña paga.
Pág. 7

=== Página 8 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
6. GitHub — workflows y agentes
12 workflows; 08 sembrar colas; 07 validar JSON; 09 homepage; 04/05/12 ops.
ID Nombre Rol Cuando
01 CI PHP Gate push/PR
02 TruffleHog Secretos push/PR
03 Labeler PR sync PR
04 Deploy SSH push paths
05 Parches Ops manual
06 Labels Repo manual
07 Valida JSON Agentes push cola
08 Seed Copilot Agentes manual
09 Homepage issues Agentes manual
10 Orquestar Legacy manual
11 Setup Copilot Agentes PR yaml
12 wp-file-mgr Ops manual
Pág. 8

=== Página 9 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
7. Estrategia — líneas definidas
7.1 Posicionamiento
• Hosting WordPress Colombia; honestidad Reseller; sin simular datacenter propio.
• Diferencial: seguridad + rendimiento + soporte en español; sin inflar métricas.
7.2 Embudo
Tráfico → Landing / SEO → Comparativa ecosistemas → CTA shop → RCC checkout → Onboarding
7.3 KPIs comerciales (seguimiento)
KPI Meta sugerida Medición Frecuencia
CTR CTA shop ↑ vs baseline GA4 evento Semanal
Tiempo checkout < 3 min RCC Por campaña
Bounce hero < 45% GA4 Semanal
Errores 404 0 críticos GSC Mensual
Pág. 9

=== Página 10 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
8. Riesgos — matriz
Riesgo P Impacto Mitigación
Deploy sin secrets H Paridad rota 05 + 04
wp-file-manager C Superficie ataque 12
Copy inflado M Marca content-audit
DNS mal propagado M Caída script DNS + runbook
Issues obsoletos GitHub B Ruido gh-issue-close
Pág. 10

=== Página 11 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
9. Roadmap
7 días
• Secrets + 05/04
• Cerrar Lorem crítico
• GSC propiedad
30 días
• Checkout completo RCC
• 2FA admin
• Rank Math fino
90 días
• Portal cliente (decisión)
•  soporte FreeScout
• Status page eval
Pág. 11

=== Página 12 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
10. Apéndice A — estadísticas repo
Agregados para auditoría; regenerar PDF tras cambios grandes.
Concepto Valor — —
Archivos .md en memory/ 67
Workflows .yml 12
Plugins gano-* en repo 7
Pág. 12

=== Página 13 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
11. Apéndice B — glosario lacónico
RCC Reseller Control Center GoDaddy
pfid ID producto Private Label / carrito
MU Must-use plugin wp-content/mu-plugins
SOTA Estado del arte; copy aspiracional sin mentir
LCP Largest Contentful Paint; hero
Pág. 13

=== Página 14 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
12. Apéndice C — control de calidad contenido
Criterios para revisión interna antes de publicar bloques comerciales.
Dimensión Criterio Pass Notas
Tono Técnico-claro; Colombia; sin jerga vacía. [ ]
Hechos Cifra solo con fuente; si no hay fuente → cualitativo. [ ]
CTA Verbo + beneficio; sin “clic aquí” solamente. [ ]
Accesibilidad Contraste; focus visible; textos alternativos. [ ]
Performance Imágenes WebP donde aplique; lazy below fold. [ ]
Legal Enlaces políticas actualizados; cookies si aplica. [ ]
Pág. 14

=== Página 15 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
13. Apéndice D — escenarios de prueba UX
• Desktop 1920: hero above fold; CTA visible sin scroll excesivo.
• Mobile 390: menú usable; tablas sin overflow horizontal destructivo.
• Checkout: un solo flujo hasta RCC; sin pasos muertos.
• 404: plantilla on-brand; buscador o volver inicio.
Pág. 15

=== Página 16 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
14. Apéndice E — mapa de dependencias (texto)
CONTENIDO (memory/content) → ELEMENTOR → RCC (pfids) → ANALYTICS
SEO MU (gano-seo) → RANK MATH panel → GSC
CI (01/02) → PR → merge → 04 opcional
Pág. 16

=== Página 17 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
15. Cola dispatch Claude (tareas cd-repo-*)
12 tareas versionadas; ejecutar con scripts/claude_dispatch.py.
ID Título Prio Humano post
cd-repo-001 Alinear checkboxes GitHub en TASKS.md (08/09/10) con criterios condicionales 1 No
cd-repo-002 Crear guía RCC → PFID (checklist para Diego) 1 Sí
cd-repo-005 Checklist manual: eliminar wp-file-manager (alternativa al workflow 12) 1 Sí
cd-repo-003 Plantilla para cerrar issues de GitHub tras merge en main 2 Sí
cd-repo-004 Checklist wp-admin: Gano SEO, Rank Math, Google Search Console 2 Sí
cd-repo-009 Validar colas de agentes y sintaxis PHP Gano 2 No
cd-repo-006 Auditar cola Copilot vs código actual (tareas posiblemente obsoletas) 3 No
cd-repo-007 Auditar tokens CSS --gano-gold y :root en style.css del child 3 No
cd-repo-008 Documentar hook LCP / hero (MutationObserver) vs estructura Elementor 3 No
cd-repo-010 Checklist Wordfence / 2FA wp-admin 3 Sí
cd-repo-011 Sincronizar memory/claude/02-pendientes con TASKS tras cambios locales 4 No
cd-repo-012 Resumen de sesión para memory/sessions/ (opcional) 5 No
Pág. 17

=== Página 18 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
16. Colas GitHub Copilot — tareas por JSON
Archivo Tareas % del total Notas
tasks-api-integrations-research.json 5 7.8% API
tasks-infra-dns-ssl.json 6 9.4% infra
tasks-security-guardian.json 5 7.8% guard
tasks-wave2.json 8 12.5%
tasks-wave3.json 15 23.4%
tasks-wave4-ia-content.json 8 12.5%
tasks.json 17 26.6%
Pág. 18

=== Página 19 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
17. Páginas SOTA (gano-content-importer / estrategia)
Referencia editorial; alinear tono y CTA con Reseller, sin prometer infra propia.
# Título Categoría Uso
1 Arquitectura NVMe: La Muerte del SSD Tradicional infra Blog/SEO
2 Zero-Trust Security: El Fin de las Contraseñas seguridad Blog/SEO
3 Gestión Predictiva con AI: Cero Caídas, Cero Sorpresas IA Blog/SEO
4 Soberanía Digital en LATAM: Tus Datos, Tu Control estrategia Blog/SEO
5 Headless WordPress: La Velocidad Absoluta rendimiento Blog/SEO
6 Mitigación DDoS Inteligente: Firewall de Nueva Generaci seguridad Blog/SEO
7 La Muerte del Hosting Compartido: El Riesgo Invisible infra Blog/SEO
8 Edge Computing: Contenido a Cero Distancia de tu Client infra Blog/SEO
9 Green Hosting: Infraestructura Sostenible para tu Negoc infra Blog/SEO
10 Cifrado Post-Cuántico: La Bóveda del Futuro seguridad Blog/SEO
11 CI/CD Automatizado: Nunca Más Rompas tu Tienda en Vivo rendimiento Blog/SEO
12 Backups Continuos en Tiempo Real: Tu Máquina del Tiempo rendimiento Blog/SEO
13 Skeleton Screens: La Psicología de la Velocidad Percibi rendimiento Blog/SEO
14 Escalamiento Elástico: Sobrevive a tu Propio Éxito Vira infra Blog/SEO
15 Self-Healing: El Ecosistema que se Cura Solo infra Blog/SEO
16 Micro-Animaciones e Interacciones Hápticas: Diseño que UX Blog/SEO
17 HTTP/3 y QUIC: El Protocolo que Rompe la Congestión rendimiento Blog/SEO
18 Alta Disponibilidad (HA): La Infraestructura Indestruct infra Blog/SEO
19 Analytics Server-Side: Privacidad, Velocidad y Datos Re analytics Blog/SEO
20 El Agente IA de Administración: Tu Infraestructura Habl IA Blog/SEO
Pág. 19

=== Página 20 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
18. Matriz — mensaje × etapa de embudo
Etapa Visitante Mensaje clave CTA
Awareness Frío Problema: hosting lento/inseguro Leer pilares
Consideración Tibio Solución: ecosistemas + Reseller Comparar planes
Decisión Caliente COP claro; sin fricción Comprar en shop
Post-compra Cliente Onboarding RCC Soporte / docs
Pág. 20

=== Página 21 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
19. Git reciente (35 líneas)
7c5b876f docs: nota salida cPanel/DNS + reanudar agentes (workflow 08)
771c0da2 docs: playbook agentes/asistentes, guía cierre issues, DNS script usage; TASKS infra al día
041a5772 fix(ci): deduplicar colas agentes sin Search API (08/10), 09 solo abiertos, CI paths
be5d29d2 docs(claude): actualizar handoff abril 2026 (7 colas, guardián, CI, commits ref)
a7a1e353 feat(security): cola guardián Copilot, skill cierre sesión y playbook ops
f8f3ddfc docs(pdf): actualizar generate_board_report_pdf al estado abril 2026 (oleadas, CI, referencias)
a34e4a13 fix(ci): labeler v6 + PHP lint workflow 11; auditoría Actions
076ef8bf docs: SOTA operativo (GitHub Copilot), progreso consolidado y skills alineadas
58d3d626 feat(github): cola Copilot API ML/GoDaddy + informe SOTA y cableado CI
fdcf509f docs: especificación menú `primary` — orden, etiquetas ES-CO y destinos (#106)
c9f17976 [agent] Índice ops: enlazar DNS/HTTPS docs en TASKS y memory/claude FAQ (#113)
dd916a01 docs(infra): documento canónico apex vs www, redirecciones y HSTS — gano.digital (#112)
00ab1342 [dns] Checklist HTTPS WordPress administrado — mixed content, Really Simple SSL, redirecciones (#111)
57c0d7ac Initial plan (#110)
e6089c19 [agent] Índice maestro memory/content/ — capas oleada 3 + 4 y lectura recomendada (#109)
ab63e790 [agent] Paquete coherencia Nosotros + Contacto + Legal (copy y enlaces cruzados) (#108)
331a822d docs(ops): plantilla registros DNS esperados con placeholders — apex + www (dns-expected-records) (#1
a4f9dc4f docs(infra): Runbook DNS + HTTPS — gano.digital en GoDaddy Managed WP (#105)
8dcc146a docs: Pilares SEO — clusters narrativos y orden de publicación 2026 (#104)
cdb5fcab [agent] Especificación ejecutable orden de bloques homepage — AIDA + CTA ecosistemas (#103)
33b3b382 docs(wave4): brecha IA propuesta vs inventario de páginas — gap-ia-vs-live-inventory-2026.md (#102)
0e086464 docs: Matriz productos/servicios — vitrina, slugs, CTAs y GANO_PFID (coherencia comercial) (#101)
bdaf1943 docs: Plan maestro de contenidos Gano Digital 2026 (#100)
d5562c78 docs: entregables cola dispatch (noche) + nota continuidad mañana
4cda1ad9 feat(github): oleada 4 contenido + cola infra DNS/HTTPS para Copilot
3d1ea709 docs: generador PDF auditoría desarrollo para handoff Claude
0e7cf9f4 chore(vscode): tareas Run Task para Claude dispatch y cola Copilot
d0d733d2 feat(claude-dispatch): cola JSON + scripts next/complete para tareas en repo
9ebb100a docs(memory): carpeta memory/claude para contexto y continuidad
8f816713 docs: skill Copilot 01-12, recordatorio Diego, workflow 12 alineado
6d89d159 docs: sesión consolidación PRs Copilot 2026-04-03
79ab3bbc style: escala tipográfica wave3 + utilidades + doc visual-tokens
57b894ee fix(reseller): locale es-CO/COP + shop-premium alineado a GANO_PFID
ba1af316 feat(a11y): skip link, focus-visible WCAG + notas wave3
0d3691f7 docs: especificación OG/Twitter Card wave3
Pág. 21

=== Página 22 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
20. Pendientes TASKS — continuación
(Sin más ítems [ ] tras truncado inicial.)
Pág. 22

=== Página 23 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
21. Inventario memory/*.md (rutas)
01  memory\archive\README-ARCHIVO-WOMPI-Y-PASARELAS-LEGACY.md
02  memory\claude\01-historial-y-contexto-cursor-2026-04.md
03  memory\claude\02-pendientes-detallados.md
04  memory\claude\03-referencia-tecnica-y-faq.md
05  memory\claude\README.md
06  memory\claude\css-root-audit-2026-04.md
07  memory\claude\dispatch-prompt.md
08  memory\claude\gh-issue-close-guide.md
09  memory\claude\lcp-hook-notes.md
10  memory\claude\obsolete-copilot-tasks.md
11  memory\commerce\rcc-pfid-checklist.md
12  memory\content\README-CONTENT-INDEX-2026.md
13  memory\content\content-audit-report.md
14  memory\content\content-master-plan-2026.md
15  memory\content\ecosystems-copy-matrix-wave3.md
16  memory\content\elementor-architecture-wave3.md
17  memory\content\elementor-home-classes.md
18  memory\content\faq-schema-candidates-wave3.md
19  memory\content\footer-contact-audit-wave3.md
20  memory\content\gap-ia-vs-live-inventory-2026.md
21  memory\content\homepage-copy-2026-04.md
22  memory\content\homepage-section-order-spec-2026.md
23  memory\content\homepage-story-arc-wave3.md
24  memory\content\microcopy-wave3-kit.md
25  memory\content\navigation-primary-spec-2026.md
26  memory\content\pilares-seo-narrative-clusters-2026.md
27  memory\content\products-services-pages-matrix-2026.md
28  memory\content\raster-asset-spec-wave3.md
29  memory\content\site-ia-wave3-proposed.md
30  memory\content\social-preview-spec-wave3.md
31  memory\content\trust-and-reseller-copy-wave3.md
32  memory\content\trust-pages-bundle-2026.md
33  memory\content\ux-a11y-wave3-notes.md
34  memory\content\visual-tokens-wave3.md
35  memory\notes\nota-diego-recomendaciones-2026-04.md
36  memory\notes\nota-salida-cpanel-dns-y-agentes-2026-04.md
37  memory\notes\plugins-de-fase.md
38  memory\ops\agent-playbook-asistentes-2026-04.md
39  memory\ops\dns-expected-records-template-2026.md
40  memory\ops\dns-https-godaddy-runbook-2026.md
41  memory\ops\dns-verify-script-usage-2026.md
42  memory\ops\gano-seo-rankmath-gsc-checklist.md
43  memory\ops\github-actions-audit-2026-04.md
44  memory\ops\https-wordpress-managed-checklist-2026.md
45  memory\ops\remover-wp-file-manager-checklist.md
46  memory\ops\security-end-session-checklist.md
47  memory\ops\security-guardian-playbook-2026.md
48  memory\ops\url-canonical-gano-digital-2026.md
49  memory\ops\wordfence-2fa-checklist.md
50  memory\projects\gano-digital.md
51  memory\research\competitive-framework-colombia-hosting.md
52  memory\research\fase4-plataforma.md
53  memory\research\gano-wave3-brand-ux-master-brief.md
54  memory\research\motion-and-3d-policy-gano.md
55  memory\research\sota-apis-mercadolibre-godaddy-2026-04.md
56  memory\research\sota-operativo-2026-04.md
57  memory\research\ux-heuristics-checklist-gano.md
58  memory\sessions\2026-04-01-reporte-cursor-descargas-y-herramientas.md
59  memory\sessions\2026-04-02-ejecucion-continuidad.md
60  memory\sessions\2026-04-02-pr-triage-copilot.md
61  memory\sessions\2026-04-02-progreso-consolidado.md
62  memory\sessions\2026-04-02-sota-investigacion-skills-y-continuidad.md
63  memory\sessions\2026-04-03-claude-dispatch.md
64  memory\sessions\2026-04-03-consolidacion-prs-copilot.md
65  memory\sessions\2026-04-03-noche-continuidad-manana.md
66  memory\sessions\2026-04-03-recordatorio-api-integrations-y-prs.md
67  memory\smoke-tests\checkout-reseller-2026-04.md
Pág. 23

=== Página 24 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
22. Ecosistemas comerciales — recordatorio
Ecosistema Rol copy Pricing CTA
Starter Entrada COP en schema shop
Business PYME COP shop
Premium Alto tráfico COP shop
Enterprise Misión crítica COP shop
Pág. 24

=== Página 25 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
23. Checklist sesión comercial (granular)
• [ ] Bloque 1: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 2: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 3: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 4: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 5: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 6: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 7: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 8: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 9: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 10: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 11: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 12: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 13: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 14: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 15: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 16: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 17: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 18: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 19: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 20: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 21: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 22: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 23: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 24: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 25: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 26: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 27: copy revisado; sin superlativos vacíos; CTA único.
• [ ] Bloque 28: copy revisado; sin superlativos vacíos; CTA único.
Pág. 25

=== Página 26 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
24. Objecciones — respuesta tipo
Objección Respuesta Evidencia No hacer
Caro TCO vs tiempo interno; RCC explícito. RCC / shop Descuento falso
Ya tengo hosting Migración medible; prueba velocidad. Lighthouse Lock-in vago
No confío Términos; marca Reseller. Legal Datos inventados
Soporte Canal; expectativas. TASKS/docs SLA inventado
Pág. 26

=== Página 27 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
25. Variables de estilo — Elementor
Token Uso Riesgo Acción
gano-el-* Layout Inconsistencia móvil Auditar breakpoints
Tipografía Jerarquía Mezcla >3 familias Limitar a 2
Color Marca Contraste bajo WCAG AA
Espaciado Ritmo Secciones apiñadas Escala 8px
Pág. 27

=== Página 28 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
26. Métricas de calidad (Definition of Done)
Definir antes de publicar bloque comercial.
Coherencia marca (rellenar %) 0%
Precision tecnica (rellenar %) 0%
Accesibilidad (rellenar %) 0%
Performance (LCP) (rellenar %) 0%
Conversion (CTA) (rellenar %) 0%
Pág. 28

=== Página 29 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
27. Anexo: prompts utiles (Claude / Cursor)
• Resume el bloque hero en 2 lineas + 1 CTA; tono CO; sin superlativos.
• Lista objeciones probables para hosting WP y respuesta de 1 linea cada una.
• Tabla 4 columnas: seccion Elementor | copy | CTA | estado [ ].
• Audita Lorem restante: grep mental por seccion homepage.
• Compara TASKS.md pendientes vs issues GitHub abiertos; sugerir cierres.
Salida esperada
Markdown estructurado o checklist; PR solo si toca archivos memory/ o tema.
Pág. 29

=== Página 30 ===
Gano Digital — Handoff Claude · Comercial/UX + auditoría interna
28. Control del informe PDF
Campo Valor
Script scripts/generate_handoff_claude_comm
ercial_extended_pdf.py
Dependencia fpdf2 (pip install fpdf2)
Regenerar python
scripts/generate_handoff_claude_comm
ercial_extended_pdf.pyVersionar el PDF en almacen interno; no commitear si politica ignora binarios.
Cierre
Generado automáticamente. Validar cifras sensibles en GitHub y paneles. No sustituye asesoría legal/fiscal. Mínimo 30 páginas solicitado: si el render
fuera menor, regenerar con más datos en TASKS/skills.
Pág. 30