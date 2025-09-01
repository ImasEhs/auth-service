<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Mereset password pengguna menjadi default (8 digit pertama NIP).
     */
    public function resetPassword(Request $request)
    {
        // 1. Validasi input: pastikan NIP dikirim dan ada.
        $request->validate([
            'nip' => 'required|string|exists:users,nip',
        ]);

        // 2. Cari pengguna target berdasarkan NIP.
        $user = User::where('nip', $request->nip)->first();

        // 3. Tentukan password default.
        $defaultPassword = substr($request->nip, 0, 8);

        // 4. Update password pengguna.
        // Model User akan otomatis melakukan hashing karena ada di properti 'casts'.
        $user->password = $defaultPassword;
        $user->save();

        // 5. Kirim respons sukses.
        return response()->json([
            'message' => 'Password for user ' . $user->name . ' has been reset successfully.',
        ]);
    }
}
