/**
 * Atlas de lectura — sheet, chips, Escape.
 */
(function () {
	'use strict';

	function closest(el, sel) {
		while (el && el !== document) {
			if (el.matches && el.matches(sel)) return el;
			el = el.parentElement;
		}
		return null;
	}

	function openAtlas(root) {
		var sheet = root.querySelector('[data-gano-atlas-sheet]');
		var backdrop = root.querySelector('[data-gano-atlas-backdrop]');
		var btn = root.querySelector('[data-gano-atlas-open]');
		if (!sheet || !backdrop) return;
		sheet.hidden = false;
		backdrop.hidden = false;
		requestAnimationFrame(function () {
			backdrop.classList.add('is-visible');
			sheet.classList.add('is-open');
		});
		if (btn) btn.setAttribute('aria-expanded', 'true');
		var closeBtn = sheet.querySelector('[data-gano-atlas-close]');
		if (closeBtn) closeBtn.focus();
		document.body.style.overflow = 'hidden';
	}

	function closeAtlas(root) {
		var sheet = root.querySelector('[data-gano-atlas-sheet]');
		var backdrop = root.querySelector('[data-gano-atlas-backdrop]');
		var btn = root.querySelector('[data-gano-atlas-open]');
		if (!sheet || !backdrop) return;
		backdrop.classList.remove('is-visible');
		sheet.classList.remove('is-open');
		if (btn) btn.setAttribute('aria-expanded', 'false');
		document.body.style.overflow = '';
		setTimeout(function () {
			sheet.hidden = true;
			backdrop.hidden = true;
		}, 280);
		if (btn) btn.focus();
	}

	function filterItems(root, filter) {
		root.querySelectorAll('.gano-atlas-item').forEach(function (li) {
			var g = li.getAttribute('data-atlas-group') || '';
			if (filter === 'todos' || g === filter) {
				li.classList.remove('is-hidden');
			} else {
				li.classList.add('is-hidden');
			}
		});
	}

	document.addEventListener('click', function (e) {
		var openBtn = closest(e.target, '[data-gano-atlas-open]');
		if (openBtn) {
			e.preventDefault();
			var root = openBtn.closest('[data-gano-atlas-root]');
			if (root) openAtlas(root);
			return;
		}
		var closeBtn = closest(e.target, '[data-gano-atlas-close]');
		if (closeBtn) {
			var sheet = closeBtn.closest('[data-gano-atlas-sheet]');
			var root = sheet ? sheet.closest('[data-gano-atlas-root]') : null;
			if (root) closeAtlas(root);
			return;
		}
		var bd = closest(e.target, '[data-gano-atlas-backdrop]');
		if (bd && bd.classList.contains('is-visible')) {
			var root2 = bd.closest('[data-gano-atlas-root]');
			if (root2) closeAtlas(root2);
		}
	});

	document.addEventListener('click', function (e) {
		var chip = closest(e.target, '.gano-atlas-chip');
		if (!chip) return;
		var sheet = chip.closest('[data-gano-atlas-sheet]');
		if (!sheet) return;
		var root = sheet.closest('[data-gano-atlas-root]');
		if (!root) return;
		var filter = chip.getAttribute('data-atlas-filter') || 'todos';
		sheet.querySelectorAll('.gano-atlas-chip').forEach(function (c) {
			c.classList.toggle('is-active', c === chip);
		});
		filterItems(root, filter);
	});

	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') return;
		var openSheet = document.querySelector('.gano-atlas-sheet.is-open');
		var root = openSheet ? openSheet.closest('[data-gano-atlas-root]') : null;
		if (root) closeAtlas(root);
	});
})();
