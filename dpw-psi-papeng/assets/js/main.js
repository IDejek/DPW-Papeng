/**
 * DPW PSI Papua Pegunungan - Main JavaScript
 * @version 1.0.0
 * @author Iqbal Tombinawa
 */
(function() {
    'use strict';

    // ============================================
    // REAL-TIME CLOCKS (WIT & WIB)
    // ============================================
    function updateClocks() {
        const now = new Date();

        // WIT (UTC+9)
        const witTime = new Date(now.getTime() + (9 * 60 * 60 * 1000) + (now.getTimezoneOffset() * 60 * 1000));
        const witEl = document.getElementById('clock-wit');
        const witDateEl = document.getElementById('date-wit');
        if (witEl) {
            witEl.textContent = witTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
        }
        if (witDateEl) {
            witDateEl.textContent = witTime.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
        }

        // WIB (UTC+7)
        const wibTime = new Date(now.getTime() + (7 * 60 * 60 * 1000) + (now.getTimezoneOffset() * 60 * 1000));
        const wibEl = document.getElementById('clock-wib');
        const wibDateEl = document.getElementById('date-wib');
        if (wibEl) {
            wibEl.textContent = wibTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
        }
        if (wibDateEl) {
            wibDateEl.textContent = wibTime.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
        }
    }

    if (document.getElementById('clock-wit')) {
        updateClocks();
        setInterval(updateClocks, 1000);
    }

    // ============================================
    // STICKY NAVBAR
    // ============================================
    const navbar = document.getElementById('psiNavbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }, { passive: true });
    }

    // ============================================
    // MOBILE MENU
    // ============================================
    const toggle = document.getElementById('navbarToggle');
    const menu = document.getElementById('navbarMenu');
    const overlay = document.getElementById('mobileOverlay');

    function closeMobileMenu() {
        if (toggle) toggle.classList.remove('active');
        if (menu) menu.classList.remove('active');
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    function openMobileMenu() {
        if (toggle) toggle.classList.add('active');
        if (menu) menu.classList.add('active');
        if (overlay) overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    if (toggle) {
        toggle.addEventListener('click', function() {
            if (menu && menu.classList.contains('active')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeMobileMenu);
    }

    // Mobile dropdown toggle
    const dropdownParents = document.querySelectorAll('.navbar-menu .menu-item-has-children > a');
    dropdownParents.forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                const parent = this.parentElement;
                parent.classList.toggle('dropdown-open');
            }
        });
    });

    // ============================================
    // HERO SLIDER
    // ============================================
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const prevBtn = document.getElementById('heroPrev');
    const nextBtn = document.getElementById('heroNext');
    let currentSlide = 0;
    let slideInterval;
    const SLIDE_DELAY = 6000;

    function goToSlide(index) {
        if (slides.length === 0) return;
        
        // Wrap around
        if (index >= slides.length) index = 0;
        if (index < 0) index = slides.length - 1;

        slides.forEach(function(s) { s.classList.remove('active'); });
        dots.forEach(function(d) { d.classList.remove('active'); });

        if (slides[index]) slides[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');
        
        currentSlide = index;
    }

    function nextSlide() {
        goToSlide(currentSlide + 1);
    }

    function prevSlide() {
        goToSlide(currentSlide - 1);
    }

    function startSlider() {
        if (slides.length > 1) {
            slideInterval = setInterval(nextSlide, SLIDE_DELAY);
        }
    }

    function resetSlider() {
        clearInterval(slideInterval);
        startSlider();
    }

    if (prevBtn) prevBtn.addEventListener('click', function() { prevSlide(); resetSlider(); });
    if (nextBtn) nextBtn.addEventListener('click', function() { nextSlide(); resetSlider(); });
    
    dots.forEach(function(dot) {
        dot.addEventListener('click', function() {
            goToSlide(parseInt(this.dataset.slide, 10));
            resetSlider();
        });
    });

    // Touch support for slider
    let touchStartX = 0;
    const heroEl = document.getElementById('heroSlider');
    if (heroEl) {
        heroEl.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        heroEl.addEventListener('touchend', function(e) {
            const touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) { nextSlide(); } else { prevSlide(); }
                resetSlider();
            }
        }, { passive: true });
    }

    startSlider();

    // ============================================
    // SCROLL ANIMATIONS
    // ============================================
    const animateElements = document.querySelectorAll('.psi-animate, .psi-animate-left, .psi-animate-right, .psi-animate-scale');
    
    if (animateElements.length > 0) {
        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -60px 0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        animateElements.forEach(function(el) {
            observer.observe(el);
        });
    }

    // ============================================
    // BACK TO TOP
    // ============================================
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 600) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        }, { passive: true });

        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ============================================
    // READING PROGRESS BAR
    // ============================================
    const progressBar = document.getElementById('readingProgress');
    if (progressBar) {
        window.addEventListener('scroll', function() {
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrolled = (window.scrollY / docHeight) * 100;
            progressBar.style.width = Math.min(scrolled, 100) + '%';
        }, { passive: true });
    }

    // ============================================
    // VIDEO MODAL
    // ============================================
    const videoModal = document.getElementById('videoModal');
    const videoIframe = document.getElementById('videoModalIframe');
    const videoClose = document.getElementById('videoModalClose');

    document.querySelectorAll('[data-video-url]').forEach(function(card) {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function() {
            const url = this.dataset.videoUrl;
            if (url && videoModal && videoIframe) {
                videoIframe.src = url + (url.indexOf('?') > -1 ? '&autoplay=1' : '?autoplay=1');
                videoModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    function closeVideoModal() {
        if (videoModal && videoIframe) {
            videoIframe.src = '';
            videoModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (videoClose) videoClose.addEventListener('click', closeVideoModal);
    if (videoModal) {
        videoModal.addEventListener('click', function(e) {
            if (e.target === videoModal) closeVideoModal();
        });
    }

    // ============================================
    // GALLERY LIGHTBOX
    // ============================================
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxClose = document.getElementById('lightboxClose');

    document.querySelectorAll('.gallery-item').forEach(function(item) {
        item.addEventListener('click', function() {
            const fullImg = this.dataset.full;
            if (fullImg && lightbox && lightboxImg) {
                lightboxImg.src = fullImg;
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    function closeLightbox() {
        if (lightbox) {
            lightboxImg.src = '';
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
    if (lightbox) {
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) closeLightbox();
        });
    }

    // ============================================
    // GALLERY FILTERS
    // ============================================
    const filterBtns = document.querySelectorAll('.gallery-filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');

            const filter = this.dataset.filter;
            galleryItems.forEach(function(item) {
                if (filter === '*' || item.classList.contains(filter.replace('.', ''))) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // ============================================
    // DPD SEARCH
    // ============================================
    const dpdSearch = document.getElementById('dpdSearch');
    const dpdCards = document.querySelectorAll('#dpdGrid .dpd-card');

    if (dpdSearch && dpdCards.length > 0) {
        dpdSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            dpdCards.forEach(function(card) {
                const searchData = card.dataset.search || '';
                if (query === '' || searchData.indexOf(query) > -1) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // ============================================
    // CONTACT FORM AJAX
    // ============================================
    const contactForm = document.getElementById('psiContactForm');
    const contactMsg = document.getElementById('contactFormMsg');
    const contactBtn = document.getElementById('contactSubmitBtn');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (contactBtn) {
                contactBtn.disabled = true;
                contactBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Mengirim...';
            }

            const formData = new FormData(contactForm);
            formData.append('action', 'dpw_psi_contact');
            formData.append('nonce', typeof dpwPsi !== 'undefined' ? dpwPsi.nonce : '');

            fetch(dpwPsi.ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (contactMsg) {
                    contactMsg.style.display = 'block';
                    if (data.success) {
                        contactMsg.innerHTML = '<div style="padding: 16px; background: #ecfdf5; color: #065f46; border-radius: 8px; border: 1px solid #a7f3d0;"><i class="bi bi-check-circle"></i> ' + data.data.message + '</div>';
                        contactForm.reset();
                    } else {
                        contactMsg.innerHTML = '<div style="padding: 16px; background: #fef2f2; color: #991b1b; border-radius: 8px; border: 1px solid #fecaca;"><i class="bi bi-exclamation-circle"></i> ' + data.data.message + '</div>';
                    }
                }
            })
            .catch(function() {
                if (contactMsg) {
                    contactMsg.style.display = 'block';
                    contactMsg.innerHTML = '<div style="padding: 16px; background: #fef2f2; color: #991b1b; border-radius: 8px; border: 1px solid #fecaca;"><i class="bi bi-exclamation-circle"></i> Terjadi kesalahan jaringan.</div>';
                }
            })
            .finally(function() {
                if (contactBtn) {
                    contactBtn.disabled = false;
                    contactBtn.innerHTML = '<i class="bi bi-send"></i> Kirim Pesan';
                }
            });
        });
    }

    // ============================================
    // KEYBOARD SHORTCUTS (ESC to close modals)
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVideoModal();
            closeLightbox();
            closeMobileMenu();
        }
    });

    // ============================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#' && targetId.length > 1) {
                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    const offset = navbar ? navbar.offsetHeight + 20 : 100;
                    const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top: top, behavior: 'smooth' });
                }
            }
        });
    });

    // Add spin animation for loading
    const style = document.createElement('style');
    style.textContent = '@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}.spin{animation:spin 1s linear infinite;display:inline-block}';
    document.head.appendChild(style);

})();
