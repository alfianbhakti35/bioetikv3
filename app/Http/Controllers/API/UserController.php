<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Function for user login
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => ['required'],
                'password' => ['required']
            ]);

            // Cek username dan password
            $credentials = request(['username', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            $user = User::where('username', $request->username)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credincials');
            }

            // Buat token untuk user
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // Resposer user berhasil login
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }
    }

    // Function for user registration
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required'],
                'email' => ['required', 'unique:users,email', 'email'],
                'password' => ['required'],
                'username' => ['required', 'unique:users,username']
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'username' => $request->username,
            ]);

            // Resposne Register Success
            return ResponseFormatter::success([
                'message' => 'Pembuatan akun berhasil',
            ], 'Aku berhasil dibuat');
        } catch (Exception $errors) {
            // Response Register Gagal
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $errors,
            ], 'Gagal registert akun', 500);
        }
    }

    // Function for user logout
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }

    // Function for get data user
    public function profile(Request $request)
    {
        return ResponseFormatter::success([
            'user' => $request->user(),
        ], 'User berhasil didapatkan');
    }

    // Function for update data user
    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required'],
                'jenis_kelamin' => ['required'],
                'tanggal_lahir' => ['required'],
                'phone' => ['required'],
                'email' => ['required'],
            ]);

            // set update
            $mhsID = Auth::user()->id;

            // return $mhsID;

            User::where('id', $mhsID)->update([
                'name' => $request->name,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            $user = User::where('id', $mhsID)->first();

            // Response Berhasil
            return ResponseFormatter::success([
                'user' => $user,
            ], 'Profile berhasil diupdate');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Gagal update profile', 500);
        }
    }
}
