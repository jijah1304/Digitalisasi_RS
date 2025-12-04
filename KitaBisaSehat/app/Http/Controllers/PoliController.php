<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\Request;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::all();
        return view('admin.polis.index', compact('polis'));
    }

    public function create()
    {
        return view('admin.polis.create');
    }

    public function store(Request $request)
    {
        // Validation: Image di sini kita anggap sebagai 'Nama Ikon' atau 'URL Gambar'
        $request->validate([
            'name' => 'required|unique:polis|max:255',
            'description' => 'required|string',
            'image' => 'required|string', // Ubah jadi string, bukan 'image|mimes:...'
        ]);

        Poli::create($request->all());

        return redirect()->route('polis.index')->with('success', 'Poli berhasil ditambahkan');
    }

    public function edit(Poli $poli)
    {
        return view('admin.polis.edit', compact('poli'));
    }

    public function update(Request $request, Poli $poli)
    {
        $request->validate([
            'name' => 'required|unique:polis,name,' . $poli->id . '|max:255',
            'description' => 'required|string',
            'image' => 'required|string',
        ]);

        $poli->update($request->all());

        return redirect()->route('polis.index')->with('success', 'Poli berhasil diperbarui');
    }

    public function destroy(Poli $poli)
    {
        $poli->delete();
        return redirect()->route('polis.index')->with('success', 'Poli berhasil dihapus');
    }
}