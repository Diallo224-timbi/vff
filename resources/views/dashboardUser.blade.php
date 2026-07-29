@extends('base')

@section('title', 'Plateforme VFF 06 – Alpes-Maritimes')

@section('head')
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="vff-root">

    {{-- ══════════ HEADER ══════════ --}}
    <header class="vff-header">
        <div class="vff-hw">
            <div class="vff-brand">
                <img src="{{ asset('img/logo_prefet.png') }}" alt="Préfet 06"
                     onerror="this.style.display='none'">
                <div class="vff-brand-sep"></div>
                <div>
                    <div class="vff-brand-name">Plateforme VFF 06 – Alpes-Maritimes</div>
                    <div class="vff-brand-sub">Schéma Départemental · DDETS 06</div>
                </div>
            </div>
            @auth
            <div class="vff-user">
                <div class="vff-user-av">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                <div>
                    <div class="vff-user-nm">{{ auth()->user()->name }}</div>
                    <div class="vff-user-rl">{{ ucfirst(auth()->user()->role ?? 'Partenaire') }}</div>
                </div>
            </div>
            @endauth
        </div>
    </header>

    {{-- ══════════ HERO + CARTE ══════════ --}}
    <section class="vff-hero">
        <div class="vff-hero-mesh"></div>
        <div class="vff-orb o1"></div>
        <div class="vff-orb o2"></div>

        <div class="vff-hw vff-hero-inner">
            <div class="vff-hero-content">
                <div class="vff-urgence">
                    <i class='bx bx-phone-call'></i>
                    Urgence <strong>3919</strong>
                </div>
                <h1 class="vff-hero-h1">
                    Bienvenue sur la plateforme collaborative
                    des acteurs engagés dans la lutte contre
                    les violences faites aux femmes dans les
                    <span class="vff-h1-loc">Alpes-Maritimes.</span>
                </h1>
                <p class="vff-hero-p">
                    Accompagnement, ressources et mise en réseau pour les acteurs du territoire.
                </p>
                <div class="vff-hero-ctas">
                    @if(Route::has('resources.index'))
                    <a href="{{ route('resources.index') }}" class="vff-cta vff-cta-primary">
                        <i class='bx bx-book-open'></i> Se Former
                    </a>
                    @endif
                    @if(Route::has('annuaire.index'))
                    <a href="{{ route('annuaire.index') }}" class="vff-cta vff-cta-ghost">
                        <i class='bx bx-search'></i> Explorer
                    </a>
                    @endif
                </div>
            </div>

            {{-- CARTE DU DÉPARTEMENT 06 --}}
            <div class="vff-hero-map">
                <svg viewBox="0 0 400 460" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M200 40 C270 40 330 80 350 150 C370 220 360 300 330 350 
                             C300 400 250 430 200 440 C150 430 100 400 70 350 
                             C40 300 30 220 50 150 C70 80 130 40 200 40Z" 
                          fill="rgba(255,255,255,0.08)" 
                          stroke="rgba(255,255,255,0.3)" 
                          stroke-width="2"/>
                    <path d="M200 70 C255 70 300 105 315 160 C330 215 320 280 295 320 
                             C270 360 230 385 200 395 C170 385 130 360 105 320 
                             C80 280 70 215 85 160 C100 105 145 70 200 70Z" 
                          fill="rgba(255,255,255,0.05)" 
                          stroke="rgba(255,255,255,0.15)" 
                          stroke-width="1.5"/>
                    <circle cx="200" cy="160" r="6" fill="#fbbf24"/>
                    <text x="210" y="155" fill="rgba(255,255,255,0.9)" font-size="11" font-weight="700">Nice</text>
                    <circle cx="260" cy="240" r="4" fill="rgba(255,255,255,0.6)"/>
                    <text x="268" y="237" fill="rgba(255,255,255,0.6)" font-size="9" font-weight="600">Grasse</text>
                    <circle cx="160" cy="280" r="4" fill="rgba(255,255,255,0.5)"/>
                    <text x="145" y="277" fill="rgba(255,255,255,0.5)" font-size="9" font-weight="600">Cannes</text>
                    <circle cx="320" cy="195" r="3" fill="rgba(255,255,255,0.4)"/>
                    <text x="310" y="190" fill="rgba(255,255,255,0.4)" font-size="8" font-weight="600">Antibes</text>
                    <circle cx="140" cy="135" r="3" fill="rgba(255,255,255,0.4)"/>
                    <text x="128" y="130" fill="rgba(255,255,255,0.4)" font-size="8" font-weight="600">Menton</text>
                    <line x1="200" y1="160" x2="260" y2="240" stroke="rgba(255,255,255,0.08)" stroke-width="1" stroke-dasharray="4 4"/>
                    <line x1="200" y1="160" x2="160" y2="280" stroke="rgba(255,255,255,0.08)" stroke-width="1" stroke-dasharray="4 4"/>
                    <line x1="200" y1="160" x2="320" y2="195" stroke="rgba(255,255,255,0.08)" stroke-width="1" stroke-dasharray="4 4"/>
                    <line x1="200" y1="160" x2="140" y2="135" stroke="rgba(255,255,255,0.08)" stroke-width="1" stroke-dasharray="4 4"/>
                    <text x="200" y="210" fill="rgba(255,255,255,0.08)" font-size="45" font-weight="900" text-anchor="middle">06</text>
                </svg>
            </div>
        </div>
    </section>

    {{-- ══════════ CARTES NAVIGATION ══════════ --}}
    <section class="vff-cards-wrap">
        <div class="vff-hw">
            <div class="vff-cards">
                @php
                    $navCards = [
                        'annuaire.index'  => ['title' => 'Annuaire des Acteurs', 'icon' => 'bx-search-alt-2', 'color' => '#3b5bdb', 'bg' => '#e8edff'],
                        'structures.map'  => ['title' => 'Cartographie Interactive', 'icon' => 'bx-map-pin', 'color' => '#7c3aed', 'bg' => '#ede9fe'],
                        'events.index'    => ['title' => 'Agenda des Événements', 'icon' => 'bx-calendar-check', 'color' => '#0891b2', 'bg' => '#e0f5fa'],
                        'forum.index'     => ['title' => 'Espace Forum', 'icon' => 'bx-message-dots', 'color' => '#059669', 'bg' => '#d1fae5'],
                        'resources.index' => ['title' => 'Ressources & Outils', 'icon' => 'bx-download', 'color' => '#d97706', 'bg' => '#fef3c7'],
                    ];
                @endphp
                @foreach($navCards as $routeName => $c)
                    @if(Route::has($routeName))
                    <a href="{{ route($routeName) }}" class="vff-card">
                        <div class="vff-card-icon" style="background:{{ $c['bg'] }};color:{{ $c['color'] }};">
                            <i class='bx {{ $c['icon'] }}'></i>
                        </div>
                        <div class="vff-card-content">
                            <h3 class="vff-card-title">{{ $c['title'] }}</h3>
                            <p class="vff-card-desc">{{ $c['desc'] ?? 'Accéder à la section' }}</p>
                        </div>
                        <span class="vff-card-arrow" style="color:{{ $c['color'] }};">→</span>
                    </a>
                    @endif
                @endforeach
                @auth
                    @if(auth()->user()->role === 'admin' && Route::has('dashboard'))
                    <a href="{{ route('dashboard') }}" class="vff-card">
                        <div class="vff-card-icon" style="background:#f0f4f8;color:#475569;">
                            <i class='bx bx-bar-chart-alt-2'></i>
                        </div>
                        <div class="vff-card-content">
                            <h3 class="vff-card-title">Tableau de bord</h3>
                            <p class="vff-card-desc">Statistiques et administration</p>
                        </div>
                        <span class="vff-card-arrow" style="color:#475569;">→</span>
                    </a>
                    @endif
                @endauth
            </div>
        </div>
    </section>

    {{-- ══════════ SINGULARITÉS ══════════ --}}
    <section class="vff-sing-section">
        <div class="vff-hw">
            <div class="vff-sing-grid">
                <div class="vff-sing">
                    <div class="vff-sing-ic" style="background:#e8edff;color:#3b5bdb;"><i class='bx bx-map'></i></div>
                    <div>
                        <h4>Approche Territoriale</h4>
                        <p>Adaptée aux spécificités des Alpes-Maritimes</p>
                    </div>
                </div>
                <div class="vff-sing">
                    <div class="vff-sing-ic" style="background:#ede9fe;color:#7c3aed;"><i class='bx bx-link'></i></div>
                    <div>
                        <h4>Parcours Coordonnés</h4>
                        <p>Lien entre tous les acteurs pour une meilleure prise en charge</p>
                    </div>
                </div>
                <div class="vff-sing">
                    <div class="vff-sing-ic" style="background:#e0f5fa;color:#0891b2;"><i class='bx bx-award'></i></div>
                    <div>
                        <h4>Formations Partagées</h4>
                        <p>Harmoniser les pratiques et monter en compétence</p>
                    </div>
                </div>
                <div class="vff-sing">
                    <div class="vff-sing-ic" style="background:#fef3c7;color:#d97706;"><i class='bx bx-bar-chart-alt'></i></div>
                    <div>
                        <h4>Outil d'Observation</h4>
                        <p>Analyser les données pour mieux orienter les politiques</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════ FOOTER ══════════ --}}
    <footer class="vff-footer">
        <div class="vff-hw vff-footer-inner">
            <div class="vff-footer-logos">
                <img src="{{ asset('img/logo_prefet.png') }}" alt="Préfecture" onerror="this.style.display='none'" style="height:32px;opacity:.7;filter:brightness(0) invert(1);">
                <span class="vff-footer-org">Préfecture 06</span>
                <span class="vff-footer-org">DDETS 06</span>
            </div>
            <div class="vff-footer-links">
                @if(Route::has('charte'))
                    <a href="{{ route('charte') }}">Mentions légales</a>
                @endif
                <a href="https://www.arretonslesviolences.gouv.fr" target="_blank" rel="noopener">arretonslesviolences.gouv.fr</a>
                <span class="vff-footer-copy">© {{ date('Y') }}</span>
            </div>
        </div>
    </footer>

