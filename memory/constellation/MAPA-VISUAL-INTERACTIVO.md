# 🌌 MAPA VISUAL INTERACTIVO — Gano Digital Constelación

**Última actualización**: 2026-04-06
**Tipo**: Visualización interactiva con Mermaid diagrams
**Propósito**: Navegación visual, relaciones de componentes, estado en tiempo real

---

## 🗺️ DIAGRAMA 1: Arquitectura General del Proyecto

```mermaid
graph TB
    subgraph "VISIÓN"
        A["🎯 Gano Digital<br/>Hosting WordPress<br/>en Colombia"]
    end

    subgraph "ESTRATEGIA"
        B["📋 Mapa Estratégico<br/>Decisiones empresariales<br/>Modelo: Reseller GoDaddy"]
    end

    subgraph "EJECUCIÓN"
        C["📅 5 Fases Roadmap<br/>F1-F3: ✅ Completadas<br/>F4: 🟠 En Progreso<br/>F5: 📅 Planeada"]
        D["👥 Ecosistema 5 Agentes<br/>Diego + Claude + Cursor<br/>+ VS Code + Antigravity"]
        E["⚙️ Arquitectura Sistemas<br/>WordPress + GoDaddy<br/>+ Reseller Store"]
    end

    subgraph "OPERACIONES"
        F["🔄 Procesos Operativos<br/>RCC Audit<br/>Cart Testing<br/>PFID Mapping"]
        G["📊 Métricas & Progreso<br/>KPIs + Dashboards<br/>Health Checks"]
    end

    subgraph "GOVERNANCE"
        H["👔 Equipo & Coordinación<br/>RACI Matrix<br/>Autoridades + Canales"]
        I["🚨 Riesgos & Dependencias<br/>Blockers + Mitigaciones<br/>CSFs"]
    end

    A --> B --> C
    A --> D
    A --> E
    C --> F
    D --> F
    E --> F
    F --> G
    F --> H
    F --> I

    style A fill:#FFD700,stroke:#000,color:#000
    style B fill:#87CEEB,stroke:#000
    style C fill:#90EE90,stroke:#000
    style D fill:#DDA0DD,stroke:#000
    style E fill:#F0E68C,stroke:#000
    style F fill:#FFB6C1,stroke:#000
    style G fill:#98FB98,stroke:#000
    style H fill:#87CEEB,stroke:#000
    style I fill:#FF6347,stroke:#000,color:#fff
```

---

## 🔄 DIAGRAMA 2: Ciclo de Desarrollo Phase 4 (Crítico)

```mermaid
graph LR
    Start["🟢 Inicio: Diego activa<br/>Antigravity"]
    RCCAudit["📋 Step 1: RCC Audit<br/>Identificar gaps"]
    PFIDMapping["🗺️ Step 2: PFID Mapping<br/>5 PFIDs requeridas"]
    StagingTest["🧪 Step 3: Testing Staging<br/>9/9 PASS = ready"]
    ProdTest["🚀 Step 4: Testing Prod<br/>Validación final"]
    GoLive["🎉 Step 5: GO LIVE<br/>Apr 20, 2026"]

    Start --> RCCAudit
    RCCAudit --> PFIDMapping
    PFIDMapping --> StagingTest
    StagingTest --> ProdTest
    ProdTest --> GoLive

    style Start fill:#FFD700,stroke:#000,color:#000
    style RCCAudit fill:#87CEEB,stroke:#000
    style PFIDMapping fill:#FF6347,stroke:#000,color:#fff
    style StagingTest fill:#90EE90,stroke:#000
    style ProdTest fill:#FFB6C1,stroke:#000
    style GoLive fill:#32CD32,stroke:#000,color:#fff
```

---

## 👥 DIAGRAMA 3: Ecosistema de 5 Agentes & Responsabilidades

