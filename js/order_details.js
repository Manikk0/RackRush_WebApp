
(function () {
    'use strict';

    //Accordion sections
    const sections = [
        { btn: 'btn-delivery',  body: 'body-delivery' },
        { btn: 'btn-who',       body: 'body-who'      },
        { btn: 'btn-courier',   body: 'body-courier'  },
        { btn: 'btn-payment',   body: 'body-payment'  },
    ];

    function closeAll(except) {
        sections.forEach(({ btn, body }) => {
            const b = document.getElementById(btn);
            const p = document.getElementById(body);
            if (b && p && btn !== except) {
                b.setAttribute('aria-expanded', 'false');
                p.classList.remove('is-open');
            }
        });
    }

    sections.forEach(({ btn, body }) => {
        const b = document.getElementById(btn);
        const p = document.getElementById(body);
        if (!b || !p) return;

        b.addEventListener('click', () => {
            const isOpen = b.getAttribute('aria-expanded') === 'true';

            
            closeAll(null);

            if (!isOpen) {
                b.setAttribute('aria-expanded', 'true');
                p.classList.add('is-open');
            }
        });
    });

    const voucherBtn   = document.getElementById('btn-voucher');
    const voucherWrap  = document.getElementById('voucher-input-wrap');

    if (voucherBtn && voucherWrap) {
        voucherBtn.addEventListener('click', () => {
            voucherWrap.classList.toggle('visible');
            if (voucherWrap.classList.contains('visible')) {
                document.getElementById('voucher-code')?.focus();
            }
        });
    }

    function setSubtitle(id, text) {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    }

    document.getElementById('save-delivery')?.addEventListener('click', () => {
        const city    = document.getElementById('delivery-city')?.value.trim();
        const address = document.getElementById('delivery-address')?.value.trim();
        const label   = city && address ? `${city}, ${address}` : (city || address || 'Vyberte doručenie');
        setSubtitle('subtitle-delivery', label);
        closeAll(null);
    });

    document.getElementById('save-who')?.addEventListener('click', () => {
        const name  = document.getElementById('who-name')?.value.trim();
        const email = document.getElementById('who-email')?.value.trim();
        const label = name || email || 'Vyplnte informácie';
        setSubtitle('subtitle-who', label);
        closeAll(null);
    });

    document.getElementById('save-courier')?.addEventListener('click', () => closeAll(null));
    document.getElementById('save-payment')?.addEventListener('click', () => closeAll(null));

    ['btn-order-desktop', 'btn-order-mobile'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', () => {
            alert('Objednávka bola odoslaná! Ďakujeme.');
        });
    });

})();
