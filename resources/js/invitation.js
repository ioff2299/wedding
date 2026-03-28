import anime from 'animejs/lib/anime.es.js';
import { initLocationSlider } from './location-slider.js';

// ── Scroll-reveal with anime.js ──
function setupReveal() {
    const items = document.querySelectorAll('.reveal');

    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el    = entry.target;
                const delay = parseInt(el.dataset.delay || 0, 10);

                setTimeout(() => {
                    el.classList.add('is-visible');

                    // Animate timeline dots with anime.js
                    const dot = el.querySelector?.('.timeline-dot');
                    if (dot) {
                        dot.classList.add('is-visible');
                        anime({
                            targets: dot,
                            scale: [0, 1],
                            duration: 600,
                            easing: 'easeOutElastic(1, .6)',
                        });
                    }
                }, delay);

                io.unobserve(el);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    items.forEach(el => io.observe(el));
}

// ── Section ornament frames — fade in with draw effect when section enters viewport ──
function setupOrnamentFrames() {
    const sections = document.querySelectorAll('section');

    sections.forEach(section => {
        const frames = section.querySelectorAll('.section-ornament-frame');
        if (!frames.length) return;

        const io = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                // Fade in frames
                frames.forEach(f => f.classList.add('is-visible'));

                // Animate SVG paths drawing in
                const paths = section.querySelectorAll('.section-ornament-frame path, .section-ornament-frame line');
                paths.forEach(path => {
                    const length = path.getTotalLength?.();
                    if (length) {
                        path.style.strokeDasharray = length;
                        path.style.strokeDashoffset = length;
                    }
                });

                anime({
                    targets: paths,
                    strokeDashoffset: [anime.setDashoffset, 0],
                    duration: 1200,
                    delay: anime.stagger(100),
                    easing: 'easeInOutSine',
                });

                // Animate SVG circles popping in
                const circles = section.querySelectorAll('.section-ornament-frame circle');
                anime({
                    targets: circles,
                    scale: [0, 1],
                    opacity: [0, 1],
                    duration: 500,
                    delay: anime.stagger(80, { start: 600 }),
                    easing: 'easeOutBack',
                });

                io.disconnect();
            }
        }, { threshold: 0.15 });

        io.observe(section);
    });
}

// ── Scatter elements with anime.js stagger ──
function showScatterItems() {
    const items = document.querySelectorAll('.scatter-item');
    anime({
        targets: items,
        opacity: [0, 1],
        translateY: [8, 0],
        rotate: (_el, i) => (i % 2 === 0 ? ['-5deg', '0deg'] : ['5deg', '0deg']),
        delay: anime.stagger(120, { start: 500 }),
        duration: 800,
        easing: 'easeOutQuad',
        complete: () => {
            items.forEach(el => el.classList.add('visible'));
        },
    });
}

// ── Hero entrance with anime.js ──
function heroEntrance() {
    const heroEls = document.querySelectorAll('.section-hero .reveal');
    if (!heroEls.length) return;

    // Staggered reveal
    anime({
        targets: heroEls,
        opacity: [0, 1],
        translateY: [30, 0],
        delay: anime.stagger(130, { start: 200 }),
        duration: 900,
        easing: 'easeOutCubic',
        begin: () => {
            heroEls.forEach(el => el.style.transition = 'none');
        },
        complete: () => {
            heroEls.forEach(el => {
                el.classList.add('is-visible');
                el.style.transition = '';
            });
        },
    });

    // Hero photo — simple fade in without bounce
    const heroPhoto = document.querySelector('.hero-photo');
    if (heroPhoto) {
        anime({
            targets: heroPhoto,
            opacity: [0, 1],
            duration: 1000,
            delay: 500,
            easing: 'easeOutQuad',
        });
    }

    // Animate hero tag label decorative lines
    const tagLabel = document.querySelector('.hero-tag-label');
    if (tagLabel) {
        anime({
            targets: tagLabel,
            letterSpacing: ['0.1em', '0.32em'],
            opacity: [0, 1],
            duration: 1200,
            delay: 100,
            easing: 'easeOutQuart',
        });
    }

    // Animate divider lines expanding
    const dividerLines = document.querySelectorAll('.hero-divider .divider-line');
    anime({
        targets: dividerLines,
        scaleX: [0, 1],
        duration: 800,
        delay: anime.stagger(200, { start: 800 }),
        easing: 'easeOutQuad',
    });

    // Animate divider heart icon
    const dividerIcon = document.querySelector('.hero-divider .divider-icon');
    if (dividerIcon) {
        anime({
            targets: dividerIcon,
            scale: [0, 1],
            rotate: ['0deg', '360deg'],
            duration: 800,
            delay: 900,
            easing: 'easeOutBack',
        });
    }
}

