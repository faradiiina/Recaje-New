@extends('layouts.admin.app')

@section('title', '| Pesan Kontak')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Pesan Kontak</h1>
        </div>

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
                            PESAN
                        </th>
                        <th scope="col" class="px-6 py-3">
                            TANGGAL
                        </th>
                        <th scope="col" class="px-6 py-3">
                            STATUS
                        </th>
                        <th scope="col" class="px-6 py-3 text-right rounded-tr-lg">
                            
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr class="h-16 bg-white dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 transition-colors hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $message->name }}
                            </th>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $message->email }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ Str::limit($message->message, 50) }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $message->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($message->is_read)
                                    <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Dibaca
                                    </span>
                                @else
                                    <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Belum Dibaca
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right {{ $loop->last && $loop->first ? 'rounded-br-lg' : '' }} {{ $loop->last ? 'rounded-br-lg' : '' }}">
                                <div class="flex items-center justify-end space-x-4">
                                    <a href="{{ route('admin.contact-messages.show', $message) }}" class="text-xs font-semibold uppercase hover:underline text-blue-600 dark:text-blue-400">
                                        Detail
                                    </a>
                                    <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold uppercase hover:underline text-blue-600 dark:text-blue-400 cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="h-16 bg-white dark:bg-gray-700">
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 rounded-b-lg">
                                Tidak ada pesan kontak yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    </div>
@endsection 