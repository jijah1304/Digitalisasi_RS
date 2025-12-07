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
                $appointment->update(['status' => 'obat_diterima']);
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

        // Access control: Admin, Doctor (who created), or Patient (own record)
        if (Auth::user()->role === 'admin') {
            // Admin can view all
        } elseif (Auth::user()->role === 'dokter' && $medicalRecord->appointment->doctor_id === Auth::id()) {
            // Doctor can view their own patients' records
        } elseif (Auth::user()->role === 'pasien' && $medicalRecord->appointment->patient_id === Auth::id()) {
            // Patient can view their own records
        } else {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Pastikan appointment selesai (untuk pasien melihat hasil)
        if (Auth::user()->role === 'pasien' && $medicalRecord->appointment->status !== 'selesai') {
            abort(403, 'Rekam medis belum selesai diproses.');
        }

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
            'date' => 'required|date', // Tambah validasi tanggal
        ]);

        try {
            DB::transaction(function () use ($request, $medicalRecord) {
                // 1. Update Data Medis
                $medicalRecord->update([
                    'diagnosis' => $request->diagnosis,
                    'medical_action' => $request->medical_action,
                    'notes' => $request->notes,
                ]);

                // 2. Update Tanggal di Appointment (Tabel Relasi)
                // Ini penting karena tanggal disimpan di tabel appointments, bukan medical_records
                $medicalRecord->appointment->update([
                    'date' => $request->date
                ]);

                // 3. LOGIKA STOK OBAT
                foreach ($medicalRecord->medicines as $oldMed) {
                    $oldMed->increment('stock', $oldMed->pivot->quantity);
                }
                $medicalRecord->medicines()->detach();

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

    public function patientIndex()
    {
        $patient = auth()->user();

        // Get all medical records for the current patient
        $medicalRecords = MedicalRecord::with(['appointment.doctor.poli', 'medicines'])
            ->whereHas('appointment', function($query) use ($patient) {
                $query->where('patient_id', $patient->id)
                      ->where('status', 'selesai');
            })
            ->latest()
            ->paginate(10);

        return view('patient.medical_records.index', compact('medicalRecords'));
    }

    public function patientPrescriptions()
    {
        $patient = auth()->user();

        // Get all prescriptions for the current patient
        $prescriptions = MedicalRecord::with(['appointment.doctor.poli', 'medicines'])
            ->whereHas('appointment', function($query) use ($patient) {
                $query->where('patient_id', $patient->id)
                      ->where('status', 'selesai');
            })
            ->latest()
            ->paginate(10);

        return view('patient.prescriptions.index', compact('prescriptions'));
    }
}
