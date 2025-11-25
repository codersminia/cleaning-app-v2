<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Blade with Vue mount
    }

public function login(Request $request)
    {
        // 1. Validate
        $request->validate([
            'data.attributes.email' => 'required|email',
            'data.attributes.password' => 'required',
        ]);

        $credentials = [
            'email' => $request->input('data.attributes.email'),
            'password' => $request->input('data.attributes.password')
        ];

        // 2. Attempt Login (Creates Session)
        if (Auth::attempt($credentials)) {
            // Security: Regenerate session ID to prevent fixation attacks
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'user' => Auth::user(),
                'redirect' => route('dashboard')
            ]);
        }

        // 3. Fail
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
{
    // 1. Logout the user
    Auth::guard('web')->logout();

    // 2. Invalidate the session (Kill it in the DB)
    $request->session()->invalidate();

    // 3. Regenerate the token to prevent reuse
    $request->session()->regenerateToken();

    return response()->json([
        'success' => true,
        'redirect' => '/'
    ]);
}
}
