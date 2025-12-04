<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // Menampilkan Daftar Obat (Dengan Filter)
    public function index(Request $request)
    {
        $query = Medicine::query();

        // 1. Filter Pencarian Nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. Filter Status Stok
        if ($request->filled('status')) {
            if ($request->status == 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->status == 'unavailable') {
                $query->where('stock', '<=', 0);
            }
        }

        // 3. Filter Tipe Obat
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Urutkan stok paling sedikit dulu agar admin sadar, lalu yang terbaru
        $medicines = $query->orderBy('stock', 'asc')
                         ->latest()
                         ->paginate(10)
                         ->withQueryString();

        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:keras,biasa',
            'stock' => 'required|integer|min:0', // Tambahkan min:0 agar tidak negatif
            'description' => 'required|string',
        ]);

        Medicine::create($request->all());

        return redirect()->route('medicines.index')->with('success', 'Obat berhasil ditambahkan');
    }

    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        // Perbaikan: Validasi harus sama lengkapnya dengan store
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:keras,biasa',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string', // Jangan lupa validasi deskripsi saat update
        ]);

        $medicine->update($request->all());

        return redirect()->route('medicines.index')->with('success', 'Obat berhasil diperbarui');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Obat dihapus');
    }
}