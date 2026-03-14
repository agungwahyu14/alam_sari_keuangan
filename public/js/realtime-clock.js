/**
 * Realtime Clock Helper
 * Update waktu secara realtime di seluruh aplikasi
 */

class RealtimeClock {
    constructor() {
        this.timeElements = [];
        this.dateElements = [];
        this.relativeTimeElements = [];
        this.init();
    }

    init() {
        // Update setiap detik
        this.updateAll();
        setInterval(() => this.updateAll(), 1000);
    }

    updateAll() {
        this.updateRealtimeClocks();
        this.updateDates();
        this.updateRelativeTimes();
    }

    // Update jam realtime (HH:mm:ss)
    updateRealtimeClocks() {
        const elements = document.querySelectorAll('[data-realtime-clock]');
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        });

        elements.forEach(element => {
            element.textContent = timeString;
        });
    }

    // Update tanggal realtime
    updateDates() {
        const elements = document.querySelectorAll('[data-realtime-date]');
        const now = new Date();

        elements.forEach(element => {
            const format = element.dataset.realtimeDate || 'full';
            let dateString;

            switch (format) {
                case 'short':
                    dateString = now.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                    break;
                case 'long':
                    dateString = now.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                    break;
                case 'full':
                default:
                    dateString = now.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    }) + ' ' + now.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
            }

            element.textContent = dateString;
        });
    }

    // Update waktu relatif (X menit yang lalu, dll)
    updateRelativeTimes() {
        const elements = document.querySelectorAll('[data-relative-time]');

        elements.forEach(element => {
            const timestamp = element.dataset.relativeTime;
            if (!timestamp) return;

            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;

            element.textContent = this.formatRelativeTime(diff);
        });
    }

    formatRelativeTime(diff) {
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        const weeks = Math.floor(days / 7);
        const months = Math.floor(days / 30);
        const years = Math.floor(days / 365);

        if (seconds < 60) {
            return 'baru saja';
        } else if (minutes < 60) {
            return minutes + ' menit yang lalu';
        } else if (hours < 24) {
            return hours + ' jam yang lalu';
        } else if (days < 7) {
            return days + ' hari yang lalu';
        } else if (weeks < 4) {
            return weeks + ' minggu yang lalu';
        } else if (months < 12) {
            return months + ' bulan yang lalu';
        } else {
            return years + ' tahun yang lalu';
        }
    }

    // Format custom untuk kebutuhan spesifik
    static formatTime(date, format = 'HH:mm:ss') {
        if (!(date instanceof Date)) {
            date = new Date(date);
        }

        const pad = (num) => String(num).padStart(2, '0');

        const formats = {
            'HH:mm:ss': () => `${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`,
            'HH:mm': () => `${pad(date.getHours())}:${pad(date.getMinutes())}`,
            'dd/MM/yyyy': () => `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()}`,
            'dd MMM yyyy': () => {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                return `${pad(date.getDate())} ${months[date.getMonth()]} ${date.getFullYear()}`;
            }
        };

        return formats[format] ? formats[format]() : date.toLocaleString('id-ID');
    }
}

// Initialize saat DOM ready
document.addEventListener('DOMContentLoaded', function () {
    window.realtimeClock = new RealtimeClock();
    console.log('Realtime Clock initialized');
});

// Export untuk digunakan di tempat lain
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RealtimeClock;
}
