/**
 * DPW PSI Papua Pegunungan — Main Theme JS
 * @package DPW_PSIPapeng
 */
(function () {
    'use strict';

    /* ── Preloader ───────────────────────────────────────────── */
    window.addEventListener('load', function () {
        var preloader = document.getElementById('dpw-preloader');
        if (preloader) {
            preloader.classList.add('dpw-loaded');
            setTimeout(function () {
                preloader.style.display = 'none';
            }, 600);
        }
    });

    /* ── Navbar Scroll ──────────────────────────────────────── */
    var navbar = document.getElementById('dpwNavbar');
    if (navbar) {
        var lastScroll = 0;
        window.addEventListener('scroll', function () {
            var scrollY = window.scrollY;
            if (scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            lastScroll = scrollY;
        }, { passive: true });
    }

    /* ── Back to Top ────────────────────────────────────────── */
    var backToTop = document.getElementById('dpwBackToTop');
    if (backToTop) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 400) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        }, { passive: true });
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ── Scroll Animations (Intersection Observer) ──────────── */
    var animateEls = document.querySelectorAll('.dpw-animate-on-scroll');
    if (animateEls.length > 0 && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var delay = parseInt(entry.target.getAttribute('data-delay'), 10) || 0;
                    setTimeout(function () {
                        entry.target.classList.add('dpw-visible');
                    }, delay);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
        animateEls.forEach(function (el) { observer.observe(el); });
    } else {
        animateEls.forEach(function (el) { el.classList.add('dpw-visible'); });
    }

    /* ── Hero Slider ────────────────────────────────────────── */
    var sliderTrack = document.getElementById('dpwSliderTrack');
    if (sliderTrack) {
        var slides = sliderTrack.querySelectorAll('.dpw-slide');
        var dotsContainer = document.getElementById('dpwSliderDots');
        var prevBtn = document.querySelector('.dpw-slider-prev');
        var nextBtn = document.querySelector('.dpw-slider-next');
        var currentSlide = 0;
        var slideCount = slides.length;
        var autoSlideInterval;

        // Create dots
        if (dotsContainer && slideCount > 1) {
            for (var i = 0; i < slideCount; i++) {
                var dot = document.createElement('span');
                dot.className = 'dpw-slider-dot' + (i === 0 ? ' active' : '');
                dot.setAttribute('data-index', i);
                dot.addEventListener('click', function () {
                    goToSlide(parseInt(this.getAttribute('data-index'), 10));
                });
                dotsContainer.appendChild(dot);
            }
        }

        function goToSlide(index) {
            slides.forEach(function (s) { s.classList.remove('active'); });
            var dots = dotsContainer ? dotsContainer.querySelectorAll('.dpw-slider-dot') : [];
            dots.forEach(function (d) { d.classList.remove('active'); });
            currentSlide = ((index % slideCount) + slideCount) % slideCount;
            slides[currentSlide].classList.add('active');
            if (dots[currentSlide]) dots[currentSlide].classList.add('active');
        }

        function nextSlide() { goToSlide(currentSlide + 1); }
        function prevSlide() { goToSlide(currentSlide - 1); }

        if (prevBtn) prevBtn.addEventListener('click', function () { prevSlide(); resetAuto(); });
        if (nextBtn) nextBtn.addEventListener('click', function () { nextSlide(); resetAuto(); });

        function startAuto() {
            if (slideCount > 1) {
                autoSlideInterval = setInterval(nextSlide, 5000);
            }
        }
        function resetAuto() {
            clearInterval(autoSlideInterval);
            startAuto();
        }
        startAuto();

        // Touch support
        var touchStartX = 0;
        sliderTrack.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        sliderTrack.addEventListener('touchend', function (e) {
            var diff = touchStartX - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) { nextSlide(); } else { prevSlide(); }
                resetAuto();
            }
        }, { passive: true });
    }

    /* ── Video Modal ────────────────────────────────────────── */
    var videoModal = document.getElementById('dpwVideoModal');
    if (videoModal) {
        var videoEmbed = document.getElementById('dpwVideoEmbed');
        var videoThumbs = document.querySelectorAll('.dpw-video-thumb-wrapper[data-video-url]');

        videoThumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                var url = this.getAttribute('data-video-url');
                if (!url) return;
                var match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
                if (match && match[1]) {
                    videoEmbed.innerHTML = '<iframe src="https://www.youtube.com/embed/' + match[1] + '?autoplay=1&rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen class="w-100"></iframe>';
                } else {
                    videoEmbed.innerHTML = '<video src="' + url + '" controls class="w-100"></video>';
                }
                var modal = new bootstrap.Modal(videoModal);
                modal.show();
            });
            thumb.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });

        videoModal.addEventListener('hidden.bs.modal', function () {
            videoEmbed.innerHTML = '';
        });
    }

    /* ── Gallery Lightbox ───────────────────────────────────── */
    var lightboxModal = document.getElementById('dpwLightbox');
    if (lightboxModal) {
        var lightboxImg = document.getElementById('dpwLightboxImg');
        var lightboxTitle = document.getElementById('dpwLightboxTitle');
        var galleryItems = document.querySelectorAll('.dpw-gallery-item[data-lightbox]');

        galleryItems.forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                lightboxImg.src = this.getAttribute('href');
                lightboxImg.alt = this.getAttribute('data-title') || '';
                lightboxTitle.textContent = this.getAttribute('data-title') || '';
                var modal = new bootstrap.Modal(lightboxModal);
                modal.show();
            });
        });
    }

    /* ── Contact Form AJAX ──────────────────────────────────── */
    var contactForm = document.getElementById('dpw-contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var resultDiv = document.getElementById('contact-form-result');
            var submitBtn = contactForm.querySelector('button[type="submit"]');
            var formData = new FormData(contactForm);
            formData.append('action', 'dpw_contact_send');
            formData.append('nonce', dpwPsi.nonce);

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';

            fetch(dpwPsi.ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.success) {
                    resultDiv.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>' + data.data.message + '</div>';
                    contactForm.reset();
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>' + data.data.message + '</div>';
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Kirim Pesan';
            })
            .catch(function () {
                resultDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>Terjadi kesalahan jaringan.</div>';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Kirim Pesan';
            });
        });
    }

    /* ── Mobile Menu Hamburger Animation ────────────────────── */
    var offcanvasEl = document.getElementById('dpwMobileMenu');
    if (offcanvasEl) {
        var hamburger = document.querySelector('.dpw-hamburger');
        offcanvasEl.addEventListener('show.bs.offcanvas', function () {
            if (hamburger) hamburger.classList.add('active');
        });
        offcanvasEl.addEventListener('hide.bs.offcanvas', function () {
            if (hamburger) hamburger.classList.remove('active');
        });
    }

    /* ── Smooth Scroll for Anchor Links ─────────────────────── */
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                var offset = navbar ? navbar.offsetHeight : 0;
                var top = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        });
    });

})();
