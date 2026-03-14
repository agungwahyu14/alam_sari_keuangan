<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display the layanan index view (SPA entry).
     */
    public function index()
    {
        return view('layanan.index');
    }

    /**
     * Return all services as JSON for DataTables.
     */
    public function data()
    {
        $services = Service::select(['id', 'name', 'price'])->get();
        \Log::info($services);
        return response()->json([
            'draw' => request()->get('draw'),
            'recordsTotal' => $services->count(),
            'recordsFiltered' => $services->count(),
            'data' => $services
        ]);
    }

    /**
     * Store a newly created service (AJAX).
     */
    public function store(Request $request)
    {
        // Prevent employees from creating services
        if (auth()->user()->role === 'karyawan') {
            return response()->json(['error' => 'Karyawan tidak diizinkan menambah layanan.'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|unique:services,name',
            'price' => 'required|integer|min:0',
        ]);
        $service = Service::create($validated);
        return response()->json(['success' => true, 'message' => 'Layanan berhasil ditambahkan.', 'service' => $service]);
    }

    /**
     * Return the specified service as JSON.
     */
    public function show(Service $layanan)
    {
        return response()->json($layanan);
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $layanan)
    {
        // Prevent employees from editing services
        if (auth()->user()->role === 'karyawan') {
            return response()->json(['error' => 'Karyawan tidak diizinkan mengedit layanan.'], 403);
        }
        
        return response()->json($layanan);
    }

    /**
     * Update the specified service (AJAX).
     */
    public function update(Request $request, Service $layanan)
    {
        // Prevent employees from updating services
        if (auth()->user()->role === 'karyawan') {
            return response()->json(['error' => 'Karyawan tidak diizinkan mengedit layanan.'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|unique:services,name,' . $layanan->id,
            'price' => 'required|integer|min:0',
        ]);
        $layanan->update($validated);
        return response()->json(['success' => true, 'message' => 'Layanan berhasil diperbarui.', 'service' => $layanan]);
    }

    /**
     * Delete the specified service (AJAX).
     */
    public function destroy(Service $layanan)
    {
        // Prevent employees from deleting services
        if (auth()->user()->role === 'karyawan') {
            return response()->json(['error' => 'Karyawan tidak diizinkan menghapus layanan.'], 403);
        }
        
        $layanan->delete();
        return response()->json(['success' => true, 'message' => 'Layanan berhasil dihapus.']);
    }
}