// ── Location cards entrance ──
function setupLocationAnimations() {
    const locationSection = document.querySelector('.section-location');
    if (!locationSection) return;

    const io = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            // Animate location cards sliding in
            const cards = locationSection.querySelectorAll('.location-card');
            anime({
                targets: cards,
                translateY: [40, 0],
                opacity: [0, 1],
                scale: [0.95, 1],
                delay: anime.stagger(200, { start: 200 }),
                duration: 800,
                easing: 'easeOutCubic',
                begin: () => {
                    cards.forEach(c => {
                        c.style.opacity = '0';
                        c.style.transition = 'none';
                    });
                },
                complete: () => {
                    cards.forEach(c => c.style.transition = '');
                },
            });

            // Animate corner ornaments floating in
            const corners = locationSection.querySelectorAll('.location-corner');
            anime({
                targets: corners,
                opacity: [0, 1],
                scale: [0.5, 1],
                delay: anime.stagger(150, { start: 400 }),
                duration: 600,
                easing: 'easeOutBack',
            });

            // Animate top ornament SVG drawing
            const topPaths = locationSection.querySelectorAll('.location-top-ornament path');
            anime({
                targets: topPaths,
                strokeDashoffset: [anime.setDashoffset, 0],
                duration: 1500,
                delay: anime.stagger(200),
                easing: 'easeInOutSine',
            });

            io.disconnect();
        }
    }, { threshold: 0.15 });

    io.observe(locationSection);
}

// ── Timeline items entrance ──
function setupTimelineAnimations() {
    const timeline = document.querySelector('.timeline');
    if (!timeline) return;

    // Animate the central line growing
    const io = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            anime({
                targets: timeline,
                '--timeline-progress': [0, 1],
                duration: 1500,
                easing: 'easeInOutQuad',
            });

            // Stagger timeline items from left/right
            const items = timeline.querySelectorAll('.timeline-item');
            items.forEach((item, i) => {
                const isOdd = i % 2 === 0;
                anime({
                    targets: item,
                    translateX: [isOdd ? -20 : 20, 0],
                    opacity: [0, 1],
                    duration: 700,
                    delay: 300 + i * 150,
                    easing: 'easeOutCubic',
                    begin: () => {
                        item.style.opacity = '0';
                    },
                });
            });

            io.disconnect();
        }
    }, { threshold: 0.1 });

    io.observe(timeline);
}

// ── Color swatch pop-in with anime.js ──
function setupSwatchAnimations() {
    const swatchWrap = document.querySelector('.color-palette');
    if (!swatchWrap) return;

    const io = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            const swatches = swatchWrap.querySelectorAll('.color-swatch-circle');
            anime({
                targets: swatches,
                scale: [0, 1],
                opacity: [0, 1],
                rotate: ['15deg', '0deg'],
                delay: anime.stagger(80, { from: 'center' }),
                duration: 600,
                easing: 'easeOutBack',
            });
            io.disconnect();
        }
    }, { threshold: 0.3 });

    io.observe(swatchWrap);
}

// ── Color swatch hover — anime.js driven ──
function setupSwatchHoverAnimations() {
    const wraps = document.querySelectorAll('.color-swatch-wrap');
    if (!wraps.length) return;

    wraps.forEach(wrap => {
        const circle = wrap.querySelector('.color-swatch-circle');
        if (!circle) return;

        let hoverAnim = null;
        let leaveAnim = null;
        let pulseAnim = null;

        wrap.addEventListener('mouseenter', () => {
            if (leaveAnim) leaveAnim.pause();

            // Main lift + spin
            hoverAnim = anime({
                targets: circle,
                translateY: -8,
                scale: 1.15,
                rotateY: '15deg',
                rotateX: '-5deg',
                duration: 400,
                easing: 'easeOutBack',
            });

            // Ring pulse outward
            pulseAnim = anime({
                targets: wrap,
                // Animate the pseudo-element via a CSS variable
                duration: 700,
                easing: 'easeOutQuad',
                begin: () => {
                    // Create a temporary ring element
                    const ring = document.createElement('span');
                    ring.className = 'swatch-pulse-ring';
                    ring.style.cssText = `
                        position:absolute; top:50%; left:50%;
                        width:58px; height:58px; border-radius:50%;
                        border:2px solid rgba(201,169,110,0.5);
                        transform:translate(-50%,-50%) scale(1);
                        pointer-events:none; z-index:10;
                    `;
                    wrap.appendChild(ring);
                    anime({
                        targets: ring,
                        scale: [1, 1.6],
                        opacity: [0.7, 0],
                        duration: 700,
                        easing: 'easeOutQuad',
                        complete: () => ring.remove(),
                    });
                },
            });
        });

        wrap.addEventListener('mouseleave', () => {
            if (hoverAnim) hoverAnim.pause();

            leaveAnim = anime({
                targets: circle,
                translateY: 0,
                scale: 1,
                rotateY: '0deg',
                rotateX: '0deg',
                duration: 500,
                easing: 'easeOutElastic(1, .6)',
            });
        });
    });
}

// ── Details section: envelope icon & ribbon animations ──
function setupDetailsAnimations() {
    const detailsSection = document.querySelector('.section-details');
    if (!detailsSection) return;

    const io = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            // Envelope icon floating down
            const envIcon = detailsSection.querySelector('.details-envelope-image');
            if (envIcon) {
                anime({
                    targets: envIcon,
                    translateY: [-20, 0],
                    opacity: [0, 0.88],
                    rotate: ['-10deg', '0deg'],
                    duration: 900,
                    delay: 300,
                    easing: 'easeOutBack',
                });
            }

            // Ribbon silk animation
            const ribbon = detailsSection.querySelector('.details-ribbon-image');
            if (ribbon) {
                anime({
                    targets: ribbon,
                    scaleX: [0, 1],
                    opacity: [0, 0.8],
                    duration: 1000,
                    delay: 800,
                    easing: 'easeOutElastic(1, .8)',
                });
            }

            // Divider SVG drawing
            const dividerPaths = detailsSection.querySelectorAll('.details-divider line, .details-divider ellipse, .details-divider path');
            anime({
                targets: dividerPaths,
                strokeDashoffset: [anime.setDashoffset, 0],
                opacity: [0, 1],
                duration: 1200,
                delay: anime.stagger(80, { start: 500 }),
                easing: 'easeInOutSine',
            });

            io.disconnect();
        }
    }, { threshold: 0.2 });

    io.observe(detailsSection);
}

