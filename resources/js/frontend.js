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

    /* Scrollspy 6 anchor */
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

    /* Counter statistik */
    document.querySelectorAll('[data-count-to]').forEach(function (el) {
        var target = parseInt(el.getAttribute('data-count-to'), 10);
        if (isNaN(target)) return;
        if (prefersReducedMotion || !('IntersectionObserver' in window)) {
            el.textContent = target;
            return;
        }
        var counterObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                counterObserver.unobserve(el);
                var start = null, duration = 1200;
                function step(timestamp) {
                    if (!start) start = timestamp;
                    var progress = Math.min((timestamp - start) / duration, 1);
                    el.textContent = Math.round(target * (1 - Math.pow(1 - progress, 3)));
                    if (progress < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            });
        }, { threshold: 0.5 });
        counterObserver.observe(el);
    });

    /* Filter portfolio vanilla (pengganti Isotope) */
    var filterButtons = document.querySelectorAll('[data-portfolio-filter]');
    var portfolioItems = document.querySelectorAll('[data-portfolio-item]');
    var filterTrack = document.getElementById('portfolio-track');
    var filterRefresh = null; // diisi oleh blok marquee di bawah, dipanggil setelah filter
    if (filterButtons.length && portfolioItems.length) {
        filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var filter = btn.getAttribute('data-portfolio-filter');
                filterButtons.forEach(function (b) {
                    b.classList.toggle('active', b === btn);
                    b.setAttribute('aria-pressed', String(b === btn));
                });
                portfolioItems.forEach(function (item) {
                    var show = filter === '*' || item.classList.contains('filter-' + filter);
                    item.classList.toggle('hidden', !show);
                });
                /* Set width berubah karena card tersembunyi: ukur ulang marquee
                   dan pastikan posisi scroll masih di set pertama yang valid */
                if (filterRefresh) filterRefresh();
                if (filterTrack) {
                    requestAnimationFrame(function () {
                        var setW = filterTrack.scrollWidth / 2;
                        if (setW > 0 && filterTrack.scrollLeft > setW + 4) {
                            filterTrack.scrollLeft = filterTrack.scrollLeft - setW;
                        }
                    });
                }
            });
        });
    }

    /* Marquee portfolio: muter kontinu searah, tanpa lompat balik.
       Isi track di-render 2x (set klon) sehingga pergeseran selalu mulus. */
    var portfolioTrack = document.getElementById('portfolio-track');
    var prevBtn = document.getElementById('portfolio-prev');
    var nextBtn = document.getElementById('portfolio-next');
    if (portfolioTrack && prevBtn && nextBtn) {
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
        filterRefresh = refreshPortfolioCarousel; // dipakai hook filter di atas
        window.addEventListener('load', refreshPortfolioCarousel);
        window.addEventListener('resize', refreshPortfolioCarousel);
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
    }

    /* Prefill select service dari CTA pricing */
    document.querySelectorAll('[data-service-prefill]').forEach(function (link) {
        link.addEventListener('click', function () {
            var service = link.getAttribute('data-service-prefill');
            setTimeout(function () {
                var select = document.getElementById('contact-service');
                if (select && service) {
                    var matched = false;
                    Array.prototype.forEach.call(select.options, function (opt) {
                        if (opt.text === service || opt.value === service) {
                            select.value = opt.value || opt.text;
                            matched = true;
                        }
                    });
                    if (!matched) {
                        var other = Array.prototype.find.call(select.options, function (opt) {
                            return opt.value === 'Lainnya';
                        });
                        if (other) select.value = other.value;
                        var message = document.getElementById('contact-message');
                        if (message && !message.value) message.value = 'Halo, saya tertarik dengan: ' + service;
                    }
                }
            }, prefersReducedMotion ? 0 : 600);
        });
    });

    /* Skill progress bar */
    document.querySelectorAll('[data-skill-fill]').forEach(function (el) {
        var target = Math.max(0, Math.min(100, parseInt(el.getAttribute('data-skill-fill'), 10) || 0));
        if (prefersReducedMotion || !('IntersectionObserver' in window)) {
            el.style.width = target + '%';
            return;
        }
        var skillObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                skillObserver.unobserve(el);
                requestAnimationFrame(function () {
                    el.style.width = target + '%';
                });
            });
        }, { threshold: 0.4 });
        skillObserver.observe(el);
    });

    /* Tahun footer */
    document.querySelectorAll('[data-current-year]').forEach(function (el) {
        el.textContent = new Date().getFullYear();
    });
})();
