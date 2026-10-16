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

        /* Durasi & ambang — diselaraskan dengan frontend.css */
        var LB_CLOSE_MS = 300;      /* > transition terpanjang saat tutup (0.26s) */
        var LB_OUT_MS = 165;        /* < durasi fade-out gambar (0.18s) */
        var LB_LOAD_MS = 900;       /* pengaman bila onload tidak pernah datang */
        var LB_SHIFT_PX = 22;       /* offset arah masuk/keluar gambar popup */
        var DRAG_LOCK_PX = 6;       /* px sebelum arah gestur diputuskan */
        var DRAG_CLICK_PX = 6;      /* px minimum yang membatalkan klik palsu */
        var DRAG_RUBBER = 0.35;     /* tahanan rubber-band di slide ujung */
        var DRAG_VELOCITY = 0.5;    /* px/ms: flick tetap pindah walau jarak kecil */
        var DRAG_DIST_RATIO = 0.15; /* ambang jarak = 15% lebar (clamp 42-96px) */
        var CLICK_GUARD_MS = 350;   /* blokir klik pasca-drag */

        /* State animasi popup */
        var lbCloseTimer = null;    /* menutup: set hidden setelah fade selesai */
        var lbOutTimer = null;      /* tukar src setelah fade-out */
        var lbLoadTimer = null;     /* pengaman onload */
        var lbToken = 0;            /* pembatal callback basi (swipe cepat/tutup/buka) */
        var lbClosing = false;      /* sedang animasi tutup */
        var lbSwitchPx = 0;         /* geseran jari saat switch popup dipicu */

        /* State gestur slider halaman */
        var dragIndex = 0;
        var dragWidth = 1;

        var clickGuardUntil = 0;    /* timestamp; klik palsu pasca-drag diblokir */

        function galleryLabel(i) {
            return (i + 1) + ' / ' + galleryCount;
        }

        function armClickGuard() {
            clickGuardUntil = performance.now() + CLICK_GUARD_MS;
        }

        function clickBlocked() {
            return performance.now() < clickGuardUntil;
        }

        /* Mesin gestur geser bersama (slider halaman & popup):
           kunci arah horizontal, rekam velocity, balas via callback. */
        function makeSwipeGesture(el, opts) {
            var pending = false, on = false, moved = false;
            var startX = 0, startY = 0, dx = 0;
            var sampleX = 0, sampleT = 0, vel = 0;

            function start(e) {
                if (!e.touches || e.touches.length !== 1 || !opts.canStart()) return;
                pending = true;
                on = false;
                moved = false;
                dx = 0;
                vel = 0;
                startX = sampleX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                sampleT = performance.now();
            }

            function move(e) {
                if (!pending || !e.touches || e.touches.length !== 1) return;
                var x = e.touches[0].clientX;
                var d = x - startX;
                if (opts.reduced) {             /* tanpa drag-follow: rekam arah saja */
                    dx = d;
                    if (Math.abs(d) > DRAG_CLICK_PX) moved = true;
                    return;
                }
                if (!on) {
                    var dy = e.touches[0].clientY - startY;
                    if (Math.abs(d) < DRAG_LOCK_PX && Math.abs(dy) < DRAG_LOCK_PX) return;
                    if (Math.abs(dy) >= Math.abs(d)) {      /* niat vertikal: biarkan browser scroll */
                        pending = false;
                        return;
                    }
                    on = true;
                    opts.followStart();
                }
                dx = d;
                if (Math.abs(d) > DRAG_CLICK_PX) moved = true;
                var now = performance.now();
                if (now - sampleT > 12) {       /* velocity px/ms, dihaluskan */
                    vel = vel * 0.6 + ((x - sampleX) / (now - sampleT)) * 0.4;
                    sampleX = x;
                    sampleT = now;
                }
                opts.onFollow(d);
            }

            function end(cancelled) {
                if (!pending) return;
                var state = {
                    on: on,
                    moved: moved,
                    cancelled: cancelled,
                    dx: dx,
                    vel: vel,
                    idle: performance.now() - sampleT
                };
                pending = false;
                on = false;
                moved = false;
                dx = 0;
                vel = 0;
                if (state.moved) armClickGuard();
                opts.onRelease(state);
            }

            el.addEventListener('touchstart', function (e) {
                if (!e.touches || e.touches.length !== 1) { end(true); return; }
                start(e);
            }, { passive: true });
            el.addEventListener('touchmove', move, { passive: true });
            el.addEventListener('touchend', function () { end(false); }, { passive: true });
            el.addEventListener('touchcancel', function () { end(true); }, { passive: true });
        }

        /* ---------- popup: crossfade ganti gambar ---------- */
        function lbCanAnimateSwitch() {
            return !!lightbox && !!lightboxImage && !lightbox.hidden && !lbClosing &&
                !prefersReducedMotion && lightbox.classList.contains('is-open');
        }

        function lbCancelSwitch() {
            lbToken++;
            clearTimeout(lbOutTimer); lbOutTimer = null;
            clearTimeout(lbLoadTimer); lbLoadTimer = null;
            if (!lightboxImage) return;
            lightboxImage.classList.remove('is-switching');
            lightboxImage.style.transition = '';
            lightboxImage.style.removeProperty('--lb-shift');
        }

        function lbFinishSwitch(token) {
            if (token !== lbToken || !lightboxImage) return;    /* rantai basi: abaikan */
            clearTimeout(lbOutTimer); lbOutTimer = null;
            clearTimeout(lbLoadTimer); lbLoadTimer = null;
            lightboxImage.style.setProperty('--lb-shift', '0px');
            lightboxImage.classList.remove('is-switching');     /* fade-in + geser masuk */
        }

        function lbStartSwitch(src, dir, dragPx) {
            lbToken++;
            var token = lbToken;
            clearTimeout(lbOutTimer); clearTimeout(lbLoadTimer);
            /* Fase 1: gambar lama fade-out sambil keluar melanjutkan arah geseran. */
            var out = Math.max(LB_SHIFT_PX, Math.abs(dragPx || 0) + LB_SHIFT_PX);
            lightboxImage.style.setProperty('--lb-shift', (dir >= 0 ? -out : out) + 'px');
            lightboxImage.classList.add('is-switching');
            lbOutTimer = setTimeout(function () {
                if (token !== lbToken) return;
                /* Fase 2: src ditukar saat gambar tak terlihat. Lompat instan ke sisi masuk
                   (transisi transform dimatikan sesaat) supaya arah masuk selalu benar
                   walau gambar sudah ter-cache. */
                lightboxImage.style.transition = 'opacity 0.18s ease';
                lightboxImage.style.setProperty('--lb-shift', (dir >= 0 ? LB_SHIFT_PX : -LB_SHIFT_PX) + 'px');
                void lightboxImage.offsetWidth;
                lightboxImage.style.transition = '';
                lightboxImage.onload = function () { lbFinishSwitch(token); };
                lightboxImage.onerror = function () { lbFinishSwitch(token); };
                lightboxImage.src = src;
                /* cache memory: onload bisa tidak terpanggil walau gambar siap */
                if (lightboxImage.complete && lightboxImage.naturalWidth > 0) lbFinishSwitch(token);
                lbLoadTimer = setTimeout(function () { lbFinishSwitch(token); }, LB_LOAD_MS);
            }, LB_OUT_MS);
        }

        function setLightboxImage(index, dir) {
            if (!lightboxImage || !gallerySlides[index]) return;
            var slide = gallerySlides[index];
            var dragPx = lbSwitchPx;
            lbSwitchPx = 0;
            lightboxImage.alt = slide.alt || 'Pratinjau gambar project';
            if (lightboxImage.getAttribute('src') === slide.src) {
                /* gambar sama; kalau baru dilepas dari drag, kembalikan posisinya */
                if (dragPx) lightboxImage.style.setProperty('--lb-shift', '0px');
                return;
            }
            if (lbCanAnimateSwitch()) {
                lbStartSwitch(slide.src, dir, dragPx);
                return;
            }
            lbCancelSwitch();                               /* popup tertutup/instan: set langsung */
            lightboxImage.src = slide.src;
        }

        function galleryGoTo(i) {
            if (!galleryCount) return;
            var prev = galleryIndex;
            galleryIndex = ((i % galleryCount) + galleryCount) % galleryCount;
            var dir = 1;                                    /* +1 maju (masuk dari kanan), -1 mundur */
            if ((prev + 1) % galleryCount === galleryIndex) dir = 1;
            else if ((prev - 1 + galleryCount) % galleryCount === galleryIndex) dir = -1;
            else if (galleryIndex < prev) dir = -1;
            if (galleryTrack) {
                galleryTrack.style.transform = 'translateX(-' + (galleryIndex * 100) + '%)';
            }
            if (galleryCounter) galleryCounter.textContent = galleryLabel(galleryIndex);
            if (lightboxCounter) lightboxCounter.textContent = galleryLabel(galleryIndex);
            setLightboxImage(galleryIndex, dir);
        }

        /* Gambar popup belum siap (jaringan lambat): tahan opacity 0 sampai load. */
        function lbHoldUntilLoaded() {
            if (!lightboxImage || prefersReducedMotion) return;
            if (lightboxImage.complete && lightboxImage.naturalWidth > 0) return;
            lbToken++;
            var token = lbToken;
            lightboxImage.classList.add('is-switching');
            lightboxImage.onload = function () { lbFinishSwitch(token); };
            lightboxImage.onerror = function () { lbFinishSwitch(token); };
            clearTimeout(lbLoadTimer);
            lbLoadTimer = setTimeout(function () { lbFinishSwitch(token); }, LB_LOAD_MS);
        }

        /* ---------- popup: buka/tutup beranimasi ---------- */
        function galleryOpen(i) {
            if (!lightbox || !galleryCount) return;
            clearTimeout(lbCloseTimer);
            lbCloseTimer = null;
            lbClosing = false;
            lbCancelSwitch();
            /* penting: src di-set saat popup masih hidden → set langsung tanpa animasi */
            galleryGoTo(typeof i === 'number' ? i : galleryIndex);
            lightbox.hidden = false;
            document.body.style.overflow = 'hidden';
            if (prefersReducedMotion) {
                lightbox.classList.add('is-open');          /* CSS transisi = none */
            } else {
                void lightbox.offsetWidth;                  /* commit frame tertutup agar fade jalan */
                lightbox.classList.add('is-open');
            }
            if (lightboxClose) lightboxClose.focus();
            lbHoldUntilLoaded();
        }

        function lbFinalizeClose() {
            clearTimeout(lbCloseTimer);
            lbCloseTimer = null;
            lbClosing = false;
            if (lightbox) lightbox.hidden = true;
            if (lightboxImage) lightboxImage.style.removeProperty('--lb-shift');
        }

        function galleryClose() {
            if (!lightbox || lightbox.hidden || lbClosing) return;
            lbClosing = true;
            lbToken++;                                      /* hentikan rantai ganti gambar */
            clearTimeout(lbOutTimer); lbOutTimer = null;
            clearTimeout(lbLoadTimer); lbLoadTimer = null;
            if (lightboxImage) {
                lightboxImage.classList.remove('is-switching');
                lightboxImage.style.transition = '';
                lightboxImage.style.removeProperty('--lb-shift');
            }
            lightbox.classList.remove('is-open');           /* memulai fade-out */
            document.body.style.overflow = '';
            if (prefersReducedMotion) {
                lbFinalizeClose();
                return;
            }
            clearTimeout(lbCloseTimer);
            lbCloseTimer = setTimeout(lbFinalizeClose, LB_CLOSE_MS);
        }

        /* ---------- slider halaman: drag mengikuti jari ---------- */
        makeSwipeGesture(gallery, {
            reduced: prefersReducedMotion,
            canStart: function () {
                if (galleryCount <= 1 || !galleryTrack) return false;
                dragIndex = galleryIndex;
                dragWidth = gallery.getBoundingClientRect().width || 1;   /* 1 baca layout per gestur */
                return true;
            },
            followStart: function () {
                galleryTrack.classList.add('is-dragging');
            },
            onFollow: function (d) {
                var eff = d;
                if ((dragIndex === 0 && d > 0) || (dragIndex === galleryCount - 1 && d < 0)) {
                    eff = d * DRAG_RUBBER;                  /* rubber-band di slide ujung */
                }
                galleryTrack.style.transform =
                    'translateX(calc(' + (-dragIndex * 100) + '% + ' + eff.toFixed(1) + 'px))';
            },
            onRelease: function (state) {
                if (!state.on && !state.moved) return;      /* gestur batal tanpa pergeseran */
                if (state.on) {
                    galleryTrack.classList.remove('is-dragging');
                    void galleryTrack.offsetWidth;          /* commit posisi drag sebelum transition hidup */
                }
                var target = galleryIndex;
                if (!state.cancelled) {
                    if (prefersReducedMotion) {
                        if (Math.abs(state.dx) >= 40) target = galleryIndex + (state.dx < 0 ? 1 : -1);
                    } else {
                        var vel = state.idle > 120 ? 0 : state.vel;    /* jari berhenti: bukan flick */
                        var threshold = Math.max(42, Math.min(96, dragWidth * DRAG_DIST_RATIO));
                        if (Math.abs(state.dx) >= threshold) target = galleryIndex + (state.dx < 0 ? 1 : -1);
                        else if (Math.abs(vel) >= DRAG_VELOCITY) target = galleryIndex + (vel < 0 ? 1 : -1);
                    }
                    if (target < 0) target = 0;
                    if (target > galleryCount - 1) target = galleryCount - 1;   /* drag tidak wrap */
                }
                galleryGoTo(target);
            }
        });

        /* ---------- popup: drag mengikuti jari, lepas → crossfade ---------- */
        if (lightbox) {
            makeSwipeGesture(lightbox, {
                reduced: prefersReducedMotion,
                canStart: function () {
                    return galleryCount > 1 && !!lightboxImage && !lbClosing &&
                        lightbox.classList.contains('is-open') &&
                        !lightboxImage.classList.contains('is-switching');
                },
                followStart: function () {
                    lightboxImage.classList.add('is-dragging');
                },
                onFollow: function (d) {
                    lightboxImage.style.setProperty('--lb-shift', d.toFixed(1) + 'px');
                },
                onRelease: function (state) {
                    if (!state.on && !state.moved) return;
                    if (state.on) {
                        lightboxImage.classList.remove('is-dragging');
                        void lightboxImage.offsetWidth;     /* commit posisi drag sebelum transition hidup */
                    }
                    var target = galleryIndex;
                    if (!state.cancelled) {
                        if (prefersReducedMotion) {
                            if (Math.abs(state.dx) >= 40) target = galleryIndex + (state.dx < 0 ? 1 : -1);
                        } else {
                            var vel = state.idle > 120 ? 0 : state.vel;
                            var threshold = Math.max(42, Math.min(96, (window.innerWidth || 1) * DRAG_DIST_RATIO));
                            if (Math.abs(state.dx) >= threshold) target = galleryIndex + (state.dx < 0 ? 1 : -1);
                            else if (Math.abs(vel) >= DRAG_VELOCITY) target = galleryIndex + (vel < 0 ? 1 : -1);
                        }
                    }
                    target = ((target % galleryCount) + galleryCount) % galleryCount;   /* popup: wrap */
                    if (target !== galleryIndex) {
                        lbSwitchPx = state.dx;              /* arah keluar melanjutkan geseran */
                        galleryGoTo(target);
                    } else if (state.on) {
                        lightboxImage.style.setProperty('--lb-shift', '0px');   /* spring back */
                    }
                }
            });
        }

        /* ---------- kontrol ---------- */
        if (galleryCount <= 1) {
            [galleryPrev, galleryNext, galleryExpand, galleryCounter].forEach(function (el) {
                if (el) el.style.display = 'none';
            });
            [lightboxPrev, lightboxNext].forEach(function (el) {
                if (el) el.style.display = 'none';
            });
        }
        if (galleryPrev) galleryPrev.addEventListener('click', function () {
            if (clickBlocked()) return;
            galleryGoTo(galleryIndex - 1);
        });
        if (galleryNext) galleryNext.addEventListener('click', function () {
            if (clickBlocked()) return;
            galleryGoTo(galleryIndex + 1);
        });
        if (galleryExpand) galleryExpand.addEventListener('click', function () {
            if (clickBlocked()) return;                     /* klik palsu pasca-drag */
            galleryOpen(galleryIndex);
        });
        Array.prototype.forEach.call(gallerySlides, function (img, i) {
            img.addEventListener('click', function () {
                if (clickBlocked()) return;                 /* klik palsu pasca-drag */
                galleryOpen(i);
            });
        });
        if (lightboxPrev) lightboxPrev.addEventListener('click', function (e) {
            e.stopPropagation();
            if (clickBlocked()) return;
            galleryGoTo(galleryIndex - 1);
        });
        if (lightboxNext) lightboxNext.addEventListener('click', function (e) {
            e.stopPropagation();
            if (clickBlocked()) return;
            galleryGoTo(galleryIndex + 1);
        });
        if (lightboxClose) lightboxClose.addEventListener('click', galleryClose);
        if (lightbox) lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox && !clickBlocked()) galleryClose();
        });
        document.addEventListener('keydown', function (e) {
            if (!lightbox || lightbox.hidden || lbClosing) return;
            if (e.key === 'Escape') galleryClose();
            if (e.key === 'ArrowLeft') galleryGoTo(galleryIndex - 1);
            if (e.key === 'ArrowRight') galleryGoTo(galleryIndex + 1);
        });
        galleryGoTo(0);
    }

    /* Tahun footer */
    document.querySelectorAll('[data-current-year]').forEach(function (el) {
        el.textContent = new Date().getFullYear();
    });
})();
