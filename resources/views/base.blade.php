<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Vite (CSS + JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('title', 'Mon site')</title>
</head>

<body class="bg-gray-100 text-gray-900">

<!-- Navbar -->
<nav class="sticky top-0 z-50 backdrop-blur-md bg-gray-900/80 border-b border-gray-700 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <!-- Logo -->
        <div class="flex items-center space-x-2 text-xl font-bold text-white">
            <i class="bx bx-layer text-blue-400 text-2xl animate-pulse"></i>
            <span class="">pmvff</span>
        </div>

        <!-- Menu -->
        <ul class="flex items-center space-x-6 text-white">

            <!-- Item -->
            <li class="group relative">
                <a href="/" class="menu-link">
                    <i class="bx bx-home">Accueil</i>
                </a>
                <span class="tooltip">Retour à la page d'accueil pour voir nos publication</span>
            </li>

            @auth
            <li class="group relative">
                <a href="/cartographie" class="menu-link">
                    <i class="bx bx-map">Cartographie</i>
                </a>
                <span class="tooltip">Acceder à la cartographie</span>
            </li>

            <li class="group relative">
                <a href="/forum" class="menu-link">
                    <i class="bx bx-chat">Forum</i>
                </a>
                <span class="tooltip">Participer aux forum</span>
            </li>

            <li class="group relative">
                <a href="/projet" class="menu-link">
                    <i class="bx bx-briefcase">Projets</i>
                </a>
                <span class="tooltip">Visiter les projets en cours</span>
            </li>
              <li class="group relative">
                <a href="/agenda" class="menu-link">
                    <i class="bx bx-calendar">Agenda</i>
                </a>
                <span class="tooltip">Consulter l'agenda</span>
            </li>

            @if(auth()->user()->role === 'admin')
            <li class="group relative">
                <a href="{{ route('admin.users') }}" class="menu-link text-yellow-400">
                    <i class="bx bx-shield">users</i>
                </a>
                <span class="tooltip">Administration</span>
            </li>
          
            @endif
            @endauth
        </ul>

        <!-- User -->
        <div class="flex items-center space-x-4">
            @guest
                <a href="{{ route('login') }}" class="btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn-outline">Register</a>
            @else
                <div class="relative group">
                    <div class="flex items-center space-x-2 cursor-pointer">
                        <i class="bx bx-user-circle text-2xl text-blue-400"></i>
                        <span class="text-white font-medium">
                            {{ Auth::user()->name }}
                        </span>
                    </div>

                    <!-- Dropdown -->
                    <div class="dropdown">
                        <a href="{{ route('profile.show') }}" class="dropdown-item">
                            <i class="bx bx-user"></i> Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item w-full text-left">
                                <i class="bx bx-log-out"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</nav>


<!-- Content -->
<main class="container mx-auto mt-6 px-4">
    @yield('content')
</main>

<!-- Scripts -->
@yield('scripts')

</body>
</html>
