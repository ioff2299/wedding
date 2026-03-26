import { initEnvelope } from './envelope.js';
import { initInvitation } from './invitation.js';

// Make initInvitation globally accessible for envelope.js callback
window.__initInvitation = initInvitation;

const hasOpened = localStorage.getItem('wedding_envelope_opened') === '1';

if (hasOpened) {
    // Skip straight to invitation
    const screen = document.getElementById('envelope-screen');
    if (screen) screen.style.display = 'none';
    initInvitation();
} else {
    // Show envelope animation, then reveal invitation on complete
    initEnvelope(() => {
        initInvitation();
    });
}
