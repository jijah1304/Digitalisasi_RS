<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Poli;
use App\Models\Medicine;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\MedicalRecord;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED MASTER DATA POLI [cite: 32]
        $poliUmum = Poli::create([
            'name' => 'Poli Umum',
            'description' => 'Penanganan penyakit umum dan konsultasi kesehatan dasar.',
            'image' => 'poli-umum.jpg'
        ]);

        $poliGigi = Poli::create([
            'name' => 'Poli Gigi',
            'description' => 'Perawatan kesehatan gigi dan mulut.',
            'image' => 'poli-gigi.jpg'
        ]);

        $poliAnak = Poli::create([
            'name' => 'Poli Anak',
            'description' => 'Layanan kesehatan khusus bayi dan anak-anak.',
            'image' => 'poli-anak.jpg'
        ]);

        // 2. SEED MASTER DATA OBAT [cite: 52]
        $paracetamol = Medicine::create([
            'name' => 'Paracetamol 500mg',
            'description' => 'Obat pereda nyeri dan penurun demam.',
            'type' => 'biasa',
            'stock' => 100,
            'image' => 'paracetamol.jpg'
        ]);

        $amoxicillin = Medicine::create([
            'name' => 'Amoxicillin 500mg',
            'description' => 'Antibiotik untuk infeksi bakteri.',
            'type' => 'keras',
            'stock' => 50,
            'image' => 'amoxicillin.jpg'
        ]);

        $vitaminC = Medicine::create([
            'name' => 'Vitamin C 1000mg',
            'description' => 'Suplemen untuk daya tahan tubuh.',
            'type' => 'biasa',
            'stock' => 200,
            'image' => 'vitaminc.jpg'
        ]);

        // 3. SEED USERS (Admin, Dokter, Pasien) [cite: 9]
        // A. Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@rs.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // B. Dokter (Dr. Budi - Poli Umum) [cite: 25]
        $drBudi = User::create([
            'name' => 'dr. Budi Santoso',
            'email' => 'drbudi@rs.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
            'poli_id' => $poliUmum->id
        ]);

        // C. Dokter (Dr. Siti - Poli Gigi)
        $drSiti = User::create([
            'name' => 'drg. Siti Aminah',
            'email' => 'drsiti@rs.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
            'poli_id' => $poliGigi->id
        ]);

        // D. Pasien (guweh) [cite: 15]
        $pasienAzizah = User::create([
            'name' => 'Azizah Nurul',
            'email' => 'azizah@mail.com',
            'password' => Hash::make('password'),
            'role' => 'pasien',
        ]);

        // 4. SEED JADWAL DOKTER [cite: 42]
        // Aturan: Durasi 30 menit
        // Jadwal dr. Budi (Senin 08:00 - 08:30 dan 08:30 - 09:00)
        $scheduleBudi1 = Schedule::create([
            'doctor_id' => $drBudi->id,
            'day' => 'Senin',
            'start_time' => '08:00',
            'end_time' => '08:30'
        ]);

        $scheduleBudi2 = Schedule::create([
            'doctor_id' => $drBudi->id,
            'day' => 'Senin',
            'start_time' => '08:30',
            'end_time' => '09:00'
        ]);

        // Jadwal dr. Siti (Selasa 09:00 - 09:30)
        $scheduleSiti1 = Schedule::create([
            'doctor_id' => $drSiti->id,
            'day' => 'Selasa',
            'start_time' => '09:00',
            'end_time' => '09:30'
        ]);

        // 5. SEED JANJI TEMU (APPOINTMENTS) [cite: 63]
        // Kasus 1: Janji Temu "Pending" (Baru booking)
        Appointment::create([
            'patient_id' => $pasienAzizah->id,
            'doctor_id' => $drBudi->id,
            'schedule_id' => $scheduleBudi2->id,
            'date' => now()->addDays(1)->format('Y-m-d'), // Besok
            'complaint' => 'Demam tinggi dan pusing.',
            'status' => 'pending'
        ]);

        // Kasus 2: Janji Temu "Selesai" (Sudah diperiksa - Histori)
        // Ini agar kita bisa demo fitur Rekam Medis
        $appointmentSelesai = Appointment::create([
            'patient_id' => $pasienAzizah->id,
            'doctor_id' => $drBudi->id,
            'schedule_id' => $scheduleBudi1->id,
            'date' => now()->subDays(2)->format('Y-m-d'), // 2 hari lalu
            'complaint' => 'Sakit tenggorokan.',
            'status' => 'selesai'
        ]);

        // 6. SEED REKAM MEDIS (MEDICAL RECORD) [cite: 79]
        // Untuk Janji Temu yang sudah selesai
        $record = MedicalRecord::create([
            'appointment_id' => $appointmentSelesai->id,
            'diagnosis' => 'Radang Tenggorokan Akut',
            'medical_action' => 'Pemeriksaan fisik dan pemberian obat.',
            'notes' => 'Pasien disarankan istirahat total selama 2 hari.'
        ]);

        // Lampirkan Resep Obat ke Rekam Medis (Pivot Table) [cite: 86]
        // Pasien dikasih: 1 Strip Amoxicillin & 1 Strip Vitamin C
        $record->medicines()->attach([
            $amoxicillin->id => ['quantity' => 1],
            $vitaminC->id => ['quantity' => 1]
        ]);
    }
}