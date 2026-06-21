/**
 * airo-logo-orientation.js — Runtime size correction for logo orientation mismatches.
 *
 * Fetches /airo-media.json, finds <img> elements pointing at /airo-assets/images/logo/*,
 * and bumps their rendered height when the slot's `naturalOrientation` doesn't match
 * the slot's layout (e.g. user uploads a square logo into the horizontal slot — without
 * this correction the logo renders tiny because the template's `h-* w-auto` sizing was
 * picked for a wider horizontal logo).
 *
 * Mirrors the pattern in airo-video-slots.js: loaded synchronously in <head>, uses a
 * MutationObserver to catch React-rendered images, polls the manifest in dev mode.
 */
;(function () {
  var SLOT_PREFIX_IMAGES = '/airo-assets/images/'
  // Must stay aligned with LOGO_NATURAL_SLOT_PATHS in MediaManifestService.ts.
  var SLOT_TO_EXPECTED_LAYOUT = {
    'logo/horizontal': 'horizontal',
    'logo/primary': 'square',
    'logo/vertical': 'vertical',
  }
  var naturalOrientations = {}

  function extractSlotPath(url) {
    if (!url) return null
    var idx = url.indexOf(SLOT_PREFIX_IMAGES)
    if (idx === -1) return null
    return url.substring(idx + SLOT_PREFIX_IMAGES.length).split('?')[0]
  }

  var MISMATCH_SCALE = 1.4

  function correctLogo(img) {
    if (!img.src) return
    if (img.getAttribute('data-airo-logo-corrected')) return
    var slotPath = extractSlotPath(img.src)
    if (!slotPath) return
    var expectedLayout = SLOT_TO_EXPECTED_LAYOUT[slotPath]
    if (!expectedLayout) return
    var naturalOrientation = naturalOrientations[slotPath]
    if (!naturalOrientation) return

    // Uploads have no transparent layer the agent can rely on — a brightness-0/invert
    // Tailwind filter would render the opaque upload as a flat blob. Strip it
    // regardless of whether the URL carries `?variant=` (the agent doesn't always
    // append one; the underlying invariant is "slot is upload-backed").
    img.style.filter = 'none'

    if (naturalOrientation === expectedLayout) {
      img.setAttribute('data-airo-logo-corrected', naturalOrientation)
      return
    }

    function apply(retriesLeft) {
      if (img.getAttribute('data-airo-logo-corrected')) return
      var h = img.clientHeight
      if (!h) {
        if (retriesLeft > 0) {
          requestAnimationFrame(function () { apply(retriesLeft - 1) })
        }
        return
      }
      img.style.height = (h * MISMATCH_SCALE) + 'px'
      img.style.width = 'auto'
      img.setAttribute('data-airo-logo-corrected', naturalOrientation)
    }

    if (img.complete && img.clientHeight) {
      apply(0)
    } else {
      img.addEventListener('load', function () { apply(20) }, { once: true })
      requestAnimationFrame(function () { apply(20) })
    }
  }

  function patchAll() {
    document.querySelectorAll('img').forEach(correctLogo)
  }

  // Re-read the manifest, sync naturalOrientations, clear stale corrections,
  // re-patch. Used on src changes (Vite HMR pushes new versions before our 3s poll).
  function refreshFromManifest() {
    fetch('/airo-media.json').then(function (r) {
      return r.ok ? r.json() : {}
    }).then(function (m) {
      for (var k in SLOT_TO_EXPECTED_LAYOUT) {
        var n = m[k] && m[k].naturalOrientation
        if (n) naturalOrientations[k] = n
        else delete naturalOrientations[k]
      }
      document.querySelectorAll('img[data-airo-logo-corrected]').forEach(function (img) {
        img.removeAttribute('data-airo-logo-corrected')
        img.style.height = ''
        img.style.width = ''
        img.style.filter = ''
      })
      patchAll()
    }).catch(function () {})
  }

  fetch('/airo-media.json')
    .then(function (res) {
      if (!res.ok) return {}
      return res.json()
    })
    .then(function (manifest) {
      for (var key in manifest) {
        if (manifest[key] && manifest[key].naturalOrientation) {
          naturalOrientations[key] = manifest[key].naturalOrientation
        }
      }
      patchAll()

      var observer = new MutationObserver(function (mutations) {
        for (var i = 0; i < mutations.length; i++) {
          var mutation = mutations[i]
          if (mutation.type === 'childList') {
            var added = mutation.addedNodes
            for (var j = 0; j < added.length; j++) {
              var node = added[j]
              if (node instanceof HTMLImageElement) {
                correctLogo(node)
              } else if (node instanceof HTMLElement) {
                node.querySelectorAll('img').forEach(correctLogo)
              }
            }
          } else if (mutation.type === 'attributes' && mutation.attributeName === 'src') {
            var target = mutation.target
            if (target instanceof HTMLImageElement && extractSlotPath(target.src)) {
              target.removeAttribute('data-airo-logo-corrected')
              target.style.height = ''
              target.style.width = ''
              target.style.filter = ''
              // Manifest may have changed alongside the src (Vite HMR pushes versions
              // instantly; our 3s poll lags). Refresh before correcting.
              refreshFromManifest()
            }
          }
        }
      })
      observer.observe(document.documentElement, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['src'],
      })

      var isDevMode = window.__AIRO_DEV_MODE__ === true || location.hostname === 'localhost' || location.hostname === '127.0.0.1'
      if (isDevMode) {
        var pollFailures = 0
        var pollIntervalId = setInterval(function () {
          fetch('/airo-media.json').then(function (r) {
            if (!r.ok) return {}
            return r.json()
          }).then(function (m) {
            pollFailures = 0
            var changed = false
            for (var k in m) {
              var n = m[k] && m[k].naturalOrientation
              if (n && naturalOrientations[k] !== n) {
                naturalOrientations[k] = n
                changed = true
              } else if (!n && naturalOrientations[k]) {
                delete naturalOrientations[k]
                changed = true
              }
            }
            if (changed) {
              document.querySelectorAll('img[data-airo-logo-corrected]').forEach(function (img) {
                img.removeAttribute('data-airo-logo-corrected')
                img.style.height = ''
                img.style.width = ''
                img.style.filter = ''
              })
              patchAll()
            }
          }).catch(function (err) {
            pollFailures++
            if (pollFailures === 1) {
              // eslint-disable-next-line no-console
              console.warn('[airo-logo-orientation] manifest poll failed:', err.message || err)
            }
            if (pollFailures >= 5) {
              clearInterval(pollIntervalId)
            }
          })
        }, 3000)
      }
    })
    .catch(function () {})
})()
