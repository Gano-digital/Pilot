# Gano Digital — Backlog Integration Notes
*(Sección 6 Infrastructure)*

Este documento detalla los pasos técnicos para completar la integración de recursos externos una vez se disponga de las credenciales de producción.

## 1. AI Connector (OpenAI / Gemini)
- **Archivo**: `class-gano-agent-api.php`
- **Pasos**: 
    1. Reemplazar `generate_mock_ai_response` con una llamada `wp_remote_post` a la API elegida.
    2. Usar la key guardada en `get_option('gano_ai_api_key')`.
    3. Implementar caché de respuestas (Transients) para reducir costos.

## 2. CRM & Automation (HubSpot / Salesforce)
- **Archivo**: `class-gano-leads-handler.php`
- **Pasos**:
    1. El hook `gano_new_lead_captured` debe disparar un webhook al `gano_crm_webhook`.
    2. Mapear los campos: `name` -> `firstname`, `whatsapp` -> `phone`.

## 3. Email transaccional (SMTP/SendGrid)
- **Recomendación**: Instalar `WP Mail SMTP`.
- **Plantillas**: Las plantillas HTML de Gano Digital se encuentran en `mu-plugins/gano-agent/templates/`.

## 4. Analytics Master
- **Pasos**: Integrar la tabla de leads con una librería de gráficas (Chart.js) en el tab "Central Intel" del Gano Hub.
