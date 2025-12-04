<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan Daftar User (Dengan Filter)
    public function index(Request $request)
    {
        $query = User::query();

        // 1. Filter Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 2. Filter Tanggal Registrasi
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // 3. Pencarian Nama/Email (Opsional tapi berguna)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Form Tambah User
    public function create()
    {
        $polis = Poli::all(); // Untuk dropdown jika role dokter
        return view('admin.users.create', compact('polis'));
    }

    // Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,dokter,pasien'],
            'poli_id' => ['nullable', 'required_if:role,dokter', 'exists:polis,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'poli_id' => $request->role === 'dokter' ? $request->poli_id : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // (Edit & Delete bisa ditambahkan jika perlu, untuk sekarang Index & Create dulu sesuai permintaan filter)
}