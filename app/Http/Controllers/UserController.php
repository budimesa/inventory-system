<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Method untuk menampilkan modal ganti password
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // Method untuk mengubah password
    public function changePassword(Request $request)
    {
        // Validasi form
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|different:current_password',
            'new_password_confirmation' => 'required|string|min:8|same:new_password',
        ]);

        // Mengambil user saat ini
        $user = Auth::user();

        // Memeriksa apakah password saat ini cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Password saat ini salah.'], 422);
        }

        // Mengubah password user
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => 'Password berhasil diubah.']);
    }
}
