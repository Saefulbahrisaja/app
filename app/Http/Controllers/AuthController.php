<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required', 'string', 'min:6']
        ], [
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 6 karakter',
        ]);

        // Ambil data kredensial
        $credentials = $request->only('email', 'password');

        // Cek login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect sesuai role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Login berhasil sebagai Admin');
                case 'kasir':
                    return redirect()->route('kasir.dashboard')->with('success', 'Login berhasil sebagai Kasir');
                default:
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Role tidak dikenali. Hubungi administrator.'
                    ]);
            }
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->withInput($request->only('email'));
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus session & regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout');
    }
}
