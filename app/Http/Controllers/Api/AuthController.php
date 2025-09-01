<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'nip' => 'required',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Coba Otentikasi
        // 'attempt' akan mencari user berdasarkan credentials dan membandingkan password hash secara otomatis
        $credentials = $request->only('nip', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            // Jika kredensial salah (NIP tidak ditemukan atau password tidak cocok)
            return response()->json(['error' => 'NIP atau password salah'], 401);
        }

        // 3. Jika berhasil, kirim response dengan token
        return $this->respondWithToken($token);
    }

    /**
     * Helper function untuk format response JWT.
     */

    protected function respondWithToken1($token)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil! (Mode Debug)', // Tambahkan pesan agar ingat
            'data' => [
                'nip' => auth('api')->user()->nip,
                'token' => $token,
            ]
        ]);
    }

    protected function respondWithToken($token)
    {
        // Ambil NIP dari user yang sedang terotentikasi
//        $nip = auth('api')->user()->nip;


        // 1. Siapkan data yang akan dikirim
        $dataToEncrypt = [
            'nip' => auth('api')->user()->nip,
            'token' => $token,
        ];

        // 2. Ubah data menjadi format JSON String
        $jsonString = json_encode($dataToEncrypt);

        // 3. Enkripsi JSON String tersebut
        $encryptedData = Crypt::encryptString($jsonString);
        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'data' => $encryptedData
        ]);
    }

    // app/Http/Controllers/API/AuthController.php

    /**
     * Mendapatkan data user yang terotentikasi.
     */
    public function me()
    {
        // Ambil objek user yang sedang terotentikasi
        $user = auth('api')->user();

        // Kembalikan response JSON dengan format baru
        return response()->json([
            'message' => 'Data pengguna berhasil diambil',
            'data' => [
                'nip' => $user->nip
            ]
        ]);
    }

    public function changePassword(Request $request)
    {
        // 1. Validasi input dari pengguna
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Ambil user yang sedang login (sudah diidentifikasi oleh middleware)
        $user = auth('api')->user();
        // 3. Verifikasi apakah password saat ini yang dimasukkan benar
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password saat ini tidak cocok'], 422);
        }

        // 4. Jika benar, update password dengan yang baru
        // Model User Anda akan otomatis melakukan hashing karena ada di 'casts'
        $user->password = $request->new_password;
        $user->save();

        // 5. Berikan respons sukses
        return response()->json([
            'message' => 'Password berhasil diubah',
        ]);
    }

    public function logout()
    {
        // Panggil method logout dari guard 'api'.
        // Ini akan otomatis memasukkan token yang sedang digunakan ke dalam blocklist.
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
