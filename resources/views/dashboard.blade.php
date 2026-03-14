@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <style>
        /* Base reset and container fixes */
        * {
            box-sizing: border-box;
        }

        /* Remove conflicting layout styles */
        .container {
            max-width: 100%;
            width: 100%;
            margin: 0 auto;
            padding-left: 0;
            padding-right: 0;
        }

        /* Main content wrapper fixes */
        .main-content {
            width: 100%;
            height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        /* Animations */
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

        /* Card hover effects with safe transforms */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            will-change: transform, box-shadow;
            position: relative;
            z-index: 1;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        .card-hover:hover {
            transform: translateY(-3px) translateZ(0);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            z-index: 5;
            /* Reduced from 10 to prevent dropdown overlap */
        }

        /* Metric cards with proper positioning */
        .metric-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
            min-height: 120px;
            display: flex;
            align-items: center;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
            pointer-events: none;
        }

        .metric-card:hover::before {
            left: 100%;
        }

        .metric-card.income {
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
        }

        .metric-card.expense {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        }

        .metric-card.profit {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        }

        .metric-card.employees {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .activity-dot {
            width: 8px;
            height: 8px;
        }

        .activity-line {
            width: 2px;
            background: #e5e7eb;
        }

        /* Real-time indicator */
        .live-indicator {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Health indicator colors */
        .health-excellent {
            color: #059669;
        }

        .health-good {
            color: #22c55e;
        }

        .health-warning {
            color: #f59e0b;
        }

        .health-danger {
            color: #ef4444;
        }

        /* Chart container with proper height */
        .chart-container {
            position: relative;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 0.75rem;
            padding: 1rem;
            height: 350px;
            overflow: hidden;
        }

        /* Analytics cards with proper spacing */
        .analytics-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .analytics-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #22c55e;
        }

        /* Performance indicators with safe positioning */
        .performance-ring {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto;
            flex-shrink: 0;
        }

        .performance-ring svg {
            transform: rotate(-90deg);
            width: 100%;
            height: 100%;
        }

        .performance-ring .bg-circle {
            fill: none;
            stroke: #e5e7eb;
            stroke-width: 8;
        }

        .performance-ring .progress-circle {
            fill: none;
            stroke-width: 8;
            stroke-linecap: round;
            transition: stroke-dasharray 1s ease;
        }

        /* Grid layout fixes */
        .grid {
            display: grid;
            gap: 1.5rem;
            width: 100%;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        /* Responsive grid fixes */
        @media (min-width: 768px) {
            .md\\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .md\\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .lg\\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .lg\\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .lg\\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .lg\\:col-span-2 {
                grid-column: span 2 / span 2;
            }
        }

        @media (min-width: 1280px) {
            .xl\\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .xl\\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        /* Trend indicators */
        .trend-up {
            color: #22c55e;
        }

        .trend-down {
            color: #ef4444;
        }

        .trend-neutral {
            color: #6b7280;
        }

        /* Mini charts */
        .mini-chart {
            height: 40px;
            width: 100%;
            overflow: hidden;
        }

        /* Loading animations */
        .loading-shimmer {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .status-excellent {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-good {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Content spacing fixes */
        .space-y-4>*+* {
            margin-top: 1rem;
        }

        .space-y-6>*+* {
            margin-top: 1.5rem;
        }

        /* Flexbox fixes */
        .flex {
            display: flex;
        }

        .flex-col {
            flex-direction: column;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .flex-1 {
            flex: 1 1 0%;
        }

        .flex-shrink-0 {
            flex-shrink: 0;
        }

        /* Text overflow fixes */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Mobile responsive fixes */
        @media (max-width: 768px) {
            .main-content {
                padding: 0;
            }

            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .grid {
                gap: 1rem;
            }

            .card-hover:hover {
                transform: translateY(-2px) translateZ(0);
            }

            .metric-card {
                min-height: 100px;
            }

            .chart-container {
                height: 300px;
                padding: 0.75rem;
            }

            /* Stack elements on mobile */
            .md\\:flex-row {
                flex-direction: column;
            }

            .md\\:items-center {
                align-items: flex-start;
            }

            .md\\:justify-between {
                justify-content: flex-start;
            }

            /* Hide overflow on mobile */
            .analytics-card {
                overflow-x: hidden;
            }

            /* Prevent horizontal scroll on small screens */
            .text-4xl {
                font-size: 2rem;
                line-height: 2.5rem;
            }

            /* Adjust spacing for mobile */
            .space-x-4>*+* {
                margin-left: 0.5rem;
            }

            /* Responsive text sizing */
            .status-badge {
                font-size: 0.7rem;
                padding: 0.2rem 0.5rem;
            }
        }

        /* Ensure proper scrolling and prevent conflicts */
        .main-content {
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
        }

        /* Fix z-index issues and prevent overlap */
        .metric-card {
            z-index: 1;
            isolation: isolate;
        }

        .analytics-card {
            z-index: 1;
            isolation: isolate;
        }

        .card-hover:hover {
            z-index: 5;
            /* Reduced to prevent dropdown overlap */
            isolation: isolate;
        }

        /* Prevent transform issues on mobile */
        @media (max-width: 1024px) {
            .card-hover:hover {
                transform: none;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            .metric-card:hover::before {
                left: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <div class="container mx-auto py-8 fade-in-up">
            <!-- Welcome Section -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-2">
                            Dashboard Analitik Keuangan
                            <span class="live-indicator inline-flex items-center ml-3">
                                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                <span class="text-sm font-normal text-green-600">Live</span>
                            </span>
                        </h1>
                        <p class="text-gray-600">
                            Pemantauan real-time performa bisnis dan kesehatan keuangan perusahaan
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex items-center space-x-4">
                        <!-- Business Health Status -->
                        @php
                            $healthScore = 0;
                            if ($currentMonthIncome > 0) {
                                $healthScore += 25;
                                if ($profitMargin > 20) {
                                    $healthScore += 25;
                                } elseif ($profitMargin > 10) {
                                    $healthScore += 15;
                                } elseif ($profitMargin > 0) {
                                    $healthScore += 10;
                                }

                                if ($incomeChange > 10) {
                                    $healthScore += 25;
                                } elseif ($incomeChange > 0) {
                                    $healthScore += 15;
                                } elseif ($incomeChange >= -5) {
                                    $healthScore += 10;
                                }

                                if ($expenseChange < 5) {
                                    $healthScore += 25;
                                } elseif ($expenseChange < 15) {
                                    $healthScore += 15;
                                } elseif ($expenseChange < 25) {
                                    $healthScore += 10;
                                }
                            }

                            $healthStatus = 'danger';
                            $healthText = 'Memerlukan Perhatian';
                            $healthColor = '#ef4444';

                            if ($healthScore >= 80) {
                                $healthStatus = 'excellent';
                                $healthText = 'Sangat Sehat';
                                $healthColor = '#059669';
                            } elseif ($healthScore >= 60) {
                                $healthStatus = 'good';
                                $healthText = 'Sehat';
                                $healthColor = '#22c55e';
                            } elseif ($healthScore >= 40) {
                                $healthStatus = 'warning';
                                $healthText = 'Perlu Pengawasan';
                                $healthColor = '#f59e0b';
                            }
                        @endphp

                        <!-- Real-time update indicator -->
                        <div class="text-xs text-gray-500">
                            Update terakhir: <span data-realtime-clock></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 {{ auth()->user()->role === 'admin' ? 'xl:grid-cols-4' : 'xl:grid-cols-3' }} gap-6 mb-8">
                <!-- Total Income Card -->
                <div class="metric-card income rounded-xl p-6 text-white card-hover"
                    style="background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%) !important; background-color: #22c55e !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Pemasukan</p>
                            <p class="text-3xl font-bold mb-1">{{ 'Rp ' . number_format($currentMonthIncome, 0, ',', '.') }}
                            </p>
                            <div class="flex items-center text-sm">
                                @if ($incomeChange == 0 && $currentMonthIncome == 0)
                                    <i class="fas fa-minus mr-1"></i>
                                    <span>Belum ada data</span>
                                @else
                                    <i class="fas fa-arrow-{{ $incomeChange >= 0 ? 'up' : 'down' }} mr-1"></i>
                                    <span>{{ number_format(abs($incomeChange), 1) }}% dari bulan lalu</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-arrow-trend-up text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Expense Card -->
                <div class="metric-card expense rounded-xl p-6 text-white card-hover"
                    style="background: linear-gradient(135deg, #f87171 0%, #ef4444 100%) !important; background-color: #ef4444 !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Pengeluaran</p>
                            <p class="text-3xl font-bold mb-1">
                                {{ 'Rp ' . number_format($currentMonthExpense, 0, ',', '.') }}</p>
                            <div class="flex items-center text-sm">
                                @if ($expenseChange == 0 && $currentMonthExpense == 0)
                                    <i class="fas fa-minus mr-1"></i>
                                    <span>Belum ada data</span>
                                @else
                                    <i class="fas fa-arrow-{{ $expenseChange >= 0 ? 'up' : 'down' }} mr-1"></i>
                                    <span>{{ number_format(abs($expenseChange), 1) }}% dari bulan lalu</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-arrow-trend-down text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Net Profit Card -->
                <div class="metric-card profit rounded-xl p-6 text-white card-hover"
                    style="background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%) !important; background-color: #3b82f6 !important;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Laba Bersih</p>
                            <p class="text-3xl font-bold mb-1">{{ 'Rp ' . number_format($netProfit, 0, ',', '.') }}</p>
                            <div class="flex items-center text-sm">
                                @if ($profitChange == 0 && $netProfit == 0)
                                    <i class="fas fa-minus mr-1"></i>
                                    <span>Belum ada data</span>
                                @else
                                    <i class="fas fa-arrow-{{ $profitChange >= 0 ? 'up' : 'down' }} mr-1"></i>
                                    <span>{{ number_format(abs($profitChange), 1) }}% dari bulan lalu</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Employee Count Card - Only visible to admins -->
                @if (auth()->user()->role === 'admin')
                    <div class="metric-card employees rounded-xl p-6 text-white card-hover"
                        style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; background-color: #f59e0b !important;">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm opacity-90">Total Karyawan</p>
                                <p class="text-3xl font-bold mb-1">{{ $employeeCount }}</p>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-users mr-1"></i>
                                    <span>Gaji: Rp {{ number_format($totalSalaries, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-20 p-3 rounded-lg flex-shrink-0">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            {{-- 
    <!-- Performance Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Business Performance Ring -->
        <div class="analytics-card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Performa Bisnis</h3>
            <div class="flex items-center justify-center">
                <div class="performance-ring">
                    <svg width="80" height="80">
                        <circle cx="40" cy="40" r="32" class="bg-circle"></circle>
                        <circle cx="40" cy="40" r="32" class="progress-circle" 
                                style="stroke: {{ $healthColor }}; stroke-dasharray: {{ ($healthScore/100) * 201 }} 201;"></circle>
                    </svg>
                </div>
            </div>
            <p class="text-center text-sm text-gray-600 mt-2">{{ $healthText }}</p>
        </div>

        <!-- Cash Flow Analysis -->
        <div class="analytics-card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Analisis Arus Kas</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Rasio Kas</span>
                    @php
                        $cashRatio = $currentMonthExpense > 0 ? $currentMonthIncome / $currentMonthExpense : 0;
                        $cashRatioColor = $cashRatio >= 1.5 ? 'text-green-600' : ($cashRatio >= 1.2 ? 'text-yellow-600' : 'text-red-600');
                    @endphp
                    <span class="font-semibold {{ $cashRatioColor }}">{{ number_format($cashRatio, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Margin Operasi</span>
                    <span class="font-semibold {{ $profitMargin > 15 ? 'text-green-600' : ($profitMargin > 5 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($profitMargin, 1) }}%
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Efisiensi Biaya</span>
                    @php
                        $costEfficiency = $currentMonthIncome > 0 ? (($currentMonthIncome - $currentMonthExpense) / $currentMonthIncome) * 100 : 0;
                    @endphp
                    <span class="font-semibold {{ $costEfficiency > 20 ? 'text-green-600' : ($costEfficiency > 10 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($costEfficiency, 1) }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Growth Metrics -->
        <div class="analytics-card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Metrik Pertumbuhan</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pertumbuhan Pendapatan</span>
                    <div class="flex items-center">
                        <i class="fas fa-arrow-{{ $incomeChange >= 0 ? 'up' : 'down' }} text-xs mr-1 {{ $incomeChange >= 0 ? 'text-green-600' : 'text-red-600' }}"></i>
                        <span class="font-semibold {{ $incomeChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format(abs($incomeChange), 1) }}%
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Efektivitas Biaya</span>
                    <div class="flex items-center">
                        <i class="fas fa-arrow-{{ $expenseChange <= 0 ? 'down' : 'up' }} text-xs mr-1 {{ $expenseChange <= 0 ? 'text-green-600' : 'text-red-600' }}"></i>
                        <span class="font-semibold {{ $expenseChange <= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format(abs($expenseChange), 1) }}%
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">ROI Bulanan</span>
                    @php
                        $monthlyROI = $currentMonthExpense > 0 ? (($netProfit / $currentMonthExpense) * 100) : 0;
                    @endphp
                    <span class="font-semibold {{ $monthlyROI > 30 ? 'text-green-600' : ($monthlyROI > 15 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($monthlyROI, 1) }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Real-time Alerts -->
        <div class="analytics-card p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Notifikasi Real-time</h3>
            <div class="space-y-2">
                @if ($profitMargin < 5)
                    <div class="flex items-center p-2 bg-red-50 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-xs text-red-700">Margin keuntungan rendah</span>
                    </div>
                @endif
                
                @if ($incomeChange < -10)
                    <div class="flex items-center p-2 bg-orange-50 rounded-lg">
                        <i class="fas fa-trending-down text-orange-500 mr-2"></i>
                        <span class="text-xs text-orange-700">Penurunan pendapatan signifikan</span>
                    </div>
                @endif
                
                @if ($expenseChange > 20)
                    <div class="flex items-center p-2 bg-yellow-50 rounded-lg">
                        <i class="fas fa-chart-line text-yellow-500 mr-2"></i>
                        <span class="text-xs text-yellow-700">Kenaikan biaya tinggi</span>
                    </div>
                @endif
                
                @if ($profitMargin > 20 && $incomeChange > 10)
                    <div class="flex items-center p-2 bg-green-50 rounded-lg">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-xs text-green-700">Performa excellent!</span>
                    </div>
                @endif
                
                @if ($profitMargin < 5 && $incomeChange < -10 && $expenseChange < 20)
                    <div class="text-center py-4">
                        <i class="fas fa-shield-alt text-2xl text-gray-400 mb-2"></i>
                        <p class="text-xs text-gray-500">Semua indikator normal</p>
                    </div>
                @endif
            </div>
        </div>
    </div> --}}

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Enhanced Revenue Trend Chart -->
                <div class="lg:col-span-2 analytics-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">Analisis Tren Keuangan</h3>
                        <div class="flex items-center space-x-2">
                            <button
                                class="chart-period-btn active px-3 py-1 text-xs rounded-full bg-green-100 text-green-700"
                                data-period="6">6 Bulan</button>
                            <button class="chart-period-btn px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600"
                                data-period="12">1 Tahun</button>
                        </div>
                    </div>
                    <div class="chart-container" style="height: 350px;">
                        <canvas id="revenueChart"></canvas>
                    </div>

                    <!-- Chart insights -->
                    <div class="mt-4 grid grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Rata-rata Pendapatan</p>
                            <p class="font-semibold text-green-600">
                                Rp {{ number_format(collect($chartData['income'])->avg(), 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Rata-rata Pengeluaran</p>
                            <p class="font-semibold text-red-600">
                                Rp {{ number_format(collect($chartData['expenses'])->avg(), 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Tren Keuntungan</p>
                            @php
                                $avgProfit =
                                    collect($chartData['income'])->avg() - collect($chartData['expenses'])->avg();
                            @endphp
                            <p class="font-semibold {{ $avgProfit > 0 ? 'text-blue-600' : 'text-red-600' }}">
                                {{ $avgProfit > 0 ? '+' : '' }}Rp {{ number_format($avgProfit, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Top Services with Analytics -->
                <div class="analytics-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">Analisis Layanan</h3>
                        <span class="text-xs text-gray-500">Top {{ $topServices->count() }} Layanan</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($topServices as $index => $service)
                            <div
                                class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg hover:from-green-50 hover:to-green-100 transition-all">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gradient-to-r {{ $index == 0 ? 'from-yellow-400 to-yellow-600' : ($index == 1 ? 'from-gray-300 to-gray-500' : 'from-orange-400 to-orange-600') }} flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">{{ $service->name }}</p>
                                        <div class="flex items-center space-x-2 text-xs text-gray-600">
                                            <span>{{ $service->transactions_count }} transaksi</span>
                                            <span>•</span>
                                            <span>Avg: Rp
                                                {{ number_format($service->transactions_count > 0 ? $service->total_amount / $service->transactions_count : 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">Rp
                                        {{ number_format($service->total_amount, 0, ',', '.') }}</p>
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                                        @php
                                            $maxAmount = $topServices->max('total_amount');
                                            $progressWidth =
                                                $maxAmount > 0
                                                    ? min(100, ($service->total_amount / $maxAmount) * 100)
                                                    : 0;
                                        @endphp
                                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full transition-all duration-1000"
                                            style="width: {{ $progressWidth }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $topServices->sum('total_amount') > 0 ? number_format(($service->total_amount / $topServices->sum('total_amount')) * 100, 1) : 0 }}%
                                        dari total
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-chart-bar text-3xl mb-2"></i>
                                <p>Belum ada data layanan</p>
                            </div>
                        @endforelse

                        @if ($topServices->count() > 0)
                            <!-- Service performance insights -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <p class="text-xs text-gray-500">Layanan Terbaik</p>
                                        <p class="font-semibold text-green-600 text-sm">
                                            {{ $topServices->first()->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Total Revenue</p>
                                        <p class="font-semibold text-blue-600 text-sm">Rp
                                            {{ number_format($topServices->sum('total_amount'), 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Enhanced Activity and Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                <!-- Enhanced Recent Transactions with Analytics -->
                <div class="lg:col-span-2 analytics-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">Aktivitas Transaksi Real-time</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                <span>Pemasukan</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                <span>Pengeluaran</span>
                            </div>
                            <a href="/transaksi"
                                class="text-blue-600 hover:text-blue-800 font-medium transition-colors text-sm">
                                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Transaction timeline -->
                    <div class="space-y-4">
                        @forelse($recentTransactions as $transaction)
                            <div
                                class="flex items-center space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-all border-l-4 {{ $transaction->type === 'income' ? 'border-green-500 hover:border-green-600' : 'border-red-500 hover:border-red-600' }}">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-full flex items-center justify-center transition-all
                                    {{ $transaction->type === 'income' ? 'bg-green-100 text-green-600 hover:bg-green-200' : 'bg-red-100 text-red-600 hover:bg-red-200' }}">
                                        <i
                                            class="fas fa-{{ $transaction->type === 'income' ? 'arrow-up' : 'arrow-down' }}"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <p class="text-gray-800 font-medium truncate">{{ $transaction->description }}
                                            </p>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $transaction->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                <i
                                                    class="fas fa-{{ $transaction->type === 'income' ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                                                {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-gray-500 ml-2"
                                            data-relative-time="{{ $transaction->created_at->toIso8601String() }}">
                                            {{ $transaction->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                                            <span>{{ $transaction->created_at->format('d M Y') }}</span>
                                            @if ($transaction->service)
                                                <span>•</span>
                                                <span>{{ $transaction->service->name }}</span>
                                            @endif
                                            @if (auth()->user()->role === 'admin' && $transaction->user)
                                                <span>•</span>
                                                <span>{{ $transaction->user->name }}</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p
                                                class="font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $transaction->type === 'income' ? '+' : '-' }}Rp
                                                {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-receipt text-3xl mb-2"></i>
                                <p>Belum ada transaksi hari ini</p>
                                <a href="/transaksi/create"
                                    class="inline-flex items-center mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-plus mr-1"></i>
                                    Tambah Transaksi Pertama
                                </a>
                            </div>
                        @endforelse
                    </div>

                    @if ($recentTransactions->count() > 0)
                        <!-- Transaction summary -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-xs text-gray-500">Transaksi Hari Ini</p>
                                    <p class="font-semibold text-gray-800">{{ $todayTransactionCount }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Rata-rata Transaksi</p>
                                    @php
                                        $avgTransaction =
                                            $recentTransactions->count() > 0 ? $recentTransactions->avg('amount') : 0;
                                    @endphp
                                    <p class="font-semibold text-blue-600">
                                        Rp {{ number_format($avgTransaction, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Trend Mingguan</p>
                                    @php
                                        $weeklyTrend = rand(-15, 25); // Placeholder for actual weekly trend calculation
                                    @endphp
                                    <p class="font-semibold {{ $weeklyTrend > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $weeklyTrend > 0 ? '+' : '' }}{{ $weeklyTrend }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div> <!-- Close container -->
    </div> <!-- Close main-content -->
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced Revenue Chart with better styling and interactivity
            const ctx = document.getElementById('revenueChart').getContext('2d');

            // Chart configuration
            const chartConfig = {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [{
                        label: 'Pemasukan',
                        data: {!! json_encode($chartData['income']) !!},
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(34, 197, 94)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }, {
                        label: 'Pengeluaran',
                        data: {!! json_encode($chartData['expenses']) !!},
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'rgb(239, 68, 68)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }, {
                        label: 'Keuntungan',
                        data: {!! json_encode(
                            array_map(
                                function ($income, $expense) {
                                    return $income - $expense;
                                },
                                $chartData['income'],
                                $chartData['expenses'],
                            ),
                        ) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.05)',
                        tension: 0.4,
                        fill: false,
                        borderDash: [5, 5],
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.parsed.y
                                        .toLocaleString('id-ID');
                                },
                                footer: function(tooltipItems) {
                                    let income = tooltipItems.find(item => item.datasetIndex === 0)?.parsed
                                        .y || 0;
                                    let expense = tooltipItems.find(item => item.datasetIndex === 1)?.parsed
                                        .y || 0;
                                    let profit = income - expense;
                                    return 'Net Profit: Rp ' + profit.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                },
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        line: {
                            borderWidth: 3
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutCubic'
                    }
                }
            };

            const revenueChart = new Chart(ctx, chartConfig);

            // Chart period switching functionality
            document.querySelectorAll('.chart-period-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.chart-period-btn').forEach(b => {
                        b.classList.remove('active', 'bg-green-100', 'text-green-700');
                        b.classList.add('bg-gray-100', 'text-gray-600');
                    });

                    // Add active class to clicked button
                    this.classList.add('active', 'bg-green-100', 'text-green-700');
                    this.classList.remove('bg-gray-100', 'text-gray-600');

                    const period = this.dataset.period;
                    // Here you would typically fetch new data based on the period
                    console.log('Switching to period:', period, 'months');

                    // For now, just animate the chart
                    revenueChart.update('active');
                });
            });

            // Realtime clock sudah dihandle oleh realtime-clock.js

            // Smooth counter animation for metrics
            function animateValue(element, start, end, duration) {
                if (!element) return;

                const range = end - start;
                const increment = end > start ? 1 : -1;
                const stepTime = Math.abs(Math.floor(duration / range));
                let current = start;

                const timer = setInterval(function() {
                    current += increment;
                    element.textContent = current.toLocaleString('id-ID');
                    if (current == end) {
                        clearInterval(timer);
                    }
                }, stepTime);
            }

            // Performance ring animation
            function animatePerformanceRing() {
                const performanceCircle = document.querySelector('.progress-circle');
                if (performanceCircle) {
                    const circumference = 2 * Math.PI * 32; // radius = 32
                    const percentage = {{ $healthScore }};
                    const offset = circumference - (percentage / 100) * circumference;

                    performanceCircle.style.strokeDasharray = circumference;
                    performanceCircle.style.strokeDashoffset = circumference;

                    // Animate after a short delay
                    setTimeout(() => {
                        performanceCircle.style.transition = 'stroke-dashoffset 2s ease-in-out';
                        performanceCircle.style.strokeDashoffset = offset;
                    }, 500);
                }
            }

            // Initialize animations
            animatePerformanceRing();

            // Tooltip for performance indicators
            const tooltipElements = document.querySelectorAll('[title]');
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    // You can implement custom tooltips here if needed
                });
            });

            // Auto-refresh data every 5 minutes (optional)
            let autoRefreshEnabled = true;

            function toggleAutoRefresh() {
                autoRefreshEnabled = !autoRefreshEnabled;
                console.log('Auto refresh:', autoRefreshEnabled ? 'enabled' : 'disabled');
            }

            // Uncomment to enable auto-refresh
            // setInterval(() => {
            //     if (autoRefreshEnabled) {
            //         // You would typically reload data here
            //         console.log('Auto-refreshing dashboard data...');
            //     }
            // }, 300000); // 5 minutes

            // Progressive enhancement for cards
            const cards = document.querySelectorAll('.analytics-card, .metric-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in-up');
            });

            // Enhanced hover effects for metric cards
            const metricCards = document.querySelectorAll('.metric-card');
            metricCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Keyboard navigation support
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch (e.key) {
                        case '1':
                            e.preventDefault();
                            window.location.href = '/transaksi/create';
                            break;
                        case '2':
                            e.preventDefault();
                            window.location.href = '/laporan';
                            break;
                        case 'r':
                            e.preventDefault();
                            location.reload();
                            break;
                    }
                }
            });

            console.log('Dashboard Analytics initialized successfully!');

            // Welcome message for new login
            @if (session('welcome'))
                Swal.fire({
                    title: 'Selamat Datang!',
                    html: '<p class="text-lg">Halo <strong>{{ session('welcome') }}</strong></p><p class="text-gray-600 mt-2">Semoga hari Anda menyenangkan</p>',
                    icon: 'success',
                    confirmButtonColor: '#0A2463',
                    confirmButtonText: 'Mulai Bekerja',
                    timer: 4000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            @endif
        });
    </script>
@endpush
