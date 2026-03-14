<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @isset($title) {{ $title }} - @endif Mancraft Finance
    </title>

    <!-- Vite Assets (Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Asset CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --brand-yellow: #F9C74F;
            --charcoal-gray: #343A40;
            --off-white: #F8F9FA;
            --navy-blue: #0A2463;
        }

        body {
            background-color: var(--off-white);
        }

        .sidebar-bg {
            background: linear-gradient(180deg, var(--charcoal-gray) 0%, #212529 100%);
        }

        .text-brand {
            color: var(--brand-yellow);
        }

        .text-primary {
            color: var(--charcoal-gray);
        }

        .btn-cta {
            background: linear-gradient(135deg, var(--navy-blue), #061a4a);
            transition: all 0.3s ease;
        }

        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(10, 36, 99, 0.3);
        }

        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(52, 58, 64, 0.1), 0 2px 4px -1px rgba(52, 58, 64, 0.06);
            transition: all 0.3s ease;
        }

        .card-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(52, 58, 64, 0.15);
        }

        .kpi-card {
            position: relative;
            overflow: hidden;
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .kpi-card:hover::before {
            left: 100%;
        }

        .kpi-card .icon {
            transition: all 0.3s ease;
        }

        .kpi-card:hover .icon {
            transform: scale(1.1) rotate(5deg);
        }

        .sidebar-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background-color: var(--brand-yellow);
            transition: height 0.3s ease;
        }

        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            height: 70%;
        }

        .sidebar-link:hover {
            background-color: rgba(0, 0, 0, 0.2);
            padding-left: 20px;
        }

        .sidebar-link.active {
            background-color: rgba(0, 0, 0, 0.3);
        }

        .counter {
            transition: all 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .activity-item {
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .activity-item:hover {
            border-left-color: var(--brand-yellow);
            background-color: rgba(249, 199, 79, 0.05);
        }

        .quick-action-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .quick-action-card:hover {
            border-color: var(--brand-yellow);
            transform: scale(1.05);
        }

        .sidebar-collapsed {
            width: 80px;
        }

        .sidebar-collapsed .sidebar-text {
            display: none;
        }

        .sidebar-collapsed .sidebar-link span {
            display: none;
        }

        .sidebar-collapsed #sidebar-logo span {
            display: none;
        }

        /* Logo styling */
        .logo-image {
            object-fit: contain;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin: 1rem 0;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin: 0 0.5rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            margin: 0 0.25rem;
            background: white;
            color: #374151;
            text-decoration: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--brand-yellow);
            border-color: var(--brand-yellow);
            color: white;
        }

        .dataTables_wrapper table.dataTable thead th {
            border-bottom: 2px solid #e5e7eb;
            background: #f9fafb;
            font-weight: 600;
            color: var(--charcoal-gray);
        }

        .dataTables_wrapper table.dataTable tbody tr:hover {
            background-color: #f0fdf4;
        }

        /* Additional layout fixes for scrolling */
        html,
        body {
            height: 100%;
            overflow-x: hidden;
        }

        body {
            scroll-behavior: smooth;
        }

        .flex.h-screen {
            min-height: 100vh;
            height: 100vh;
        }

        /* Ensure main content area scrolls properly */
        main {
            position: relative;
            height: calc(100vh - 80px);
            /* Adjust based on header height */
            max-height: calc(100vh - 80px);
        }

        /* Fix z-index issues */
        .sidebar-bg {
            z-index: 40;
        }

        aside {
            z-index: 40;
        }

        /* Mobile fixes */
        @media (max-width: 768px) {
            main {
                height: calc(100vh - 60px);
                max-height: calc(100vh - 60px);
                padding: 1rem;
            }

            .sidebar-bg {
                z-index: 50;
            }
        }

        /* Prevent content from going under fixed elements */
        .container {
            position: relative;
            z-index: 1;
        }

        /* Profile dropdown fixes */
        #user-dropdown {
            z-index: 9999 !important;
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            margin-top: 0.5rem !important;
            min-width: 12rem !important;
            background: white !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            border: 1px solid #e5e7eb !important;
        }

        /* Ensure header has proper z-index */
        header {
            position: relative;
            z-index: 100;
        }

        /* Profile dropdown button container */
        .relative {
            position: relative;
        }

        /* Make sure dropdown is clickable */
        #user-dropdown a,
        #user-dropdown button {
            position: relative;
            z-index: 10000;
            pointer-events: auto;
            display: block;
            width: 100%;
        }

        /* Dropdown animation */
        #user-dropdown {
            transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
            transform-origin: top right;
        }

        #user-dropdown.hidden {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
        }

        #user-dropdown:not(.hidden) {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }
    </style>

    <!-- Stacked Styles from Child Views -->
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="flex h-screen">

        <!-- Sidebar -->
        <aside id="sidebar"
            class="sidebar-bg text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-all duration-300 ease-in-out z-50">
            <div class="flex justify-between items-center px-4">
                <a href="{{ route('dashboard') }}" id="sidebar-logo"
                    class="logo-container text-white flex items-center space-x-2">
                    <img src="{{ asset('Logo_Mancraft.jpg') }}" alt="Mancraft Logo"
                        class="logo-image w-10 h-10 object-contain">
                    <span class="sidebar-text text-2xl font-extrabold">Mancraft</span>
                </a>
            </div>
            <nav>
                <a href="{{ route('dashboard') }}"
                    class="sidebar-link @if (request()->routeIs('dashboard')) active @endif flex items-center py-2.5 px-4 rounded text-white">
                    <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('transaksi.index') }}"
                    class="sidebar-link @if (request()->routeIs('transaksi.*')) active @endif flex items-center py-2.5 px-4 rounded text-white hover:text-brand">
                    <i class="fas fa-exchange-alt mr-3 w-5"></i>
                    <span>Transaksi</span>
                </a>
                <a href="{{ route('laporan.index') }}"
                    class="sidebar-link @if (request()->routeIs('laporan.*')) active @endif flex items-center py-2.5 px-4 rounded text-white hover:text-brand">
                    <i class="fas fa-file-invoice-dollar mr-3 w-5"></i>
                    <span>Laporan</span>
                </a>

                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('layanan.index') }}"
                        class="sidebar-link @if (request()->routeIs('layanan.*')) active @endif flex items-center py-2.5 px-4 rounded text-white hover:text-brand">
                        <i class="fas fa-scissors mr-3 w-5"></i>
                        <span>Layanan</span>
                    </a>
                    <a href="{{ route('karyawan.index') }}"
                        class="sidebar-link @if (request()->routeIs('karyawan.*')) active @endif flex items-center py-2.5 px-4 rounded text-white hover:text-brand">
                        <i class="fas fa-users mr-3 w-5"></i>
                        <span>Karyawan</span>
                    </a>
                    <a href="{{ route('admin.chatbot.index') }}"
                        class="sidebar-link @if (request()->routeIs('admin.chatbot.*')) active @endif flex items-center py-2.5 px-4 rounded text-white hover:text-brand">
                        <i class="fas fa-robot mr-3 w-5"></i>
                        <span>Chatbot FAQ</span>
                    </a>
                @endif
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <!-- Mobile Menu Toggle -->
                    <button id="menu-toggle" class="md:hidden text-primary hover:opacity-75 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <!-- Menu Profil di Pojok Kanan -->
                    <div class="flex items-center space-x-4 ml-auto">
                        <div class="relative">
                            <button onclick="toggleDropdown(event)"
                                class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2"
                                style="focus:ring-color: var(--brand-yellow);">
                                <img class="h-8 w-8 rounded-full"
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=100"
                                    alt="Avatar">
                                <span
                                    class="ml-2 font-medium text-primary hidden sm:block">{{ Auth::user()->name ?? 'Admin' }}</span>
                                <i class="fas fa-chevron-down ml-2 text-primary opacity-50"></i>
                            </button>
                            <div id="user-dropdown"
                                class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    {{-- <a href="#" class="block px-4 py-2 text-sm text-primary hover:bg-gray-100">Profil Saya</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-primary hover:bg-gray-100">Pengaturan</a> --}}
                                    <hr class="my-1">
                                    <!-- Form logout untuk keamanan -->
                                    <form id="logout-form-app" method="POST" action="{{ route('logout') }}"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <button onclick="confirmLogoutApp()"
                                        class="block w-full text-left px-4 py-2 text-sm text-primary hover:bg-gray-100">Keluar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Konten Dinamis Akan Dimuat Di Sini -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 fade-in-up"
                style="scroll-behavior: smooth; -webkit-overflow-scrolling: touch;">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Asset JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Realtime Clock Helper -->
    <script src="{{ asset('js/realtime-clock.js') }}"></script>

    <!-- Script untuk Sidebar dan Dropdown (Global) -->
    <script>
        // Mobile sidebar toggle
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // Enhanced dropdown functionality
        function toggleDropdown(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            const dropdown = document.getElementById('user-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');

                // Add focus management
                if (!dropdown.classList.contains('hidden')) {
                    dropdown.focus();
                }
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('user-dropdown');
            const button = e.target.closest('button[onclick*="toggleDropdown"]');

            if (dropdown && !dropdown.classList.contains('hidden')) {
                // If clicked outside both dropdown and button, close dropdown
                if (!button && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            }
        });

        // Close dropdown with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const dropdown = document.getElementById('user-dropdown');
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            }
        });

        // Prevent dropdown from closing when clicking inside it
        document.getElementById('user-dropdown')?.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Ensure dropdown is properly positioned on page load
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.getElementById('user-dropdown');
            if (dropdown) {
                // Ensure dropdown starts hidden
                dropdown.classList.add('hidden');

                // Set proper initial styles
                dropdown.style.position = 'absolute';
                dropdown.style.zIndex = '9999';
                dropdown.style.right = '0';
                dropdown.style.top = '100%';
                dropdown.style.marginTop = '0.5rem';
            }
        });

        // SweetAlert logout confirmation
        function confirmLogoutApp() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari sistem",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0A2463',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form-app').submit();
                }
            });
        }
    </script>

    <!-- Slot untuk script spesifik halaman (jika ada) -->
    @stack('scripts')

    <!-- Chatbot Widget -->
    <x-chatbot-widget />
</body>

</html>
