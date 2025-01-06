<?php

namespace App\Http\Controllers\ManageTimeframeAndQuota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupervisorHuntingPeriod;

class TimeframeController extends Controller
{
    /**
     * Display the timeframe creation form.
     */
    public function setTimeframe()
    {
        return view('ManageTimeframeAndQuota.set-timeframe');
    }

    /**
     * Save a new timeframe.
     */
    public function storeTimeframe(Request $request)
    {

        // Validate input
        $request->validate([
            'semester' => 'required|string|max:50',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Check if the semester already exists in the database
        $semesterExists = SupervisorHuntingPeriod::where('semester', $request->semester)->exists();

        if ($semesterExists) {
            // Return validation error if the semester already exists
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['semester' => 'Timeframe for this semester already exists.']);
        }

        // Create or update the timeframe
        SupervisorHuntingPeriod::create([
            'semester' => $request->semester,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_set' => true,
        ]);

        return redirect()->route('set-timeframe')->with('success', 'Timeframe has been set successfully!');
    }

    /**
     * Display the timeframe editing form.
     */
    public function editTimeframe()
    {
        // Retrieve the latest timeframe for editing
        $currentPeriod = SupervisorHuntingPeriod::latest()->first();

        return view('ManageTimeframeAndQuota.edit-timeframe', compact('currentPeriod'));
    }

    /**
     * Update the timeframe.
     */
    public function updateTimeframe(Request $request)
    {
        // Validate input
        $request->validate([
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Find the current timeframe or fail
        $currentPeriod = SupervisorHuntingPeriod::latest()->first();

        if ($currentPeriod) {
            $currentPeriod->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            return redirect()->route('edit-timeframe')->with('success', 'Timeframe has been updated successfully!');
        }

        return redirect()->route('edit-timeframe')->with('error', 'No existing timeframe found!');
    }
}
