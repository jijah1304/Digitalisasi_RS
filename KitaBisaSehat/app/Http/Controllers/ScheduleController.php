<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        // Hanya tampilkan jadwal milik dokter yang sedang login
        $schedules = Schedule::where('doctor_id', Auth::id())->get();
        return view('doctor.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('doctor.schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'start_time' => 'required',
        ]);

        // Hitung End Time otomatis (30 menit) [cite: 48]
        $startTime = \Carbon\Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes(30);

        Schedule::create([
            'doctor_id' => Auth::id(),
            'day' => $request->day,
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
        ]);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil dibuat');
    }

    public function edit(Schedule $schedule)
    {
        // Pastikan dokter hanya mengedit jadwalnya sendiri
        if ($schedule->doctor_id !== Auth::id()) abort(403);
        return view('doctor.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->doctor_id !== Auth::id()) abort(403);
        
        $startTime = \Carbon\Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes(30);

        $schedule->update([
            'day' => $request->day,
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
        ]);

        return redirect()->route('schedules.index')->with('success', 'Jadwal diperbarui');
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->doctor_id !== Auth::id()) abort(403);
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Jadwal dihapus');
    }
}