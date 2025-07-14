@extends('layouts.admin.app')

@section('title', ' - Dashboard')

@section('content')
<div>
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-800 mb-4">Dashboard Admin</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('admin.users.index') }}" class="block">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-500 rounded-md p-3 flex-shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Pengguna</p>
                        <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.cafes.index') }}" class="block">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-500 rounded-md p-3 flex-shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Café</p>
                        <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ \App\Models\Cafe::count() }}</p>
                    </div>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.contact-messages.index') }}" class="block">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="bg-purple-500 rounded-md p-3 flex-shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pesan Belum Dibaca</p>
                        <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ \App\Models\ContactMessage::where('is_read', false)->count() }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Users -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Pengguna Terbaru</h2>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-hidden rounded-lg">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="h-12">
                        <th scope="col" class="px-6 py-3 rounded-tl-lg">
                            NAMA
                        </th>
                        <th scope="col" class="px-6 py-3">
                            EMAIL
                        </th>
                        <th scope="col" class="px-6 py-3">
                            ROLE
                        </th>
                        <th scope="col" class="px-6 py-3 rounded-tr-lg">
                            TANGGAL DAFTAR
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $recentUsers = \App\Models\User::where('role', 'user')->latest()->take(5)->get();
                    @endphp
                    @forelse($recentUsers as $user)
                        <tr class="h-16 bg-gray-700 text-white border-b border-gray-600 transition-colors" style="cursor: pointer;" onmouseover="this.style.backgroundColor='#2B3544'" onmouseout="this.style.backgroundColor='#1F2937'">
                            <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                {{ $user->name }}
                            </th>
                            <td class="px-6 py-4" style="color: rgba(255, 255, 255, 0.5);">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4" style="color: rgba(255, 255, 255, 0.5);">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr class="h-16 bg-gray-700 text-white" style="cursor: pointer;" onmouseover="this.style.backgroundColor='#2B3544'" onmouseout="this.style.backgroundColor='#1F2937'">
                            <td colspan="4" class="px-6 py-4 text-center rounded-b-lg">
                                Tidak ada pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 