</div>

<style>
/* ════════════════════════════════════════════════
   VARIABLES
════════════════════════════════════════════════ */
:root {
    --p:   #1a4a52;
    --pd:  #0e2f34;
    --pl:  #255156;
    --w:   #ffffff;
    --bg:  #f4f7f8;
    --txt: #0d1f22;
    --mu:  #5e7e83;
    --bdr: #dde6e8;
    --r:   14px;
    --rr:  20px;
    --ease: cubic-bezier(.4,0,.2,1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ════════════════════════════════════════════════
   ROOT - PLEIN ÉCRAN
════════════════════════════════════════════════ */
.vff-root {
    display: flex;
    flex-direction: column;
    height: 100vh;
    height: 100dvh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--bg);
    color: var(--txt);
    -webkit-font-smoothing: antialiased;
    overflow: hidden;
    min-height: 700px;
}

.vff-hw {
    max-width: 1280px;
    margin: 0 auto;
    width: 100%;
    padding: 0 2rem;
}

/* ════════════════════════════════════════════════
   HEADER
════════════════════════════════════════════════ */
.vff-header {
    background: var(--w);
    border-bottom: 3px solid var(--pl);
    padding: 0.6rem 0;
    flex-shrink: 0;
    z-index: 50;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
}

.vff-header .vff-hw {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.vff-brand {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.vff-brand img { 
    height: 44px; 
    width: auto;
    transition: transform 0.2s ease;
}

.vff-brand img:hover { transform: scale(1.05); }

.vff-brand-sep {
    width: 2px;
    height: 34px;
    background: var(--bdr);
    flex-shrink: 0;
}

.vff-brand-name {
    font-size: 0.85rem;
    font-weight: 800;
    color: var(--pl);
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.vff-brand-sub {
    font-size: 0.6rem;
    color: var(--mu);
    font-style: italic;
    margin-top: 0.05rem;
}

.vff-user {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f0f6f7;
    border: 1px solid var(--bdr);
    border-radius: 50px;
    padding: 0.2rem 0.8rem 0.2rem 0.2rem;
    transition: all 0.2s ease;
}

.vff-user:hover {
    background: #e8f0f1;
    border-color: var(--pl);
}

.vff-user-av {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--pl), #2d7a82);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(37,81,86,.25);
}

.vff-user-nm { font-size: 0.75rem; font-weight: 700; color: var(--txt); }
.vff-user-rl { font-size: 0.55rem; color: var(--mu); }

/* ════════════════════════════════════════════════
   HERO + CARTE
════════════════════════════════════════════════ */
.vff-hero {
    position: relative;
    overflow: hidden;
    padding: 1.5rem 0 1.2rem;
    flex-shrink: 0;
    background: linear-gradient(135deg, #0d2b30 0%, #1a4a52 50%, #255156 100%);
    min-height: 280px;
}

.vff-hero-mesh {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,.03) 0%, transparent 60%);
    pointer-events: none;
}

.vff-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    filter: blur(60px);
}

.o1 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(45,122,130,.3) 0%, transparent 70%);
    top: -150px; right: -100px;
    animation: float1 12s ease-in-out infinite alternate;
}

