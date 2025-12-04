<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Poli;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        // --- VALIDASI HARI PRAKTIK ---
        $schedule = Schedule::findOrFail($request->schedule_id);
        $selectedDayEnglish = Carbon::parse($request->date)->format('l');
        $dayMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $translatedDay = $dayMap[$selectedDayEnglish] ?? '';

        if ($translatedDay !== $schedule->day) {
            return back()
                ->withInput()
                ->withErrors(['date' => "Dokter ini hanya praktik pada hari {$schedule->day}, sedangkan tanggal yang Anda pilih adalah hari $translatedDay."]);
        }
        // -----------------------------

        Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'date' => $request->date,
            'complaint' => $request->complaint,
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard')->with('success', 'Janji temu berhasil dibuat, mohon tunggu konfirmasi.');
    }

    // === FITUR DOKTER & ADMIN (APPROVAL) ===

    // 3. Daftar Janji Temu Masuk
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $appointments = Appointment::with(['patient', 'doctor', 'schedule'])->latest()->get();
        } elseif ($user->role === 'dokter') {
            $appointments = Appointment::with(['patient', 'schedule'])
                ->where('doctor_id', $user->id)
                ->latest()
                ->get();
        } else {
            $appointments = Appointment::where('patient_id', $user->id)->latest()->get();
        }

        return view('appointments.index', compact('appointments'));
    }

    // 4. Update Status (Approve/Reject dengan Alasan)
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            // Jika status rejected, alasan wajib diisi
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255',
        ]);

        $data = ['status' => $request->status];

        // Simpan alasan jika ditolak
        if ($request->status == 'rejected') {
            $data['rejection_reason'] = $request->rejection_reason;
        }

        $appointment->update($data);

        return back()->with('success', 'Status janji temu diperbarui.');
    }
}