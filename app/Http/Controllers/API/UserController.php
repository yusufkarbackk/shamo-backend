<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Fortify\Rules\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function register(Request $request){
        try {
            $request->validate([
                'name'     => ['required', 'string', 'max:255'],
                'userName' => ['required', 'string', 'max:255', 'unique:users'],
                'email'    => ['required', 'string', 'max:255', 'unique:users'],
                'phone'    => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', new Password]
        ]);

        User::create([
            'name'     => $request->name,
            'userName' => $request->userName,
            'email'    => $request->email,
            'name'     => $request->name,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password)
        ]);

        $user = User::where('email', $request->email)->first();

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return ResponseFormatter::success([
            'access_token' => $tokenResult,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 'User registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
            'message' => 'Something went wrong'
        ], 'Authentication failed', 500);
        }
    }

    public function login(Request $request){
        try {
            $request->validate([
                'email'    => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication failed', 500);
            }

            $user = User::where('email', $request->email)->first();

            if (! Hash::check($request->password, $user->password)) {
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type'   => 'Bearer',
                'user'         => $user
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                    'message' => 'Something went wrong',
                    'error'   => $error
                ], 'Authentication failed', 500);
        }
    }

    public function fetch(Request $request){
        return ResponseFormatter::success(
            $request->user(), 'Data profile user berhasil diambil'
        );
    }

    public function updateProfile(Request $request){
        $data $request->all();

        $user = Auth::user();
        $user->update($data);

        return ResponseFormatter:: succes($user, 'Profile updated');
    }

    public function logout(Request $request){
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter:: success($token, 'Token revoked');
    }

    
}