# 🕐 Panduan Penggunaan Realtime Clock

## 📝 Overview

Sistem Realtime Clock memungkinkan tampilan waktu dan tanggal di aplikasi untuk update secara otomatis tanpa perlu refresh halaman.

## ✨ Fitur

-   ✅ Update waktu setiap detik (HH:mm:ss)
-   ✅ Format tanggal realtime
-   ✅ Waktu relatif ("5 menit yang lalu", dll) yang auto-update
-   ✅ Support multiple format
-   ✅ Otomatis aktif di seluruh aplikasi

## 🚀 Cara Penggunaan

### 1. Jam Realtime (HH:mm:ss)

Tambahkan atribut `data-realtime-clock` pada elemen HTML:

```html
<!-- Simple -->
<span data-realtime-clock></span>
<!-- Output: 14:35:22 (update setiap detik) -->

<!-- Dengan text tambahan -->
Update terakhir: <span data-realtime-clock></span>
<!-- Output: Update terakhir: 14:35:22 -->
```

**Contoh di Blade:**

```blade
<div class="text-xs text-gray-500">
    Update terakhir: <span data-realtime-clock></span>
</div>
```

---

### 2. Tanggal Realtime

Tambahkan atribut `data-realtime-date` dengan format yang diinginkan:

```html
<!-- Full (default) - Tanggal + Waktu -->
<span data-realtime-date="full"></span>
<!-- Output: 13 Desember 2025 14:35:22 -->

<!-- Short - Tanggal pendek -->
<span data-realtime-date="short"></span>
<!-- Output: 13 Des 2025 -->

<!-- Long - Tanggal lengkap -->
<span data-realtime-date="long"></span>
<!-- Output: Jumat, 13 Desember 2025 -->
```

**Contoh di Blade:**

```blade
<div class="header">
    <span data-realtime-date="long"></span>
</div>
```

---

### 3. Waktu Relatif (Auto-Update)

Untuk menampilkan "X menit yang lalu" yang auto-update:

```html
<!-- Gunakan ISO 8601 timestamp -->
<span data-relative-time="2025-12-13T14:30:00+07:00"> 5 menit yang lalu </span>
<!-- Auto-update setiap detik -->
```

**Contoh di Blade dengan Laravel:**

```blade
<span data-relative-time="{{ $transaction->created_at->toIso8601String() }}">
    {{ $transaction->created_at->diffForHumans() }}
</span>
```

**Hasil:**

-   `baru saja` (< 1 menit)
-   `5 menit yang lalu`
-   `2 jam yang lalu`
-   `3 hari yang lalu`
-   `2 minggu yang lalu`
-   `1 bulan yang lalu`
-   `1 tahun yang lalu`

---

## 📋 Contoh Implementasi Lengkap

### Dashboard Header

```blade
<div class="header">
    <h1>Dashboard</h1>
    <div class="meta">
        <span data-realtime-date="long"></span>
        <span class="separator">|</span>
        <span data-realtime-clock></span>
    </div>
</div>
```

### List Transaksi

```blade
@foreach($transactions as $transaction)
<div class="transaction-item">
    <div class="transaction-info">
        <h3>{{ $transaction->description }}</h3>
        <span data-relative-time="{{ $transaction->created_at->toIso8601String() }}">
            {{ $transaction->created_at->diffForHumans() }}
        </span>
    </div>
    <div class="transaction-amount">
        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
    </div>
</div>
@endforeach
```

### Footer dengan Update Time

```blade
<footer>
    <p>
        Last sync: <span data-realtime-clock></span>
    </p>
    <p>
        &copy; 2025 Mancraft - <span data-realtime-date="short"></span>
    </p>
</footer>
```

---

## 🎨 Styling Tips

### Menambahkan animasi saat update

```css
[data-realtime-clock],
[data-relative-time] {
    transition: opacity 0.3s ease;
}

[data-realtime-clock].updating,
[data-relative-time].updating {
    opacity: 0.5;
}
```

### Format custom dengan CSS

