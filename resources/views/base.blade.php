<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
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
    /* ===== VARIABLES RESPONSIVES ===== */
    :root {
      --sidebar-width: 18rem;
      --sidebar-collapsed: 0rem;
      --header-height: 70px;
      --drawer-width: 280px;
    }

    /* ===== RESET & BASE ===== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #E9F7F6;
      overflow-x: hidden;
      width: 100%;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ===== LAYOUT PRINCIPAL ===== */
    .app-wrapper {
      display: flex;
      flex: 1;
      height: calc(100vh - var(--header-height));
      overflow: hidden;
      position: relative;
    }

    /* ===== NAVBAR RESPONSIVE ===== */
    .navbar {
      background-color: #255156;
      z-index: 1050;
      color: white;
      height: var(--header-height);
      padding: 0.75rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      width: 100%;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .logo-text {
      font-weight: 700;
      font-size: 1.25rem;
      line-height: 1.2;
      color: white;
    }

    @media (max-width: 640px) {
      .navbar {
        padding: 0.5rem 1rem;
      }
      .logo-text {
        font-size: 1rem;
      }
      .logo-text br {
        display: none;
      }
      .logo-text span {
        display: inline;
      }
    }

    /* ===== SIDEBAR PRINCIPAL RESPONSIVE ===== */
    .sidebar {
      background-color: #2d6268;
      color: white;
      display: flex;
      flex-direction: column;
      width: var(--sidebar-width);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      height: 100%;
      flex-shrink: 0;
      overflow-y: auto;
      overflow-x: hidden;
    }

    .sidebar.collapsed {
      width: 5rem;
    }

    .sidebar.collapsed .sidebar-link span,
    .sidebar.collapsed .sidebar-bottom .user-info {
      display: none;
    }

    .sidebar.collapsed .sidebar-bottom {
      justify-content: center;
      padding: 1rem 0;
    }

    .sidebar-nav {
      flex: 1;
      padding: 1.5rem 0.75rem;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      border-radius: 0.75rem;
      color: white;
      text-decoration: none;
      transition: all 0.2s;
      white-space: nowrap;
      font-size: 0.95rem;
    }

    .sidebar-link i {
      font-size: 1.25rem;
      flex-shrink: 0;
    }

    .sidebar-link:hover {
      background-color: #3A7378;
      transform: translateX(5px);
    }

    .sidebar-bottom {
      padding: 1.25rem 1rem;
      border-top: 1px solid rgba(255,255,255,0.15);
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-shrink: 0;
    }

    .user-avatar {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: linear-gradient(135deg, #484f5c 0%, #e1dfe2 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      color: white;
      flex-shrink: 0;
    }

    .user-info {
      overflow: hidden;
      white-space: nowrap;
    }

    .user-name {
      font-weight: 600;
      font-size: 0.9rem;
    }

    .user-role {
      font-size: 0.7rem;
      opacity: 0.9;
    }

    /* ===== MOBILE SIDEBAR ===== */
    @media (max-width: 1024px) {
      .sidebar {
        position: fixed;
        left: 0;
        top: var(--header-height);
        height: calc(100vh - var(--header-height));
        z-index: 1040;
        box-shadow: 2px 0 15px rgba(0,0,0,0.1);
      }

      .sidebar.collapsed {
        left: calc(-1 * var(--sidebar-width) + 5rem);
      }

      .sidebar.collapsed:hover {
        left: 0;
      }

      .sidebar:not(.collapsed) {
        left: 0;
      }

      .app-wrapper {
        position: relative;
      }

      .main-content {
        width: 100%;
        margin-left: 0;
      }
    }

    @media (max-width: 640px) {
      .sidebar {
        width: 85%;
        max-width: 320px;
      }

      .sidebar.collapsed {
        left: -100%;
      }
    }

    /* ===== DESSIN SECONDAIRE (OFFCANVAS) ===== */
    .offcanvas.drawer-sub {
      width: min(18rem, 85vw) !important;
      background-color: #145a5f !important;
      color: white;
    }

    .offcanvas-header {
      border-bottom: 1px solid rgba(255,255,255,0.1);
      padding: 1.25rem 1.5rem;
    }

    .offcanvas-body {
      padding: 1.5rem !important;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .drawer-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      border-radius: 0.75rem;
      color: white;
      text-decoration: none;
      transition: background 0.2s;
    }

    .drawer-link:hover {
      background-color: rgba(255,255,255,0.15);
    }

    /* ===== PROFIL DROPDOWN ===== */
    .profile-dropdown {
      position: relative;
    }

    .profile-btn {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      background-color: rgba(255,255,255,0.1);
      border-radius: 2rem;
      transition: all 0.2s;
      border: 1px solid rgba(255,255,255,0.2);
    }

    .profile-btn:hover {
      background-color: rgba(255,255,255,0.2);
    }

    .dropdown-menu-custom {
      position: absolute;
      top: 120%;
      right: 0;
      background: white;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      width: 240px;
      display: none;
      z-index: 1060;
      border: 1px solid #e2e8f0;
      overflow: hidden;
    }

    .dropdown-menu-custom.show {
      display: block;
      animation: fadeInDown 0.2s ease;
    }

    .dropdown-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1.25rem;
      color: #1a202c;
      text-decoration: none;
      transition: background 0.2s;
      width: 100%;
      border: none;
      background: transparent;
      font-size: 0.95rem;
    }

    .dropdown-item:hover {
      background-color: #f7fafc;
    }

    .dropdown-item i {
      color: #4a5568;
      width: 1.25rem;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
      flex: 1;
      background: linear-gradient(135deg, #ECFAF9 0%, #f0fdfc 100%);
      overflow-y: auto;
      overflow-x: hidden;
      padding: 1.5rem;
      transition: margin-left 0.3s ease;
      width: 100%;
    }

    @media (max-width: 1024px) {
      .main-content {
        padding: 1rem;
      }
    }

    @media (max-width: 640px) {
      .main-content {
        padding: 0.75rem;
      }
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* ===== UTILITAIRES RESPONSIVES ===== */
    .container-fluid-responsive {
      width: 100%;
      max-width: 1600px;
      margin: 0 auto;
      padding: 0 1rem;
    }

    @media (max-width: 640px) {
      .container-fluid-responsive {
        padding: 0 0.5rem;
      }
    }

    /* ===== SCROLLBAR PERSONNALISÉE ===== */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    ::-webkit-scrollbar-track {
      background: rgba(0,0,0,0.05);
    }

    ::-webkit-scrollbar-thumb {
      background: rgba(45, 98, 104, 0.5);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: rgba(45, 98, 104, 0.8);
    }
  </style>
</head>
<body>
  <!-- NAVBAR RESPONSIVE -->
  <header class="navbar">
    <div class="navbar-brand">
      @auth
      <button id="sidebarToggle" class="text-2xl text-white hover:opacity-80 transition">
        <i class='bx bx-menu'></i>
      </button>
      @endauth
      <div class="logo-text">
        <span>Plateforme</span><span class="hidden sm:inline"><br></span><span>Multi Acteurs</span>
      </div>
    </div>

    @auth
    <!-- Profil avec dropdown responsive -->
    <div class="profile-dropdown">
      <button id="profileBtn" class="profile-btn">
        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-gray-900 to-gray-500 flex items-center justify-center text-white font-semibold">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <span class="hidden md:inline">{{ Auth::user()->name }}</span>
        <i class='bx bx-chevron-down'></i>
      </button>
      
      <div id="profileMenu" class="dropdown-menu-custom ">
        <a href="/profile" class="dropdown-item">
          <i class='bx bx-user'></i>
          Profil
        </a>
        <a href="/guide" class="dropdown-item">
          <i class='bx bx-book-open'></i>
          Guide
        </a>
        <div class="border-t border-gray-200 my-1"></div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="dropdown-item">
            <i class='bx bx-log-out'></i>
            Déconnexion
          </button>
        </form>
      </div>
    </div>
    @endauth

    @guest
    <a href="{{ route('login') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition backdrop-blur-sm">
      Se connecter
    </a>
    @endguest
  </header>

  <div class="app-wrapper">
    @auth
    <!-- SIDEBAR PRINCIPAL RESPONSIVE -->
    <aside id="sidebar" class="sidebar">
      <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="sidebar-link">
          <i class='bx bx-home'></i>
          <span>Accueil</span>
        </a>
        
        <div>
          <a href="#" id="openSecondarySidebar" class="sidebar-link">
            <i class='bx bx-book-reader'></i>
            <span title="Acceder à la liste et à la carte interactive de l'annuaire">Annuaire</span>
          </a>
        </div>

        <a href="/ressources" class="sidebar-link">
          <i class='bx bx-book-open'></i>
          <span>Ressources</span>
        </a>

        <a href="/projets" class="sidebar-link">
          <i class='bx bx-briefcase'></i>
          <span>Projets</span>
        </a>
        <a href="{{ route('forum.index') }}" class="sidebar-link">
          <i class='bx bx-chat'></i>
          <span>Forum</span>
        </a>
        <a href="/agenda" class="sidebar-link">
          <i class='bx bx-calendar'></i>
          <span>Agenda</span>
        </a>
        <!--
        <a href="{{ route('structures.index') }}" class="sidebar-link">
          <i class='bx bx-building'></i>
          <span>Structure</span>
        </a>
      -->
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'moderateur')
        <a href="{{ route('admin.users') }}" class="sidebar-link">
          <i class='bx bx-shield'></i>
          <span>Administration</span>
        </a>
        <!-- logs d'activité accessible uniquement aux admins -->
        
        @endif
          @if(Auth::user()->role === 'admin')
          <a href="{{ route('activity_logs.index') }}" class="sidebar-link">
            <i class='bx bx-list-ul'></i>
            <span>Logs d'activité</span>
          </a>
          @endif
      </nav>

      <div class="sidebar-bottom">
        <div class="user-avatar">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="user-info">
          <div class="user-name">{{ Auth::user()->name }}</div>
          <div class="user-role">{{ Auth::user()->role }}</div>
        </div>
      </div>
    </aside>

    <!-- SIDEBAR SECONDAIRE (OFFCANVAS) -->
    <div class="offcanvas offcanvas-start drawer-sub" id="secondarySidebar" tabindex="-1">
      <div class="offcanvas-header">
        <h5 class="font-bold text-lg flex items-center gap-2">
          <i class='bx bx-map'></i>
          Annuaire
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <a href="{{ route('annuaire.index') }}" class="drawer-link">
          <i class='bx bx-book-reader'></i>
          Liste
        </a>
        <a href="/structures/map" class="drawer-link">
          <i class='bx bx-map-alt'></i>
          Carte interactive
        </a>
      </div>
    </div>
    @endauth

    <!-- MAIN CONTENT RESPONSIVE -->
    <main class="main-content">
      <div class="container-fluid-responsive">
        @yield('content')
      </div>
    </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      @auth
      // ===== SIDEBAR TOGGLE RESPONSIVE =====
      const sidebar = document.getElementById('sidebar');
      const toggleBtn = document.getElementById('sidebarToggle');
      
      if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          sidebar.classList.toggle('collapsed');
          
          // Sauvegarde préférence utilisateur
          localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });

        // Restaurer état sidebar
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
          sidebar.classList.add('collapsed');
        }
      }

      // ===== OFFCANVAS SECONDAIRE =====
      const openSecondarySidebar = document.getElementById('openSecondarySidebar');
      if (openSecondarySidebar) {
        openSecondarySidebar.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          
          // Utilisation de Bootstrap Offcanvas
          const secondaryDrawer = document.getElementById('secondarySidebar');
          if (secondaryDrawer) {
            const offcanvas = new bootstrap.Offcanvas(secondaryDrawer);
            offcanvas.show();
          }
        });
      }

      // Fermer offcanvas au clic sur un lien
      document.querySelectorAll('.drawer-link').forEach(link => {
        link.addEventListener('click', () => {
          const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('secondarySidebar'));
          if (offcanvas) offcanvas.hide();
        });
      });
      @endauth

      // ===== PROFIL DROPDOWN RESPONSIVE =====
      const profileBtn = document.getElementById('profileBtn');
      const profileMenu = document.getElementById('profileMenu');

      if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          profileMenu.classList.toggle('show');
        });

        // Fermer au clic extérieur
        document.addEventListener('click', function(e) {
          if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.classList.remove('show');
          }
        });

        // Fermer à l'échappement
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape') {
            profileMenu.classList.remove('show');
          }
        });
      }

      // ===== RESPONSIVE: Fermer sidebar sur mobile quand lien cliqué =====
      if (window.innerWidth < 1024) {
        document.querySelectorAll('.sidebar-link').forEach(link => {
          link.addEventListener('click', function() {
            if (sidebar && !sidebar.classList.contains('collapsed')) {
              sidebar.classList.add('collapsed');
            }
          });
        });
      }
    });

    // ===== RESIZE HANDLER =====
    let resizeTimer;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {
        // Logique responsive supplémentaire
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth >= 1024 && sidebar) {
          // Desktop: restaurer état sauvegardé
          const savedState = localStorage.getItem('sidebarCollapsed');
          if (savedState === 'true') {
            sidebar.classList.add('collapsed');
          } else {
            sidebar.classList.remove('collapsed');
          }
        }
      }, 250);
    });
  </script>
  
  @yield('scripts')
</body>
</html>