<?php

namespace App\Http\Controllers\ManageTitle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\LecturerQuota;
use App\Models\StudentApplication;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ManageTitleController extends Controller
{
    public function DisplayProposalList()
    {
        // Retrieve proposals where the lecturer's remaining quota > 0 and the proposal status is "Available"
        $proposals = Proposal::whereHas('lecturer.lecturerQuotas', function ($query) {
            $query->where('remaining_quota', '>', 0);
        })->where('status', 'Available')->get();

        return view('ApplyTitle.ProposalList', compact('proposals'));
    }


    public function DisplayProposalDetail($id)
    {
        $proposal = Proposal::findOrFail($id); // This fetches a single proposal by its ID.

        return view('ApplyTitle.ViewProposalDetails', compact('proposal'));
    }


    public function Applysvandtitle($id)
    {


        // Find the proposal by ID
        $proposal = Proposal::findOrFail($id);

        // Find the related lecturer quota
        $lecturerQuota = LecturerQuota::where('lecturer_id', $proposal->lecturer_id)->first();

        // Check if lecturer has an assigned quota
        if ($lecturerQuota) {
            // Create a new student application
            $application = StudentApplication::create([
                'student_id' => Auth::id(),
                'student_title' => $proposal->proposal_title,
                'student_description' => $proposal->proposal_description,
                'lecturer_id' => $proposal->lecturer_id,
                'lecturer_quota_id' => $lecturerQuota->id,
                'proposal_id' => $proposal->id,
                'proposal_title' => $proposal->proposal_title,
                'remarks' => null,
                'status' => 'Pending', // Default status
                'decision_date' => null, // Decision date will be updated later
            ]);

            // Update the proposal status to 'Taken'
            $proposal->status = 'Taken';
            $proposal->save();

            return redirect('ProposalList')->with('success', 'Your application has been submitted successfully.');
        }

        return redirect('ProposalList')->with('error', 'The lecturer does not have a defined quota. Please choose another proposal.');
    }



    public function CreateApplication($lecturerId)
    {
        // Fetch the lecturer details
        $lecturer = User::findOrFail($lecturerId);

        // Return the view with lecturer details
        return view('ApplyTitle.StudentApplicationForm', compact('lecturer'));
    }

    public function SubmitApplication(Request $request)
    {


        // Validate form input
        $request->validate([
            'lecturer_id' => 'required|exists:users,id',
            'project_title' => 'required|string|max:255',
            'project_description' => 'required|string',
        ]);



        // Find the lecturer's quota
        $lecturerQuota = LecturerQuota::where('lecturer_id', $request->lecturer_id)->first();

        // Check if the lecturer has a quota
        if ($lecturerQuota) {
            // Create the student application
            StudentApplication::create([
                'student_id' => Auth::id(),
                'lecturer_id' => $request->lecturer_id,
                'student_title' => $request->project_title,
                'student_description' => $request->project_description,
                'lecturer_quota_id' => $lecturerQuota->id, // Assign lecturer quota ID
                'proposal_title' => null,
                'status' => 'Pending', // Default status
                'decision_date' => null,
            ]);

            return redirect('/ProposalList')->with('success', 'Your application has been submitted successfully.');
        }

        // If no quota is found for the lecturer
        return redirect('/ProposalList')->with('error', 'The lecturer does not have a quota assigned. Please choose another lecturer.');
    }


    public function DisplayApplicationList()
    {
        // Get the authenticated lecturer ID
        $lecturerId = Auth::id();

        // Retrieve applications grouped by status
        $pendingApplications = StudentApplication::where('lecturer_id', $lecturerId)
            ->where('status', 'Pending')
            ->with('student')
            ->get();

        $acceptedApplications = StudentApplication::where('lecturer_id', $lecturerId)
            ->where('status', 'Accepted')
            ->with('student')
            ->get();

        $rejectedApplications = StudentApplication::where('lecturer_id', $lecturerId)
            ->where('status', 'Rejected')
            ->with('student')
            ->get();

        // Filter applications that are pending for more than 7 days
        $pendingMoreThan7Days = $pendingApplications->filter(function ($application) {
            return $application->created_at->diffInDays(now()) > 7;
        });

        return view('ApplyTitle.StudentApplicationList', compact(
            'pendingApplications',
            'acceptedApplications',
            'rejectedApplications',
            'pendingMoreThan7Days'  // Pass filtered data to the view
        ));
    }



    public function DisplayApplicationDetail($id)
    {
        // Fetch application with related student details
        $application = StudentApplication::with('student')->findOrFail($id);

        return view('ApplyTitle.ResponseApplication', compact('application'));
    }

    public function handleResponse(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:accepted,rejected',
        ]);

        // Fetch application
        $application = StudentApplication::with('proposal', 'lecturerQuota')->findOrFail($id);

        if ($request->status === 'accepted') {
            return $this->AcceptApplication($application, $request->remarks);
        } elseif ($request->status === 'rejected') {
            return $this->RejectApplication($application, $request->remarks);
        }

        return back()->with('error', 'Invalid response status.');
    }

    private function AcceptApplication($application, $remarks)
    {

        // Update application status
        $application->status = 'Accepted';
        $application->remarks = $remarks;
        $application->decision_date = now();
        $application->save();

        // Update proposal status to "Taken"
        if ($application->proposal) {
            $application->proposal->status = 'Taken';
            $application->proposal->save();
        }

        // Decrease lecturer quota by 1 (if applicable)
        if ($application->lecturerQuota) {
            $application->lecturerQuota->remaining_quota -= 1;
            $application->lecturerQuota->save();
        }

        // Write to notification model
        Notification::create([
            'user_id' => $application->student_id,
            'title' => 'Application Accepted',
            'content' => 'Your application for the project "' . $application->proposal_title . '" has been accepted.',
            'status' => 'unread' // Assuming the status should be "unread"
        ]);

        return redirect('/ApplicationList')
            ->with('success', 'Application accepted successfully.');
    }


    private function RejectApplication($application, $remarks)
    {
        // Update application status
        $application->status = 'rejected';
        $application->remarks = $remarks;
        $application->decision_date = now();
        $application->save();

        // If the proposal_id is not null, update the proposal's status back to "Available"
        if ($application->proposal_id) {
            $proposal = Proposal::find($application->proposal_id);
            if ($proposal) {
                $proposal->status = 'Available';
                $proposal->save();
            }
        }

        // Write to the notification model
        Notification::create([
            'user_id' => $application->student_id,
            'title' => 'Your application has been rejected.',
            'content' => 'We regret to inform you that your application has been rejected.',
            'status' => 'unread' // Assuming the status should be "unread"
        ]);

        return redirect('/ApplicationList')
            ->with('success', 'Application rejected successfully.');
    }


    public function PostProposal()
    {
        // Get the current logged-in lecturer's ID
        $lecturerId = Auth::id();

        // Check the lecturer's remaining quota
        $lecturerQuota = LecturerQuota::where('lecturer_id', $lecturerId)->first();

        if ($lecturerQuota && $lecturerQuota->remaining_quota <= 0) {
            // Redirect to ProposalList with a failure message
            redirect('/ProposalList')->with('error', 'You have no quota left to post a proposal.');
        }

        // If quota is available, show the proposal posting page
        return view('ApplyTitle.PostProposal');
    }


    public function SubmitProposal(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'proposal_title' => 'required|string|max:255',
            'proposal_description' => 'required|string',
        ]);

        // Create a new proposal
        Proposal::create([
            'lecturer_id' => Auth::user()->id,  // Assuming the logged-in user is a lecturer
            'proposal_title' => $request->proposal_title,
            'proposal_description' => $request->proposal_description,
            // 'status' => 'pending',  // Set initial status to pending
        ]);

        // Redirect with a success message
        return redirect('/ApplicationList')->with('success', 'Proposal posted successfully!');
    }

    public function DisplayStudentApplications()
    {
        // Fetch the current student's applications, including lecturer details
        $studentApplications = StudentApplication::where('student_id', Auth::id())
            ->with('lecturer') // eager load the lecturer information
            ->get();

        // Return the applications view with the fetched data
        return view('studentDashboard', compact('studentApplications'));
    }

    public function DisplayLecturerProposals()
    {
        // Fetch the current logged-in lecturer's proposals
        $lecturerProposals = Proposal::where('lecturer_id', Auth::id())->get();

        // Return the view with the fetched proposals
        return view('lecturerDashboard', compact('lecturerProposals'));
    }
}
