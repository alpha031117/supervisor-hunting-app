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
        // Retrieve the latest timeframe or create a blank one for the view
        $currentPeriod = SupervisorHuntingPeriod::latest()->first();

        return view('ManageTimeframeAndQuota.set-timeframe', compact('currentPeriod'));
    }

    /**
     * Save a new timeframe.
     */
    public function storeTimeframe(Request $request)
    {
        // Validate input
        $request->validate([
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Create or update the timeframe
        SupervisorHuntingPeriod::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
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
