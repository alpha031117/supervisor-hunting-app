<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    // Display the login view.
    public function create()
    {
        return view('ManageUser.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        Log::info("Login Request: {$request}");

        $request->authenticate();

        $request->session()->regenerate();

        // Retrieve the authenticated user
        $user = Auth::user();

        if ($user === null) {
            Log::info("User is null");
            return redirect()->route('login')->with('error', 'Invalid credentials. Please try again.');
        }
        else{
            Log::info("User is validated.");
            Log::info("login User Role: {$user->role}"); 

            // Check the user's role and redirect accordingly
            if ($user->role === 'coordinator') {
                // Redirect to the coordinator dashboard
                return redirect()->route('coordinator.dashboard');
            } elseif ($user->role === 'lecturer') {
                // Redirect to the lecturer dashboard or page
                return redirect()->route('lecturer.dashboard');
            } elseif ($user->role === 'student') {
                // Redirect to the student dashboard
                return redirect()->route('student.dashboard');
            }
        }

        // Default redirect in case no specific role is found
        return redirect()->route('login')->with('error', 'Unauthorized');
    }

    // Logout the user
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


    // Display the reset password view.
    public function resetPassword()
    {
        return view('ManageUser.reset-password');
    }

    // Store the user's new password in session
    public function store_session(Request $request)
    {
        // Validate the password
        $request->validate([
            'new_password' => 'required|min:8',
        ]);
        
        // Store the password in session
        $request->session()->put('new_password', $request->new_password);
        
        // Redirect to the retype password view
        return redirect()->route('confirm-password');
    }

    // Display retype password view.
    public function retypePassword(Request $request)
    {
        $password = session('new_password');
        
        // Pass the password to the next view (session or directly)
        return view('ManageUser.confirm-password', compact('password'));
    }

}
