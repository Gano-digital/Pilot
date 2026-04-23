(function () {
  "use strict";

  function qs(sel, root) {
    return (root || document).querySelector(sel);
  }

  function clamp(n, a, b) {
    return Math.max(a, Math.min(b, n));
  }

  function percentile(sortedAsc, p) {
    if (!sortedAsc.length) return null;
    const idx = (sortedAsc.length - 1) * p;
    const lo = Math.floor(idx);
    const hi = Math.ceil(idx);
    if (lo === hi) return sortedAsc[lo];
    const t = idx - lo;
    return sortedAsc[lo] * (1 - t) + sortedAsc[hi] * t;
  }

  function nowMs() {
    return performance.now();
  }

  function formatTimeHHMMSS(date) {
    const h = String(date.getHours()).padStart(2, "0");
    const m = String(date.getMinutes()).padStart(2, "0");
    const s = String(date.getSeconds()).padStart(2, "0");
    return h + ":" + m + ":" + s;
  }

  function getDclMs() {
    // Navigation Timing Level 2
    const nav = performance.getEntriesByType && performance.getEntriesByType("navigation");
    if (nav && nav.length) {
      const n = nav[0];
      if (typeof n.domContentLoadedEventEnd === "number" && n.domContentLoadedEventEnd > 0) {
        return Math.round(n.domContentLoadedEventEnd);
      }
    }
    // Legacy fallback
    // eslint-disable-next-line compat/compat
    const t = performance.timing;
    if (t && t.navigationStart && t.domContentLoadedEventEnd) {
      return Math.max(0, Math.round(t.domContentLoadedEventEnd - t.navigationStart));
    }
    return null;
  }

  function makeAbortableTimeout(ms) {
    const ctrl = new AbortController();
    const t = setTimeout(function () {
      ctrl.abort();
    }, ms);
    return { ctrl: ctrl, cancel: function () { clearTimeout(t); } };
  }

  function initHud() {
    const root = qs("[data-gano-hud]");
    if (!root) return;

    const bar = qs("[data-gano-hud-bar]", root);
    const sr = qs("[data-gano-hud-sr]", root);

    const fpsEl = qs("[data-gano-hud-fps]", root);
    const dclEl = qs("[data-gano-hud-dcl]", root);
    const pingEl = qs("[data-gano-hud-ping]", root);
    const timeEl = qs("[data-gano-hud-time]", root);

    if (!bar || !fpsEl || !dclEl || !pingEl || !timeEl) return;

    // Mobile expand
    let expandTimer = null;
    function setExpanded(on) {
      root.classList.toggle("is-expanded", on);
      if (expandTimer) {
        clearTimeout(expandTimer);
        expandTimer = null;
      }
      if (on) {
        expandTimer = setTimeout(function () {
          setExpanded(false);
        }, 10000);
      }
    }
    bar.addEventListener("click", function () {
      // only meaningful on mobile, but safe on desktop too
      setExpanded(!root.classList.contains("is-expanded"));
    });

    // Time (local)
    function tickTime() {
      timeEl.textContent = formatTimeHHMMSS(new Date());
      timeEl.setAttribute("data-t", timeEl.textContent);
    }
    tickTime();
    setInterval(tickTime, 250);

    // FPS measurement (update display at 10Hz)
    let frames = 0;
    let lastFpsSample = nowMs();
    let lastFps = 0;
    function rafLoop() {
      frames++;
      requestAnimationFrame(rafLoop);
    }
    requestAnimationFrame(rafLoop);

    setInterval(function () {
      const t = nowMs();
      const dt = Math.max(1, t - lastFpsSample);
      const fps = Math.round((frames * 1000) / dt);
      frames = 0;
      lastFpsSample = t;
      lastFps = clamp(fps, 0, 240);
      fpsEl.textContent = String(lastFps).padStart(2, "0");
      fpsEl.setAttribute("data-t", fpsEl.textContent);
    }, 100);

    // DCL + soft-nav estimate anchored to #gano-main-content
    let dclInitial = getDclMs();
    let dclSoft = null;
    if (dclInitial != null) {
      dclEl.textContent = String(dclInitial) + "ms";
      dclEl.setAttribute("data-t", dclEl.textContent);
    } else {
      dclEl.textContent = "---";
      dclEl.setAttribute("data-t", dclEl.textContent);
    }

    let pendingSoftNav = null;
    const mainAnchor = qs("#gano-main-content");
    if (mainAnchor) {
      const io = new IntersectionObserver(
        function (entries) {
          if (!pendingSoftNav) return;
          const vis = entries && entries[0] && entries[0].isIntersecting;
          if (!vis) return;
          const dt = Math.round(nowMs() - pendingSoftNav.t0);
          pendingSoftNav = null;
          dclSoft = dt;
          dclEl.textContent = String(dt) + "ms";
          dclEl.setAttribute("data-t", dclEl.textContent);
        },
        { root: null, threshold: 0.01 }
      );
      io.observe(mainAnchor);

      document.addEventListener(
        "click",
        function (e) {
          const a = e.target && e.target.closest ? e.target.closest("a") : null;
          if (!a) return;
          const href = a.getAttribute("href") || "";
          if (!href || href[0] === "#") return;
          try {
            const u = new URL(href, location.href);
            if (u.origin !== location.origin) return;
          } catch {
            return;
          }
          pendingSoftNav = { t0: nowMs() };
        },
        true
      );
    }

    // Ping with retry + backoff
    const pingSamples = [];
    const dclSamples = [];
    let consecutiveFails = 0;
    let backoffUntil = 0;
    let intervalMs = 5000;
    const retryDelayMs = 300;

    function setState(next) {
      const prev = root.getAttribute("data-state") || "ok";
      root.setAttribute("data-state", next);
      if (sr && prev !== next) {
        sr.textContent = next === "ok" ? "Estado estable" : next === "warn" ? "Atención: variación detectada" : "Alerta: degradación detectada";
      }
    }

    let glitchStrongUntil = 0;
    let glitchWeakUntil = 0;

    function triggerGlitch(strong) {
      const t = nowMs();
      const dur = strong ? 1100 : 700;
      if (strong) {
        glitchStrongUntil = Math.max(glitchStrongUntil, t + dur);
      } else {
        glitchWeakUntil = Math.max(glitchWeakUntil, t + dur);
      }
    }

    function shouldStrongGlitch() {
      const t = nowMs();
      return t < glitchStrongUntil;
    }

    function shouldWeakGlitch() {
      const t = nowMs();
      return t < glitchWeakUntil || t < glitchStrongUntil;
    }

    function setGlitchAttr(on) {
      const v = on ? "1" : "0";
      fpsEl.setAttribute("data-gl", v);
      dclEl.setAttribute("data-gl", v);
      pingEl.setAttribute("data-gl", v);
      timeEl.setAttribute("data-gl", v);
    }

    setInterval(function () {
      const strong = shouldStrongGlitch();
      setGlitchAttr(strong || shouldWeakGlitch());
    }, 80);

    function pushSample(arr, v, max) {
      if (typeof v !== "number" || !isFinite(v)) return;
      arr.push(v);
      if (arr.length > max) arr.splice(0, arr.length - max);
    }

    function computeP90(arr) {
      if (!arr.length) return null;
      const sorted = arr.slice().sort(function (a, b) { return a - b; });
      return percentile(sorted, 0.9);
    }

    function updateStateFromSamples(pingMs, dclMs) {
      const p90Ping = computeP90(pingSamples);
      const p90Dcl = computeP90(dclSamples);
      const pingHigh = p90Ping != null && pingMs != null && pingMs > p90Ping;
      const dclHigh = p90Dcl != null && dclMs != null && dclMs > p90Dcl;

      if (consecutiveFails >= 1) {
        setState("alert");
        triggerGlitch(true);
        return;
      }
      if (pingHigh || dclHigh) {
        setState(dclHigh && pingHigh ? "alert" : "warn");
        triggerGlitch(dclHigh && pingHigh ? true : false);
        return;
      }
      setState("ok");
    }

    async function doPingOnce() {
      const timeout = makeAbortableTimeout(1500);
      const t0 = nowMs();
      try {
        await fetch("/", { method: "HEAD", cache: "no-store", signal: timeout.ctrl.signal });
        timeout.cancel();
        return Math.max(0, Math.round(nowMs() - t0));
      } catch {
        timeout.cancel();
        return null;
      }
    }

    async function pingLoop() {
      const t = Date.now();
      if (t < backoffUntil) return;

      const ms1 = await doPingOnce();
      let ms = ms1;
      if (ms == null) {
        // quick retry once
        await new Promise(function (r) { setTimeout(r, retryDelayMs); });
        ms = await doPingOnce();
      }

      if (ms == null) {
        consecutiveFails++;
        pingEl.textContent = "---";
        pingEl.setAttribute("data-t", pingEl.textContent);
      } else {
        consecutiveFails = 0;
        pingEl.textContent = String(ms) + "ms";
        pingEl.setAttribute("data-t", pingEl.textContent);
        pushSample(pingSamples, ms, 36); // ~3 min at 5s
      }

      const dclNow = dclSoft != null ? dclSoft : dclInitial;
      if (dclNow != null) pushSample(dclSamples, dclNow, 36);

      updateStateFromSamples(ms, dclNow);

      // backoff rule: if 3 consecutive fails, slow down for 2 min
      if (consecutiveFails >= 3) {
        backoffUntil = Date.now() + 2 * 60 * 1000;
      }
    }

    setInterval(pingLoop, intervalMs);
    pingLoop();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initHud, { once: true });
  } else {
    initHud();
  }
})();

