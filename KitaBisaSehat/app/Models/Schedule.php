<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'day',          // Senin, Selasa, dll
        'start_time',
        'end_time',
    ];

    // Jadwal milik satu Dokter
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Satu jadwal bisa dipesan banyak Appointment (di tanggal berbeda)
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}