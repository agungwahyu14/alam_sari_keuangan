<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup - Tabel Histori Transaksi & Arus Kas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex flex-col font-sans">
    @include('mockup.nav')

    <div class="flex-grow p-6 max-w-7xl w-full mx-auto space-y-6">
        <!-- Title & Filter Bar -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Histori Transaksi & Arus Kas</h1>
                    <p class="text-xs text-slate-500 mt-1">Subbab 4.2.6 & 4.4.6 - Tabel Rekam Jejak Arus Kas Transaksi Pemasukan dan Pengeluaran</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('mockup.pemasukan') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fas fa-plus"></i> + Pemasukan
                    </a>
                    <a href="{{ route('mockup.pengeluaran') }}" class="bg-rose-600 hover:bg-rose-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fas fa-minus"></i> + Pengeluaran
                    </a>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="pt-4 border-t border-slate-100 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Jenis Transaksi</label>
                    <select class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium">
                        <option value="">Semua Transaksi</option>
                        <option value="Pemasukan">Pemasukan</option>
                        <option value="Pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Dari Tanggal</label>
                    <input type="date" value="2026-07-01" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Sampai Tanggal</label>
                    <input type="date" value="2026-07-31" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs">
                </div>
                <div class="flex items-end">
                    <button class="w-full py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">
                        <i class="fas fa-filter mr-1"></i> Terapkan Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <input type="text" placeholder="Cari ID transaksi, nama pembeli, notaris..." class="px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs w-72">
                <span class="text-xs text-slate-500 font-semibold">Total {{ count($transactions) }} Transaksi Tercatat</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">ID Transaksi</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Jenis</th>
                            <th class="px-6 py-3">Kategori</th>
                            <th class="px-6 py-3">Properti / Pihak Terkait</th>
                            <th class="px-6 py-3">Nominal (Rp)</th>
                            <th class="px-6 py-3 text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transactions as $tx)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500">{{ $tx['id'] }}</td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $tx['date'] }}</td>
                            <td class="px-6 py-4">
                                @if($tx['type'] === 'Pemasukan')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Pemasukan</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $tx['category'] }}</td>
                            <td class="px-6 py-4 text-xs">
                                <div class="font-bold text-slate-800">{{ $tx['property'] }}</div>
                                <div class="text-slate-500">{{ $tx['party'] }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold font-mono text-slate-900">
                                @if($tx['type'] === 'Pemasukan')
                                    <span class="text-emerald-600">+ Rp {{ number_format($tx['amount'], 0, ',', '.') }}</span>
                                @else
                                    <span class="text-rose-600">- Rp {{ number_format($tx['amount'], 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="alert('Modal Detail Transaksi:\nID: {{ $tx['id'] }}\nCatatan: {{ $tx['notes'] }}')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
