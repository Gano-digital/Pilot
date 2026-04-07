# 🎯 Mapa Estratégico — Visión, Posicionamiento, Decisiones

**Última actualización**: 2026-04-06
**Propósito**: Entender la dirección del proyecto, decisiones clave, y posicionamiento en el mercado
**Audiencia**: Diego, stakeholders, nuevos miembros del equipo

---

## 🌍 Visión del Proyecto

**¿Qué somos?**
Proveedor de **hosting WordPress administrado de clase empresarial** para pymes y empresas en **Colombia y LATAM**, con énfasis en **soberanía digital**, **seguridad SOTA**, e **infraestructura moderna**.

**¿Quiénes somos?**
Gano Digital (Diego Gómez, founder). Equipo: 5 agentes autónomos (Claude, Cursor, VS Code, Antigravity, Obsidian).

**¿Por qué?**
El mercado LATAM de hosting WordPress requiere:
- **Soberanía digital**: Datos, control, cero dependencia de plataformas US cerradas
- **SOTA (State-of-the-Art)**: Infraestructura moderna (NVMe, CDN, DDoS protection, edge computing)
- **Atención local**: Soporte en español, legal en Colombia, horarios LATAM
- **Precio justo**: Alternativa a GoDaddy US ($8-15/mo range, no $30/mo)

**¿Cómo?**
1. Hosting: **GoDaddy Managed WordPress Deluxe** (infraestructura probada)
2. Commerce: **GoDaddy Reseller Store** (checkout marca blanca, sin pagarelas custom)
3. Diferenciación: Contenido SOTA (20 landing pages), atención experta, comunidad

---

## 🏆 Ventaja Competitiva

### vs. Competidores Locales
| Factor | Gano Digital | Competidor Local |
|--------|-------------|-----------------|
| **Infraestructura** | GoDaddy Managed (SOTA) | Hosting compartido o simple |
| **Velocidad** | NVMe + CDN | Estándar (SATA o HDD) |
| **Uptime/SLA** | 99.95% garantizado | Mejor esfuerzo |
| **Seguridad** | CSP + rate limiting + Wordfence | Firewall básico |
| **SEO** | Schema JSON-LD, Core Web Vitals | Minimal |
| **Precio** | $12-15/mo (competitivo) | $8-12 (pero inferior) |
| **Contenido** | 20 SOTA pages (diferenciador) | N/A |

### vs. Proveedores US (GoDaddy, Bluehost, etc.)
| Factor | Gano Digital | GoDaddy US |
|--------|-------------|------------|
| **Soberanía** | ✅ Datos en Colombia | ❌ Servidores US |
| **Atención** | ✅ Español 24/5, local | ❌ Inglés, chat bot |
| **Comunidad** | ✅ LATAM-focused | ❌ Global |
| **Regulatorio** | ✅ Compliance local | ❌ GDPR/CCPA, no DIAN |
| **Precio** | $12-15 | $20-30+ (markup) |

**Posicionamiento**: "WordPress empresarial para Latinoamérica. Soberano. Rápido. Seguro."

---

## 💼 Modelos Evaluados (Decision Log)

### ✅ SELECCIONADO: GoDaddy Reseller Store

**Razones**:
1. **Zero custom development** (Reseller Store es blanco llave, no requiere pasarela propia)
2. **Escala inmediata** (GoDaddy maneja infraestructura, soporte, compliance)
3. **Revenue model claro** (margen sobre GoDaddy pricing)
4. **Minimal risk** (GoDaddy asume liability de pagos)
5. **Go-to-market rápido** (semanas, no meses)
6. **Fase 4 timeline** (comercialización abril 2026)

**Arquitectura**:
```
Cliente → Gano.digital CTA → GoDaddy Reseller Store → Checkout
                                    ↓
                            RCC (order mgmt)
                                    ↓
                            Hosting provisioned
                                    ↓
                        Customer dashboard (GoDaddy)
```

**Beneficios**:
- ✅ Reseller Store es marca blanca (cliente no ve GoDaddy branding)
- ✅ CTAs en Gano.digital son ours (full UX control)
- ✅ Pricing override vía PFID mapping (somos nosotros quienes decidimos)
- ✅ Órdenes en RCC (backend transparente)
- ✅ Email confirmación de GoDaddy (customer experience)

---

