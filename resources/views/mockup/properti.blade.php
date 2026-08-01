<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup - Kelola Data Master Properti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex flex-col font-sans">
    @include('mockup.nav')

    <div class="flex-grow p-6 max-w-7xl w-full mx-auto space-y-6">
        <!-- Title & Action Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Kelola Data Master Properti</h1>
                <p class="text-xs text-slate-500 mt-1">Subbab 4.2.3 & 4.4.3 - Form dan Tabel Inventaris Unit Properti Alam Sari</p>
            </div>
            <button onclick="document.getElementById('modalTambahProperti').classList.remove('hidden')" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2.5 rounded-xl text-sm shadow-md transition flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Properti Baru</span>
            </button>
        </div>

        <!-- Property Data Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <input type="text" placeholder="Cari properti atau lokasi..." class="px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <select class="px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-none">
                        <option value="">Semua Status</option>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Terjual">Terjual</option>
                    </select>
                </div>
                <span class="text-xs text-slate-500 font-medium">Menampilkan {{ count($properties) }} Unit Properti</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">Kode</th>
                            <th class="px-6 py-3">Nama Properti</th>
                            <th class="px-6 py-3">Lokasi</th>
                            <th class="px-6 py-3">Harga Jual (Rp)</th>
                            <th class="px-6 py-3">Komisi Agen (5%)</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($properties as $prop)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500">{{ $prop['code'] }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $prop['name'] }}</td>
                            <td class="px-6 py-4 text-xs text-slate-600"><i class="fas fa-location-dot text-rose-500 mr-1"></i>{{ $prop['location'] }}</td>
                            <td class="px-6 py-4 font-bold font-mono text-slate-900">Rp {{ number_format($prop['price'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-emerald-700 font-semibold">Rp {{ number_format($prop['commission'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($prop['status'] === 'Tersedia')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Tersedia</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">Terjual</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <button onclick="alert('Form Edit Properti: {{ $prop['name'] }}')" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg text-xs font-semibold transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="alert('Konfirmasi Hapus Data Properti')" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-semibold transition" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form Tambah Properti (Mockup) -->
    <div id="modalTambahProperti" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
            <div class="bg-slate-900 text-white p-6 flex items-center justify-between">
                <h3 class="font-bold text-base">Form Tambah Master Properti</h3>
                <button onclick="document.getElementById('modalTambahProperti').classList.add('hidden')" class="text-slate-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Properti</label>
                    <input type="text" class="w-full px-3 py-2 border rounded-xl text-sm" placeholder="Contoh: Vila Luxury Sanur">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Lokasi Properti</label>
                    <input type="text" class="w-full px-3 py-2 border rounded-xl text-sm" placeholder="Contoh: Jl. Danau Tamblingan No. 45, Sanur">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Harga Jual (Rp)</label>
                        <input type="number" class="w-full px-3 py-2 border rounded-xl text-sm" placeholder="2500000000">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Status Properti</label>
                        <select class="w-full px-3 py-2 border rounded-xl text-sm">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Terjual">Terjual</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Deskripsi Tambahan</label>
                    <textarea class="w-full px-3 py-2 border rounded-xl text-sm h-20" placeholder="Keterangan spesifikasi vila..."></textarea>
                </div>
            </div>
            <div class="p-4 bg-slate-50 border-t flex justify-end gap-2">
                <button onclick="document.getElementById('modalTambahProperti').classList.add('hidden')" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Batal</button>
                <button onclick="alert('Data properti disimpan (Mockup)'); document.getElementById('modalTambahProperti').classList.add('hidden');" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold">Simpan Properti</button>
            </div>
        </div>
    </div>
</body>
</html>
