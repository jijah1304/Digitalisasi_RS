<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorPatientController;

// 1. HALAMAN PUBLIK (Guest)
Route::get('/', function () {
    return view('welcome');
});

// 2. RUTE YANG BUTUH LOGIN (Authenticated)
Route::middleware(['auth'])->group(function () {
    
    // --- DASHBOARD ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // --- FITUR UMUM ---
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'show'])->name('medical_records.show');

    // AREA ADMIN
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::resource('polis', PoliController::class);
        Route::resource('medicines', MedicineController::class);
        
        // Resource User (Admin: Manage User)
        Route::resource('users', UserController::class)->names('admin.users');

        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
            ->name('admin.appointments.status');
        // Laporan Analitik
        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');
    });

    // AREA DOKTER
    Route::middleware(['role:dokter'])->prefix('doctor')->group(function () {
        Route::resource('schedules', ScheduleController::class);
        
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
            ->name('doctor.appointments.status');
        
        // --- MEDICAL RECORDS (Create, Store, Edit, Update, Destroy) ---
        
        // 1. Create & Store (Dari Appointment)
        Route::get('/appointments/{appointment}/record/create', [MedicalRecordController::class, 'create'])
            ->name('medical_records.create');
        Route::post('/appointments/{appointment}/record', [MedicalRecordController::class, 'store'])
            ->name('medical_records.store');

        // 2. Edit & Update (Tambahan Baru)
        Route::get('/medical-records/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])
            ->name('medical_records.edit');
        Route::put('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'update'])
            ->name('medical_records.update');
            
        // 3. Delete (Tambahan Baru)
        Route::delete('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'destroy'])
            ->name('medical_records.destroy');

        // --- RIWAYAT PASIEN (DOKTER) ---
        Route::get('/my-patients', [DoctorPatientController::class, 'index'])
            ->name('doctor.patients.index');
    });

    // AREA PASIEN
    Route::middleware(['role:pasien'])->prefix('patient')->group(function () {
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/get-doctors/{poli_id}', [AppointmentController::class, 'getDoctorsByPoli']);
        Route::get('/get-schedules/{doctor_id}', [AppointmentController::class, 'getSchedulesByDoctor']);
    });
    
    // --- PROFILE USER (Bawaan Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';