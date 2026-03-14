<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('karyawan.index');
    }

    /**
     * Get data for DataTables with salary calculation.
     */
    public function data()
    {
        $employees = User::isKaryawan()->get();
        $data = [];
        foreach ($employees as $employee) {
            $data[] = [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'bank_account' => $employee->bank_account
            ];
        }
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Prevent employees from creating users
        if (auth()->user()->role === 'karyawan') {
            return response()->json(['error' => 'Karyawan tidak diizinkan menambah karyawan.'], 403);
        }
        
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'bank_account' => 'nullable|string|max:255'
        ]);
        
        // Create new user
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'karyawan',
            'bank_account' => $validated['bank_account'] ?? null
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $karyawan)
    {
        return response()->json($karyawan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $karyawan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $karyawan)
    {
        // Prevent employees from updating users
        if (auth()->user()->role === 'karyawan') {
            return response()->json(['error' => 'Karyawan tidak diizinkan mengedit karyawan.'], 403);
        }
        
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($karyawan->id)
            ],
            'password' => 'nullable|string|min:8',
            'bank_account' => 'nullable|string|max:255'
        ]);
        
        // Update user data
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'bank_account' => $validated['bank_account'] ?? null
        ];
        
        // If password is provided, hash and include it
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }
        
        $karyawan->update($updateData);
        
        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $karyawan)
    {
        // Prevent employees from deleting users
        if (auth()->user()->role === 'karyawan') {
            return response()->json(['error' => 'Karyawan tidak diizinkan menghapus karyawan.'], 403);
        }
        
        $karyawan->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully'
        ]);
    }
}
