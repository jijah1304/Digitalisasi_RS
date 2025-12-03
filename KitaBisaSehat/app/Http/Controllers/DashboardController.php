<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Data untuk Dashboard Admin: Jumlah pasien, dokter, obat
            $totalPatients = User::where('role', 'pasien')->count();
            $totalDoctors = User::where('role', 'dokter')->count();
            $totalMedicines = Medicine::count();
            // Janji temu yang butuh persetujuan
            $pendingAppointments = Appointment::where('status', 'pending')->count();

            return view('dashboard.admin', compact('totalPatients', 'totalDoctors', 'totalMedicines', 'pendingAppointments'));
        } 
        
        elseif ($user->role === 'dokter') {
            // Data untuk Dashboard Dokter
            // Janji temu hari ini yang sudah disetujui
            $todayAppointments = Appointment::where('doctor_id', $user->id)
                ->where('date', date('Y-m-d'))
                ->where('status', 'approved')
                ->get();
            
            return view('dashboard.doctor', compact('todayAppointments'));
        } 
        
        else {
            // Data untuk Dashboard Pasien: Riwayat janji temu terakhir
            $appointments = Appointment::where('patient_id', $user->id)->latest()->get();
            return view('dashboard.patient', compact('appointments'));
        }
    }
}