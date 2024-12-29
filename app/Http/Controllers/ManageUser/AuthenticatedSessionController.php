<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    // Display the login view.
    public function create()
    {
        return view('ManageUser.login');
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
