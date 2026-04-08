# Checklist: Mapeo RCC → PFID (GoDaddy Reseller)

Este documento detalla los pasos para obtener los identificadores de producto (**PFIDs**) desde el **Reseller Control Center (RCC)** de GoDaddy y vincularlos con la vitrina de Gano Digital.

> **Nota:** El **Developer API** ([getstarted](https://developer.godaddy.com/getstarted)) es independiente de este flujo: el checkout canónico sigue siendo **Reseller Store + RCC**. Las claves REST son opcionales para otras herramientas; no son necesarias para mapear PFIDs.

## 🔒 Privacidad y Seguridad
> [!WARNING]
> Los **PFIDs** y el **Private Label ID (PLID)** son identificadores públicos del catálogo, pero se recomienda completar este paso directamente en el servidor (`wp-config.php` o `functions.php`) para no commitear valores reales al repositorio si prefieres mantener el catálogo en privado durante el desarrollo.

---

## 1. Localizar el Private Label ID (PLID)
El PLID identifica tu tienda reseller. Sin él, el carrito no sabrá a qué cuenta atribuir la venta.

1. Inicia sesión en el **RCC**.
2. Ve a **Configuración** → **Información del sitio**.
3. Busca el campo **Private Label ID**.
4. Cópialo y pégalo en WordPress: **wp-admin** → **Ajustes** → **Reseller Store** → **Private Label ID**.

---

## 2. Obtener PFIDs del Catálogo
El **PFID** (Product Family ID) es el código que GoDaddy usa para meter un producto específico en el carrito.

### Pasos en el RCC:
1. En el menú superior, ve a **Productos** → **Catálogo de productos**.
2. Verás una lista de todos los productos disponibles (Hosting, SSL, Email, etc.).
3. Localiza cada producto de la tabla de abajo.
4. Identifica la columna **Product Family ID** (o similar, a veces simplemente "ID").
5. Copia el valor numérico.

---

## 3. Mapeo de Constantes en el Código
Debes reemplazar el valor `'PENDING_RCC'` por el número obtenido en `wp-content/themes/gano-child/functions.php`.

| Constante PHP | Producto Comercial | Categoría RCC |
| :--- | :--- | :--- |
| `GANO_PFID_HOSTING_ECONOMIA` | **Núcleo Prime** | Web Hosting -> WordPress Economy |
| `GANO_PFID_HOSTING_DELUXE` | **Fortaleza Delta** | Web Hosting -> WordPress Deluxe |
| `GANO_PFID_HOSTING_PREMIUM` | **Bastión SOTA** | Web Hosting -> WordPress Premium |
| `GANO_PFID_HOSTING_ULTIMATE` | **Ultimate WP** | Web Hosting -> WordPress Ultimate |
| `GANO_PFID_SSL_DELUXE` | **SSL Deluxe** | SSL & Seguridad -> SSL DV Deluxe |
| `GANO_PFID_SECURITY_ULTIMATE` | **Security Ultimate** | SSL & Seguridad -> Website Security Premium |
| `GANO_PFID_M365_PREMIUM` | **M365 Premium** | Email & Office -> M365 Business Premium |
| `GANO_PFID_ONLINE_STORAGE` | **Online Storage** | Email & Office -> Online Storage 1 TB |

---

## 4. Validación del Checkout
Una vez configurados los PFIDs y el PLID:

1. Ve a la página de **Ecosistemas** (`/ecosistemas`).
2. Haz clic en un botón de **Comprar**.
3. Deberías ser redirigido a `cart.secureserver.net` con tu marca blanca y el producto en el carrito.
4. Verifica que el precio coincida con el configurado en el RCC.

---

## Referencias Técnicas
*   **Lógica de URL**: La función `gano_rstore_cart_url()` en `functions.php` construye la URL dinámicamente.
*   **Tareas Globales**: Ver [TASKS.md](../../TASKS.md) Fase 4.
*   **Integración**: Ver [shop-premium.php](../../wp-content/themes/gano-child/templates/shop-premium.php) para ver dónde se disparan estos IDs.
