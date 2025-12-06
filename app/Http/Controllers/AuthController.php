<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $login_request)
    {
        $credentials = $login_request->validated();

        if (Auth::attempt($credentials)) {
            $login_request->session()->regenerate();

            // Check the user's role
            $user = Auth::user();

            if ($user->role === 'admin' || $user->role === 'cashier' || $user->role === 'registrar') {
                return redirect()->route('dashboard')->with('login_success', true);
            }

            if ($user->role === 'student') {
                return redirect()->route('students.dashboard')->with('login_success', true);
            }

            // Default redirect for other roles or no specific role
            // return redirect()->route('dashboard')->with('login_success', true);
        }

        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('showLogin');
    }
}
