<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        // Fetch all polyclinics
        $polis = Poli::all();

        // Fetch all doctors with their poli
        $doctors = User::where('role', 'dokter')
            ->with('poli')
            ->get();

        // Fetch all schedules with doctor and poli
        $schedules = Schedule::with(['doctor.poli'])
            ->get()
            ->groupBy('doctor_id');

        return view('guest.index', compact('polis', 'doctors', 'schedules'));
    }
}