```css
[data-realtime-clock] {
    font-family: "Courier New", monospace;
    font-weight: bold;
    color: #22c55e;
    font-size: 1.2em;
}

[data-relative-time] {
    color: #6b7280;
    font-size: 0.875rem;
}
```

---

## 🔧 JavaScript API

### Menggunakan method static

```javascript
// Format waktu custom
const formattedTime = RealtimeClock.formatTime(new Date(), "HH:mm:ss");
console.log(formattedTime); // "14:35:22"

// Format tanggal custom
const formattedDate = RealtimeClock.formatTime(new Date(), "dd MMM yyyy");
console.log(formattedDate); // "13 Des 2025"
```

### Mengakses instance global

```javascript
// Instance sudah tersedia sebagai window.realtimeClock
if (window.realtimeClock) {
    // Force update manual (jarang diperlukan)
    window.realtimeClock.updateAll();
}
```

---

## 🎯 Format yang Tersedia

### Waktu

-   `HH:mm:ss` - 14:35:22
-   `HH:mm` - 14:35

### Tanggal

-   `dd/MM/yyyy` - 13/12/2025
-   `dd MMM yyyy` - 13 Des 2025
-   `short` - 13 Des 2025
-   `long` - Jumat, 13 Desember 2025
-   `full` - 13 Desember 2025 14:35:22

### Waktu Relatif (Otomatis)

-   baru saja
-   X menit yang lalu
-   X jam yang lalu
-   X hari yang lalu
-   X minggu yang lalu
-   X bulan yang lalu
-   X tahun yang lalu

---

## ⚡ Performance

-   ✅ **Lightweight**: < 2KB (uncompressed)
-   ✅ **Efficient**: Update setiap 1 detik, tidak memberatkan browser
-   ✅ **Smart**: Hanya update elemen yang ada di DOM
-   ✅ **No Dependencies**: Pure JavaScript, tidak butuh library tambahan

---

## 🐛 Troubleshooting

### Waktu tidak update?

1. Pastikan script loaded: `console.log(window.realtimeClock)`
2. Check browser console untuk error
3. Verify atribut data-\* ditulis dengan benar

### Format tidak sesuai?

1. Check spelling atribut: `data-realtime-clock`, `data-realtime-date`, `data-relative-time`
2. Untuk relative time, pastikan menggunakan ISO 8601 format

### Performa lambat?

-   Normal jika ada ratusan elemen dengan realtime clock
-   Pertimbangkan untuk limit jumlah elemen yang di-update

---

## 📚 Best Practices

### ✅ DO

-   Gunakan untuk header, dashboard, status updates
-   Combine dengan Laravel timestamps yang sudah ada
-   Test di berbagai timezone

### ❌ DON'T

-   Jangan gunakan untuk ribuan elemen sekaligus
-   Jangan modify script untuk update lebih cepat dari 1 detik
-   Jangan lupa fallback text untuk SEO

---

## 🔄 Update & Maintenance

File yang terlibat:

-   `/public/js/realtime-clock.js` - Core script
-   `/resources/views/layouts/app.blade.php` - Load script
-   Blade files yang menggunakan atribut data-\*

Untuk update, edit file `realtime-clock.js` dan clear cache browser.

---

## 💡 Tips & Tricks

### Kombinasi dengan Auto-Refresh Data

```javascript
// Refresh data setiap 30 detik sambil update waktu real-time
setInterval(() => {
    fetch("/api/dashboard-data")
        .then((response) => response.json())
        .then((data) => {
            // Update data tanpa reload page
            updateDashboard(data);
        });
}, 30000);
```

### Custom Formatter

```javascript
// Buat custom formatter di page script
document.addEventListener("DOMContentLoaded", function () {
    // Custom update untuk element tertentu
    setInterval(() => {
        const customElement = document.querySelector(".custom-time");
        if (customElement) {
            customElement.textContent = new Date().toLocaleString("id-ID");
        }
    }, 1000);
});
```

---

## 🎉 Kesimpulan

Realtime Clock helper membuat aplikasi terasa lebih "hidup" dan modern dengan update waktu otomatis. Gunakan dengan bijak sesuai kebutuhan!

**Happy Coding! 🚀**
