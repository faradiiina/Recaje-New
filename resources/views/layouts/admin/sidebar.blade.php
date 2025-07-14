<!-- Admin Sidebar -->
<div class="bg-white dark:bg-gray-800 text-gray-800 dark:text-white min-h-screen">
    <div class="flex flex-col h-full">
        <!-- Logo Recaje -->
        <div class="py-3 px-2 border-b border-gray-200 dark:border-gray-700 justify-center mt-4 hidden md:flex">
            <img src="{{ asset('assets/images/Logo.png') }}" alt="Recaje Logo" style="width: 120px; height: auto;">
        </div>
        
        <nav class="py-3 flex-grow">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                        <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.users.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                        <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>Pengguna</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.cafes.index') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.cafes.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                        <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Café</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.categories.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                        <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span>Kriteria</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.subcategories.index') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.subcategories.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                        <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span>Sub-Kriteria</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.contact-messages.index') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.contact-messages.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                        <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span>Pesan Kontak</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="p-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <p>Recaje Admin Panel</p>
                <p>Version 1.0</p>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Sidebar -->
<div x-show="sidebarOpen" @click.away="sidebarOpen = false" x-cloak class="fixed z-40 flex md:hidden top-16 bottom-0">
    <div @click="sidebarOpen = false" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-linear duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
    
    <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-200 transform"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-200 transform"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        class="relative flex flex-col flex-1 w-full max-w-xs bg-white dark:bg-gray-800">
        
        <div class="absolute top-0 right-0 pt-2 -mr-12">
            <button @click="sidebarOpen = false"
                class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
            <div class="flex items-center flex-shrink-0 px-4 hidden">
                <img src="{{ asset('assets/images/Logo.png') }}" alt="Recaje Logo" class="h-8">
            </div>
            <nav class="py-3 px-4 flex-grow md:px-0">
                <a href="{{ route('admin.dashboard') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                    <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.users.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                    <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Pengguna</span>
                </a>
                <a href="{{ route('admin.cafes.index') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.cafes.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                    <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Café</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.categories.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                    <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span>Kategori</span>
                </a>
                <a href="{{ route('admin.subcategories.index') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.subcategories.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                    <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Sub-Kategori</span>
                </a>
                <a href="{{ route('admin.contact-messages.index') }}" style="border-radius: 6px;" class="{{ request()->routeIs('admin.contact-messages.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }} flex items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 text-sm text-gray-800 dark:text-white hover:text-yellow-500 dark:hover:text-yellow-400">
                    <svg class="h-5 w-5" style="margin-right: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Pesan Kontak</span>
                </a>
            </nav>
        </div>

        <div class="flex flex-shrink-0 p-4 border-t border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <p>Recaje Admin Panel</p>
                <p>Version 1.0</p>
            </div>
        </div>
    </div>
</div> 