// ── Angel image — cinematic entrance + permanent floating ──
function setupAngelAnimation() {
    const angelWrap = document.querySelector('.footer-angel');
    if (!angelWrap) return;

    const angelImg = angelWrap.querySelector('.footer-angel-image');
    if (!angelImg) return;

    const io = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            // Entrance: fade in, then start floating
            anime({
                targets: angelImg,
                opacity: [0, 0.92],
                translateY: [20, 0],
                duration: 1000,
                easing: 'easeOutCubic',
                complete: () => {
                    // Continuous floating — starts only after entrance finishes
                    anime({
                        targets: angelImg,
                        translateY: [-5, 5],
                        rotate: ['-1.5deg', '1.5deg'],
                        duration: 3000,
                        direction: 'alternate',
                        loop: true,
                        easing: 'easeInOutSine',
                    });
                },
            });

            io.disconnect();
        }
    }, { threshold: 0.3 });

    io.observe(angelWrap);
}

// ── Footer names & closing text entrance ──
function setupFooterAnimations() {
    const footer = document.querySelector('.section-footer');
    if (!footer) return;

    const io = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            // Footer closing text
            const closing = footer.querySelector('.footer-closing');
            if (closing) {
                anime({
                    targets: closing,
                    opacity: [0, 1],
                    translateY: [20, 0],
                    duration: 900,
                    delay: 200,
                    easing: 'easeOutCubic',
                });
            }

            // Footer subtext
            const subtext = footer.querySelector('.footer-subtext');
            if (subtext) {
                anime({
                    targets: subtext,
                    opacity: [0, 1],
                    translateY: [15, 0],
                    duration: 800,
                    delay: 500,
                    easing: 'easeOutCubic',
                });
            }

            // Heart divider
            const heartDiv = footer.querySelector('.footer-heart-divider');
            if (heartDiv) {
                anime({
                    targets: heartDiv,
                    opacity: [0, 1],
                    scaleX: [0, 1],
                    duration: 800,
                    delay: 700,
                    easing: 'easeOutBack',
                });
            }

            // Names sliding from sides
            const names = footer.querySelectorAll('.footer-name');
            if (names.length >= 2) {
                anime({
                    targets: names[0],
                    opacity: [0, 1],
                    translateX: [-30, 0],
                    duration: 800,
                    delay: 900,
                    easing: 'easeOutCubic',
                });
                anime({
                    targets: names[1],
                    opacity: [0, 1],
                    translateX: [30, 0],
                    duration: 800,
                    delay: 900,
                    easing: 'easeOutCubic',
                });
            }

            // Ampersand spinning in
            const andEl = footer.querySelector('.footer-and');
            if (andEl) {
                anime({
                    targets: andEl,
                    opacity: [0, 1],
                    scale: [0, 1],
                    rotate: ['180deg', '0deg'],
                    duration: 800,
                    delay: 1000,
                    easing: 'easeOutBack',
                });
            }

            // Footer ornament corners
            const ornaments = footer.querySelectorAll('.footer-ornament');
            anime({
                targets: ornaments,
                opacity: [0, 0.8],
                scale: [0.5, 1],
                delay: anime.stagger(100, { start: 300 }),
                duration: 600,
                easing: 'easeOutQuad',
            });

            // Footer edges (gold lines) extending
            const edges = footer.querySelectorAll('.footer-edge');
            anime({
                targets: edges,
                scaleX: [0, 1],
                duration: 1200,
                delay: anime.stagger(200, { start: 200 }),
                easing: 'easeInOutQuad',
            });

            io.disconnect();
        }
    }, { threshold: 0.15 });

    io.observe(footer);
}

// ── Form fields entrance animation ──
function setupFormAnimations() {
    const formSection = document.querySelector('.section-form');
    if (!formSection) return;

    const io = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            const groups = formSection.querySelectorAll('.form-group');
            anime({
                targets: groups,
                translateY: [25, 0],
                opacity: [0, 1],
                delay: anime.stagger(100, { start: 300 }),
                duration: 700,
                easing: 'easeOutCubic',
                begin: () => {
                    groups.forEach(g => {
                        g.style.opacity = '0';
                        g.style.transition = 'none';
                    });
                },
                complete: () => {
                    groups.forEach(g => {
                        g.classList.add('is-visible');
                        g.style.transition = '';
                    });
                },
            });

            io.disconnect();
        }
    }, { threshold: 0.15 });

    io.observe(formSection);
}

// ── Floral divider SVG draw animation ──
function setupFloralDividerAnimations() {
    const dividers = document.querySelectorAll('.floral-divider-wrap');

    dividers.forEach(divider => {
        const io = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                const paths = divider.querySelectorAll('svg path, svg line, svg circle');
                anime({
                    targets: paths,
                    strokeDashoffset: [anime.setDashoffset, 0],
                    opacity: [0, 1],
                    duration: 1000,
                    delay: anime.stagger(40),
                    easing: 'easeInOutSine',
                });
                io.disconnect();
            }
        }, { threshold: 0.5 });

        io.observe(divider);
    });
}

