// Order details accordion and form helper behavior.
const orderSections = [
    { btn: 'btn-delivery', body: 'body-delivery' },
    { btn: 'btn-who', body: 'body-who' },
    { btn: 'btn-courier', body: 'body-courier' },
    { btn: 'btn-payment', body: 'body-payment' }
];

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