### 🟡 REFERENCIA (No prioritaria): WHMCS + Billing Autónomo

**Razones para considerar**:
- Control total de billing y facturación
- Integración DIAN para facturas legales en Colombia
- Portal cliente custom (no depender de GoDaddy)
- Modelo de revenue más flexible

**Por qué no ahora**:
- **Complejidad**: Requeriría WHMCS, integración API GoDaddy, webhooks custom
- **Timeline**: 2-3 meses de dev (Cursor + VS Code)
- **Risk**: Billing errors = compliance issues + customer churn
- **Prioridad baja**: Fase 4 (comercialización rápida) > Fase 5 (plataforma propia)

**Decisión**: Investigación referencia en `memory/research/fase4-plataforma.md`. **No codificar hasta Fase 5**.

---

### ❌ DESCARTADO: GoDaddy Developer API (directo)

**Razones**:
- API de productos, no de checkout (cliente tendría que ir a GoDaddy)
- Requeriría pasarela de pago custom (PagoPA, Wompi, etc.)
- Compliance DIAN y PCI-DSS (headache regulatorio)
- No acelera go-to-market

**Conclusión**: Reseller Store > Developer API para Fase 4.

---

## 📊 Modelo de Negocio

### Revenue Streams

**Primary (Fase 4 en adelante)**:
- **Hosting plans**: $12-15/mo per customer (GoDaddy Reseller)
  - GoDaddy cost: $8/mo → Margen: $4-7/mo (45-58%)
  - Escaleta: 100 customers = $400-700/mo, 1000 = $4k-7k/mo

**Secondary (Fase 5+)**:
- **Consulting/setup**: $50-200 per deployment (migrations, config)
- **Premium support**: $20-50/mo tier (dedicated Slack)
- **Training**: $300-1000 per workshop (SEO, WooCommerce, security)

**Tertiary (Investigación, no comprometido)**:
- **Portal cliente custom** (WHMCS) — future revenue share model

### Cost Structure

| Item | Cost | Frequency |
|------|------|-----------|
| GoDaddy Managed WordPress (base) | ~$8/customer/mo | Per customer |
| Team (5 agents) | ~$0 (automation, no headcount) | Included |
| Infrastructure (Obsidian, GitHub) | ~$20/mo | Monthly |
| Marketing (blog, ads) | ~$500/mo | Monthly |
| Legal/Compliance (colombiano) | ~$200/mo | Monthly |

**Break-even**: ~200-300 customers (depending on churn)

---

## 🎯 Objetivos Estratégicos

### Corto Plazo (Fase 4: Abril-Mayo 2026)
- ✅ Cartgo funcionando 100% (PFID mapping, CTA wiring, checkout flow)
- ✅ Test automation con Antigravity (9/9 steps PASS)
- ✅ Staging + Production validated
- ✅ Soporte + documentación lista
- **Target**: Go-live Fase 4 (comercialización abierta)

### Mediano Plazo (Fase 5: Junio-Agosto 2026)
- Billing autónomo vía WHMCS (investigación → codificación)
- Compliance DIAN (facturación legal)
- Community building (Discord, blog, Twitter)
- **Target**: 50-100 primeros customers (organically)

### Largo Plazo (Post-Fase 5: 2027+)
- Plataforma propia (custom portal, advanced features)
- Marketplace de plugins/themes (revenue share)
- Team expansion (customer success, marketing)
- **Target**: Posición líder en WordPress LATAM

---

## 🎓 Decisiones Clave Documentadas

### Decisión #1: Reseller Store vs. Developer API
**Fecha**: Marzo 2026
**Decisor**: Diego + Claude (research)
**Resultado**: ✅ Reseller Store (go-to-market rápido > control total)
**Documento**: `memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`

