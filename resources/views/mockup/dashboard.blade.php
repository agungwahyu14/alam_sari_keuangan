<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockup - Dashboard Keuangan Utama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col font-sans">
    @include('mockup.nav')

    <div class="flex-grow p-6 max-w-7xl w-full mx-auto space-y-6">
        <!-- Header Dashboard -->
        <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Dashboard Utama Keuangan</h1>
                <p class="text-xs text-slate-500 mt-1">Ringkasan arus kas, unit properti, dan pergerakan transaksi bulanan</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-3 py-1.5 rounded-xl text-xs font-semibold">
                    <i class="fas fa-user-shield mr-1"></i> Mode Admin
                </span>
                <span class="bg-slate-100 text-slate-700 px-3 py-1.5 rounded-xl text-xs font-medium border border-slate-200">
                    Periode: Juli 2026
                </span>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pemasukan</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($metrics['total_income'], 0, ',', '.') }}</div>
                <div class="text-xs text-emerald-600 font-semibold mt-2 flex items-center gap-1">
                    <i class="fas fa-caret-up"></i> +12.5% dibanding bulan lalu
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pengeluaran</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-slate-900">Rp {{ number_format($metrics['total_expense'], 0, ',', '.') }}</div>
                <div class="text-xs text-slate-500 mt-2">Termasuk komisi agen 5% & operasional</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Laba Bersih (Net)</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-indigo-700">Rp {{ number_format($metrics['net_profit'], 0, ',', '.') }}</div>
                <div class="text-xs text-indigo-600 font-semibold mt-2">Arus Kas Positif</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Unit Properti</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-slate-900">{{ $metrics['total_properties'] }} Unit</div>
                <div class="text-xs text-slate-500 mt-2">
                    <span class="text-emerald-600 font-semibold">{{ $metrics['sold_properties'] }} Terjual</span> | {{ $metrics['available_properties'] }} Tersedia
                </div>
            </div>
        </div>

        <!-- Main Chart & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Arus Kas Chart -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900">Grafik Arus Kas (Pemasukan vs Pengeluaran)</h3>
                    <span class="text-xs font-semibold bg-slate-100 px-3 py-1 rounded-lg text-slate-600">6 Bulan Terakhir</span>
                </div>
                <div class="h-64 w-full">
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </div>

            <!-- Chatbot FAQ Widget -->
            <div class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white p-6 rounded-2xl shadow-sm border border-indigo-900/50 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-900 flex items-center justify-center font-bold text-lg">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-base">Asisten FAQ Keuangan AI</h3>
                            <p class="text-xs text-indigo-300">Widget Chatbot Interaktif</p>
                        </div>
                    </div>
                    <div class="bg-slate-800/80 border border-slate-700/60 p-3.5 rounded-xl text-xs text-slate-300 space-y-2 mb-4">
                        <p class="font-semibold text-white">Contoh Pertanyaan Umum:</p>
                        <ul class="list-disc list-inside space-y-1 text-slate-300">
                            <li>Berapa komisi agen untuk Vila Sanur?</li>
                            <li>Bagaimana alur pencatatan termin notaris?</li>
                            <li>Berapa total laba bersih bulan ini?</li>
                        </ul>
                    </div>
                </div>
                <button onclick="alert('Fitur Chatbot AI Keuangan berjalan')" class="w-full bg-amber-400 hover:bg-amber-500 text-slate-950 font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center gap-2">
                    <i class="fas fa-comments"></i>
                    <span>Tanya Asisten AI</span>
                </button>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Histori Transaksi Terkini</h3>
                <a href="{{ route('mockup.histori-transaksi') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Lihat Semua Transaksi &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Kategori</th>
                            <th class="px-6 py-3">Jenis</th>
                            <th class="px-6 py-3">Jumlah (Rp)</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentTransactions as $tx)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-mono text-xs">{{ $tx['date'] }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $tx['category'] }}</td>
                            <td class="px-6 py-4">
                                @if($tx['type'] === 'Pemasukan')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Pemasukan</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold font-mono">Rp {{ number_format($tx['amount'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4"><span class="text-xs font-medium px-2 py-0.5 rounded bg-slate-100 text-slate-700">{{ $tx['status'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('cashFlowChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Feb 2026', 'Mar 2026', 'Apr 2026', 'Mei 2026', 'Jun 2026', 'Jul 2026'],
                    datasets: [
                        {
                            label: 'Pemasukan (Rp)',
                            data: [350000000, 500000000, 420000000, 680000000, 550000000, 700000000],
                            backgroundColor: '#22c55e',
                            borderRadius: 6
                        },
                        {
                            label: 'Pengeluaran (Rp)',
                            data: [60000000, 85000000, 70000000, 110000000, 90000000, 120000000],
                            backgroundColor: '#ef4444',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
    </script>
</body>
</html>
