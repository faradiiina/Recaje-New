<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware auth diterapkan di routes/web.php
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Cek apakah pengguna adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }
        
        // Di sini Anda bisa mengambil data yang dibutuhkan untuk dashboard admin
        $totalUsers = \App\Models\User::count();
        
        return view('admin.dashboard', [
            'totalUsers' => $totalUsers
        ]);
    }
}
