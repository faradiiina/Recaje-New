@extends('layouts.admin.app')

@section('title', '| Detail Pesan Kontak')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Pesan Kontak</h1>
            <div>
                <a href="{{ route('admin.contact-messages.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Informasi Pengirim</h2>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">ID</p>
                    <p class="text-lg text-gray-800 dark:text-white">{{ $contactMessage->id }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama</p>
                    <p class="text-lg text-gray-800 dark:text-white">{{ $contactMessage->name }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                    <p class="text-lg text-gray-800 dark:text-white">{{ $contactMessage->email }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <p class="text-lg text-gray-800 dark:text-white">
                        @if($contactMessage->is_read)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Sudah Dibaca
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Belum Dibaca
                            </span>
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Detail Pesan</h2>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Dikirim</p>
                    <p class="text-lg text-gray-800 dark:text-white">{{ $contactMessage->created_at->format('d M Y H:i') }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Terakhir Diupdate</p>
                    <p class="text-lg text-gray-800 dark:text-white">{{ $contactMessage->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-200 dark:border-gray-600">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Isi Pesan</h2>
            <div class="p-4 bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-600">
                <p class="text-gray-800 dark:text-white whitespace-pre-wrap">{{ $contactMessage->message }}</p>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 dark:border-gray-600 pt-6">
            <div class="flex space-x-4">
                <a href="mailto:{{ $contactMessage->email }}?subject=Re: Pesan dari Website RECAJE&body=Halo {{ $contactMessage->name }}, %0D%0A%0D%0ATerima kasih telah menghubungi kami. Berikut adalah balasan atas pesan Anda:%0D%0A%0D%0A%0D%0A%0D%0A--------------------%0D%0APesan asli dari {{ $contactMessage->name }} pada {{ $contactMessage->created_at->format('d M Y H:i') }}:%0D%0A%0D%0A{{ $contactMessage->message }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200 inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Balas Pesan
                </a>
                
                @if(!$contactMessage->is_read)
                <form action="{{ route('admin.contact-messages.mark-as-read', $contactMessage->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors duration-200 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Tandai Sudah Dibaca
                    </button>
                </form>
                @endif
                
                <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200">
                        Hapus Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection 