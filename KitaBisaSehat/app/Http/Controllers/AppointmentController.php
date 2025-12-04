<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Poli;
use App\Models\User;
use App\Models\Schedule; // Pastikan Model Schedule di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Pastikan Carbon di-import untuk manipulasi tanggal

class AppointmentController extends Controller
{
    // === FITUR PASIEN ===

    // 1. Form Buat Janji (Pasien)
    public function create()
    {
        $polis = Poli::all();
        return view('patient.appointments.create', compact('polis'));
    }

    // API Kecil untuk mendapatkan Dokter berdasarkan Poli
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

        // --- VALIDASI HARI PRAKTIK (LOGIKA BARU) ---
        
        // 1. Ambil data jadwal yang dipilih user
        $schedule = Schedule::findOrFail($request->schedule_id);

        // 2. Ambil nama hari dari tanggal yang dipilih (Format Inggris: Monday, Tuesday...)
        $selectedDayEnglish = Carbon::parse($request->date)->format('l');

        // 3. Mapping Hari Inggris ke Indonesia (Sesuai Database)
        $dayMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        
        $translatedDay = $dayMap[$selectedDayEnglish] ?? '';

        // 4. Bandingkan: Jika hari tanggal beda dengan hari jadwal, tolak!
        if ($translatedDay !== $schedule->day) {
            return back()
                ->withInput() // Kembalikan inputan user agar tidak mengetik ulang
                ->withErrors(['date' => "Dokter ini hanya praktik pada hari {$schedule->day}, sedangkan tanggal yang Anda pilih adalah hari $translatedDay."]);
        }
        // -------------------------------------------

        Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'date' => $request->date,
            'complaint' => $request->complaint,
            'status' => 'pending' // Default pending
        ]);

        return redirect()->route('dashboard')->with('success', 'Janji temu berhasil dibuat, mohon tunggu konfirmasi.');
    }

    // === FITUR DOKTER & ADMIN (APPROVAL) ===

    // 3. Daftar Janji Temu Masuk
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

    // 4. Update Status (Approve/Reject)
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