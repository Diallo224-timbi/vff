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
                <img src="{{ asset('img/logo_prefet.png') }}" alt="Préfet 06" onerror="this.style.display='none'">
                <div class="vff-brand-sep"></div>
                <div>
                    <div class="vff-brand-name">Plateforme VFF 06 – Alpes-Maritimes</div>
                    <div class="vff-brand-sub">Schéma Départemental · DDETS 06</div>
                </div>
            </div>
            @auth
            <div class="vff-user">
                <div class="vff-user-av">{{ strtoupper(substr(auth()->user()->prenom,0,1)) }}</div>
                <div>
                    <div class="vff-user-nm">{{ auth()->user()->prenom }}</div>
                    <div class="vff-user-rl">{{ ucfirst(auth()->user()->role ?? 'Partenaire') }}</div>
                </div>
            </div>
            @endauth
        </div>
    </header>

    {{-- ══════════ HERO SECTION ══════════ --}}
    <section class="vff-hero">
        <div class="vff-hero-mesh"></div>
        <div class="vff-hw vff-hero-inner">
            <div class="vff-hero-content">
                <h1 class="vff-hero-h1">
                    <span class="vff-animated-welcome">Bienvenue sur la plateforme collaborative</span><br>
                    des acteurs engagés dans la lutte contre les violences faites aux femmes dans les
                    <span class="vff-h1-loc">Alpes-Maritimes.</span>
                </h1>
                <p class="vff-hero-p">
                    Accompagnement, ressources et mise en réseau pour l'ensemble des acteurs du territoire.
                </p>
            </div>
        </div>
    </section>

    {{-- ══════════ CONTENU PRINCIPAL ══════════ --}}
    <main class="vff-main-content">

        {{-- ══════════ CARTES NAVIGATION ══════════ --}}
        @php
            $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        @endphp

        <section class="vff-cards-wrap">
            <div class="vff-hw">
                <div class="vff-cards {{ $isAdmin ? 'is-admin' : 'is-user' }}">
                    @php
                        $navCards = [
                            'annuaire.index'  => ['title' => 'Annuaire des Acteurs', 'desc' => 'Consulter les partenaires', 'icon' => 'bx-search-alt-2', 'color' => '#3b5bdb', 'bg' => '#e8edff'],
                            'structures.map'  => ['title' => 'Cartographie Interactive', 'desc' => 'Géolocaliser les structures', 'icon' => 'bx-map-pin', 'color' => '#7c3aed', 'bg' => '#ede9fe'],
                            'events.index'    => ['title' => 'Agenda des Événements', 'desc' => 'Découvrir les dates et rencontres', 'icon' => 'bx-calendar-check', 'color' => '#0891b2', 'bg' => '#e0f5fa'],
                            'forum.index'     => ['title' => 'Espace Forum', 'desc' => 'Échanger avec les membres', 'icon' => 'bx-message-dots', 'color' => '#059669', 'bg' => '#d1fae5'],
                            'resources.index' => ['title' => 'Ressources & Outils', 'desc' => 'Guides, outils et documentation', 'icon' => 'bx-download', 'color' => '#d97706', 'bg' => '#fef3c7'],
                        ];
                    @endphp

                    {{-- Cartes publiques --}}
                    @foreach($navCards as $routeName => $c)
                        @if(Route::has($routeName))
                        <a href="{{ route($routeName) }}" class="vff-card">
                            <div class="vff-card-icon" style="background:{{ $c['bg'] }};color:{{ $c['color'] }};">
                                <i class='bx {{ $c['icon'] }}'></i>
                            </div>
                            <div class="vff-card-content">
                                <h3 class="vff-card-title">{{ $c['title'] }}</h3>
                                <p class="vff-card-desc">{{ $c['desc'] }}</p>
                                <span class="vff-card-more">En savoir plus →</span>
                            </div>
                        </a>
                        @endif
                    @endforeach

                    {{-- Cartes réservées à l'ADMIN --}}
                    @auth
                        @if($isAdmin)
                            <a href="" class="vff-card">
                                <div class="vff-card-icon" style="background:#fce7f3;color:#db2777;">
                                    <i class='bx bx-sitemap'></i>
                                </div>
                                <div class="vff-card-content">
                                    <h3 class="vff-card-title">Schéma</h3>
                                    <p class="vff-card-desc">Pilotage et orientations</p>
                                    <span class="vff-card-more" style="color:#db2777;">En savoir plus →</span>
                                </div>
                            </a>
                            @if(Route::has('dashboard'))
                            <a href="{{ route('dashboard') }}" class="vff-card">
                                <div class="vff-card-icon" style="background:#f0f4f8;color:#475569;">
                                    <i class='bx bx-bar-chart-alt-2'></i>
                                </div>
                                <div class="vff-card-content">
                                    <h3 class="vff-card-title">Tableau de bord</h3>
                                    <p class="vff-card-desc">Statistiques et administration</p>
                                    <span class="vff-card-more" style="color:#475569;">En savoir plus →</span>
                                </div>
                            </a>
                            @endif
                            @if (Route::has('admin.users'))
                            <a href="{{ route('admin.users') }}" class="vff-card">
                                <div class="vff-card-icon" style="background:#e0e7ff;color:#4f46e5;">
                                    <i class='bx bx-group'></i>
                                </div>
                                <div class="vff-card-content">
                                    <h3 class="vff-card-title">Espace administration</h3>
                                    <p class="vff-card-desc">Gestion des utilisateurs</p>
                                    <span class="vff-card-more" style="color:#4f46e5;">En savoir plus →</span>
                                </div>
                            </a>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>
        </section>

        {{-- ══════════ BANDEAU DÉFILANT ÉVÉNEMENTS ══════════ --}}
        <section class="vff-ticker-wrap">
            <div class="vff-hw">
                <div class="vff-ticker-container {{ !$isAdmin ? 'is-user-large' : '' }}">
                    <div class="vff-ticker-badge">
                        <i class='bx bx-bell bx-tada'></i>
                        <span>{{ !$isAdmin ? 'À LA UNE / AGENDA' : 'INFO / AGENDA' }}</span>
                    </div>
                    <div class="vff-ticker-content">
                        <div class="vff-ticker-track">
                            @php
                                $futureEvents = $agenda->filter(function($event) {
                                    return \Carbon\Carbon::parse($event->date_debut)->isFuture();
                                });
                            @endphp
                            @forelse($futureEvents as $agen)
                                @php
                                    $date = \Carbon\Carbon::parse($agen->date_debut);
                                    $isSpecial = ($date->day === 25 && $date->month === 11) || ($date->day === 8 && $date->month === 3);
                                    $specialTag = ($date->day === 25 && $date->month === 11) ? '🔥 25 NOVEMBRE - JOURNÉE INTERNATIONALE' : '🌟 8 MARS - DROITS DES FEMMES';
                                @endphp
                                <div class="vff-ticker-item {{ $isSpecial ? 'is-special-date' : '' }}">
                                    @if($isSpecial)
                                        <span class="vff-ticker-tag tag-special"><i class="bx bx-star"></i> {{ $specialTag }}</span>
                                    @else
                                        <span class="vff-ticker-tag"><i class="bx bx-calendar-event"></i> Événement à venir</span>
                                    @endif
                                    <strong class="vff-ticker-title">{{ $agen->titre }}</strong>
                                    <span class="vff-ticker-date">
                                        <i class='bx bx-time-five'></i>
                                        {{ $date->translatedFormat('d F Y à H\hi') }}
                                    </span>
                                    @if(!empty($agen->lieu))
                                        <span class="vff-ticker-location">
                                            <i class='bx bx-map'></i> {{ $agen->lieu }}
                                        </span>
                                    @endif
                                    <a href="{{ route('events.index') }}" class="vff-ticker-action">
                                        En savoir plus <i class='bx bx-right-arrow-alt'></i>
                                    </a>
                                </div>
                                <span class="vff-ticker-sep">•</span>
                            @empty
                                <div class="vff-ticker-item">
                                    <span class="vff-ticker-tag"><i class="bx bx-info-circle"></i> Info</span>
                                    <span>Aucun événement à venir pour le moment.</span>
                                </div>
                            @endforelse
                            @foreach($futureEvents as $agen)
                                @php
                                    $date = \Carbon\Carbon::parse($agen->date_debut);
                                    $isSpecial  = ($date->day === 25 && $date->month === 11) || ($date->day === 8 && $date->month === 3);
                                    $specialTag = ($date->day === 25 && $date->month === 11) ? '🔥 25 NOVEMBRE - JOURNÉE INTERNATIONALE' : '🌟 8 MARS - DROITS DES FEMMES';
                                @endphp
                                <div class="vff-ticker-item {{ $isSpecial ? 'is-special-date' : '' }}" aria-hidden="true">
                                    @if($isSpecial)
                                        <span class="vff-ticker-tag tag-special"><i class="bx bx-star"></i> {{ $specialTag }}</span>
                                    @else
                                        <span class="vff-ticker-tag"><i class="bx bx-calendar-event"></i> Événement à venir</span>
                                    @endif
                                    <strong class="vff-ticker-title">{{ $agen->titre }}</strong>
                                    <span class="vff-ticker-date">
                                        <i class='bx bx-time-five'></i>
                                        {{ $date->translatedFormat('d F Y à H\hi') }}
                                    </span>
                                    @if(!empty($agen->lieu))
                                        <span class="vff-ticker-location">
                                            <i class='bx bx-map'></i> {{ $agen->lieu }}
                                        </span>
                                    @endif
                                    @if(!$isAdmin && Route::has('events.index'))
                                        <a href="{{ route('events.index') }}" class="vff-ticker-action">
                                            En savoir plus <i class='bx bx-right-arrow-alt'></i>
                                        </a>
                                    @endif
                                </div>
                                <span class="vff-ticker-sep" aria-hidden="true">•</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- ══════════ FOOTER ══════════ --}}
    <footer class="vff-footer">
        <div class="vff-hw vff-footer-inner">
            <div class="vff-footer-logos">
                <span class="vff-footer-org">Préfecture Alpes Maritimes</span>
                <span class="vff-footer-org">DDETS 06</span>
            </div>
            <div class="vff-footer-links">
                @if(Route::has('charte'))
                    <a href="{{ route('charte') }}">Charte de la plateforme</a>
                @endif
                <a href="https://www.arretonslesviolences.gouv.fr" target="_blank" rel="noopener">arretonslesviolences.gouv.fr</a>
                <span class="vff-footer-copy">© {{ date('Y') }}</span>
            </div>
        </div>
    </footer>
