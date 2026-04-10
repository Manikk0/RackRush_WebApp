(function () {
  var MAX_QTY = 99;
  var DEBOUNCE_MS = 550;

  var pendingSyncTimers = {};

  function getCsrfToken() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (!meta) return '';
    return meta.getAttribute('content') || '';
  }

  function clampQty(x) {
    var n = parseInt(x, 10);
    if (isNaN(n)) return 0;
    if (n < 0) return 0;
    if (n > MAX_QTY) return MAX_QTY;
    return n;
  }

  function findWrap(el) {
    if (!el) return null;
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

    if (!control || !addBtn || !compact) return;

    if (qty <= 0) {
      control.classList.add('d-none');
      compact.classList.add('d-none');
      addBtn.classList.remove('d-none');
      if (input) input.value = '0';
      return;
    }

    addBtn.classList.add('d-none');
    if (input) input.value = String(qty);

    var compactQty = compact.querySelector('.product-card__cart-compact-qty');
    if (compactQty) compactQty.textContent = String(qty);
  }

  function setExpanded(root, productId, expanded) {
    var control = root.querySelector('.js-card-cart-control[data-product-id="' + productId + '"]');
    var compact = root.querySelector('.js-card-cart-compact[data-product-id="' + productId + '"]');
    if (!control || !compact) return;

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
    if (!input) return 0;
    return clampQty(input.value);
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
        if (typeof window.loadCartPopup === 'function') window.loadCartPopup();
      })
      .catch(function () {
        if (typeof window.loadCartPopup === 'function') window.loadCartPopup();
      });
  }

  function scheduleSync(productId, qty) {
    var pid = String(productId);
    var q = clampQty(qty);

    clearTimer(pendingSyncTimers, pid);
    if (q <= 0) {
      return;
    }
    pendingSyncTimers[pid] = setTimeout(function () {
      delete pendingSyncTimers[pid];
      sendExactToServer(pid, q);
    }, DEBOUNCE_MS);
  }

  function getServerQty(cartData, productId) {
    var item = cartData ? cartData[String(productId)] : null;
    if (!item || typeof item.quantity === 'undefined') {
      return 0;
    }
    return clampQty(item.quantity);
  }

  // Sync product-card widgets with real server cart state.
  window.syncProductCardCartFromServer = function (cartData) {
    var anchors = document.querySelectorAll('.js-card-cart-anchor[data-product-id]');
    for (var i = 0; i < anchors.length; i++) {
      var anchor = anchors[i];
      var pid = anchor.getAttribute('data-product-id');
      if (!pid) continue;

      var root = findWrap(anchor);
      if (!root) continue;

      var serverQty = getServerQty(cartData, pid);
      setUiQty(root, pid, serverQty);

      if (serverQty > 0) {
        setExpanded(root, pid, false);
      } else {
        clearTimer(pendingSyncTimers, String(pid));
      }
    }
  };

  function stopBubble(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  document.addEventListener('click', function (e) {
    var t = e.target;

    var addBtn = t.closest ? t.closest('.js-card-cart-add') : null;
    if (addBtn) {
      stopBubble(e);
      var root = findWrap(addBtn);
      var productId = addBtn.getAttribute('data-product-id');
      if (!root || !productId) return;

      setUiQty(root, productId, 1);
      setExpanded(root, productId, true);
      scheduleSync(productId, 1);
      return;
    }

    var pmBtn = t.closest ? t.closest('.product-card__cart-btn') : null;
    if (pmBtn) {
      stopBubble(e);
      var control = pmBtn.closest('.js-card-cart-control');
      var root2 = findWrap(pmBtn);
      if (!control || !root2) return;

      var productIdx = control.getAttribute('data-product-id');
      if (!productIdx) return;

      var action = pmBtn.getAttribute('data-cart-action');
      var qty2 = getCurrentQty(root2, productIdx);
      if (action === 'plus') qty2 += 1;
      if (action === 'minus') qty2 -= 1;

      qty2 = clampQty(qty2);
      setUiQty(root2, productIdx, qty2);
      scheduleSync(productIdx, qty2);

      if (qty2 > 0) setExpanded(root2, productIdx, true);
      return;
    }

    var compactBtn = t.closest ? t.closest('.js-card-cart-compact') : null;
    if (compactBtn) {
      stopBubble(e);
      var root3 = findWrap(compactBtn);
      var productIdz = compactBtn.getAttribute('data-product-id');
      if (!root3 || !productIdz) return;

      setExpanded(root3, productIdz, true);
      var inp = root3.querySelector('.js-card-cart-control[data-product-id="' + productIdz + '"] .product-card__cart-qty');
      if (inp) {
        inp.focus();
        inp.select();
      }
      return;
    }

    var clickedInsideCart = t.closest ? t.closest('.js-card-cart-anchor') : null;
    if (!clickedInsideCart) {
      var expandedControls = document.querySelectorAll('.js-card-cart-control:not(.d-none)');
      for (var i = 0; i < expandedControls.length; i++) {
        var controlNode = expandedControls[i];
        var pidClose = controlNode.getAttribute('data-product-id');
        var rootClose = findWrap(controlNode);
        if (!pidClose || !rootClose) continue;

        var qtyClose = getCurrentQty(rootClose, pidClose);
        if (qtyClose > 0) {
          setExpanded(rootClose, pidClose, false);
        } else {
          setUiQty(rootClose, pidClose, 0);
        }
      }
    }
  });

  document.addEventListener('input', function (e) {
    var target = e.target;
    if (!target || !target.classList || !target.classList.contains('product-card__cart-qty')) return;

    var control = target.closest('.js-card-cart-control');
    var root = findWrap(target);
    if (!control || !root) return;

    var productIdInp = control.getAttribute('data-product-id');
    if (!productIdInp) return;

    var cleaned = target.value.replace(/[^0-9]/g, '');
    target.value = cleaned;
    var qtyInp = clampQty(parseInt(cleaned || '0', 10));

    setUiQty(root, productIdInp, qtyInp);
    scheduleSync(productIdInp, qtyInp);

    if (qtyInp > 0) setExpanded(root, productIdInp, true);
  });

  document.addEventListener('keydown', function (e) {
    var target = e.target;
    if (!target || !target.classList || !target.classList.contains('product-card__cart-qty')) return;

    if (e.key === 'Enter') {
      e.preventDefault();
      target.blur();
    }
  });
})();
