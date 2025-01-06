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
     * Display the edit timeframe page.
     */
    public function editTimeframe($id = null)
    {
        // Fetch all timeframes for the dropdown
        $timeframes = SupervisorHuntingPeriod::all();

        // Fetch the selected timeframe (if an ID is provided)
        $currentPeriod = $id ? SupervisorHuntingPeriod::find($id) : null;


        // Format the start and end dates for the Date Picker
        if ($currentPeriod) {
            $currentPeriod->formatted_start_date = \Carbon\Carbon::parse($currentPeriod->start_date)->format('m/d/Y');
            $currentPeriod->formatted_end_date = \Carbon\Carbon::parse($currentPeriod->end_date)->format('m/d/Y');
        }

        // dd($currentPeriod);
        return view('ManageTimeframeAndQuota.edit-timeframe', compact('timeframes', 'currentPeriod'));
    }

    /**
     * Update the timeframe.
     */
    public function updateTimeframe(Request $request)
    {
        // Validate input
        $request->validate([
            'timeframe_id' => 'required|exists:supervisor_hunting_periods,id',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Fetch the timeframe to update
        $timeframe = SupervisorHuntingPeriod::find($request->timeframe_id);

        // Update the timeframe
        $timeframe->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('edit-timeframe', $timeframe->id)->with('success', 'Timeframe has been updated successfully!');
    }
}
