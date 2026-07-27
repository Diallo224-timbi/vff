<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="description" content="Plateforme sécurisée qui réunit l'ensemble des acteurs engagés contre les violences faites aux femmes dans les Alpes-Maritimes, afin de coordonner leurs actions, partager leurs ressources et fluidifier les parcours de protection.">
    <title>Plateforme Multi-Acteurs VFF - Alpes-Maritimes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            overflow-x: hidden;
            overflow-y: auto;
            background: #0a0a0a;
            color: #fff;
            font-family: 'Inter', sans-serif;
        }

        @media (prefers-reduced-motion: reduce) {
            * { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
            .hero { animation: none !important; }
            .particle { animation: none !important; display: none !important; }
            .project-slide { animation: none !important; }
        }

        /* --- HEADER --- */
        .header {
            position: fixed;
            top: 16px;
            left: 16px;
            right: 16px;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            background: rgba(10, 10, 10, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 60px;
            padding: 6px 16px 6px 10px;
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
            gap: 8px;
            flex-shrink: 0;
        }

        .header-logo-box {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.06);
            padding: 4px 10px 4px 6px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .header-logo-box img {
            width: 28px;
            height: auto;
        }

        .header-logo-box .org-name {
            font-size: 9px;
            line-height: 1.2;
            color: #b0b0b0;
            font-weight: 500;
        }

        .header-logo-box .org-name strong {
            color: #fff;
            font-weight: 700;
        }

        .header-title {
            font-size: 11px;
            font-weight: 600;
            color: #4fd1d9;
            letter-spacing: 0.3px;
            padding-left: 8px;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            display: none;
        }

        .header-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
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
            padding: 5px 14px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(79, 209, 217, 0.35);
        }

        /* --- HERO --- */
        .hero {
            min-height: 100vh;
            width: 100vw;
            background-image: url("{{ asset('img/photo.png') }}");
            background-size: cover;
            background-position: center 30%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 90px 20px 180px 20px;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                    rgba(0, 0, 0, 0.92) 0%,
                    rgba(0, 0, 0, 0.75) 40%,
                    rgba(0, 0, 0, 0.50) 70%,
                    rgba(0, 0, 0, 0.25) 100%);
            z-index: 1;
        }

        /* --- PARTICLES --- */
        .particles {
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: rgba(79, 209, 217, 0.06);
            border-radius: 50%;
            animation: float 30s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg) scale(0.5); }
            100% { transform: translateY(-100vh) rotate(360deg) scale(1); }
        }

        /* --- CONTENU HERO (2 colonnes) --- */
        .hero-container {
            position: relative;
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 50px;
            width: 100%;
            max-width: 1800px;
            margin: 0 auto;
        }

        /* --- COLONNE GAUCHE : TEXTE --- */
        .hero-content {
            flex: 0 0 50%;
            max-width: 50%;
            animation: contentFade 2s ease-out;
        }

        @keyframes contentFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-content h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 42px;
            line-height: 1.08;
            color: #ffffff;
            margin-bottom: 14px;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 40px rgba(0, 0, 0, 0.6);
        }

        .hero-content h1 .highlight {
            background: linear-gradient(135deg, #4fd1d9, #7ae0e8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 15px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.92);
            max-width: 520px;
            font-weight: 400;
            text-shadow: 0 1px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle strong {
            color: #4fd1d9;
            font-weight: 600;
        }

        .hero-divider {
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #4fd1d9, transparent);
            border-radius: 2px;
            margin: 18px 0 22px 0;
        }

        /* --- STATS BADGE --- */
        .stats-badge {
            display: inline-flex;
            align-items: center;
            gap: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 40px;
            padding: 4px 6px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 12px;
        }

        .stats-badge .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }

        .stats-badge .stat-item:last-child {
            border-right: none;
        }

        .stats-badge .stat-number {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: #4fd1d9;
        }

        .stats-badge .stat-label {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 400;
        }

        /* ================================================================ */
        /* === CARTE PROJET - DESIGN OPTIMISÉ VISIBILITÉ === */
        /* ================================================================ */
        .hero-project-carousel {
            flex: 0 0 38%;
            max-width: 38%;
            animation: contentFade 1s ease-out 0.3s both;
        }

        .project-card-wrapper {
            background: rgba(15, 20, 30, 0.95);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(79, 209, 217, 0.25);
            border-radius: 24px;
            padding: 24px 22px 20px 22px;
            box-shadow: 
                0 30px 80px rgba(0, 0, 0, 0.9),
                inset 0 1px 0 rgba(79, 209, 217, 0.15),
                0 0 60px rgba(79, 209, 217, 0.03);
            max-height: 460px;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .project-card-wrapper:hover {
            border-color: rgba(79, 209, 217, 0.5);
            box-shadow: 
                0 30px 80px rgba(0, 0, 0, 0.9),
                inset 0 1px 0 rgba(79, 209, 217, 0.25),
                0 0 80px rgba(79, 209, 217, 0.08);
            transform: translateY(-2px);
        }

        /* En-tête */
        .project-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .project-card-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, rgba(79, 209, 217, 0.2), rgba(79, 209, 217, 0.06));
            border: 1px solid rgba(79, 209, 217, 0.3);
            padding: 5px 16px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
            color: #4fd1d9;
            letter-spacing: 0.3px;
            box-shadow: 0 0 30px rgba(79, 209, 217, 0.05);
        }

        .project-card-badge i {
            font-size: 11px;
        }

        .project-card-date {
            font-size: 11px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.05);
            padding: 4px 14px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .project-card-title {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.3;
            margin-bottom: 14px;
            letter-spacing: -0.2px;
            text-shadow: 0 1px 20px rgba(0, 0, 0, 0.3);
        }

        .project-card-title .highlight {
            background: linear-gradient(135deg, #4fd1d9, #7ae0e8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* === SLIDES AVEC FOND SOMBRE === */
        .project-slides-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            min-height: 190px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.04);
            padding: 6px 4px;
        }

        .project-slides-track {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            will-change: transform;
        }

        /* Chaque slide = carte avec fond */
        .project-slide {
            flex: 0 0 100%;
            min-width: 0;
            padding: 10px 10px 6px 10px;
        }

        /* Fond de chaque slide */
        .project-slide .slide-inner {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            padding: 16px 16px 14px 16px;
            transition: all 0.3s ease;
            min-height: 130px;
        }

        .project-slide .slide-inner:hover {
            background: rgba(79, 209, 217, 0.05);
            border-color: rgba(79, 209, 217, 0.15);
        }

        .project-slide .slide-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(79, 209, 217, 0.15), rgba(79, 209, 217, 0.05));
            border: 1px solid rgba(79, 209, 217, 0.15);
            margin-bottom: 8px;
        }

        .project-slide .slide-icon i {
            font-size: 16px;
            color: #4fd1d9;
        }

        .project-slide .slide-title {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 4px;
            letter-spacing: -0.1px;
        }

        .project-slide .slide-title .highlight {
            background: linear-gradient(135deg, #4fd1d9, #7ae0e8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .project-slide .slide-content {
            font-size: 12.5px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.82);
        }

        .project-slide .slide-content strong {
            color: #4fd1d9;
            font-weight: 600;
        }

        .project-slide .slide-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #4fd1d9;
            text-decoration: none;
            font-size: 11.5px;
            font-weight: 500;
            margin-top: 6px;
            border-bottom: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .project-slide .slide-link:hover {
            border-bottom-color: #4fd1d9;
            gap: 10px;
        }

        .project-slide .slide-link i {
            font-size: 10px;
            transition: transform 0.3s ease;
        }

        .project-slide .slide-link:hover i {
            transform: translateX(3px);
        }

        .project-slide .slide-footer {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.35);
        }

        .project-slide .slide-footer i {
            margin-right: 5px;
            color: #4fd1d9;
        }

        /* === NAVIGATION === */
        .project-slides-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .project-slides-nav .nav-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.4);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        .project-slides-nav .nav-btn:hover {
            background: rgba(79, 209, 217, 0.15);
            border-color: rgba(79, 209, 217, 0.3);
            color: #4fd1d9;
            transform: scale(1.08);
            box-shadow: 0 0 30px rgba(79, 209, 217, 0.1);
        }

        .project-slides-nav .nav-btn:disabled {
            opacity: 0.15;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .project-slides-nav .dots {
            display: flex;
            gap: 7px;
        }

        .project-slides-nav .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
        }

        .project-slides-nav .dot.active {
            background: #4fd1d9;
            width: 22px;
            border-radius: 4px;
            box-shadow: 0 0 30px rgba(79, 209, 217, 0.3);
        }

        .project-slides-nav .dot:hover {
            background: rgba(79, 209, 217, 0.4);
        }

        /* --- SERVICES --- */
        .services {
            position: absolute;
            bottom: 115px;
            left: 16px;
            right: 16px;
            z-index: 3;
            display: flex;
            gap: 8px;
            animation: contentFade 1s ease-out 0.3s both;
            flex-wrap: wrap;
            justify-content: center;
        }

        .service-item {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-top: 2px solid rgba(79, 209, 217, 0.4);
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: default;
            flex: 1;
            min-width: 55px;
            max-width: 120px;
            text-align: center;
        }

        .service-item:hover {
            background: rgba(79, 209, 217, 0.10);
            border-top-color: #4fd1d9;
            transform: translateY(-4px);
        }

        .service-item i {
            font-size: 16px;
            color: #4fd1d9;
            display: block;
            margin-bottom: 2px;
        }

        .service-item h3 {
            font-size: 10px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.2px;
        }

        .service-item p {
            font-size: 8px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 1px;
            display: none;
        }

        /* --- BANDEAU PARTENAIRES --- */
        .partners-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 3;
            background: rgba(255, 255, 255, 0.96);
            border-top: 2px solid #4fd1d9;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            animation: contentFade 1s ease-out 0.6s both;
            box-shadow: 0 -4px 30px rgba(0, 0, 0, 0.25);
            min-height: 62px;
            flex-wrap: wrap;
        }

        .partners-bar .label-group {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0px;
            flex-shrink: 0;
        }

        .partners-bar .label {
            font-size: 10px;
            color: #1a1a2e;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .partners-bar .label i {
            color: #4fd1d9;
            font-size: 14px;
        }

        .partners-bar .count {
            font-size: 8px;
            color: #888;
            font-weight: 400;
            padding-left: 0px;
        }

        .partners-carousel {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 6px;
            overflow: hidden;
            position: relative;
            max-width: 100%;
            min-width: 0;
        }

        .partners-carousel-wrapper {
            overflow: hidden;
            flex: 1;
            position: relative;
            padding: 2px 0;
            min-width: 0;
        }

        .partner-logos {
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            white-space: nowrap;
            will-change: transform;
        }

        .partners-bar .partner-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            transition: all 0.3s ease;
            background: #ffffff;
            padding: 6px 10px;
            border-radius: 8px;
            border: 1px solid #e8e8e8;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            flex-shrink: 0;
            min-width: 60px;
            min-height: 42px;
        }

        .partners-bar .partner-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(79, 209, 217, 0.15);
            border-color: #4fd1d9;
        }

        .partners-bar .partner-item img {
            height: 30px;
            width: auto;
            max-width: 55px;
            object-fit: contain;
        }

        .partners-bar .partner-item a {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .partners-bar .partner-item span {
            font-size: 9px;
            color: #333;
            font-weight: 500;
        }

        .nav-btn {
            background: transparent;
            border: 1px solid rgba(79, 209, 217, 0.3);
            color: #4fd1d9;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            flex-shrink: 0;
        }

        .nav-btn:hover {
            background: #4fd1d9;
            color: #0a0a0a;
            border-color: #4fd1d9;
            transform: scale(1.05);
        }

        /* --- RESPONSIVE --- */
        @media (min-width: 601px) {
            .header {
                top: 20px;
                left: 30px;
                right: 30px;
                padding: 8px 20px 8px 12px;
                border-radius: 60px;
            }
            .header-logo-box img {
                width: 36px;
            }
            .header-logo-box .org-name {
                font-size: 12px;
            }
            .header-title {
                display: block;
                font-size: 13px;
                padding-left: 12px;
            }
            .btn-outline {
                padding: 8px 20px;
                font-size: 13px;
            }
            .btn-primary {
                padding: 8px 24px;
                font-size: 13px;
            }
            .hero {
                padding-left: 40px;
                padding-right: 40px;
                padding-top: 100px;
                padding-bottom: 120px;
            }
            .hero-content h1 {
                font-size: 44px;
            }
            .hero-subtitle {
                font-size: 15px;
            }
            .hero-divider {
                width: 50px;
                margin: 18px 0 22px 0;
            }

            .service-item {
                padding: 14px 22px;
                min-width: 100px;
                max-width: none;
            }
            .service-item i {
                font-size: 20px;
            }
            .service-item h3 {
                font-size: 13px;
            }
            .service-item p {
                display: block;
                font-size: 11px;
            }
            .services {
                left: 40px;
                right: auto;
                bottom: 110px;
                gap: 16px;
                flex-wrap: nowrap;
            }

            .partners-bar {
                padding: 18px 30px;
                gap: 20px;
                min-height: 90px;
            }
            .partners-bar .label {
                font-size: 13px;
                gap: 8px;
            }
            .partners-bar .label i {
                font-size: 18px;
            }
            .partners-bar .count {
                font-size: 11px;
                padding-left: 28px;
            }
            .partners-bar .partner-item {
                padding: 10px 22px;
                min-width: 130px;
                min-height: 76px;
            }
            .partners-bar .partner-item img {
                height: 56px;
                max-width: 120px;
            }
            .partners-bar .partner-item span {
                font-size: 13px;
            }
            .nav-btn {
                width: 38px;
                height: 38px;
                font-size: 14px;
            }
            .partners-carousel {
                gap: 12px;
                max-width: 78%;
            }
            .partner-logos {
                gap: 28px;
            }
        }

        @media (min-width: 901px) {
            .header-title {
                display: block;
            }
            .hero-content h1 {
                font-size: 58px;
            }
            .hero {
                padding-left: 60px;
                padding-right: 60px;
            }
            .hero-subtitle {
                font-size: 17px;
            }
            .services {
                left: 60px;
            }

            .hero-content {
                flex: 0 0 52%;
                max-width: 52%;
            }
            .hero-project-carousel {
                flex: 0 0 35%;
                max-width: 35%;
            }
            .project-card-wrapper {
                padding: 26px 24px 22px 24px;
                max-height: 480px;
            }
            .project-card-title {
                font-size: 19px;
            }
            .project-slide .slide-content {
                font-size: 13px;
            }
            .project-slide .slide-title {
                font-size: 15px;
            }
            .project-slide .slide-inner {
                padding: 18px 18px 16px 18px;
            }
        }

        @media (max-width: 768px) {
            .hero-container {
                flex-direction: column;
                align-items: center;
                gap: 24px;
            }
            .hero-content {
                flex: 1;
                max-width: 100%;
                text-align: center;
            }
            .hero-content h1 {
                font-size: 30px;
            }
            .hero-divider {
                margin: 12px auto 16px auto;
            }
            .hero-subtitle {
                max-width: 100%;
                font-size: 13px;
            }
            .stats-badge {
                justify-content: center;
            }

            .hero-project-carousel {
                flex: 1;
                max-width: 100%;
                width: 100%;
            }
            .project-card-wrapper {
                max-height: 420px;
                padding: 18px 16px;
                max-width: 520px;
                margin: 0 auto;
            }
            .project-card-title {
                font-size: 16px;
            }
            .project-slide .slide-content {
                font-size: 12px;
            }
            .project-slide .slide-inner {
                padding: 14px 14px 12px 14px;
            }
        }

        @media (max-width: 600px) {
            .header {
                top: 10px;
                left: 10px;
                right: 10px;
                padding: 4px 10px 4px 8px;
                border-radius: 30px;
            }
            .header-logo-box {
                padding: 3px 8px 3px 5px;
            }
            .header-logo-box img {
                width: 22px;
            }
            .header-logo-box .org-name {
                font-size: 7px;
            }
            .header-logo-box .org-name br {
                display: none;
            }
            .header-buttons .btn-outline {
                display: none;
            }
            .header-buttons .btn-primary {
                padding: 3px 10px;
                font-size: 9px;
            }
            .hero {
                padding: 80px 14px 160px 14px;
            }
            .hero-content h1 {
                font-size: 26px;
            }
            .hero-subtitle {
                font-size: 12px;
            }
            .stats-badge {
                padding: 3px 4px;
                border-radius: 20px;
            }
            .stats-badge .stat-item {
                padding: 3px 8px;
            }
            .stats-badge .stat-number {
                font-size: 11px;
            }
            .stats-badge .stat-label {
                font-size: 8px;
            }

            .project-card-wrapper {
                max-height: 380px;
                padding: 14px 12px;
                border-radius: 18px;
            }
            .project-card-title {
                font-size: 14px;
            }
            .project-slide .slide-content {
                font-size: 11px;
                line-height: 1.5;
            }
            .project-slide .slide-title {
                font-size: 12.5px;
            }
            .project-slide .slide-inner {
                padding: 12px 12px 10px 12px;
                min-height: 110px;
            }
            .project-slide .slide-icon {
                width: 30px;
                height: 30px;
            }
            .project-slide .slide-icon i {
                font-size: 14px;
            }
            .project-slides-nav .nav-btn {
                width: 26px;
                height: 26px;
                font-size: 10px;
            }
            .project-slides-nav .dot {
                width: 5px;
                height: 5px;
            }
            .project-slides-nav .dot.active {
                width: 18px;
            }

            .services {
                left: 8px;
                right: 8px;
                bottom: 75px;
                gap: 4px;
            }
            .service-item {
                padding: 6px 8px;
                min-width: 40px;
                border-top-width: 2px;
                border-radius: 8px;
            }
            .service-item i {
                font-size: 12px;
            }
            .service-item h3 {
                font-size: 8px;
            }

            .partners-bar {
                padding: 8px 8px;
                min-height: 50px;
                gap: 4px;
                border-top-width: 2px;
            }
            .partners-bar .label {
                font-size: 8px;
                gap: 3px;
            }
            .partners-bar .label i {
                font-size: 11px;
            }
            .partners-bar .count {
                font-size: 7px;
            }
            .partners-carousel {
                gap: 4px;
            }
            .partners-bar .partner-item {
                min-width: 45px;
                padding: 4px 6px;
                min-height: 32px;
                border-radius: 6px;
            }
            .partners-bar .partner-item img {
                height: 24px;
                max-width: 40px;
            }
            .partners-bar .partner-item span {
                font-size: 7px;
            }
            .nav-btn {
                width: 20px;
                height: 20px;
                font-size: 8px;
                border-width: 1px;
            }
            .partner-logos {
                gap: 10px;
            }
            .partners-bar .label-group {
                flex-direction: row;
                align-items: center;
                gap: 4px;
            }
        }

        @media (max-width: 400px) {
            .hero-content h1 {
                font-size: 22px;
            }
            .hero-subtitle {
                font-size: 11px;
            }
            .project-card-wrapper {
                max-height: 340px;
                padding: 10px 10px;
            }
            .project-card-title {
                font-size: 12px;
            }
            .project-slide .slide-content {
                font-size: 10px;
            }
            .project-slide .slide-title {
                font-size: 11px;
            }
            .project-slide .slide-inner {
                padding: 10px 10px 8px 10px;
                min-height: 100px;
            }
            .service-item {
                padding: 4px 6px;
                min-width: 35px;
            }
            .service-item i {
                font-size: 10px;
            }
            .service-item h3 {
                font-size: 7px;
            }
            .stats-badge .stat-item {
                padding: 2px 6px;
            }
            .stats-badge .stat-number {
                font-size: 10px;
            }
            .stats-badge .stat-label {
                font-size: 7px;
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
                Plateforme <span>·</span> Multi-Acteurs VFF 06
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

        <div class="hero-container">

            <!-- ====== COLONNE GAUCHE : TEXTE ====== -->
            <div class="hero-content">
                <h1>
                    Nos singularités <br>
                    <span class="highlight">au service du collectif.</span>
                </h1>

                <div class="hero-divider" aria-hidden="true"></div>

                <p class="hero-subtitle">
                    <strong style="color: #ffffff;">
                        Une plateforme sécurisée qui réunit l'ensemble des acteurs engagés contre les violences faites aux femmes, 
                        afin de coordonner leurs actions, partager leurs ressources et fluidifier les parcours de protection.
                    </strong>
                </p>

                <div style="margin-top: 20px;">
                    <div class="stats-badge">
                        <div class="stat-item">
                            <span class="stat-number">{{ $organismes->count() }}</span>
                            <span class="stat-label">Organismes</span>
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

            <!-- ====== COLONNE DROITE : CARTE PROJET ====== -->
            <div class="hero-project-carousel">
                <div class="project-card-wrapper">
                    <!-- En-tête -->
                    <div class="project-card-header">
                        <span class="project-card-badge">
                            <i class="fas fa-flag-checkered" aria-hidden="true"></i>
                            Projet départemental
                        </span>
                        <span class="project-card-date">2024 ‑ 2027</span>
                    </div>

                    <h3 class="project-card-title">
                        Schéma <span class="highlight">VFF 06</span>
                    </h3>

                    <!-- Carrousel avec fond sombre par slide -->
                    <div class="project-slides-wrapper">
                        <div class="project-slides-track" id="projectTrack">

                            <!-- SLIDE 1 -->
                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-handshake" aria-hidden="true"></i></div>
                                    <div class="slide-title">Plateforme <span class="highlight">Multi-Acteurs</span></div>
                                    <div class="slide-content">
                                        Un espace numérique sécurisé pour renforcer la coopération entre institutions, collectivités, associations, professionnels de santé, forces de l'ordre et acteurs de la justice.
                                    </div>
                                </div>
                            </div>

                            <!-- SLIDE 2 -->
                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-gavel" aria-hidden="true"></i></div>
                                    <div class="slide-title">Origine du <span class="highlight">projet</span></div>
                                    <div class="slide-content">
                                        Issue du <strong>Schéma départemental 2024‑2027</strong> porté par la Préfecture, les Parquets de Nice et Grasse, les collectivités et les associations spécialisées.
                                    </div>
                                    <a href="#" class="slide-link" aria-label="Consulter le Schéma départemental">
                                        Consulter le schéma <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- SLIDE 3 -->
                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-network-wired" aria-hidden="true"></i></div>
                                    <div class="slide-title">Mettre en <span class="highlight">réseau</span></div>
                                    <div class="slide-content">
                                        <strong>Annuaire départemental</strong> et <strong>cartographie interactive</strong> pour identifier rapidement les partenaires et dispositifs du territoire.
                                    </div>
                                </div>
                            </div>

                            <!-- SLIDE 4 -->
                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-comments" aria-hidden="true"></i></div>
                                    <div class="slide-title">Favoriser les <span class="highlight">échanges</span></div>
                                    <div class="slide-content">
                                        Espace sécurisé pour partager expériences, pratiques et ressources dans le respect du <strong>RGPD</strong>.
                                    </div>
                                </div>
                            </div>

                            <!-- SLIDE 5 -->
                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-folder-open" aria-hidden="true"></i></div>
                                    <div class="slide-title">Centraliser les <span class="highlight">ressources</span></div>
                                    <div class="slide-content">
                                        <strong>Documents, procédures, fiches réflexes</strong> et outils métiers pour l'accompagnement des victimes.
                                    </div>
                                </div>
                            </div>

                            <!-- SLIDE 6 -->
                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-star" aria-hidden="true"></i></div>
                                    <div class="slide-title">Valoriser les <span class="highlight">initiatives</span></div>
                                    <div class="slide-content">
                                        Mettre en avant les <strong>projets innovants</strong> et groupes de travail développés dans les Alpes‑Maritimes.
                                    </div>
                                </div>
                            </div>

                            <!-- SLIDE 7 -->
                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-bullseye" aria-hidden="true"></i></div>
                                    <div class="slide-title">Notre <span class="highlight">ambition</span></div>
                                    <div class="slide-content">
                                        Construire un réseau plus <strong>lisible, accessible et efficace</strong> pour améliorer la prévention, la protection et l'accompagnement.
                                    </div>
                                    <div class="slide-footer">
                                        <i class="fas fa-shield-alt" aria-hidden="true"></i> RGPD · Aucune donnée individuelle
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="project-slides-nav">
                        <button class="nav-btn" id="projectPrev" aria-label="Slide précédent">
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                        </button>
                        <div class="dots" id="projectDots"></div>
                        <button class="nav-btn" id="projectNext" aria-label="Slide suivant">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </button>
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
        // ================================================================
        // CARROUSEL PROJET
        // ================================================================
        (function() {
            const track = document.getElementById('projectTrack');
            const slides = track.querySelectorAll('.project-slide');
            const totalSlides = slides.length;
            let currentSlide = 0;
            let autoSlideInterval = null;
            let isPaused = false;

            function moveToSlide(index) {
                const maxIndex = totalSlides - 1;
                currentSlide = Math.max(0, Math.min(index, maxIndex));

                const slideWidth = slides[0].offsetWidth;
                const offset = currentSlide * slideWidth;
                track.style.transform = `translateX(-${offset}px)`;

                const dots = document.querySelectorAll('#projectDots .dot');
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentSlide);
                });

                document.getElementById('projectPrev').disabled = currentSlide === 0;
                document.getElementById('projectNext').disabled = currentSlide >= maxIndex;
            }

            function nextSlide() {
                if (currentSlide < totalSlides - 1) {
                    moveToSlide(currentSlide + 1);
                } else {
                    moveToSlide(0);
                }
            }

            function prevSlide() {
                if (currentSlide > 0) {
                    moveToSlide(currentSlide - 1);
                } else {
                    moveToSlide(totalSlides - 1);
                }
            }

            function createDots() {
                const dotsContainer = document.getElementById('projectDots');
                dotsContainer.innerHTML = '';
                for (let i = 0; i < totalSlides; i++) {
                    const dot = document.createElement('button');
                    dot.className = 'dot' + (i === 0 ? ' active' : '');
                    dot.setAttribute('data-index', i);
                    dot.setAttribute('aria-label', 'Slide ' + (i + 1));
                    dot.addEventListener('click', function() {
                        moveToSlide(parseInt(this.dataset.index));
                        resetAutoSlide();
                    });
                    dotsContainer.appendChild(dot);
                }
            }

            function startAutoSlide() {
                if (autoSlideInterval) clearInterval(autoSlideInterval);
                if (isPaused) return;
                autoSlideInterval = setInterval(nextSlide, 4500);
            }

            function stopAutoSlide() {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                    autoSlideInterval = null;
                }
            }

            function resetAutoSlide() {
                stopAutoSlide();
                startAutoSlide();
            }

            document.getElementById('projectPrev').addEventListener('click', function() {
                prevSlide();
                resetAutoSlide();
            });

            document.getElementById('projectNext').addEventListener('click', function() {
                nextSlide();
                resetAutoSlide();
            });

            const wrapper = document.querySelector('.project-card-wrapper');
            wrapper.addEventListener('mouseenter', function() {
                isPaused = true;
                stopAutoSlide();
            });
            wrapper.addEventListener('mouseleave', function() {
                isPaused = false;
                startAutoSlide();
            });

            setTimeout(function() {
                createDots();
                moveToSlide(0);
                setTimeout(startAutoSlide, 2000);
            }, 200);

            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    moveToSlide(currentSlide);
                }, 300);
            });
        })();


        // ================================================================
        // CARROUSEL PARTENAIRES
        // ================================================================
        let currentIndex = 0;
        let itemsPerView = 4;
        let totalItems = 0;
        let autoSlideIntervalPartner = null;
        let isPausedPartner = false;

        function updateItemsPerView() {
            const width = window.innerWidth;
            if (width < 400) itemsPerView = 2;
            else if (width < 600) itemsPerView = 2;
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

            const itemWidth = logos[0].offsetWidth + 16;
            const offset = currentIndex * itemWidth;
            
            const logosContainer = document.getElementById('partnerLogos');
            logosContainer.style.transform = `translateX(-${offset}px)`;

            const prevBtn = document.querySelector('.nav-btn.prev');
            const nextBtn = document.querySelector('.nav-btn.next');
            if (prevBtn) prevBtn.style.opacity = currentIndex === 0 ? '0.3' : '1';
            if (nextBtn) nextBtn.style.opacity = currentIndex >= maxIndex ? '0.3' : '1';
        }

        let resizeTimeoutPartner;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeoutPartner);
            resizeTimeoutPartner = setTimeout(() => {
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

        function startAutoSlidePartner() {
            if (autoSlideIntervalPartner) clearInterval(autoSlideIntervalPartner);
            if (isPausedPartner) return;
            
            autoSlideIntervalPartner = setInterval(() => {
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

        function stopAutoSlidePartner() {
            if (autoSlideIntervalPartner) {
                clearInterval(autoSlideIntervalPartner);
                autoSlideIntervalPartner = null;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.querySelector('.partners-carousel');
            if (carousel) {
                carousel.addEventListener('mouseenter', function() {
                    isPausedPartner = true;
                    stopAutoSlidePartner();
                });
                carousel.addEventListener('mouseleave', function() {
                    isPausedPartner = false;
                    startAutoSlidePartner();
                });
            }
            
            setTimeout(() => {
                updateItemsPerView();
                moveCarousel(0);
                setTimeout(startAutoSlidePartner, 2000);
            }, 100);
        });


        // ================================================================
        // PARTICULES
        // ================================================================
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