<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function data(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Role-based filtering: Employees can only see their own reports
        if (auth()->user() && auth()->user()->role === 'agen') {
            $employees = User::where('id', auth()->id())->with([
                'transactions' => function ($q) use ($start, $end) {
                    $q->where('type', 'income')
                        ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()]);
                }
            ])->get();
        } else {
            // Admin can see all employees
            $employees = User::isAgen()->with([
                'transactions' => function ($q) use ($start, $end) {
                    $q->where('type', 'income')
                        ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()]);
                }
            ])->get();
        }

        $data = [];
        foreach ($employees as $employee) {
            $totalIncome = $employee->transactions->sum('amount');
            $commission = $totalIncome * 0.05; // 5% commission for freelance agents

            // Group transactions by service to calculate percentages
            $serviceBreakdown = $employee->transactions->groupBy('service_id')->map(function ($serviceTransactions) use ($totalIncome) {
                $service = $serviceTransactions->first()->service;
                $serviceIncome = $serviceTransactions->sum('amount');
                $serviceCommission = $serviceIncome * 0.05; // 5% commission

                return [
                    'service_name' => $service ? $service->name : 'Unknown Service',
                    'service_income' => $serviceIncome,
                    'service_commission' => $serviceCommission,
                    'percentage' => $totalIncome > 0 ? ($serviceIncome / $totalIncome) * 100 : 0,
                    'commission_percentage' => 5 // Fixed 5% for freelance agents
                ];
            })->sortByDesc('service_income')->values();

            $data[] = [
                'name' => $employee->name,
                'email' => $employee->email,
                'bank_account' => $employee->bank_account ?: '-',
                'total_income' => 'Rp ' . number_format($totalIncome, 0, ',', '.'),
                'commission' => 'Rp ' . number_format($commission, 0, ',', '.'),
                'service_breakdown' => $serviceBreakdown
            ];
        }
        return response()->json(['data' => $data]);
    }

    // Laporan Arus Kas
    public function cashFlow(Request $request)
    {
        // Strict RBAC: Agen cannot access this report
        if (auth()->user() && auth()->user()->role === 'agen') {
            abort(403, 'Unauthorized action.');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = Transaction::whereBetween('transaction_date', [$startDate, $endDate]);

        // Role-based filtering for employees
        if (auth()->user() && auth()->user()->role === 'agen') {
            $query->where('user_id', auth()->id());
        }

        $transactions = $query->with(['user', 'service'])
            ->orderBy('transaction_date')
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');
        $netCashFlow = $income - $expenses;

        $dailyCashFlow = $transactions->groupBy(function ($transaction) {
            return Carbon::parse($transaction->transaction_date)->format('Y-m-d');
        })->map(function ($dayTransactions) {
            $dailyIncome = $dayTransactions->where('type', 'income')->sum('amount');
            $dailyExpenses = $dayTransactions->where('type', 'expense')->sum('amount');
            return [
                'income' => $dailyIncome,
                'expenses' => $dailyExpenses,
                'net' => $dailyIncome - $dailyExpenses
            ];
        });

        return response()->json([
            'summary' => [
                'total_income' => $income,
                'total_expenses' => $expenses,
                'net_cash_flow' => $netCashFlow,
                'period' => $startDate . ' to ' . $endDate
            ],
            'daily_cash_flow' => $dailyCashFlow,
            'transactions' => $transactions
        ]);
    }

    // Laporan Laba Rugi
    public function profitLoss(Request $request)
    {
        // Strict RBAC: Agen cannot access this report
        if (auth()->user() && auth()->user()->role === 'agen') {
            abort(403, 'Unauthorized action.');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $baseQuery = Transaction::whereBetween('transaction_date', [$startDate, $endDate]);

        // Role-based filtering for employees
        if (auth()->user() && auth()->user()->role === 'agen') {
            $baseQuery->where('user_id', auth()->id());
        }

        $income = (clone $baseQuery)->where('type', 'income')->sum('amount');
        $operationalExpenses = (clone $baseQuery)->where('type', 'expense')->sum('amount');

        // Calculate employee salaries (35% of income)
        $employeeCosts = $income * 0.35;
        $totalExpenses = $employeeCosts + $operationalExpenses;
        $netProfit = $income - $totalExpenses;

        return response()->json([
            'revenue' => $income,
            'employee_costs' => $employeeCosts,
            'operational_expenses' => $operationalExpenses,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'profit_margin' => $income > 0 ? ($netProfit / $income) * 100 : 0,
            'period' => $startDate . ' to ' . $endDate
        ]);
    }

    // Ringkasan Pendapatan per Layanan
    public function serviceRevenue(Request $request)
    {
        // Strict RBAC: Agen cannot access this report
        if (auth()->user() && auth()->user()->role === 'agen') {
            abort(403, 'Unauthorized action.');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = Transaction::where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->with('service');

        // Role-based filtering for employees
        if (auth()->user() && auth()->user()->role === 'agen') {
            $query->where('user_id', auth()->id());
        }

        $transactions = $query->get();
        $totalRevenue = $transactions->sum('amount');

        $serviceRevenue = $transactions->groupBy('service_id')->map(function ($serviceTransactions) use ($totalRevenue) {
            $service = $serviceTransactions->first()->service;
            $revenue = $serviceTransactions->sum('amount');
            $transactionCount = $serviceTransactions->count();

            return [
                'service_name' => $service ? $service->name : 'Unknown Service',
                'revenue' => $revenue,
                'transaction_count' => $transactionCount,
                'percentage' => $totalRevenue > 0 ? ($revenue / $totalRevenue) * 100 : 0,
                'average_per_transaction' => $transactionCount > 0 ? $revenue / $transactionCount : 0
            ];
        })->sortByDesc('revenue')->values();

        return response()->json([
            'total_revenue' => $totalRevenue,
            'services' => $serviceRevenue,
            'period' => $startDate . ' to ' . $endDate
        ]);
    }

    // Laporan Transaksi Harian/Bulanan
    public function transactionReport(Request $request)
    {
        // Strict RBAC: Agen cannot access this report
        if (auth()->user() && auth()->user()->role === 'agen') {
            abort(403, 'Unauthorized action.');
        }

        $period = $request->input('period', 'monthly'); // daily, monthly
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->with(['user', 'service']);

        // Role-based filtering for employees
        if (auth()->user() && auth()->user()->role === 'agen') {
            $query->where('user_id', auth()->id());
        }

        $transactions = $query->orderBy('transaction_date')->get();

        if ($period === 'daily') {
            $groupedData = $transactions->groupBy(function ($transaction) {
                return Carbon::parse($transaction->transaction_date)->format('Y-m-d');
            });
        } else {
            $groupedData = $transactions->groupBy(function ($transaction) {
                return Carbon::parse($transaction->transaction_date)->format('Y-m');
            });
        }

        $reportData = $groupedData->map(function ($periodTransactions, $periodKey) {
            $income = $periodTransactions->where('type', 'income')->sum('amount');
            $expenses = $periodTransactions->where('type', 'expense')->sum('amount');
            $transactionCount = $periodTransactions->count();

            return [
                'period' => $periodKey,
                'income' => $income,
                'expenses' => $expenses,
                'net' => $income - $expenses,
                'transaction_count' => $transactionCount,
                'transactions' => $periodTransactions->values()
            ];
        });

        return response()->json([
            'report_data' => $reportData,
            'summary' => [
                'total_income' => $transactions->where('type', 'income')->sum('amount'),
                'total_expenses' => $transactions->where('type', 'expense')->sum('amount'),
                'total_transactions' => $transactions->count()
            ],
            'period_type' => $period,
            'date_range' => $startDate . ' to ' . $endDate
        ]);
    }

    // Download PDF Reports
    public function downloadCashFlowPdf(Request $request)
    {
        if (auth()->user() && auth()->user()->role === 'agen') {
            abort(403, 'Unauthorized action.');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $data = $this->getCashFlowData($startDate, $endDate);

        $pdf = Pdf::loadView('laporan.pdf.cash-flow', compact('data', 'startDate', 'endDate'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-arus-kas-' . $startDate . '-to-' . $endDate . '.pdf');
    }

    public function downloadProfitLossPdf(Request $request)
    {
        if (auth()->user() && auth()->user()->role === 'agen') {
            abort(403, 'Unauthorized action.');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $data = $this->getProfitLossData($startDate, $endDate);

        $pdf = Pdf::loadView('laporan.pdf.profit-loss', compact('data', 'startDate', 'endDate'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-laba-rugi-' . $startDate . '-to-' . $endDate . '.pdf');
    }

    public function downloadServiceRevenuePdf(Request $request)
    {
        if (auth()->user() && auth()->user()->role === 'agen') {
            abort(403, 'Unauthorized action.');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $data = $this->getServiceRevenueData($startDate, $endDate);

        $pdf = Pdf::loadView('laporan.pdf.service-revenue', compact('data', 'startDate', 'endDate'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-pendapatan-layanan-' . $startDate . '-to-' . $endDate . '.pdf');
    }

    // Download Salary Slip PDF for Employee
    public function downloadSalarySlipPdf(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Employees can only download their own salary slip
        if (auth()->user() && auth()->user()->role === 'agen') {
            $employee = User::where('id', auth()->id())->with([
                'transactions' => function ($q) use ($start, $end) {
                    $q->where('type', 'income')
                        ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()])
                        ->with('service');
                }
            ])->first();
        } else {
            // Admin can download any employee's salary slip
            $employeeId = $request->input('employee_id');
            $employee = User::where('id', $employeeId)->with([
                'transactions' => function ($q) use ($start, $end) {
                    $q->where('type', 'income')
                        ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()])
                        ->with('service');
                }
            ])->first();
        }

        if (!$employee) {
            return redirect()->back()->with('error', 'Data agen tidak ditemukan');
        }

        $data = $this->getSalarySlipData($employee, $start, $end);

        $pdf = Pdf::loadView('laporan.pdf.salary-slip', compact('data', 'employee', 'month'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('slip-gaji-' . $employee->name . '-' . $month . '.pdf');
    }

    // Download Salary Report PDF for Admin
    public function downloadSalaryReportPdf(Request $request)
    {
        if (auth()->user() && auth()->user()->role === 'agen') {
            abort(403, 'Unauthorized action.');
        }

        $month = $request->input('month', date('Y-m'));
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $employees = User::isAgen()->with([
            'transactions' => function ($q) use ($start, $end) {
                $q->where('type', 'income')
                    ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()])
                    ->with('service');
            }
        ])->get();

        $data = $this->getSalaryReportData($employees, $start, $end);

        $pdf = Pdf::loadView('laporan.pdf.salary-report', compact('data', 'month'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-gaji-karyawan-' . $month . '.pdf');
    }

    // Helper methods for getting data (used by both API and PDF)
    private function getCashFlowData($startDate, $endDate)
    {
        $query = Transaction::whereBetween('transaction_date', [$startDate, $endDate]);

        if (auth()->user() && auth()->user()->role === 'agen') {
            $query->where('user_id', auth()->id());
        }

        $transactions = $query->with(['user', 'service'])->orderBy('transaction_date')->get();
        $income = $transactions->where('type', 'income')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');

        return [
            'transactions' => $transactions,
            'total_income' => $income,
            'total_expenses' => $expenses,
            'net_cash_flow' => $income - $expenses
        ];
    }

    private function getProfitLossData($startDate, $endDate)
    {
        $baseQuery = Transaction::whereBetween('transaction_date', [$startDate, $endDate]);

        if (auth()->user() && auth()->user()->role === 'agen') {
            $baseQuery->where('user_id', auth()->id());
        }

        $income = (clone $baseQuery)->where('type', 'income')->sum('amount');
        $operationalExpenses = (clone $baseQuery)->where('type', 'expense')->sum('amount');
        $employeeCosts = $income * 0.35;
        $totalExpenses = $employeeCosts + $operationalExpenses;

        return [
            'revenue' => $income,
            'employee_costs' => $employeeCosts,
            'operational_expenses' => $operationalExpenses,
            'total_expenses' => $totalExpenses,
            'net_profit' => $income - $totalExpenses
        ];
    }

    private function getServiceRevenueData($startDate, $endDate)
    {
        $query = Transaction::where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->with('service');

        if (auth()->user() && auth()->user()->role === 'agen') {
            $query->where('user_id', auth()->id());
        }

        $transactions = $query->get();
        $totalRevenue = $transactions->sum('amount');

        $serviceRevenue = $transactions->groupBy('service_id')->map(function ($serviceTransactions) use ($totalRevenue) {
            $service = $serviceTransactions->first()->service;
            $revenue = $serviceTransactions->sum('amount');

            return [
                'service_name' => $service ? $service->name : 'Unknown Service',
                'revenue' => $revenue,
                'transaction_count' => $serviceTransactions->count(),
                'percentage' => $totalRevenue > 0 ? ($revenue / $totalRevenue) * 100 : 0
            ];
        })->sortByDesc('revenue')->values();

        return [
            'total_revenue' => $totalRevenue,
            'services' => $serviceRevenue
        ];
    }

    private function getSalarySlipData($employee, $start, $end)
    {
        $totalIncome = $employee->transactions->sum('amount');
        $salary = $totalIncome * 0.35;

        // Group transactions by service to calculate percentages
        $serviceBreakdown = $employee->transactions->groupBy('service_id')->map(function ($serviceTransactions) use ($totalIncome) {
            $service = $serviceTransactions->first()->service;
            $serviceIncome = $serviceTransactions->sum('amount');
            $serviceSalary = $serviceIncome * 0.35;

            return [
                'service_name' => $service ? $service->name : 'Unknown Service',
                'service_income' => $serviceIncome,
                'service_salary' => $serviceSalary,
                'percentage' => $totalIncome > 0 ? ($serviceIncome / $totalIncome) * 100 : 0,
                'salary_percentage' => 35,
                'transaction_count' => $serviceTransactions->count()
            ];
        })->sortByDesc('service_income')->values();

        return [
            'total_income' => $totalIncome,
            'total_salary' => $salary,
            'salary_percentage' => 35,
            'period_start' => $start->format('d F Y'),
            'period_end' => $end->format('d F Y'),
            'service_breakdown' => $serviceBreakdown,
            'transaction_count' => $employee->transactions->count()
        ];
    }

    private function getSalaryReportData($employees, $start, $end)
    {
        $reportData = [];
        $totalSalaryPaid = 0;
        $totalIncome = 0;

        foreach ($employees as $employee) {
            $employeeIncome = $employee->transactions->sum('amount');
            $employeeSalary = $employeeIncome * 0.35;

            $serviceBreakdown = $employee->transactions->groupBy('service_id')->map(function ($serviceTransactions) use ($employeeIncome) {
                $service = $serviceTransactions->first()->service;
                $serviceIncome = $serviceTransactions->sum('amount');

                return [
                    'service_name' => $service ? $service->name : 'Unknown Service',
                    'service_income' => $serviceIncome,
                    'service_salary' => $serviceIncome * 0.35,
                    'percentage' => $employeeIncome > 0 ? ($serviceIncome / $employeeIncome) * 100 : 0,
                    'salary_percentage' => 35
                ];
            })->sortByDesc('service_income')->values();

            $reportData[] = [
                'name' => $employee->name,
                'email' => $employee->email,
                'bank_account' => $employee->bank_account ?: '-',
                'total_income' => $employeeIncome,
                'total_salary' => $employeeSalary,
                'formatted_income' => 'Rp ' . number_format($employeeIncome, 0, ',', '.'),
                'formatted_salary' => 'Rp ' . number_format($employeeSalary, 0, ',', '.'),
                'service_breakdown' => $serviceBreakdown,
                'transaction_count' => $employee->transactions->count()
            ];

            $totalSalaryPaid += $employeeSalary;
            $totalIncome += $employeeIncome;
        }

        return [
            'employees' => $reportData,
            'total_employees' => $employees->count(),
            'total_income' => $totalIncome,
            'total_salary_paid' => $totalSalaryPaid,
            'formatted_total_income' => 'Rp ' . number_format($totalIncome, 0, ',', '.'),
            'formatted_total_salary' => 'Rp ' . number_format($totalSalaryPaid, 0, ',', '.'),
            'period_start' => $start->format('d F Y'),
            'period_end' => $end->format('d F Y')
        ];
    }
}
