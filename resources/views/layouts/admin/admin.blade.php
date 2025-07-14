<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - DIJEE Elektronik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            background-color: #f8fafc;
        }

        .sidebar {
            background-color: #1e293b;
            color: #f8fafc;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.15s ease;
        }

        .nav-link-active {
            background-color: #2c7a7b;
            color: white;
        }

        .nav-link-inactive {
            color: #cbd5e1;
        }

        .nav-link-inactive:hover {
            background-color: rgba(44, 122, 123, 0.1);
            color: white;
        }

        .content-header {
            background-color: white;
            border-bottom: 1px solid #e2e8f0;
        }

        .btn-primary {
            background-color: #2c7a7b;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #246c6d;
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #475569;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
        }

        .btn-secondary:hover {
            background-color: #cbd5e1;
        }

        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden sidebar md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 px-4 border-b border-slate-700">
                    <img src="{{ asset('assets/images/lonceng.png') }}" alt="Logo" class="w-8 h-8 mr-2">
                    <span class="text-xl font-bold text-white">DIJEE Admin</span>
                </div>

                <!-- Sidebar Content -->
                <div class="flex flex-col flex-1 overflow-y-auto">
                    <!-- User Info -->
                    <div class="flex items-center px-4 py-6 border-b border-slate-700">
                        <div class="flex-shrink-0">
                            <span class="relative flex items-center justify-center w-10 h-10 bg-teal-600 rounded-full">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->nama }}</p>
                            <p class="text-xs font-medium text-slate-400">Administrator</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="px-3 py-4">
                        <h3 class="px-3 mb-2 text-xs font-semibold tracking-wider uppercase text-slate-400">Menu</h3>
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : 'nav-link-inactive' }}">
                            <i class="w-5 mr-3 text-center fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                            <i class="w-5 mr-3 text-center fas fa-users"></i> Pengguna
                        </a>
                        <a href="{{ route('admin.barang.index') }}"
                            class="nav-link {{ request()->routeIs('admin.barang*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                            <i class="w-5 mr-3 text-center fas fa-box"></i> Barang
                        </a>
                        <!-- <a href="{{ route('admin.banners.index') }}"
                            class="nav-link {{ request()->routeIs('admin.banners*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                            <i class="w-5 mr-3 text-center fas fa-image"></i> Banner Promo
                        </a> -->
                        <!-- <a href="{{ route('admin.flash-sales.index') }}"
                            class="nav-link {{ request()->routeIs('admin.flash-sales*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                            <i class="w-5 mr-3 text-center fas fa-bolt"></i> Flash Sale
                        </a> -->
                        <a href="{{ route('admin.pesanan.index') }}"
                            class="nav-link {{ request()->routeIs('admin.pesanan*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                            <i class="w-5 mr-3 text-center fas fa-shopping-cart"></i> Pesanan
                        </a>
                        <a href="{{ route('admin.transaksi.index') }}"
                            class="nav-link {{ request()->routeIs('admin.transaksi*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                            <i class="w-5 mr-3 text-center fas fa-credit-card"></i> Transaksi
                        </a>
                        <a href="{{ route('admin.laporan.index') }}"
                            class="nav-link {{ request()->routeIs('admin.laporan*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                            <i class="w-5 mr-3 text-center fas fa-chart-bar"></i> Laporan
                        </a>
                    </div>
                </div>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-slate-700">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium text-white transition-colors duration-150 rounded-md hover:bg-slate-700">
                            <i class="mr-2 fas fa-sign-out-alt"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Mobile Header -->
            <div class="flex items-center justify-between px-4 py-3 content-header md:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md text-slate-600">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex items-center">
                    <img src="{{ asset('assets/images/lonceng.png') }}" alt="Logo" class="w-8 h-8">
                    <span class="ml-2 text-lg font-semibold">DIJEE Admin</span>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 rounded-md text-slate-600">
                        <i class="fas fa-user"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 z-10 w-48 py-1 mt-2 bg-white rounded-md shadow-lg">
                        <div class="px-4 py-2 text-xs text-slate-500">{{ Auth::user()->nama }}</div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-sm text-left text-slate-700 hover:bg-slate-100">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Sidebar -->
            <div x-show="sidebarOpen" @click.away="sidebarOpen = false" x-cloak
                class="fixed inset-0 z-40 flex md:hidden">
                <div @click="sidebarOpen = false" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-opacity-75 bg-slate-600"></div>
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-200 transform"
                    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in-out duration-200 transform"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                    class="relative flex flex-col flex-1 w-full max-w-xs bg-slate-800">
                    <div class="absolute top-0 right-0 pt-2 -mr-12">
                        <button @click="sidebarOpen = false"
                            class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <i class="text-white fas fa-times"></i>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex items-center flex-shrink-0 px-4">
                            <span class="text-xl font-bold text-white">DIJEE Admin</span>
                        </div>
                        <nav class="px-2 mt-5 space-y-1">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                <i class="w-5 mr-3 text-center fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link {{ request()->routeIs('admin.users*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                <i class="w-5 mr-3 text-center fas fa-users"></i> Pengguna
                            </a>
                            <a href="{{ route('admin.barang.index') }}"
                                class="nav-link {{ request()->routeIs('admin.barang*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                <i class="w-5 mr-3 text-center fas fa-box"></i> Barang
                            </a>
                            <a href="{{ route('admin.banners.index') }}"
                                class="nav-link {{ request()->routeIs('admin.banners*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                <i class="w-5 mr-3 text-center fas fa-image"></i> Banner Promo
                            </a>
                            <a href="{{ route('admin.flash-sales.index') }}"
                                class="nav-link {{ request()->routeIs('admin.flash-sales*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                <i class="w-5 mr-3 text-center fas fa-bolt"></i> Flash Sale
                            </a>
                            <a href="{{ route('admin.pesanan.index') }}"
                                class="nav-link {{ request()->routeIs('admin.pesanan*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                <i class="w-5 mr-3 text-center fas fa-shopping-cart"></i> Pesanan
                            </a>
                            <a href="{{ route('admin.transaksi.index') }}"
                                class="nav-link {{ request()->routeIs('admin.transaksi*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                <i class="w-5 mr-3 text-center fas fa-credit-card"></i> Transaksi
                            </a>
                            <a href="{{ route('admin.laporan.index') }}"
                                class="nav-link {{ request()->routeIs('admin.laporan*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                <i class="w-5 mr-3 text-center fas fa-chart-bar"></i> Laporan
                            </a>
                        </nav>
                    </div>
                    <div class="flex flex-shrink-0 p-4 border-t border-slate-700">
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium text-white transition-colors duration-150 rounded-md hover:bg-slate-700">
                                <i class="mr-2 fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                @if (session('success'))
                <div class="p-4 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="p-4 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>