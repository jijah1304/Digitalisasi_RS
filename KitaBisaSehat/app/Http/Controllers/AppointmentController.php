<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Poli;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // === FITUR PASIEN ===

    // 1. Form Buat Janji (Pasien) [cite: 64]
    public function create()
    {
        $polis = Poli::all();
        return view('patient.appointments.create', compact('polis'));
    }

    // API Kecil untuk mendapatkan Dokter berdasarkan Poli (dipanggil via AJAX/JS nanti)
    public function getDoctorsByPoli($poli_id)
    {
        $doctors = User::where('role', 'dokter')->where('poli_id', $poli_id)->get();
        return response()->json($doctors);
    }

    // API Kecil untuk mendapatkan Jadwal Dokter
    public function getSchedulesByDoctor($doctor_id)
    {
        $schedules = Schedule::where('doctor_id', $doctor_id)->get();
        return response()->json($schedules);
    }

    // 2. Simpan Janji Temu (Pasien)
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required',
            'schedule_id' => 'required',
            'date' => 'required|date|after_or_equal:today',
            'complaint' => 'required',
        ]);

        Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'date' => $request->date,
            'complaint' => $request->complaint,
            'status' => 'pending' // Default pending [cite: 70]
        ]);

        return redirect()->route('dashboard')->with('success', 'Janji temu berhasil dibuat, mohon tunggu konfirmasi.');
    }

    // === FITUR DOKTER & ADMIN (APPROVAL) ===

    // 3. Daftar Janji Temu Masuk (Untuk Admin & Dokter) [cite: 75, 77]
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Admin melihat semua
            $appointments = Appointment::with(['patient', 'doctor', 'schedule'])->latest()->get();
        } elseif ($user->role === 'dokter') {
            // Dokter hanya melihat janji untuk dirinya
            $appointments = Appointment::with(['patient', 'schedule'])
                ->where('doctor_id', $user->id)
                ->latest()
                ->get();
        } else {
            // Pasien melihat riwayatnya sendiri
            $appointments = Appointment::where('patient_id', $user->id)->latest()->get();
        }

        return view('appointments.index', compact('appointments'));
    }

    // 4. Update Status (Approve/Reject) [cite: 78]
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status janji temu diperbarui.');
    }
}