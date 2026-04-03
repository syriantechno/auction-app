/* 
    Motor Bazar Admin Logic 
    Unified Component Initialization
*/

import './bootstrap';
import Chart from 'chart.js/auto';

// Global Chart configurations
window.initializeAdminCharts = (canvasId, data, options = {}) => {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#ffffff',
                titleColor: '#111827',
                bodyColor: '#64748b',
                borderColor: '#e2e8f0',
                borderWidth: 1,
                padding: 12,
                boxPadding: 6,
                usePointStyle: true,
                bodyFont: { weight: '600' }
            }
        },
        scales: {
            y: { grid: { color: '#f8fafc' }, border: { display: false }, ticks: { font: { weight: '600', size: 10 } } },
            x: { grid: { display: false }, border: { display: false }, ticks: { font: { weight: '600', size: 10 } } }
        }
    };

    return new Chart(ctx, {
        type: 'line',
        data: data,
        options: { ...defaultOptions, ...options }
    });
};

// Auto-init for some common components
document.addEventListener('DOMContentLoaded', () => {
    console.log('Motor Bazar Admin Framework Initialized');
});
