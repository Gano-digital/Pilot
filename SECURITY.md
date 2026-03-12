# Security Policy — Gano.digital / Pilot

## Versiones soportadas

| Versión | Soporte de seguridad |
| ------- | -------------------- |
| main    | ✅ Activo            |
| otras   | ❌ No soportadas     |

## Reporte de vulnerabilidades

Si encuentras una vulnerabilidad de seguridad en este repositorio, **NO abras un Issue público**.

### Cómo reportar

1. Envía un correo a: **security@gano.digital**
2. Incluye en el reporte:
   - Descripción detallada de la vulnerabilidad
   - Pasos para reproducirla
   - Impacto potencial
   - (Opcional) Propuesta de solución o mitigación

### Tiempo de respuesta

- Acuse de recibo: dentro de 48 horas
- Evaluación inicial: dentro de 7 días
- Resolución: dentro de 30 días para vulnerabilidades críticas/altas

## Scope — Qué está en alcance

- Repositorio `Gano-digital/Pilot`
- Workflows de GitHub Actions (.github/workflows/)
- Scripts de infraestructura (scripts/, security/)
- Configuraciones de despliegue hacia GoDaddy Hosting

## Qué NO es un reporte válido

- Problemas en dependencias de terceros ya reportados upstream
- Configuraciones de GoDaddy fuera del alcance de este repositorio
- Problemas de disponibilidad del servicio no relacionados con el código

## Mejores prácticas implementadas

- Secrets gestionados exclusivamente via GitHub Secrets (nunca hardcodeados)
- Permisos mínimos (least privilege) en todos los workflows
- StrictHostKeyChecking=yes en todas las conexiones SSH
- FTPS (FTP sobre TLS) para transferencias de archivos de configuración
- Concurrency control para evitar despliegues simultáneos
- Auditoría de seguridad automatizada semanal (securityaudit.yml)
- Dependabot habilitado para alertas de dependencias vulnerables

## Responsable de seguridad

Organización: **Gano-digital**  
Repositorio: **Pilot**  
Última actualización de esta política: Noviembre 2026
