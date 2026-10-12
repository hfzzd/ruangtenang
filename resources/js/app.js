/**
 * RuangTenang — skrip interaksi utama.
 *
 * Berisi:
 *  1. Inisialisasi Alpine.js (via Vite)
 *  2. Toggle menu mobile
 *  3. Smooth scroll untuk tautan jangkar
 *
 * Logika autentikasi (token Sanctum) didefinisikan di
 * resources/views/layouts/app.blade.php sebagai objek global `window.RT`,
 * supaya tersedia juga saat halaman dibuka tanpa build Vite.
 */

import '../css/app.css';
import Alpine from 'alpinejs';

(function () {
    'use strict';

    window.Alpine = Alpine;
    Alpine.start();

    /* ------------------------------------------------------------------
     * Menu mobile
     * ------------------------------------------------------------------ */
    function siapkanMenuMobile() {
        const tombol = document.querySelector('[data-toggle-menu]');
        const menu = document.querySelector('[data-menu-mobile]');
        if (!tombol || !menu) return;

        tombol.addEventListener('click', function () {
            const terbuka = menu.classList.toggle('hidden');
            tombol.setAttribute('aria-expanded', terbuka ? 'false' : 'true');
        });
    }

    /* ------------------------------------------------------------------
     * Smooth scroll untuk tautan jangkar (#...)
     * ------------------------------------------------------------------ */
    function siapkanSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function (tautan) {
            tautan.addEventListener('click', function (e) {
                const id = tautan.getAttribute('href');
                if (id.length < 2) return;
                const target = document.querySelector(id);
                if (!target) return;
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    }

    function init() {
        siapkanMenuMobile();
        siapkanSmoothScroll();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
