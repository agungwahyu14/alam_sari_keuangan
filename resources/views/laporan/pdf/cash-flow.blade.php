<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Arus Kas</title>
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

        .income {
            color: #22c55e;
        }

        .expense {
            color: #ef4444;
        }

        .net-positive {
            color: #22c55e;
        }

        .net-negative {
            color: #ef4444;
        }

        .transactions {
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

        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .transaction-table th,
        .transaction-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        .transaction-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .transaction-table .amount {
            text-align: right;
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
        <div class="report-title">LAPORAN ARUS KAS</div>
        <div class="period">Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</div>
    </div>

    <div class="summary">
        <div class="section-title">Ringkasan Arus Kas</div>
        <table class="summary-table">
            <tr>
                <th style="width: 60%;">Keterangan</th>
                <th style="width: 40%;">Jumlah</th>
            </tr>
            <tr>
                <td>Total Pemasukan</td>
                <td class="amount income">Rp {{ number_format($data['total_income'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pengeluaran</td>
                <td class="amount expense">Rp {{ number_format($data['total_expenses'], 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 2px solid #333;">
                <td><strong>Arus Kas Bersih</strong></td>
                <td class="amount {{ $data['net_cash_flow'] >= 0 ? 'net-positive' : 'net-negative' }}">
                    <strong>Rp {{ number_format($data['net_cash_flow'], 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="transactions">
        <div class="section-title">Detail Transaksi</div>
        <table class="transaction-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 25%;">Keterangan</th>
                    <th style="width: 20%;">Karyawan</th>
                    <th style="width: 20%;">Pemasukan</th>
                    <th style="width: 20%;">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['transactions'] as $transaction)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</td>
                        <td>
                            @if ($transaction->service)
                                {{ $transaction->service->name }}
                            @elseif($transaction->description)
                                {{ $transaction->description }}
                            @else
                                {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            @endif
                        </td>
                        <td>{{ $transaction->user ? $transaction->user->name : '-' }}</td>
                        <td class="amount">
                            @if ($transaction->type === 'income')
                                <span class="income">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="amount">
                            @if ($transaction->type === 'expense')
                                <span class="expense">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} pukul
            {{ \Carbon\Carbon::now()->format('H:i:s') }} WITA</p>
        <p>{{ config('app.name') }} - Sistem Manajemen Keuangan</p>
    </div>
</body>

</html>
