# Kimi ↔ Cursor Bridge — Referencia Rápida

## Modelos Kimi disponibles via OpenRouter

| Modelo | ID OpenRouter | Contexto | Ideal para |
|--------|--------------|----------|-----------|
| Kimi K2.5 | `moonshotai/kimi-k2.5` | 256K tokens | Refactor masivo, efectos proc., razonamiento |
| Kimi K1.5 | `moonshotai/kimi-k1.5` | 128K tokens | Tareas estándar, más rápido |

URL base: `https://openrouter.ai/api/v1`

---

## Atajos de teclado (Cursor)

| Acción | Atajo |
|--------|-------|
| Abrir chat | `Ctrl+L` |
| Cambiar modelo | Click en modelo (arriba del chat) → seleccionar `moonshotai/kimi-k2.5` |
| Settings → Models | `Ctrl+,` → Models tab |

---

## Prompts de un-línea (copiar y pegar)

### Delegar a Kimi (desde Cursor)
```
@kimi-bridge Tarea: [DESCRIPCIÓN]. Archivo: [PATH]. Contexto: [2-3 líneas]. Bloqueante: [1 línea].
```

### Reintegrar desde Kimi (a Cursor)
```
@cursor-integrate Archivos: [LISTA]. Cambios: [RESUMEN]. Estado: php-l OK. Revisar coherencia con design system.
```

---

## Estructura de archivos compartidos

```
Gano.digital-copia/
├── .cursor/
│   ├── memory/           ← Cursor escribe aquí
│   ├── skills/
│   │   └── kimi-cursor-bridge/  ← ESTA SKILL
│   └── mcp.json          ← MCP servers (github, filesystem, obsidian)
├── .kimi/
│   └── plans/            ← Kimi escribe planes aquí
├── memory/
│   └── sessions/         ← Handoffs compartidos
└── TASKS.md              ← Checklist global
```

---

## Flags de seguridad

- 🚨 **Nunca** compartir `GITHUB_TOKEN` o `OBSIDIAN_API_KEY` con Kimi
- 🚨 **Nunca** pegar claves SSH o credenciales de servidor en prompts de Kimi
- ✅ Kimi puede leer código fuente, no secretos de entorno
- ✅ Usar `.env` files para secretos, nunca commitearlos

---

## Checklist de integración

- [ ] Cuenta OpenRouter creada y con crédito
- [ ] API key guardada en variable de entorno `OPENROUTER_API_KEY`
- [ ] Modelo `moonshotai/kimi-k2.5` añadido en Cursor Settings
- [ ] Skill `kimi-cursor-bridge` leída al menos una vez por agente Cursor
- [ ] `.cursor/memory/activeContext.md` incluye referencia a plan Kimi actual
- [ ] Workflow de delegación probado al menos una vez (Cursor → Kimi → Cursor)
