/* 
    Motor Bazar Admin Logic 
    Unified Component Initialization & Modernized Script Hub
*/

import './bootstrap';
import $ from 'jquery';
import Chart from 'chart.js/auto';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { createIcons, icons as lucideIcons } from 'lucide';

// Pre-Init Globals
window.$ = window.jQuery = $;
window.Swal = Swal;
window.flatpickr = flatpickr;

// Alpine Registry
Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();


// --- GLOBAL UTILITIES ---

window.initBazarPickers = function(container = document) {
    container.querySelectorAll('.bazar-date').forEach(el => {
        flatpickr(el, { dateFormat: "d M Y", minDate: "today", disableMobile: true });
    });
    container.querySelectorAll('.bazar-time').forEach(el => {
        flatpickr(el, { 
            enableTime: true, 
            noCalendar: true, 
            dateFormat: "h:i K", 
            time_24hr: false, 
            disableMobile: true 
        });
    });
};

// --- PREMIUM ELITE TOAST ENGINE ---

let eliteToastContainer = null;

function ensureToastContainer() {
    if (eliteToastContainer) return eliteToastContainer;
    eliteToastContainer = document.createElement('div');
    eliteToastContainer.id = 'eliteToastContainer';
    eliteToastContainer.style.cssText = 'position:fixed;top:2.5rem;right:2.5rem;z-index:9999999;display:flex;flex-direction:column;gap:1rem;pointer-events:none;';
    document.body.appendChild(eliteToastContainer);
    return eliteToastContainer;
}

const toastConfigs = {
    success: {
        label: 'Sync Successful',
        icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-400"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        bg: 'rgba(2, 6, 23, 0.96)',
        border: 'rgba(255, 255, 255, 0.15)',
        subColor: 'rgba(255,255,255,0.4)'
    },
    error: {
        label: 'Sync Failure',
        icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-red-400"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        bg: '#dc2626',
        border: 'rgba(255, 255, 255, 0.2)',
        subColor: 'rgba(255,255,255,0.6)'
    }
};

window.showToast = function(msg, type = 'success', duration = 5000) {
    const config = toastConfigs[type] || toastConfigs.success;
    const toast = document.createElement('div');
    toast.style.cssText = `
        pointer-events: auto;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.5rem 2.5rem;
        background: ${config.bg};
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid ${config.border};
        border-radius: 5rem;
        box-shadow: 0 35px 60px -15px rgba(0,0,0,0.3);
        min-width: 380px;
        max-width: 500px;
        opacity: 0;
        transform: translateX(3rem) scale(0.9) blur(10px);
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        font-family: 'Plus Jakarta Sans', sans-serif;
    `;

    toast.innerHTML = `
        <div style="width: 3.5rem; height: 3.5rem; border-radius: 1.2rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: inset 0 0 12px rgba(0,0,0,0.1);">
            ${config.icon}
        </div>
        <div style="flex: 1;">
            <p style="margin: 0; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em; color: ${config.subColor}; margin-bottom: 0.25rem;">${config.label}</p>
            <p style="margin: 0; font-size: 1.05rem; font-weight: 500; color: white; letter-spacing: -0.02em; line-height: 1.2;">${msg}</p>
        </div>
        <div style="position: absolute; top: 0.75rem; right: 2rem; opacity: 0.1; color: white; pointer-events: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-5c1.62-2.2 5-2.5 5-2.5"/><path d="M12 15v5s3.03-.55 5-2c2.2-1.62 2.5-5 2.5-5"/></svg>
        </div>
    `;

    const target = ensureToastContainer();
    target.appendChild(toast);
    
    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0) scale(1) blur(0)';
    });

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(3rem) scale(0.9) blur(10px)';
        setTimeout(() => toast.remove(), 500);
    }, duration);
};

window.notify = {
    success: (m) => window.showToast(m, 'success'),
    error: (m) => window.showToast(m, 'error'),
    warning: (m) => window.showToast(m, 'error'),
    info: (m) => window.showToast(m, 'success')
};

// Alpine Event Link
window.addEventListener('show-toast', e => {
    window.showToast(e.detail.message || e.detail.msg, e.detail.type || 'success');
});

// --- NOTIFICATION CENTER SYSTEM ---

