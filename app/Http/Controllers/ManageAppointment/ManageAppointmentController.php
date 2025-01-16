<?php

namespace App\Http\Controllers\ManageAppointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Timetable;
use App\Models\User;
use Carbon\Carbon;


class ManageAppointmentController extends Controller
{
    /**
     * List all appointments for the authenticated student.
     */
    // public function listAppointments(Request $request)
    // {
    //     $appointments = Appointment::where('student_id', Auth::id())->paginate(10);

    //     return view('ManageAppointment.ViewAppointmentStatus', compact('appointments'));
    // }
    public function listAppointments(Request $request)
    {
        $userId = Auth::id();
        $now = Carbon::now()->timezone('Asia/Kuala_Lumpur');
        
        // Get tomorrow's date without time
        $tomorrow = $now->copy()->addDay()->format('Y-m-d');
        
        // Fetch all appointments for pagination
        $appointments = Appointment::where('student_id', $userId)
            ->paginate(10);
        
        // Check for approved appointments scheduled for tomorrow
        $upcomingAppointments = Appointment::where('student_id', $userId)
            ->where('status', 'Approved')
            ->whereDate('appointment_date', $tomorrow)
            ->get();
        
        // Generate and flash reminder message if appointments are found
        if ($upcomingAppointments->isNotEmpty()) {
            // Build the reminder message
            $reminderMessage = '<div class="text-left">';
            $reminderMessage .= '<div class="font-bold mb-2">Appointment Reminder</div>';
            $reminderMessage .= '<div class="text-gray-700">You have the following appointment(s) scheduled for tomorrow:</div>';
            
            foreach ($upcomingAppointments as $appointment) {
                $appointmentTime = Carbon::parse($appointment->appointment_time)->format('g:i A');
                
                $reminderMessage .= sprintf(
                    '<div class="mt-3 p-3 bg-blue-50 rounded-lg">
                        <div class="font-semibold text-blue-600">%s</div>
                        <div class="mt-1">Time: %s</div>
                        <div class="mt-1">Location: %s</div>
                    </div>',
                    $appointment->lecturer->name,
                    $appointmentTime,
                    $appointment->lecturer->timetable->room_no ?? 'Room not specified'
                );
            }
            
            $reminderMessage .= '<div class="mt-3 text-gray-600">Please ensure you arrive on time.</div>';
            $reminderMessage .= '</div>';
            
            // Store the reminder in the session without the reminderShown check
            session()->flash('reminder', $reminderMessage);
        }
        
        return view('ManageAppointment.ViewAppointmentStatus', compact('appointments'));
    }

    
    /**
     * Show the request form to apply for an appointment with a specific lecturer.
     */
    public function showRequestForm($lecturerId)
    {
        $lecturer = User::where('id', $lecturerId)->where('role', 'lecturer')->firstOrFail();
        $timetable = Timetable::where('lecturer_id', $lecturer->id)->first();

        return view('ManageAppointment.ApplyAppointment', [
            'lecturer' => $lecturer,
            'timetable' => $timetable,
        ])->with('warning', $timetable ? null : 'No timetable found for this lecturer.');
    }

    /**
     * Show the create appointment form.
     */
    public function create($lecturerId)
    {
        $lecturer = User::findOrFail($lecturerId);
        return view('ManageAppointment.ApplyAppointment', compact('lecturer'));
    }

    /**
     * Store a new appointment in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'lecturer_id' => 'required|integer',
        ]);

        Appointment::create([
            'student_id' => Auth::id(),
            'lecturer_id' => $request->input('lecturer_id'),
            'appointment_date' => $request->input('appointment_date'),
            'appointment_time' => $request->input('appointment_time'),
            'reason' => $request->input('appointment_type'),
            'status' => 'Pending',
        ]);

        return redirect()->route('search')->with('success', 'Appointment request submitted successfully!');
    }

    /**
     * Cancel an existing appointment.
     */
    public function cancelAppointment($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('student_id', Auth::id())
            ->first();

        if (!$appointment) {
            return redirect()->back()->with('failure', 'Appointment not found or unauthorized.');
        }

        $appointment->delete();

        return redirect()->route('appointments.myAppointments')->with('success', 'Appointment canceled successfully.');
    }

    /**
     * Display details of a specific appointment.
     */
    public function show($id)
    {
        $appointment = Appointment::with('lecturer')->findOrFail($id);
        return view('ManageAppointment.ShowAppointment', compact('appointment'));
    }

