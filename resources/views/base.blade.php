<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Plateforme Multi Acteurs')</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Vite -->
  @vite(['resources/css/app.css', 'resources/css/base.css', 'resources/js/app.js'])

  <style>
    body { font-family: 'Montserrat', sans-serif; background-color: #E9F7F6; }
    .sidebar { background-color: #2d6268; color: white; display: flex; flex-direction: column; width: 18rem; transition: all 0.3s; }
    .sidebar.collapsed { width: 0rem; }
    .sidebar a { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 0.5rem; color: white; text-decoration: none; transition: background 0.2s; white-space: nowrap; }
    .sidebar a:hover { background-color: #3A7378; }
    .sidebar.collapsed a span { display: none; }
    .sidebar-bottom { padding: 1rem; border-top: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; gap: 0.5rem; }
    .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #484f5c 0%, #e1dfe2 100%); display: flex; align-items: center; justify-content: center; font-weight: 600; color: white; }

    /* Styles drawer imbriqué */
    .drawer-sub {
      width: 260px;
      background: #145a5f;
      color: white;
      left: 18rem; /* collé au sidebar principal */
      border-left: 1px solid rgba(255,255,255,0.1);
    }

    .navbar { background-color: #255156; z-index: 50; color: white; }
    .dropdown-menu { display: none; position: absolute; right: 0; background: white; border-radius: 0.5rem; }
    .dropdown-menu.show { display: block; }
  </style>
</head>
<body class="h-screen flex flex-col">

  <!-- NAVBAR -->
  <header class="navbar flex items-center justify-between px-6 py-3 sticky top-0">
    <div class="flex items-center gap-4">
      @auth
      <button id="sidebarToggle" class="text-2xl text-white"
       ><i class='bx bx-menu'></i></button>
      @endauth
      <span class="font-bold text-xl text-white">
        Plateforme<br>Multi Acteurs
      </span>
    </div>
    @auth
    <!-- Profil avec sous-menu -->
    <div class="relative">
      <button id="profileBtn" class="flex items-center gap-2 bg-gray-600 px-3 py-1 rounded-lg hover:bg-gray-900 transition">
        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-gray-600 to-gray-500 flex items-center justify-center text-white font-semibold">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <span>{{ Auth::user()->name }}</span>
        <i class='bx bx-chevron-down'></i>
      </button>
      <div id="profileMenu" class="dropdown-menu mt-2 right-0 w-48 shadow-lg border border-gray-200">
        <a href="/profile" class="block px-4 py-2 hover:bg-gray-100"><i class='bx bx-user'></i> Profil</a>
        <a href="/guide" class="block px-4 py-2 hover:bg-gray-100"><i class='bx bx-book-open'></i> Guide</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100"><i class='bx bx-log-out'></i> Déconnexion</button>
        </form>
      </div>
    </div>
    @endauth

    @guest
    <a href="{{ route('login') }}" class="px-3 py-1 bg-gray-500 text-white rounded-lg hover:bg-gray-800 transition">Se connecter</a>
    @endguest
  </header>

  <div class="flex flex-1 h-full overflow-hidden">
    @auth
    <!-- SIDEBAR PRINCIPAL -->
    <aside id="sidebar" class="sidebar flex flex-col">
      <nav class="flex-1 px-4 space-y-2 text-sm">
        <a href="{{ route('dashboard') }}" class="flex items-center"><i class='bx bx-home'></i> <span>Accueil</span></a>
        <div>
          <a href="#" id="openSecondarySidebar" class="flex items-center gap-2">
            <i class='bx bx-map'></i> Cartographie
          </a>
        </div>
        <a href="{{ route('forum.index') }}" class="flex items-center"><i class='bx bx-chat'></i><span>Forum</span><i class="tooltip">Aller au forum</i></a>
        <a href="/ressources" class="flex items-center"><i class='bx bx-book-open'></i> <span>Ressources</span></a>
        <a href="/projets" class="flex items-center"><i class='bx bx-briefcase'></i> <span>Projets</span></a>
        <a href="/agenda" class="flex items-center"><i class='bx bx-calendar'></i> <span>Agenda</span></a>
        <a href="{{ route('structures.index') }}" class="flex items-center"><i class='bx bx-building'></i> <span>Structure</span></a>
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'moderateur')
        <a href="{{ route('admin.users') }}" class="flex items-center"><i class='bx bx-shield'></i> <span>Administration</span></a>
        @endif
      </nav>
      <div class="sidebar-bottom">
        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
        <div class="overflow-hidden">
          <div>{{ Auth::user()->name }}</div>
          <div style="font-size: 0.75rem; opacity: 0.8;">{{ Auth::user()->role }}</div>
        </div>
      </div>
    </aside>
   <!-- SIDEBAR SECONDAIRE -->
    <div class="offcanvas offcanvas-start drawer-sub" id="secondarySidebar" tabindex="-1" 
        style="width: 18rem; height: 100%; background-color: #2d6268; color: white;">
      <div class="offcanvas-header border-b border-gray-700">
        <h5 class="font-bold text-lg">Cartographie</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body p-4 flex flex-col flex-1">
        <a href="{{ route('annuaire.index') }}" 
          class="block px-3 py-2 rounded hover:bg-gray-700 text-white"><i class='bx bx-book-reader'></i>
          Annuaire
        </a>
        <a href="/structures/map" 
          class="block px-3 py-2 rounded hover:bg-gray-700 text-white"><i class='bx bx-map-alt'></i>
          Carte interactive
        </a>
      </div>
    </div>

    @endauth

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col bg-[#ECFAF9] p-6 overflow-y-auto">
      @yield('content')
    </main>
  </div>

  <script>
    @auth
    // Sidebar principal toggle
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
    });

    // Sidebar secondaire (Bootstrap offcanvas)
    const openSecondarySidebar = document.getElementById('openSecondarySidebar');
    if (openSecondarySidebar) {
      openSecondarySidebar.addEventListener('click', (e) => {
        e.preventDefault();
        const secondaryDrawer = new bootstrap.Offcanvas(document.getElementById('secondarySidebar'));
        secondaryDrawer.show();
      });
    }
    @endauth

    // Profil dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    if(profileBtn){
      profileBtn.addEventListener('click', () => {
        profileMenu.classList.toggle('show');
      });

      document.addEventListener('click', (e) => {
        if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
          profileMenu.classList.remove('show');
        }
      });
    }
  </script>
@yield('scripts')
</body>
</html>
