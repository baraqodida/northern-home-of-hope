<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Render the secure login gateway page view.
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Authenticate administrative input credentials.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to pass through the authentication checkpoint
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Protects against session fixation exploits

            return redirect()->intended('dashboard')
                ->with('success', 'Authentication verified successfully. Welcome back to the portal session panel!');
        }

        // Drop backward if credentials break authentication rules
        return back()->withErrors([
            'email' => 'The provided credentials do not match our secure administration records.',
        ])->onlyInput('email');
    }

    /**
     * Safely terminate the administrative session.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Refreshes hidden CSRF tokens completely

        return redirect()->route('login');
    }
}