.o2 {
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(201,162,39,.12) 0%, transparent 70%);
    bottom: -80px; left: 5%;
    animation: float2 15s ease-in-out infinite alternate-reverse;
}

@keyframes float1 {
    from { transform: translate(0,0) scale(1); }
    to   { transform: translate(30px,-20px) scale(1.1); }
}

@keyframes float2 {
    from { transform: translate(0,0) scale(1); }
    to   { transform: translate(-20px,30px) scale(1.15); }
}

.vff-hero-inner {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2.5rem;
    align-items: center;
    position: relative;
    z-index: 2;
}

.vff-hero-content {
    animation: fadeUp 0.7s var(--ease);
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

.vff-urgence {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #dc2626;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.9rem;
    border-radius: 6px;
    margin-bottom: 0.8rem;
    box-shadow: 0 4px 14px rgba(220,38,38,.3);
    animation: pulseUrgence 2s ease-in-out infinite;
}

@keyframes pulseUrgence {
    0%, 100% { box-shadow: 0 4px 14px rgba(220,38,38,.3); }
    50% { box-shadow: 0 4px 24px rgba(220,38,38,.5); }
}

.vff-hero-h1 {
    font-size: clamp(1.2rem, 2.2vw, 1.8rem);
    font-weight: 900;
    line-height: 1.2;
    color: rgba(255,255,255,.95);
    letter-spacing: -0.02em;
    margin-bottom: 0.5rem;
    max-width: 600px;
}

.vff-h1-loc {
    color: #fbbf24;
    background: linear-gradient(90deg, #fbbf24, #f59e0b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.vff-hero-p {
    font-size: clamp(0.75rem, 0.9vw, 0.9rem);
    color: rgba(255,255,255,.55);
    line-height: 1.5;
    margin-bottom: 0.9rem;
    max-width: 480px;
}

.vff-hero-ctas { display: flex; gap: 0.6rem; flex-wrap: wrap; }

.vff-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1.2rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.25s var(--ease);
    cursor: pointer;
}

.vff-cta-primary {
    background: var(--w);
    color: var(--pl);
    box-shadow: 0 4px 16px rgba(0,0,0,.15);
}

.vff-cta-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
}

