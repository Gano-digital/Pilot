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

  function setupLeadCapture() {
    var form = document.getElementById("gano-lead-magnet");
    if (!form) return;

    var statusNode = form.querySelector("[data-gano-form-status]");
    form.addEventListener("submit", async function (event) {
      event.preventDefault();
      var email = form.email ? String(form.email.value || "").trim() : "";
      var nonce = form.nonce ? String(form.nonce.value || "") : "";
      var plan = form.plan ? String(form.plan.value || "homepage-sota") : "homepage-sota";

      if (!email) {
        if (statusNode) statusNode.textContent = "Ingresa un correo válido para continuar.";
        return;
      }

      if (statusNode) statusNode.textContent = "Enviando...";

      try {
        var endpoint =
          (window.ganoHomepageConfig && window.ganoHomepageConfig.leadEndpoint) ||
          "/wp-json/gano/v1/lead-capture";
        var response = await fetch(endpoint, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email: email, nonce: nonce, plan: plan }),
        });
        var data = await response.json();
        if (!response.ok) {
          throw new Error(data && data.error ? data.error : "No se pudo capturar el lead");
        }

        if (statusNode) {
          statusNode.textContent = "Listo. Revisa tu correo para continuar.";
        }
        form.reset();
        safeTrack("gano_lead_capture", {
          source: "homepage",
          email_domain: email.split("@")[1] || "",
        });
      } catch (error) {
        if (statusNode) {
          statusNode.textContent = "No fue posible enviar tu registro. Intenta nuevamente.";
        }
        console.error("Lead capture error:", error);
      }
    });
  }

  function setupAdvancedModesDisclosure() {
    var toggle = document.querySelector("[data-gano-advanced-toggle]");
    var panel = document.querySelector("[data-gano-advanced-modes]");
    if (!toggle || !panel) return;

    toggle.addEventListener("click", function () {
      var isExpanded = toggle.getAttribute("aria-expanded") === "true";
      toggle.setAttribute("aria-expanded", isExpanded ? "false" : "true");
      panel.hidden = isExpanded;
      safeTrack("gano_catalog_advanced_modes_toggle", {
        expanded: !isExpanded,
      });
    });
  }

  function setupMobileCatalogExpansion() {
    var root = document.querySelector(".gano-home");
    var button = document.querySelector("[data-gano-mobile-more]");
    if (!root || !button) return;

    button.addEventListener("click", function () {
      var expanded = root.classList.toggle("catalog-mobile-expanded");
      button.setAttribute("aria-expanded", expanded ? "true" : "false");
      button.textContent = expanded ? "Mostrar menos planes" : "Ver más planes";
      safeTrack("gano_catalog_mobile_expand_toggle", { expanded: expanded });
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    setupLeadCapture();
    setupAdvancedModesDisclosure();
    setupMobileCatalogExpansion();
  });
})();
