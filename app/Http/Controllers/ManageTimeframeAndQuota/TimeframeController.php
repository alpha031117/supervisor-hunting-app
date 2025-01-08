<?php

namespace App\Http\Controllers\ManageTimeframeAndQuota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupervisorHuntingPeriod;
use App\Models\LecturerQuota;
use App\Models\User;

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
            'semester'    => 'required|string|max:255',
            'start_date'  => 'required|date|before_or_equal:end_date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.before_or_equal' => 'Start date must be before or the same as the end date.',
            'end_date.after_or_equal'    => 'End date must be after or the same as the start date.',
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

        // Create the timeframe
        $timeframe = SupervisorHuntingPeriod::create([
            'semester' => $request->semester,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_set' => true,
        ]);

        // Create LecturerQuota entries for all lecturers
        $lecturers = User::where('role', 'lecturer')->get();
        foreach ($lecturers as $lecturer) {
            LecturerQuota::insert([
                'supervisor_hunting_period_id' => $timeframe->id,
                'lecturer_id' => $lecturer->id,
                'total_quota' => 0, // Default quota
                'remaining_quota' => 0, // Default remaining quota
            ]);
        }

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

    /**
     * Delete a timeframe and its associated lecturer quotas.
     */
    public function deleteTimeframe($id)
    {
        // Find the timeframe
        $timeframe = SupervisorHuntingPeriod::find($id);

        if (!$timeframe) {
            return response()->json(['success' => false, 'message' => 'Timeframe not found.'], 404);
        }

        // Delete associated lecturer quotas
        LecturerQuota::where('supervisor_hunting_period_id', $timeframe->id)->delete();

        // Delete the timeframe
        $timeframe->delete();

        return response()->json(['success' => true, 'message' => 'Timeframe deleted successfully.']);
    }
}