```mermaid
graph TB
    subgraph "AGENTES"
        Diego["👨‍💼 DIEGO<br/>Founder/Conductor<br/>Decisiones estratégicas<br/>Unblock críticos"]
        Claude["🧠 CLAUDE<br/>Research/Architecture<br/>Code review<br/>Documentation"]
        Cursor["⌨️ CURSOR<br/>Code Implementation<br/>PFID Mappings<br/>Feature development"]
        VSCode["🖥️ VS CODE<br/>Operations/Deployment<br/>Staging & Production<br/>Monitoring"]
        Antigravity["🤖 ANTIGRAVITY<br/>Automation/QA<br/>Browser testing<br/>Release gate"]
        Obsidian["📚 OBSIDIAN<br/>Knowledge Management<br/>Documentation<br/>Tracking"]
    end

    subgraph "COORDINACIÓN"
        RACI["📋 RACI Matrix<br/>R: Responsible<br/>A: Accountable<br/>C: Consulted<br/>I: Informed"]
        Channels["📡 Canales<br/>Async: Obsidian/GitHub<br/>Sync: Slack<br/>Weekly: Reviews"]
    end

    Diego --> RACI
    Claude --> RACI
    Cursor --> RACI
    VSCode --> RACI
    Antigravity --> RACI
    Obsidian --> RACI

    RACI --> Channels

    style Diego fill:#FFD700,stroke:#000,color:#000
    style Claude fill:#87CEEB,stroke:#000
    style Cursor fill:#DDA0DD,stroke:#000
    style VSCode fill:#F0E68C,stroke:#000
    style Antigravity fill:#FF6347,stroke:#000,color:#fff
    style Obsidian fill:#90EE90,stroke:#000
    style RACI fill:#FFB6C1,stroke:#000
    style Channels fill:#98FB98,stroke:#000
```

---

## 🏗️ DIAGRAMA 4: Flujo Técnico — Orden de Cliente (Reseller Store)

```mermaid
sequenceDiagram
    Cliente->>gano.digital: 1. Visita sitio + CTA
    gano.digital->>Reseller: 2. Click "Get Started"<br/>(PFID-001 → Reseller Store)
    Reseller->>Cliente: 3. Carrito marca blanca
    Cliente->>Reseller: 4. Selecciona plan + checkout
    Reseller->>Payment: 5. Procesa pago
    Reseller->>RCC: 6. Crea orden en RCC
    RCC->>gano.digital: 7. Webhook → Orden confirmada
    RCC->>Cliente: 8. Email de confirmación
    Cliente->>Cliente: ✅ Hosting activado

    rect rgb(200, 255, 200)
    Note over gano.digital,Cliente: Fase 4: Validar que TODOS los pasos funcionan
    end
```

---

## 📊 DIAGRAMA 5: Estado de Progreso Phase 4 (Burn-down)

```mermaid
graph LR
    Apr6["Apr 6<br/>Inicio<br/>15%"]
    Apr8["Apr 8<br/>RCC Audit<br/>20%"]
    Apr10["Apr 10<br/>PFID 1-2<br/>35%"]
    Apr13["Apr 13<br/>PFID 3-5<br/>60%"]
    Apr15["Apr 15<br/>Tests PASS<br/>80%"]
    Apr20["Apr 20<br/>GO LIVE<br/>100% ✅"]

    Apr6 --> Apr8
    Apr8 --> Apr10
    Apr10 --> Apr13
    Apr13 --> Apr15
    Apr15 --> Apr20

    style Apr6 fill:#FFD700,stroke:#000,color:#000
    style Apr8 fill:#FF9900,stroke:#000
    style Apr10 fill:#FF6347,stroke:#000,color:#fff
    style Apr13 fill:#FFB6C1,stroke:#000
    style Apr15 fill:#90EE90,stroke:#000
    style Apr20 fill:#32CD32,stroke:#000,color:#fff
```

---

## ⚠️ DIAGRAMA 6: Matriz de Riesgos (Probabilidad vs Impacto)

```mermaid
graph TB
    subgraph "RIESGOS ACTIVOS"
        B001["🔴 B-001: Diego Antigravity<br/>Prob: 5% | Impact: HIGH<br/>Heat: 🟡 MEDIUM"]
        B002["🟡 B-002: PFID Availability<br/>Prob: 10% | Impact: HIGH<br/>Heat: 🟡 MEDIUM"]
        B003["🟡 B-003: Compliance Data<br/>Prob: 60% | Impact: NONE<br/>Heat: 🟢 LOW"]
    end

    subgraph "DEPENDENCIAS"
        D001["🔴 D-001: GoDaddy Reseller<br/>Criticidad: CRITICAL<br/>Status: ✅ Verified"]
        D002["🔴 D-002: GoDaddy WordPress<br/>Criticidad: CRITICAL<br/>Status: ✅ Verified"]
        D004["🟡 D-004: Cursor Availability<br/>Criticidad: HIGH<br/>Status: ✅ Allocated"]
    end

    subgraph "MITIGACIONES"
        M1["📋 Early Detection<br/>Test assumptions<br/>No esperar al final"]
        M2["🔄 Redundancy/Backups<br/>Identificar fallbacks<br/>Backup resources"]
        M3["⏱️ Time Buffers<br/>Agregar holgura<br/>al critical path"]
    end

    B001 --> M1
    B002 --> M1
    D001 --> M2
    D002 --> M2
    D004 --> M2
    B003 --> M3

    style B001 fill:#FF6347,stroke:#000,color:#fff
    style B002 fill:#FFA500,stroke:#000
    style B003 fill:#90EE90,stroke:#000
    style D001 fill:#FF6347,stroke:#000,color:#fff
    style D002 fill:#FF6347,stroke:#000,color:#fff
    style D004 fill:#FFA500,stroke:#000
    style M1 fill:#87CEEB,stroke:#000
    style M2 fill:#DDA0DD,stroke:#000
    style M3 fill:#F0E68C,stroke:#000
```

