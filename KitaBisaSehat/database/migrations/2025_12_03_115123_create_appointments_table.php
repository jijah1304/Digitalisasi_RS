<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete(); // [cite: 70]
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete(); // [cite: 70]
            $table->date('date'); // Tanggal Booking
            $table->text('complaint'); // Keluhan Singkat [cite: 70]
            $table->enum('status', ['pending', 'approved', 'rejected', 'selesai'])->default('pending'); // [cite: 70, 78, 88]
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments_tabel');
    }
};
