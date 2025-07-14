<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Menampilkan form login
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return route('admin.dashboard');
        }
        
        return '/';
    }

    /**
     * Memproses request login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ], [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Password harus diisi',
            ]);

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                if (Auth::user()->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                
                return redirect()->intended('/');
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email atau password yang diberikan tidak cocok dengan data kami.',
                ]);

        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'error' => 'Terjadi kesalahan saat mencoba login. Silakan coba lagi.',
                ]);
        }
    }

    /**
     * Logout pengguna
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect ke halaman login Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user berdasarkan google id, jika tidak ada cari berdasarkan email
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                // Cek apakah email sudah terdaftar
                $user = User::where('email', $googleUser->email)->first();
                
                if (!$user) {
                    // Jika user belum terdaftar, buat user baru
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => Hash::make(rand(1,10000)),
                        'email_verified_at' => now(), // Email otomatis terverifikasi
                    ]);
                } else {
                    // Update google_id untuk user yang sudah ada
                    $user->update([
                        'google_id' => $googleUser->id,
                        'email_verified_at' => now(), // Email otomatis terverifikasi
                    ]);
                }
            }
            
            // Login user
            Auth::login($user);
            
            return redirect('/');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage());
        }
    }
} 