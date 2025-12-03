<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::all();
        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:keras,biasa',
            'stock' => 'required|integer',
            'description' => 'required',
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
            'name' => 'required',
            'type' => 'required',
            'stock' => 'required|integer',
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