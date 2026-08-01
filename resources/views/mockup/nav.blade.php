<div class="bg-slate-900 text-white px-6 py-3 border-b border-slate-800 flex flex-wrap items-center justify-between gap-4 sticky top-0 z-50 shadow-md">
    <div class="flex items-center gap-3">
        <a href="{{ route('mockup.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 transition-colors">
            <i class="fas fa-th-large"></i>
            <span>Hub Mockup (/mockup)</span>
        </a>
        <span class="text-slate-600">|</span>
        <span class="text-sm font-semibold text-slate-200">
            @yield('mockup_title', 'Mockup Antarmuka')
        </span>
    </div>

    <div class="flex items-center gap-2 overflow-x-auto text-xs py-1">
        <a href="{{ route('mockup.login') }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium">1. Login</a>
        <a href="{{ route('mockup.dashboard') }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium">2. Dashboard</a>
        <a href="{{ route('mockup.properti') }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium">3. Properti</a>
        <a href="{{ route('mockup.pemasukan') }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium">4. Pemasukan</a>
        <a href="{{ route('mockup.pengeluaran') }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium">5. Pengeluaran</a>
        <a href="{{ route('mockup.histori-transaksi') }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium">6. Histori</a>
        <a href="{{ route('mockup.cetak-laporan') }}" class="px-2.5 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium">7. Laporan</a>
    </div>
</div>
