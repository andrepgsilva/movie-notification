<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validatedFields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $email = strtolower($request->email);

        $user = User::where('email', $email)->first();
     
        if (! is_null($user)) {
            return response()->json(
                'The user already exists.',
                '400'
            );
        }

        $password = Hash::make($validatedFields['password']);
        $validatedFields['password'] = $password;

        $user = User::create($validatedFields);

        $secret = env('APP_TOKEN_SECRET');

        $token = $user->createToken($secret)->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json($data);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
     
        $email = strtolower($request->email);

        $user = User::where('email', $email)->first();
     
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        $token = $user->createToken(
            env('APP_TOKEN_SECRET')
        )->plainTextToken;

        return response()->json(compact('token'));
    }
}
