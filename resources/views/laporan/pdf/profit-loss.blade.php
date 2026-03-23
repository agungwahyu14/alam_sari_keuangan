<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
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

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #22c55e;
            border-bottom: 1px solid #22c55e;
            padding-bottom: 5px;
        }

        .profit-loss-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .profit-loss-table th,
        .profit-loss-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .profit-loss-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .profit-loss-table .amount {
            text-align: right;
            font-weight: bold;
        }

        .revenue {
            color: #22c55e;
        }

        .expense {
            color: #ef4444;
        }

        .net-profit-positive {
            color: #22c55e;
            background-color: #f0f9ff;
        }

        .net-profit-negative {
            color: #ef4444;
            background-color: #fef2f2;
        }

        .total-row {
            border-top: 2px solid #333;
            font-weight: bold;
        }

        .summary-metrics {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .metric-box {
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 45%;
        }

        .metric-value {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .metric-label {
            font-size: 12px;
            color: #666;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="/public/AlamSari.png" alt="Alam Sari Properti" style="max-width: 150px; height: auto;" />
        </div>
        <div class="company-name">Manajemen Keuangan</div>
        <div class="report-title">LAPORAN LABA RUGI</div>
        <div class="period">Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</div>
    </div>

    <div class="section">
        <div class="section-title">Laporan Laba Rugi</div>
        <table class="profit-loss-table">
            <tr>
                <th style="width: 70%;">Keterangan</th>
                <th style="width: 30%;">Jumlah</th>
            </tr>

            <!-- PENDAPATAN -->
            <tr>
                <td><strong>PENDAPATAN</strong></td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Total Pendapatan Layanan</td>
                <td class="amount revenue">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>TOTAL PENDAPATAN</strong></td>
                <td class="amount revenue"><strong>Rp {{ number_format($data['revenue'], 0, ',', '.') }}</strong></td>
            </tr>

            <!-- PENGELUARAN -->
            <tr>
                <td><strong>PENGELUARAN</strong></td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Biaya Karyawan (35% dari pendapatan)</td>
                <td class="amount expense">Rp {{ number_format($data['employee_costs'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Biaya Operasional</td>
                <td class="amount expense">Rp {{ number_format($data['operational_expenses'], 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>TOTAL PENGELUARAN</strong></td>
                <td class="amount expense"><strong>Rp {{ number_format($data['total_expenses'], 0, ',', '.') }}</strong>
                </td>
            </tr>

            <!-- LABA BERSIH -->
            <tr class="total-row {{ $data['net_profit'] >= 0 ? 'net-profit-positive' : 'net-profit-negative' }}">
                <td><strong>LABA BERSIH</strong></td>
                <td class="amount">
                    <strong>Rp {{ number_format($data['net_profit'], 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Analisis Kinerja</div>
        <table class="profit-loss-table">
            <tr>
                <th>Metrik</th>
                <th>Nilai</th>
            </tr>
            <tr>
                <td>Margin Keuntungan</td>
                <td class="amount">
                    @php
                        $margin = $data['revenue'] > 0 ? ($data['net_profit'] / $data['revenue']) * 100 : 0;
                    @endphp
                    {{ number_format($margin, 1) }}%
                </td>
            </tr>
            <tr>
                <td>Rasio Biaya Karyawan</td>
                <td class="amount">35.0%</td>
            </tr>
            <tr>
                <td>Rasio Biaya Operasional</td>
                <td class="amount">
                    @php
                        $opRatio = $data['revenue'] > 0 ? ($data['operational_expenses'] / $data['revenue']) * 100 : 0;
                    @endphp
                    {{ number_format($opRatio, 1) }}%
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Kesimpulan</div>
        <p>
            @if ($data['net_profit'] >= 0)
                <strong style="color: #22c55e;">Perusahaan mengalami keuntungan</strong> sebesar Rp
                {{ number_format($data['net_profit'], 0, ',', '.') }}
                pada periode {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}.
            @else
                <strong style="color: #ef4444;">Perusahaan mengalami kerugian</strong> sebesar Rp
                {{ number_format(abs($data['net_profit']), 0, ',', '.') }}
                pada periode {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}.
            @endif
        </p>
        <p>
            Total pendapatan yang diperoleh adalah Rp {{ number_format($data['revenue'], 0, ',', '.') }} dengan total
            pengeluaran
            Rp {{ number_format($data['total_expenses'], 0, ',', '.') }}.
        </p>
    </div>

    <div class="footer">
        <p>Laporan dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} pukul
            {{ \Carbon\Carbon::now()->format('H:i:s') }} WITA</p>
        <p>{{ config('app.name') }} - Sistem Manajemen Keuangan</p>
    </div>
</body>

</html>
