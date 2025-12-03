<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'date',
        'complaint',
        'status',           // pending, approved, rejected, selesai
        'rejection_reason',
    ];

    // Milik Pasien
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Milik Dokter
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Berdasarkan Jadwal tertentu
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Punya satu Rekam Medis (jika sudah diperiksa)
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }
}