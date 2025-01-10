<?php

namespace App\Http\Controllers\ManageAppointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Timetable;
use App\Models\User;

class ManageAppointmentController extends Controller
{
    /**
     * List all appointments for the authenticated student.
     */
    public function listAppointments(Request $request)
    {
        $appointments = Appointment::where('student_id', Auth::id())->get();
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

    /**
     * List all appointments for the authenticated lecturer.
     */
    public function lecturerAppointments(Request $request)
    {
        $appointments = Appointment::where('lecturer_id', Auth::id())
            ->orderBy('appointment_date', 'asc')
            ->paginate(10);

        return view('ManageAppointment.ResponseAppointment', compact('appointments'));
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
            'schedule' => 'required|file|mimes:pdf,doc,png|max:2048',
        ]);

        if ($request->hasFile('schedule')) {
            $file = $request->file('schedule');
            $filePath = $file->storeAs('schedules', $file->getClientOriginalName(), 'public');

            Timetable::updateOrCreate(
                ['lecturer_id' => Auth::id()],
                ['file_path' => $filePath]
            );
        }

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
