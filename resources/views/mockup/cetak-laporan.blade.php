<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup - Cetak Laporan Keuangan PDF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex flex-col font-sans">
    @include('mockup.nav')

    <div class="flex-grow p-6 max-w-5xl w-full mx-auto space-y-6">
        <!-- Title -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h1 class="text-2xl font-bold text-slate-900">Cetak & Rekap Laporan Keuangan</h1>
            <p class="text-xs text-slate-500 mt-1">Subbab 4.2.7 & 4.4.7 - Filter Parameter Laporan dan Pratinjau Dokumen Cetak PDF Resmi</p>
        </div>

        <!-- Filter & Report Selection Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
            <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">Parameter Pencetakan Laporan</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Jenis Laporan Keuangan</label>
                    <select id="jenisLaporan" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="cash-flow">Laporan Arus Kas (Cash Flow Statement)</option>
                        <option value="profit-loss">Laporan Laba Rugi (Profit & Loss Statement)</option>
                        <option value="service-revenue">Laporan Pendapatan Layanan / Properti</option>
                        <option value="transaction">Laporan Detail Transaksi Keuangan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Tanggal Mulai</label>
                    <input type="date" value="2026-07-01" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Tanggal Selesai</label>
                    <input type="date" value="2026-07-31" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button onclick="alert('Generasi Dokumen PDF Laporan Keuangan (Mockup PDF)')" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-rose-200 transition flex items-center gap-2">
                    <i class="fas fa-file-pdf text-sm"></i>
                    <span>Unduh Dokumen PDF Resmi</span>
                </button>
            </div>
        </div>

        <!-- Pratinjau Dokumen PDF Resmi (Print Preview Layout) -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-300 p-10 font-serif space-y-6 text-slate-900">
            <!-- Kop Surat Resmi -->
            <div class="text-center border-b-2 border-slate-900 pb-4">
                <h2 class="text-2xl font-bold uppercase tracking-wider font-sans text-slate-900">PT ALAM SARI REALTY & FINANCE</h2>
                <p class="text-xs font-sans text-slate-600">Jl. Raya Sanur No. 88, Denpasar, Bali | Email: info@alamsari.com | Telp: (0361) 894032</p>
                <div class="inline-block mt-3 px-4 py-1 bg-slate-100 text-slate-900 font-sans text-xs font-bold uppercase tracking-widest border border-slate-300">
                    LAPORAN ARUS KAS (CASH FLOW STATEMENT)
                </div>
                <p class="text-xs font-sans text-slate-500 mt-1">Periode: 01 Juli 2026 s/d 31 Juli 2026</p>
            </div>

            <!-- Preview Table -->
            <table class="w-full text-xs font-sans border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 uppercase text-slate-800 font-bold border-b border-slate-300">
                        <th class="border border-slate-300 px-4 py-2 text-left">Deskripsi Transaksi / Arus Kas</th>
                        <th class="border border-slate-300 px-4 py-2 text-right">Pemasukan (Rp)</th>
                        <th class="border border-slate-300 px-4 py-2 text-right">Pengeluaran (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr>
                        <td class="border border-slate-300 px-4 py-2 font-semibold">Penjualan Vila Alam Sari Luxury (Pelunasan)</td>
                        <td class="border border-slate-300 px-4 py-2 text-right font-mono text-emerald-700 font-bold">450.000.000</td>
                        <td class="border border-slate-300 px-4 py-2 text-right font-mono text-slate-400">-</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-4 py-2 font-semibold">Komisi Perantara Agen 5% (Agus Pratama)</td>
                        <td class="border border-slate-300 px-4 py-2 text-right font-mono text-slate-400">-</td>
                        <td class="border border-slate-300 px-4 py-2 text-right font-mono text-rose-700 font-bold">22.500.000</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-4 py-2 font-semibold">Pembayaran Termin 2 Rumah Canggu (Notaris)</td>
                        <td class="border border-slate-300 px-4 py-2 text-right font-mono text-emerald-700 font-bold">150.000.000</td>
                        <td class="border border-slate-300 px-4 py-2 text-right font-mono text-slate-400">-</td>
                    </tr>
                    <tr>
                        <td class="border border-slate-300 px-4 py-2 font-semibold">Biaya Legalisasi Notaris Hendra, SH</td>
                        <td class="border border-slate-300 px-4 py-2 text-right font-mono text-slate-400">-</td>
                        <td class="border border-slate-300 px-4 py-2 text-right font-mono text-rose-700 font-bold">12.000.000</td>
                    </tr>
                    <tr class="bg-slate-50 font-bold">
                        <td class="border border-slate-300 px-4 py-2.5 uppercase font-sans">TOTAL AKUMULASI ARUS KAS</td>
                        <td class="border border-slate-300 px-4 py-2.5 text-right font-mono text-emerald-800 font-extrabold text-sm">600.000.000</td>
                        <td class="border border-slate-300 px-4 py-2.5 text-right font-mono text-rose-800 font-extrabold text-sm">34.500.000</td>
                    </tr>
                    <tr class="bg-indigo-50 font-extrabold text-indigo-950">
                        <td colspan="2" class="border border-slate-300 px-4 py-3 uppercase font-sans">SALDO BERSIH (NET CASH FLOW)</td>
                        <td class="border border-slate-300 px-4 py-3 text-right font-mono text-base text-indigo-900">Rp 565.500.000</td>
                    </tr>
                </tbody>
            </table>

            <!-- Signatures Section -->
            <div class="pt-8 flex justify-between font-sans text-xs text-slate-800">
                <div class="text-center space-y-12">
                    <p>Dibuat Oleh,<br><span class="font-bold">Staf Keuangan</span></p>
                    <p class="font-bold border-b border-slate-800 inline-block px-4 pb-1">( _____________________ )</p>
                </div>
                <div class="text-center space-y-12">
                    <p>Disetujui Oleh,<br><span class="font-bold">Pimpinan PT Alam Sari</span></p>
                    <p class="font-bold border-b border-slate-800 inline-block px-4 pb-1">( _____________________ )</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
