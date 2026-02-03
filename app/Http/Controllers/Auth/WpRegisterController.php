<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class WpRegisterController extends Controller
{
    public function store(Request $request)
    {
        if ($request->header('X-APP-KEY') !== config('services.wp.key')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8',
            'name'     => 'required|string|max:255', // Add this
        ]);

        $user = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name'     => $data['name'], // Save the name here
                'password' => Hash::make($data['password']),
            ]
        );

        return response()->json([
            'success'  => true,
            'message'  => 'User created or found',
            'redirect' => url('/login'),
        ]);
    }
}

