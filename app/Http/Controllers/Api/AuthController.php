<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cari pengguna di database
        $user = User::where('nip', $request->nip)->first();

        // 3. Verifikasi pengguna dan password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'NIP atau password salah.',
            ], 401);
        }

        // 4. Buat token JWT
        $token = JWTAuth::fromUser($user);

        // 5. Kirim respons sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'user' => [
                'nip' => $user->nip,
            ],
            'token' => $token,
        ]);
    }
}