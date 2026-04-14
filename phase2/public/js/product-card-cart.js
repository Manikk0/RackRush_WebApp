// Product card cart controls and server sync.
(function () {
  var MAX_QTY = 99;
  var WAIT_BEFORE_SEND_MS = 550;

  // Store pending debounce timers per product id.
  var timersWaitingToSend = {};

  // Read CSRF token for cart API requests.
  function getCsrfFromPage() {
    var m = document.querySelector('meta[name="csrf-token"]');
    if (m === null) {
      return '';
    }
    var v = m.getAttribute('content');
    if (v === null) {
      return '';
    }
    return v;
  }

  // Clamp and sanitize quantity input.
  function makeQtyValid(x) {
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

  // Find product card image wrapper from clicked element.
  function getImageWrap(element) {
    if (!element) {
      return null;
    }
    return element.closest('.product-card__image-wrap');
  }

  // Find parent product card element.
  function getProductCard(element) {
    if (!element) {
      return null;
    }
    return element.closest('.product-card');
  }

  // Cancel pending debounce timer for one product.
  function clearWaitTimer(productIdString) {
    if (timersWaitingToSend[productIdString]) {
      clearTimeout(timersWaitingToSend[productIdString]);
      delete timersWaitingToSend[productIdString];
    }
  }

  // Show add/compact/stepper controls based on quantity.
  function showCorrectButtons(imageWrap, productId, qty) {
    var addCircleBtn = imageWrap.querySelector('.js-card-cart-add[data-product-id="' + productId + '"]');
    var stepperBox = imageWrap.querySelector('.js-card-cart-control[data-product-id="' + productId + '"]');
    var compactBtn = imageWrap.querySelector('.js-card-cart-compact[data-product-id="' + productId + '"]');
    var qtyInput = null;
    if (stepperBox) {
      qtyInput = stepperBox.querySelector('.product-card__cart-qty');
    }

    if (!addCircleBtn || !stepperBox || !compactBtn) {
      return;
    }

    if (qty <= 0) {
      stepperBox.classList.add('d-none');
      compactBtn.classList.add('d-none');
      addCircleBtn.classList.remove('d-none');
      if (qtyInput) {
        qtyInput.value = '0';
      }
      return;
    }

    addCircleBtn.classList.add('d-none');
    if (qtyInput) {
      qtyInput.value = String(qty);
    }

    var compactQtySpan = compactBtn.querySelector('.product-card__cart-compact-qty');
    if (compactQtySpan) {
      compactQtySpan.textContent = String(qty);
    }
  }

  // Toggle between stepper and compact controls.
  function setStepperVisible(imageWrap, productId, yes) {
    var stepperBox = imageWrap.querySelector('.js-card-cart-control[data-product-id="' + productId + '"]');
    var compactBtn = imageWrap.querySelector('.js-card-cart-compact[data-product-id="' + productId + '"]');
    if (!stepperBox || !compactBtn) {
      return;
    }
    if (yes) {
      compactBtn.classList.add('d-none');
      stepperBox.classList.remove('d-none');
    } else {
      stepperBox.classList.add('d-none');
      compactBtn.classList.remove('d-none');
    }
  }

  // Read quantity from stepper input.
  function readQtyFromInput(imageWrap, productId) {
    var stepperBox = imageWrap.querySelector('.js-card-cart-control[data-product-id="' + productId + '"]');
    if (!stepperBox) {
      return 0;
    }
    var qtyInput = stepperBox.querySelector('.product-card__cart-qty');
    if (!qtyInput) {
      return 0;
    }
    return makeQtyValid(qtyInput.value);
  }

  // Send exact quantity to backend.
  function sendQtyToServer(productId, qty) {
    var idStr = String(productId);
    var q = makeQtyValid(qty);

    fetch('/cart/add/' + encodeURIComponent(idStr), {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': getCsrfFromPage(),
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

  // Debounced sync to backend while user is editing quantity.
  function planSendToServerLater(productId, qty) {
    var idStr = String(productId);
    var q = makeQtyValid(qty);

    clearWaitTimer(idStr);

    timersWaitingToSend[idStr] = setTimeout(function () {
      delete timersWaitingToSend[idStr];
      sendQtyToServer(idStr, q);
    }, WAIT_BEFORE_SEND_MS);
  }

  // Read one product quantity from cart JSON payload.
  function readQtyFromServerCart(cartJson, productId) {
    if (!cartJson) {
      return 0;
    }
    var row = cartJson[String(productId)];
    if (!row) {
      return 0;
    }
    if (typeof row.quantity === 'undefined') {
      return 0;
    }
    return makeQtyValid(row.quantity);
  }

  // Hook called after drawer refresh to sync all product cards.
  window.syncProductCardCartFromServer = function (cartJson) {
    var allAnchors = document.querySelectorAll('.js-card-cart-anchor[data-product-id]');
    var i = 0;
    for (i = 0; i < allAnchors.length; i++) {
      var anchor = allAnchors[i];
      var productId = anchor.getAttribute('data-product-id');
      if (!productId) {
        continue;
      }

      var imageWrap = getImageWrap(anchor);
      if (!imageWrap) {
        continue;
      }

      var card = getProductCard(anchor);
      var userLeftStepperOpen = false;
      if (card && card.classList.contains('product-card--cart-open')) {
        userLeftStepperOpen = true;
      }

      var fromServer = readQtyFromServerCart(cartJson, productId);
      showCorrectButtons(imageWrap, productId, fromServer);

      if (fromServer <= 0) {
        if (card) {
          card.classList.remove('product-card--cart-open');
        }
        clearWaitTimer(String(productId));
        continue;
      }

      if (userLeftStepperOpen) {
        setStepperVisible(imageWrap, productId, true);
      } else {
        setStepperVisible(imageWrap, productId, false);
      }
    }
  };

  // Delegated click handling for card cart controls.
  document.addEventListener('click', function (ev) {
    var t = ev.target;

    // Close steppers on other cards when clicking outside them.
    var cardWeClickedIn = null;
    if (t.closest) {
      cardWeClickedIn = t.closest('.product-card');
    }

    var openCards = document.querySelectorAll('.product-card.product-card--cart-open');
    var k = 0;
    for (k = 0; k < openCards.length; k++) {
      var oneOpen = openCards[k];
      if (cardWeClickedIn === oneOpen) {
        continue;
      }
      oneOpen.classList.remove('product-card--cart-open');
      var wrapClose = oneOpen.querySelector('.product-card__image-wrap');
      var anchorClose = oneOpen.querySelector('.js-card-cart-anchor[data-product-id]');
      if (!wrapClose || !anchorClose) {
        continue;
      }
      var pidClose = anchorClose.getAttribute('data-product-id');
      if (!pidClose) {
        continue;
      }
      var qClose = readQtyFromInput(wrapClose, pidClose);
      if (qClose > 0) {
        setStepperVisible(wrapClose, pidClose, false);
      } else {
        showCorrectButtons(wrapClose, pidClose, 0);
      }
    }

    // Handle first add-to-cart click.
    var addBtn = null;
    if (t.closest) {
      addBtn = t.closest('.js-card-cart-add');
    }
    if (addBtn) {
      ev.preventDefault();
      ev.stopPropagation();

      var wrap = getImageWrap(addBtn);
      var pid = addBtn.getAttribute('data-product-id');
      if (!wrap || !pid) {
        return;
      }

      var card = getProductCard(addBtn);
      if (card) {
        card.classList.add('product-card--cart-open');
      }

      showCorrectButtons(wrap, pid, 1);
      setStepperVisible(wrap, pid, true);
      planSendToServerLater(pid, 1);
      return;
    }

    // Handle stepper plus/minus buttons.
    var pmBtn = null;
    if (t.closest) {
      pmBtn = t.closest('.product-card__cart-btn');
    }
    if (pmBtn) {
      ev.preventDefault();
      ev.stopPropagation();

      var stepper = pmBtn.closest('.js-card-cart-control');
      var wrap2 = getImageWrap(pmBtn);
      if (!stepper || !wrap2) {
        return;
      }

      var pid2 = stepper.getAttribute('data-product-id');
      if (!pid2) {
        return;
      }

      var card2 = getProductCard(pmBtn);
      if (card2) {
        card2.classList.add('product-card--cart-open');
      }

      var what = pmBtn.getAttribute('data-cart-action');
      var q2 = readQtyFromInput(wrap2, pid2);
      if (what === 'plus') {
        q2 = q2 + 1;
      }
      if (what === 'minus') {
        q2 = q2 - 1;
      }
      q2 = makeQtyValid(q2);
      showCorrectButtons(wrap2, pid2, q2);
      setStepperVisible(wrap2, pid2, true);
      planSendToServerLater(pid2, q2);
      return;
    }

    // Expand compact control into stepper.
    var compactBtn = null;
    if (t.closest) {
      compactBtn = t.closest('.js-card-cart-compact');
    }
    if (compactBtn) {
      ev.preventDefault();
      ev.stopPropagation();

      var wrap3 = getImageWrap(compactBtn);
      var pid3 = compactBtn.getAttribute('data-product-id');
      if (!wrap3 || !pid3) {
        return;
      }

      var card3 = getProductCard(compactBtn);
      if (card3) {
        card3.classList.add('product-card--cart-open');
      }

      setStepperVisible(wrap3, pid3, true);
      var inp = wrap3.querySelector('.js-card-cart-control[data-product-id="' + pid3 + '"] .product-card__cart-qty');
      if (inp) {
        inp.focus();
        inp.select();
      }
      return;
    }
  });

  // Handle direct quantity typing in stepper input.
  document.addEventListener('input', function (ev) {
    var target = ev.target;
    if (!target || !target.classList) {
      return;
    }
    if (!target.classList.contains('product-card__cart-qty')) {
      return;
    }

    var stepper = target.closest('.js-card-cart-control');
    var wrap = getImageWrap(target);
    if (!stepper || !wrap) {
      return;
    }

    var productIdInp = stepper.getAttribute('data-product-id');
    if (!productIdInp) {
      return;
    }

    var onlyDigits = target.value.replace(/[^0-9]/g, '');
    target.value = onlyDigits;
    var qInp = makeQtyValid(parseInt(onlyDigits || '0', 10));

    var cardInp = getProductCard(target);
    if (cardInp) {
      cardInp.classList.add('product-card--cart-open');
    }

    showCorrectButtons(wrap, productIdInp, qInp);
    setStepperVisible(wrap, productIdInp, true);
    planSendToServerLater(productIdInp, qInp);
  });

  // Enter key commits input and blurs field.
  document.addEventListener('keydown', function (ev) {
    var target = ev.target;
    if (!target || !target.classList) {
      return;
    }
    if (!target.classList.contains('product-card__cart-qty')) {
      return;
    }
    if (ev.key === 'Enter') {
      ev.preventDefault();
      target.blur();
    }
  });
})();