window.initNotificationCenter = function(config) {
    const { listUrl, countUrl, readAllUrl, readUrlTemplate, csrf, initialCount } = config;
    const API = {
        list: listUrl,
        count: countUrl,
        readAll: readAllUrl,
        read: (id) => readUrlTemplate.replace(':id', id),
    };
    const FETCH_OPTS = { credentials: 'same-origin', headers: { 'Accept': 'application/json' } };

    let panelOpen = false;
    let audioCtx = null;
    let lastCount = initialCount || 0;

    function playAlertTone() {
        try {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator(), gain = audioCtx.createGain();
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, audioCtx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(440, audioCtx.currentTime + 0.25);
            gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);
            osc.start(audioCtx.currentTime);
            osc.stop(audioCtx.currentTime + 0.5);
        } catch(e) {}
    }

    function setBadge(count) {
        const badge = document.getElementById('notif-badge');
        const label = document.getElementById('notif-count-label');
        const cardCount = document.getElementById('notif-card-count');
        
        if (!badge) return;
        
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
            if (cardCount) cardCount.textContent = count + ' New';
        } else {
            badge.textContent = '0';
            badge.classList.add('hidden');
            if (cardCount) cardCount.textContent = '0 New';
        }
        
        if (label) label.textContent = count > 0
            ? `${count} unread notification${count !== 1 ? 's' : ''}`
            : 'All caught up!';
    }

    function renderItem(n) {
        const icons  = { 'user-round-plus': '👤', 'gavel': '🔨', 'bell': '🔔', 'dollar-sign': '💰' };
        const colors = { 'orange': 'bg-orange-50 text-orange-500', 'emerald': 'bg-emerald-50 text-emerald-500' };
        const icon  = icons[n.icon]  ?? '🔔';
        const color = colors[n.color] ?? 'bg-slate-50 text-slate-500';
        const unreadDot = !n.read ? '<span class="w-2 h-2 rounded-full bg-[#ff6900] flex-shrink-0"></span>' : '';
        const safeUrl = (n.url && n.url !== 'undefined' && n.url !== 'null') ? n.url : '#';
        return `<div class="flex gap-4 px-6 py-4 hover:bg-slate-50/70 transition-all cursor-pointer ${n.read ? 'opacity-60' : ''}"
                     onclick="window.readAndGo('${n.id}', '${safeUrl}')">
            <div class="w-9 h-9 rounded-xl ${color} flex items-center justify-center text-base flex-shrink-0">${icon}</div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-[0.7rem] font-black text-[#031629]">${n.title}</span>
                    ${unreadDot}
                </div>
                <p class="text-[0.65rem] text-slate-500 font-medium leading-snug mt-0.5">${n.message}</p>
                <span class="text-[0.55rem] text-slate-300 font-bold uppercase tracking-widest">${n.created_at}</span>
            </div>
        </div>`;
    }

    function showNotifToast(n) {
        if (typeof window.showToast !== 'function') return;
        const title = n.title || 'Notification';
        const body  = (n.message || '').substring(0, 80);
        window.showToast(`<strong style="font-size:0.7rem">${title}</strong><br><span style="font-size:0.65rem;opacity:0.75">${body}</span>`, 'info', 6000);
    }

    window.loadNotifications = async function() {
        try {
            const res = await fetch(API.list, FETCH_OPTS);
            if (!res.ok) return;
            const data = await res.json();
            const newCount = data.unread_count ?? 0;

            if (newCount > lastCount) {
                playAlertTone();
                const newest = (data.notifications ?? []).find(n => !n.read);
                if (newest) showNotifToast(newest);
            }

            lastCount = newCount;
            setBadge(newCount);

            const list = document.getElementById('notif-list');
            if (list) {
                if (!data.notifications?.length) {
                    list.innerHTML = '<div class="py-12 text-center text-[0.65rem] font-black uppercase tracking-widest text-slate-300">No notifications yet.</div>';
                } else {
                    list.innerHTML = data.notifications.map(renderItem).join('');
                }
            }
        } catch(e) { console.warn('[Notif] Fetch error:', e.message); }
    };

    window.toggleNotifPanel = function() {
        const panel = document.getElementById('notif-panel');
        const userPanel = document.getElementById('user-panel');
        if (!panel) return;
        
        if (userPanel && !userPanel.classList.contains('hidden')) {
            userPanel.classList.add('hidden');
        }
        
        panelOpen = !panelOpen;
        panel.classList.toggle('hidden', !panelOpen);
        if (panelOpen) window.loadNotifications();
    };

    window.readAndGo = function(id, url) {
        fetch(API.read(id), {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        }).then(() => window.loadNotifications()).catch(() => {});
        if (url && url !== '#') window.location.href = url;
    };

    window.markAllRead = function() {
        fetch(API.readAll, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        }).then(() => { lastCount = 0; setBadge(0); window.loadNotifications(); }).catch(() => {});
    };
    
    // Listen for Pusher events
    if (window.Echo) {
             const userChannel = window.Echo.private(`notifications.${config.userId}`);
             userChannel.listen('.notification.sent', (e) => {
                 playAlertTone();
                 showNotifToast(e);
                 lastCount++;
                 setBadge(lastCount);
                 window.loadNotifications();
             });
             
             (config.userRoles || []).forEach(role => {
                 window.Echo.private(`role.${role}`)
                     .listen('.notification.sent', (e) => {
                         playAlertTone();
                         showNotifToast(e);
                         lastCount++;
                         setBadge(lastCount);
                         window.loadNotifications();
                     });
             });
    }

    // Initial Badge UI
    setBadge(lastCount);
};

window.toggleUserPanel = function() {
    const panel = document.getElementById('user-panel');
    const notifPanel = document.getElementById('notif-panel');
    if (!panel) return;
    
    if (notifPanel && !notifPanel.classList.contains('hidden')) {
        notifPanel.classList.add('hidden');
    }
    
    panel.classList.toggle('hidden');
};

// --- AUTO-INITIALIZATION ---

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons: lucideIcons });
    window.initBazarPickers();

    // Auto-sidebar closure on small screens via Alpine state interaction
    window.addEventListener('resize', () => {
         const xDataElement = document.querySelector('[x-data]');
         if(xDataElement && xDataElement.__x && window.innerWidth < 1024) {
             xDataElement.__x.$data.sidebarOpen = false;
         }
    });

    // Close panels on outside click
    document.addEventListener('click', (e) => {
        const notifWrapper = document.getElementById('notif-wrapper');
        const userWrapper = document.getElementById('user-menu-wrapper');
        
        if (notifWrapper && !notifWrapper.contains(e.target)) {
            document.getElementById('notif-panel')?.classList.add('hidden');
        }
        
        if (userWrapper && !userWrapper.contains(e.target)) {
            document.getElementById('user-panel')?.classList.add('hidden');
        }
    });

    console.log('Motor Bazar Admin Framework Initialized (NPM Bundled)');
});