// ── Submit button hover ripple ──
function setupButtonEffects() {
    const btn = document.querySelector('.btn-submit');
    if (!btn) return;

    btn.addEventListener('mouseenter', () => {
        anime({
            targets: btn,
            scale: [1, 1.02],
            duration: 300,
            easing: 'easeOutCubic',
        });
    });

    btn.addEventListener('mouseleave', () => {
        anime({
            targets: btn,
            scale: [1.02, 1],
            duration: 300,
            easing: 'easeOutCubic',
        });
    });
}

// ── Stepper logic ──
const TOTAL_STEPS = 5; // steps 0-4

function initStepper(form) {
    const steps = form.querySelectorAll('.stepper-step');
    const bar = form.querySelector('.stepper-progress-bar');

    function goToStep(idx) {
        steps.forEach(s => {
            s.style.display = parseInt(s.dataset.step) === idx ? '' : 'none';
            if (parseInt(s.dataset.step) === idx) {
                s.style.animation = 'none';
                s.offsetHeight; // reflow
                s.style.animation = '';
            }
        });
        if (bar) bar.style.width = ((idx + 1) / TOTAL_STEPS * 100) + '%';
    }

    // Next buttons
    form.querySelectorAll('.btn-stepper-next').forEach(btn => {
        btn.addEventListener('click', () => {
            const currentStep = parseInt(btn.dataset.next) - 1;
            const currentStepEl = form.querySelector(`.stepper-step[data-step="${currentStep}"]`);

            // Validate current step
            if (currentStep === 0) {
                const nameInput = currentStepEl.querySelector('input[name="name"]');
                if (!nameInput.value.trim()) {
                    nameInput.focus();
                    nameInput.reportValidity();
                    return;
                }
            }

            if (currentStep === 1) {
                const attending = form.querySelector('input[name="attending"]:checked');
                if (!attending) {
                    currentStepEl.querySelector('input[name="attending"]').reportValidity();
                    return;
                }
                // If "not attending" (value=0) — skip to submit immediately
                if (attending.value === '0') {
                    submitRsvpForm(form);
                    return;
                }
            }

            goToStep(parseInt(btn.dataset.next));
        });
    });

    // Back buttons
    form.querySelectorAll('.btn-stepper-back').forEach(btn => {
        btn.addEventListener('click', () => {
            goToStep(parseInt(btn.dataset.back));
        });
    });

    return { goToStep };
}

function getFormPayload(form, token) {
    const data = new FormData(form);
    return {
        user_token: token,
        name:               data.get('name'),
        attending:          data.get('attending'),
        food_preference:    data.get('food_preference'),
        alcohol_preferences: data.getAll('alcohol_preferences[]'),
        food_allergy:       data.get('food_allergy'),
    };
}

async function submitRsvpForm(form) {
    const token = window.userToken;
    const payload = getFormPayload(form, token);

    try {
        const res = await fetch('/api/rsvp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
            },
            body: JSON.stringify(payload),
        });

        if (res.ok) {
            localStorage.setItem('wedding_rsvp_submitted', '1');

            // Show success on inline form
            showFormSuccess('rsvp-form', 'form-success');
            // Show success on modal form
            showFormSuccess('rsvp-modal-form', 'modal-form-success');

            // Sync data between forms
            syncFormData(form);

            // Success animations
            const successEl = form.closest('.rsvp-modal-body')
                ? '#modal-form-success' : '#form-success';

            anime({
                targets: successEl,
                scale: [0.9, 1],
                opacity: [0, 1],
                duration: 500,
                easing: 'easeOutBack',
            });
            anime({
                targets: successEl + ' .success-icon',
                scale: [0, 1],
                rotate: ['0deg', '360deg'],
                duration: 800,
                delay: 200,
                easing: 'easeOutElastic(1, .6)',
            });
        }
    } catch (err) {
        console.error('RSVP submit error:', err);
    }
}

function syncFormData(sourceForm) {
    const targetId = sourceForm.id === 'rsvp-form' ? 'rsvp-modal-form' : 'rsvp-form';
    const target = document.getElementById(targetId);
    if (!target) return;

    const data = new FormData(sourceForm);

    const nameInput = target.querySelector('input[name="name"]');
    if (nameInput) nameInput.value = data.get('name') || '';

    const att = data.get('attending');
    if (att !== null) {
        const radio = target.querySelector(`input[name="attending"][value="${att}"]`);
        if (radio) radio.checked = true;
    }

    const food = data.get('food_preference');
    if (food) {
        const radio = target.querySelector(`input[name="food_preference"][value="${food}"]`);
        if (radio) radio.checked = true;
    }

    target.querySelectorAll('input[name="alcohol_preferences[]"]').forEach(cb => cb.checked = false);
    data.getAll('alcohol_preferences[]').forEach(val => {
        const cb = target.querySelector(`input[name="alcohol_preferences[]"][value="${val}"]`);
        if (cb) cb.checked = true;
    });

    const allergy = target.querySelector('textarea[name="food_allergy"]');
    if (allergy) allergy.value = data.get('food_allergy') || '';
}

