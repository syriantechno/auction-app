import './bootstrap';
import './echo';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { createIcons, icons } from 'lucide';
import L from 'leaflet';
import '@google/model-viewer';

// ── Global Plugins ──
Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

// ── Shared UI Utilities ──
const BazarToast = {
    show(message, type = 'success') {
        const event = new CustomEvent('show-toast', {
            detail: { message, type }
        });
        window.dispatchEvent(event);
    },
    success(m) { this.show(m, 'success'); },
    error(m) { this.show(m, 'error'); },
    warn(m) { this.show(m, 'warning'); },
    info(m) { this.show(m, 'info'); }
};
window.BazarToast = BazarToast;

// ── Lucide Engine ──
window.initLucide = () => {
    createIcons({ icons });
};

// ── Real-time Auction Countdowns ──
window.initCountdowns = () => {
    const updateCountdowns = () => {
        document.querySelectorAll('.active-countdown').forEach(el => {
            const endAt = new Date(el.getAttribute('data-end-at')).getTime();
            const now = new Date().getTime();
            const diff = endAt - now;

            const timerSpan = el.querySelector('.timer-values');
            if (!timerSpan) return;

            if (diff <= 0) {
                timerSpan.innerText = 'EXPIRED';
                el.classList.replace('bg-emerald-500', 'bg-slate-500');
                return;
            }

            const h = Math.floor(diff / (1000 * 60 * 60));
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);

            timerSpan.innerText = (h > 0 ? h + 'h ' : '') + 
                                  String(m).padStart(2, '0') + 'm ' + 
                                  String(s).padStart(2, '0') + 's';
        });
    };
    updateCountdowns();
    setInterval(updateCountdowns, 1000);
};

document.addEventListener('DOMContentLoaded', () => {
    window.initLucide();
    window.initCountdowns();
});

// ── Leaflet Export ──
window.L = L;

// ── Export Initialization ──
import { initBazarWizard } from './wizard';
window.initBazarWizard = initBazarWizard;

export { BazarToast, initBazarWizard };
