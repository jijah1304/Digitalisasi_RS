<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    // --- 1. CREATE (Form Pemeriksaan) ---
    public function create(Appointment $appointment)
    {
        if ($appointment->status !== 'approved') {
            return back()->with('error', 'Pasien belum disetujui untuk diperiksa.');
        }

        $medicines = Medicine::where('stock', '>', 0)->get();
        return view('doctor.medical_records.create', compact('appointment', 'medicines'));
    }

    // --- 2. STORE (Simpan Baru) ---
    public function store(Request $request, Appointment $appointment)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'medical_action' => 'required|string',
            'medicines' => 'required|array',
            'quantities' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request, $appointment) {
                // Simpan Record
                $record = MedicalRecord::create([
                    'appointment_id' => $appointment->id,
                    'diagnosis' => $request->diagnosis,
                    'medical_action' => $request->medical_action,
                    'notes' => $request->notes,
                ]);

                // Simpan Resep & Kurangi Stok
                foreach ($request->medicines as $index => $medicineId) {
                    $quantity = $request->quantities[$index];
                    $record->medicines()->attach($medicineId, ['quantity' => $quantity]);
                    Medicine::where('id', $medicineId)->decrement('stock', $quantity);
                }

                // Update Status Janji Temu
                $appointment->update(['status' => 'selesai']);
            });

            return redirect()->route('dashboard')->with('success', 'Rekam medis berhasil disimpan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // --- 3. SHOW (Lihat Detail) ---
    public function show($id)
    {
        $medicalRecord = MedicalRecord::with(['appointment.patient', 'appointment.doctor', 'medicines'])->findOrFail($id);
        return view('medical_records.show', compact('medicalRecord'));
    }

    // --- 4. EDIT (Form Edit) - BARU ---
    public function edit(MedicalRecord $medicalRecord)
    {
        // Validasi: Hanya dokter pembuat yang boleh edit
        if ($medicalRecord->appointment->doctor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $medicines = Medicine::all(); // Ambil semua obat
        return view('doctor.medical_records.edit', compact('medicalRecord', 'medicines'));
    }

    // --- 5. UPDATE (Simpan Perubahan) - BARU ---
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'medical_action' => 'required|string',
            'medicines' => 'required|array',
            'quantities' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request, $medicalRecord) {
                // A. Update Teks
                $medicalRecord->update([
                    'diagnosis' => $request->diagnosis,
                    'medical_action' => $request->medical_action,
                    'notes' => $request->notes,
                ]);

                // B. LOGIKA STOK OBAT
                // 1. Kembalikan stok lama ke gudang dulu
                foreach ($medicalRecord->medicines as $oldMed) {
                    $oldMed->increment('stock', $oldMed->pivot->quantity);
                }

                // 2. Hapus semua resep lama
                $medicalRecord->medicines()->detach();

                // 3. Masukkan resep baru & Kurangi stok baru
                foreach ($request->medicines as $index => $medicineId) {
                    $quantity = $request->quantities[$index];
                    $medicalRecord->medicines()->attach($medicineId, ['quantity' => $quantity]);
                    Medicine::where('id', $medicineId)->decrement('stock', $quantity);
                }
            });

            return redirect()->route('medical_records.show', $medicalRecord->id)
                             ->with('success', 'Rekam medis berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    // --- 6. DESTROY (Hapus) - BARU ---
    public function destroy(MedicalRecord $medicalRecord)
    {
        // Validasi akses
        if ($medicalRecord->appointment->doctor_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($medicalRecord) {
                // 1. Kembalikan stok obat ke gudang
                foreach ($medicalRecord->medicines as $med) {
                    $med->increment('stock', $med->pivot->quantity);
                }

                // 2. Hapus Pivot & Record
                $medicalRecord->medicines()->detach();
                $medicalRecord->delete();

                // 3. Kembalikan status Appointment jadi Approved (bisa diperiksa ulang)
                $medicalRecord->appointment->update(['status' => 'approved']);
            });

            return redirect()->route('dashboard')->with('success', 'Rekam medis dihapus & Stok obat dikembalikan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}