<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduMaster') }} - @yield('title', 'School Management System')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-turquoise: #20B2AA;
            --primary-turquoise-dark: #1A9B94;
            --primary-turquoise-light: #4DD0C7;
            --secondary-orange: #FF8C42;
            --secondary-orange-dark: #E67A3A;
            --secondary-orange-light: #FFB366;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--gray-50);
        }

        .bg-primary {
            background-color: var(--primary-turquoise);
        }

        .bg-primary-dark {
            background-color: var(--primary-turquoise-dark);
        }

        .bg-secondary {
            background-color: var(--secondary-orange);
        }

        .text-primary {
            color: var(--primary-turquoise);
        }

        .text-secondary {
            color: var(--secondary-orange);
        }

        .border-primary {
            border-color: var(--primary-turquoise);
        }

        .hover\:bg-primary:hover {
            background-color: var(--primary-turquoise);
        }

        .hover\:bg-primary-dark:hover {
            background-color: var(--primary-turquoise-dark);
        }

        .btn-primary {
            background-color: var(--primary-turquoise);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--primary-turquoise-dark);
        }

        .btn-secondary {
            background-color: var(--secondary-orange);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background-color: var(--secondary-orange-dark);
        }

        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary-turquoise) 0%, var(--primary-turquoise-dark) 100%);
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .navbar {
            background: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-link i {
            margin-right: 0.75rem;
            width: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary-turquoise);
        }

        .stat-card.secondary {
            border-left-color: var(--secondary-orange);
        }

        .table {
            width: 100%;
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: var(--gray-50);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
        }

        .table td {
            padding: 1rem;
            border-top: 1px solid var(--gray-200);
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: #10B981;
            color: white;
        }

        .badge-warning {
            background-color: #F59E0B;
            color: white;
        }

        .badge-danger {
            background-color: #EF4444;
            color: white;
        }

        .badge-info {
            background-color: var(--primary-turquoise);
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @livewireStyles
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Sidebar -->
        <nav class="sidebar" x-data="{ open: false }">
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-graduation-cap text-2xl text-white mr-3"></i>
                    <h1 class="text-xl font-bold text-white">EduMaster</h1>
                </div>
                <p class="text-sm text-gray-200 mt-1">School Management</p>
            </div>

            <div class="mt-8">
                @auth
                    <!-- Admin/Director Menu -->
                    @if(auth()->user()->hasRole(['admin', 'director']))
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Tableau de Bord
                        </a>
                        <a href="{{ route('students.index') }}" class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate"></i>
                            Élèves
                        </a>
                        <a href="{{ route('teachers.index') }}" class="sidebar-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                            <i class="fas fa-chalkboard-teacher"></i>
                            Professeurs
                        </a>
                        <a href="{{ route('classes.index') }}" class="sidebar-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                            <i class="fas fa-door-open"></i>
                            Classes
                        </a>
                        <a href="{{ route('subjects.index') }}" class="sidebar-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            Matières
                        </a>
                        <a href="{{ route('grades.index') }}" class="sidebar-link {{ request()->routeIs('grades.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            Notes
                        </a>
                        <a href="{{ route('attendances.index') }}" class="sidebar-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check"></i>
                            Présences
                        </a>
                        <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                            <i class="fas fa-money-bill-wave"></i>
                            Paiements
                        </a>
                        <a href="{{ route('timetables.index') }}" class="sidebar-link {{ request()->routeIs('timetables.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            Emploi du Temps
                        </a>
                        <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            Rapports
                        </a>
                    @endif

                    <!-- Teacher Menu -->
                    @if(auth()->user()->hasRole('teacher'))
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Tableau de Bord
                        </a>
                        <a href="{{ route('teacher.classes') }}" class="sidebar-link {{ request()->routeIs('teacher.classes') ? 'active' : '' }}">
                            <i class="fas fa-door-open"></i>
                            Mes Classes
                        </a>
                        <a href="{{ route('teacher.students') }}" class="sidebar-link {{ request()->routeIs('teacher.students') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate"></i>
                            Mes Élèves
                        </a>
                        <a href="{{ route('grades.index') }}" class="sidebar-link {{ request()->routeIs('grades.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            Notes
                        </a>
                        <a href="{{ route('attendances.index') }}" class="sidebar-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check"></i>
                            Présences
                        </a>
                        <a href="{{ route('teacher.timetable') }}" class="sidebar-link {{ request()->routeIs('teacher.timetable') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            Mon Emploi du Temps
                        </a>
                    @endif

                    <!-- Parent Menu -->
                    @if(auth()->user()->hasRole('parent'))
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Tableau de Bord
                        </a>
                        <a href="{{ route('parent.children') }}" class="sidebar-link {{ request()->routeIs('parent.children') ? 'active' : '' }}">
                            <i class="fas fa-child"></i>
                            Mes Enfants
                        </a>
                    @endif

                    <!-- Secretary Menu -->
                    @if(auth()->user()->hasRole('secretary'))
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Tableau de Bord
                        </a>
                        <a href="{{ route('students.index') }}" class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate"></i>
                            Élèves
                        </a>
                        <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                            <i class="fas fa-money-bill-wave"></i>
                            Paiements
                        </a>
                        <a href="{{ route('attendances.index') }}" class="sidebar-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check"></i>
                            Présences
                        </a>
                    @endif

                    <!-- Student Menu -->
                    @if(auth()->user()->hasRole('student'))
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Tableau de Bord
                        </a>
                    @endif
                @endauth
            </div>

            <!-- User Profile Section -->
            @auth
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white border-opacity-20">
                    <div class="flex items-center text-white">
                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-200 truncate">
                                @foreach(auth()->user()->roles as $role)
                                    {{ ucfirst($role->name) }}@if(!$loop->last), @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            @endauth
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <nav class="navbar">
                <div class="flex items-center">
                    <button class="md:hidden mr-4" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-gray-600"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bell"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border z-50">
                            <div class="p-4 border-b">
                                <h3 class="font-semibold text-gray-800">Notifications</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <div class="p-4 border-b hover:bg-gray-50">
                                    <p class="text-sm text-gray-800">Nouveau paiement reçu</p>
                                    <p class="text-xs text-gray-500 mt-1">Il y a 5 minutes</p>
                                </div>
                                <div class="p-4 border-b hover:bg-gray-50">
                                    <p class="text-sm text-gray-800">Absence signalée</p>
                                    <p class="text-xs text-gray-500 mt-1">Il y a 1 heure</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100">
                            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">{{ auth()->user()->name ?? 'Utilisateur' }}</span>
                            <i class="fas fa-chevron-down text-gray-500 text-xs"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-user-edit mr-2"></i>
                                Profil
                            </a>
                            <div class="border-t"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>
