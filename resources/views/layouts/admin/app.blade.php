<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Recaje') }} Admin @yield('title', '')</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
        
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" crossorigin="anonymous"></script>
        
        <style>
            /* Sidebar Animation */
            #sidebar {
                transition: all 0.3s ease;
                width: 260px;
                z-index: 40;
            }
            
            @media (max-width: 768px) {
                #sidebar {
                    position: fixed;
                    left: 0;
                    top: 64px;
                    bottom: 0;
                    transform: translateX(-100%);
                    height: calc(100vh - 64px);
                }
                
                #sidebar.active {
                    transform: translateX(0);
                }
                
                .sidebar-overlay {
                    display: none;
                    position: fixed;
                    top: 64px;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: rgba(0, 0, 0, 0.5);
                    z-index: 30;
                }
                
                .sidebar-overlay.active {
                    display: block;
                }

                .main-content {
                    margin-left: 0 !important;
                }
            }
            
            /* Main Content Animation */
            .main-content {
                transition: margin-left 0.3s ease;
                width: 100%;
            }
            
            .main-expanded {
                margin-left: 0 !important;
            }

            /* Desktop Sidebar Toggle */
            @media (min-width: 769px) {
                #sidebar {
                    width: 260px;
                    transition: width 0.3s ease;
                }

                #sidebar.collapsed {
                    width: 0;
                    overflow: hidden;
                }

                .main-content {
                    /* margin-left: 260px; */
                    transition: margin-left 0.3s ease;
                }

                .main-content.expanded {
                    margin-left: 0;
                }
            }
            
            /* Ensure map container has proper z-index */
            #map {
                z-index: 1 !important;
            }
            
            /* Ensure Leaflet controls are visible */
            .leaflet-control-container {
                z-index: 1000 !important;
            }

            /* Enable text selection for table cells */
            td {
                user-select: text !important;
                -webkit-user-select: text !important;
                -moz-user-select: text !important;
                -ms-user-select: text !important;
            }

            /* Content Container */
            .content-container {
                max-width: 1280px;
                margin: 0 auto;
                padding: 1rem;
            }

            @media (min-width: 640px) {
                .content-container {
                    padding: 1.5rem;
                }
            }

            @media (min-width: 1024px) {
                .content-container {
                    padding: 2rem;
                }
            }

            /* Touch Device Fixes */
            @media (hover: none) and (pointer: coarse) {
                .nav-link {
                    padding: 0.75rem 1rem;
                }
                
                button, a {
                    cursor: pointer;
                    -webkit-tap-highlight-color: transparent;
                }
            }
        </style>
        @yield('styles')
    </head>
    <body class="bg-gray-100 dark:bg-gray-300 flex flex-col min-h-screen">
        <!-- Header - full width at the top -->
        <header class="bg-white dark:bg-gray-900 shadow-md sticky top-0 z-50 w-full">
            @include('layouts.admin.header')
        </header>

        <!-- Main container - contains sidebar and content -->
        <div class="flex flex-1 min-h-0">
            <!-- Sidebar Overlay for Mobile -->
            <div id="sidebar-overlay" class="sidebar-overlay"></div>
            
            <!-- Sidebar - left side -->
            <aside id="sidebar" class="bg-gray-800 shrink-0 border-r border-gray-700 h-[calc(100vh-64px)] sticky top-16 overflow-hidden">
                @include('layouts.admin.sidebar')
            </aside>
            
            <!-- Content - right side -->
            <div id="main-content" class="flex-1 flex flex-col main-content">
                <main class="flex-1 overflow-auto">
                    <!-- Session Messages -->
                    <x-session-alerts />
                    
                    <div class="content-container">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
        
        <!-- Footer -->
        <!-- <footer class="bg-gray-800 text-white py-4 sm:py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm sm:text-base">&copy; {{ date('Y') }} Recaje. All rights reserved.</p>
                </div>
            </div>
        </footer> -->

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarToggle = document.getElementById('sidebar-toggle');
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('main-content');
                const sidebarOverlay = document.getElementById('sidebar-overlay');
                
                function toggleSidebar() {
                    if (window.innerWidth >= 769) {
                        // Desktop behavior
                        sidebar.classList.toggle('collapsed');
                        mainContent.classList.toggle('expanded');
                    } else {
                        // Mobile behavior
                        sidebar.classList.toggle('active');
                        sidebarOverlay.classList.toggle('active');
                    }
                }
                
                // Toggle sidebar on button click
                sidebarToggle.addEventListener('click', toggleSidebar);
                
                // Close sidebar when clicking overlay (mobile only)
                sidebarOverlay.addEventListener('click', function() {
                    if (window.innerWidth < 769) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                    }
                });
                
                // Handle window resize
                function handleResize() {
                    if (window.innerWidth >= 769) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                    } else {
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('expanded');
                    }
                }
                
                window.addEventListener('resize', handleResize);
                
                // Initial check
                handleResize();
            });
        </script>
        @stack('scripts')
    </body>
</html> 