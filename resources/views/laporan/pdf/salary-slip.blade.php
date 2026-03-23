<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
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

        .employee-info {
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #22c55e;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
        }

        .info-value {
            flex: 1;
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
            padding: 10px;
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

        .service-breakdown {
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

        .service-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .service-table th,
        .service-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        .service-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .service-table .amount {
            text-align: right;
        }

        .service-table .percentage {
            text-align: center;
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
        <div class="report-title">SLIP GAJI</div>
        <div class="period">Periode: {{ $data['period_start'] }} - {{ $data['period_end'] }}</div>
    </div>

    <div class="employee-info">
        <div class="info-row">
            <div class="info-label">Nama Karyawan:</div>
            <div class="info-value">{{ $employee->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $employee->email }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nomor Rekening:</div>
            <div class="info-value">{{ $employee->bank_account ?: '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jabatan:</div>
            <div class="info-value">{{ ucfirst($employee->role) }}</div>
        </div>
    </div>

    <div class="summary">
        <div class="section-title">Ringkasan Gaji</div>
        <table class="summary-table">
            <tr>
                <th style="width: 60%;">Keterangan</th>
                <th style="width: 40%;">Jumlah</th>
            </tr>
            <tr>
                <td>Total Pendapatan Layanan</td>
                <td class="amount">Rp {{ number_format($data['total_income'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Persentase Gaji</td>
                <td class="amount">{{ $data['salary_percentage'] }}%</td>
            </tr>
            <tr style="border-top: 2px solid #333;">
                <td><strong>Total Gaji Bersih</strong></td>
                <td class="amount salary"><strong>Rp {{ number_format($data['total_salary'], 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="service-breakdown">
        <div class="section-title">Detail Gaji per Layanan</div>
        <table class="service-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Layanan</th>
                    <th style="width: 20%;">Pendapatan Layanan</th>
                    <th style="width: 15%;">% dari Total</th>
                    <th style="width: 15%;">% Gaji</th>
                    <th style="width: 15%;">Gaji dari Layanan</th>
                    <th style="width: 10%;">Jumlah Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['service_breakdown'] as $service)
                    <tr>
                        <td>{{ $service['service_name'] }}</td>
                        <td class="amount">Rp {{ number_format($service['service_income'], 0, ',', '.') }}</td>
                        <td class="percentage">{{ number_format($service['percentage'], 1) }}%</td>
                        <td class="percentage">{{ $service['salary_percentage'] }}%</td>
                        <td class="amount salary">Rp {{ number_format($service['service_salary'], 0, ',', '.') }}</td>
                        <td class="percentage">{{ $service['transaction_count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>Catatan:</strong> Gaji dihitung berdasarkan {{ $data['salary_percentage'] }}% dari total pendapatan
            layanan yang dihasilkan karyawan.</p>
        <p>Slip gaji dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} pukul
            {{ \Carbon\Carbon::now()->format('H:i:s') }} WITA</p>
        <p>{{ config('app.name') }} - Sistem Manajemen Keuangan</p>
    </div>
</body>

</html>
