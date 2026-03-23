<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $thisMonth = $now->startOfMonth()->toDateString();
        $lastMonth = $now->subMonth()->startOfMonth()->toDateString();
        $lastMonthEnd = $now->endOfMonth()->toDateString();
        $now = Carbon::now(); // Reset after manipulation
        
        // Base query with role-based filtering
        $currentMonthQuery = Transaction::whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year);
        $lastMonthQuery = Transaction::whereMonth('transaction_date', $now->subMonth()->month)
            ->whereYear('transaction_date', $now->subMonth()->year);
        
        // Apply role-based filtering
        if (auth()->user()->role === 'agen') {
            $currentMonthQuery->where('user_id', auth()->id());
            $lastMonthQuery->where('user_id', auth()->id());
        }
        
        // Current month metrics
        $currentMonthIncome = (clone $currentMonthQuery)->where('type', 'income')->sum('amount');
        $currentMonthExpense = (clone $currentMonthQuery)->where('type', 'expense')->sum('amount');
        
        // Last month metrics for comparison
        $lastMonthIncome = (clone $lastMonthQuery)->where('type', 'income')->sum('amount');
        $lastMonthExpense = (clone $lastMonthQuery)->where('type', 'expense')->sum('amount');
        
        $now = Carbon::now(); // Reset
        
        // Calculate percentage changes with better logic
        if ($lastMonthIncome > 0) {
            $incomeChange = (($currentMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100;
        } elseif ($currentMonthIncome > 0) {
            $incomeChange = 100; // New income when there was none before
        } else {
            $incomeChange = 0; // No change when both are zero
        }
        
        if ($lastMonthExpense > 0) {
            $expenseChange = (($currentMonthExpense - $lastMonthExpense) / $lastMonthExpense) * 100;
        } elseif ($currentMonthExpense > 0) {
            $expenseChange = 100; // New expense when there was none before
        } else {
            $expenseChange = 0; // No change when both are zero
        }
        
        // Today's transactions
        $todayTransactionCount = Transaction::whereDate('transaction_date', $today)->count();
        
        // Net profit
        $netProfit = $currentMonthIncome - $currentMonthExpense;
        $lastMonthNetProfit = $lastMonthIncome - $lastMonthExpense;
        
        // Calculate profit change with better logic
        if ($lastMonthNetProfit != 0) {
            $profitChange = (($netProfit - $lastMonthNetProfit) / abs($lastMonthNetProfit)) * 100;
        } elseif ($netProfit > 0) {
            $profitChange = 100; // New profit when there was none before
        } elseif ($netProfit < 0 && $lastMonthNetProfit == 0) {
            $profitChange = -100; // New loss when there was no change before
        } else {
            $profitChange = 0; // No change when both are zero
        }
        
        // Recent transactions with role-based filtering
        $recentTransactionsQuery = Transaction::with(['user', 'service'])->orderByDesc('created_at')->limit(5);
        if (auth()->user()->role === 'agen') {
            $recentTransactionsQuery->where('user_id', auth()->id());
        }
        $recentTransactions = $recentTransactionsQuery->get();
        
        // Top services by revenue this month with role-based filtering
        $topServicesQuery = Service::leftJoin('transactions', function($join) use ($now) {
                $join->on('services.id', '=', 'transactions.service_id')
                     ->where('transactions.type', 'income')
                     ->whereMonth('transactions.transaction_date', $now->month)
                     ->whereYear('transactions.transaction_date', $now->year);
                // Add role-based filtering for employees
                if (auth()->user()->role === 'agen') {
                    $join->where('transactions.user_id', auth()->id());
                }
            });
        $topServices = $topServicesQuery
            ->selectRaw('services.*, 
                        COALESCE(SUM(transactions.amount), 0) as total_amount, 
                        COALESCE(COUNT(transactions.id), 0) as transactions_count')
            ->groupBy('services.id', 'services.name', 'services.price', 'services.created_at', 'services.updated_at')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();
        
        // Monthly data for chart (last 6 months) with role-based filtering
        $chartData = [
            'labels' => [],
            'income' => [],
            'expenses' => []
        ];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $incomeQuery = Transaction::where('type', 'income')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year);
            $expenseQuery = Transaction::where('type', 'expense')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year);
            
            // Apply role-based filtering
            if (auth()->user()->role === 'agen') {
                $incomeQuery->where('user_id', auth()->id());
                $expenseQuery->where('user_id', auth()->id());
            }
            
            $income = $incomeQuery->sum('amount');
            $expense = $expenseQuery->sum('amount');
            
            $chartData['labels'][] = $month->format('M Y');
            $chartData['income'][] = $income;
            $chartData['expenses'][] = $expense;
        }
        
        // Employee count (only shown to admins)
        $employeeCount = auth()->user()->role === 'admin' ? User::where('role', 'karyawan')->count() : 0;
        
        // Total salary this month (35% of income)
        $totalSalaries = $currentMonthIncome * 0.35;
        
        // Profit margin calculation
        $profitMargin = $currentMonthIncome > 0 ? (($netProfit / $currentMonthIncome) * 100) : 0;

        return view('dashboard', compact(
            'currentMonthIncome',
            'currentMonthExpense', 
            'netProfit',
            'todayTransactionCount',
            'incomeChange',
            'expenseChange',
            'profitChange',
            'recentTransactions',
            'topServices',
            'chartData',
            'employeeCount',
            'totalSalaries',
            'profitMargin'
        ));
    }
}
