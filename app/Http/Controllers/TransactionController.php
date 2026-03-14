<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index()
    {
        $services = \App\Models\Service::all();
        $users = \App\Models\User::all();
        $transactions = Transaction::with(['user', 'service'])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->get();
        return view('transaksi.index', compact('services', 'users', 'transactions'));
    }

    /**
     * Return transaction data for DataTables AJAX.
     */
    public function data()
    {
        try {
            $query = Transaction::with(['user', 'service']);
            
            // Role-based filtering: Employees can only see their own transactions
            if (auth()->user()->role === 'karyawan') {
                $query->where('user_id', auth()->id());
            }
            
            // Apply month filter if provided
            if (request()->has('month') && request()->month) {
                $monthYear = explode('-', request()->month);
                if (count($monthYear) == 2) {
                    $year = $monthYear[0];
                    $month = $monthYear[1];
                    $query->whereYear('transaction_date', $year)
                          ->whereMonth('transaction_date', $month);
                }
            }
            
            $transactions = $query->orderByDesc('transaction_date')
                                 ->orderByDesc('id')
                                 ->get();
                
            return response()->json([
                'draw' => request()->get('draw'),
                'recordsTotal' => $transactions->count(),
                'recordsFiltered' => $transactions->count(),
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data transaksi.'], 500);
        }
    }
    public function create()
    {
        $services = \App\Models\Service::all();
        $users = \App\Models\User::all();
        return view('transaksi.create', compact('services', 'users'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        
        // For employees, automatically set their user_id for income transactions
        if (auth()->user()->role === 'karyawan' && $data['type'] === 'income') {
            $data['user_id'] = auth()->id();
        }
        
        $transaction = Transaction::create($data);
        
        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Transaksi berhasil ditambahkan!']);
        }
        
        // Return with session flash for regular form submissions
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaksi)
    {
        // Role-based access: Employees can only view their own transactions
        if (auth()->user()->role === 'karyawan' && $transaksi->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this transaction.');
        }
        
        $transaksi->load(['user', 'service']);
        
        // Format the data properly for the frontend
        $transactionData = $transaksi->toArray();
        $transactionData['transaction_date'] = $transaksi->transaction_date 
            ? $transaksi->transaction_date->format('Y-m-d') 
            : null;
        
        return response()->json($transactionData);
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Transaction $transaksi)
    {
        // Employees can only edit their own transactions
        if (auth()->user()->role === 'karyawan' && $transaksi->user_id !== auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Anda hanya bisa mengedit transaksi Anda sendiri.'], 403);
            }
            abort(403, 'Anda hanya bisa mengedit transaksi Anda sendiri.');
        }
        
        // Return JSON for AJAX requests
        if (request()->expectsJson()) {
            $transaksi->load(['user', 'service']);
            
            // Format the data properly for the frontend
            $transactionData = $transaksi->toArray();
            $transactionData['transaction_date'] = $transaksi->transaction_date 
                ? $transaksi->transaction_date->format('Y-m-d') 
                : null;
                
            return response()->json($transactionData);
        }
        
        // Return view for regular requests
        $services = \App\Models\Service::all();
        $users = \App\Models\User::all();
        return view('transaksi.edit', compact('transaksi', 'services', 'users'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(StoreTransactionRequest $request, Transaction $transaksi)
    {
        // Employees can only update their own transactions
        if (auth()->user()->role === 'karyawan' && $transaksi->user_id !== auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda hanya bisa mengedit transaksi Anda sendiri.'], 403);
            }
            abort(403, 'Anda hanya bisa mengedit transaksi Anda sendiri.');
        }
        
        $data = $request->validated();
        
        // For employees, automatically set their user_id for income transactions
        if (auth()->user()->role === 'karyawan' && $data['type'] === 'income') {
            $data['user_id'] = auth()->id();
        }
        
        $transaksi->update($data);
        
        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Transaksi berhasil diperbarui!']);
        }
        
        // Return with session flash for regular form submissions
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaction $transaksi)
    {
        // Employees can only delete their own transactions
        if (auth()->user()->role === 'karyawan' && $transaksi->user_id !== auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Anda hanya bisa menghapus transaksi Anda sendiri.'], 403);
            }
            abort(403, 'Anda hanya bisa menghapus transaksi Anda sendiri.');
        }
        
        $transaksi->delete();
        
        // Return JSON for AJAX requests
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Transaksi berhasil dihapus!']);
        }
        
        // Return with session flash for regular form submissions
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
