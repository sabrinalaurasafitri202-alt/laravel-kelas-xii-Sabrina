<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display login page / modal trigger or redirect if authenticated.
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        if ($request->has('redirect')) {
            session()->put('url.intended', $request->query('redirect'));
        }

        return redirect()->route('home')->with('open_login_modal', true);
    }

    /**
     * Display registration page / modal trigger or redirect if authenticated.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return redirect()->route('home')->with('open_register_modal', true);
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required'    => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $credentials = [
            $field     => $login,
            'password' => $password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Login berhasil! Selamat datang di Admin Dashboard.');
            }

            $redirectTo = $request->filled('redirect_to') && !str_contains($request->input('redirect_to'), '/login') && !str_contains($request->input('redirect_to'), '/register')
                ? $request->input('redirect_to')
                : route('home');

            return redirect()->intended($redirectTo)
                ->with('success', 'Login berhasil! Selamat datang, ' . $user->name . '.');
        }

        return back()
            ->withInput($request->only('login', 'redirect_to'))
            ->with('login_error', true)
            ->withErrors([
                'login' => 'Email/Username atau password yang Anda masukkan salah.',
            ]);
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'min:3', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ], [
            'name.required'                  => 'Nama / Username wajib diisi.',
            'name.min'                       => 'Nama / Username minimal 3 karakter.',
            'email.required'                 => 'Email wajib diisi.',
            'email.email'                    => 'Format email tidak valid.',
            'email.unique'                   => 'Email ini sudah terdaftar.',
            'password.required'              => 'Password wajib diisi.',
            'password.min'                   => 'Password minimal harus 8 karakter.',
            'password.confirmed'             => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'user',
            'role_id'  => 1,
        ]);

        return redirect()->route('home')->with([
            'success'          => 'Registrasi berhasil! Silakan login dengan akun baru Anda.',
            'open_login_modal' => true,
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }
}
