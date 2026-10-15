(function () {
    'use strict';

    // Tutup sidebar mobile dengan tombol Escape
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            var header = document.querySelector('#header');
            if (header && header.classList.contains('header-show')) {
                var toggle = document.querySelector('.header-toggle');
                if (toggle) toggle.click();
            }
        }
    });

    // Status loading pada form kontak Laravel
    document.querySelectorAll('form.php-email-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) return;
            var button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.dataset.originalText = button.textContent;
                button.textContent = 'Mengirim...';
            }
        });
    });

    // Buka toggle sidebar via keyboard (elemen <i> bukan button bawaan template)
    var headerToggle = document.querySelector('.header-toggle');
    if (headerToggle) {
        headerToggle.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                headerToggle.click();
            }
        });
    }
})();