</div>

<style>
/* ════════════════════════════════════════════════
   VARIABLES & GLOBAL LAYOUT
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
    --ease: cubic-bezier(.4,0,.2,1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ✅ Root : prend au minimum toute la hauteur de la fenêtre */
.vff-root {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--bg);
    color: var(--txt);
    -webkit-font-smoothing: antialiased;
}

.vff-hw {
    max-width: 1440px;
    margin: 0 auto;
    width: 100%;
    padding: 0 2rem;
}

/* ✅ Main : grandit pour pousser le footer en bas */
.vff-main-content {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    flex-grow: 1;
}

/* ════════════════════════════════════════════════
   HEADER
════════════════════════════════════════════════ */
.vff-header {
    background: var(--w);
    border-bottom: 3px solid var(--pl);
    padding: 0.65rem 0;
    z-index: 50;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
    flex-shrink: 0;
}

.vff-header .vff-hw {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.vff-brand { display: flex; align-items: center; gap: 0.8rem; }
.vff-brand img { height: 40px; width: auto; }
.vff-brand-sep { width: 2px; height: 30px; background: var(--bdr); }
.vff-brand-name { font-size: 0.82rem; font-weight: 800; color: var(--pl); text-transform: uppercase; }
.vff-brand-sub { font-size: 0.62rem; color: var(--mu); font-style: italic; }

.vff-user {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    background: #f0f6f7;
    border: 1px solid var(--bdr);
    border-radius: 50px;
    padding: 0.22rem 0.9rem 0.22rem 0.22rem;
}

.vff-user-av {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--pl), #2d7a82);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
}

