/**
 * catalog-sota.js v2.2.0
 * Gano Digital — Motor de catálogo SOTA
 * Arquitectura: IIFE · strict · sin dependencias externas
 * Filtro activo: solo productos MEDIO y ALTO (11 items)
 * Estilo: GoDaddy-inspired — bloques por categoría, pitch largo, specs detallados
 *
 * v2.2.0 — PFIDS REALES desde servidor (2026-06-14):
 *   PFIDs extraídos de functions.php en servidor vía SSH:
 *   - Hosting WP (familia): 457
 *   - WHP Expansión (webhosting plus): 459
 *   - SSL (todos los tipos): 75
 *   - Email / M365: 466
 *   - Seguridad Web: 557
 *   - Dominios: domain_search (dinámico — el plugin maneja la búsqueda)
 *   - Builder: pendiente de activación en RCC
 *
 * v2.1.0 — CORRECCIÓN CRÍTICA:
 *   Los CTAs apuntan ahora al carrito Reseller de Gano Digital
 *   (cart.secureserver.net con PFID) en lugar de godaddy.com público.
 *   Cuando PFID = null (pendiente de configurar en RCC), el botón
 *   cae back a WhatsApp para no perder la conversión.
 */

(function () {
  'use strict';

  /* ─── CONSTANTES ──────────────────────────────────────────────── */
  const PLID      = '599667';   // Private Label ID — verificar en RCC → Account
  const WA_NUM    = '573000000000';
  const WA_MSG    = encodeURIComponent('Hola, vi el catálogo de Gano Digital y quiero saber más.');

  /**
   * PFIDs del Reseller Control Center (RCC) de GoDaddy.
   * Obtener en: https://reseller.godaddy.com → Products & Pricing → [producto] → Product ID
   * Formato: número entero (ej. 123456). null = pendiente → fallback WA.
   *
   * INSTRUCCIÓN para Diego:
   *   1. Ir a RCC → Products & Pricing
   *   2. Buscar cada producto y copiar su Product ID (número en la URL o ficha)
   *   3. Reemplazar null por el número aquí (ej. 'pro-managed': 123456)
   *   4. Hacer commit + deploy del archivo actualizado al servidor
   */
  const PFIDS = {
    // ── Hosting WordPress ──────────────────────────────────────────
    // PFID familia 457 = WordPress Managed (Básico/Pro/Developer)
    // Obtenido desde functions.php en servidor 2026-06-14
    'pro-managed':       457,   // Managed WordPress Pro → familia WP (pfid 457)
    'business-nvme':     457,   // Managed WordPress Business NVMe → familia WP (pfid 457)
    'ultimate':          457,   // Managed WordPress Ultimate → familia WP (pfid 457)

    // ── Dominio ───────────────────────────────────────────────────
    // 'domain_search' = dinámico; el plugin reseller maneja la búsqueda.
    // Apuntamos al buscador de dominios del carrito en lugar de PFID fijo.
    'dom-co':            null,  // Dominio .CO → domain_search dinámico → WA fallback

    // ── SSL ───────────────────────────────────────────────────────
    'ssl-pro':           75,    // SSL (todos los tipos DV/EV/OV) → pfid 75

    // ── Email / M365 ──────────────────────────────────────────────
    'email-pro':         466,   // Email Profesional / M365 → pfid 466

    // ── Website Builder ───────────────────────────────────────────
    'builder-marketing': null,  // Builder — pendiente activación en RCC → WA fallback

    // ── Servicios → siempre WhatsApp (sin PFID Reseller) ─────────
    'vps-alpha':         'WA',
    'waf-pro':           'WA',
    'diagnostico':       'WA',
    'disenio-custom':    'WA',
  };

  /* ─── ESTADO ───────────────────────────────────────────────────── */
  let activeCategory  = 'all';
  let activeObjective = 'all';
  let isAnnual        = true;   // toggle mensual/anual

  /* ─── HELPERS DE PRECIO ────────────────────────────────────────── */
  function fCOP(n) {
    if (n == null) return '—';
    if (n >= 1000000) return '$' + (n / 1000000).toFixed(n % 1000000 === 0 ? 0 : 1).replace('.', ',') + 'M';
    if (n >= 1000)    return '$' + Math.round(n / 1000) + 'K';
    return '$' + n;
  }
  function fCOPFull(n) {
    if (n == null) return '—';
    return '$' + n.toLocaleString('es-CO');
  }

  /* precio a mostrar según toggle */
  function currentPrice(p) {
    if (p.yearly == null && p.monthly == null) return null;
    if (p.monthly == null) return { amount: p.yearly, label: '/año',   cycle: 'año' };
    if (p.yearly  == null) return { amount: p.monthly, label: '/mes',  cycle: 'mes' };
    return isAnnual
      ? { amount: Math.round(p.yearly / 12),  label: '/mes, facturado anual', cycle: 'año', save: p.monthly - Math.round(p.yearly / 12) }
      : { amount: p.monthly, label: '/mes', cycle: 'mes' };
  }

  /* ─── URLS DE COMPRA ───────────────────────────────────────────── */
  /**
   * Construye la URL del carrito Reseller de Gano Digital.
   *
   * Flujo:
   *   PFID válido  → cart.secureserver.net (carrito white-label branded Gano Digital)
   *   PFID = 'WA'  → WhatsApp directo (productos que no tienen carrito Reseller)
   *   PFID = null  → WhatsApp fallback (PFID pendiente de configurar en RCC)
   *
   * NUNCA redirige a godaddy.com público — eso sacaría al cliente de la marca.
   */
  function buyUrl(productId) {
    const pfid = PFIDS[productId];

    // Productos sin PFID reseller → WhatsApp personalizado
    const waCustom = {
      'diagnostico':    encodeURIComponent('Quiero agendar un Diagnóstico de Soberanía con Gano Digital.'),
      'disenio-custom': encodeURIComponent('Me interesa el Ecosistema SOTA de Gano Digital.'),
      'vps-alpha':      encodeURIComponent('Quiero información sobre el VPS Pro Alpha de Gano Digital.'),
      'waf-pro':        encodeURIComponent('Quiero información sobre seguridad WAF Pro de Gano Digital.'),
    };
    if (waCustom[productId]) return `https://wa.me/${WA_NUM}?text=${waCustom[productId]}`;

    // PFID configurado → carrito Reseller white-label de Gano Digital
    if (pfid && pfid !== 'WA') {
      return `https://cart.secureserver.net/order/main/add/${pfid}?plid=${PLID}&currencyType=COP&marketId=es-CO`;
    }

    // PFID = null (pendiente) → WhatsApp fallback para no perder la conversión
    return `https://wa.me/${WA_NUM}?text=${WA_MSG}`;
  }

  /**
   * Indica si el producto tiene PFID listo (para ajustar el label del CTA).
   */
  function pfidReady(productId) {
    const pfid = PFIDS[productId];
    return pfid !== null && pfid !== 'WA';
  }

  /* ─── BADGE HTML ───────────────────────────────────────────────── */
  function badgeHtml(p) {
    if (!p.badge) return '';
    const cls = {
      'Popular':    'badge-popular',
      'Élite':      'badge-elite',
      'SOTA':       'badge-sota',
      'A medida':   'badge-custom',
      'Único pago': 'badge-unique',
    }[p.badge] || 'badge-default';
    return `<span class="badge ${cls}">${p.badge}</span>`;
  }

  /* ─── RENDER PRECIO ────────────────────────────────────────────── */
  function renderPrice(p) {
    const pr = currentPrice(p);
    if (!pr) return `<div class="card-price price-custom"><span class="price-label-custom">Precio a medida · Consultar</span></div>`;

    let saveHtml = '';
    if (pr.save && pr.save > 0) {
      saveHtml = `<span class="price-save">Ahorra ${fCOP(pr.save)}/mes</span>`;
    }

    return `
      <div class="card-price" data-id="${p.id}">
        <span class="price-from">A partir de</span>
        <span class="price-amount">${fCOP(pr.amount)}</span>
        <span class="price-period">${pr.label}</span>
        ${saveHtml}
        <span class="price-cop-full">${fCOPFull(pr.amount)} COP</span>
      </div>`;
  }

  /* ─── RENDER CARD ──────────────────────────────────────────────── */
  function renderCard(p) {
    const pr      = currentPrice(p);
    const classes = [
      'pcard',
      p.featured   ? 'is-featured' : '',
      p.badge === 'Élite' ? 'is-elite' : '',
      p.badge === 'SOTA'  ? 'is-sota'  : '',
      p.badge === 'A medida' ? 'is-medida' : '',
    ].filter(Boolean).join(' ');

    /* features list */
    const feats = (p.features || []).map(f =>
      `<li class="feat-row">
        <span class="feat-icon"><i class="fas fa-check"></i></span>
        <span class="flabel">${f.label}</span>
        <span class="fvalue">${f.value}</span>
      </li>`
    ).join('');

    /* specs técnicas */
    const specs = p.specs ? Object.entries(p.specs).map(([k, v]) =>
      `<div class="spec-row"><span class="sk">${k.charAt(0).toUpperCase() + k.slice(1)}</span><span class="sv">${v}</span></div>`
    ).join('') : '';

    /* best-for chips */
    const bfChips = (p.bestFor || []).map(b =>
      `<span class="bf-chip"><i class="fas fa-check-circle"></i> ${b}</span>`
    ).join('');

    /* CTA principal
     * - Productos WA (VPS, WAF, diagnóstico, diseño) → siempre WhatsApp
     * - Productos con PFID listo → "Comenzar ahora" (carrito Reseller)
     * - Productos con PFID pendiente (null) → WhatsApp fallback + tooltip
     */
    const isWaProduct = ['diagnostico','disenio-custom','vps-alpha','waf-pro'].includes(p.id);
    const hasCart     = pfidReady(p.id);
    const ctaLabel = isWaProduct
      ? '<i class="fab fa-whatsapp"></i> Consultar por WhatsApp'
      : hasCart
        ? '<i class="fas fa-rocket"></i> Comenzar ahora'
        : '<i class="fab fa-whatsapp"></i> Consultar disponibilidad';
    const ctaTitle = (!isWaProduct && !hasCart)
      ? 'title="Carrito en configuración — te atendemos por WhatsApp"'
      : '';

    return `
<article class="pcard ${classes}" data-id="${p.id}" data-cat="${p.category}" data-obj="${(p.objectives || []).join(' ')}">

  <div class="pcard-inner">

    <!-- HEAD -->
    <div class="card-head">
      <div class="card-icon-wrap">
        <i class="fas ${p.icon} card-icon"></i>
      </div>
      <div class="card-meta">
        <span class="card-tier">${p.tier}</span>
        ${badgeHtml(p)}
      </div>
    </div>

    <!-- NAME + PITCH -->
    <div class="card-body-top">
      <h3 class="card-name">${p.name}</h3>
      <p class="card-tagline">${p.tagline}</p>
      <p class="card-pitch">${p.pitch}</p>
    </div>

    <!-- PRECIO -->
    ${renderPrice(p)}

    <!-- CTA PRINCIPAL -->
    <div class="card-cta-wrap">
      <a class="btn-buy" href="${buyUrl(p.id)}" target="_blank" rel="noopener" ${ctaTitle}>
        ${ctaLabel}
      </a>
      <button class="btn-wa-secondary" onclick="window.open('https://wa.me/${WA_NUM}?text=${WA_MSG}','_blank')">
        <i class="fab fa-whatsapp"></i>
      </button>
    </div>

    <!-- FEATURES -->
    <ul class="feat-list">${feats}</ul>

    <!-- SPECS TÉCNICAS (desplegable) -->
    ${specs ? `
    <div class="specs-section">
      <button class="specs-btn" onclick="window.ganoToggleSpecs(this)" aria-expanded="false">
        <i class="fas fa-microchip"></i> Especificaciones técnicas
        <i class="fas fa-chevron-down specs-chevron"></i>
      </button>
      <div class="specs-body">
        ${specs}
      </div>
    </div>` : ''}

    <!-- BEST FOR -->
    ${bfChips ? `
    <div class="best-for">
      <span class="bf-label"><i class="fas fa-bullseye"></i> Ideal para:</span>
      <div class="bf-chips">${bfChips}</div>
    </div>` : ''}

  </div><!-- /.pcard-inner -->

</article>`;
  }

  /* ─── RENDER CABECERA DE CATEGORÍA ────────────────────────────── */
  function renderCatHeader(catId) {
    const cat = (window.GANO_CATEGORIES || []).find(c => c.id === catId);
    if (!cat || catId === 'all') return '';
    return `
<div class="gano-cat-header" data-cat="${catId}">
  <i class="fas ${cat.icon}"></i>
  <h2>${cat.label}</h2>
</div>`;
  }

  /* ─── RENDER GRID COMPLETO ─────────────────────────────────────── */
  function renderGrid() {
    const grid = document.getElementById('product-grid');
    if (!grid) return;

    const products = window.GANO_PRODUCTS || [];

    /* Filtrar por categoría y objetivo */
    const visible = products.filter(p => {
      const catOk = activeCategory === 'all' || p.category === activeCategory;
      const objOk = activeObjective === 'all' || (p.objectives || []).includes(activeObjective);
      return catOk && objOk;
    });

    if (visible.length === 0) {
      grid.innerHTML = '<div class="catalog-empty"><i class="fas fa-search"></i><p>Sin resultados para esta combinación. <button onclick="window.resetFilters()">Ver todos</button></p></div>';
      updateCount(0);
      return;
    }

    /* Agrupar por categoría manteniendo orden de GANO_CATEGORIES */
    const catOrder = (window.GANO_CATEGORIES || []).map(c => c.id).filter(id => id !== 'all');
    const groups = {};
    catOrder.forEach(id => { groups[id] = []; });
    visible.forEach(p => { if (groups[p.category]) groups[p.category].push(p); });

    let html = '';
    catOrder.forEach(catId => {
      const items = groups[catId];
      if (!items || items.length === 0) return;

      /* Si hay más de 1 o filtramos todo, mostrar header de categoría */
      if (activeCategory === 'all') {
        html += renderCatHeader(catId);
      }

      const gridClass = items.length === 1 ? 'cat-grid cat-grid--single' :
                        items.length === 2 ? 'cat-grid cat-grid--two'   : 'cat-grid';
      html += `<div class="${gridClass}" data-cat="${catId}">`;
      items.forEach(p => { html += renderCard(p); });
      html += '</div>';
    });

    grid.innerHTML = html;
    updateCount(visible.length);
    // No llamar updatePriceLabels() aquí — el innerHTML ya tiene los precios correctos
    // updatePriceLabels() solo se usa en el toggle anual/mensual
  }

  /* ─── UPDATE CONTADOR ──────────────────────────────────────────── */
  function updateCount(n) {
    const el = document.getElementById('results-count');
    if (el) el.textContent = n === 0 ? 'Sin resultados' : `${n} servicio${n === 1 ? '' : 's'}`;
  }

  /* ─── UPDATE ETIQUETAS DE PRECIO (sin re-render) ───────────────── */
  function updatePriceLabels() {
    document.querySelectorAll('[data-id]').forEach(el => {
      const pid = el.dataset.id;
      const p = (window.GANO_PRODUCTS || []).find(x => x.id === pid);
      if (!p) return;
      el.outerHTML = renderPrice(p);   // sustituye solo el bloque de precio
    });
  }

  function updatePrices() {
    const products = window.GANO_PRODUCTS || [];
    document.querySelectorAll('.card-price[data-id], .card-price').forEach(el => {
      const pid = el.dataset.id;
      const p   = pid ? products.find(x => x.id === pid) : null;
      if (!p) return;
      el.outerHTML = renderPrice(p);
    });
  }

  /* ─── TABS DE CATEGORÍA ────────────────────────────────────────── */
  function buildCatTabs() {
    const wrap = document.getElementById('cat-tabs');
    if (!wrap) return;
    const cats = window.GANO_CATEGORIES || [];
    wrap.innerHTML = cats.map(c => `
      <button class="ftab${c.id === activeCategory ? ' active' : ''}"
              data-cat="${c.id}"
              onclick="window.setCat(this,'${c.id}')">
        <i class="fas ${c.icon}"></i> ${c.label}
      </button>`
    ).join('');
  }

  /* ─── CHIPS DE OBJETIVO ────────────────────────────────────────── */
  function buildObjChips() {
    const wrap = document.getElementById('obj-chips');
    if (!wrap) return;
    const objs = window.GANO_OBJECTIVES || [];
    wrap.innerHTML = objs.map(o => `
      <button class="ochip${o.id === activeObjective ? ' active' : ''}"
              data-obj="${o.id}"
              onclick="window.setObj(this,'${o.id}')">
        <i class="fas ${o.icon}"></i> ${o.label}
      </button>`
    ).join('');
  }

  /* ─── TIMELINE ─────────────────────────────────────────────────── */
  function buildTimeline() {
    const track = document.getElementById('timeline-track');
    if (!track) return;
    const steps = window.GANO_TIMELINE || [];
    track.innerHTML = steps.map((s, i) => `
      <div class="tl-step" style="--step-i:${i}">
        <div class="tl-icon"><i class="${s.iconBrand ? 'fab' : 'fas'} ${s.icon}"></i></div>
        <div class="tl-content">
          <span class="tl-time">${s.t}</span>
          <strong class="tl-title">${s.title}</strong>
          <p class="tl-body">${s.body}</p>
        </div>
        ${i < steps.length - 1 ? '<div class="tl-connector"></div>' : ''}
      </div>`
    ).join('');
  }

  /* ─── TOOLTIP GLOSARIO ─────────────────────────────────────────── */
  function initGlossaryTooltip() {
    const tt = document.getElementById('gtooltip');
    if (!tt) return;
    document.addEventListener('click', function (e) {
      const kw = e.target.closest('[data-glosario]');
      if (!kw) { tt.classList.remove('visible'); return; }
      const key  = kw.dataset.glosario;
      const data = (window.GANO_GLOSSARY || {})[key];
      if (!data) return;
      const ttTitle  = document.getElementById('gtt-title');
      const ttBody   = document.getElementById('gtt-body');
      const ttMetric = document.getElementById('gtt-metric');
      if (ttTitle)  ttTitle.textContent  = data.title;
      if (ttBody)   ttBody.textContent   = data.body;
      if (ttMetric) ttMetric.textContent = data.metric || '';
      const rect = kw.getBoundingClientRect();
      tt.style.top  = (rect.bottom + window.scrollY + 8) + 'px';
      tt.style.left = Math.min(rect.left, window.innerWidth - 320) + 'px';
      tt.classList.add('visible');
      e.stopPropagation();
    });
  }

  /* ─── URL PARAM ?cat= ──────────────────────────────────────────── */
  function applyUrlCatParam() {
    const params = new URLSearchParams(window.location.search);
    const cat    = params.get('cat');
    if (!cat) return;
    const valid = (window.GANO_CATEGORIES || []).find(c => c.id === cat);
    if (!valid) return;
    activeCategory = cat;
    // activar tab
    const tab = document.querySelector(`[data-cat="${cat}"]`);
    if (tab) {
      document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    }
    // scroll suave al grid
    const grid = document.getElementById('product-grid');
    if (grid) {
      setTimeout(() => {
        grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 200);
    }
  }

  /* ─── TOGGLE PRECIO ANUAL/MENSUAL ──────────────────────────────── */
  function initPriceToggle() {
    const toggle = document.getElementById('price-toggle');
    const lblM   = document.getElementById('lbl-monthly');
    const lblA   = document.getElementById('lbl-annual');
    if (!toggle) return;

    function applyToggle() {
      isAnnual = toggle.checked;
      if (lblM) lblM.classList.toggle('active', !isAnnual);
      if (lblA) lblA.classList.toggle('active', isAnnual);
      // Actualizar precios en el DOM sin re-render completo
      document.querySelectorAll('.card-price').forEach(el => {
        const pid = el.dataset.id;
        const p   = pid ? (window.GANO_PRODUCTS || []).find(x => x.id === pid) : null;
        if (!p) return;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = renderPrice(p);
        el.replaceWith(tempDiv.firstElementChild);
      });
    }

    toggle.checked = isAnnual;
    if (lblA) lblA.classList.add('active');
    toggle.addEventListener('change', applyToggle);
  }

  /* ─── TOGGLE SPECS ─────────────────────────────────────────────── */
  window.ganoToggleSpecs = function (btn) {
    const body = btn.nextElementSibling;
    const open = body.classList.toggle('show');
    btn.setAttribute('aria-expanded', open);
    btn.querySelector('.specs-chevron').style.transform = open ? 'rotate(180deg)' : '';
  };

  /* ─── RESET FILTROS ────────────────────────────────────────────── */
  window.resetFilters = function () {
    window.setCat(document.querySelector('[data-cat="all"]'), 'all');
    window.setObj(document.querySelector('[data-obj="all"]'), 'all');
  };

  /* ─── FILTROS EXPUESTOS ────────────────────────────────────────── */
  window.setCat = function (btn, id) {
    activeCategory = id;
    document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
    if (btn) btn.classList.add('active');
    renderGrid();
  };

  window.setObj = function (btn, id) {
    activeObjective = id;
    document.querySelectorAll('.ochip').forEach(c => c.classList.remove('active'));
    if (btn) btn.classList.add('active');
    renderGrid();
  };

  /* ─── INIT PRINCIPAL ───────────────────────────────────────────── */
  function init() {
    buildCatTabs();
    buildObjChips();
    renderGrid();
    buildTimeline();
    initGlossaryTooltip();
    initPriceToggle();
    applyUrlCatParam();
    // re-render si URL param cambió la categoría
    if (activeCategory !== 'all') renderGrid();
  }

  /* ─── BOOTSTRAP ────────────────────────────────────────────────── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