function fillForm(form, g) {
    if (!form || !g) return;

    const nameInput = form.querySelector('input[name="name"]');
    if (nameInput && g.name) nameInput.value = g.name;

    const att = form.querySelector(`input[name="attending"][value="${g.attending ? '1' : '0'}"]`);
    if (att) att.checked = true;

    if (g.food_preference) {
        const food = form.querySelector(`input[name="food_preference"][value="${g.food_preference}"]`);
        if (food) food.checked = true;
    }

    if (g.alcohol_preferences) {
        g.alcohol_preferences.forEach(val => {
            const cb = form.querySelector(`input[name="alcohol_preferences[]"][value="${val}"]`);
            if (cb) cb.checked = true;
        });
    }

    const allergy = form.querySelector('textarea[name="food_allergy"]');
    if (allergy && g.food_allergy) allergy.value = g.food_allergy;
}

// ── Prefill RSVP from server ──
async function prefillRsvp(token) {
    try {
        const res = await fetch(`/api/rsvp/${encodeURIComponent(token)}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) return;
        const g = await res.json();
        if (!g || g.id == null) return;

        fillForm(document.getElementById('rsvp-form'), g);
        fillForm(document.getElementById('rsvp-modal-form'), g);

        showFormSuccess('rsvp-form', 'form-success');
        showFormSuccess('rsvp-modal-form', 'modal-form-success');

    } catch (e) {
        // No existing RSVP — leave form empty
    }
}

// ── Form success state ──
function showFormSuccess(formId, successId) {
    const form    = document.getElementById(formId);
    const success = document.getElementById(successId);
    if (!form || !success) return;

    form.style.display    = 'none';
    success.style.display = 'block';
}

function hideFormSuccess(formId, successId, stepper) {
    const form    = document.getElementById(formId);
    const success = document.getElementById(successId);
    if (!form || !success) return;

    success.style.display = 'none';
    form.style.display    = '';
    if (stepper) stepper.goToStep(0);
}

// ── Setup RSVP form ──
function setupRsvpForm(token) {
    const form    = document.getElementById('rsvp-form');
    const editBtn = document.getElementById('btn-edit-rsvp');

    if (!form) return;

    const stepper = initStepper(form);

    editBtn?.addEventListener('click', () => {
        hideFormSuccess('rsvp-form', 'form-success', stepper);
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        await submitRsvpForm(form);
    });
}

// ── Setup RSVP Modal ──
function setupRsvpModal(token) {
    const overlay   = document.getElementById('rsvp-modal');
    const openBtn   = document.getElementById('btn-open-rsvp-modal');
    const closeBtn  = document.getElementById('rsvp-modal-close');
    const form      = document.getElementById('rsvp-modal-form');
    const editBtn   = document.getElementById('btn-edit-modal-rsvp');

    if (!overlay) return;

    let stepper;
    if (form) {
        stepper = initStepper(form);

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            await submitRsvpForm(form);
        });
    }

    editBtn?.addEventListener('click', () => {
        hideFormSuccess('rsvp-modal-form', 'modal-form-success', stepper);
    });

    function openModal() {
        overlay.style.display = 'flex';
        requestAnimationFrame(() => {
            overlay.classList.add('is-visible');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        overlay.classList.remove('is-visible');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
        document.body.style.overflow = '';
    }

    openBtn?.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && overlay.style.display !== 'none') closeModal();
    });
}

function loadYandexMapsScript(apiKey) {
    return new Promise((resolve, reject) => {
        if (typeof window.ymaps !== 'undefined') {
            resolve();
            return;
        }
        if (!apiKey) {
            reject(new Error('YANDEX_MAPS_API_KEY'));
            return;
        }
        const id = 'yandex-maps-api-script';
        const existing = document.getElementById(id);
        if (existing) {
            const deadline = Date.now() + 12000;
            (function wait() {
                if (typeof window.ymaps !== 'undefined') {
                    resolve();
                    return;
                }
                if (Date.now() > deadline) {
                    reject(new Error('Yandex Maps timeout'));
                    return;
                }
                setTimeout(wait, 30);
            })();
            return;
        }
        const s = document.createElement('script');
        s.id = id;
        s.async = true;
        s.src = `https://api-maps.yandex.ru/2.1/?apikey=${encodeURIComponent(apiKey)}&lang=ru_RU`;
        s.onload = () => {
            const deadline = Date.now() + 12000;
            (function wait() {
                if (typeof window.ymaps !== 'undefined') {
                    resolve();
                    return;
                }
                if (Date.now() > deadline) {
                    reject(new Error('Yandex Maps timeout'));
                    return;
                }
                setTimeout(wait, 20);
            })();
        };
        s.onerror = () => reject(new Error('Yandex Maps script failed'));
        document.head.appendChild(s);
    });
}

function setMapFallback(el, html) {
    el.innerHTML = `<p class="jost location-map-fallback">${html}</p>`;
}

