<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        try {
            $user = User::create([
                'name' => $attr['name'],
                'username' => $attr['username'],
                'password' => bcrypt($attr['password']),
                'email' => $attr['email']
            ]);

            return response()->json([
                'token' => $user->createToken('tokens')->plainTextToken,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
    //use this method to signin users
    public function login(Request $request)
    {
        $attr = $request->validate([
            'username' => 'required|string|exists:users,username',
            'password' => 'required|string|min:8'
        ]);

        try {
            if (!Auth::attempt($attr)) {
                return $this->error('Credentials not match', 401);
            }

            return response()->json([
                'token' => auth()->user()->createToken('API Token')->plainTextToken,
                'user' => auth()->user(),
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // this method signs out users by removing tokens
    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return response()->json([
                'message' => 'Tokens Revoked'
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
