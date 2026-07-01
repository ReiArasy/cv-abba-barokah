<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 
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
                return redirect('/admin'); 
            } elseif ($user->role === 'customer') {
                return redirect()->intended(route('home')); 
            }

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
            'required' => 'semua field wajib diisi lengkap!',
            'unique'   => 'email sudah terdaftar, gunakan yang lain!',
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

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silahkan login!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // ============================================================
    // FITUR LUPA PASSWORD
    // ============================================================

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function updatePassword(Request $request)
    {
        $messages = [
            'email.required'    => 'Email wajib diisi lengkap.',
            'email.email'       => 'Format email tidak valid.',
            'email.exists'      => 'Email tidak terdaftar', 
            'password.required' => 'Password wajib diisi lengkap.',
            'password.min'      => 'Password minimal :min karakter.',
            'password.confirmed'=> 'Konfirmasi password baru tidak cocok', 
        ];

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], $messages);

        // Ambil data user berdasarkan email yang diinput
        $user = User::where('email', $request->email)->first();

        // REVISI LOGIKA: Cek apakah password baru sama dengan password yang saat ini aktif
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password baru tidak boleh sama dengan password lama Anda!'
            ])->withInput();
        }

        // Jika tidak sama, lakukan proses update seperti biasa
        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui! Silahkan gunakan password baru Anda!');
    }
}