.vff-user-nm { font-size: 0.73rem; font-weight: 700; }
.vff-user-rl { font-size: 0.58rem; color: var(--mu); }

/* ════════════════════════════════════════════════
   HERO
════════════════════════════════════════════════ */
.vff-hero {
    position: relative;
    overflow: hidden;
    padding: 2rem 0;
    background: linear-gradient(135deg, #0d2b30 0%, #1a4a52 50%, #255156 100%);
    flex-shrink: 0;
}

.vff-hero-mesh {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,.04) 0%, transparent 60%);
}

.vff-hero-inner { position: relative; z-index: 2; }

.vff-hero-h1 {
    font-size: clamp(1.25rem, 1.8vw, 1.75rem);
    font-weight: 900;
    line-height: 1.32;
    color: rgba(255,255,255,.95);
    margin-bottom: 0.6rem;
}

.vff-animated-welcome {
    background: linear-gradient(90deg, #ffffff, #6ee7b7, #93c5fd, #ffffff);
    background-size: 200% auto;
    color: #fff;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: textGradient 6s linear infinite, fadeSlideDown 0.8s ease-out;
    display: inline-block;
}

@keyframes textGradient {
    0% { background-position: 0% center; }
    100% { background-position: 200% center; }
}

@keyframes fadeSlideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.vff-h1-loc {
    color: #fbbf24;
    background: linear-gradient(90deg, #fbbf24, #f59e0b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.vff-hero-p { font-size: 0.9rem; color: rgba(255,255,255,.75); }

/* ════════════════════════════════════════════════
   CARTES NAVIGATION
════════════════════════════════════════════════ */
.vff-cards-wrap {
    flex: 0 0 auto;
    padding: 1rem 0 0.5rem 0;
    overflow: visible;
}

.vff-cards {
    display: grid;
    gap: 1.15rem;
}

/* Grille Admin */
.vff-cards.is-admin {
    grid-template-columns: repeat(4, 1fr);
}

.vff-card {
    display: flex;
    align-items: flex-start;
    gap: 0.9rem;
    padding: 1.1rem;
    border: 1.5px solid var(--bdr);
    border-radius: var(--r);
    background: var(--w);
    text-decoration: none;
    color: var(--txt);
    transition: all 0.25s var(--ease);
    box-shadow: 0 4px 12px rgba(0,0,0,.03);
    overflow: hidden;
}

.vff-card:hover {
    transform: translateY(-3px);
    border-color: var(--pl);
    box-shadow: 0 12px 25px rgba(0,0,0,.08);
}

.vff-card-icon {
    width: 44px;
    height: 44px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}

.vff-card-content {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.vff-card-title {
    font-size: 0.9rem;
    font-weight: 800;
    color: var(--txt);
    line-height: 1.3;
}

.vff-card-desc {
    font-size: 0.73rem;
    color: var(--mu);
    margin-top: 0.2rem;
    margin-bottom: 0.4rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.vff-card-more {
    font-size: 0.73rem;
    font-weight: 700;
    color: var(--pl);
    margin-top: auto;
    transition: transform 0.25s var(--ease);
    display: inline-block;
}

.vff-card:hover .vff-card-more {
    transform: translateX(4px);
}

/* NON-ADMIN CARTES */
.vff-cards.is-user {
    grid-template-columns: repeat(5, 1fr);
}

.vff-cards.is-user .vff-card {
    padding: 1.25rem 1.1rem;
}

.vff-cards.is-user .vff-card-icon {
    width: 50px;
    height: 50px;
    font-size: 1.6rem;
}

.vff-cards.is-user .vff-card-title {
    font-size: 0.98rem;
}

.vff-cards.is-user .vff-card-desc {
    font-size: 0.78rem;
}

/* ════════════════════════════════════════════════
   BANDEAU ÉVÉNEMENTS DÉFILANT
════════════════════════════════════════════════ */
.vff-ticker-wrap {
    flex: 0 0 auto;
    padding: 0.4rem 0 0.5rem 0;
}

.vff-ticker-container {
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 2px solid #e11d48;
    border-radius: var(--r);
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(225, 29, 72, 0.1);
}

.vff-ticker-badge {
    background: linear-gradient(135deg, #e11d48, #be123c);
    color: #ffffff;
    padding: 0.9rem 1.4rem;
    font-size: 0.82rem;
    font-weight: 800;
    letter-spacing: 0.4px;
    display: flex;
    align-items: center;
    gap: 0.55rem;
    flex-shrink: 0;
    z-index: 2;
    box-shadow: 4px 0 15px rgba(190, 18, 60, 0.25);
}

.vff-ticker-badge i { font-size: 1.2rem; }

.vff-ticker-content {
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    width: 100%;
    padding: 0.75rem 0;
}

.vff-ticker-track {
    display: inline-flex;
    align-items: center;
    animation: tickerMove 30s linear infinite;
}

.vff-ticker-container:hover .vff-ticker-track {
    animation-play-state: paused;
}

.vff-ticker-item {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.35rem 1rem;
    border-radius: 30px;
}

.vff-ticker-tag {
    background: #ffe4e6;
    color: #be123c;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 0.3rem 0.7rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    text-transform: uppercase;
}

.vff-ticker-title {
    font-size: 0.93rem;
    font-weight: 800;
    color: #0f172a;
}

.vff-ticker-date,
.vff-ticker-location {
    font-size: 0.84rem;
    color: #475569;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-weight: 500;
}

.vff-ticker-date i,
.vff-ticker-location i {
    color: #e11d48;
    font-size: 1.05rem;
}

.vff-ticker-sep {
    color: #fda4af;
    margin: 0 1.2rem;
    font-size: 1.3rem;
}

/* Version grand format non-admin */
.vff-ticker-container.is-user-large {
    border: 2px solid #be123c;
    box-shadow: 0 10px 30px rgba(190, 18, 60, 0.18);
}

.vff-ticker-container.is-user-large .vff-ticker-badge {
    padding: 1.15rem 1.9rem;
    font-size: 0.95rem;
}

.vff-ticker-container.is-user-large .vff-ticker-badge i {
    font-size: 1.5rem;
}

.vff-ticker-container.is-user-large .vff-ticker-content {
    padding: 1rem 0;
}

.vff-ticker-container.is-user-large .vff-ticker-title {
    font-size: 1.08rem;
    font-weight: 800;
}

.vff-ticker-container.is-user-large .vff-ticker-tag {
    font-size: 0.82rem;
    padding: 0.4rem 0.85rem;
}

.vff-ticker-container.is-user-large .vff-ticker-date,
.vff-ticker-container.is-user-large .vff-ticker-location {
    font-size: 0.9rem;
}

.vff-ticker-action {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #e11d48;
    color: #ffffff;
    font-size: 0.82rem;
    font-weight: 800;
    padding: 0.4rem 0.9rem;
    border-radius: 20px;
    text-decoration: none;
    margin-left: 0.55rem;
    box-shadow: 0 4px 10px rgba(225, 29, 72, 0.25);
    transition: all 0.2s ease;
}

.vff-ticker-action:hover {
    background: #be123c;
    transform: translateX(4px);
    box-shadow: 0 6px 14px rgba(190, 18, 60, 0.35);
}

.vff-ticker-item.is-special-date {
    background: linear-gradient(135deg, #fff1f2, #ffe4e6);
    border: 2px solid #e11d48;
    padding-right: 1.25rem;
}

.vff-ticker-item.is-special-date .tag-special {
    background: linear-gradient(135deg, #e11d48, #9f1239);
    color: #ffffff;
    font-weight: 900;
    animation: pulse 2s infinite;
}

.vff-ticker-item.is-special-date .vff-ticker-title {
    color: #881337;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.8; }
    100% { opacity: 1; }
}

@keyframes tickerMove {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* ════════════════════════════════════════════════
   FOOTER
════════════════════════════════════════════════ */
.vff-footer {
    background: var(--pd);
    padding: 1.1rem 0;
    margin-top: auto;
    flex-shrink: 0;
    border-top: 1px solid rgba(255,255,255,.1);
}

.vff-footer-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.vff-footer-logos { display: flex; align-items: center; gap: 0.8rem; }

.vff-footer-org {
    font-size: 0.68rem;
    color: rgba(255,255,255,.5);
    font-weight: 600;
    text-transform: uppercase;
    padding-left: 0.8rem;
    border-left: 1px solid rgba(255,255,255,.15);
}

.vff-footer-links { display: flex; align-items: center; gap: 1.1rem; }
.vff-footer-links a { font-size: 0.73rem; color: rgba(255,255,255,.6); text-decoration: none; }
.vff-footer-links a:hover { color: #fff; }
.vff-footer-copy { font-size: 0.68rem; color: rgba(255,255,255,.4); }

/* ════════════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════════════ */
@media (max-width: 1400px) {
    .vff-cards.is-user { grid-template-columns: repeat(3, 1fr); }
    .vff-cards.is-admin { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 900px) {
    .vff-cards.is-user,
    .vff-cards.is-admin { grid-template-columns: repeat(2, 1fr); }
    .vff-hw { padding: 0 1.25rem; }
}

@media (max-width: 640px) {
    .vff-cards.is-user,
    .vff-cards.is-admin { grid-template-columns: 1fr; }
    .vff-footer-inner { flex-direction: column; gap: 0.8rem; align-items: flex-start; }
    .vff-ticker-badge span { display: none; }
}

/* ════════════════════════════════════════════════
   FIX SPÉCIAL 1920×1080 @125% (≈ 1536×864 CSS)
════════════════════════════════════════════════ */
@media (min-width: 1400px) and (max-height: 900px) {
    .vff-header {
        padding: 0.5rem 0;
    }
    .vff-brand img { height: 36px; }
    .vff-brand-sep { height: 26px; }
    .vff-brand-name { font-size: 0.78rem; }
    .vff-brand-sub { font-size: 0.58rem; }

    .vff-hero {
        padding: 1.5rem 0;
    }
    .vff-hero-h1 {
        font-size: clamp(1.15rem, 1.6vw, 1.55rem);
        line-height: 1.3;
        margin-bottom: 0.5rem;
    }
    .vff-hero-p { font-size: 0.85rem; }

    .vff-cards-wrap {
        padding: 0.9rem 0 0.5rem 0;
    }
    .vff-cards {
        gap: 0.95rem;
    }
    .vff-card {
        padding: 0.95rem;
        gap: 0.75rem;
    }
    .vff-card-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
        border-radius: 10px;
    }
    .vff-card-title { font-size: 0.85rem; }
    .vff-card-desc { font-size: 0.68rem; margin-bottom: 0.3rem; }
    .vff-card-more { font-size: 0.68rem; }

    .vff-cards.is-user .vff-card { padding: 1.05rem 0.95rem; }
    .vff-cards.is-user .vff-card-icon { width: 44px; height: 44px; font-size: 1.4rem; }
    .vff-cards.is-user .vff-card-title { font-size: 0.9rem; }
    .vff-cards.is-user .vff-card-desc { font-size: 0.73rem; }

    .vff-ticker-wrap {
        padding: 0.3rem 0 0.4rem 0;
    }
    .vff-ticker-badge {
        padding: 0.75rem 1.2rem;
        font-size: 0.78rem;
    }
    .vff-ticker-badge i { font-size: 1.05rem; }
    .vff-ticker-content { padding: 0.6rem 0; }
    .vff-ticker-title { font-size: 0.88rem; }
    .vff-ticker-date,
    .vff-ticker-location { font-size: 0.8rem; }
    .vff-ticker-action {
        padding: 0.35rem 0.8rem;
        font-size: 0.78rem;
    }

    .vff-ticker-container.is-user-large .vff-ticker-badge {
        padding: 0.95rem 1.5rem;
        font-size: 0.88rem;
    }
    .vff-ticker-container.is-user-large .vff-ticker-content {
        padding: 0.8rem 0;
    }
    .vff-ticker-container.is-user-large .vff-ticker-title {
        font-size: 1rem;
    }

    .vff-footer {
        padding: 0.85rem 0;
    }
    .vff-footer-org { font-size: 0.62rem; }
    .vff-footer-links a { font-size: 0.68rem; }
    .vff-footer-copy { font-size: 0.62rem; }
}

/* ════════════════════════════════════════════════
   ✅ FOOTER STICKY BOTTOM
   Le footer reste toujours collé en bas de la fenêtre,
   même si le contenu est plus court que la page.
   (1920×1080 @ 125% ≈ 1536×864 CSS)
════════════════════════════════════════════════ */
.vff-root {
    min-height: 100vh !important;
    display: flex !important;
    flex-direction: column !important;
}

.vff-main-content {
    flex-grow: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}

.vff-cards-wrap {
    padding-bottom: 0.5rem !important;
    margin-bottom: 0 !important;
}

.vff-ticker-wrap {
    padding-top: 0.3rem !important;
    padding-bottom: 0.4rem !important;
    margin-bottom: 0 !important;
}

.vff-footer {
    margin-top: auto !important;
    flex-shrink: 0 !important;
    padding-top: 0.85rem !important;
    padding-bottom: 0.85rem !important;
}

/* Au cas où le body parent aurait des contraintes */
body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

body > main,
body > .container,
body > .container-fluid {
    flex-grow: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.vff-card').forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(15px)';
        setTimeout(() => {
            el.style.transition = 'all 0.4s ease ' + (i * 60) + 'ms';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 80);
    });
});
</script>
@endsection