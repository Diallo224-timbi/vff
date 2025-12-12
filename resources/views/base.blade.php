<!DOCTYPE html>
<html lang="en">
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
   <!-- Navbar -->
<nav class="bg-gray-900 text-white p-4 flex justify-between items-center">
    <ul class="flex space-x-4">
        <li>
            <a href="/" class="hover:text-gray-300">
               <i class="bx bx-home"></i> 
               <span>Home</span>
            </a>
        </li>

        @auth
            <!-- Liens visibles uniquement pour les utilisateurs connectés -->
            <li>
                <a href="/cartographie" class="hover:text-gray-300">
                    <i class="bx bx-map"></i>
                    <span>Cartographie</span>
                </a>
            </li>
            <li>
                <a href="/forum" class="hover:text-gray-300">
                    <i class="bx bx-chat"></i>
                    <span>Forum</span>
                </a>
            </li>
            <li>
                <a href="/projet" class="hover:text-gray-300">
                    <i class="bx bx-briefcase"></i>
                    <span>Projet</span>
                </a>
            </li>
        @endauth
    </ul>

    <div class="flex space-x-4">
        @guest
            <!-- Lien login uniquement pour les invités -->
            <a href="{{ route('login') }}" class="flex items-center space-x-1 hover:text-gray-300">
                <i class='bx bx-log-in'></i>
                <span>Login</span>
            </a>
            <a href="{{ route('register') }}" class="flex items-center space-x-1 hover:text-gray-300">
                <i class='bx bx-user-plus'></i>
                <span>Register</span>
            </a>
        @else
            <!-- Bouton logout pour les utilisateurs connectés -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-1 hover:text-gray-300 bg-transparent border-none cursor-pointer">
                    <i class='bx bx-log-out'></i>
                    <span>Logout</span>
                </button>
            </form>
        @endguest
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
