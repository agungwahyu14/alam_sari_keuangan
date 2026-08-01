<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup - Transaksi Pengeluaran & Komisi Perantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex flex-col font-sans">
    @include('mockup.nav')

    <div class="flex-grow p-6 max-w-4xl w-full mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h1 class="text-2xl font-bold text-slate-900">Form Transaksi Pengeluaran & Komisi Perantara (Agen 5%)</h1>
            <p class="text-xs text-slate-500 mt-1">Subbab 4.2.5 & 4.4.5 - Form Pencatatan Dana Keluar dan Perhitungan Komisi Agen Freelance</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <form onsubmit="event.preventDefault(); alert('Transaksi Pengeluaran & Komisi Berhasil Dicatat (Mockup Data)');">
                <div class="space-y-6">
                    <!-- Kategori Pengeluaran -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Pengeluaran</label>
                        <select id="kategoriSelect" onchange="toggleAgenSection()" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-rose-500 focus:bg-white outline-none">
                            <option value="Komisi Agen" selected>Komisi Perantara / Agen Freelance (5%)</option>
                            <option value="Operasional Kantor">Operasional & Administrasi Kantor</option>
                            <option value="Biaya Legalitas Notaris">Biaya Legalitas & Notaris</option>
                            <option value="Perizinan & Pajak">Biaya Perizinan & Pajak Properti</option>
                        </select>
                    </div>

                    <!-- Panel Informasi Agen Freelance -->
                    <div id="agenSection" class="p-6 bg-rose-50/60 border border-rose-200 rounded-2xl space-y-4">
                        <div class="flex items-center gap-2 text-rose-800 font-bold text-sm">
                            <i class="fas fa-user-tie"></i>
                            <span>Detail Agen Penerima Komisi</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Pilih Agen Freelance</label>
                                <select onchange="updateBankInfo(this)" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-rose-500">
                                    @foreach($agents as $agent)
                                        <option value="{{ json_encode($agent) }}">{{ $agent['name'] }} (Bank {{ $agent['bank'] }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nilai Penjualan Properti (Rp)</label>
                                <input type="number" id="hargaProp" value="450000000" oninput="calculateCommission()" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-mono" placeholder="Nilai penjualan">
                            </div>
                        </div>

                        <!-- Rekening Bank info card -->
                        <div class="bg-white p-4 rounded-xl border border-rose-200/80 flex items-center justify-between text-xs">
                            <div>
                                <span class="text-slate-500 block">Tujuan Transfer Bank:</span>
                                <span id="bankDetail" class="font-bold text-slate-900 text-sm">8291039401 a/n Agus Pratama</span>
                            </div>
                            <div class="text-right">
                                <span class="text-slate-500 block">Kalkulasi Komisi (5%):</span>
                                <span id="commissionResult" class="font-extrabold text-rose-700 text-base font-mono">Rp 22.500.000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Detail -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nominal Pengeluaran Final (Rp)</label>
                            <input type="number" id="finalAmount" value="22500000" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono font-bold text-rose-700 focus:ring-2 focus:ring-rose-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Pengeluaran</label>
                            <input type="date" value="2026-07-28" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 focus:bg-white outline-none">
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Unggah Bukti Pengeluaran / Kwitansi Komisi</label>
                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 bg-slate-50 flex items-center justify-between text-xs">
                            <span class="font-mono text-slate-700 font-semibold"><i class="fas fa-receipt text-rose-500 mr-2"></i>kwitansi_komisi_agus_22.5jt.pdf</span>
                            <span class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded border border-rose-200">Terunggah</span>
                        </div>
                    </div>

                    <!-- Form Action -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('mockup.histori-transaksi') }}" class="px-5 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs transition">Batal</a>
                        <button type="submit" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-rose-200 transition flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            <span>Simpan Transaksi Pengeluaran</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calculateCommission() {
            const price = parseFloat(document.getElementById('hargaProp').value) || 0;
            const comm = price * 0.05;
            const formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(comm);
            document.getElementById('commissionResult').innerText = formatted;
            document.getElementById('finalAmount').value = comm;
        }

        function updateBankInfo(select) {
            const data = JSON.parse(select.value);
            const acc = data.bank_account || data.account || '1234567891';
            document.getElementById('bankDetail').innerText = `${acc} a/n ${data.name}`;
        }
    </script>
</body>
</html>
