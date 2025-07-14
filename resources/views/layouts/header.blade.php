<!-- Navigation -->
<nav class="sticky top-0 w-full bg-white dark:bg-gray-900 shadow-md z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                
                <a href="/" class="font-bold text-gray-800 dark:text-white">
                    <img src="{{ asset('assets/images/LogoLight.png') }}" alt="Recaje Logo" class="hidden md:block dark:hidden" style="width: 100px; height: auto;">
                    <img src="{{ asset('assets/images/LogoDark.png') }}" alt="Recaje Logo" class="hidden md:dark:block" style="width: 100px; height: auto;">
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="/" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer">Home</a>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none flex items-center cursor-pointer">
                        <span>Features</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('all-cafes') }}" class="block px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">Daftar Cafe</a>
                        <a href="{{ route('search-cafe') }}" class="block px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">Rekomendasi Cafe</a>
                        <!-- <a href="{{ route('recommend-cafe') }}" class="block px-4 py-2 text-sm text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">Rekomendasi Cafe</a> -->
                        <a href="{{ route('cafe.search-history') }}" class="block px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">Riwayat Rekomendasi</a>
                    </div>
                </div>
                
                <a href="/#about" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer">About</a>
                <a href="/#contact" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white cursor-pointer">Contact</a>
                
                @guest
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300 cursor-pointer">Login</a>
                @else
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none cursor-pointer">
                            <div class="h-8 w-8 rounded-full bg-transparent overflow-hidden flex items-center justify-center">
                                <img src="{{ asset('assets/icons/User Light.png') }}" alt="User Profile" class="w-6 h-6 block dark:hidden" style="max-width: 25px; max-height: 25px;">
                                <img src="{{ asset('assets/icons/User Dark.png') }}" alt="User Profile" class="w-6 h-6 hidden dark:block" style="max-width: 25px; max-height: 25px;">
                            </div>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                                <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">Profil dan Pengaturan</a>
                            <a href="{{ route('favorites.index') }}" class="block px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">Cafe Favorit</a>
                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                               class="block px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav> 