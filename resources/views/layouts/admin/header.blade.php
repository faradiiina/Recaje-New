<!-- Admin Navigation -->
<nav class="w-full bg-white dark:bg-gray-900 shadow-md">
    <div class="px-6">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white focus:outline-none cursor-pointer">
                    <svg class="h-6 w-6 menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <div class="flex items-center">
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
                        <div class="border-t border-gray-200 dark:border-gray-600"></div>
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                           class="block px-4 py-2 text-sm text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav> 