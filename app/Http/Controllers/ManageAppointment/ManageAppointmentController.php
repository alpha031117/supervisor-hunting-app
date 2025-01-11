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
        $today = \Carbon\Carbon::now();
        $tomorrow = $today->copy()->addDay();
    
        // Fetch all appointments for the authenticated student
        $appointments = Appointment::where('student_id', $userId)
            ->paginate(10);
    
        // Check for approved appointments scheduled for tomorrow
        $upcomingAppointments = Appointment::where('student_id', $userId)
            ->where('status', 'Approved') // Only approved appointments
            ->whereDate('appointment_date', $tomorrow->toDateString())
            ->get();
    
        // Flash reminder message for approved appointments if not already shown
        if ($upcomingAppointments->isNotEmpty() && !session()->has('reminderShown')) {
            $reminderMessage = '<div style="text-align: left;"><strong>Appointment Notification:</strong> You have an upcoming scheduled appointment tomorrow.';
            foreach ($upcomingAppointments as $appointment) {
                $reminderMessage .= '<br><br><strong>Details:</strong><br> Appointment with <span style="color: #1814F3; font-weight: bold;">' 
                    . $appointment->lecturer->name 
                    . '</span> at <strong>' 
                    . \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') 
                    . '</strong>.<br> Please ensure that you are prepared and arrive on time.';
            }
            $reminderMessage .= '</div>';
    
            session()->flash('reminder', $reminderMessage);
            session()->put('reminderShown', true); // Set the session variable
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

    public function lecturerAppointments()
{
    $lecturerId = Auth::id();

    // Fetch pending and approved appointments for the lecturer
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

    // Identify appointments scheduled for tomorrow
    $tomorrow = Carbon::now()->timezone('Asia/Kuala_Lumpur')->addDay()->startOfDay()->toDateString();
    $upcomingAppointments = $approvedAppointments->filter(function ($appointment) use ($tomorrow) {
        return Carbon::parse($appointment->appointment_date)->toDateString() === $tomorrow;
    });

    // Generate reminder message if there are appointments tomorrow
    if ($upcomingAppointments->isNotEmpty()) {
        $reminderMessage = '<strong>Appointment Notification:</strong> You have approved appointments scheduled for tomorrow:<br>';
        foreach ($upcomingAppointments as $appointment) {
            $reminderMessage .= '<br><strong>Appointment with:</strong> <span style="color: #1814F3; font-weight: bold;">' 
                . $appointment->student->name 
                . '</span><br><strong>Time:</strong> ' 
                . Carbon::parse($appointment->appointment_time)->format('g:i A') 
                . '<br>';
        }

        // Flash the reminder message to the session
        session()->flash('reminder', $reminderMessage);
    }

    // Return the view with both pending and approved appointments
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
