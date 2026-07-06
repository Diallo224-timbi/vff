<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme Multi-Acteurs VFF - Alpes-Maritimes</title>
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        /* --- RESET & BASE --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
            background: #0a0a0a;
            color: #fff;
            font-family: 'Inter', sans-serif;
        }

        /* --- PREFERS REDUCED MOTION --- */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            .hero {
                animation: none !important;
            }
            .particle {
                animation: none !important;
                display: none !important;
            }
            .partner-logos {
                transition: none !important;
            }
        }

        /* --- HEADER --- */
        .header {
            position: fixed;
            top: 20px;
            left: 30px;
            right: 30px;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            background: rgba(10, 10, 10, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 60px;
            padding: 8px 20px 8px 12px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.6);
            animation: headerFade 0.8s ease-out;
        }

        @keyframes headerFade {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-logo-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.06);
            padding: 6px 14px 6px 10px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .header-logo-box img {
            width: 36px;
            height: auto;
        }

        .header-logo-box .org-name {
            font-size: 12px;
            line-height: 1.2;
            color: #b0b0b0;
            font-weight: 500;
        }

        .header-logo-box .org-name strong {
            color: #fff;
            font-weight: 700;
        }

        .header-title {
            font-size: 13px;
            font-weight: 600;
            color: #4fd1d9;
            letter-spacing: 0.5px;
            padding-left: 4px;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            padding-left: 12px;
        }

        .header-title span {
            font-weight: 300;
            color: #888;
        }

        .header-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
            font-family: 'Inter', sans-serif;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4fd1d9, #3bbac1);
            color: #0a0a0a;
            border: none;
            padding: 8px 24px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(79, 209, 217, 0.35);
        }

        /* --- HERO --- */
        .hero {
            height: 100vh;
            width: 100vw;
            background-image: url("{{ asset('img/photo.png') }}");
            background-size: cover;
            background-position: center 30%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 80px;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                    rgba(0, 0, 0, 0.88) 0%,
                    rgba(0, 0, 0, 0.70) 40%,
                    rgba(0, 0, 0, 0.50) 70%,
                    rgba(0, 0, 0, 0.30) 100%);
            z-index: 1;
        }

        /* --- PARTICLES (discrètes) --- */
        .particles {
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: rgba(79, 209, 217, 0.10);
            border-radius: 50%;
            animation: float 30s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg) scale(0.5); }
            100% { transform: translateY(-100vh) rotate(360deg) scale(1); }
        }

        /* --- CONTENU HERO --- */
        .hero-content {
            position: relative;
            z-index: 3;
            max-width: 620px;
            animation: contentFade 1s ease-out;
        }

        @keyframes contentFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(79, 209, 217, 0.12);
            border: 1px solid rgba(79, 209, 217, 0.20);
            padding: 5px 16px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 500;
            color: #4fd1d9;
            letter-spacing: 0.3px;
            margin-bottom: 24px;
        }

        .hero-badge i {
            font-size: 12px;
        }

        .hero h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 72px;
            line-height: 1.05;
            color: #fff;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .hero h1 .highlight {
            background: linear-gradient(135deg, #4fd1d9, #7ae0e8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 18px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.85);
            max-width: 500px;
            font-weight: 400;
        }

        .hero-subtitle strong {
            color: #4fd1d9;
            font-weight: 600;
        }

        .hero-divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #4fd1d9, transparent);
            border-radius: 2px;
            margin: 24px 0 28px 0;
        }

        /* --- STATS BADGE --- */
        .stats-badge {
            display: inline-flex;
            align-items: center;
            gap: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 40px;
            padding: 6px 8px;
        }

        .stats-badge .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }

        .stats-badge .stat-item:last-child {
            border-right: none;
        }

        .stats-badge .stat-number {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 18px;
            color: #4fd1d9;
        }

        .stats-badge .stat-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 400;
        }

        /* --- SERVICES --- */
        .services {
            position: absolute;
            bottom: 130px;
            left: 80px;
            z-index: 3;
            display: flex;
            gap: 16px;
            animation: contentFade 1s ease-out 0.3s both;
        }

        .service-item {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-top: 2px solid rgba(79, 209, 217, 0.4);
            padding: 14px 22px;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: default;
            min-width: 100px;
            text-align: center;
        }

        .service-item:hover {
            background: rgba(79, 209, 217, 0.10);
            border-top-color: #4fd1d9;
            transform: translateY(-4px);
        }

        .service-item i {
            font-size: 20px;
            color: #4fd1d9;
            display: block;
            margin-bottom: 4px;
        }

        .service-item h3 {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.3px;
        }

        .service-item p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 2px;
        }

        /* --- BANDEAU PARTENAIRES --- */
        .partners-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 3;
            background: rgba(255, 255, 255, 0.96);
            border-top: 3px solid #4fd1d9;
            padding: 18px 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            animation: contentFade 1s ease-out 0.6s both;
            box-shadow: 0 -4px 30px rgba(0, 0, 0, 0.25);
            min-height: 90px;
        }

        .partners-bar .label-group {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 2px;
            flex-shrink: 0;
        }

        .partners-bar .label {
            font-size: 13px;
            color: #1a1a2e;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .partners-bar .label i {
            color: #4fd1d9;
            font-size: 18px;
        }

        .partners-bar .count {
            font-size: 11px;
            color: #888;
            font-weight: 400;
            padding-left: 28px;
        }

        .partners-carousel {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            overflow: hidden;
            position: relative;
            max-width: 78%;
        }

        .partners-carousel-wrapper {
            overflow: hidden;
            flex: 1;
            position: relative;
            padding: 4px 0;
        }

        .partner-logos {
            display: flex;
            align-items: center;
            gap: 28px;
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            white-space: nowrap;
        }

        .partners-bar .partner-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            background: #ffffff;
            padding: 10px 22px;
            border-radius: 10px;
            border: 1px solid #e8e8e8;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            flex-shrink: 0;
            min-width: 130px;
            min-height: 76px;
        }

        .partners-bar .partner-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(79, 209, 217, 0.15);
            border-color: #4fd1d9;
        }

        .partners-bar .partner-item img {
            height: 56px;
            width: auto;
            max-width: 120px;
            object-fit: contain;
        }

        .partners-bar .partner-item a {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .partners-bar .partner-item span {
            font-size: 13px;
            color: #333;
            font-weight: 500;
        }

        /* Boutons de navigation */
        .nav-btn {
            background: transparent;
            border: 1.5px solid rgba(79, 209, 217, 0.4);
            color: #4fd1d9;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .nav-btn:hover {
            background: #4fd1d9;
            color: #0a0a0a;
            border-color: #4fd1d9;
            transform: scale(1.05);
        }

        .nav-btn:focus-visible {
            outline: 2px solid #4fd1d9;
            outline-offset: 2px;
        }

        .nav-btn.prev {
            margin-right: 2px;
        }

        .nav-btn.next {
            margin-left: 2px;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 1100px) {
            .hero {
                padding-left: 50px;
            }
            .hero h1 {
                font-size: 56px;
            }
            .services {
                left: 50px;
                bottom: 110px;
            }
            .partners-bar .partner-item {
                min-width: 110px;
                padding: 8px 16px;
                min-height: 64px;
            }
            .partners-bar .partner-item img {
                height: 44px;
                max-width: 90px;
            }
        }

        @media (max-width: 900px) {
            .header {
                top: 12px;
                left: 16px;
                right: 16px;
                padding: 6px 14px 6px 10px;
                border-radius: 40px;
            }
            .header-title {
                display: none;
            }
            .header-logo-box .org-name {
                font-size: 10px;
            }
            .header-logo-box img {
                width: 30px;
            }
            .hero {
                padding-left: 30px;
                padding-right: 30px;
            }
            .hero h1 {
                font-size: 44px;
            }
            .hero-subtitle {
                font-size: 15px;
            }
            .hero-divider {
                width: 40px;
                margin: 16px 0 20px 0;
            }
            .services {
                left: 30px;
                right: 30px;
                bottom: 95px;
                gap: 10px;
                flex-wrap: wrap;
                justify-content: center;
            }
            .service-item {
                padding: 10px 16px;
                min-width: 70px;
                flex: 1;
            }
            .service-item i {
                font-size: 16px;
            }
            .service-item h3 {
                font-size: 11px;
            }
            .service-item p {
                display: none;
            }
            .stats-badge .stat-item {
                padding: 6px 14px;
            }
            .stats-badge .stat-number {
                font-size: 15px;
            }
            .stats-badge .stat-label {
                font-size: 10px;
            }
            .partners-bar {
                padding: 12px 16px;
                gap: 12px;
                min-height: 72px;
                flex-wrap: wrap;
            }
            .partners-bar .label-group {
                flex-direction: row;
                align-items: center;
                gap: 8px;
            }
            .partners-bar .count {
                padding-left: 0;
                font-size: 10px;
            }
            .partners-bar .label {
                font-size: 11px;
            }
            .partners-carousel {
                max-width: 100%;
                gap: 8px;
            }
            .partners-bar .partner-item {
                min-width: 80px;
                padding: 6px 12px;
                min-height: 54px;
            }
            .partners-bar .partner-item img {
                height: 36px;
                max-width: 70px;
            }
            .nav-btn {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }
            .btn-outline,
            .btn-primary {
                padding: 6px 14px;
                font-size: 11px;
            }
        }

        @media (max-width: 600px) {
            .header {
                top: 10px;
                left: 10px;
                right: 10px;
                padding: 4px 10px 4px 8px;
                border-radius: 30px;
                flex-wrap: nowrap;
            }
            .header-logo-box {
                padding: 4px 10px 4px 6px;
                gap: 6px;
            }
            .header-logo-box img {
                width: 24px;
            }
            .header-logo-box .org-name {
                font-size: 8px;
            }
            .header-logo-box .org-name br {
                display: none;
            }
            .header-buttons .btn-outline {
                display: none;
            }
            .header-buttons .btn-primary {
                padding: 2px 10px;
                font-size: 10px;
            }
            .hero {
                padding-left: 16px;
                padding-right: 16px;
                align-items: center;
                text-align: center;
            }
            .hero h1 {
                font-size: 36px;
            }
            .hero-subtitle {
                font-size: 13px;
                max-width: 100%;
            }
            .hero-subtitle br {
                display: none;
            }
            .hero-badge {
                font-size: 10px;
                padding: 4px 12px;
                margin-bottom: 16px;
            }
            .hero-divider {
                margin: 14px auto 18px auto;
            }
            .stats-badge {
                flex-wrap: wrap;
                justify-content: center;
                border-radius: 20px;
                padding: 4px 6px;
                gap: 0;
            }
            .stats-badge .stat-item {
                padding: 4px 12px;
                border-right: 1px solid rgba(255, 255, 255, 0.06);
            }
            .stats-badge .stat-item:last-child {
                border-right: none;
            }
            .stats-badge .stat-number {
                font-size: 13px;
            }
            .stats-badge .stat-label {
                font-size: 9px;
            }
            .services {
                left: 10px;
                right: 10px;
                bottom: 78px;
                gap: 6px;
            }
            .service-item {
                padding: 8px 10px;
                min-width: 50px;
                border-top-width: 2px;
            }
            .service-item i {
                font-size: 14px;
            }
            .service-item h3 {
                font-size: 9px;
            }
            .partners-bar {
                padding: 10px 10px;
                min-height: 58px;
                gap: 6px;
                border-top-width: 2px;
            }
            .partners-bar .label {
                font-size: 9px;
                gap: 4px;
            }
            .partners-bar .label i {
                font-size: 14px;
            }
            .partners-bar .count {
                font-size: 8px;
            }
            .partners-carousel {
                gap: 4px;
            }
            .partners-bar .partner-item {
                min-width: 55px;
                padding: 4px 8px;
                min-height: 38px;
            }
            .partners-bar .partner-item img {
                height: 28px;
                max-width: 50px;
            }
            .partners-bar .partner-item span {
                font-size: 8px;
            }
            .nav-btn {
                width: 22px;
                height: 22px;
                font-size: 10px;
                border-width: 1px;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="header" role="banner">
        <div class="header-left">
            <div class="header-logo-box">
                <img src="{{ asset('img/logo_prefet.png') }}" alt="Logo Préfecture des Alpes-Maritimes">
                <div class="org-name">
                    PRÉFECTURE<br>
                    <strong>ALPES-MARITIMES</strong>
                </div>
            </div>
            <div class="header-title">
                Plateforme <span>·</span> Multi-Acteurs
            </div>
        </div>
        <div class="header-buttons">
            <a href="/register" class="btn-outline" aria-label="Créer un compte">S'inscrire</a>
            <a href="/login" class="btn-primary" aria-label="Se connecter à la plateforme">Se connecter</a>
        </div>
    </header>

    <!-- HERO -->
    <main class="hero" role="main">
        <div class="particles" id="particles" aria-hidden="true"></div>

        <div class="hero-content">
            <h1>
                Nos singularités <br>
                <span class="highlight">au service du collectif.</span>
            </h1>

            <div class="hero-divider" aria-hidden="true"></div>

         <p class="hero-subtitle">
            <strong style="color: rgb(255, 255, 255);">
                Une plateforme sécurisée qui réunit l’ensemble des acteurs engagés contre les violences faites aux femmes, 
                afin de coordonner leurs actions, partager leurs ressources et fluidifier les parcours de protection.
            </strong>
          </p>


            <div style="margin-top: 28px;">
                <div class="stats-badge">
                    <div class="stat-item">
                        <span class="stat-number">{{ $organismes->count() }}</span>
                        <span class="stat-label">Organisme</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $structures->count() }}</span>
                        <span class="stat-label">Structures</span>
                    </div>
                     <div class="stat-item">
                        <span class="stat-number">{{ $user->count() }}</span>
                        <span class="stat-label">Membres</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Sécurisé</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SERVICES -->
        <div class="services" role="list">
            <div class="service-item" role="listitem">
                <i class="fas fa-address-book" aria-hidden="true"></i>
                <h3>Annuaire</h3>
                <p>Acteurs &amp; cartographie</p>
            </div>
            <div class="service-item" role="listitem">
                <i class="fas fa-folder-open" aria-hidden="true"></i>
                <h3>Documents</h3>
                <p>Ressources partagées</p>
            </div>
            <div class="service-item" role="listitem">
                <i class="fas fa-comments" aria-hidden="true"></i>
                <h3>Forum</h3>
                <p>Échanges professionnels</p>
            </div>
            <div class="service-item" role="listitem">
                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                <h3>Agenda</h3>
                <p>Événements &amp; réunions</p>
            </div>
        </div>

        <!-- BANDEAU PARTENAIRES -->
        <div class="partners-bar" role="complementary" aria-label="Partenaires signataires">
            <div class="label-group">
                <span class="label">
                    <i class="fas fa-handshake" aria-hidden="true"></i> Partenaires
                </span>
                <span class="count">{{ $organismes->count() }} organismes signataires</span>
            </div>

            <div class="partners-carousel" role="region" aria-label="Carrousel des logos partenaires">
                <button class="nav-btn prev" onclick="moveCarousel(-1)" aria-label="Voir les partenaires précédents">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                </button>

                <div class="partners-carousel-wrapper">
                    <div class="partner-logos" id="partnerLogos" role="list">
                        @foreach($organismes as $organisme)
                            <div class="partner-item" role="listitem" title="{{ $organisme->nom }}">
                                @if($organisme->logo_path)
                                    <a href="{{ $organisme->site_web ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $organisme->nom }} (site web)">
                                        <img src="{{ asset('storage/' . $organisme->logo_path) }}" alt="Logo {{ $organisme->nom }}">
                                    </a>
                                @else
                                    <span>{{ $organisme->nom }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <button class="nav-btn next" onclick="moveCarousel(1)" aria-label="Voir les partenaires suivants">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </main>

    <script>
        // --- CARROUSEL ---
        let currentIndex = 0;
        let itemsPerView = 4;
        let totalItems = 0;
        let autoSlideInterval = null;
        let isPaused = false;

        function updateItemsPerView() {
            const width = window.innerWidth;
            if (width < 600) itemsPerView = 2;
            else if (width < 900) itemsPerView = 3;
            else itemsPerView = 4;
        }

        function moveCarousel(direction) {
            const logos = document.querySelectorAll('.partner-item');
            if (!logos.length) return;

            totalItems = logos.length;
            updateItemsPerView();

            const maxIndex = Math.max(0, totalItems - itemsPerView);
            currentIndex += direction;

            if (currentIndex < 0) currentIndex = maxIndex;
            else if (currentIndex > maxIndex) currentIndex = 0;

            const itemWidth = logos[0].offsetWidth + 28;
            const offset = currentIndex * itemWidth;
            
            const logosContainer = document.getElementById('partnerLogos');
            logosContainer.style.transform = `translateX(-${offset}px)`;

            const prevBtn = document.querySelector('.nav-btn.prev');
            const nextBtn = document.querySelector('.nav-btn.next');
            if (prevBtn) prevBtn.style.opacity = currentIndex === 0 ? '0.3' : '1';
            if (nextBtn) nextBtn.style.opacity = currentIndex >= maxIndex ? '0.3' : '1';
        }

        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                updateItemsPerView();
                currentIndex = 0;
                const logos = document.querySelectorAll('.partner-item');
                if (logos.length) {
                    document.getElementById('partnerLogos').style.transform = 'translateX(0)';
                }
                const prevBtn = document.querySelector('.nav-btn.prev');
                if (prevBtn) prevBtn.style.opacity = '0.3';
                const nextBtn = document.querySelector('.nav-btn.next');
                if (nextBtn) nextBtn.style.opacity = '1';
            }, 300);
        });
        function startAutoSlide() {
            if (autoSlideInterval) clearInterval(autoSlideInterval);
            if (isPaused) return;
            
            autoSlideInterval = setInterval(() => {
                const logos = document.querySelectorAll('.partner-item');
                const total = logos.length;
                const maxIndex = Math.max(0, total - itemsPerView);
                
                if (currentIndex < maxIndex) {
                    moveCarousel(1);
                } else {
                    currentIndex = -1;
                    moveCarousel(1);
                }
            }, 5000);
        }
        function stopAutoSlide() {
            if (autoSlideInterval) {
                clearInterval(autoSlideInterval);
                autoSlideInterval = null;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.querySelector('.partners-carousel');
            if (carousel) {
                carousel.addEventListener('mouseenter', function() {
                    isPaused = true;
                    stopAutoSlide();
                });
                carousel.addEventListener('mouseleave', function() {
                    isPaused = false;
                    startAutoSlide();
                });
                carousel.addEventListener('focusin', function() {
                    isPaused = true;
                    stopAutoSlide();
                });
                carousel.addEventListener('focusout', function() {
                    isPaused = false;
                    startAutoSlide();
                });
            }
            
            setTimeout(() => {
                updateItemsPerView();
                moveCarousel(0);
                // Démarrer après un délai
                setTimeout(startAutoSlide, 2000);
            }, 100);
        });

        // --- PARTICULES (discrètes) ---
        function createParticles() {
            const container = document.getElementById('particles');
            if (!container) return;
            
            const particleCount = 30;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                const size = Math.random() * 5 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 30 + 's';
                particle.style.opacity = Math.random() * 0.15 + 0.05;
                container.appendChild(particle);
            }
        }

        window.addEventListener('load', function() {
            createParticles();
        });
    </script>

</body>
</html>