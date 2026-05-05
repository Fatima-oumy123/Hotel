import './bootstrap';
import Alpine from 'alpinejs';
console.log('Mon Hotel chargé !');

window.Alpine = Alpine;
Alpine.start();

// Auto-dismiss flash messages
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('[data-auto-dismiss]');
    alerts.forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });

    const netBadge = document.querySelector('[data-network-status]');
    const syncNetworkState = () => {
        if (!netBadge) return;

        if (navigator.onLine) {
            netBadge.textContent = 'En ligne';
            netBadge.classList.remove('badge-danger');
            netBadge.classList.add('badge-success');
            return;
        }

        netBadge.textContent = 'Hors ligne';
        netBadge.classList.remove('badge-success');
        netBadge.classList.add('badge-danger');
    };

    window.addEventListener('online', syncNetworkState);
    window.addEventListener('offline', syncNetworkState);
    syncNetworkState();

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    }

    // Active nav highlighting — Radio role cards
    document.querySelectorAll('input[type="radio"][name="role"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('input[type="radio"][name="role"]').forEach(r => {
                const label = r.closest('label');
                if (label) {
                    label.querySelector('div').classList.toggle('border-amber-500', r.checked);
                    label.querySelector('div').classList.toggle('bg-amber-50', r.checked);
                    label.querySelector('div').classList.toggle('border-gray-200', !r.checked);
                }
            });
        });
    });
});