function initLocationMap() {
    const el = document.getElementById('wedding-map');
    if (!el) return;

    const apiKey = window.yandexMapsApiKey;
    if (!apiKey) {
        setMapFallback(
            el,
            'Укажите <strong>YANDEX_MAPS_API_KEY</strong> в файле <code>.env</code> и выполните <code>php artisan config:clear</code>, затем обновите страницу.'
        );
        return;
    }

    const lat = Number.parseFloat(el.dataset.lat || '');
    const lng = Number.parseFloat(el.dataset.lng || '');
    const address = (el.dataset.address || '').trim();
    const hasCoords = !Number.isNaN(lat) && !Number.isNaN(lng);

    const buildMap = (center) => {
        window.ymaps.ready(() => {
            el.innerHTML = '';
            const hint = (el.dataset.hint || '').trim();
            const esc = (s) =>
                String(s)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');

            const map = new window.ymaps.Map(
                el,
                {
                    center,
                    zoom: 16,
                    controls: ['zoomControl', 'fullscreenControl'],
                },
                {
                    suppressMapOpenBlock: true,
                }
            );

            try {
                if (map.options && typeof map.options.set === 'function') {
                    map.options.set('theme', 'dark');
                }
            } catch {
                /* optional */
            }

            map.behaviors.disable('scrollZoom');

            const placemark = new window.ymaps.Placemark(
                center,
                {
                    hintContent: hint || 'Место встречи',
                    balloonContent: `<div class="ymap-balloon-inner">${esc(hint || 'Место встречи')}</div>`,
                },
                {
                    preset: 'islands#circleDotIcon',
                    iconColor: '#C9A96E',
                }
            );
            map.geoObjects.add(placemark);

            setTimeout(() => {
                if (map.container && map.container.fitToViewport) {
                    map.container.fitToViewport();
                }
            }, 320);
        });
    };

    const failLoad = () => {
        setMapFallback(
            el,
            'Не удалось загрузить Яндекс.Карты. Проверьте ключ в .env и доступность api-maps.yandex.ru.'
        );
    };

    const failGeocode = () => {
        setMapFallback(
            el,
            'Не удалось определить координаты по адресу. Укажите широту и долготу в админке, уточните адрес или проверьте ключ <strong>YANDEX_MAPS_API_KEY</strong> на сервере.'
        );
    };

    if (hasCoords) {
        loadYandexMapsScript(apiKey).then(() => buildMap([lat, lng])).catch(failLoad);
        return;
    }

    if (address) {
        fetch(`/api/geocode?address=${encodeURIComponent(address)}`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then((res) => {
                if (!res.ok) {
                    return res.json().then((body) => {
                        throw new Error(body.message || 'geocode');
                    });
                }
                return res.json();
            })
            .then((data) => {
                if (data.lat == null || data.lng == null) {
                    throw new Error('no coords');
                }
                return loadYandexMapsScript(apiKey).then(() =>
                    buildMap([Number(data.lat), Number(data.lng)])
                );
            })
            .catch(failGeocode);
        return;
    }

    el.style.display = 'none';
}

// ── Falling petals — anime.js driven, continuous spawn ──
function setupFallingPetals() {
    const container = document.getElementById('falling-petals');
    if (!container) return;

    // Reduce on mobile for performance
    const isMobile = window.innerWidth < 600;
    const PETAL_COUNT = isMobile ? 10 : 20;
    const SPAWN_INTERVAL = isMobile ? 1800 : 900;

    const petalShapes = [
        // Burgundy petal
        (w, h) => `<svg width="${w}" height="${h}" viewBox="0 0 ${w} ${h}" fill="none"><path d="M${w/2} 0 C${w*0.8} ${h*0.25} ${w} ${h*0.55} ${w/2} ${h} C0 ${h*0.55} ${w*0.2} ${h*0.25} ${w/2} 0Z" fill="rgba(124,28,45,0.08)"/></svg>`,
        // Gold petal
        (w, h) => `<svg width="${w}" height="${h}" viewBox="0 0 ${w} ${h}" fill="none"><path d="M${w/2} 0 C${w*0.8} ${h*0.2} ${w} ${h*0.6} ${w/2} ${h} C0 ${h*0.6} ${w*0.2} ${h*0.2} ${w/2} 0Z" fill="rgba(201,169,110,0.1)"/></svg>`,
        // Rose petal
        (w, h) => `<svg width="${w}" height="${h}" viewBox="0 0 ${w} ${h}" fill="none"><path d="M${w/2} 0 C${w*0.9} ${h*0.3} ${w*0.8} ${h*0.7} ${w/2} ${h} C${w*0.2} ${h*0.7} ${w*0.1} ${h*0.3} ${w/2} 0Z" fill="rgba(212,160,167,0.1)"/></svg>`,
        // Leaf shape
        (w, h) => `<svg width="${w}" height="${h}" viewBox="0 0 ${w} ${h}" fill="none"><path d="M${w/2} 0 C${w} ${h*0.3} ${w*0.8} ${h*0.8} ${w/2} ${h} C${w*0.2} ${h*0.8} 0 ${h*0.3} ${w/2} 0Z" fill="rgba(201,169,110,0.07)" stroke="rgba(201,169,110,0.12)" stroke-width="0.3"/></svg>`,
    ];

    let activePetals = 0;

    function spawnPetal() {
        if (activePetals >= PETAL_COUNT) return;

        const el = document.createElement('div');
        el.className = 'petal';

        const w = 8 + Math.random() * 8;
        const h = w * (1.2 + Math.random() * 0.4);
        const shape = petalShapes[Math.floor(Math.random() * petalShapes.length)];
        el.innerHTML = shape(Math.round(w), Math.round(h));

        const startX = Math.random() * 100;
        el.style.left = `${startX}%`;
        el.style.top = '-40px';

        container.appendChild(el);
        activePetals++;

        const duration = 8000 + Math.random() * 8000;
        const swayAmount = 40 + Math.random() * 60;
        const rotateEnd = (Math.random() - 0.5) * 720;

        anime({
            targets: el,
            translateY: [0, window.innerHeight + 80],
            translateX: [
                { value: swayAmount, duration: duration * 0.25, easing: 'easeInOutSine' },
                { value: -swayAmount * 0.7, duration: duration * 0.25, easing: 'easeInOutSine' },
                { value: swayAmount * 0.5, duration: duration * 0.25, easing: 'easeInOutSine' },
                { value: 0, duration: duration * 0.25, easing: 'easeInOutSine' },
            ],
            rotate: [0, rotateEnd],
            opacity: [
                { value: 0.7, duration: duration * 0.08, easing: 'easeOutQuad' },
                { value: 0.5, duration: duration * 0.72, easing: 'linear' },
                { value: 0, duration: duration * 0.2, easing: 'easeInQuad' },
            ],
            duration: duration,
            easing: 'linear',
            complete: () => {
                el.remove();
                activePetals--;
            },
        });
    }

    // Initial burst
    for (let i = 0; i < Math.min(PETAL_COUNT, 8); i++) {
        setTimeout(() => spawnPetal(), i * 300);
    }

    // Continuous spawn
    setInterval(spawnPetal, SPAWN_INTERVAL);
}

// ── Ambient gold dust particles ──
function setupGoldDust() {
    const container = document.getElementById('gold-dust-container');
    if (!container) return;
    if (window.innerWidth < 600) return; // skip on mobile

    function spawnDust() {
        const el = document.createElement('div');
        el.className = 'gold-dust';

        const startX = Math.random() * window.innerWidth;
        const startY = Math.random() * window.innerHeight;
        const size = 2 + Math.random() * 3;

        el.style.left = `${startX}px`;
        el.style.top = `${startY}px`;
        el.style.width = `${size}px`;
        el.style.height = `${size}px`;

        container.appendChild(el);

        anime({
            targets: el,
            translateY: [0, -(30 + Math.random() * 50)],
            translateX: (Math.random() - 0.5) * 40,
            opacity: [0, 0.6, 0],
            scale: [0.5, 1, 0.3],
            duration: 3000 + Math.random() * 3000,
            easing: 'easeInOutSine',
            complete: () => el.remove(),
        });
    }

    // Spawn a few dust particles periodically
    setInterval(() => {
        const count = 1 + Math.floor(Math.random() * 2);
        for (let i = 0; i < count; i++) {
            setTimeout(spawnDust, i * 200);
        }
    }, 1200);
}

// ── Section title character-by-character reveal ──
function setupTitleCharAnimations() {
    const titles = document.querySelectorAll('.section-title-script');

    titles.forEach(title => {
        const text = title.textContent.trim();
        title.textContent = '';
        title.setAttribute('aria-label', text);

        [...text].forEach(char => {
            const span = document.createElement('span');
            span.className = 'title-char';
            span.textContent = char === ' ' ? '\u00A0' : char;
            title.appendChild(span);
        });

        const chars = title.querySelectorAll('.title-char');

        const io = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                anime({
                    targets: chars,
                    opacity: [0, 1],
                    translateY: [20, 0],
                    rotate: ['5deg', '0deg'],
                    delay: anime.stagger(35, { from: 'center' }),
                    duration: 600,
                    easing: 'easeOutBack',
                    complete: () => {
                        chars.forEach(c => c.classList.add('is-revealed'));
                    },
                });
                io.disconnect();
            }
        }, { threshold: 0.3 });

        io.observe(title);
    });
}

