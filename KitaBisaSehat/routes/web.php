<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;

// 1. HALAMAN PUBLIK (Guest)
Route::get('/', function () {
    return view('welcome'); // Nanti kita ubah jadi landing page
});
// 2. RUTE YANG BUTUH LOGIN (Authenticated)
Route::middleware(['auth'])->group(function () {
    // --- DASHBOARD (Redirect sesuai role) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // --- FITUR UMUM (Bisa diakses user yang login) ---
    // List Janji Temu (Tampilan beda tiap role diatur Controller)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    // Lihat Detail Rekam Medis (Pasien & Dokter)
    Route::get('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'show'])->name('medical_records.show');

    // AREA ADMIN
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Kelola Poli & Obat
        Route::resource('polis', PoliController::class);
        Route::resource('medicines', MedicineController::class);
        // Admin bisa Approve/Reject Janji Temu
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
            ->name('admin.appointments.status');
    });

    // AREA DOKTER
    Route::middleware(['role:dokter'])->prefix('doctor')->group(function () {
        // Kelola Jadwal Praktik
        Route::resource('schedules', ScheduleController::class);
        // Dokter bisa Approve/Reject Janji Temu (Sama fungsinya dgn Admin)
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
            ->name('doctor.appointments.status');
        // Input Rekam Medis (Hanya bisa dari Janji Temu yang Approved)
        Route::get('/appointments/{appointment}/record/create', [MedicalRecordController::class, 'create'])
            ->name('medical_records.create');
        Route::post('/appointments/{appointment}/record', [MedicalRecordController::class, 'store'])
            ->name('medical_records.store');
    });

    // AREA PASIEN
    Route::middleware(['role:pasien'])->prefix('patient')->group(function () {
        // Buat Janji Temu Baru
        Route::get('/appointments/create', [AppointmentController::class, 'create'])
            ->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])
            ->name('appointments.store');

        Route::get('/get-doctors/{poli_id}', [AppointmentController::class, 'getDoctorsByPoli']);
        Route::get('/get-schedules/{doctor_id}', [AppointmentController::class, 'getSchedulesByDoctor']);
    });

});

require __DIR__.'/auth.php';