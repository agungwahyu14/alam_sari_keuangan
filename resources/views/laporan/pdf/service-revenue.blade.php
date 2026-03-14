<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan per Layanan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #22c55e;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .period {
            font-size: 12px;
            color: #666;
        }

        .summary {
            margin-bottom: 30px;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .total-revenue {
            font-size: 24px;
            font-weight: bold;
            color: #22c55e;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 14px;
            color: #666;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #22c55e;
            border-bottom: 1px solid #22c55e;
            padding-bottom: 5px;
        }

        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .service-table th,
        .service-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .service-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }

        .service-table td {
            font-size: 11px;
        }

        .service-table .amount {
            text-align: right;
            font-weight: bold;
        }

        .service-table .percentage {
            text-align: center;
        }

        .service-table .count {
            text-align: center;
        }

        .revenue {
            color: #22c55e;
        }

        .chart-section {
            margin-top: 30px;
        }

        .service-bar {
            margin-bottom: 10px;
        }

        .service-name {
            font-size: 11px;
            margin-bottom: 3px;
            font-weight: bold;
        }

        .bar-container {
            width: 100%;
            height: 20px;
            background-color: #f3f4f6;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .bar-fill {
            height: 100%;
            background: linear-gradient(to right, #22c55e, #16a34a);
            border-radius: 10px;
        }

        .bar-text {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            font-weight: bold;
            color: #333;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .insights {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f9ff;
            border-left: 4px solid #22c55e;
        }

        .insights-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #22c55e;
        }
    </style>
</head>

<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="/public/Logo_Mancraft.jpg" alt="Logo" style="max-width: 150px; height: auto;" />
        </div>
        <div class="company-name">Manajemen Keuangan</div>
        <div class="report-title">LAPORAN PENDAPATAN PER LAYANAN</div>
        <div class="period">Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</div>
    </div>

    <div class="summary">
        <div class="total-revenue">Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</div>
        <div class="summary-label">Total Pendapatan Layanan</div>
    </div>

    <div class="section-title">Detail Pendapatan per Layanan</div>
    <table class="service-table">
        <thead>
            <tr>
                <th style="width: 25%;">Nama Layanan</th>
                <th style="width: 25%;">Pendapatan</th>
                <th style="width: 15%;">Transaksi</th>
                <th style="width: 15%;">Persentase</th>
                <th style="width: 20%;">Rata-rata/Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['services'] as $service)
                <tr>
                    <td><strong>{{ $service['service_name'] }}</strong></td>
                    <td class="amount revenue">Rp {{ number_format($service['revenue'], 0, ',', '.') }}</td>
                    <td class="count">{{ $service['transaction_count'] }}</td>
                    <td class="percentage">{{ number_format($service['percentage'], 1) }}%</td>
                    <td class="amount">Rp
                        {{ number_format($service['revenue'] / max($service['transaction_count'], 1), 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="chart-section">
        <div class="section-title">Visualisasi Kontribusi Layanan</div>
        @foreach ($data['services'] as $service)
            <div class="service-bar">
                <div class="service-name">{{ $service['service_name'] }}</div>
                <div class="bar-container">
                    <div class="bar-fill" style="width: {{ $service['percentage'] }}%;"></div>
                    <div class="bar-text">{{ number_format($service['percentage'], 1) }}%</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="insights">
        <div class="insights-title">Insights & Analisis:</div>
        @php
            $topService = $data['services']->first();
            $serviceCount = count($data['services']);
        @endphp

        @if ($topService)
            <p>• <strong>Layanan Terbaik:</strong> {{ $topService['service_name'] }} berkontribusi
                {{ number_format($topService['percentage'], 1) }}% dari total pendapatan dengan
                {{ $topService['transaction_count'] }} transaksi.</p>
        @endif

        <p>• <strong>Diversifikasi Layanan:</strong> Terdapat {{ $serviceCount }} jenis layanan yang berkontribusi
            terhadap pendapatan.</p>

        @php
            $avgRevenuePerService = $data['total_revenue'] / max($serviceCount, 1);
            $highPerformers = collect($data['services'])
                ->filter(function ($service) use ($avgRevenuePerService) {
                    return $service['revenue'] > $avgRevenuePerService;
                })
                ->count();
        @endphp

        <p>• <strong>Kinerja Layanan:</strong> {{ $highPerformers }} dari {{ $serviceCount }} layanan memiliki
            pendapatan di atas rata-rata (Rp {{ number_format($avgRevenuePerService, 0, ',', '.') }}).</p>

        @if ($data['services']->count() > 0)
            @php
                $totalTransactions = collect($data['services'])->sum('transaction_count');
                $avgPerTransaction = $data['total_revenue'] / max($totalTransactions, 1);
            @endphp
            <p>• <strong>Nilai Rata-rata Transaksi:</strong> Rp {{ number_format($avgPerTransaction, 0, ',', '.') }}
                per transaksi dari total {{ $totalTransactions }} transaksi.</p>
        @endif
    </div>

    <div class="footer">
        <p>Laporan dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} pukul
            {{ \Carbon\Carbon::now()->format('H:i:s') }} WITA</p>
        <p>{{ config('app.name') }} - Sistem Manajemen Keuangan</p>
    </div>
</body>

</html>