---

## 🎯 DIAGRAMA 7: RACI Matrix (Quién Hace Qué)

```mermaid
graph TB
    subgraph "TAREAS CRÍTICAS"
        RCC["🔍 RCC Audit<br/>R: Antigravity | A: Diego"]
        PFID["🗺️ PFID Mapping<br/>R: Cursor | A: Diego"]
        Test["🧪 Cart Testing<br/>R: Antigravity | A: Diego"]
        Deploy["📦 Deployment<br/>R: VS Code | A: Diego"]
        Code["✅ Code Review<br/>R: Claude | A: Diego"]
    end

    subgraph "GOVERNANCE"
        Decision["⚖️ Decisiones Mayores<br/>A: Diego | C: Claude"]
        Blocker["🚨 Resolver Blockers<br/>A: Diego | C: Team"]
        Sync["📞 Weekly Review<br/>A: Diego | I: All"]
    end

    style RCC fill:#87CEEB,stroke:#000
    style PFID fill:#FF6347,stroke:#000,color:#fff
    style Test fill:#90EE90,stroke:#000
    style Deploy fill:#FFB6C1,stroke:#000
    style Code fill:#DDA0DD,stroke:#000
    style Decision fill:#F0E68C,stroke:#000
    style Blocker fill:#FF6347,stroke:#000,color:#fff
    style Sync fill:#98FB98,stroke:#000
```

---

## 📈 DIAGRAMA 8: Métricas Clave Phase 4

```mermaid
gauge title PFID Mapping (0/5 = 0%)
    0 "0%"
    100 "100%"
    data --> 0

gauge title Test Pass Rate (Staging: N/A)
    0 "0%"
    100 "100%"
    data --> 0

gauge title Production Readiness (0%)
    0 "0%"
    100 "100%"
    data --> 0
```

---

## 🔗 NAVEGACIÓN RÁPIDA

### 📚 Leer en este orden:
1. **Este documento** (MAPA-VISUAL-INTERACTIVO) — visualización rápida
2. [[01-MAPA-ESTRATEGICO]] — visión + decisiones
3. [[02-FASES-ROADMAP]] — timeline + milestones
4. [[03-ECOSISTEMA-AGENTES]] — agentes + responsabilidades
5. [[04-ARQUITECTURA-SISTEMAS]] — tech stack + infraestructura
6. [[05-PROCESOS-OPERATIVOS]] — workflows + ciclos
7. [[06-METRICAS-PROGRESO]] — dashboards + KPIs
8. [[07-EQUIPO-COORDINACION]] — RACI + comunicación
9. [[08-DEPENDENCIAS-RIESGOS]] — riesgos + mitigaciones

---

## 🎬 ACCIONES INMEDIATAS (Próximos 7 días)

| Acción | Responsable | Deadline | Critical? |
|--------|-------------|----------|-----------|
| Activar Antigravity | Diego | Apr 7 EOD | 🔴 YES |
| RCC Audit | Antigravity | Apr 8 | 🔴 YES |
| Validar PFIDs en RCC | Cursor | Apr 8-9 | 🟡 MAYBE |
| Mapear PFID-001 & 002 | Cursor | Apr 10 | 🔴 YES |
| Desplegar a staging | VS Code | Apr 10 | 🔴 YES |
| Test staging (9/9) | Antigravity | Apr 13 | 🔴 YES |

---

## 💡 TIPS DE USO

✅ **En Obsidian**:
- Usa **Ctrl+Click** en links [[documento]] para abrir en nueva pestaña
- Abre **Graph View** (Ctrl+G) para ver relaciones visuales entre notas
- Usa **search** (Ctrl+P) para saltar rápido entre documentos
- Ve a **Backlinks** para ver qué otros documentos referencia este

✅ **Para presentación a team**:
- Comparte este documento (MAPA-VISUAL-INTERACTIVO) como intro
- Luego profundiza en documentos específicos según preguntas
- Usa diagramas Mermaid para explicar flujos

✅ **Para tracking**:
- Actualiza "% complete" en cada diagrama semanalmente
- Revisa matriz de riesgos cada viernes
- Consulta "Acciones Inmediatas" para próximos pasos

---

**Última actualización**: 2026-04-06
**Mantenido por**: Claude + Diego
**Próxima revisión**: Viernes 5 PM (semanal)
