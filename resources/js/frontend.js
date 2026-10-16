/**
 * Interaksi public frontend (vanilla, tanpa dependensi vendor).
 * Menghormati prefers-reduced-motion di semua animasi.
 */
(function () {
    'use strict';

    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Navbar: shadow saat scroll + scroll-top visibility */
    var navbar = document.getElementById('navbar');
    var scrollTop = document.getElementById('scroll-top');

    function onScroll() {
        var y = window.scrollY || window.pageYOffset;
        if (navbar) navbar.classList.toggle('scrolled', y > 8);
        if (scrollTop) scrollTop.classList.toggle('visible', y > 600);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    if (scrollTop) {
        scrollTop.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
        });
    }

    /* Hamburger mobile */
    var toggle = document.getElementById('nav-toggle');
    var mobileMenu = document.getElementById('mobile-menu');
    if (toggle && mobileMenu) {
        toggle.addEventListener('click', function () {
            var expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!expanded));
            mobileMenu.classList.toggle('hidden');
        });
        mobileMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                toggle.setAttribute('aria-expanded', 'false');
                mobileMenu.classList.add('hidden');
            });
        });
    }

    /* Scrollspy navbar */
    var spyLinks = document.querySelectorAll('[data-spy]');
    var sections = Array.prototype.map.call(spyLinks, function (link) {
        return document.getElementById(link.getAttribute('href').replace(/^.*#/, ''));
    }).filter(Boolean);

    if ('IntersectionObserver' in window && sections.length && !prefersReducedMotion) {
        var spyObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var id = entry.target.id;
                spyLinks.forEach(function (link) {
                    link.classList.toggle('active', link.getAttribute('href').endsWith('#' + id));
                });
            });
        }, { rootMargin: '-40% 0px -55% 0px' });
        sections.forEach(function (section) { spyObserver.observe(section); });
    }

    /* Reveal on scroll */
    var revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && !prefersReducedMotion) {
        var revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        revealEls.forEach(function (el) { revealObserver.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('revealed'); });
    }

    /* Typed roles (pengganti typed.umd.js) */
    var typedEl = document.querySelector('[data-typed-items]');
    if (typedEl) {
        var phrases = typedEl.getAttribute('data-typed-items').split(',').map(function (s) { return s.trim(); }).filter(Boolean);
        var fallback = typedEl.textContent.trim();
        if (phrases.length && !prefersReducedMotion) {
            /* Reserve width = kata terpanjang agar mengetik/menghapus tidak
               memicu reflow ke elemen lain di hero */
            (function reserveTypedWidth() {
                var cs = getComputedStyle(typedEl);
                var probe = document.createElement('span');
                probe.style.cssText = 'position:absolute;visibility:hidden;white-space:pre;left:-9999px;';
                probe.style.font = cs.font;
                probe.style.fontWeight = cs.fontWeight;
                document.body.appendChild(probe);
                var maxW = 0;
                phrases.forEach(function (p) {
                    probe.textContent = p;
                    if (probe.offsetWidth > maxW) maxW = probe.offsetWidth;
                });
                document.body.removeChild(probe);
                if (maxW > 0) typedEl.style.minWidth = maxW + 'px';
            })();
            var phraseIndex = 0, charIndex = fallback.length, deleting = true, current = fallback || phrases[0];
            if (!fallback) { charIndex = 0; deleting = false; current = phrases[0]; }
            (function tick() {
                if (deleting) {
                    charIndex--;
                    if (charIndex <= 0) { charIndex = 0; deleting = false; phraseIndex = (phraseIndex + 1) % phrases.length; current = phrases[phraseIndex]; }
                } else {
                    charIndex++;
                    if (charIndex >= current.length) {
                        typedEl.textContent = current;
                        setTimeout(function () { deleting = true; tick(); }, 1800);
                        return;
                    }
                }
                typedEl.textContent = current.slice(0, charIndex);
                setTimeout(tick, deleting ? 32 : 64);
            })();
        } else if (!fallback && phrases.length) {
            typedEl.textContent = phrases[0];
        }
    }

    /* Marquee portfolio: muter kontinu searah, tanpa lompat balik.
       Hanya berjalan di desktop (min-width 768px); di mobile track tampil
       sebagai grid statis (klon disembunyikan via CSS). Isi track di-render
       2x (set klon) sehingga pergeseran selalu mulus. */
    var portfolioTrack = document.getElementById('portfolio-track');
    var prevBtn = document.getElementById('portfolio-prev');
    var nextBtn = document.getElementById('portfolio-next');
    if (portfolioTrack && prevBtn && nextBtn) {
        var portfolioMedia = window.matchMedia('(min-width: 768px)');
        var portfolioFilter = 'all'; // filter tab aktif; marquee hanya jalan saat 'all'
        var portfolioOriginals = portfolioTrack.querySelectorAll('[data-portfolio-original]');
        var marqueeSpeed = 45; // px per detik (sedang)
        var marqueeRaf = null;
        var marqueeLast = 0;
        var marqueePaused = false; // jeda saat hover/fokus/sentuh
        var marqueeVisible = true; // jeda saat section di luar viewport

        function cardStep() {
            var card = portfolioOriginals[0] || portfolioTrack.querySelector('[data-portfolio-item]:not(.hidden)');
            if (!card) return Math.round(portfolioTrack.clientWidth / 3);
            var gap = parseFloat(getComputedStyle(portfolioTrack).columnGap) || 0;
            var w = card.getBoundingClientRect().width;
            return w > 0 ? w + gap : Math.round(portfolioTrack.clientWidth / 3);
        }

        /* Lebar satu set card (isi track = 2 set identik).
           Diukur sekali dari set pertama, refresh saat resize/load/filter,
           bukan scrollWidth/2 tiap frame (hindari forced reflow). */
        var marqueeSetW = 0;
        var trackGap = function () {
            return parseFloat(getComputedStyle(portfolioTrack).columnGap) || 0;
        };
        function measureMarqueeSet() {
            marqueeSetW = 0;
            var gap = trackGap();
            var visible = 0;
            Array.prototype.forEach.call(portfolioOriginals, function (card) {
                var w = card.offsetWidth;
                if (w <= 0) return; // tersembunyi oleh filter
                marqueeSetW += w + gap;
                visible++;
            });
            if (visible > 0) marqueeSetW -= gap; // gap terakhir tidak dihitung
        }
        measureMarqueeSet();

        function marqueeCanRun() {
            if (!portfolioOriginals.length) return false;
            if (!portfolioMedia.matches) return false; // mobile: grid statis
            if (portfolioFilter !== 'all') return false; // tab filter aktif: tampilkan hasil saja
            return marqueeSetW > portfolioTrack.clientWidth + 8;
        }

        function syncCarouselButtons() {
            var needs = marqueeCanRun();
            prevBtn.toggleAttribute('hidden', !needs);
            nextBtn.toggleAttribute('hidden', !needs);
        }

        function marqueeTick(now) {
            marqueeRaf = null;
            if (!marqueeLast) marqueeLast = now;
            var dt = (now - marqueeLast) / 1000;
            marqueeLast = now;
            if (dt > 0.1) dt = 0.1; // tab sempat tidur: jangan melompat jauh
            if (!marqueePaused && marqueeVisible && !document.hidden && !prefersReducedMotion &&
                performance.now() >= nudgePauseUntil && marqueeCanRun()) {
                var setW = marqueeSetW;
                var next = portfolioTrack.scrollLeft + marqueeSpeed * dt;
                if (next >= setW) next -= setW; // wrap di titik identik: tidak terlihat
                portfolioTrack.scrollLeft = next;
            }
            marqueeRaf = requestAnimationFrame(marqueeTick);
        }

        function startMarquee() {
            if (marqueeRaf || prefersReducedMotion) return;
            marqueeLast = 0;
            marqueeRaf = requestAnimationFrame(marqueeTick);
        }

        /* Tombol: geser 1 card, tetap dalam putaran mulus (tanpa lompat balik).
           Jeda marquee ~600ms agar smooth-scroll nudge tidak beradu rAF. */
        var nudgePauseUntil = 0;
        function nudgePortfolio(direction) {
            if (!marqueeCanRun()) return;
            if (direction < 0 && portfolioTrack.scrollLeft <= 8) {
                // Pindah instan ke set kedua (tampilan identik), lalu geser mundur mulus
                portfolioTrack.scrollLeft = marqueeSetW;
            }
            nudgePauseUntil = performance.now() + 600;
            portfolioTrack.scrollBy({ left: direction * cardStep(), behavior: prefersReducedMotion ? 'auto' : 'smooth' });
        }

        prevBtn.addEventListener('click', function () { nudgePortfolio(-1); });
        nextBtn.addEventListener('click', function () { nudgePortfolio(1); });

        /* Pause hover dengan debounce: pointer melintasi antar card tidak
           membuat marquee jalan→stop→jalan (penyebab macet-macet). */
        var hoverPauseTimer = null;
        var carouselZone = portfolioTrack.parentElement || portfolioTrack;
        carouselZone.addEventListener('mouseenter', function () {
            marqueePaused = true;
        });
        carouselZone.addEventListener('mouseleave', function () {
            clearTimeout(hoverPauseTimer);
            hoverPauseTimer = setTimeout(function () { marqueePaused = false; }, 300);
        });
        carouselZone.addEventListener('focusin', function () { marqueePaused = true; });
        carouselZone.addEventListener('focusout', function () { marqueePaused = false; });
        carouselZone.addEventListener('touchstart', function () { marqueePaused = true; }, { passive: true });
        carouselZone.addEventListener('touchend', function () {
            clearTimeout(hoverPauseTimer);
            hoverPauseTimer = setTimeout(function () { marqueePaused = false; }, 300);
        }, { passive: true });

        /* Inisialisasi tahan banting: ukur ulang setelah CSS/gambar siap dan setiap resize */
        function refreshPortfolioCarousel() {
            measureMarqueeSet();
            syncCarouselButtons();
        }
        refreshPortfolioCarousel();
        startMarquee();
        window.addEventListener('load', refreshPortfolioCarousel);
        window.addEventListener('resize', refreshPortfolioCarousel);
        if (portfolioMedia.addEventListener) {
            portfolioMedia.addEventListener('change', refreshPortfolioCarousel);
        }
        if ('IntersectionObserver' in window) {
            var portfolioSection = document.getElementById('portfolio');
            if (portfolioSection) {
                var portfolioObserver = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        marqueeVisible = entry.isIntersecting;
                    });
                }, { threshold: 0.05 });
                portfolioObserver.observe(portfolioSection);
            }
        }

        /* Tab filter kategori (homepage): tampilkan card original yang cocok,
           sembunyikan set klon agar tidak duplikat, dan hentikan marquee. */
        var portfolioTabs = document.querySelectorAll('[data-portfolio-filter]');
        if (portfolioTabs.length) {
            Array.prototype.forEach.call(portfolioTabs, function (tab) {
                tab.addEventListener('click', function () {
                    portfolioFilter = tab.getAttribute('data-portfolio-filter') || 'all';
                    Array.prototype.forEach.call(portfolioTabs, function (t) {
                        var isActive = t === tab;
                        t.setAttribute('data-portfolio-active', isActive ? 'true' : 'false');
                        t.classList.toggle('portfolio-tab-active', isActive);
                    });
                    Array.prototype.forEach.call(portfolioOriginals, function (card) {
                        var show = portfolioFilter === 'all' || card.classList.contains('filter-' + portfolioFilter);
                        card.classList.toggle('hidden', !show);
                    });
                    portfolioTrack.classList.toggle('is-filtered', portfolioFilter !== 'all');
                    portfolioTrack.scrollLeft = 0;
                    refreshPortfolioCarousel();
                });
            });
        }
    }

    /* Slider + popup galeri halaman detail portfolio.
       Ter-guard: tidak jalan bila #project-gallery tidak ada (homepage aman). */
    var gallery = document.getElementById('project-gallery');
    if (gallery) {
        var galleryTrack = document.getElementById('project-gallery-track');
        var galleryPrev = document.getElementById('project-gallery-prev');
        var galleryNext = document.getElementById('project-gallery-next');
        var galleryCounter = document.getElementById('project-gallery-counter');
        var galleryExpand = document.getElementById('project-gallery-expand');
        var lightbox = document.getElementById('project-lightbox');
        var lightboxImage = document.getElementById('project-lightbox-image');
        var lightboxPrev = document.getElementById('project-lightbox-prev');
        var lightboxNext = document.getElementById('project-lightbox-next');
        var lightboxClose = document.getElementById('project-lightbox-close');
        var lightboxCounter = document.getElementById('project-lightbox-counter');
        var gallerySlides = galleryTrack ? galleryTrack.querySelectorAll('.project-gallery-slide img') : [];
        var galleryCount = gallerySlides.length;
        var galleryIndex = 0;

        function galleryLabel(i) {
            return (i + 1) + ' / ' + galleryCount;
        }

        function galleryGoTo(i) {
            if (!galleryCount) return;
            galleryIndex = ((i % galleryCount) + galleryCount) % galleryCount;
            if (galleryTrack) {
                galleryTrack.style.transform = 'translateX(-' + (galleryIndex * 100) + '%)';
            }
            if (galleryCounter) galleryCounter.textContent = galleryLabel(galleryIndex);
            if (lightboxImage) {
                lightboxImage.src = gallerySlides[galleryIndex].src;
                lightboxImage.alt = gallerySlides[galleryIndex].alt || 'Pratinjau gambar project';
            }
            if (lightboxCounter) lightboxCounter.textContent = galleryLabel(galleryIndex);
        }

        function galleryOpen(i) {
            if (!lightbox || !galleryCount) return;
            galleryGoTo(typeof i === 'number' ? i : galleryIndex);
            lightbox.hidden = false;
            document.body.style.overflow = 'hidden';
            if (lightboxClose) lightboxClose.focus();
        }

        function galleryClose() {
            if (!lightbox) return;
            lightbox.hidden = true;
            document.body.style.overflow = '';
        }

        if (galleryCount <= 1) {
            [galleryPrev, galleryNext, galleryExpand, galleryCounter].forEach(function (el) {
                if (el) el.style.display = 'none';
            });
            [lightboxPrev, lightboxNext].forEach(function (el) {
                if (el) el.style.display = 'none';
            });
        }
        if (galleryPrev) galleryPrev.addEventListener('click', function () { galleryGoTo(galleryIndex - 1); });
        if (galleryNext) galleryNext.addEventListener('click', function () { galleryGoTo(galleryIndex + 1); });
        if (galleryExpand) galleryExpand.addEventListener('click', function () { galleryOpen(galleryIndex); });
        Array.prototype.forEach.call(gallerySlides, function (img, i) {
            img.addEventListener('click', function () { galleryOpen(i); });
        });
        if (lightboxPrev) lightboxPrev.addEventListener('click', function (e) { e.stopPropagation(); galleryGoTo(galleryIndex - 1); });
        if (lightboxNext) lightboxNext.addEventListener('click', function (e) { e.stopPropagation(); galleryGoTo(galleryIndex + 1); });
        if (lightboxClose) lightboxClose.addEventListener('click', galleryClose);
        if (lightbox) lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) galleryClose();
        });
        document.addEventListener('keydown', function (e) {
            if (!lightbox || lightbox.hidden) return;
            if (e.key === 'Escape') galleryClose();
            if (e.key === 'ArrowLeft') galleryGoTo(galleryIndex - 1);
            if (e.key === 'ArrowRight') galleryGoTo(galleryIndex + 1);
        });
        var galleryTouchX = null;
        if (gallery) {
            gallery.addEventListener('touchstart', function (e) {
                galleryTouchX = e.touches[0].clientX;
            }, { passive: true });
            gallery.addEventListener('touchend', function (e) {
                if (galleryTouchX === null) return;
                var dx = e.changedTouches[0].clientX - galleryTouchX;
                galleryTouchX = null;
                if (Math.abs(dx) < 40) return;
                galleryGoTo(galleryIndex + (dx < 0 ? 1 : -1));
            }, { passive: true });
        }
        /* Geser popup dengan jari (mobile): pola sama seperti slider,
           dipasang pada overlay lightbox yang sedang terbuka. */
        var lightboxTouchX = null;
        if (lightbox) {
            lightbox.addEventListener('touchstart', function (e) {
                lightboxTouchX = e.touches[0].clientX;
            }, { passive: true });
            lightbox.addEventListener('touchend', function (e) {
                if (lightbox.hidden || lightboxTouchX === null) return;
                var dx = e.changedTouches[0].clientX - lightboxTouchX;
                lightboxTouchX = null;
                if (Math.abs(dx) < 40) return;
                galleryGoTo(galleryIndex + (dx < 0 ? 1 : -1));
            }, { passive: true });
        }
        galleryGoTo(0);
    }

    /* Tahun footer */
    document.querySelectorAll('[data-current-year]').forEach(function (el) {
        el.textContent = new Date().getFullYear();
    });
})();