.vff-cta-ghost {
    background: rgba(255,255,255,.08);
    color: rgba(255,255,255,.9);
    border: 1.5px solid rgba(255,255,255,.15);
    backdrop-filter: blur(4px);
}

.vff-cta-ghost:hover {
    background: rgba(255,255,255,.16);
    transform: translateY(-2px);
}

/* ═══════ CARTE DÉPARTEMENT ═══════ */
.vff-hero-map {
    flex-shrink: 0;
    width: 220px;
    opacity: 0.85;
    transition: opacity 0.3s ease;
}

.vff-hero-map:hover { opacity: 1; }

.vff-hero-map svg {
    width: 100%;
    height: auto;
    display: block;
    filter: drop-shadow(0 4px 20px rgba(0,0,0,.2));
}

/* ════════════════════════════════════════════════
   CARTES NAVIGATION
════════════════════════════════════════════════ */
.vff-cards-wrap {
    background: var(--w);
    padding: 0.8rem 0;
    border-bottom: 1px solid var(--bdr);
    flex-shrink: 0;
    box-shadow: 0 2px 12px rgba(0,0,0,.02);
}

.vff-cards {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.8rem;
}

.vff-card {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.6rem 0.8rem;
    border: 1.5px solid var(--bdr);
    border-radius: var(--r);
    background: var(--w);
    text-decoration: none;
    color: var(--txt);
    transition: all 0.25s var(--ease);
    position: relative;
    overflow: hidden;
}

.vff-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, transparent 50%, rgba(0,0,0,.01));
    pointer-events: none;
}

.vff-card:hover {
    transform: translateY(-3px);
    border-color: var(--pl);
    box-shadow: 0 6px 20px rgba(0,0,0,.08);
}

.vff-card-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
    transition: transform 0.25s var(--ease);
}

.vff-card:hover .vff-card-icon { transform: scale(1.1) rotate(3deg); }

.vff-card-content {
    flex: 1;
    min-width: 0;
}