// ── Wedding mini-icon entrance animations ──
function setupMiniIconAnimations() {
    const icons = document.querySelectorAll('.wedding-mini-icon');

    icons.forEach(icon => {
        const io = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                icon.classList.add('is-visible');

                // Pop + spin the SVG
                const svg = icon.querySelector('svg');
                if (svg) {
                    anime({
                        targets: svg,
                        scale: [0, 1],
                        rotate: ['0deg', '360deg'],
                        opacity: [0, 1],
                        duration: 800,
                        easing: 'easeOutBack',
                    });

                    // Gentle perpetual float
                    anime({
                        targets: svg,
                        translateY: [-3, 3],
                        duration: 2500,
                        direction: 'alternate',
                        loop: true,
                        easing: 'easeInOutSine',
                        delay: 800,
                    });
                }

                io.disconnect();
            }
        }, { threshold: 0.5 });

        io.observe(icon);
    });
}

// ── Section glow — add radial glow behind section titles ──
function setupSectionGlows() {
    const sections = document.querySelectorAll('section');

    sections.forEach(section => {
        if (section.querySelector('.section-glow')) return;

        const title = section.querySelector('.section-title-script');
        if (!title) return;

        const glow = document.createElement('div');
        glow.className = 'section-glow';
        section.style.position = section.style.position || 'relative';
        section.insertBefore(glow, section.firstChild);

        const io = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                glow.classList.add('is-visible');
                io.disconnect();
            }
        }, { threshold: 0.15 });

        io.observe(section);
    });
}

