<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Plateforme sécurisée qui réunit l'ensemble des acteurs engagés contre les violences faites aux femmes dans les Alpes-Maritimes, afin de coordonner leurs actions, partager leurs ressources et fluidifier les parcours de protection.">
    <title>Plateforme Multi-Acteurs Violence faites aux femmes - Alpes-Maritimes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* --- RESET & BASE --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            min-height: 100vh;
            background: #0a0a0a;
            color: #fff;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        @media (prefers-reduced-motion: reduce) {
            * { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
            .hero, .particle, .project-slide { animation: none !important; }
            .particle { display: none !important; }
        }

        /* ==================================================================
           HEADER — fixe mais plus compact
           ================================================================== */
        .header {
            position: fixed;
            top: 12px;
            left: 12px;
            right: 12px;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            background: rgba(10, 10, 10, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
            gap: 10px;
            flex-shrink: 0;
        }

        .header-logo-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.06);
            padding: 4px 12px 4px 6px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .header-logo-box img {
            width: 26px;
            height: auto;
            object-fit: contain;
        }

        .header-logo-box .org-name {
            font-size: 9.5px;
            line-height: 1.2;
            color: #b0b0b0;
            font-weight: 500;
        }

        .header-logo-box .org-name strong {
            color: #fff;
            font-weight: 700;
        }

        .header-title {
            font-size: 12px;
            font-weight: 600;
            color: #4fd1d9;
            letter-spacing: 0.3px;
            padding-left: 10px;
            border-left: 1px solid rgba(255, 255, 255, 0.15);
            display: none;
        }

        .header-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
            white-space: nowrap;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4fd1d9, #3bbac1);
            color: #0a0a0a;
            border: none;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
            white-space: nowrap;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(79, 209, 217, 0.4);
        }

        /* ==================================================================
           HERO — ajusté pour tenir sur 1920x1080 @125%
           La hauteur utile réelle ≈ 864px après scaling.
           On laisse de la place pour le header (~60px) et les bandeaux du bas.
           ================================================================== */
        .hero {
            min-height: 100vh;
            width: 100%;
            background-image: url("{{ asset('img/photo.png') }}");
            background-size: cover;
            background-position: center 30%;
            position: relative;
            display: flex;
            flex-direction: column;
            padding-top: 88px;
            padding-bottom: 12px;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                    rgba(10, 10, 10, 0.95) 0%,
                    rgba(10, 10, 10, 0.82) 40%,
                    rgba(10, 10, 10, 0.65) 70%,
                    rgba(10, 10, 10, 0.40) 100%);
            z-index: 1;
        }

        .particles {
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            background: rgba(79, 209, 217, 0.08);
            border-radius: 50%;
            animation: float 30s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg) scale(0.5); }
            100% { transform: translateY(-100vh) rotate(360deg) scale(1); }
        }

        /* ==================================================================
           BANDEAU TEXTE — plus compact
           ================================================================== */
        .hero-banner {
            position: relative;
            z-index: 3;
            width: 100%;
            padding: 18px 32px 16px 32px;
            text-align: center;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 2px solid rgba(79, 209, 217, 0.3);
            animation: bannerSlide 0.8s ease-out;
            flex-shrink: 0;
        }

        @keyframes bannerSlide {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-banner h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 900;
            font-size: clamp(28px, 3.2vw, 48px);
            line-height: 1.1;
            color: #ffffff;
            letter-spacing: -0.5px;
            text-shadow: 0 4px 60px rgba(0, 0, 0, 0.9);
            margin-bottom: 4px;
        }

        .hero-banner h1 .highlight {
            background: linear-gradient(135deg, #4fd1d9, #7ae0e8, #a8f0f5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
        }

        .hero-banner .subtitle {
            font-size: clamp(12px, 1.1vw, 17px);
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.5px;
            text-shadow: 0 2px 30px rgba(0, 0, 0, 0.6);
        }

        .hero-banner .subtitle strong {
            color: #4fd1d9;
            font-weight: 600;
        }

        .hero-banner .banner-divider {
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #4fd1d9, transparent);
            border-radius: 2px;
            margin: 8px auto 8px auto;
        }

        /* ==================================================================
           CONTENEUR PRINCIPAL — gap réduit, padding optimisé
           ================================================================== */
        .hero-container {
            position: relative;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 16px 32px 12px 32px;
            flex: 1 1 auto;
            min-height: 0;
        }

        /* --- COLONNE GAUCHE --- */
        .hero-content {
            flex: 1 1 50%;
            max-width: 720px;
            animation: contentFade 1.2s ease-out 0.3s both;
        }

        @keyframes contentFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-content .description {
            font-size: clamp(13px, 1.05vw, 16px);
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.92);
            font-weight: 400;
            text-shadow: 0 1px 20px rgba(0, 0, 0, 0.4);
            margin-bottom: 16px;
        }

        .hero-content .description strong {
            color: #ffffff;
            font-weight: 500;
        }

        .hero-content .description .accent {
            color: #4fd1d9;
            font-weight: 600;
        }

        /* --- STATS BADGE --- */
        .stats-badge {
            display: inline-flex;
            align-items: center;
            gap: 0;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 40px;
            padding: 4px;
            flex-wrap: wrap;
        }

        .stats-badge .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 14px;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        .stats-badge .stat-item:last-child {
            border-right: none;
        }

        .stats-badge .stat-number {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: #4fd1d9;
        }

        .stats-badge .stat-label {
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
        }

        /* --- CARTE PROJET — réduite pour tenir dans la hauteur --- */
        .hero-project-carousel {
            flex: 0 0 400px;
            max-width: 100%;
            animation: contentFade 1s ease-out 0.5s both;
        }

        .project-card-wrapper {
            background: rgba(15, 20, 30, 0.95);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(79, 209, 217, 0.3);
            border-radius: 20px;
            padding: 16px 16px 12px 16px;
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.9),
                inset 0 1px 0 rgba(79, 209, 217, 0.2);
            transition: all 0.3s ease;
        }

        .project-card-wrapper:hover {
            border-color: rgba(79, 209, 217, 0.55);
            box-shadow: 0 35px 90px rgba(0, 0, 0, 0.95), inset 0 1px 0 rgba(79, 209, 217, 0.3);
        }

        .project-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 8px;
        }

        .project-card-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, rgba(79, 209, 217, 0.2), rgba(79, 209, 217, 0.06));
            border: 1px solid rgba(79, 209, 217, 0.35);
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 10.5px;
            font-weight: 600;
            color: #4fd1d9;
        }

        .project-card-date {
            font-size: 10.5px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.06);
            padding: 3px 10px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .project-card-title {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.3;
            margin-bottom: 10px;
        }

        .project-card-title .highlight {
            background: linear-gradient(135deg, #4fd1d9, #7ae0e8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .project-slides-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 14px;
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.06);
            padding: 3px;
        }

        .project-slides-track {
            display: flex;
            transition: transform 0.5s cubic-bezier(0.2, 1, 0.3, 1);
            will-change: transform;
            width: 100%;
        }

        .project-slide {
            flex: 0 0 100%;
            width: 100%;
            min-width: 100%;
            padding: 3px;
        }

        .project-slide .slide-inner {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 12px;
            transition: all 0.3s ease;
            min-height: 118px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .project-slide .slide-inner:hover {
            background: rgba(79, 209, 217, 0.06);
            border-color: rgba(79, 209, 217, 0.2);
        }

        .project-slide .slide-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: rgba(79, 209, 217, 0.15);
            border: 1px solid rgba(79, 209, 217, 0.2);
            margin-bottom: 6px;
        }

        .project-slide .slide-icon i {
            font-size: 14px;
            color: #4fd1d9;
        }

        .project-slide .slide-title {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 3px;
        }

        .project-slide .slide-title .highlight {
            background: linear-gradient(135deg, #4fd1d9, #7ae0e8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .project-slide .slide-content {
            font-size: 11.5px;
            line-height: 1.55;
            color: rgba(255, 255, 255, 0.85);
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
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px;
            transition: all 0.3s ease;
        }

        .project-slide .slide-link:hover {
            gap: 10px;
        }

        .project-slide .slide-footer {
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 10px;
            color: rgba(255, 255, 255, 0.45);
        }

        .project-slides-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .project-slides-nav .nav-btn {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.6);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .project-slides-nav .nav-btn:hover {
            background: rgba(79, 209, 217, 0.2);
            color: #4fd1d9;
            border-color: rgba(79, 209, 217, 0.4);
        }

        .project-slides-nav .nav-btn:disabled {
            opacity: 0.2;
            cursor: not-allowed;
        }

        .project-slides-nav .dots {
            display: flex;
            gap: 5px;
        }

        .project-slides-nav .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
        }

        .project-slides-nav .dot.active {
            background: #4fd1d9;
            width: 16px;
            border-radius: 4px;
        }

        /* ==================================================================
           SERVICES — bandeau compact
           ================================================================== */
        .services {
            position: relative;
            z-index: 3;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            width: 100%;
            max-width: 1600px;
            margin: 0 auto 12px auto;
            padding: 0 32px;
            flex-shrink: 0;
        }

        .service-item {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 2px solid rgba(79, 209, 217, 0.6);
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-align: center;
        }

        .service-item:hover {
            background: rgba(79, 209, 217, 0.12);
            border-top-color: #4fd1d9;
            transform: translateY(-3px);
        }

        .service-item i {
            font-size: 16px;
            color: #4fd1d9;
            margin-bottom: 2px;
            display: block;
        }

        .service-item h3 {
            font-size: 11.5px;
            font-weight: 600;
            color: #fff;
        }

        .service-item p {
            font-size: 9.5px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 1px;
        }

        /* ==================================================================
           BANDEAU PARTENAIRES — compact
           ================================================================== */
        .partners-bar {
            position: relative;
            z-index: 3;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            border-top: 3px solid #4fd1d9;
            padding: 8px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 -4px 30px rgba(0, 0, 0, 0.4);
            flex-wrap: wrap;
            flex-shrink: 0;
        }

        .partners-bar .label-group {
            display: flex;
            flex-direction: column;
            gap: 1px;
            flex-shrink: 0;
        }

        .partners-bar .label {
            font-size: 11.5px;
            color: #1a1a2e;
            font-weight: 700;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .partners-bar .label i {
            color: #4fd1d9;
            font-size: 15px;
        }

        .partners-bar .count {
            font-size: 9.5px;
            color: #666;
        }

        .partners-carousel {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
            position: relative;
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
            gap: 14px;
            transition: transform 0.5s cubic-bezier(0.2, 1, 0.3, 1);
            white-space: nowrap;
            will-change: transform;
        }

        .partners-bar .partner-item {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 4px 12px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            flex-shrink: 0;
            min-width: 70px;
            min-height: 40px;
            transition: all 0.25s ease;
        }

        .partners-bar .partner-item:hover {
            transform: translateY(-2px);
            border-color: #4fd1d9;
            box-shadow: 0 6px 18px rgba(79, 209, 217, 0.2);
        }

        .partners-bar .partner-item img {
            height: 28px;
            width: auto;
            max-width: 75px;
            object-fit: contain;
        }

        .partners-bar .partner-item span {
            font-size: 10.5px;
            color: #333;
            font-weight: 600;
        }

        .partners-carousel .nav-btn {
            background: #f8fafc;
            border: 1px solid rgba(79, 209, 217, 0.4);
            color: #3bbac1;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            flex-shrink: 0;
        }

        .partners-carousel .nav-btn:hover {
            background: #4fd1d9;
            color: #0a0a0a;
        }

        /* ==================================================================
           RESPONSIVE
           ================================================================== */

        /* Écran large mais sous 1536px de large (typique 1920×1080 @125%) */
        @media (max-width: 1536px) {
            .hero {
                padding-top: 80px;
            }
            .hero-banner {
                padding: 14px 24px 12px 24px;
            }
            .hero-banner h1 {
                font-size: clamp(26px, 3vw, 42px);
            }
            .hero-container {
                padding: 12px 24px 10px 24px;
                gap: 22px;
            }
            .hero-project-carousel {
                flex: 0 0 360px;
            }
            .services {
                padding: 0 24px;
            }
            .partners-bar {
                padding: 8px 24px;
            }
        }

        @media (min-width: 769px) {
            .header-title { display: block; }
        }

        @media (max-width: 900px) {
            .hero-container {
                flex-direction: column;
                align-items: stretch;
                padding-top: 10px;
            }
            .hero-content {
                flex: 1 1 100%;
                max-width: 100%;
                text-align: center;
            }
            .stats-badge {
                justify-content: center;
            }
            .hero-project-carousel {
                flex: 1 1 100%;
                width: 100%;
                max-width: 550px;
                margin: 0 auto;
            }
            .services {
                grid-template-columns: repeat(2, 1fr);
                padding: 0 16px;
            }
            .partners-bar {
                padding: 10px 16px;
            }
            .hero-banner {
                padding: 16px 16px;
            }
        }

        @media (max-width: 500px) {
            .header {
                top: 4px;
                left: 4px;
                right: 4px;
                padding: 4px 8px;
            }
            .btn-outline { display: none; }
            .hero {
                padding-top: 72px;
            }
            .services {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            .service-item p { display: none; }
            .partners-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .partners-carousel {
                width: 100%;
            }
            .hero-banner h1 {
                font-size: 24px;
            }
            .hero-banner .subtitle {
                font-size: 13px;
            }
        }

        /* Fix spécial 1920x1080 @125% : hauteur écran CSS ≈ 864px */
        @media (min-width: 1400px) and (max-height: 900px) {
            .hero {
                padding-top: 78px;
                padding-bottom: 6px;
            }
            .hero-banner {
                padding: 12px 32px 10px 32px;
            }
            .hero-banner h1 {
                font-size: clamp(26px, 2.8vw, 40px);
                margin-bottom: 2px;
            }
            .hero-banner .banner-divider {
                margin: 6px auto;
            }
            .hero-container {
                padding: 10px 32px 8px 32px;
                gap: 22px;
            }
            .project-card-wrapper {
                padding: 14px 14px 10px 14px;
            }
            .project-slide .slide-inner {
                min-height: 110px;
                padding: 10px;
            }
            .services {
                margin-bottom: 8px;
                gap: 8px;
            }
            .service-item {
                padding: 6px 10px;
            }
            .partners-bar {
                padding: 6px 32px;
            }
            .partners-bar .partner-item {
                min-height: 36px;
            }
            .partners-bar .partner-item img {
                height: 24px;
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
                Plateforme <span>·</span> Multi-Acteurs Violences faites aux femmes 06
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

        <div class="hero-banner">
            <h1>
                Plateforme collaborative <br>
                <span class="highlight">multi-acteurs</span>
            </h1>
            <div class="banner-divider" aria-hidden="true"></div>
            <div class="subtitle">
                au service de la lutte contre les <strong>violences faites aux femmes</strong>
            </div>
        </div>

        <div class="hero-container">
            <div class="hero-content">
                <p class="description">
                    <strong>Une plateforme sécurisée</strong> qui réunit l'ensemble des acteurs engagés contre les violences faites aux femmes,
                    afin de <span class="accent">coordonner leurs actions</span>, <span class="accent">partager leurs ressources</span>
                    et <span class="accent">fluidifier les parcours de protection</span>.
                </p>

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

            <div class="hero-project-carousel">
                <div class="project-card-wrapper">
                    <div class="project-card-header">
                        <span class="project-card-badge">
                            <i class="fas fa-flag-checkered" aria-hidden="true"></i>
                            Projet départemental
                        </span>
                        <span class="project-card-date">2024 ‑ 2027</span>
                    </div>

                    <h3 class="project-card-title">
                        Schéma départementale de lutte contre les <span class="highlight">violences faites aux femmes 06</span>
                    </h3>

                    <div class="project-slides-wrapper">
                        <div class="project-slides-track" id="projectTrack">
                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-handshake" aria-hidden="true"></i></div>
                                    <div class="slide-title">Plateforme <span class="highlight">Multi-Acteurs</span></div>
                                    <div class="slide-content">
                                        Un espace numérique sécurisé pour renforcer la coopération entre institutions, collectivités, associations, professionnels de santé, forces de l'ordre et acteurs de la justice.
                                    </div>
                                </div>
                            </div>

                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-gavel" aria-hidden="true"></i></div>
                                    <div class="slide-title">Origine du <span class="highlight">projet</span></div>
                                    <div class="slide-content">
                                        Issue du <strong>Schéma départemental 2024‑2027</strong> porté par la Préfecture, les Parquets de Nice et Grasse, les collectivités et les associations spécialisées.
                                    </div>
                                    <a href="{{ asset('docs/schema_vff06.pdf') }}" target="_blank" class="slide-link" aria-label="Consulter le Schéma départemental">
                                        Consulter le schéma <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-network-wired" aria-hidden="true"></i></div>
                                    <div class="slide-title">Mettre en <span class="highlight">réseau</span></div>
                                    <div class="slide-content">
                                        <strong>Annuaire départemental</strong> et <strong>cartographie interactive</strong> pour identifier rapidement les partenaires et dispositifs du territoire.
                                    </div>
                                </div>
                            </div>

                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-comments" aria-hidden="true"></i></div>
                                    <div class="slide-title">Favoriser les <span class="highlight">échanges</span></div>
                                    <div class="slide-content">
                                        Espace sécurisé pour partager expériences, pratiques et ressources dans le respect du <strong>RGPD</strong>.
                                    </div>
                                </div>
                            </div>

                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-folder-open" aria-hidden="true"></i></div>
                                    <div class="slide-title">Centraliser les <span class="highlight">ressources</span></div>
                                    <div class="slide-content">
                                        <strong>Documents, procédures, fiches réflexes</strong> et outils métiers pour l'accompagnement des victimes.
                                    </div>
                                </div>
                            </div>

                            <div class="project-slide">
                                <div class="slide-inner">
                                    <div class="slide-icon"><i class="fas fa-star" aria-hidden="true"></i></div>
                                    <div class="slide-title">Valoriser les <span class="highlight">initiatives</span></div>
                                    <div class="slide-content">
                                        Mettre en avant les <strong>projets innovants</strong> et groupes de travail développés dans les Alpes‑Maritimes.
                                    </div>
                                </div>
                            </div>

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
                <span class="count">{{ $organismes->count() }} Organismes inscrits</span>
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
        // CARROUSEL PROJET
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
                track.style.transform = `translateX(-${currentSlide * 100}%)`;

                const dots = document.querySelectorAll('#projectDots .dot');
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentSlide);
                });

                document.getElementById('projectPrev').disabled = currentSlide === 0;
                document.getElementById('projectNext').disabled = currentSlide >= maxIndex;
            }

            function nextSlide() {
                moveToSlide(currentSlide < totalSlides - 1 ? currentSlide + 1 : 0);
            }

            function prevSlide() {
                moveToSlide(currentSlide > 0 ? currentSlide - 1 : totalSlides - 1);
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

            createDots();
            moveToSlide(0);
            startAutoSlide();
        })();

        // CARROUSEL PARTENAIRES
        let currentIndex = 0;
        let itemsPerView = 4;
        let totalItems = 0;

        function updateItemsPerView() {
            const width = window.innerWidth;
            if (width < 500) itemsPerView = 2;
            else if (width < 800) itemsPerView = 3;
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

            const itemWidth = logos[0].offsetWidth + 14;
            const offset = currentIndex * itemWidth;

            const logosContainer = document.getElementById('partnerLogos');
            logosContainer.style.transform = `translateX(-${offset}px)`;
        }

        window.addEventListener('resize', function() {
            updateItemsPerView();
            moveCarousel(0);
        });

        document.addEventListener('DOMContentLoaded', function() {
            updateItemsPerView();
            moveCarousel(0);
        });

        // PARTICULES
        function createParticles() {
            const container = document.getElementById('particles');
            if (!container) return;

            const particleCount = 20;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                const size = Math.random() * 4 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                container.appendChild(particle);
            }
        }
        window.addEventListener('load', createParticles);
    </script>
</body>
</html>