.vff-card-title {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--txt);
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.vff-card-desc {
    font-size: 0.6rem;
    color: var(--mu);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.vff-card-arrow {
    font-size: 1rem;
    opacity: 0.4;
    transition: all 0.25s var(--ease);
    flex-shrink: 0;
}

.vff-card:hover .vff-card-arrow {
    opacity: 1;
    transform: translateX(4px);
}

/* ════════════════════════════════════════════════
   SINGULARITÉS
════════════════════════════════════════════════ */
.vff-sing-section {
    padding: 0.8rem 0;
    background: var(--bg);
    flex-shrink: 0;
    border-top: 1px solid var(--bdr);
}

.vff-sing-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.8rem;
}

.vff-sing {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.5rem 0.8rem;
    background: var(--w);
    border: 1px solid var(--bdr);
    border-radius: var(--r);
    transition: all 0.25s var(--ease);
}

.vff-sing:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
    border-color: var(--pl);
}

.vff-sing-ic {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
    transition: transform 0.25s var(--ease);
}

.vff-sing:hover .vff-sing-ic { transform: scale(1.08); }

.vff-sing h4 {
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    line-height: 1.1;
    color: var(--txt);
}

.vff-sing p {
    font-size: 0.6rem;
    color: var(--mu);
    line-height: 1.3;
}

/* ════════════════════════════════════════════════
   FOOTER
════════════════════════════════════════════════ */
.vff-footer {
    background: var(--pd);
    padding: 0.5rem 0;
    flex-shrink: 0;
    margin-top: auto;
    border-top: 1px solid rgba(255,255,255,.05);
}

.vff-footer-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.vff-footer-logos {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.vff-footer-org {
    font-size: 0.6rem;
    color: rgba(255,255,255,.35);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding-left: 0.6rem;
    border-left: 1px solid rgba(255,255,255,.08);
}

.vff-footer-links {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.vff-footer-links a {
    font-size: 0.65rem;
    color: rgba(255,255,255,.45);
    text-decoration: none;
    transition: color 0.2s ease;
}

.vff-footer-links a:hover { color: rgba(255,255,255,.85); }

.vff-footer-copy {
    font-size: 0.6rem;
    color: rgba(255,255,255,.25);
}

/* ════════════════════════════════════════════════
   RESPONSIVE - OPTIMISÉ POUR ORDINATEUR
════════════════════════════════════════════════ */
@media (max-width: 1024px) {
    .vff-hero-inner { grid-template-columns: 1fr; gap: 0.8rem; }
    .vff-hero-map { width: 160px; margin: 0 auto; opacity: 0.5; }
    .vff-cards { grid-template-columns: repeat(3, 1fr); }
    .vff-sing-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .vff-hw { padding: 0 1rem; }
    .vff-cards { grid-template-columns: repeat(2, 1fr); }
    .vff-sing-grid { grid-template-columns: 1fr; }
    .vff-hero-map { width: 120px; }
    .vff-hero-h1 { font-size: 1rem; max-width: 100%; }
    .vff-brand-name { font-size: 0.65rem; }
    .vff-brand img { height: 32px; }
    .vff-user-nm { display: none; }
    .vff-card-desc { display: none; }
    .vff-footer-inner { flex-direction: column; align-items: flex-start; gap: 0.3rem; }
    .vff-footer-links { flex-wrap: wrap; }
}

@media (max-width: 480px) {
    .vff-cards { grid-template-columns: 1fr 1fr; gap: 0.4rem; }
    .vff-card { padding: 0.4rem 0.5rem; }
    .vff-card-title { font-size: 0.6rem; }
    .vff-card-icon { width: 30px; height: 30px; font-size: 0.9rem; }
    .vff-hero-h1 { font-size: 0.85rem; }
    .vff-hero-map { width: 90px; }
}

@media (min-width: 1400px) {
    .vff-hw { max-width: 1440px; padding: 0 3rem; }
    .vff-hero-h1 { font-size: 2rem; }
    .vff-hero-map { width: 260px; }
    .vff-cards { gap: 1rem; }
    .vff-card { padding: 0.8rem 1rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Animation d'entrée des cartes
    document.querySelectorAll('.vff-card, .vff-sing').forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(10px)';
        setTimeout(() => {
            el.style.transition = 'all 0.35s ease ' + (i * 50) + 'ms';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 100);
    });

    // Effet de survol sur la carte
    document.querySelectorAll('.vff-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.borderColor = '#255156';
        });
        card.addEventListener('mouseleave', function() {
            this.style.borderColor = '#dde6e8';
        });
    });

    // Gestion des erreurs
    window.addEventListener('error', e => {
        if (['IMG','LINK'].includes(e.target?.tagName)) e.preventDefault();
    }, true);
});
</script>
@endsection