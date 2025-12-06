<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status == 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->status == 'unavailable') {
                $query->where('stock', '<=', 0);
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

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
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'required|string', // Image sebagai string (URL/Nama File)
            'expiry_date' => 'required|date|after:today',
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
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:keras,biasa',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'required|string',
            'expiry_date' => 'required|date',
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