    public function lecturerAppointments(Request $request)
{
    $lecturerId = Auth::id();
    
    // Get current time in Asia/Kuala_Lumpur timezone
    $now = Carbon::now()->timezone('Asia/Kuala_Lumpur');
    
    // Calculate tomorrow's date range
    $tomorrowStart = $now->copy()->addDay()->startOfDay();
    $tomorrowEnd = $now->copy()->addDay()->endOfDay();

    // Fetch pending appointments
    $pendingAppointments = Appointment::with('student')
        ->where('lecturer_id', $lecturerId)
        ->where('status', 'Pending')
        ->orderBy('appointment_date', 'asc')
        ->get();

    // Fetch approved appointments
    $approvedAppointments = Appointment::with('student')
        ->where('lecturer_id', $lecturerId)
        ->where('status', 'Approved')
        ->orderBy('appointment_date', 'asc')
        ->get();

    // Check for approved appointments scheduled for tomorrow
    $upcomingAppointments = Appointment::with(['student', 'lecturer.timetable'])
        ->where('lecturer_id', $lecturerId)
        ->where('status', 'Approved')
        ->whereDate('appointment_date', $tomorrowStart->toDateString())
        ->orderBy('appointment_time', 'asc')
        ->get();

    // Generate and flash reminder message if appointments are found
    if ($upcomingAppointments->isNotEmpty()) {
        // Build the reminder message
        $reminderMessage = '<div class="text-left">';
        $reminderMessage .= '<div class="font-bold mb-2 text-xl">Tomorrow\'s Appointment Schedule</div>';
        $reminderMessage .= '<div class="text-gray-700 mb-4">You have the following appointments scheduled for tomorrow:</div>';
        
        foreach ($upcomingAppointments as $appointment) {
            $appointmentTime = Carbon::parse($appointment->appointment_time)->format('g:i A');
            $appointmentDate = Carbon::parse($appointment->appointment_date)->format('l, F j, Y');
            
            $reminderMessage .= sprintf(
                '<div class="mt-3 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="font-semibold text-blue-600 text-lg">Student: %s</div>
                    <div class="mt-2 grid grid-cols-1 gap-2">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Time: %s
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Date: %s
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Purpose: %s
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Location: %s
                        </div>
                    </div>
                </div>',
                $appointment->student->name,
                $appointmentTime,
                $appointmentDate,
                $appointment->reason ?? 'Not specified',
                $appointment->lecturer->timetable->room_no ?? 'Room not specified'
            );
        }
        
        // Add a summary of total appointments
        $reminderMessage .= sprintf(
            '<div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="font-semibold text-lg mb-2">Daily Summary:</div>
                <div class="grid grid-cols-1 gap-2">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Total appointments tomorrow: %d
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        First appointment: %s
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Last appointment: %s
                    </div>
                </div>
            </div>',
            $upcomingAppointments->count(),
            Carbon::parse($upcomingAppointments->first()->appointment_time)->format('g:i A'),
            Carbon::parse($upcomingAppointments->last()->appointment_time)->format('g:i A')
        );
        
        $reminderMessage .= '</div>';
        
        // Store the reminder in the session using flash instead of now
        session()->flash('reminder', $reminderMessage);
    }

    // Return view with all appointments
    return view('ManageAppointment.ResponseAppointment', compact('pendingAppointments', 'approvedAppointments'));
}


    
    
    


    /**
     * Show pending and approved appointments for the lecturer.
     */
    public function responseAppointment()
    {
        $lecturerId = Auth::id();
        $pendingAppointments = Appointment::with('student')
            ->where('lecturer_id', $lecturerId)
            ->where('status', 'Pending')
            ->orderBy('appointment_date', 'asc')
            ->get();

        $approvedAppointments = Appointment::with('student')
            ->where('lecturer_id', $lecturerId)
            ->where('status', 'Approved')
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('ManageAppointment.ResponseAppointment', compact('pendingAppointments', 'approvedAppointments'));
    }

    /**
     * Approve a specific appointment.
     */
    public function approveAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->lecturer_id != Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $appointment->status = 'Approved';
        $appointment->save();
        session()->flash('success', 'You have approved the appointment from ' . $appointment->student->name);


        return redirect()->back();
    }

    /**
     * Reject a specific appointment.
     */
    public function rejectAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->lecturer_id != Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $appointment->status = 'Rejected';
        $appointment->save();
        session()->flash('error', 'You have rejected the appointment from ' . $appointment->student->name);

        return redirect()->back();
    }

    /**
     * Upload a timetable for the lecturer.
     */
    public function uploadSchedule(Request $request)
    {
        $request->validate([
            'schedule' => 'nullable|file|mimes:pdf,doc,png|max:2048', // Allow nullable schedule
            'room_no' => 'required|string|max:50', // Validate room_no
        ]);
    
        // Default the file path to null
        $filePath = null;
    
        // Check if a file is uploaded
        if ($request->hasFile('schedule')) {
            $file = $request->file('schedule');
            $originalName = $file->getClientOriginalName();
            $filePath = $file->storeAs('schedules', $file->getClientOriginalName(), 'public');
        }
    
        // Fetch the existing timetable or create a new one
        $timetable = Timetable::firstOrNew(['lecturer_id' => Auth::id()]);
    
        // Update fields
        if ($filePath) {
            $timetable->file_path = $filePath;
        }
        $timetable->room_no = $request->input('room_no');
    
        // Save the record
        $timetable->save();
    
        return redirect()->back()->with('success', 'Timetable uploaded successfully!');
    }
    


    /**
     * Show the upload timetable form for the lecturer.
     */
    public function showUploadForm()
    {
        $timetable = Timetable::where('lecturer_id', Auth::id())->first();
        return view('ManageAppointment.UploadTimetable', compact('timetable'));
    }

    /**
     * Search for lecturers.
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $lecturers = User::where('role', 'lecturer')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->paginate(10);

        return view('ManageAppointment.SearchLecturer', compact('lecturers', 'search'));
    }
}
