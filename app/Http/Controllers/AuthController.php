<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // AMBIL DATA USER YANG LOGIN
            $user = Auth::user();

            // LOGIKA PENGARAHAN BERDASARKAN ROLE
            if ($user->role === 'admin') {
                return redirect('/admin'); // Dashboard Filament untuk Admin
            } elseif ($user->role === 'customer') {
                return redirect()->route('customer.dashboard'); // Halaman Baru untuk Customer
            }

            // Default jika role tidak dikenal
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib diisi lengkap.',
            'unique'   => ':attribute sudah terdaftar, gunakan yang lain.',
            'email'    => 'Format email tidak valid.',
            'min'      => ':attribute minimal :min karakter.',
        ];

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8',
            'alamat_lengkap' => 'required|string',
            'provinsi' => 'required|string',
            'kota' => 'required|string',
        ], $messages);

        User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'alamat_lengkap' => $validatedData['alamat_lengkap'],
            'provinsi' => $validatedData['provinsi'],
            'kota' => $validatedData['kota'],
            'password' => bcrypt($validatedData['password']),
            'role' => 'customer', 
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // TAMBAHKAN FUNGSI LOGOUT AGAR BISA GANTI AKUN SAAT TESTING
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}