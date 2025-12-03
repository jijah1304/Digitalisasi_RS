<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',     // 'keras', 'biasa'
        'stock',
        'image',
    ];

    // Obat bisa ada di banyak Rekam Medis (Resep)
    public function medicalRecords()
    {
        return $this->belongsToMany(MedicalRecord::class, 'medical_record_medicine')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}