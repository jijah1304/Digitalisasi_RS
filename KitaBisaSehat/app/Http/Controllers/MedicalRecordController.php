<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    // Form Periksa Pasien (Hanya bisa dibuka jika status Approved)
    public function create(Appointment $appointment)
    {
        if ($appointment->status !== 'approved') {
            return back()->with('error', 'Pasien belum disetujui untuk diperiksa.');
        }

        $medicines = Medicine::where('stock', '>', 0)->get();
        return view('doctor.medical_records.create', compact('appointment', 'medicines'));
    }

    // Simpan Rekam Medis & Resep
    public function store(Request $request, Appointment $appointment)
    {
        $request->validate([
            'diagnosis' => 'required',
            'medical_action' => 'required',
            'medicines' => 'array', // Array ID obat
            'quantities' => 'array', // Array jumlah obat
        ]);

        // Gunakan Transaksi Database agar aman (konsistensi data)
        DB::transaction(function () use ($request, $appointment) {
            
            // 1. Buat Rekam Medis [cite: 85]
            $record = MedicalRecord::create([
                'appointment_id' => $appointment->id,
                'diagnosis' => $request->diagnosis,
                'medical_action' => $request->medical_action,
                'notes' => $request->notes,
            ]);

            // 2. Simpan Resep Obat & Kurangi Stok [cite: 86]
            if ($request->medicines) {
                foreach ($request->medicines as $index => $medicineId) {
                    $quantity = $request->quantities[$index] ?? 1;
                    
                    // Attach ke pivot table
                    $record->medicines()->attach($medicineId, ['quantity' => $quantity]);
                    
                    // Kurangi stok master
                    Medicine::where('id', $medicineId)->decrement('stock', $quantity);
                }
            }

            // 3. Ubah Status Janji Temu jadi 'Selesai' [cite: 88]
            $appointment->update(['status' => 'selesai']);
        });

        return redirect()->route('dashboard')->with('success', 'Pemeriksaan selesai & rekam medis disimpan.');
    }
    
    // Lihat Detail Rekam Medis (Bisa dilihat Pasien/Dokter)
    public function show(MedicalRecord $medicalRecord)
    {
        return view('medical_records.show', compact('medicalRecord'));
    }
}