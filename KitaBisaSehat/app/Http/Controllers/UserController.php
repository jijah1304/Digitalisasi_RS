<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Tambahkan import Auth

class UserController extends Controller
{
    // Menampilkan Daftar User (Dengan Filter & Sorting)
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

        // 3. Pencarian Nama/Email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 4. Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Form Tambah User
    public function create()
    {
        $polis = Poli::all();
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

    // Hapus User (FITUR BARU)
    public function destroy(User $user)
    {
        // Mencegah admin menghapus dirinya sendiri saat sedang login
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        // Hapus user (Data terkait seperti appointment akan terhapus otomatis jika onDelete cascade aktif di migration)
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}