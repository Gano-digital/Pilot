/**
 * catalog-sota.js v2.0.0
 * Gano Digital — Motor de catálogo SOTA
 * Arquitectura: IIFE · strict · sin dependencias externas
 * Filtro activo: solo productos MEDIO y ALTO (11 items)
 * Estilo: GoDaddy-inspired — bloques por categoría, pitch largo, specs detallados
 */

(function () {
  'use strict';

  /* ─── CONSTANTES ──────────────────────────────────────────────── */
  const PLID      = '599667';
  const WA_NUM    = '573000000000';
  const WA_MSG    = encodeURIComponent('Hola, vi el catálogo de Gano Digital y quiero saber más.');

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
  function buyUrl(productId) {
    // deeplink GoDaddy reseller + fallback WA
    const links = {
      'pro-managed':        `https://www.godaddy.com/hosting/wordpress-hosting?plid=${PLID}`,
      'business-nvme':      `https://www.godaddy.com/hosting/wordpress-hosting?plid=${PLID}`,
      'ultimate':           `https://www.godaddy.com/hosting/wordpress-hosting?plid=${PLID}`,
      'vps-alpha':          `https://wa.me/${WA_NUM}?text=${WA_MSG}`,
      'dom-co':             `https://www.godaddy.com/domainsearch/find?domainToCheck=.co&plid=${PLID}`,
      'waf-pro':            `https://wa.me/${WA_NUM}?text=${WA_MSG}`,
      'ssl-pro':            `https://www.godaddy.com/web-security/ssl-certificate?plid=${PLID}`,
      'email-pro':          `https://www.godaddy.com/email/professional-business-email?plid=${PLID}`,
      'builder-marketing':  `https://www.godaddy.com/websites/website-builder?plid=${PLID}`,
      'diagnostico':        `https://wa.me/${WA_NUM}?text=${encodeURIComponent('Quiero agendar un Diagnóstico de Soberanía con Gano Digital.')}`,
      'disenio-custom':     `https://wa.me/${WA_NUM}?text=${encodeURIComponent('Me interesa el Ecosistema SOTA de Gano Digital.')}`,
    };
    return links[productId] || `https://wa.me/${WA_NUM}?text=${WA_MSG}`;
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

    /* CTA principal */
    const ctaLabel = ['diagnostico','disenio-custom','vps-alpha','waf-pro'].includes(p.id)
      ? '<i class="fab fa-whatsapp"></i> Consultar por WhatsApp'
      : '<i class="fas fa-rocket"></i> Comenzar ahora';

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
      <a class="btn-buy" href="${buyUrl(p.id)}" target="_blank" rel="noopener">
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
