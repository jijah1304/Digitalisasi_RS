<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poli;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // 1. LAPORAN PASIEN PER POLI
        // Menghitung jumlah pasien yang ditangani (rekam medis dibuat) hari ini per poli
        $patientsPerPoli = DB::table('polis')
            ->select('polis.id', 'polis.name', DB::raw('COUNT(DISTINCT appointments.patient_id) as total_patients'))
            ->join('users as doctors', 'polis.id', '=', 'doctors.poli_id')
            ->join('appointments', 'doctors.id', '=', 'appointments.doctor_id')
            ->join('medical_records', 'appointments.id', '=', 'medical_records.appointment_id')
            ->whereDate('medical_records.created_at', Carbon::today())
            ->groupBy('polis.id', 'polis.name')
            ->get();

        // 2. LAPORAN KINERJA DOKTER
        // Mengurutkan dokter berdasarkan jumlah pasien yang SUDAH diperiksa (status: selesai)
        $doctorPerformance = User::where('role', 'dokter')
            ->withCount(['doctorAppointments as finished_appointments' => function($query) {
                $query->where('status', 'selesai');
            }])
            ->orderByDesc('finished_appointments') // Urutkan dari yang paling rajin
            ->take(10) // Ambil Top 10
            ->get();

        // 3. LAPORAN PENGGUNAAN OBAT (HARI INI)
        // Kita hitung dari pivot table 'medical_record_medicine'
        // Filter berdasarkan 'created_at' hari ini
        $medicineUsageToday = DB::table('medical_record_medicine')
            ->join('medicines', 'medical_record_medicine.medicine_id', '=', 'medicines.id')
            ->select('medicines.name', DB::raw('SUM(medical_record_medicine.quantity) as total_usage'))
            ->whereDate('medical_record_medicine.created_at', Carbon::today())
            ->groupBy('medicines.name')
            ->orderByDesc('total_usage')
            ->get();

        // Total Obat Keluar Hari Ini (Angka Keseluruhan)
        $totalMedicineItemsOut = $medicineUsageToday->sum('total_usage');

        return view('admin.reports.index', compact(
            'patientsPerPoli', 
            'doctorPerformance', 
            'medicineUsageToday',
            'totalMedicineItemsOut'
        ));
    }
}