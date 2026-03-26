/**
 * Classic CSS envelope + wax seal (idle animation, click to open).
 */

import anime from 'animejs/lib/anime.es.js';

function getUserToken() {
    let token = localStorage.getItem('wedding_token');
    if (!token) {
        token = 'usr_' + Date.now() + '_' + Math.random().toString(36).substr(2, 10);
        localStorage.setItem('wedding_token', token);
    }
    return token;
}
window.userToken = getUserToken();

let _sealFloatAnim = null;
let _sealGlowAnim = null;
let _opening = false;

function startSealAnimations() {
    const sealAnim = document.getElementById('env-seal-anim');
    if (!sealAnim) return;

    _sealFloatAnim = anime({
        targets: '#env-seal-anim',
        translateY: [0, -3, 0],
        rotate: [0, 1.2, -1.2, 0],
        duration: 5200,
        easing: 'easeInOutSine',
        loop: true,
    });

    _sealGlowAnim = anime({
        targets: '#env-wax-seal .env-seal-glow',
        scale: [1, 1.08, 1],
        opacity: [0.45, 0.85, 0.45],
        duration: 2800,
        easing: 'easeInOutSine',
        loop: true,
    });
}

function stopSealAnimations() {
    if (_sealFloatAnim) _sealFloatAnim.pause();
    if (_sealGlowAnim) _sealGlowAnim.pause();
    _sealFloatAnim = null;
    _sealGlowAnim = null;
    anime.remove('#env-seal-anim');
    anime.remove('#env-wax-seal .env-seal-glow');
}

function runExitToInvitation(onComplete) {
    const tl = anime.timeline({ easing: 'easeInCubic' });

    tl.add({
        targets: '#envelope-wrapper',
        scale: 0.88,
        translateY: [0, 24],
        opacity: 0,
        duration: 500,
    });

    tl.add({
        targets: '#envelope-screen',
        opacity: 0,
        duration: 460,
        easing: 'easeInQuad',
        complete() {
            const screen = document.getElementById('envelope-screen');
            if (screen) screen.style.display = 'none';
            document.body.style.overflow = '';
            localStorage.setItem('wedding_envelope_opened', '1');
            if (typeof onComplete === 'function') onComplete();
        },
    }, '-=180');
}

export function openEnvelope(onComplete) {
    const envelope = document.getElementById('envelope');
    const sealAnim = document.getElementById('env-seal-anim');
    if (!envelope || !sealAnim || _opening) return;
    _opening = true;

    stopSealAnimations();
    anime.remove('#envelope-wrapper');

    anime({
        targets: '#envelope-wrapper',
        scale: 1,
        duration: 100,
        easing: 'easeOutQuad',
        complete: () => {
            const tl = anime.timeline({ easing: 'easeOutCubic' });

            tl.add({
                targets: '#env-seal-anim',
                translateY: [0, 6],
                scaleX: [1, 1.06],
                scaleY: [1, 0.88],
                duration: 140,
                easing: 'easeInQuad',
            });

            tl.add({
                targets: '#env-seal-anim',
                scale: [1, 1.55],
                translateY: [6, -28],
                opacity: [1, 0],
                rotate: [0, 8],
                duration: 380,
                easing: 'easeOutExpo',
                complete: () => {
                    const sealEl = document.getElementById('env-wax-seal');
                    if (sealEl) sealEl.style.visibility = 'hidden';
                    envelope.classList.add('open');
                    envelope.classList.remove('close');
                    setTimeout(() => runExitToInvitation(onComplete), 2200);
                },
            });
        },
    });
}

export function initEnvelope(onComplete) {
    document.body.style.overflow = 'hidden';

    const screen = document.getElementById('envelope-screen');
    const seal = document.getElementById('env-wax-seal');
    if (!screen || !seal) return;

    screen.style.display = 'flex';
    screen.style.opacity = '0';

    const onActivate = (e) => {
        if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') return;
        if (e.type === 'keydown') e.preventDefault();
        openEnvelope(onComplete);
    };

    seal.addEventListener('click', onActivate);
    seal.addEventListener('keydown', onActivate);

    anime({
        targets: '#envelope-screen',
        opacity: [0, 1],
        duration: 550,
        easing: 'easeOutCubic',
        complete() {
            anime({
                targets: '#envelope-wrapper',
                opacity: [0, 1],
                scale: [0.98, 1],
                duration: 680,
                easing: 'easeOutCubic',
                complete() {
                    startSealAnimations();
                },
            });

            anime({
                targets: '.env-bg-petal',
                opacity: [0, 1],
                translateY: [12, 0],
                duration: 800,
                delay: anime.stagger(200, { start: 350 }),
                easing: 'easeOutCubic',
            });
        },
    });
}