// ── Countdown digits flip animation ──
function setupCountdownAnimations() {
    const digits = document.querySelectorAll('#countdown-timer .countdown-value');
    if (!digits.length) return;

    // Watch for text changes and add a subtle bounce
    digits.forEach(digit => {
        let lastVal = digit.textContent;
        const observer = new MutationObserver(() => {
            const newVal = digit.textContent;
            if (newVal !== lastVal) {
                lastVal = newVal;
                anime({
                    targets: digit,
                    scale: [1.15, 1],
                    duration: 400,
                    easing: 'easeOutElastic(1, .6)',
                });
            }
        });
        observer.observe(digit, { childList: true, characterData: true, subtree: true });
    });
}

// ── Gold sparkle cursor trail ──
function setupSparkleCursor() {
    const container = document.getElementById('sparkle-container');
    if (!container) return;

    // Only on non-touch devices
    if ('ontouchstart' in window) return;

    let lastTime = 0;
    const throttle = 50; // ms between particles

    document.addEventListener('mousemove', (e) => {
        const now = Date.now();
        if (now - lastTime < throttle) return;
        lastTime = now;

        // Create 2-3 particles per move
        const count = 2 + Math.floor(Math.random() * 2);
        for (let i = 0; i < count; i++) {
            const particle = document.createElement('div');
            const isStar = Math.random() > 0.6;
            particle.className = `sparkle-particle${isStar ? ' sparkle-star' : ''}`;

            const offsetX = (Math.random() - 0.5) * 20;
            const offsetY = (Math.random() - 0.5) * 20;
            const size = 2 + Math.random() * 4;

            particle.style.left = `${e.clientX + offsetX}px`;
            particle.style.top = `${e.clientY + offsetY}px`;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            particle.style.animationDuration = `${0.5 + Math.random() * 0.5}s`;

            container.appendChild(particle);

            // Clean up
            setTimeout(() => particle.remove(), 1000);
        }
    });
}

// ── Parallax scatter on scroll ──
function setupParallax() {
    const items = document.querySelectorAll('.scatter-item');
    if (!items.length) return;

    // Assign random parallax speeds
    const speeds = Array.from(items).map(() => 0.02 + Math.random() * 0.04);

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            const scrollY = window.scrollY;
            items.forEach((item, i) => {
                const shift = scrollY * speeds[i];
                const sway = Math.sin(scrollY * 0.003 + i) * 4;
                item.style.transform = `translateY(${-shift}px) translateX(${sway}px) ${item.dataset.baseRotate || ''}`;
            });
            ticking = false;
        });
    });

    // Save base rotation
    items.forEach(item => {
        const computed = getComputedStyle(item);
        const matrix = computed.transform;
        if (matrix && matrix !== 'none') {
            item.dataset.baseRotate = '';
        }
    });
}

// ── Countdown timer ──
function setupCountdown() {
    const el = document.getElementById('countdown-timer');
    if (!el) return;

    const dateStr = el.dataset.date || '';
    // Parse date format: DD/MM/YYYY or DD.MM.YYYY
    const parts = dateStr.split(/[\/.\-]/);
    if (parts.length < 3) return;

    const day = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1;
    const year = parseInt(parts[2], 10);
    const target = new Date(year, month, day, 12, 0, 0); // noon

    if (isNaN(target.getTime())) return;

    const daysEl = document.getElementById('cd-days');
    const hoursEl = document.getElementById('cd-hours');
    const minsEl = document.getElementById('cd-mins');
    const secsEl = document.getElementById('cd-secs');

    function pad(n) { return String(n).padStart(2, '0'); }

    function tick(prevSecs) {
        const now = new Date();
        const diff = Math.max(0, target - now);

        const d = Math.floor(diff / (1000 * 60 * 60 * 24));
        const h = Math.floor((diff / (1000 * 60 * 60)) % 24);
        const m = Math.floor((diff / (1000 * 60)) % 60);
        const s = Math.floor((diff / 1000) % 60);

        daysEl.textContent = pad(d);
        hoursEl.textContent = pad(h);
        minsEl.textContent = pad(m);
        secsEl.textContent = pad(s);

        // Tick animation on seconds change
        if (s !== prevSecs) {
            secsEl.classList.add('tick');
            setTimeout(() => secsEl.classList.remove('tick'), 300);
        }

        if (diff > 0) {
            setTimeout(() => tick(s), 1000);
        }
    }

    tick(-1);
}

// ── Main invitation init ──
export function initInvitation() {
    const inv = document.getElementById('invitation');
    if (!inv) return;

    inv.style.display = 'block';

    // Scroll to top
    window.scrollTo(0, 0);

    // Kick off animations after a brief delay
    requestAnimationFrame(() => {
        setupReveal();
        showScatterItems();
        heroEntrance();
        setupOrnamentFrames();
        setupLocationAnimations();
        setupTimelineAnimations();
        setupSwatchAnimations();
        setupSwatchHoverAnimations();
        setupDetailsAnimations();
        setupAngelAnimation();
        setupFooterAnimations();
        setupFormAnimations();
        setupFloralDividerAnimations();
        setupButtonEffects();
        setupSparkleCursor();
        setupParallax();
        initLocationSlider();
        initLocationMap();

        // New animations
        setupFallingPetals();
        setupGoldDust();
        setupTitleCharAnimations();
        setupMiniIconAnimations();
        setupSectionGlows();
        setupCountdownAnimations();

        const token = window.userToken || localStorage.getItem('wedding_token');
        if (token) {
            setupRsvpForm(token);
            setupRsvpModal(token);
            prefillRsvp(token);
        }
    });
}
