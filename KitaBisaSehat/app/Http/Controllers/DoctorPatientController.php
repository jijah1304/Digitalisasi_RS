<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DoctorPatientController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = Auth::id();
        $search = $request->input('search');

        // Query: Ambil Appointment yang 'selesai' milik dokter ini
        // Kita gunakan 'with' untuk mengambil data pasien dan rekam medis
        $query = Appointment::with(['patient', 'medicalRecord'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'selesai');

        // Fitur Pencarian (Nama Pasien / Diagnosis)
        if ($search) {
            $query->where(function($q) use ($search) {
                // Cari berdasarkan Nama Pasien (via relasi patient)
                $q->whereHas('patient', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })
                // Atau cari berdasarkan Diagnosis (via relasi medicalRecord)
                ->orWhereHas('medicalRecord', function($subQ) use ($search) {
                    $subQ->where('diagnosis', 'like', "%{$search}%");
                });
            });
        }

        // Tampilkan hasil dengan pagination (10 per halaman)
        $histories = $query->latest()->paginate(10)->withQueryString();

        return view('doctor.patients.index', compact('histories'));
    }
}