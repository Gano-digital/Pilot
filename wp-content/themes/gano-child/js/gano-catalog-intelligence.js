(function () {
  function safeTrack(eventName, payload) {
    if (typeof window.gtag === "function") {
      window.gtag("event", eventName, payload || {});
    }
    document.dispatchEvent(
      new CustomEvent("gano:analytics", {
        detail: { event: eventName, payload: payload || {} },
      }),
    );
  }

  function setupMobileFullscreenNav() {
    const header = document.querySelector("#site-header, header.site-header");
    const nav = header ? header.querySelector("nav") : null;
    if (!header || !nav || document.querySelector(".gano-mobile-nav-toggle")) {
      return;
    }

    const toggle = document.createElement("button");
    toggle.type = "button";
    toggle.className = "gano-mobile-nav-toggle";
    toggle.setAttribute("aria-label", "Abrir navegación");
    toggle.setAttribute("aria-expanded", "false");
    toggle.innerHTML = "☰";

    const panel = document.createElement("aside");
    panel.className = "gano-mobile-nav-panel";
    panel.setAttribute("aria-label", "Navegación móvil");
    panel.innerHTML =
      '<a href="/">Inicio</a>' +
      '<a href="/ecosistemas">Ecosistemas</a>' +
      '<a href="/shop-premium">Catálogo</a>' +
      '<a href="/sota-hub">SOTA Hub</a>' +
      '<a href="/diagnostico-digital">Diagnóstico</a>' +
      '<a href="/contacto" class="gano-mobile-nav-cta">Hablar con ventas</a>' +
      '<a href="#" data-gano-close-mobile-nav>Cerrar</a>';

    header.appendChild(toggle);
    document.body.appendChild(panel);

    const close = () => {
      document.body.classList.remove("gano-mobile-nav-open");
      toggle.setAttribute("aria-expanded", "false");
    };

    toggle.addEventListener("click", function () {
      const open = !document.body.classList.contains("gano-mobile-nav-open");
      document.body.classList.toggle("gano-mobile-nav-open", open);
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      if (open) {
        safeTrack("gano_mobile_nav_open", { source: "toggle" });
      }
    });

    panel.addEventListener("click", function (event) {
      const target = event.target;
      if (!(target instanceof HTMLElement)) {
        return;
      }
      if (target.hasAttribute("data-gano-close-mobile-nav")) {
        event.preventDefault();
        close();
      } else if (target.tagName === "A") {
        close();
      }
    });
  }

  function setupCatalogIntelligence() {
    const shell = document.querySelector("[data-gano-catalog]");
    if (!shell) {
      return;
    }

    const config = window.ganoCatalogConfig || {};
    const categories = Array.from(
      shell.querySelectorAll("[data-category], .product-card"),
    );
    const catalogContainer =
      shell.querySelector("#catalog-container") ||
      shell.querySelector(".catalog-grid") ||
      shell.querySelector(".ecosistemas-grid") ||
      shell.querySelector(".gano-plans-grid");
    const familyHost = document.createElement("div");
    familyHost.className = "gano-catalog-family-host";
    const originalParent =
      catalogContainer ||
      (categories[0] ? categories[0].parentElement : null);
    const categoryLabels = {};
    Array.from(shell.querySelectorAll("[data-filter]")).forEach(function (node) {
      const key = node.getAttribute("data-filter");
      if (key && key !== "all") {
        categoryLabels[key] = (node.textContent || key).trim();
      }
    });
    const modeButtons = Array.from(shell.querySelectorAll("[data-gano-mode]"));
    const modeDesc = shell.querySelector("[data-gano-mode-description]");
    const guidedPanel = shell.querySelector("[data-gano-guided-panel]");
    const guidedList = shell.querySelector("[data-gano-guided-list]");
    const compareBox = shell.querySelector("[data-gano-compare]");
    const compareList = shell.querySelector("[data-gano-compare-list]");
    const compareGrid = shell.querySelector("[data-gano-compare-grid]");
    const compareState = new Set(
      JSON.parse(window.sessionStorage.getItem("gano-catalog-compare") || "[]"),
    );

    function setCardVisibility(card, visible) {
      card.style.display = visible ? "flex" : "none";
      card.setAttribute("aria-hidden", visible ? "false" : "true");
    }

    function applyCategoryFilter(category) {
      categories.forEach(function (card) {
        const cardCategory =
          card.getAttribute("data-category") ||
          card.dataset.category ||
          "unknown";
        const show = category === "all" || cardCategory === category;
        setCardVisibility(card, show);
      });
    }

    function renderFamilyMode() {
      if (!originalParent || !categories.length) {
        return;
      }
      familyHost.innerHTML = "";
      const groups = new Map();
      categories.forEach(function (card) {
        const key = card.getAttribute("data-category") || "otros";
        if (!groups.has(key)) {
          groups.set(key, []);
        }
        groups.get(key).push(card);
      });

      groups.forEach(function (cards, key) {
        const section = document.createElement("section");
        section.className = "gano-catalog-family-section";
        section.setAttribute("data-family-section", key);

        const title = document.createElement("h3");
        title.className = "gano-catalog-family-title";
        title.textContent = categoryLabels[key] || key;
        section.appendChild(title);

        const grid = document.createElement("div");
        grid.className = "catalog-grid";
        cards.forEach(function (card) {
          grid.appendChild(card);
          setCardVisibility(card, true);
        });
        section.appendChild(grid);
        familyHost.appendChild(section);
      });

      if (!familyHost.parentElement && originalParent.parentElement) {
        originalParent.parentElement.insertBefore(familyHost, originalParent);
      }
      originalParent.style.display = "none";
      familyHost.style.display = "block";
    }

    function restoreGridMode() {
      if (!originalParent || !categories.length) {
        return;
      }
      categories.forEach(function (card) {
        originalParent.appendChild(card);
      });
      originalParent.style.display = "grid";
      if (familyHost.parentElement) {
        familyHost.style.display = "none";
      }
    }

    function renderGuidedOptions() {
      if (!guidedList || !Array.isArray(config.guided)) {
        return;
      }
      guidedList.innerHTML = "";
      config.guided.forEach(function (intent) {
        const li = document.createElement("li");
        const btn = document.createElement("button");
        btn.type = "button";
        btn.className = "gano-catalog-guided-btn";
        btn.innerHTML =
          "<strong>" +
          intent.label +
          "</strong><span>" +
          intent.description +
          "</span>";
        btn.addEventListener("click", function () {
          categories.forEach(function (card) {
            const cardCategory = card.getAttribute("data-category") || "";
            const visible = Array.isArray(intent.categories)
              ? intent.categories.includes(cardCategory)
              : true;
            setCardVisibility(card, visible);
          });
          safeTrack("gano_catalog_guided_intent", { intent: intent.id });
        });
        li.appendChild(btn);
        guidedList.appendChild(li);
      });
    }

    function renderComparator() {
      if (!compareBox || !compareList || !compareGrid) {
        return;
      }
      const selected = categories.filter(function (card) {
        const id = card.getAttribute("data-product-id");
        return id && compareState.has(id);
      });

      compareBox.hidden = selected.length === 0;
      compareList.innerHTML = "";
      compareGrid.innerHTML = "";

      selected.slice(0, 3).forEach(function (card) {
        const id = card.getAttribute("data-product-id") || "";
        const name =
          card.getAttribute("data-product-name") ||
          (card.querySelector(".p-name, h3") || {}).textContent ||
          "Producto";
        const price =
          card.getAttribute("data-product-price") ||
          (card.querySelector(".p-price, .gano-plan-price") || {}).textContent ||
          "Sin precio";

        const chip = document.createElement("li");
        chip.className = "gano-catalog-compare-chip";
        chip.textContent = name;
        compareList.appendChild(chip);

        const gridItem = document.createElement("article");
        gridItem.className = "gano-catalog-compare-card";
        gridItem.innerHTML =
          "<h4>" +
          name +
          "</h4><p>" +
          price +
          '</p><button type="button" data-remove-compare="' +
          id +
          '">Quitar</button>';
        compareGrid.appendChild(gridItem);
      });

      window.sessionStorage.setItem(
        "gano-catalog-compare",
        JSON.stringify(Array.from(compareState).slice(0, 3)),
      );
    }

    function toggleCompare(card, btn) {
      const id = card.getAttribute("data-product-id");
      if (!id) return;
      if (compareState.has(id)) {
        compareState.delete(id);
      } else if (compareState.size < 3) {
        compareState.add(id);
      }
      btn.setAttribute("aria-pressed", compareState.has(id) ? "true" : "false");
      renderComparator();
      safeTrack("gano_catalog_compare_toggle", {
        product_id: id,
        selected: compareState.has(id),
      });
    }

    categories.forEach(function (card, index) {
      if (!card.getAttribute("data-product-id")) {
        card.setAttribute("data-product-id", "gano-product-" + index);
      }
      const compareBtn = card.querySelector("[data-gano-compare-toggle]");
      if (compareBtn) {
        compareBtn.setAttribute(
          "aria-pressed",
          compareState.has(card.getAttribute("data-product-id")) ? "true" : "false",
        );
        compareBtn.addEventListener("click", function () {
          toggleCompare(card, compareBtn);
        });
      }

      const primaryCta = card.querySelector(
        ".rstore-add-to-cart, .gano-km-btn-primary, .gano-btn-secondary",
      );
      if (primaryCta) {
        primaryCta.addEventListener("click", function () {
          safeTrack("gano_catalog_cta_click", {
            product_id: card.getAttribute("data-product-id") || "",
            product_name: card.getAttribute("data-product-name") || "",
            category: card.getAttribute("data-category") || "",
            target_url: primaryCta.getAttribute("href") || "",
          });
        });
      }
    });

    if (compareGrid) {
      compareGrid.addEventListener("click", function (event) {
        const target = event.target;
        if (!(target instanceof HTMLElement)) return;
        const id = target.getAttribute("data-remove-compare");
        if (!id) return;
        compareState.delete(id);
        const sourceCard = categories.find(function (card) {
          return card.getAttribute("data-product-id") === id;
        });
        if (sourceCard) {
          const sourceToggle = sourceCard.querySelector("[data-gano-compare-toggle]");
          if (sourceToggle) sourceToggle.setAttribute("aria-pressed", "false");
        }
        renderComparator();
      });
    }

    function setMode(mode) {
      const modeMap = config.modes || {};
      modeButtons.forEach(function (btn) {
        const isActive = btn.getAttribute("data-gano-mode") === mode;
        btn.setAttribute("aria-pressed", isActive ? "true" : "false");
      });
      if (modeDesc && modeMap[mode]) {
        modeDesc.textContent = modeMap[mode].description || "";
      }
      if (guidedPanel) {
        guidedPanel.classList.toggle("is-visible", mode === "guided");
      }

      if (mode === "grid") {
        restoreGridMode();
        applyCategoryFilter("all");
      } else if (mode === "family") {
        renderFamilyMode();
      } else {
        restoreGridMode();
      }

      shell.setAttribute("data-gano-mode-active", mode);
      safeTrack("gano_catalog_mode_change", { mode: mode });
    }

    modeButtons.forEach(function (btn) {
      btn.addEventListener("click", function () {
        setMode(btn.getAttribute("data-gano-mode") || "grid");
      });
    });

    const filterNodes = Array.from(
      shell.querySelectorAll("[data-filter], .nav-item"),
    );
    filterNodes.forEach(function (node) {
      node.addEventListener("click", function () {
        const filter = node.getAttribute("data-filter") || "all";
        filterNodes.forEach(function (el) {
          el.classList.remove("active");
        });
        node.classList.add("active");
        applyCategoryFilter(filter);
        safeTrack("gano_catalog_category_filter", { category: filter });
      });
    });

    renderGuidedOptions();
    renderComparator();
    setMode(config.defaultMode || "grid");
  }

  document.addEventListener("DOMContentLoaded", function () {
    setupMobileFullscreenNav();
    setupCatalogIntelligence();
  });
})();
