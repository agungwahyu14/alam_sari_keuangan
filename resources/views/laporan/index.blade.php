@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
    <style>
        /* Animation for page load */
        .fade-in-up {
            animation: fadeInUp 0.7s cubic-bezier(.39, .575, .565, 1) both;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Button Styles */
        .btn-cta {
            background: linear-gradient(to right, #22c55e, #16a34a) !important;
            color: #fff !important;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.15);
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-cta:hover {
            background: linear-gradient(to right, #16a34a, #166534) !important;
            color: #fff !important;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #f3f4f6 !important;
            color: #374151 !important;
            font-weight: 500;
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s ease;
            border: 1px solid #d1d5db;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-secondary:hover {
            background: #e5e7eb !important;
            color: #374151 !important;
            text-decoration: none;
        }

        .btn-download {
            background: linear-gradient(to right, #3b82f6, #2563eb) !important;
            color: #fff !important;
            font-weight: 500;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-download:hover {
            background: linear-gradient(to right, #2563eb, #1d4ed8) !important;
            color: #fff !important;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* Form Controls */
        input:focus,
        select:focus {
            outline: none !important;
            box-shadow: 0 0 0 2px #22c55e !important;
            border-color: #22c55e !important;
            transition: box-shadow 0.2s;
        }

        /* Card Styles */
        .report-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 24px rgba(34, 197, 94, 0.08);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #f3f4f6;
        }

        .metric-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 0.5rem;
            padding: 1rem;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s ease;
        }

        .metric-card:hover {
            transform: translateY(-2px);
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        /* Tab Styles */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            border: none;
            background: #f3f4f6;
            color: #6b7280;
            font-weight: 500;
            cursor: pointer;
            border-radius: 0.5rem 0.5rem 0 0;
            margin-right: 0.25rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .tab-button.active {
            background: #22c55e !important;
            color: white !important;
        }

        .tab-button:hover:not(.active) {
            background: #e5e7eb;
            text-decoration: none;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .table-container th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-container tbody tr:hover {
            background-color: #f9fafb;
        }

        /* DataTables Override Styles */
        .dataTables_wrapper {
            font-family: inherit;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.25rem;
            margin: 0 0.5rem;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .tab-button {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }

            .metric-card {
                padding: 0.75rem;
            }

            .metric-value {
                font-size: 1.25rem;
            }

            .report-card {
                padding: 1rem;
            }

            .flex.gap-4 {
                flex-direction: column;
                gap: 1rem;
            }
        }

        /* Utility Classes */
        .text-green-600 {
            color: #059669 !important;
        }

        .text-red-600 {
            color: #dc2626 !important;
        }

        .text-blue-600 {
            color: #2563eb !important;
        }

        .font-semibold {
            font-weight: 600 !important;
        }

        .font-bold {
            font-weight: 700 !important;
        }
    </style>

    <div class="container mx-auto py-8 fade-in-up">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
                <div class="mt-2 flex items-center gap-3 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-2"></i>
                        <span data-realtime-date="long"></span>
                    </div>
                    <span class="text-gray-400">|</span>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span data-realtime-clock></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="report-card">
            <h3 class="text-lg font-semibold mb-4">Filter Periode</h3>
            <div class="flex gap-4 items-end">
                <div>
                    <label for="start-date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" id="start-date" class="border border-gray-300 rounded px-3 py-2"
                        value="{{ date('Y-m-01') }}">
                </div>
                <div>
                    <label for="end-date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" id="end-date" class="border border-gray-300 rounded px-3 py-2"
                        value="{{ date('Y-m-t') }}">
                </div>
                <button onclick="updateAllReports()" class="btn-cta">
                    <i class="fas fa-refresh mr-2"></i>Update Laporan
                </button>
            </div>
        </div>

        <!-- Report Tabs -->
        <div class="report-card">
            <div class="border-b border-gray-200 mb-4">
                <nav class="-mb-px flex">
                    @if (auth()->user()->role !== 'karyawan')
                        <button class="tab-button active" onclick="switchTab('cashflow')" id="tab-cashflow">
                            <i class="fas fa-chart-line mr-2"></i>Arus Kas
                        </button>
                        <button class="tab-button" onclick="switchTab('profitloss')" id="tab-profitloss">
                            <i class="fas fa-chart-pie mr-2"></i>Laba Rugi
                        </button>
                        <button class="tab-button" onclick="switchTab('service')" id="tab-service">
                            <i class="fas fa-bars mr-2"></i>Pendapatan per Layanan
                        </button>
                        <button class="tab-button" onclick="switchTab('transaction')" id="tab-transaction">
                            <i class="fas fa-list mr-2"></i>Laporan Transaksi
                        </button>
                    @endif
                    <button class="tab-button @if (auth()->user()->role === 'karyawan') active @endif" onclick="switchTab('salary')"
                        id="tab-salary">
                        <i class="fas fa-users mr-2"></i>
                        @if (auth()->user()->role === 'admin')
                            Laporan Gaji
                        @else
                            Slip Gaji
                        @endif
                    </button>
                </nav>
            </div>

            <!-- Cash Flow Tab -->
            @if (auth()->user()->role !== 'karyawan')
                <div id="cashflow-content" class="tab-content active">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Laporan Arus Kas</h3>
                        <button onclick="downloadPDF('cash-flow')" class="btn-download">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="metric-card">
                            <div class="metric-value text-green-600" id="total-income">Rp 0</div>
                            <div class="text-sm text-gray-600">Total Pemasukan Kas</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value text-red-600" id="total-expenses">Rp 0</div>
                            <div class="text-sm text-gray-600">Total Pengeluaran Kas</div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-value text-blue-600" id="net-cashflow">Rp 0</div>
                            <div class="text-sm text-gray-600">Arus Kas Bersih</div>
                        </div>
                    </div>

                    <div class="mb-3 p-3 bg-blue-50 border-l-4 border-blue-400 rounded">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Catatan:</strong> Laporan arus kas menampilkan transaksi kas masuk dan keluar aktual
                            saja.
                            Tidak termasuk perhitungan beban gaji karyawan yang belum dibayar.
                        </p>
                    </div>

                    <div class="overflow-x-auto table-container">
                        <table id="cashflow-table" class="min-w-full">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keterangan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Karyawan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pemasukan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pengeluaran</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Profit Loss Tab -->
            @if (auth()->user()->role !== 'karyawan')
                <div id="profitloss-content" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Laporan Laba Rugi</h3>
                        <button onclick="downloadPDF('profit-loss')" class="btn-download">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </button>
                    </div>

                    <div class="mb-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Catatan:</strong> Laporan laba rugi menghitung beban gaji karyawan (35% dari pendapatan)
                            sebagai kewajiban perusahaan. Total beban & pengeluaran mencakup semua pengeluaran kas aktual
                            ditambah beban gaji yang belum dibayar.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-lg mb-3">Pendapatan</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Total Pendapatan:</span>
                                    <span class="font-bold text-green-600" id="profit-revenue">Rp 0</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg mb-3">Beban & Pengeluaran</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Beban Gaji Karyawan (35%):</span>
                                    <span class="font-bold text-red-600" id="employee-costs">Rp 0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Pengeluaran Operasional:</span>
                                    <span class="font-bold text-red-600" id="operational-expenses">Rp 0</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="font-semibold">Total Beban & Pengeluaran:</span>
                                    <span class="font-bold text-red-600" id="total-expenses-profit">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold">Laba Bersih:</span>
                            <span class="text-2xl font-bold" id="net-profit">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm text-gray-600">Margin Keuntungan:</span>
                            <span class="text-sm font-semibold" id="profit-margin">0%</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Service Revenue Tab -->
            @if (auth()->user()->role !== 'karyawan')
                <div id="service-content" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Pendapatan per Layanan</h3>
                        <button onclick="downloadPDF('service-revenue')" class="btn-download">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </button>
                    </div>

                    <div class="mb-4">
                        <div class="metric-card inline-block">
                            <div class="metric-value text-green-600" id="service-total-revenue">Rp 0</div>
                            <div class="text-sm text-gray-600">Total Pendapatan Layanan</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto table-container">
                        <table id="service-table" class="min-w-full">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Layanan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pendapatan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Transaksi</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Persentase</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rata-rata per Transaksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Transaction Report Tab -->
            @if (auth()->user()->role !== 'karyawan')
                <div id="transaction-content" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Laporan Transaksi</h3>
                        <div class="flex gap-2">
                            <select id="period-select" class="border border-gray-300 rounded px-3 py-2">
                                <option value="daily">Harian</option>
                                <option value="monthly">Bulanan</option>
                            </select>
                            <button onclick="loadTransactionReport()" class="btn-secondary">
                                <i class="fas fa-refresh mr-2"></i>Update
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto table-container">
                        <table id="transaction-report-table" class="min-w-full">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Periode</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pemasukan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pengeluaran</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Arus Kas Bersih</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Transaksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if (auth()->user()->role === 'admin')
                <!-- Salary Report Tab -->
                <div id="salary-content" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Laporan Gaji Karyawan</h3>
                        <div class="flex gap-3 items-center">
                            <form id="salary-filter-form" class="flex gap-3 items-center">
                                <label for="salary-month" class="font-semibold text-gray-700">Bulan:</label>
                                <input type="month" id="salary-month" name="month"
                                    class="border border-gray-300 rounded px-3 py-2" value="{{ date('Y-m') }}">
                                <button type="submit" class="btn-cta">
                                    <i class="fas fa-filter mr-2"></i>Filter
                                </button>
                            </form>
                            <button onclick="downloadSalaryReportPDF()" class="btn-download">
                                <i class="fas fa-download mr-2"></i>Download PDF
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto table-container">
                        <table id="salary-table" class="min-w-full">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Karyawan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nomor Rekening</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Pemasukan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Gaji (35%)</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Detail Layanan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <!-- Employee Salary Slip Tab -->
                <div id="salary-content" class="tab-content @if (auth()->user()->role === 'karyawan') active @endif">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Slip Gaji Saya</h3>
                        <div class="flex gap-3 items-center">
                            <form id="salary-filter-form" class="flex gap-3 items-center">
                                <label for="salary-month" class="font-semibold text-gray-700">Bulan:</label>
                                <input type="month" id="salary-month" name="month"
                                    class="border border-gray-300 rounded px-3 py-2" value="{{ date('Y-m') }}">
                                <button type="submit" class="btn-cta">
                                    <i class="fas fa-filter mr-2"></i>Filter
                                </button>
                            </form>
                            <button onclick="downloadSalarySlipPDF()" class="btn-download">
                                <i class="fas fa-download mr-2"></i>Download PDF
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto table-container">
                        <table id="salary-table" class="min-w-full">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Karyawan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nomor Rekening</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Pemasukan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Gaji (35%)</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Detail Layanan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery & DataTables CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

    <script>
        const userRole = "{{ auth()->user()->role }}";
        let cashflowTable, serviceTable, transactionTable, salaryTable;

        $(document).ready(function() {
            // Initialize tables
            initializeTables();

            // Load initial data
            updateAllReports();

            // Salary report filter (both admin and employee)
            $('#salary-filter-form').on('submit', function(e) {
                e.preventDefault();
                loadSalaryReport();
            });
        });

        function initializeTables() {
            // Common DataTable configuration
            const commonConfig = {
                responsive: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                language: getDataTableLanguage(),
                dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4"<"mb-2 sm:mb-0"l><"mb-2 sm:mb-0"f>>rtip',
                columnDefs: [{
                    targets: '_all',
                    className: 'px-4 py-3 text-sm text-gray-900'
                }]
            };

            // Cash Flow Table
            if (userRole !== 'karyawan') {
                cashflowTable = $('#cashflow-table').DataTable({
                    ...commonConfig,
                    data: [],
                    columns: [{
                            data: 'transaction_date',
                            render: function(data) {
                                return data ? new Date(data).toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                }) : '-';
                            }
                        },
                        {
                            data: null,
                            render: function(row) {
                                return row.service ? row.service.name : (row.description || 'Pengeluaran');
                            }
                        },
                        {
                            data: 'user',
                            render: function(user) {
                                return user ? user.name : '-';
                            }
                        },
                        {
                            data: null,
                            render: function(row) {
                                return row.type === 'income' ?
                                    `<span class="font-semibold text-green-600">${formatCurrency(row.amount)}</span>` :
                                    '-';
                            },
                            className: 'text-right'
                        },
                        {
                            data: null,
                            render: function(row) {
                                return row.type === 'expense' ?
                                    `<span class="font-semibold text-red-600">${formatCurrency(row.amount)}</span>` :
                                    '-';
                            },
                            className: 'text-right'
                        }
                    ],
                    order: [
                        [0, 'desc']
                    ]
                });
            }

            // Service Revenue Table
            if (userRole !== 'karyawan') {
                serviceTable = $('#service-table').DataTable({
                    ...commonConfig,
                    data: [],
                    columns: [{
                            data: 'service_name'
                        },
                        {
                            data: 'revenue',
                            render: function(data) {
                                return `<span class="font-semibold text-green-600">${formatCurrency(data)}</span>`;
                            },
                            className: 'text-right'
                        },
                        {
                            data: 'transaction_count',
                            className: 'text-center'
                        },
                        {
                            data: 'percentage',
                            render: function(data) {
                                return `<span class="font-medium">${data.toFixed(1)}%</span>`;
                            },
                            className: 'text-center'
                        },
                        {
                            data: 'average_per_transaction',
                            render: function(data) {
                                return formatCurrency(data);
                            },
                            className: 'text-right'
                        }
                    ],
                    order: [
                        [1, 'desc']
                    ]
                });
            }

            // Transaction Report Table
            if (userRole !== 'karyawan') {
                transactionTable = $('#transaction-report-table').DataTable({
                    ...commonConfig,
                    data: [],
                    columns: [{
                            data: 'period'
                        },
                        {
                            data: 'income',
                            render: function(data) {
                                return `<span class="font-semibold text-green-600">${formatCurrency(data)}</span>`;
                            },
                            className: 'text-right'
                        },
                        {
                            data: 'expenses',
                            render: function(data) {
                                return `<span class="font-semibold text-red-600">${formatCurrency(data)}</span>`;
                            },
                            className: 'text-right'
                        },
                        {
                            data: 'net',
                            render: function(data) {
                                const colorClass = data >= 0 ? 'text-green-600' : 'text-red-600';
                                return `<span class="font-semibold ${colorClass}">${formatCurrency(data)}</span>`;
                            },
                            className: 'text-right'
                        },
                        {
                            data: 'transaction_count',
                            className: 'text-center'
                        }
                    ],
                    order: [
                        [0, 'desc']
                    ]
                });
            }

            // Salary Table (both admin and employee)
            salaryTable = $('#salary-table').DataTable({
                ...commonConfig,
                ajax: {
                    url: '{{ route('laporan.data') }}',
                    data: function(d) {
                        d.month = $('#salary-month').val();
                    },
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'bank_account',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'total_income',
                        render: function(data) {
                            return `<span class='font-semibold text-green-600'>${data}</span>`;
                        },
                        className: 'text-right'
                    },
                    {
                        data: 'salary',
                        render: function(data) {
                            return `<span class='font-semibold text-blue-600'>${data}</span>`;
                        },
                        className: 'text-right'
                    },
                    {
                        data: 'service_breakdown',
                        render: function(data, type, row) {
                            if (!data || data.length === 0) {
                                return '<em>Tidak ada layanan</em>';
                            }

                            let breakdownHtml = '<div class="service-breakdown" style="font-size: 11px;">';
                            data.forEach(function(service) {
                                breakdownHtml +=
                                    `<div style="margin-bottom: 3px; padding: 2px 4px; background: #f8f9fa; border-radius: 3px;">`;
                                breakdownHtml +=
                                    `<strong>${service.service_name}</strong>: ${service.percentage.toFixed(1)}% `;
                                breakdownHtml +=
                                `(Gaji: ${formatCurrency(service.service_salary)})`;
                                breakdownHtml += `</div>`;
                            });
                            breakdownHtml += '</div>';
                            return breakdownHtml;
                        }
                    }
                ]
            });
        }

        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabName + '-content').classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }

        function updateAllReports() {
            if (userRole === 'karyawan') {
                // Only update salary report/slip if needed, though it's usually loaded via DataTable ajax
                return;
            }
            loadCashFlowReport();
            loadProfitLossReport();
            loadServiceRevenueReport();
            loadTransactionReport();
        }

        function loadCashFlowReport() {
            const startDate = $('#start-date').val();
            const endDate = $('#end-date').val();

            // Show loading state
            $('#total-income, #total-expenses, #net-cashflow').text('Loading...');

            $.get('{{ route('laporan.cash-flow') }}', {
                    start_date: startDate,
                    end_date: endDate
                })
                .done(function(data) {
                    // Update summary metrics
                    $('#total-income').text(formatCurrency(data.summary.total_income));
                    $('#total-expenses').text(formatCurrency(data.summary.total_expenses));
                    $('#net-cashflow').text(formatCurrency(data.summary.net_cash_flow));

                    // Update net cashflow color
                    const netElement = $('#net-cashflow');
                    if (data.summary.net_cash_flow >= 0) {
                        netElement.removeClass('text-red-600').addClass('text-green-600');
                    } else {
                        netElement.removeClass('text-green-600').addClass('text-red-600');
                    }

                    // Update table
                    cashflowTable.clear().rows.add(data.transactions).draw();
                })
                .fail(function(xhr, status, error) {
                    console.error('Cash flow report error:', error);
                    $('#total-income, #total-expenses, #net-cashflow').text('Error');
                    showAlert('Error loading cash flow report: ' + error, 'error');
                });
        }

        function loadProfitLossReport() {
            const startDate = $('#start-date').val();
            const endDate = $('#end-date').val();

            // Show loading state
            $('#profit-revenue, #employee-costs, #operational-expenses, #total-expenses-profit, #net-profit, #profit-margin')
                .text('Loading...');

            $.get('{{ route('laporan.profit-loss') }}', {
                    start_date: startDate,
                    end_date: endDate
                })
                .done(function(data) {
                    $('#profit-revenue').text(formatCurrency(data.revenue));
                    $('#employee-costs').text(formatCurrency(data.employee_costs));
                    $('#operational-expenses').text(formatCurrency(data.operational_expenses));
                    $('#total-expenses-profit').text(formatCurrency(data.total_expenses));
                    $('#net-profit').text(formatCurrency(data.net_profit));
                    $('#profit-margin').text(data.profit_margin.toFixed(1) + '%');

                    // Color the net profit based on positive/negative
                    const profitElement = $('#net-profit');
                    if (data.net_profit >= 0) {
                        profitElement.removeClass('text-red-600').addClass('text-green-600');
                    } else {
                        profitElement.removeClass('text-green-600').addClass('text-red-600');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('Profit loss report error:', error);
                    $('#profit-revenue, #employee-costs, #operational-expenses, #total-expenses-profit, #net-profit, #profit-margin')
                        .text('Error');
                    showAlert('Error loading profit loss report: ' + error, 'error');
                });
        }

        function loadServiceRevenueReport() {
            const startDate = $('#start-date').val();
            const endDate = $('#end-date').val();

            // Show loading state
            $('#service-total-revenue').text('Loading...');

            $.get('{{ route('laporan.service-revenue') }}', {
                    start_date: startDate,
                    end_date: endDate
                })
                .done(function(data) {
                    $('#service-total-revenue').text(formatCurrency(data.total_revenue));
                    serviceTable.clear().rows.add(data.services).draw();
                })
                .fail(function(xhr, status, error) {
                    console.error('Service revenue report error:', error);
                    $('#service-total-revenue').text('Error');
                    showAlert('Error loading service revenue report: ' + error, 'error');
                });
        }

        function loadTransactionReport() {
            const startDate = $('#start-date').val();
            const endDate = $('#end-date').val();
            const period = $('#period-select').val();

            $.get('{{ route('laporan.transaction-report') }}', {
                    start_date: startDate,
                    end_date: endDate,
                    period: period
                })
                .done(function(data) {
                    const reportData = Object.values(data.report_data);
                    transactionTable.clear().rows.add(reportData).draw();
                })
                .fail(function(xhr, status, error) {
                    console.error('Transaction report error:', error);
                    showAlert('Error loading transaction report: ' + error, 'error');
                });
        }

        // Load salary report (both admin and employee)
        function loadSalaryReport() {
            salaryTable.ajax.reload();
        }

        function downloadPDF(reportType) {
            const startDate = $('#start-date').val();
            const endDate = $('#end-date').val();
            const url = `{{ url('/laporan/download') }}/${reportType}?start_date=${startDate}&end_date=${endDate}`;
            window.open(url, '_blank');
        }

        function downloadSalaryReportPDF() {
            const month = $('#salary-month').val();
            const url = `{{ route('laporan.download.salary-report') }}?month=${month}`;
            window.open(url, '_blank');
        }

        @if (auth()->user()->role === 'karyawan')
            function downloadSalarySlipPDF() {
                const month = $('#salary-month').val();
                const url = `{{ route('laporan.download.salary-slip') }}?month=${month}`;
                window.open(url, '_blank');
            }
        @endif

        function formatCurrency(amount) {
            if (isNaN(amount) || amount === null || amount === undefined) {
                return 'Rp 0';
            }

            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        function showAlert(message, type = 'info') {
            // Simple alert for now - can be enhanced with SweetAlert or custom modal
            console.log(`[${type.toUpperCase()}] ${message}`);

            // Create a simple notification
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
                    type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
                        'bg-blue-100 text-blue-800 border border-blue-200'
                }`;
            alertDiv.innerHTML = `
            <div class="flex items-center">
                <div class="flex-1">${message}</div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-lg">&times;</button>
            </div>
        `;

            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        function getDataTableLanguage() {
            return {
                processing: "Sedang memproses...",
                search: "Cari:",
                searchPlaceholder: "Ketik untuk mencari...",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ total entri)",
                loadingRecords: "Sedang memuat...",
                zeroRecords: "Tidak ada data yang cocok ditemukan",
                emptyTable: "Tidak ada data yang tersedia pada tabel",
                paginate: {
                    first: "Pertama",
                    previous: "Sebelumnya",
                    next: "Selanjutnya",
                    last: "Terakhir"
                },
                aria: {
                    sortAscending: ": aktifkan untuk mengurutkan kolom secara ascending",
                    sortDescending: ": aktifkan untuk mengurutkan kolom secara descending"
                }
            };
        }
    </script>
@endpush
