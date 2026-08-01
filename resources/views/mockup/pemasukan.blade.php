<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup - Transaksi Pemasukan & Termin Notaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex flex-col font-sans">
    @include('mockup.nav')

    <div class="flex-grow p-6 max-w-4xl w-full mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h1 class="text-2xl font-bold text-slate-900">Form Pencatatan Transaksi Pemasukan & Termin Notaris</h1>
            <p class="text-xs text-slate-500 mt-1">Subbab 4.2.4 & 4.4.4 - Form Input Penerimaan Dana Usaha, Pembayaran Termin, dan Legalisasi Notaris</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <form onsubmit="event.preventDefault(); alert('Transaksi Pemasukan Berhasil Dicatat (Mockup Data)');">
                <div class="space-y-6">
                    <!-- Kategori Pemasukan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Pemasukan</label>
                        <select class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <option value="Penjualan Properti">Penjualan Properti (Pelunasan)</option>
                            <option value="Pembayaran Termin" selected>Pembayaran Termin / Bertahap (Notaris)</option>
                            <option value="Biaya Notaris & Legalisasi">Biaya Legalisasi & Notaris</option>
                            <option value="DP Pembelian Properti">Uang Muka / DP Pembelian</option>
                        </select>
                    </div>

                    <!-- Unit Properti Terkait -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Unit Properti Terkait</label>
                            <select class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                @foreach($properties as $prop)
                                    <option value="{{ $prop }}">{{ $prop }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Pembeli / Pembayar</label>
                            <input type="text" value="Budi Santoso" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none" placeholder="Masukkan nama lengkap pembeli">
                        </div>
                    </div>

                    <!-- Financial Detail -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nominal Pemasukan (Rp)</label>
                            <input type="number" value="150000000" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono font-bold text-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Transaksi</label>
                            <input type="date" value="2026-07-25" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Metode Pembayaran</label>
                            <select class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                                <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                                <option value="Rekening Escrow Notaris">Rekening Escrow Notaris</option>
                                <option value="Tunai">Tunai / Cash</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notaris & File Upload -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan Notaris / No. Referensi</label>
                            <input type="text" value="Notaris Hendra, SH - Akta Termin No. 45/2026" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Unggah Bukti Transfer / Resi</label>
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-3 bg-slate-50 flex items-center justify-between text-xs text-slate-500">
                                <span class="font-mono text-slate-700 font-semibold"><i class="fas fa-file-invoice text-indigo-500 mr-2"></i>bukti_transfer_termin2.pdf</span>
                                <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Terunggah</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Action -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('mockup.histori-transaksi') }}" class="px-5 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs transition">Batal</a>
                        <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-200 transition flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            <span>Simpan Transaksi Pemasukan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
