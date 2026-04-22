/* ─── catalog.js — Gano Digital Catalog Engine ─── */
(function () {
  'use strict';

  const PLID = '599667';
  const WA_NUM = '573000000000';
  const waMsg = (name) => `Hola, quiero información sobre el plan ${name} de Gano Digital`;

  /* ── State ── */
  let activeCategory = 'all';
  let activeObjective = 'all';
  let isAnnual = true; // default: annual

  /* ── Cart URL builder ── */
  function cartUrl(pfid) {
    if (!pfid || pfid === 'PENDING_RCC') return null;
    return `https://cart.secureserver.net/?plid=${PLID}&pfid=${pfid}`;
  }

  /* ── Price helpers ── */
  function fmt(n) { return window.formatCOP ? window.formatCOP(n) : ('$' + n); }
  function monthlyEstimate(yearly) { return yearly ? Math.round(yearly / 12) : null; }

  /* ── Badge map ── */
  const BADGE_MAP = {
    'Popular': { cls: 'badge-popular', label: 'Popular' },
    'Élite':   { cls: 'badge-elite',   label: 'Élite' },
    'SOTA':    { cls: 'badge-sota',    label: 'SOTA' },
    'Único pago': { cls: 'badge-unique', label: 'Único pago' },
    'A medida':   { cls: 'badge-custom', label: 'A medida' },
  };

  function cardClass(p) {
    let cls = 'pcard';
    if (p.featured) cls += ' is-featured';
    if (p.badge === 'Élite') cls += ' is-elite';
    if (p.badge === 'SOTA') cls += ' is-sota';
    if (p.badge === 'A medida') cls += ' is-medida';
    return cls;
  }

  /* ── Glossary tip wrapper ── */
  function wrapGlossary(text) {
    if (!window.GANO_GLOSSARY) return text;
    let result = text;
    Object.keys(window.GANO_GLOSSARY).forEach(term => {
      if (result.includes(term)) {
        result = result.replace(term,
          `<span class="glossary-tip" data-term="${term}">${term}</span>`);
      }
    });
    return result;
  }

  /* ── Build feature list HTML ── */
  function buildFeatures(features) {
    return features.map(f =>
      `<li><span class="flabel">${f.label}</span><span class="fvalue">${wrapGlossary(f.value)}</span></li>`
    ).join('');
  }

  /* ── Build specs HTML ── */
  function buildSpecs(specs, id) {
    if (!specs || !Object.keys(specs).length) return '';
    const rows = Object.entries(specs).map(([k, v]) =>
      `<div class="spec-row"><span class="sk">${k.toUpperCase()}</span><span class="sv">${v}</span></div>`
    ).join('');
    return `
      <button class="specs-btn" onclick="toggleSpecs(this,'specs-${id}')">
        <i class="fa-solid fa-microchip"></i> Especificaciones técnicas <i class="fa-solid fa-chevron-down arr"></i>
      </button>
      <div class="specs-body" id="specs-${id}">${rows}</div>`;
  }

  /* ── Build best-for chips ── */
  function buildBestFor(bestFor) {
    if (!bestFor || !bestFor.length) return '';
    const chips = bestFor.map(b => `<span class="bf-chip"><i class="fa-solid fa-check" style="color:var(--gano-blue-light);font-size:9px;margin-right:4px"></i>${b}</span>`).join('');
    return `<div class="best-for">${chips}</div>`;
  }

  /* ── Build price block ── */
  function buildPrice(p) {
    const hasMonthly = p.monthly != null;
    const hasYearly = p.yearly != null;

    if (!hasMonthly && !hasYearly) {
      return `<span class="price-custom">Cotización a medida</span>`;
    }

    if (isAnnual && hasYearly) {
      const mo = monthlyEstimate(p.yearly);
      const strike = hasMonthly ? `<span class="price-strike">${fmt(p.monthly)}/mes</span>` : '';
      const save = hasMonthly && mo
        ? `<span class="price-save">Ahorras ${fmt((p.monthly * 12) - p.yearly)}/año</span>` : '';
      return `
        ${strike}
        <span class="price-val">${fmt(p.yearly)}</span>
        <span class="price-per">/año</span>
        ${save}`;
    } else if (!isAnnual && hasMonthly) {
      return `
        <span class="price-val">${fmt(p.monthly)}</span>
        <span class="price-per">/mes</span>`;
    } else if (hasYearly) {
      // monthly only shown, yearly product — show estimated
      const mo = monthlyEstimate(p.yearly);
      return `
        <span class="price-val">${fmt(mo)}</span>
        <span class="price-per">/mes est.</span>`;
    } else {
      return `<span class="price-val">${fmt(p.yearly)}</span><span class="price-per">/año</span>`;
    }
  }

  /* ── Build CTA ── */
  function buildCta(p) {
    const url = cartUrl(p.pfid);
    if (url) {
      return `<a href="${url}" class="btn-buy" target="_blank" rel="noopener" id="cta-${p.id}">
        <i class="fa-solid fa-shopping-cart"></i> Contratar ahora
      </a>`;
    }
    // No PFID or custom service → WhatsApp
    const msg = encodeURIComponent(waMsg(p.name));
    return `<a href="https://wa.me/${WA_NUM}?text=${msg}" class="btn-buy" target="_blank" rel="noopener" id="cta-${p.id}">
      <i class="fa-brands fa-whatsapp"></i> Solicitar cotización
    </a>`;
  }

  /* ── Build badge HTML ── */
  function buildBadge(p) {
    const b = BADGE_MAP[p.badge];
    const featured = p.featured ? `<span class="badge badge-popular">★ Popular</span>` : '';
    const custom = b ? `<span class="badge ${b.cls}">${b.label}</span>` : '';
    // avoid duplication if featured already implies popular
    if (p.featured && p.badge === 'Popular') return featured;
    return featured + (b && p.badge !== 'Popular' ? custom : '');
  }

  /* ── Render single card ── */
  function renderCard(p) {
    const hidden = shouldHide(p) ? ' hidden' : '';
    return `
<article class="${cardClass(p)}${hidden}" data-id="${p.id}" data-cat="${p.category}" data-obj="${(p.objectives||[]).join(',')}">
  <div class="card-head">
    <div class="card-icon"><i class="fa-solid ${p.icon}"></i></div>
    <div class="card-badges">${buildBadge(p)}</div>
  </div>
  <div>
    <div class="card-tier">${p.tier}</div>
    <div class="card-name">${p.name}</div>
    <div class="card-tagline">${p.tagline}</div>
  </div>
  <div class="card-price">${buildPrice(p)}</div>
  <ul class="feat-list">${buildFeatures(p.features)}</ul>
  ${buildSpecs(p.specs, p.id)}
  ${buildBestFor(p.bestFor)}
  <div class="card-cta">${buildCta(p)}</div>
</article>`;
  }

  /* ── Render all cards ── */
  function renderGrid() {
    const grid = document.getElementById('product-grid');
    if (!grid || !window.GANO_PRODUCTS) return;
    grid.innerHTML = window.GANO_PRODUCTS.map(renderCard).join('');
    updateCount();
    bindGlossaryTips();
  }

  /* ── Filter logic ── */
  function shouldHide(p) {
    const catOk = activeCategory === 'all' || p.category === activeCategory;
    const objOk = activeObjective === 'all' || (p.objectives || []).includes(activeObjective);
    return !(catOk && objOk);
  }

  function applyFilters() {
    document.querySelectorAll('.pcard').forEach(card => {
      const id = card.dataset.id;
      const p = window.GANO_PRODUCTS.find(x => x.id === id);
      if (!p) return;
      card.classList.toggle('hidden', shouldHide(p));
    });
    updateCount();
  }

  function updateCount() {
    const visible = document.querySelectorAll('.pcard:not(.hidden)').length;
    const el = document.getElementById('results-count');
    if (el) el.textContent = visible;
  }

  /* ── Re-render prices on toggle ── */
  function updatePrices() {
    document.querySelectorAll('.pcard').forEach(card => {
      const id = card.dataset.id;
      const p = window.GANO_PRODUCTS.find(x => x.id === id);
      if (!p) return;
      const priceEl = card.querySelector('.card-price');
      if (priceEl) priceEl.innerHTML = buildPrice(p);
    });
  }

  /* ── Build category tabs ── */
  function buildCatTabs() {
    const el = document.getElementById('cat-tabs');
    if (!el || !window.GANO_CATEGORIES) return;
    el.innerHTML = window.GANO_CATEGORIES.map(c => `
      <button class="ftab${c.id === 'all' ? ' active' : ''}" data-cat="${c.id}" onclick="setCat(this,'${c.id}')">
        <i class="fa-solid ${c.icon}"></i> ${c.label}
      </button>`).join('');
  }

  /* ── Build objective chips ── */
  function buildObjChips() {
    const el = document.getElementById('obj-chips');
    if (!el || !window.GANO_OBJECTIVES) return;
    el.innerHTML = window.GANO_OBJECTIVES.map(o => `
      <button class="ochip${o.id === 'all' ? ' active' : ''}" data-obj="${o.id}" onclick="setObj(this,'${o.id}')">
        <i class="fa-solid ${o.icon}"></i> ${o.label}
      </button>`).join('');
  }

  /* ── Build timeline ── */
  function buildTimeline() {
    const el = document.getElementById('timeline-track');
    if (!el || !window.GANO_TIMELINE) return;
    el.innerHTML = window.GANO_TIMELINE.map(t => `
      <div class="tl-item">
        <span class="tl-time">${t.t}</span>
        <div class="tl-icon"><i class="fa-${t.iconBrand ? 'brands' : 'solid'} ${t.icon}"></i></div>
        <div class="tl-title">${t.title}</div>
        <div class="tl-body">${t.body}</div>
      </div>`).join('');
  }

  /* ── Glossary tooltip logic ── */
  const tooltip = document.getElementById('gtooltip');

  function bindGlossaryTips() {
    document.querySelectorAll('.glossary-tip').forEach(el => {
      el.addEventListener('mouseenter', showTooltip);
      el.addEventListener('mouseleave', hideTooltip);
      el.addEventListener('mousemove', positionTooltip);
    });
  }

  function showTooltip(e) {
    const term = e.target.dataset.term;
    const data = window.GANO_GLOSSARY && window.GANO_GLOSSARY[term];
    if (!data || !tooltip) return;
    document.getElementById('gtt-title').textContent = data.title;
    document.getElementById('gtt-body').textContent = data.body;
    const metEl = document.getElementById('gtt-metric');
    if (data.metric) { metEl.textContent = data.metric; metEl.style.display = 'inline-block'; }
    else { metEl.style.display = 'none'; }
    tooltip.classList.add('visible');
    positionTooltip(e);
  }

  function hideTooltip() {
    if (tooltip) tooltip.classList.remove('visible');
  }

  function positionTooltip(e) {
    if (!tooltip) return;
    const x = e.clientX + 16;
    const y = e.clientY + 16;
    const tw = tooltip.offsetWidth;
    const th = tooltip.offsetHeight;
    const vw = window.innerWidth;
    const vh = window.innerHeight;
    tooltip.style.left = (x + tw > vw ? x - tw - 32 : x) + 'px';
    tooltip.style.top  = (y + th > vh ? y - th - 32 : y) + 'px';
  }

  /* ── Price toggle ── */
  document.getElementById('price-toggle').addEventListener('change', function () {
    isAnnual = this.checked;
    const lm = document.getElementById('lbl-monthly');
    const la = document.getElementById('lbl-annual');
    if (lm) { lm.classList.toggle('on', !isAnnual); }
    if (la) { la.classList.toggle('on', isAnnual); }
    updatePrices();
  });

  /* ── Global filter handlers (called from inline onclick) ── */
  window.setCat = function (btn, id) {
    document.querySelectorAll('.ftab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeCategory = id;
    applyFilters();
  };

  window.setObj = function (btn, id) {
    document.querySelectorAll('.ochip').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeObjective = id;
    applyFilters();
  };

  window.toggleSpecs = function (btn, panelId) {
    const panel = document.getElementById(panelId);
    if (!panel) return;
    const open = panel.classList.toggle('show');
    btn.classList.toggle('open', open);
  };

  /* ── Init ── */
  function init() {
    // Set toggle to annual by default
    const tog = document.getElementById('price-toggle');
    if (tog) { tog.checked = true; }
    document.getElementById('lbl-annual')?.classList.add('on');
    document.getElementById('lbl-monthly')?.classList.remove('on');

    buildCatTabs();
    buildObjChips();
    renderGrid();
    buildTimeline();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
