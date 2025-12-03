<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'diagnosis',
        'medical_action',
        'notes',
    ];

    // Terhubung ke satu Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Punya banyak Obat (via Pivot Table)
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'medical_record_medicine')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}