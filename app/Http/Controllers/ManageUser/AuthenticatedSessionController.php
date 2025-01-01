<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

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

            // Check if user is first time login
            // 1 = first time login
            // 0 = not first time login
            if ($user->first_login === 1) {
                // Redirect to the reset password view
                return redirect()->route('auth.reset-password');
            }
            elseif ($user->first_login === 0) {
                // Redirect to the dashboard
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
            return redirect()->route('login')->with('error', 'Invalid login status. Please contact IT administrator for further assistance.');
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
        if(strlen($request->new_password) < 8){
            return redirect()->back()->with('error', 'Password must be at least 8 characters long.');
        }
                
        // Validate the password
        $request->validate([
            'new_password' => 'required|min:8',
        ]);
        
        // Store the password in session
        $request->session()->put('new_password', $request->new_password);
        
        // Redirect to the retype password view
        return redirect()->route('auth.confirm-password');
    }

    // Display retype password view.
    public function retypePassword(Request $request)
    {
        $password = session('new_password');
        
        // Pass the password to the next view (session or directly)
        return view('ManageUser.confirm-password', compact('password'));
    }

    // Update the user's password
    public function updatePassword(Request $request)
    {
        // Retrieve the user
        $user = Auth::user();

        Log::info("Default Password: {$user->password}");
        Log::info("New Password: {$request->new_password}");
        Log::info("Confirm Password: {$request->confirm_password}");

        // Validate the password
        $request->validate([
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|min:8',
        ]);

        if ($request->new_password !== $request->confirm_password) {
            Log::info('Error: Passwords do not match.');
            return redirect()->back()->with('error', 'Passwords do not match. Please try again.');
        }        

        if (Hash::check($request->new_password, $user->password)) {
            Log::info('New password is the same as the default password.');
            return redirect()->back()->with('error', 'New password cannot be the same as the default password. Please try again.');
        }

        // Update the user's password
        $userModel = \App\Models\User::find($user->id);
        $userModel->password = bcrypt($request->new_password);
        $userModel->first_login = 0;
        $userModel->save();

        // Redirect to the success reset password view
        return redirect()->route('auth.success-reset-password');
    }

}
