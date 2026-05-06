@extends('base')

@section('title', 'Plateforme – Schéma Départemental')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Plateforme – Schéma Départemental | Alpes-Maritimes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marianne:wght@400;700;800&family=Spectral:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            --primary: #255156;
            --primary-dark: #1e4144;
            --primary-light: #2d6b72;
            --accent: #C9A227;
            --accent-light: #fdf3d8;
            --bg: #f6f8f9;
            --card: #ffffff;
            --text: #2b2b2b;
            --muted: #6b7280;
            --shadow: 0 4px 18px rgba(0,0,0,0.08);
            --shadow-card: 0 2px 16px rgba(37,81,86,0.12);
        }

        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Marianne', 'Arial', sans-serif;
            min-height: 100vh;
        }

        /* HEADER */
        header {
            background: var(--card);
            border-bottom: 3px solid var(--primary);
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding: 1rem 0;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .header-brand:hover { transform: scale(1.02); }

        .header-brand img {
            height: 72px;
            width: auto;
        }

        .header-title {
            border-left: 2px solid #ECEAE3;
            padding-left: 1.25rem;
        }

        .header-title h1 {
            font-size: 1rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.2;
            text-transform: uppercase;
            margin: 0;
        }

        .header-title p {
            font-size: 0.75rem;
            color: var(--muted);
            margin-top: 0.2rem;
            font-style: italic;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-nav {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1.1rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-nav-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-nav-outline:hover { background: var(--primary); color: var(--card); }

        .btn-nav-solid {
            background: var(--primary);
            color: var(--card);
            border: 2px solid var(--primary);
        }

        .btn-nav-solid:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

        /* HERO */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--card);
            padding: 4rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '👩‍⚖️';
            position: absolute;
            bottom: -50px;
            right: -50px;
            font-size: 300px;
            opacity: 0.08;
            pointer-events: none;
        }

        .hero::after {
            content: '⚖️';
            position: absolute;
            top: -30px;
            left: -30px;
            font-size: 250px;
            opacity: 0.08;
            pointer-events: none;
        }

        .hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-badge {
            display: inline-block;
            background: var(--accent);
            color: #1a1a1a;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.3rem 0.9rem;
            border-radius: 2px;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.05); }
        }

        .hero h2 {
            font-size: clamp(1.6rem, 3.5vw, 2.5rem);
            font-weight: 800;
            line-height: 1.15;
            max-width: 680px;
            margin-bottom: 1rem;
        }

        .hero p {
            font-family: 'Spectral', Georgia, serif;
            font-size: 1.05rem;
            opacity: 0.88;
            max-width: 560px;
            line-height: 1.65;
        }

        .hero-contact {
            margin-top: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .hero-contact-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 6px;
            padding: 0.75rem 1.25rem;
            font-size: 0.82rem;
            transition: all 0.3s;
        }

        .hero-contact-card:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .hero-contact-card strong { display: block; font-size: 0.9rem; margin-bottom: 0.15rem; }

        /* URGENCE BANNER */
        .urgence-banner {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: var(--card);
            padding: 0.85rem 2rem;
            position: sticky;
            top: 90px;
            z-index: 999;
        }

        .urgence-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .urgence-title {
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .urgence-numbers {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .urgence-num {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            transition: transform 0.2s;
            cursor: pointer;
        }

        .urgence-num:hover {
            transform: scale(1.05);
        }

        .urgence-num .num {
            background: rgba(255,255,255,0.25);
            border-radius: 3px;
            padding: 0.15rem 0.6rem;
            font-weight: 800;
            font-size: 1rem;
        }

        /* MAIN CONTENT */
        .main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 2rem 4rem;
        }

        /* QUICK ACTIONS - Version animée */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .qa-card {
            background: var(--card);
            border: 1.5px solid #ECEAE3;
            border-radius: 12px;
            padding: 1.5rem;
            text-decoration: none;
            color: var(--text);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            box-shadow: var(--shadow-card);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .qa-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(37,81,86,0.1), transparent);
            transition: left 0.5s;
        }

        .qa-card:hover::before {
            left: 100%;
        }

        .qa-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: var(--primary);
            box-shadow: 0 12px 28px rgba(37,81,86,0.2);
        }

        .qa-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            transition: transform 0.3s;
        }

        .qa-card:hover .qa-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .qa-icon-blue  { background: #dce8ff; color: var(--primary); }
        .qa-icon-green { background: #d4edda; color: #16a34a; }
        .qa-icon-gold  { background: var(--accent-light); color: var(--accent); }
        .qa-icon-info  { background: #d1ecf1; color: #0c5460; }

        .qa-card strong { font-size: 1.1rem; font-weight: 800; line-height: 1.3; }
        .qa-card p  { font-size: 0.85rem; color: var(--muted); line-height: 1.4; margin: 0; }

        /* SECTION UNIQUE (Affiche + Contacts) */
        .dynamic-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }

        /* AFFICHE WIDGET - Version image */
        .affiche-widget {
            background: var(--card);
            border-radius: 16px;
            border: 1.5px solid #ECEAE3;
            padding: 1.5rem;
            box-shadow: var(--shadow-card);
            transition: all 0.3s;
        }

        .affiche-widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(37,81,86,0.15);
        }

        .affiche-widget h3 {
            font-size: 1rem;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-light);
        }

        .affiche-image {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.25rem;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .affiche-image:hover {
            transform: scale(1.02);
        }

        .affiche-image img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .btn-dl {
            display: block;
            width: 100%;
            text-align: center;
            background: var(--primary);
            color: var(--card);
            text-decoration: none;
            padding: 0.85rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-dl::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-dl:hover::before {
            left: 100%;
        }

        .btn-dl:hover { 
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37,81,86,0.3);
        }

        /* CONTACTS WIDGET */
        .contacts-widget {
            background: var(--card);
            border-radius: 16px;
            border: 1.5px solid #ECEAE3;
            padding: 1.5rem;
            box-shadow: var(--shadow-card);
            transition: all 0.3s;
        }

        .contacts-widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(37,81,86,0.15);
        }

        .contacts-widget h3 {
            font-size: 1rem;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-light);
        }

        .contact-list {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .contact-item {
            font-size: 0.85rem;
            line-height: 1.5;
            padding: 0.65rem;
            border-radius: 8px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .contact-item:hover {
            background: linear-gradient(135deg, var(--bg), #e8f0f0);
            transform: translateX(5px);
        }

        .contact-item strong { 
            display: block; 
            color: var(--primary); 
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }
        
        .contact-item a { 
            color: var(--text); 
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .contact-item a:hover { 
            text-decoration: underline; 
            color: var(--primary);
        }

        .voir-annuaire {
            display: inline-block;
            margin-top: 1.25rem;
            font-size: 0.85rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .voir-annuaire:hover {
            transform: translateX(5px);
            text-decoration: underline;
        }

        /* FOOTER */
        footer {
            background: var(--primary-dark);
            color: rgba(255,255,255,0.8);
            padding: 2rem;
            margin-top: 3rem;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .footer-inner img { 
            height: 48px; 
            opacity: 0.9;
            filter: brightness(0) invert(1);
            transition: opacity 0.3s;
        }

        .footer-inner img:hover {
            opacity: 1;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            font-size: 0.8rem;
        }

        .footer-links a { 
            color: rgba(255,255,255,0.75); 
            text-decoration: none; 
            transition: all 0.3s;
        }
        
        .footer-links a:hover { 
            color: #fff; 
            text-decoration: underline;
            transform: translateY(-2px);
        }

        .footer-copy { font-size: 0.75rem; opacity: 0.65; }

        /* ANIMATIONS */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .quick-actions .qa-card {
            animation: slideIn 0.5s ease-out forwards;
            opacity: 0;
        }

        .quick-actions .qa-card:nth-child(1) { animation-delay: 0.1s; }
        .quick-actions .qa-card:nth-child(2) { animation-delay: 0.2s; }
        .quick-actions .qa-card:nth-child(3) { animation-delay: 0.3s; }
        .quick-actions .qa-card:nth-child(4) { animation-delay: 0.4s; }
        .quick-actions .qa-card:nth-child(5) { animation-delay: 0.5s; }

        /* RESPONSIVE */
        @media (max-width: 860px) {
            .dynamic-section { grid-template-columns: 1fr; }
            .header-inner { flex-wrap: wrap; }
            .header-nav { width: 100%; justify-content: flex-end; }
        }

        @media (max-width: 560px) {
            .quick-actions { grid-template-columns: 1fr; }
            .hero h2 { font-size: 1.4rem; }
            .urgence-inner { flex-direction: column; align-items: flex-start; }
            .hero-contact { flex-direction: column; }
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<header>
    <div class="header-inner">
        <a href="{{ url('/') }}" class="header-brand">
            <img src="{{ asset('img/logo_prefet.png') }}" alt="Préfet des Alpes-Maritimes">
            <div class="header-title">
                <h1>Schéma Départemental<br>Droits des Femmes</h1>
                <p>Alpes-Maritimes · DDETS 06</p>
            </div>
        </a>
        <div class="header-nav">
            @guest
                <a href="{{ route('login') }}" class="btn-nav btn-nav-outline">Se connecter</a>
                <a href="{{ route('register') }}" class="btn-nav btn-nav-solid">Créer un compte</a>
            @endguest
        </div>
    </div>
</header>

{{-- URGENCE BANNER --}}
<div class="urgence-banner">
    <div class="urgence-inner">
        <span class="urgence-title"><i class='bx bx-alarm-exclamation'></i> En cas d'urgence</span>
        <div class="urgence-numbers">
            <div class="urgence-num" onclick="window.location.href='tel:17'"><span class="num">17</span> Police / Gendarmerie</div>
            <div class="urgence-num" onclick="window.location.href='tel:15'"><span class="num">15</span> SAMU</div>
            <div class="urgence-num" onclick="window.location.href='tel:3919'"><span class="num">3919</span> Violences Femmes Info</div>
            <div class="urgence-num" onclick="window.location.href='tel:115'"><span class="num">115</span> Hébergement d'urgence</div>
        </div>
    </div>
</div>

{{-- HERO --}}
<section class="hero">
    <div class="hero-inner">
        <span class="hero-badge">Plateforme officielle</span>
        <h2>Bienvenue sur la plateforme<br>du Schéma Départemental</h2>
        <p>
            Espace numérique dédié aux acteurs et partenaires du schéma départemental
            pour les droits des femmes et l'égalité dans les Alpes-Maritimes.
            Accédez aux ressources, à l'annuaire et à la cartographie.
        </p>
        <div class="hero-contact">
            <div class="hero-contact-card">
                <strong>Patricia Mendoza Cerisuelo</strong>
                Déléguée départementale aux droits des femmes<br>
                📞 04 93 72 27 26 &nbsp;·&nbsp; 📱 07 89 29 36 97
            </div>
            <div class="hero-contact-card">
                <strong>DDETS 06</strong>
                147, Bd du Mercantour · CADAM<br>
                06286 Nice Cedex 3
            </div>
        </div>
    </div>
</section>

{{-- MAIN --}}
<main class="main">

    {{-- Accès rapides --}}
    <div class="quick-actions">
        <a href="{{ route('annuaire.index') }}" class="qa-card">
            <div class="qa-icon qa-icon-blue">🏢</div>
            <div>
                <strong>Annuaire</strong>
                <p>Structures, membres, carte interactive</p>
            </div>
        </a>

        <a href="{{ route('events.index') }}" class="qa-card">
            <div class="qa-icon qa-icon-gold">📅</div>
            <div>
                <strong>Agenda</strong>
                <p>Événements, 8 mars, 25 novembre</p>
            </div>
        </a>

        <a href="{{ route('forum.index') }}" class="qa-card">
            <div class="qa-icon qa-icon-info">💬</div>
            <div>
                <strong>Forum</strong>
                <p>Échanges entre partenaires</p>
            </div>
        </a>
        
        <a href="{{ route('resources.index') }}" class="qa-card">
            <div class="qa-icon qa-icon-green">📚</div>
            <div>
                <strong>Ressources</strong>
                <p>Espace documentaire</p>
            </div>
        </a>
        
        @auth
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('dashboard') }}" class="qa-card">
                <div class="qa-icon qa-icon-blue">📊</div>
                <div>
                    <strong>Tableau de bord</strong>
                    <p>Statistiques et administration</p>
                </div>
            </a>
            @endif
        @endauth
    </div>

    {{-- Section dynamique: Affiche + Contacts --}}
    <div class="dynamic-section">

        {{-- Affiche avec image --}}
        <div class="affiche-widget">
            <h3>📌 Affiche à diffuser</h3>
            <div class="affiche-image">
                <img src="{{ asset('img/affiche-violences-femmes.jpg') }}" 
                     alt="Affiche violences faites aux femmes - Alpes-Maritimes"
                     onerror="this.src='https://via.placeholder.com/400x500?text=AFFICHE+VIOLENCES+FEMMES'; this.style.objectFit='cover'">
            </div>
            <a href="{{ asset('img/Affiche-ALPES-MARITIMES.pdf') }}"
               download class="btn-dl">
                ⬇ Télécharger l'affiche (PDF)
            </a>
        </div>

        {{-- Contacts locaux --}}
        <div class="contacts-widget">
            <h3>📞 Contacts locaux</h3>
            <div class="contact-list">
                <div class="contact-item">
                    <strong>CIDFF 06</strong>
                    <a href="tel:0493715569">📞 04 93 71 55 69</a>
                </div>
                <div class="contact-item">
                    <strong>ALC – Violences conjugales</strong>
                    <a href="tel:0492075500">📞 04 92 07 55 00</a>
                </div>
                <div class="contact-item">
                    <strong>Pass'R'elles (Nice)</strong>
                    <a href="tel:0497133946">📞 04 97 13 39 46</a>
                </div>
                <div class="contact-item">
                    <strong>Parcours de femmes (Cannes)</strong>
                    <a href="tel:0493480356">📞 04 93 48 03 56</a>
                </div>
                <div class="contact-item">
                    <strong>Parenthèse (Sophia Antipolis)</strong>
                    <a href="tel:0492197560">📞 04 92 19 75 60</a>
                </div>
            </div>
            <a href="{{ route('annuaire.index') }}" class="voir-annuaire">
                Voir l'annuaire complet →
            </a>
        </div>

    </div>
</main>

{{-- FOOTER --}}
<footer>
    <div class="footer-inner">
        <img src="{{ asset('img/logo_prefet.png') }}" alt="Préfet des Alpes-Maritimes">
        <div class="footer-links">
            <a href="#">Mentions légales</a>  
            <a href="https://www.arretonslesviolences.gouv.fr" target="_blank" rel="noopener">arretonslesviolences.gouv.fr</a>
            <a href="#">Accessibilité</a>
            <a href="#">Contact</a>
        </div>
        <p class="footer-copy">© {{ date('Y') }} Préfet des Alpes-Maritimes – DDETS 06</p>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.affiche-widget, .contacts-widget').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });

        // Animation des numéros d'urgence
        document.querySelectorAll('.urgence-num').forEach(num => {
            num.addEventListener('click', function() {
                const phone = this.querySelector('.num').innerText;
                console.log(`Appel vers le ${phone}`);
            });
        });
    });
</script>

</body>
</html>
@endsection