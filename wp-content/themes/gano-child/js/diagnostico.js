/**
 * Gano Diagnóstico Digital — Motor de Quiz y Recomendaciones
 * Namespace: todo dentro de ganoDiagnostico
 * Adaptado para WordPress: usa fetch() para enviar leads vía REST API
 */
(function () {
  'use strict';

  // ── Estado ──────────────────────────────────────────────────────────
  var answers = { size: '', sector: '', web: '', email: '', data: '', channels: [], assets: [], goal: '' };
  var currentStep = 0;
  var totalSteps = 8;
  var nonce = window.ganoDiagnosticoVars ? window.ganoDiagnosticoVars.nonce : '';
  var restUrl = window.ganoDiagnosticoVars ? window.ganoDiagnosticoVars.restUrl : '';
  var leadSubmitted = false;

  // ── Productos Gano (mapeo para resultados) ──────────────────────────
  var GANO_PRODUCTS = {
    'nucleo-prime': {
      cat: 'Hosting WordPress', catColor: '#c0c1ff', catIcon: 'dns',
      name: 'Núcleo Prime — Start WP',
      why: 'El punto de partida correcto para tu presencia digital. NVMe real, WordPress preinstalado, soporte en español y activación sin fricciones.',
      price: '$19.800 COP/mes',
      url: '/ecosistemas/',
      priority: false
    },
    'fortaleza-delta': {
      cat: 'Hosting WordPress', catColor: '#c0c1ff', catIcon: 'dns',
      name: 'Fortaleza Delta — Pro Managed',
      why: 'Para marcas que ya generan ingresos. Más recursos, hardening activo, WooCommerce soportado y visibilidad operativa.',
      price: '$45.000 COP/mes',
      url: '/ecosistemas/',
      priority: true
    },
    'bastion-sota': {
      cat: 'Hosting NVMe Gen4', catColor: '#4cd7f6', catIcon: 'bolt',
      name: 'Bastión SOTA — Business NVMe',
      why: 'Rendimiento crítico para operaciones que no toleran degradación. Recursos dedicados, monitoreo proactivo, agente IA y SLA 99.9%.',
      price: '$89.000 COP/mes',
      url: '/ecosistemas/',
      priority: true
    },
    'ssl-pro': {
      cat: 'Seguridad', catColor: '#8083ff', catIcon: 'shield',
      name: 'SSL Wildcard Pro',
      why: 'Con datos de clientes o pagos, necesitas cifrado visible. SSL Wildcard cubre todos tus subdominios con validación extendida.',
      price: '$340.000 COP/año',
      url: '/ecosistemas/',
      priority: false
    },
    'm365': {
      cat: 'Email Corporativo', catColor: '#4cd7f6', catIcon: 'mail',
      name: 'Microsoft 365 Business',
      why: 'Cada correo enviado desde @gmail.com le cuesta credibilidad. M365 con tu dominio: Teams, SharePoint y 1 TB de almacenamiento.',
      price: '$12.000 COP/mes/usuario',
      url: '/ecosistemas/',
      priority: false
    },
    'dominio': {
      cat: 'Dominio', catColor: '#d0bcff', catIcon: 'public',
      name: 'Registro de Dominio .co / .com',
      why: 'Tu dirección en internet. Sin dominio propio no hay sitio web ni correo corporativo. Privacidad WHOIS incluida gratis.',
      price: 'Desde $65.000 COP/año',
      url: '/dominios/',
      priority: false
    },
    'diagnostico-soberania': {
      cat: 'Consultoría Gano', catColor: '#c0c1ff', catIcon: 'stethoscope',
      name: 'Diagnóstico de Soberanía Digital',
      why: 'Auditoría técnica completa de tu stack actual. Informe ejecutivo en 72 horas con benchmarks reales y plan de blindaje. Se acredita al contratar cualquier ecosistema.',
      price: '$650.000 COP (único pago)',
      url: '/contacto/',
      priority: false
    }
  };

  // ── DOM helpers ─────────────────────────────────────────────────────
  function $(sel, ctx) { return (ctx || document).querySelector(sel); }
  function $$(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }

  // ── Paso 1: selección simple ────────────────────────────────────────
  window.ganoSelectOption = function (el, key, val) {
    var parent = el.parentElement;
    parent.querySelectorAll('.gano-diagnostico__option').forEach(function (o) { o.classList.remove('selected'); });
    el.classList.add('selected');
    answers[key] = val;
    var btn = document.getElementById('gd-next-' + currentStep);
    if (btn) btn.disabled = false;
  };

  // ── Paso 2: checkbox multi-selección ────────────────────────────────
  window.ganoToggleCB = function (el, key, val) {
    if (val === 'none') {
      var grid = el.closest('.gano-diagnostico__checkbox-grid') || el.parentElement;
      grid.querySelectorAll('.gano-diagnostico__cb-item').forEach(function (c) { if (c !== el) c.classList.remove('selected'); });
      if (!answers[key]) answers[key] = [];
      answers[key] = [];
    } else {
      var noneEl = el.parentElement.querySelector('[onclick*="none"]');
      if (noneEl) noneEl.classList.remove('selected');
      var idx = answers[key] ? answers[key].indexOf('none') : -1;
      if (idx > -1) answers[key].splice(idx, 1);
    }
    el.classList.toggle('selected');
    if (!answers[key]) answers[key] = [];
    if (el.classList.contains('selected')) {
      if (answers[key].indexOf(val) < 0) answers[key].push(val);
    } else {
      var i = answers[key].indexOf(val);
      if (i > -1) answers[key].splice(i, 1);
    }
  };

  // ── Navegación ──────────────────────────────────────────────────────
  window.ganoNextStep = function () {
    var steps = $$('.gano-diagnostico__step');
    if (currentStep < totalSteps - 1) {
      steps[currentStep].classList.remove('active');
      currentStep++;
      steps[currentStep].classList.add('active');
      var pct = (currentStep / (totalSteps - 1)) * 100;
      var bar = $('#gd-progress');
      if (bar) bar.style.width = pct + '%';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
      showLeadForm();
    }
  };

  window.ganoPrevStep = function () {
    if (currentStep > 0) {
      var steps = $$('.gano-diagnostico__step');
      steps[currentStep].classList.remove('active');
      currentStep--;
      steps[currentStep].classList.add('active');
      var pct = (currentStep / (totalSteps - 1)) * 100;
      var bar = $('#gd-progress');
      if (bar) bar.style.width = pct + '%';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  };

  window.ganoRestart = function () {
    answers = { size: '', sector: '', web: '', email: '', data: '', channels: [], assets: [], goal: '' };
    currentStep = 0;
    leadSubmitted = false;
    $$('.gano-diagnostico__option').forEach(function (o) { o.classList.remove('selected'); });
    $$('.gano-diagnostico__cb-item').forEach(function (c) { c.classList.remove('selected'); });
    $$('.gano-diagnostico__btn-next').forEach(function (b) { if (b.id && b.id.startsWith('gd-next-')) b.disabled = true; });
    $$('.gano-diagnostico__step').forEach(function (s) { s.classList.remove('active'); });
    $$('.gano-diagnostico__step')[0].classList.add('active');
    var bar = $('#gd-progress');
    if (bar) bar.style.width = '0%';
    var quiz = $('#gd-quiz');
    var results = $('#gd-results');
    var leadBox = $('#gd-lead-box');
    var leadSuccess = $('#gd-lead-success');
    if (quiz) quiz.style.display = 'block';
    if (results) results.style.display = 'none';
    if (leadBox) leadBox.style.display = 'block';
    if (leadSuccess) leadSuccess.style.display = 'none';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  // ── Lead Form ───────────────────────────────────────────────────────
  function showLeadForm() {
    var overlay = $('#gd-lead-overlay');
    if (overlay) overlay.classList.add('active');
  }

  window.ganoSkipLead = function () {
    var overlay = $('#gd-lead-overlay');
    if (overlay) overlay.classList.remove('active');
    showResults();
  };

  window.ganoSubmitLead = function () {
    var name = $('#gd-lead-name').value.trim();
    var email = $('#gd-lead-email').value.trim();
    var phone = $('#gd-lead-phone').value.trim();
    var company = $('#gd-lead-company').value.trim();

    if (!name || !email) {
      alert('Por favor ingresa al menos tu nombre y correo electrónico.');
      return;
    }

    var btn = $('#gd-lead-submit');
    if (btn) { btn.disabled = true; btn.textContent = 'Enviando...'; }

    var payload = {
      name: name,
      email: email,
      phone: phone,
      company: company,
      answers: answers,
      source: 'diagnostico_digital',
      url: window.location.href
    };

    fetch(restUrl + 'gano/v1/lead', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': nonce
      },
      body: JSON.stringify(payload)
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        leadSubmitted = true;
        var box = $('#gd-lead-box');
        var success = $('#gd-lead-success');
        if (box) box.style.display = 'none';
        if (success) success.style.display = 'block';
        setTimeout(function () {
          var overlay = $('#gd-lead-overlay');
          if (overlay) overlay.classList.remove('active');
          showResults();
        }, 1500);
      })
      .catch(function (err) {
        console.error('Lead error:', err);
        leadSubmitted = true;
        var overlay = $('#gd-lead-overlay');
        if (overlay) overlay.classList.remove('active');
        showResults();
      });
  };

  // ── Motor de puntuación ─────────────────────────────────────────────
  function computeScores() {
    var infra = 0, sec = 0, web = 0, mkt = 0;

    if (answers.web === 'none') { web += 30; infra += 20; }
    if (answers.web === 'builder') { web += 20; infra += 25; }
    if (answers.web === 'wordpress') { infra += 35; }
    if (answers.web === 'ecommerce') { infra += 45; sec += 20; }
    if (answers.web === 'custom') { infra += 50; sec += 30; }

    if (answers.email === 'gmail' || answers.email === 'none') { infra += 20; }
    if (answers.email === 'domain') { infra += 10; }
    if (answers.email === 'm365') { infra -= 10; }

    if (answers.data === 'public') { sec += 10; }
    if (answers.data === 'clients') { sec += 30; }
    if (answers.data === 'payments') { sec += 50; }
    if (answers.data === 'sensitive') { sec += 60; }

    var ch = answers.channels || [];
    if (ch.indexOf('none') > -1) { mkt += 40; }
    else { mkt += Math.max(0, 30 - ch.length * 5); }

    var as = answers.assets || [];
    if (as.indexOf('logo') < 0) { web += 15; }
    if (as.indexOf('content') < 0) { mkt += 20; }
    if (as.indexOf('ads') < 0) { mkt += 10; }

    if (answers.goal === 'launch') { web += 20; infra += 10; }
    if (answers.goal === 'migrate') { infra += 30; }
    if (answers.goal === 'scale') { infra += 20; mkt += 20; }
    if (answers.goal === 'secure') { sec += 30; }
    if (answers.goal === 'automate') { infra += 20; }

    var max = Math.max(infra, sec, web, mkt, 50);
    return {
      infra: Math.min(100, Math.round(infra / max * 100)),
      sec: Math.min(100, Math.round(sec / max * 100)),
      web: Math.min(100, Math.round(web / max * 100)),
      mkt: Math.min(100, Math.round(mkt / max * 100))
    };
  }

  function getProfile(scores) {
    var top = Object.entries(scores).sort(function (a, b) { return b[1] - a[1]; })[0][0];
    var profiles = {
      infra: { icon: '🏗️', title: 'Constructor de Infraestructura', desc: 'Su empresa necesita una base sólida: servidor confiable, correo profesional y arquitectura para escalar sin interrupciones. Todo lo demás se construye sobre esto.' },
      sec: { icon: '🛡️', title: 'Custodio de Datos', desc: 'Sus operaciones manejan información sensible que requiere protección activa. La prioridad es blindar su infraestructura antes de cualquier otra expansión digital.' },
      web: { icon: '🌐', title: 'Constructor de Presencia Digital', desc: 'Su empresa tiene el potencial para destacar en línea pero necesita una vitrina digital profesional. El foco está en crear o mejorar su sitio, correo y activos de marca.' },
      mkt: { icon: '📣', title: 'Amplificador de Marca', desc: 'Tiene buena infraestructura pero necesita visibilidad. El foco es conectar con más clientes a través de los canales digitales correctos, con mensajes que conviertan.' }
    };
    return profiles[top];
  }

  function buildLegoHave() {
    var as = answers.assets || [];
    var ch = answers.channels || [];
    var blocks = [];
    if (as.indexOf('logo') > -1) blocks.push({ icon: '🎨', name: 'Logo', status: 'Listo ✓' });
    if (as.indexOf('brandbook') > -1) blocks.push({ icon: '📖', name: 'Manual de Marca', status: 'Listo ✓' });
    if (as.indexOf('domain') > -1) blocks.push({ icon: '🌐', name: 'Dominio', status: 'Registrado ✓' });
    if (as.indexOf('photos') > -1) blocks.push({ icon: '📸', name: 'Fotografía', status: 'Disponible ✓' });
    if (as.indexOf('flyers') > -1) blocks.push({ icon: '🖼️', name: 'Material Gráfico', status: 'Listo ✓' });
    if (as.indexOf('video') > -1) blocks.push({ icon: '🎬', name: 'Video Corporativo', status: 'Disponible ✓' });
    if (as.indexOf('content') > -1) blocks.push({ icon: '✍️', name: 'Contenido Escrito', status: 'Disponible ✓' });
    if (as.indexOf('ads') > -1) blocks.push({ icon: '📢', name: 'Pauta Digital', status: 'Activa ✓' });
    if (ch.indexOf('instagram') > -1) blocks.push({ icon: '📱', name: 'Instagram', status: 'Activo ✓' });
    if (ch.indexOf('facebook') > -1) blocks.push({ icon: '📘', name: 'Facebook', status: 'Activo ✓' });
    if (ch.indexOf('linkedin') > -1) blocks.push({ icon: '💼', name: 'LinkedIn', status: 'Activo ✓' });
    if (ch.indexOf('whatsapp') > -1) blocks.push({ icon: '💬', name: 'WhatsApp Biz', status: 'Activo ✓' });
    if (answers.email === 'm365') blocks.push({ icon: '🏢', name: 'M365', status: 'Activo ✓' });
    if (answers.email === 'domain') blocks.push({ icon: '✉️', name: 'Correo propio', status: 'Activo ✓' });
    if (answers.web === 'ecommerce' || answers.web === 'wordpress' || answers.web === 'custom') blocks.push({ icon: '🌍', name: 'Sitio web', status: 'Activo ✓' });
    if (blocks.length === 0) blocks.push({ icon: '📦', name: 'Sin recursos aún', status: 'Comenzando' });
    return blocks;
  }

  function buildLegoNeed() {
    var needs = [];
    if (answers.web === 'none' || answers.web === 'builder') needs.push({ icon: '🌐', name: 'Sitio Web Profesional', status: 'Necesario' });
    if (answers.email === 'gmail' || answers.email === 'none') needs.push({ icon: '✉️', name: 'Correo Corporativo', status: 'Necesario' });
    if (!answers.assets || answers.assets.indexOf('domain') < 0) needs.push({ icon: '🔗', name: 'Dominio Propio', status: 'Necesario' });
    if (answers.data === 'clients' || answers.data === 'payments' || answers.data === 'sensitive') needs.push({ icon: '🛡️', name: 'SSL Certificate', status: 'Necesario' });
    if (answers.data === 'payments' || answers.data === 'sensitive') needs.push({ icon: '🔒', name: 'Seguridad Web Avanzada', status: 'Crítico' });
    if (answers.channels && answers.channels.indexOf('none') > -1) needs.push({ icon: '📱', name: 'Redes Sociales', status: 'Recomendado' });
    if (!answers.assets || answers.assets.indexOf('logo') < 0) needs.push({ icon: '🎨', name: 'Identidad Visual', status: 'Recomendado' });
    if (answers.goal === 'scale') needs.push({ icon: '⚡', name: 'Infraestructura Escalable', status: 'Para escalar' });
    return needs;
  }

  function buildRecos(scores) {
    var recos = [];

    // Siempre recomendar hosting según tamaño y situación
    if (answers.size === 'solo' || answers.size === 'micro') {
      recos.push(GANO_PRODUCTS['nucleo-prime']);
    } else if (answers.size === 'small') {
      recos.push(GANO_PRODUCTS['fortaleza-delta']);
    } else {
      recos.push(GANO_PRODUCTS['bastion-sota']);
    }

    // Si no tiene dominio
    if (!answers.assets || answers.assets.indexOf('domain') < 0) {
      recos.push(GANO_PRODUCTS['dominio']);
    }

    // Si usa Gmail o no tiene email
    if (answers.email === 'gmail' || answers.email === 'none') {
      recos.push(GANO_PRODUCTS['m365']);
    }

    // Si maneja datos sensibles
    if (answers.data === 'clients' || answers.data === 'payments' || answers.data === 'sensitive') {
      recos.push(GANO_PRODUCTS['ssl-pro']);
    }

    // Si es empresa mediana+ o e-commerce
    if (answers.size === 'medium' || answers.size === 'large' || answers.web === 'ecommerce') {
      // Reemplazar nucleo-prime por bastion-sota si ya está en la lista
      var hasBastion = recos.some(function (r) { return r.name.indexOf('Bastión') > -1; });
      if (!hasBastion) {
        recos.unshift(GANO_PRODUCTS['bastion-sota']);
      }
    }

    // Agregar diagnóstico de soberanía siempre al final
    recos.push(GANO_PRODUCTS['diagnostico-soberania']);

    // Marcar prioritarios
    recos.forEach(function (r) {
      if (r.name.indexOf('Bastión') > -1 || r.name.indexOf('Fortaleza') > -1) {
        r.priority = true;
      }
    });

    return recos;
  }

  // ── Render resultados ───────────────────────────────────────────────
  function showResults() {
    var quiz = $('#gd-quiz');
    var res = $('#gd-results');
    if (quiz) quiz.style.display = 'none';
    if (res) { res.style.display = 'block'; window.scrollTo({ top: 0, behavior: 'smooth' }); }

    var scores = computeScores();
    var profile = getProfile(scores);

    // Profile badge
    var profileEl = $('#gd-result-profile');
    if (profileEl) {
      profileEl.innerHTML = '<div class="gano-diagnostico__profile-icon">' + profile.icon + '</div>' +
        '<div><div class="gano-diagnostico__profile-title">' + profile.title + '</div>' +
        '<div class="gano-diagnostico__profile-desc">' + profile.desc + '</div></div>';
    }

    // Segments
    var segDef = [
      { key: 'infra', label: 'Infraestructura', color: '#c0c1ff', icon: 'dns' },
      { key: 'sec', label: 'Seguridad', color: '#8083ff', icon: 'shield' },
      { key: 'web', label: 'Presencia Web', color: '#4cd7f6', icon: 'language' },
      { key: 'mkt', label: 'Marketing Digital', color: '#d0bcff', icon: 'trending_up' },
    ];
    var segHtml = '';
    segDef.forEach(function (s) {
      var pct = scores[s.key];
      var label_text = pct > 70 ? 'Alta necesidad' : pct > 40 ? 'Necesidad media' : 'Cubierto o bajo';
      segHtml += '<div class="gano-diagnostico__segment">' +
        '<div class="gano-diagnostico__seg-label" style="display:flex;align-items:center;gap:.3rem">' + s.label + '</div>' +
        '<div class="gano-diagnostico__seg-bar"><div class="gano-diagnostico__seg-fill" style="width:' + pct + '%%;background:linear-gradient(90deg,' + s.color + ',rgba(45,52,73,.5))"></div></div>' +
        '<div class="gano-diagnostico__seg-pct" style="color:' + s.color + '">' + pct + '%</div>' +
        '<div style="font-size:.72rem;color:var(--gd-subt);margin-top:.2rem">' + label_text + '</div>' +
        '</div>';
    });
    var segContainer = $('#gd-result-segments');
    if (segContainer) segContainer.innerHTML = segHtml;

    // LEGO have
    var have = buildLegoHave();
    var haveEl = $('#gd-lego-have');
    if (haveEl) {
      haveEl.innerHTML = have.map(function (b) {
        return '<div class="gano-diagnostico__lego-block gano-diagnostico__lego-block--have"><span class="gano-diagnostico__lego-icon">' + b.icon + '</span><div class="gano-diagnostico__lego-name">' + b.name + '</div><span class="gano-diagnostico__lego-status">' + b.status + '</span></div>';
      }).join('');
    }

    // LEGO need
    var need = buildLegoNeed();
    var needEl = $('#gd-lego-need');
    if (needEl) {
      needEl.innerHTML = need.length ? need.map(function (b) {
        var cls = b.status === 'Crítico' ? 'gano-diagnostico__lego-block--need' : b.status === 'Necesario' ? 'gano-diagnostico__lego-block--need' : 'gano-diagnostico__lego-block--optional';
        return '<div class="gano-diagnostico__lego-block ' + cls + '"><span class="gano-diagnostico__lego-icon">' + b.icon + '</span><div class="gano-diagnostico__lego-name">' + b.name + '</div><span class="gano-diagnostico__lego-status">' + b.status + '</span></div>';
      }).join('') : '<div style="color:var(--gd-subt);font-size:.85rem;padding:1rem;">¡Excelente! Su infraestructura digital parece bien cubierta.</div>';
    }

    // Recommendations
    var recos = buildRecos(scores);
    var recosEl = $('#gd-result-recos');
    if (recosEl) {
      recosEl.innerHTML = recos.map(function (r) {
        return '<div class="gano-diagnostico__reco-card' + (r.priority ? ' gano-diagnostico__reco-card--priority' : '') + '">' +
          (r.priority ? '<span class="gano-diagnostico__reco-priority-badge">⭐ Prioritario</span>' : '') +
          '<div class="gano-diagnostico__reco-cat" style="color:' + r.catColor + '">' + r.cat + '</div>' +
          '<div class="gano-diagnostico__reco-name">' + r.name + '</div>' +
          '<div class="gano-diagnostico__reco-why">' + r.why + '</div>' +
          '<div class="gano-diagnostico__reco-price" style="color:' + r.catColor + '">' + r.price + '</div>' +
          '<a href="' + r.url + '" class="gano-diagnostico__reco-btn">Ver detalles →</a>' +
          '</div>';
      }).join('');
    }
  }
})();
