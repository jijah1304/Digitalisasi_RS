<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

            // 3. Notifikasi Obat
            $medicinesNeedingNotification = Medicine::getMedicinesNeedingNotification();

            return view('dashboard.admin', compact(
                'totalPatients',
                'totalDoctors',
                'totalMedicines',
                'pendingAppointments',
                'doctorsOnDuty',
                'todayIndo',
                'medicinesNeedingNotification'
            ));
        } 
        
        // === LOGIKA DASHBOARD DOKTER ===
        elseif ($user->role === 'dokter') {
            // 1. Antrean Hari Ini (Approved)
            $todayAppointments = Appointment::where('doctor_id', $user->id)
                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                ->where('status', 'approved')
                ->get();
            
            // 2. Jumlah Janji Temu Pending (Perlu Validasi)
            $pendingRequests = Appointment::where('doctor_id', $user->id)
                ->where('status', 'pending')
                ->count();

            // 3. Pasien Terbaru yang Diperiksa (Status Menunggu Obat atau Selesai) - Ambil 5 Terakhir
            // Mengambil janji temu yang memiliki medical record milik dokter ini, diurutkan dari yang terbaru
            $recentPatients = Appointment::with(['patient', 'medicalRecord'])
                ->where('doctor_id', $user->id)
                ->whereHas('medicalRecord') // Pastikan ada medical record
                ->whereIn('status', ['menunggu_pasien_ambil_obat', 'selesai']) // Status menunggu obat atau selesai
                ->latest('updated_at') // Urutkan berdasarkan waktu update terakhir
                ->take(5)
                ->get();

            return view('dashboard.doctor', compact('todayAppointments', 'pendingRequests', 'recentPatients'));
        } 
        
        // === LOGIKA DASHBOARD PASIEN ===
        else {
            // Riwayat janji temu terakhir
            $appointments = Appointment::where('patient_id', $user->id)->latest()->get();

            // Notifikasi untuk pasien
            $approvedAppointments = Appointment::where('patient_id', $user->id)
                ->where('status', 'approved')
                ->count();

            $medicineReadyAppointments = Appointment::where('patient_id', $user->id)
                ->where('status', 'obat_diterima')
                ->whereHas('medicalRecord')
                ->count();

            return view('dashboard.patient', compact('appointments', 'approvedAppointments', 'medicineReadyAppointments'));
        }
    }
}