<?php
// app/Http/Controllers/ManageTimeframeAndQuota/QuotaController.php

namespace App\Http\Controllers\ManageTimeframeAndQuota;

use App\Http\Controllers\Controller;
use App\Models\LecturerQuota;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    /**
     * Display the lecturer quota list.
     */
    public function displayLecturerQuota()
    {
        // Retrieve lecturer data with program and quotas
        $lecturers = LecturerQuota::with(['lecturer.program'])
            ->get();

        return view('ManageTimeframeAndQuota.lecturer-quota-list', compact('lecturers'));
    }

    /**
     * Save or update the lecturer's quota.
     */
    public function saveQuota(Request $request, $lecturer_id)
    {
        // Validate the input
        $request->validate([
            'total_quota' => 'required|integer|min:0',
        ]);

        // Find or create the lecturer's quota record
        $lecturerQuota = LecturerQuota::firstOrNew(['lecturer_id' => $lecturer_id]);
        $lecturerQuota->total_quota = $request->total_quota;
        $lecturerQuota->save();

        // Return a JSON response
        return response()->json(['success' => true]);
    }
}
