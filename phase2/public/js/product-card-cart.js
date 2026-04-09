/**
 * Košík na kartičke produktu: rýchla zmena v DOM, server ukladá session cez /cart/add/{id}
 * s JSON { quantity: N, exact: true }.
 *
 * Jednoduchá verzia: žiadne "lastSent" optimalizácie – po debounce sa vždy zavolá server,
 * aby UI a session po chybe siete neostali rozdielne bez možnosti opravy.
 */
(function () {
  var MAX_QTY = 99;
  var DEBOUNCE_MS = 550;
  var COLLAPSE_AFTER_MS = 1600;

  // kľúč = id produktu (string), hodnota = číslo timeoutu z window.setTimeout
  var pendingSyncTimers = {};
  var collapseTimers = {};
  var hoveringIds = {};

  function getCsrfToken() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (!meta) {
      return '';
    }
    return meta.getAttribute('content') || '';
  }

  function clampQty(x) {
    var n = parseInt(x, 10);
    if (isNaN(n)) {
      return 0;
    }
    if (n < 0) {
      return 0;
    }
    if (n > MAX_QTY) {
      return MAX_QTY;
    }
    return n;
  }

  function findWrap(el) {
    if (!el) {
      return null;
    }
    return el.closest('.product-card__image-wrap');
  }

  function clearTimer(obj, key) {
    if (obj[key]) {
      clearTimeout(obj[key]);
      delete obj[key];
    }
  }

  function setUiQty(root, productId, qty) {
    var addBtn = root.querySelector('.js-card-cart-add[data-product-id="' + productId + '"]');
    var control = root.querySelector('.js-card-cart-control[data-product-id="' + productId + '"]');
    var compact = root.querySelector('.js-card-cart-compact[data-product-id="' + productId + '"]');
    var input = control ? control.querySelector('.product-card__cart-qty') : null;

    if (!control || !addBtn || !compact) {
      return;
    }

    if (qty <= 0) {
      control.classList.add('d-none');
      compact.classList.add('d-none');
      addBtn.classList.remove('d-none');
      if (input) {
        input.value = '0';
      }
      return;
    }

    addBtn.classList.add('d-none');
    if (input) {
      input.value = String(qty);
    }
    var compactQty = compact.querySelector('.product-card__cart-compact-qty');
    if (compactQty) {
      compactQty.textContent = String(qty);
    }
  }

  function setExpanded(root, productId, expanded) {
    var control = root.querySelector('.js-card-cart-control[data-product-id="' + productId + '"]');
    var compact = root.querySelector('.js-card-cart-compact[data-product-id="' + productId + '"]');
    if (!control || !compact) {
      return;
    }
    if (expanded) {
      compact.classList.add('d-none');
      control.classList.remove('d-none');
    } else {
      control.classList.add('d-none');
      compact.classList.remove('d-none');
    }
  }

  function getCurrentQty(root, productId) {
    var control = root.querySelector('.js-card-cart-control[data-product-id="' + productId + '"]');
    var input = control ? control.querySelector('.product-card__cart-qty') : null;
    if (!input) {
      return 0;
    }
    return clampQty(input.value);
  }

  function scheduleCollapse(root, productId) {
    var pid = String(productId);
    var qty = getCurrentQty(root, pid);

    if (qty <= 0) {
      clearTimer(collapseTimers, pid);
      return;
    }

    clearTimer(collapseTimers, pid);
    collapseTimers[pid] = setTimeout(function () {
      delete collapseTimers[pid];
      if (hoveringIds[pid]) {
        return;
      }
      setExpanded(root, pid, false);
    }, COLLAPSE_AFTER_MS);
  }

  function sendExactToServer(productId, qty) {
    var pid = String(productId);
    var q = clampQty(qty);

    fetch('/cart/add/' + encodeURIComponent(pid), {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': getCsrfToken(),
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify({ quantity: q, exact: true }),
    })
      .then(function () {
        if (typeof window.loadCartPopup === 'function') {
          window.loadCartPopup();
        }
      })
      .catch(function () {
        if (typeof window.loadCartPopup === 'function') {
          window.loadCartPopup();
        }
      });
  }

  function scheduleSync(productId, qty) {
    var pid = String(productId);
    var q = clampQty(qty);

    clearTimer(pendingSyncTimers, pid);
    pendingSyncTimers[pid] = setTimeout(function () {
      delete pendingSyncTimers[pid];
      sendExactToServer(pid, q);
    }, DEBOUNCE_MS);
  }

  function bindHover(anchor) {
    if (anchor.getAttribute('data-cart-hover-bound') === '1') {
      return;
    }
    anchor.setAttribute('data-cart-hover-bound', '1');

    anchor.addEventListener('mouseenter', function () {
      var pid = anchor.getAttribute('data-product-id');
      if (!pid) {
        return;
      }
      hoveringIds[String(pid)] = true;
      clearTimer(collapseTimers, String(pid));
    });

    anchor.addEventListener('mouseleave', function () {
      var pid = anchor.getAttribute('data-product-id');
      if (!pid) {
        return;
      }
      var pidStr = String(pid);
      delete hoveringIds[pidStr];

      var root = findWrap(anchor);
      if (!root) {
        return;
      }
      if (getCurrentQty(root, pidStr) > 0) {
        scheduleCollapse(root, pidStr);
      }
    });
  }

  function bindAllAnchors() {
    var nodes = document.querySelectorAll('.js-card-cart-anchor');
    for (var i = 0; i < nodes.length; i++) {
      bindHover(nodes[i]);
    }
  }

  function stopBubble(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  bindAllAnchors();

  document.addEventListener('click', function (e) {
    var t = e.target;

    var addBtn = t.closest ? t.closest('.js-card-cart-add') : null;
    if (addBtn) {
      stopBubble(e);
      var root = findWrap(addBtn);
      var productId = addBtn.getAttribute('data-product-id');
      if (!root || !productId) {
        return;
      }
      setUiQty(root, productId, 1);
      setExpanded(root, productId, true);
      scheduleSync(productId, 1);
      scheduleCollapse(root, productId);
      return;
    }

    var pmBtn = t.closest ? t.closest('.product-card__cart-btn') : null;
    if (pmBtn) {
      stopBubble(e);
      var control = pmBtn.closest('.js-card-cart-control');
      var root2 = findWrap(pmBtn);
      if (!control || !root2) {
        return;
      }
      var productIdx = control.getAttribute('data-product-id');
      if (!productIdx) {
        return;
      }
      var action = pmBtn.getAttribute('data-cart-action');
      var qty2 = getCurrentQty(root2, productIdx);
      if (action === 'plus') {
        qty2 = qty2 + 1;
      }
      if (action === 'minus') {
        qty2 = qty2 - 1;
      }
      qty2 = clampQty(qty2);
      setUiQty(root2, productIdx, qty2);
      scheduleSync(productIdx, qty2);

      if (qty2 <= 0) {
        clearTimer(collapseTimers, String(productIdx));
        return;
      }
      setExpanded(root2, productIdx, true);
      scheduleCollapse(root2, productIdx);
      return;
    }

    var compactBtn = t.closest ? t.closest('.js-card-cart-compact') : null;
    if (compactBtn) {
      stopBubble(e);
      var root3 = findWrap(compactBtn);
      var productIdz = compactBtn.getAttribute('data-product-id');
      if (!root3 || !productIdz) {
        return;
      }
      setExpanded(root3, productIdz, true);
      var inp = root3.querySelector(
        '.js-card-cart-control[data-product-id="' + productIdz + '"] .product-card__cart-qty'
      );
      if (inp) {
        inp.focus();
        inp.select();
      }
      scheduleCollapse(root3, productIdz);
    }
  });

  document.addEventListener('input', function (e) {
    var target = e.target;
    if (!target || !target.classList || !target.classList.contains('product-card__cart-qty')) {
      return;
    }

    var control = target.closest('.js-card-cart-control');
    var root = findWrap(target);
    if (!control || !root) {
      return;
    }
    var productIdInp = control.getAttribute('data-product-id');
    if (!productIdInp) {
      return;
    }

    var cleaned = target.value.replace(/[^0-9]/g, '');
    target.value = cleaned;
    var qtyInp = clampQty(parseInt(cleaned || '0', 10));

    setUiQty(root, productIdInp, qtyInp);
    scheduleSync(productIdInp, qtyInp);

    if (qtyInp <= 0) {
      clearTimer(collapseTimers, String(productIdInp));
      return;
    }
    setExpanded(root, productIdInp, true);
    scheduleCollapse(root, productIdInp);
  });

  document.addEventListener('keydown', function (e) {
    var target = e.target;
    if (!target || !target.classList || !target.classList.contains('product-card__cart-qty')) {
      return;
    }
    if (e.key === 'Enter') {
      e.preventDefault();
      target.blur();
    }
  });
})();
