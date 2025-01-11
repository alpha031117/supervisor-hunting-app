<?php

namespace App\Http\Controllers\ManageTimeframeAndQuota;

use App\Http\Controllers\Controller;
use App\Models\LecturerQuota;
use App\Models\SupervisorHuntingPeriod;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    /**
     * Manage the lecturer quota list.
     */
    public function manageLecturerQuota()
    {
        // Retrieve lecturer data with program, quotas, and semester
        $lecturers = LecturerQuota::with(['lecturer.program', 'supervisorHuntingPeriod'])->get();
        $semesters = SupervisorHuntingPeriod::select('semester')->distinct()->pluck('semester');

        return view('ManageTimeframeAndQuota.manage-lecturer-quota', compact('lecturers', 'semesters'));
    }

    /**
     * Display the lecturer quota list.
     */
    public function displayLecturerQuota()
    {
        // Retrieve lecturer data with program, quotas, and semester
        $lecturers = LecturerQuota::with(['lecturer.program', 'supervisorHuntingPeriod'])->get();
        $semesters = SupervisorHuntingPeriod::select('semester')->distinct()->pluck('semester');

        return view('ManageTimeframeAndQuota.lecturer-quota-list', compact('lecturers', 'semesters'));
    }

    /**
     * Combined function to filter lecturers by semester (for both quota pages).
     */
    public function filterBySemester(Request $request)
    {
        $request->validate([
            'semester' => 'nullable|string',
        ]);

        $semester = $request->input('semester');

        // Query the lecturers based on the semester
        if (empty($semester)) {
            // If 'All Semesters' is selected or no semester given, show all quotas
            $quotas = LecturerQuota::with(['lecturer.program', 'supervisorHuntingPeriod'])->get();
        } else {
            // Otherwise, find those whose supervisorHuntingPeriod has the given semester
            $quotas = LecturerQuota::with(['lecturer.program', 'supervisorHuntingPeriod'])
                ->whereHas('supervisorHuntingPeriod', function ($query) use ($semester) {
                    $query->where('semester', $semester);
                })
                ->get();
        }

        // Calculate stats (optional, based on requirements)
        $totalLecturers = $quotas->count();
        $totalQuota     = $quotas->sum('total_quota');
        $availableQuota = $quotas->sum('remaining_quota');

        return response()->json([
            'success' => true,
            'lecturers' => $quotas,
            'stats' => [
                'totalLecturers' => $totalLecturers,
                'totalQuota' => $totalQuota,
                'availableQuota' => $availableQuota,
            ],
        ]);
    }

    /**
     * Save or update the lecturer's quota.
     */
    public function saveQuota(Request $request, $lecturer_id)
    {
        // Validate input
        $request->validate([
            'total_quota' => 'required|integer|min:0',
            'semester'    => 'required|string',
        ]);

        // Find or create the row in supervisor_hunting_periods
        $supervisorHuntingPeriod = SupervisorHuntingPeriod::firstOrCreate(
            ['semester' => $request->semester],
            [
                'start_date' => now(),
                'end_date'   => now()->addWeek(),
                'is_set'     => false,
            ]
        );

        // Find or create a lecturer quota for that supervisor_hunting_period
        $lecturerQuota = LecturerQuota::firstOrNew([
            'lecturer_id' => $lecturer_id,
            'supervisor_hunting_period_id' => $supervisorHuntingPeriod->id,
        ]);

        $lecturerQuota->total_quota = $request->total_quota;
        $lecturerQuota->remaining_quota = $request->total_quota; // Reset or set as needed
        $lecturerQuota->save();

        return response()->json(['success' => true]);
    }

    /**
     * Display the quota report.
     */
    public function displayQuotaReport()
    {
        // Fetch all distinct semesters from supervisor_hunting_periods
        $semesters = SupervisorHuntingPeriod::select('semester')->distinct()->pluck('semester');

        return view('ManageTimeframeAndQuota.lecturer-quota-report', compact('semesters'));
    }
}
