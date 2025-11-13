<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Blade with Vue mount
    }

    public function login(Request $request)
    {
        $request->validate([
            'data.attributes.email' => 'required|email',
            'data.attributes.password' => 'required',
        ]);

        $email = $request->input('data.attributes.email');
        $password = $request->input('data.attributes.password');

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create token for API usage
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
            'redirect' => route('dashboard')
        ]);
    }

    public function logout(Request $request)
    {
        // Delete the token used by the user
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
            'redirect' => route('login'),
        ]);
    }
}
