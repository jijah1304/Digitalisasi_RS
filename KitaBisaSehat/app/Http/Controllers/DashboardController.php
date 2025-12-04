<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Import Carbon untuk waktu

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // === LOGIKA DASHBOARD ADMIN ===
        if ($user->role === 'admin') {
            // 1. Statistik Utama
            $totalPatients = User::where('role', 'pasien')->count();
            $totalDoctors = User::where('role', 'dokter')->count();
            $totalMedicines = Medicine::count();
            $pendingAppointments = Appointment::where('status', 'pending')->count();

            // 2. Daftar Dokter Bertugas HARI INI
            // Ambil nama hari ini dalam Bahasa Inggris (Monday, Tuesday...)
            $todayEnglish = Carbon::now()->format('l');
            
            // Translate ke Bahasa Indonesia (Sesuai database jadwal)
            $dayMap = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
            ];
            $todayIndo = $dayMap[$todayEnglish];

            // Query Dokter yang punya jadwal hari ini
            $doctorsOnDuty = User::where('role', 'dokter')
                ->whereHas('schedules', function ($query) use ($todayIndo) {
                    $query->where('day', $todayIndo);
                })
                ->with(['poli', 'schedules' => function ($query) use ($todayIndo) {
                    $query->where('day', $todayIndo); // Ambil spesifik jadwal hari ini saja
                }])
                ->get();

            return view('dashboard.admin', compact(
                'totalPatients', 
                'totalDoctors', 
                'totalMedicines', 
                'pendingAppointments',
                'doctorsOnDuty',
                'todayIndo'
            ));
        } 
        
        // === LOGIKA DASHBOARD DOKTER ===
        elseif ($user->role === 'dokter') {
            // Janji temu hari ini yang sudah disetujui (Approved)
            $todayAppointments = Appointment::where('doctor_id', $user->id)
                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                ->where('status', 'approved')
                ->get();
            
            // Hitung statistik kecil untuk dokter
            $pendingRequests = Appointment::where('doctor_id', $user->id)
                ->where('status', 'pending')
                ->count();

            return view('dashboard.doctor', compact('todayAppointments', 'pendingRequests'));
        } 
        
        // === LOGIKA DASHBOARD PASIEN ===
        else {
            // Riwayat janji temu terakhir
            $appointments = Appointment::where('patient_id', $user->id)->latest()->get();
            return view('dashboard.patient', compact('appointments'));
        }
    }
}