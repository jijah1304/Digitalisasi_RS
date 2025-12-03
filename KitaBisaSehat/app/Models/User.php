<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',     // 'admin', 'dokter', 'pasien'
        'poli_id',  // Khusus Dokter
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELASI ---

    // Dokter milik satu Poli
    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    // Dokter punya banyak jadwal
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'doctor_id');
    }

    // Dokter menerima banyak janji temu
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    // Pasien membuat banyak janji temu
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}