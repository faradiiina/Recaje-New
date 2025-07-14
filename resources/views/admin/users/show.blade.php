@extends('layouts.admin.app')

@section('title', '| Detail Pengguna')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Pengguna</h1>
            <div>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Informasi Pribadi</h2>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">ID</p>
                    <p class="text-lg text-gray-800 dark:text-white">{{ $user->id }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama</p>
                    <p class="text-lg text-gray-800 dark:text-white">{{ $user->name }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                    <p class="text-lg text-gray-800 dark:text-white">{{ $user->email }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Role</p>
                    <p class="text-lg text-gray-800 dark:text-white">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Verifikasi Email</p>
                    <p class="text-lg text-gray-800 dark:text-white">
                        @if($user->email_verified_at)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Terverifikasi ({{ $user->email_verified_at->format('d M Y H:i') }})
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Belum Verifikasi
                            </span>
                        @endif
                    </p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Login Google</p>
                    <p class="text-lg text-gray-800 dark:text-white">
                        @if($user->google_id)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Ya
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Tidak
                            </span>
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Informasi Akun</h2>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Dibuat</p>
                    <p class="text-lg text-gray-800 dark:text-white">{{ $user->created_at->format('d M Y H:i') }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Terakhir Diupdate</p>
                    <p class="text-lg text-gray-800 dark:text-white">{{ $user->updated_at->format('d M Y H:i') }}</p>
                </div>
                
                @if(Auth::id() !== $user->id)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Tindakan</h3>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200">
                                Hapus Pengguna
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 