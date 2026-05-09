// Order details accordion and form helper behavior.
const orderSections = [
    { btn: 'btn-delivery', body: 'body-delivery' },
    { btn: 'btn-shipping', body: 'body-shipping' },
    { btn: 'btn-who', body: 'body-who' },
    { btn: 'btn-courier', body: 'body-courier' },
    { btn: 'btn-payment', body: 'body-payment' }
];

// Live update sidebar totals when delivery_method radios change.
function postCheckoutDeliverySession(method) {
    var meta = document.querySelector('meta[name="csrf-token"]');
    var token = meta ? meta.getAttribute('content') : '';
    fetch('/checkout/delivery-session', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ delivery_method: method })
    }).catch(function () {});
}

function refreshCheckoutShippingTotals() {
    if (!window.CHECKOUT_SHIPPING_FEES) {
        return;
    }
    var radios = document.querySelectorAll('input[name="delivery_method"]');
    var selected = '';
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            selected = radios[i].value;
            break;
        }
    }
    if (!selected) {
        return;
    }
    var fee = window.CHECKOUT_SHIPPING_FEES[selected];
    if (fee === undefined) {
        fee = 0;
    }
    var rawEl = document.getElementById('od-subtotal-raw');
    var sub = rawEl ? parseFloat(String(rawEl.value).replace(',', '.')) : 0;
    if (isNaN(sub)) {
        sub = 0;
    }
    var shipEl = document.getElementById('od-shipping');
    var totalEl = document.getElementById('od-total');
    if (shipEl) {
        shipEl.textContent = fee.toFixed(2) + '€';
    }
    if (totalEl) {
        totalEl.textContent = (sub + fee).toFixed(2) + '€';
    }
}

// Close all accordion sections.
function closeAllOrderSections() {
    for (let i = 0; i < orderSections.length; i++) {
        const section = orderSections[i];
        const btnElement = document.getElementById(section.btn);
        const bodyElement = document.getElementById(section.body);
        if (btnElement && bodyElement) {
            btnElement.setAttribute('aria-expanded', 'false');
            bodyElement.classList.remove('is-open');
        }
    }
}

// Toggle clicked accordion section and close others.
for (let i = 0; i < orderSections.length; i++) {
    const section = orderSections[i];
    const btnElement = document.getElementById(section.btn);
    const bodyElement = document.getElementById(section.body);

    if (btnElement && bodyElement) {
        btnElement.addEventListener('click', function () {
            const isOpen = btnElement.getAttribute('aria-expanded') === 'true';

            closeAllOrderSections();

            if (isOpen === false) {
                btnElement.setAttribute('aria-expanded', 'true');
                bodyElement.classList.add('is-open');
            }
        });
    }
}

// Voucher input toggle behavior.
const voucherBtn = document.getElementById('btn-voucher');
const voucherWrap = document.getElementById('voucher-input-wrap');

if (voucherBtn && voucherWrap) {
    voucherBtn.addEventListener('click', function () {
        voucherWrap.classList.toggle('visible');
        if (voucherWrap.classList.contains('visible')) {
            const voucherInput = document.getElementById('voucher-code');
            if (voucherInput) {
                voucherInput.focus();
            }
        }
    });
}

// Subtitle updates based on form values.
function setOrderSubtitle(id, text) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = text;
    }
}

// Save delivery form summary into section subtitle.
const saveDeliveryBtn = document.getElementById('save-delivery');
if (saveDeliveryBtn) {
    saveDeliveryBtn.addEventListener('click', function () {
        const cityInput = document.getElementById('delivery-city');
        const addressInput = document.getElementById('delivery-address');
        
        let city = "";
        let address = "";
        if (cityInput) { city = cityInput.value.trim(); }
        if (addressInput) { address = addressInput.value.trim(); }

        let label = 'Vyplňte adresu';
        if (city && address) {
            label = city + ', ' + address;
        } else if (city) {
            label = city;
        } else if (address) {
            label = address;
        }

        setOrderSubtitle('subtitle-delivery', label);
        closeAllOrderSections();
    });
}

// Shipping: update subtitle and totals.
function shippingSubtitleFromSelection() {
    var radios = document.querySelectorAll('input[name="delivery_method"]');
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var box = radios[i].closest('.od-payment-option');
            var text = '';
            if (box) {
                var span = box.querySelector('.od-payment-option__box span');
                if (span) {
                    text = span.textContent.trim();
                }
            }
            setOrderSubtitle('subtitle-shipping', text || 'Vybraná doprava');
            return;
        }
    }
    setOrderSubtitle('subtitle-shipping', 'Vyberte spôsob');
}

var deliveryRadios = document.querySelectorAll('input[name="delivery_method"]');

var saveShippingBtn = document.getElementById('save-shipping');
if (saveShippingBtn) {
    saveShippingBtn.addEventListener('click', function () {
        shippingSubtitleFromSelection();
        refreshCheckoutShippingTotals();
        for (var si = 0; si < deliveryRadios.length; si++) {
            if (deliveryRadios[si].checked) {
                postCheckoutDeliverySession(deliveryRadios[si].value);
                break;
            }
        }
        closeAllOrderSections();
    });
}

for (var r = 0; r < deliveryRadios.length; r++) {
    deliveryRadios[r].addEventListener('change', function () {
        refreshCheckoutShippingTotals();
        postCheckoutDeliverySession(this.value);
    });
}
shippingSubtitleFromSelection();
refreshCheckoutShippingTotals();

// Save customer identity form summary into subtitle.
const saveWhoBtn = document.getElementById('save-who');
if (saveWhoBtn) {
    saveWhoBtn.addEventListener('click', function () {
        const nameInput = document.getElementById('who-name');
        const emailInput = document.getElementById('who-email');
        
        let name = "";
        let email = "";
        if (nameInput) { name = nameInput.value.trim(); }
        if (emailInput) { email = emailInput.value.trim(); }

        let label = 'Vyplnte informácie';
        if (name) {
            label = name;
        } else if (email) {
            label = email;
        }

        setOrderSubtitle('subtitle-who', label);
        closeAllOrderSections();
    });
}

// These sections only collapse after save click.
const saveCourierBtn = document.getElementById('save-courier');
if (saveCourierBtn) {
    saveCourierBtn.addEventListener('click', function () {
        closeAllOrderSections();
    });
}

const savePaymentBtn = document.getElementById('save-payment');
if (savePaymentBtn) {
    savePaymentBtn.addEventListener('click', function () {
        closeAllOrderSections();
    });
}
