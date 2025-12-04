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
    // 1. FITUR PASIEN (BOOKING)
    // Form Buat Janji
    public function create()
    {
        $polis = Poli::all();
        return view('patient.appointments.create', compact('polis'));
    }

    // API: Ambil Dokter by Poli (AJAX)
    public function getDoctorsByPoli($poli_id)
    {
        $doctors = User::where('role', 'dokter')->where('poli_id', $poli_id)->get();
        return response()->json($doctors);
    }

    // API: Ambil Jadwal by Dokter (AJAX)
    public function getSchedulesByDoctor($doctor_id)
    {
        $schedules = Schedule::where('doctor_id', $doctor_id)->get();
        return response()->json($schedules);
    }

    // Simpan Janji Temu Baru
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required',
            'schedule_id' => 'required',
            'date' => 'required|date|after_or_equal:today',
            'complaint' => 'required',
        ]);

        // --- VALIDASI HARI PRAKTIK (Backend Security) ---
        $schedule = Schedule::findOrFail($request->schedule_id);
        
        // Cek hari dari tanggal yang dipilih
        $selectedDayEnglish = Carbon::parse($request->date)->format('l');
        $dayMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $translatedDay = $dayMap[$selectedDayEnglish] ?? '';

        // Bandingkan dengan hari di jadwal
        if ($translatedDay !== $schedule->day) {
            return back()
                ->withInput()
                ->withErrors(['date' => "Dokter ini hanya praktik pada hari {$schedule->day}, sedangkan tanggal yang Anda pilih adalah hari $translatedDay."]);
        }
        // ------------------------------------------------

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

    // 2. FITUR DOKTER & ADMIN (DAFTAR & APPROVAL)
    // Menampilkan Daftar Janji Temu
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Admin melihat semua data
            $appointments = Appointment::with(['patient', 'doctor', 'schedule'])->latest()->get();
        } elseif ($user->role === 'dokter') {
            // Dokter hanya melihat data miliknya
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

    // Update Status (Approve / Reject)
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            // Jika reject, alasan wajib diisi
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255',
        ]);

        $data = ['status' => $request->status];

        // Simpan alasan jika statusnya rejected
        if ($request->status == 'rejected') {
            $data['rejection_reason'] = $request->rejection_reason;
        }

        $appointment->update($data);

        return back()->with('success', 'Status janji temu diperbarui.');
    }

    // 3. FITUR FEEDBACK SYSTEM (PASIEN)
    // Simpan Rating & Ulasan
    public function storeFeedback(Request $request, Appointment $appointment)
    {
        // 1. Validasi Kepemilikan (Security)
        if ($appointment->patient_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak memberikan ulasan untuk janji temu ini.');
        }

        // 2. Validasi Status (Harus Selesai)
        if ($appointment->status !== 'selesai') {
            return back()->with('error', 'Feedback hanya bisa diberikan setelah kunjungan selesai.');
        }

        // 3. Validasi Duplikasi (Cek apakah sudah pernah rating)
        if ($appointment->rating != null) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk kunjungan ini.');
        }

        // 4. Validasi Input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
        ]);

        // 5. Update Database
        $appointment->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan dan rating Anda!');
    }
}