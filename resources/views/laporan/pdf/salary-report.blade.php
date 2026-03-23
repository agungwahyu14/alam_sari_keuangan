<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Gaji Karyawan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
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
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .summary-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .summary-table .amount {
            text-align: right;
            font-weight: bold;
        }

        .salary {
            color: #22c55e;
        }

        .income {
            color: #2563eb;
        }

        .employees {
            margin-top: 30px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #22c55e;
            border-bottom: 1px solid #22c55e;
            padding-bottom: 5px;
        }

        .employee-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .employee-table th,
        .employee-table td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        .employee-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .employee-table .amount {
            text-align: right;
        }

        .employee-table .center {
            text-align: center;
        }

        .service-details {
            font-size: 8px;
            color: #666;
        }

        .service-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .service-details th,
        .service-details td {
            border: 1px solid #eee;
            padding: 2px;
        }

        .service-details th {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="/public/AlamSari.png" alt="Alam Sari Properti" style="max-width: 150px; height: auto;" />
        </div>
        <div class="company-name">Manajemen Keuangan</div>
        <div class="report-title">LAPORAN GAJI KARYAWAN</div>
        <div class="period">Periode: {{ $data['period_start'] }} - {{ $data['period_end'] }}</div>
    </div>

    <div class="summary">
        <div class="section-title">Ringkasan Laporan</div>
        <table class="summary-table">
            <tr>
                <th style="width: 50%;">Keterangan</th>
                <th style="width: 50%;">Jumlah</th>
            </tr>
            <tr>
                <td>Total Karyawan</td>
                <td class="amount">{{ $data['total_employees'] }} orang</td>
            </tr>
            <tr>
                <td>Total Pendapatan Layanan</td>
                <td class="amount income">{{ $data['formatted_total_income'] }}</td>
            </tr>
            <tr>
                <td>Total Gaji Dibayarkan (35%)</td>
                <td class="amount salary">{{ $data['formatted_total_salary'] }}</td>
            </tr>
            <tr style="border-top: 2px solid #333;">
                <td><strong>Sisa Pendapatan (65%)</strong></td>
                <td class="amount"><strong>Rp
                        {{ number_format($data['total_income'] - $data['total_salary_paid'], 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="employees">
        <div class="section-title">Detail Gaji per Karyawan</div>
        <table class="employee-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Nama Karyawan</th>
                    <th style="width: 12%;">Email</th>
                    <th style="width: 12%;">Nomor Rekening</th>
                    <th style="width: 10%;">Total Pendapatan</th>
                    <th style="width: 10%;">Total Gaji</th>
                    <th style="width: 8%;">Jumlah Transaksi</th>
                    <th style="width: 33%;">Detail per Layanan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['employees'] as $employee)
                    <tr>
                        <td><strong>{{ $employee['name'] }}</strong></td>
                        <td>{{ $employee['email'] }}</td>
                        <td>{{ $employee['bank_account'] }}</td>
                        <td class="amount income">{{ $employee['formatted_income'] }}</td>
                        <td class="amount salary">{{ $employee['formatted_salary'] }}</td>
                        <td class="center">{{ $employee['transaction_count'] }}</td>
                        <td>
                            @if (count($employee['service_breakdown']) > 0)
                                <div class="service-details">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Layanan</th>
                                                <th>Pendapatan</th>
                                                <th>%</th>
                                                <th>Gaji</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($employee['service_breakdown'] as $service)
                                                <tr>
                                                    <td>{{ $service['service_name'] }}</td>
                                                    <td>Rp {{ number_format($service['service_income'], 0, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($service['percentage'], 1) }}%</td>
                                                    <td>Rp {{ number_format($service['service_salary'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <em>Tidak ada transaksi</em>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>Catatan:</strong> Gaji karyawan dihitung berdasarkan 35% dari total pendapatan layanan yang
            dihasilkan masing-masing karyawan.</p>
        <p>Laporan dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} pukul
            {{ \Carbon\Carbon::now()->format('H:i:s') }} WITA</p>
        <p>{{ config('app.name') }} - Sistem Manajemen Keuangan</p>
    </div>
</body>

</html>
