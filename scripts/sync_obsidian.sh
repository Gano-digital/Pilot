#!/bin/bash
# Gano Digital — Obsidian Sync via REST API
# Sincronizar constelación con Obsidian en tiempo real
#
# Prerequisito: define la variable de entorno OBSIDIAN_API_KEY antes de ejecutar.
# Ejemplo: export OBSIDIAN_API_KEY=<tu-token>

# Verificar dependencias
if ! command -v jq &>/dev/null; then
    echo "❌ Error: 'jq' no está instalado. Instálalo con: sudo apt install jq  (Linux) o brew install jq (macOS)"
    exit 1
fi

# Leer API key desde variable de entorno
if [ -z "${OBSIDIAN_API_KEY}" ]; then
    echo "❌ Error: variable de entorno OBSIDIAN_API_KEY no definida."
    echo "Ejemplo: export OBSIDIAN_API_KEY=<tu-token>"
    exit 1
fi

API_KEY="${OBSIDIAN_API_KEY}"
API_URL="https://localhost:27124"
HEADER_AUTH="Authorization: Bearer $API_KEY"

# Validar que el host sea localhost antes de usar -k
API_HOST=$(echo "$API_URL" | awk -F[/:] '{print $4}')
if [[ "$API_HOST" != "localhost" && "$API_HOST" != "127.0.0.1" && "$API_HOST" != "::1" ]]; then
    echo "❌ Error: API_URL apunta a host no-local ($API_HOST). No se puede omitir verificación TLS."
    exit 1
fi

echo "🌌 Gano Digital — Obsidian Sync"
echo "================================"
echo ""

# Test conexión
echo "🔍 Verificando conexión con Obsidian..."
VAULT_INFO=$(curl -s -k -H "$HEADER_AUTH" "$API_URL/vault/" 2>/dev/null)

if echo "$VAULT_INFO" | grep -q "files"; then
    echo "✅ Conectado a Obsidian Local REST API"
    echo ""
    echo "📂 Archivos en vault:"
    echo "$VAULT_INFO" | grep -o '"[^"]*"' | head -10
    echo ""
else
    echo "❌ No se pudo conectar a Obsidian"
    exit 1
fi

# Función para escribir archivo
write_obsidian_file() {
    local file_path=$1
    local content=$2

    echo "📝 Escribiendo: $file_path"

    curl -s -k -X POST \
        -H "$HEADER_AUTH" \
        -H "Content-Type: application/json" \
        -d "$(jq -n --arg path "$file_path" --arg content "$content" '{path: $path, content: $content, overwrite: true}')" \
        "$API_URL/vault/create" >/dev/null 2>&1

    if [ $? -eq 0 ]; then
        echo "✅ $file_path actualizado"
    else
        echo "⚠️ Error actualizando $file_path"
    fi
}

# Función para leer archivo
read_obsidian_file() {
    local file_path=$1
    local encoded_path=$(echo "$file_path" | sed 's/ /%20/g')

    curl -s -k -H "$HEADER_AUTH" \
        "$API_URL/vault/abstract/file/$encoded_path" 2>/dev/null
}

# Crear archivo de estado
STATUS_REPORT="# 📊 Estado de Sincronización — $(date '+%Y-%m-%d %H:%M')

## Sistema
- ✅ Obsidian Local REST API: Conectado
- ✅ Sincronización: Activa en tiempo real
- ✅ API Key: Validada

## Métrica de Progreso Phase 4
- Fases completadas: 3/5 (60%)
- Fase 4: 15% completada
- PFID Mappings: 0/5
- Test Pass Rate: Pending
- Blockers Activos: 1 🔴
- Go-Live Target: Apr 20, 2026

## Próximas Acciones
1. Diego activa Antigravity (Apr 7)
2. RCC Audit ejecutado (Apr 8)
3. PFID mappings iniciados (Apr 9)

---
Sincronizado via Obsidian Local REST API | $(date -u '+%Y-%m-%d %H:%M:%S') UTC
"

# Escribir reporte
write_obsidian_file "memory/constellation/STATUS-LIVE.md" "$STATUS_REPORT"

echo ""
echo "✅ Sincronización completada"
echo ""
echo "💡 Tip: Este script se puede ejecutar cada X minutos para mantener Obsidian actualizado"