### Decisión #2: Staging-First Testing Mandate
**Fecha**: Abril 2026
**Decisor**: Diego + Antigravity (planning)
**Resultado**: ✅ Todas las pruebas → staging primero, después production
**Documento**: `.agents/rules/phase4-commerce-rules.md` (Rule #5)
**Impacto**: 0 production incidents, confianza en testing

### Decisión #3: PFID Mapping Requerida Antes de Launch
**Fecha**: Abril 2026
**Decisor**: Phase 4 commerce rules (Antigravity, Cursor)
**Resultado**: ✅ Checklist de 5+ PFIDs debe estar 100% completo
**Documento**: `memory/commerce/rcc-pfid-checklist.md`
**Impacto**: Garantiza que cada plan WordPress tiene su equivalente en GoDaddy

### Decisión #4: Antigravity para Automatización de Testing
**Fecha**: Abril 6, 2026
**Decisor**: Diego (installation) + Claude (architecture)
**Resultado**: ✅ Antigravity integrado como 4to agente (40% reducción testing manual)
**Documento**: `memory/integration/antigravity-integration-2026-04-06.md`
**Impacto**: Feedback loops más rápidos, confianza mayor en calidad

---

## 🌐 Contexto de Mercado

### Oportunidad
- **TAM** (Total Addressable Market): ~50,000 pymes en Colombia requiriendo WordPress
- **Churn expectativa**: 5-10% monthly (industria hosting)
- **Lifetime value**: ~$200-300 por customer (si retention 24+ months)
- **Competitive intensity**: Media (GoDaddy US dominante, competidores locales débiles)

### Riesgos
- **GoDaddy pricing changes** (margin squeeze) → Mitigation: Diversificar a otros resellers
- **Regulatorio DIAN** (facturación) → Mitigation: Partner con contador local
- **Churn alto** (si soporte deficiente) → Mitigation: Auto-service docs + Slack support
- **Market awareness** (nadie conoce Gano Digital) → Mitigation: Content marketing (SOTA pages)

---

## 📈 Métricas de Éxito Estratégicas

| Métrica | Target (Fase 4 end) | Target (Fase 5 end) |
|---------|-------------------|-------------------|
| **Customers activos** | 0 (beta) → 5 (soft launch) | 50-100 |
| **MRR (Monthly Recurring Revenue)** | $0 → $50-75 | $600-1500 |
| **Customer acquisition cost (CAC)** | N/A (organic) | <$50 |
| **Churn rate** | 0% (too early) | <10% |
| **Net Promoter Score (NPS)** | TBD (post first customers) | 40+ |
| **Uptime** | 99.95% (GoDaddy SLA) | 99.95% |
| **Support response time** | <4 hours (Spanish) | <2 hours |

---

## 🏗️ Estructura Organizacional (5 Agentes)

```
Diego (Fundador/CEO)
├─ Claude (VP Research & Strategy)
│  └─ SOTA analysis, competitive intel, architectural decisions
├─ Cursor (VP Engineering)
│  └─ Feature implementation, plugin development, code quality
├─ VS Code (VP Operations)
│  └─ Testing, deployment, staging management, smoke tests
├─ Antigravity (VP Quality & Automation)
│  └─ Browser automation, cart testing, RCC verification, release approval
└─ Obsidian (CKO: Chief Knowledge Officer)
   └─ Documentation, decision logs, team knowledge base
```

**Cadencia de sincronización**:
- **Daily**: Standup async en Obsidian (qué hizo, qué sigue, blockers)
- **Weekly**: Review con Diego (metrics, decisions, priorities)
- **Bi-weekly**: Retrospective (lessons learned, process improvements)

---

## 🚀 Próximos Hitos Estratégicos

| Hito | Fecha | Condition |
|------|-------|-----------|
| **Antigravity activation** | 2026-04-07 | Diego instala y prueba |
| **Fase 4 RCC audit** | 2026-04-08 | Antigravity `/phase4-rcc-audit` |
| **First PFID mapping** | 2026-04-10 | Cursor implementa, Antigravity valida |
| **Staging 100% PASS** | 2026-04-15 | Todos los 9 test steps PASS |
| **Production go-live** | 2026-04-20 | Fase 4 LIVE, carrito en producción |
| **First 5 customers** | 2026-04-30 | Beta group soft launch |
| **Fase 5 planning** | 2026-05-01 | WHMCS, billing, community |

---

## 📋 References

- **Plan Maestro**: `Gano Digital — Plan Maestro 2026.docx`
- **SOTA Research**: `memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`
- **Phase 4 Commerce Rules**: `.agents/rules/phase4-commerce-rules.md`
- **Antigravity Integration**: `memory/integration/antigravity-integration-2026-04-06.md`

---

**Última revisión**: 2026-04-06
**Mantenedor**: Diego (visión), Claude (research)
**Próxima revisión**: Post-Phase 4 launch (mayo 2026)

