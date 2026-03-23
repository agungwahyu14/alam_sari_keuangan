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
        $services = Service::select(['id', 'name', 'property_type', 'location', 'price', 'status'])->get();
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
        if (auth()->user()->role === 'agen') {
            return response()->json(['error' => 'Karyawan tidak dapat menambah properti (hanya admin).'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|unique:services,name',
            'property_type' => 'required|in:rumah,tanah,ruko,apartemen,villa,gudang',
            'location' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'status' => 'required|in:available,sold,pending',
            'description' => 'nullable|string',
        ]);
        $service = Service::create($validated);
        return response()->json(['success' => true, 'message' => 'Properti berhasil ditambahkan.', 'service' => $service]);
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
        if (auth()->user()->role === 'agen') {
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
        if (auth()->user()->role === 'agen') {
            return response()->json(['error' => 'Karyawan tidak dapat mengedit properti (hanya admin).'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|unique:services,name,' . $layanan->id,
            'property_type' => 'required|in:rumah,tanah,ruko,apartemen,villa,gudang',
            'location' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'status' => 'required|in:available,sold,pending',
            'description' => 'nullable|string',
        ]);
        $layanan->update($validated);
        return response()->json(['success' => true, 'message' => 'Properti berhasil diperbarui.', 'service' => $layanan]);
    }

    /**
     * Delete the specified service (AJAX).
     */
    public function destroy(Service $layanan)
    {
        // Prevent employees from deleting services
        if (auth()->user()->role === 'agen') {
            return response()->json(['error' => 'Karyawan tidak dapat menghapus properti (hanya admin).'], 403);
        }
        
        $layanan->delete();
        return response()->json(['success' => true, 'message' => 'Properti berhasil dihapus.']);
    }
}
