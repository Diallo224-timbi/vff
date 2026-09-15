@extends('base')
@section('title', 'Tableau de bord')
@section('head')
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
@endsection
@section('content')
<div class="vff-dash">
    {{-- ══════════ MESSAGE DE SUCCÈS ══════════ --}}
    @if(session('success'))
        <div class="vff-alert" role="alert">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Succès !</strong>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" aria-label="Fermer">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    {{-- ══════════ HEADER ══════════ --}}
    <div class="vff-dash-header">
        <div class="vff-dash-header-left">
            <div class="vff-dash-header-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <h1 class="vff-dash-title">Tableau de bord</h1>
                <p class="vff-dash-subtitle">
                    <i class="fas fa-info-circle"></i>
                    Vue d'ensemble de la plateforme
                </p>
            </div>
        </div>
    </div>
    {{-- ══════════ KPI RAPIDES ══════════ --}}
    <section class="vff-kpi-grid">
        <article class="vff-kpi-card">
            <header class="vff-kpi-head">
                <div>
                    <p class="vff-kpi-label">Utilisateurs</p>
                    <p class="vff-kpi-value">{{ $totalUsers ?? 0 }}</p>
                </div>
                <div class="vff-kpi-icon" style="background:#008C95;">
                    <i class="fas fa-users"></i>
                </div>
            </header>
            <div class="vff-kpi-foot">
                <span class="vff-kpi-dot" style="background:#008C95;"></span>
                {{ $validatedUsers ?? 0 }} validés
                <span class="vff-kpi-sep">·</span>
                <span class="vff-kpi-dot" style="background:#D2B467;"></span>
                {{ $pendingUsers ?? 0 }} en attente
            </div>
        </article>

        <article class="vff-kpi-card">
            <header class="vff-kpi-head">
                <div>
                    <p class="vff-kpi-label">Structures</p>
                    <p class="vff-kpi-value">{{ $totalStructures ?? 0 }}</p>
                </div>
                <div class="vff-kpi-icon" style="background:#59BEC9;">
                    <i class="fas fa-building"></i>
                </div>
            </header>
            <div class="vff-kpi-foot">
                {{ $villesCount ?? 0 }} villes couvertes
            </div>
        </article>

        <article class="vff-kpi-card">
            <header class="vff-kpi-head">
                <div>
                    <p class="vff-kpi-label">Organismes</p>
                    <p class="vff-kpi-value">{{ $organismesCount ?? 0 }}</p>
                </div>
                <div class="vff-kpi-icon" style="background:#9B7EA4;">
                    <i class="fas fa-sitemap"></i>
                </div>
            </header>
            <div class="vff-kpi-foot">
                Total organismes partenaires
            </div>
        </article>

        <article class="vff-kpi-card">
            <header class="vff-kpi-head">
                <div>
                    <p class="vff-kpi-label">Connexions</p>
                    <p class="vff-kpi-value">{{ $totalConnexions ?? 0 }}</p>
                </div>
                <div class="vff-kpi-icon" style="background:#C79674;">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
            </header>
            <div class="vff-kpi-foot">
                {{ $connexionsJour ?? 0 }} aujourd'hui
            </div>
        </article>

        <article class="vff-kpi-card">
            <header class="vff-kpi-head">
                <div>
                    <p class="vff-kpi-label">Documents</p>
                    <p class="vff-kpi-value">{{ $totalDocuments ?? 0 }}</p>
                </div>
                <div class="vff-kpi-icon" style="background:#CCA1A6;">
                    <i class="fas fa-file-alt"></i>
                </div>
            </header>
            <div class="vff-kpi-foot">
                <span><i class="fas fa-image" style="color:#9B7EA4;"></i> {{ $stats['images'] ?? 0 }}</span>
                <span><i class="fas fa-file-pdf" style="color:#008C95;"></i> {{ $stats['documents'] ?? 0 }}</span>
                <!-- lien -->
                <span><i class="fas fa-link" style="color:#59BEC9;"></i> {{ $totalDocuments-($stats['images'] + $stats['documents']) ?? 0 }}</span>
            </div>
        </article>
    </section>
    {{-- ══════════ BANDEAU UTILISATEURS PAR RÔLE ══════════ --}}
    <article class="vff-chart-card vff-chart-card-strip">
        <header class="vff-chart-head">
            <h2><i class="fas fa-users"></i> Répartition des utilisateurs par rôle</h2>
        </header>
        <div class="vff-strip-body">
            <div class="vff-strip-chart">
                <canvas id="usersChart"></canvas>
            </div>
            <div class="vff-strip-legend">
                <div class="vff-strip-item" style="border-left-color:#008C95;">
                    <span class="vff-strip-dot" style="background:#008C95;"></span>
                    <div>
                        <p class="vff-strip-label">Administrateurs</p>
                        <p class="vff-strip-value">{{ $admins ?? 0 }}</p>
                    </div>
                </div>
                <div class="vff-strip-item" style="border-left-color:#59BEC9;">
                    <span class="vff-strip-dot" style="background:#59BEC9;"></span>
                    <div>
                        <p class="vff-strip-label">Responsables Organismes</p>
                        <p class="vff-strip-value">{{ $moderateurs ?? 0 }}</p>
                    </div>
                </div>
                <div class="vff-strip-item" style="border-left-color:#9B7EA4;">
                    <span class="vff-strip-dot" style="background:#9B7EA4;"></span>
                    <div>
                        <p class="vff-strip-label">Responsables Structures</p>
                        <p class="vff-strip-value">{{ $moderateur_classique ?? 0 }}</p>
                    </div>
                </div>
                <div class="vff-strip-item" style="border-left-color:#D2B467;">
                    <span class="vff-strip-dot" style="background:#D2B467;"></span>
                    <div>
                        <p class="vff-strip-label">Utilisateurs</p>
                        <p class="vff-strip-value">{{ $usersCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </article>

    {{-- ══════════ GRAPHIQUE ORGANISMES ══════════ --}}
    <article class="vff-chart-card">
        <header class="vff-chart-head">
            <h2><i class="fas fa-building"></i> Top organismes par nombre de structures</h2>
            <button type="button" class="vff-chart-link" data-modal-open="modalOrganismes">
                Voir le détail <i class="fas fa-arrow-right"></i>
            </button>
        </header>
        <div class="vff-chart-body vff-chart-body-tall">
            <canvas id="organismesChart"></canvas>
        </div>
    </article>

    {{-- ══════════ ACTIVITÉ DES LOGS ══════════ --}}
    @if(auth()->user()->role === 'admin')
        <article class="vff-chart-card">
            <header class="vff-chart-head">
                <h2><i class="fas fa-chart-line"></i> Activité des 7 derniers jours</h2>
                <a href="{{ route('activity_logs.index') }}" class="vff-chart-link">
                    Voir tous les logs <i class="fas fa-arrow-right"></i>
                </a>
            </header>
            <div class="vff-chart-body">
                <canvas id="activityChart"></canvas>
            </div>
        </article>
    @endif

    {{-- ══════════ DERNIERS ÉLÉMENTS ══════════ --}}
    <section class="vff-recent-grid">

        <article class="vff-recent-card">
            <header class="vff-chart-head">
                <h2><i class="fas fa-user-plus"></i> Derniers inscrits</h2>
            </header>
            <div class="vff-recent-list">
                @forelse($recentUsers ?? [] as $user)
                    <div class="vff-recent-item">
                        <div class="vff-recent-avatar" style="background: linear-gradient(135deg, #008C95, #59BEC9);">
                            {{ strtoupper(substr($user->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="vff-recent-info">
                            <p class="vff-recent-name">{{ $user->prenom ?? '' }} {{ $user->name ?? '' }}</p>
                            <p class="vff-recent-sub">{{ $user->email ?? '' }}</p>
                        </div>
                        <span class="vff-recent-time">{{ $user->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="vff-recent-empty">Aucun utilisateur récent</p>
                @endforelse
            </div>
        </article>

        <article class="vff-recent-card">
            <header class="vff-chart-head">
                <h2><i class="fas fa-file-upload"></i> Derniers documents</h2>
            </header>
            <div class="vff-recent-list">
                @forelse($recentDocuments ?? [] as $doc)
                    <div class="vff-recent-item">
                        <div class="vff-recent-icon" style="background:#6ddceb;">
                            <i class="fas {{ $doc->file_icon }}"></i>
                        </div>
                        <div class="vff-recent-info">
                            <p class="vff-recent-name">{{ $doc->title }}</p>
                            <p class="vff-recent-sub">{{ Str::limit($doc->description ?? '', 30) }}</p>
                        </div>
                        <span class="vff-recent-time">{{ $doc->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="vff-recent-empty">Aucun document récent</p>
                @endforelse
            </div>
        </article>

        <article class="vff-recent-card">
            <header class="vff-chart-head">
                <h2><i class="fas fa-history"></i> Dernières activités</h2>
            </header>
            <div class="vff-recent-list">
                @forelse($recentLogs ?? [] as $log)
                    @php
                        $iconMap = [
                            'Connexion'  => ['fa-sign-in-alt', '#008C95'],
                            'Déconnexion'=> ['fa-sign-out-alt','#C79674'],
                            'create'     => ['fa-plus',        '#59BEC9'],
                            'update'     => ['fa-edit',        '#D2B467'],
                            'delete'     => ['fa-trash',       '#F03E3E'],
                        ];
                        [$icon, $color] = $iconMap[$log->action] ?? ['fa-history', '#9B7EA4'];
                    @endphp
                    <div class="vff-recent-item">
                        <div class="vff-recent-icon" style="background:{{ $color }};">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <div class="vff-recent-info">
                            <p class="vff-recent-name">{{ $log->user->prenom ?? 'Système' }}</p>
                            <p class="vff-recent-sub">{{ Str::limit($log->description ?? $log->action ?? '', 30) }}</p>
                        </div>
                        <span class="vff-recent-time">{{ $log->created_at?->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="vff-recent-empty">Aucune activité récente</p>
                @endforelse
            </div>
        </article>
    </section>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MODAL — DÉTAIL DES ORGANISMES
══════════════════════════════════════════════════════════════ --}}
<div class="vff-modal" id="modalOrganismes" aria-hidden="true">
    <div class="vff-modal-backdrop" data-modal-close></div>
    <div class="vff-modal-panel" role="dialog" aria-modal="true" aria-labelledby="modalOrganismesTitle">
        <header class="vff-modal-head">
            <h3 id="modalOrganismesTitle">
                <i class="fas fa-building"></i> Détail des organismes
            </h3>
            <button type="button" class="vff-modal-close" data-modal-close aria-label="Fermer">
                <i class="fas fa-times"></i>
            </button>
        </header>
        <div class="vff-modal-body">
            <table class="vff-modal-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Organisme</th>
                        <th style="width: 130px; text-align: right;">Structures</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $organismePairs = collect($organismes ?? [])
                            ->map(fn($o, $i) => [
                                'label' => $o->nom_organisme ?? 'Sans nom',
                                'value' => $organismeStructures[$i] ?? 0,
                            ])
                            ->sortByDesc('value')
                            ->values();
                    @endphp
                    @forelse($organismePairs as $i => $p)
                        <tr>
                            <td class="vff-modal-rank">{{ $i + 1 }}</td>
                            <td>{{ $p['label'] }}</td>
                            <td class="vff-modal-value">{{ $p['value'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="vff-modal-empty">Aucun organisme enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     STYLE
══════════════════════════════════════════════════════════════ --}}
<style>
:root {
    /* PALETTE STRICTE */
    --vff-primary:   #008C95;
    --vff-secondary: #59BEC9;
    --vff-purple:    #9B7EA4;
    --vff-brown:     #C79674;
    --vff-salmon:    #CCA1A6;
    --vff-gold:      #D2B467;
    --vff-gray:      #C4CEC2;
    --vff-text:      #2D2926;

    /* UI — NET ET CONTRASTÉ */
    --bg:        #F2F4F3;
    --card:      #FFFFFF;
    --border:    #DDE2E0;        /* bordures plus marquées */
    --border-str:#CDD4D1;
    --muted:     #6B706E;
    --muted-str: #4A4F4D;
    --radius:    16px;
    --radius-sm: 12px;
    --shadow-sm: 0 1px 3px rgba(45,41,38,.08), 0 2px 6px rgba(45,41,38,.05);
    --shadow-md: 0 4px 14px rgba(45,41,38,.10), 0 8px 28px rgba(45,41,38,.08);
    --shadow-lg: 0 20px 60px rgba(45,41,38,.25);
    --ease:      cubic-bezier(.4,0,.2,1);
}

.vff-dash {
    max-width: 1600px;
    margin: 0 auto;
    padding: 1.25rem 1.25rem 2rem;
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--vff-text);
    background: var(--bg);
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.vff-dash h1,
.vff-dash h2,
.vff-dash h3,
.vff-kpi-value {
    font-family: 'Montserrat', -apple-system, sans-serif;
    letter-spacing: -0.015em;
}

/* ═══ ALERTE ═══ */
.vff-alert {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .9rem 1.1rem;
    border-radius: var(--radius-sm);
    background: #E6F4F5;
    color: #005C63;
    border: 1px solid #008C95;
    border-left-width: 4px;
    font-family: 'Roboto', sans-serif;
    font-size: .875rem;
    font-weight: 500;
    position: relative;
}
.vff-alert i { font-size: 1.1rem; color: var(--vff-primary); }
.vff-alert strong { font-family: 'Montserrat', sans-serif; font-weight: 800; margin-right: .3rem; }
.vff-alert button {
    position: absolute;
    top: 50%; right: .8rem;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: inherit;
    cursor: pointer;
    opacity: .7;
    transition: opacity .2s;
}
.vff-alert button:hover { opacity: 1; }

/* ═══ HEADER ═══ */
.vff-dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: var(--radius);
    color: #fff;
    background: linear-gradient(135deg, #00707A 0%, #008C95 50%, #046B73 100%);
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}
.vff-dash-header::after {
    content: '';
    position: absolute;
    top: -40%; right: -5%;
    width: 320px; height: 320px;
    background: radial-gradient(circle, rgba(255,255,255,.15), transparent 60%);
    pointer-events: none;
}
.vff-dash-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative; z-index: 1;
}
.vff-dash-header-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.22);
    border: 1px solid rgba(255,255,255,.25);
    font-size: 1.3rem;
}
.vff-dash-title {
    font-size: 1.25rem;
    font-weight: 800;
    margin: 0;
    color: #fff;
}
.vff-dash-subtitle {
    font-size: .78rem;
    margin: .15rem 0 0;
    color: rgba(255,255,255,.92);
    font-family: 'Roboto', sans-serif;
    font-weight: 400;
}
.vff-dash-subtitle i { margin-right: .25rem; }
.vff-dash-header-right {
    display: flex; align-items: center; gap: .65rem;
    position: relative; z-index: 1;
}
.vff-role-pill {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .45rem .9rem;
    border-radius: 999px;
    font-size: .72rem;
    font-weight: 700;
    background: rgba(255,255,255,.22);
    border: 1px solid rgba(255,255,255,.3);
    text-transform: capitalize;
    font-family: 'Montserrat', sans-serif;
    color: #fff;
}
.vff-avatar {
    width: 38px; height: 38px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: #FFFFFF;
    color: var(--vff-primary);
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: .95rem;
    box-shadow: 0 2px 6px rgba(0,0,0,.15);
}

/* ═══ KPI GRID ═══ */
.vff-kpi-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: .85rem;
}
.vff-kpi-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 1rem 1.1rem;
    box-shadow: var(--shadow-sm);
    transition: transform .25s var(--ease), box-shadow .25s var(--ease), border-color .25s var(--ease);
    display: flex;
    flex-direction: column;
    gap: .6rem;
}
.vff-kpi-card:hover {
    transform: translateY(-3px);
    border-color: var(--vff-primary);
    box-shadow: var(--shadow-md);
}
.vff-kpi-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: .5rem;
}
.vff-kpi-label {
    font-size: .72rem;
    color: var(--muted-str);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin: 0;
    font-family: 'Montserrat', sans-serif;
}
.vff-kpi-value {
    font-size: 1.85rem;
    font-weight: 800;
    color: var(--vff-text);
    margin: .2rem 0 0;
    line-height: 1;
}
.vff-kpi-icon {
    width: 40px; height: 40px;
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.05rem;
    color: #FFFFFF;
    flex-shrink: 0;
    box-shadow: 0 3px 8px rgba(0,0,0,.12);
}
.vff-kpi-foot {
    font-size: .72rem;
    color: var(--muted-str);
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    display: flex; align-items: center; gap: .45rem;
    flex-wrap: wrap;
    padding-top: .4rem;
    border-top: 1px solid var(--border);
}
.vff-kpi-dot {
    display: inline-block;
    width: 7px; height: 7px;
    border-radius: 50%;
}
.vff-kpi-sep { opacity: .4; }

/* ═══ CHART CARD ═══ */
.vff-chart-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.15rem 1.25rem;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    gap: .9rem;
    transition: box-shadow .25s var(--ease), border-color .25s var(--ease);
}
.vff-chart-card:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--border-str);
}

.vff-chart-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .5rem;
    min-height: 28px;
    flex-shrink: 0;
    padding-bottom: .6rem;
    border-bottom: 1px solid var(--border);
}
.vff-chart-head h2 {
    font-size: .92rem;
    font-weight: 800;
    color: var(--vff-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: .5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.vff-chart-head h2 i {
    color: #FFFFFF;
    font-size: .78rem;
    flex-shrink: 0;
    background: var(--vff-primary);
    width: 24px; height: 24px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.vff-chart-link {
    font-size: .72rem;
    color: #FFFFFF;
    background: var(--vff-primary);
    text-decoration: none;
    font-weight: 700;
    display: inline-flex; align-items: center; gap: .35rem;
    transition: background .2s var(--ease), gap .2s var(--ease);
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap;
    border: none;
    cursor: pointer;
    padding: .4rem .85rem;
    border-radius: 8px;
}
.vff-chart-link:hover {
    background: #00707A;
    gap: .55rem;
}

.vff-chart-body {
    position: relative;
    width: 100%;
    flex: 1 1 auto;
    min-height: 240px;
    max-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.vff-chart-body-tall {
    min-height: 300px;
    max-height: 360px;
}
.vff-chart-body canvas {
    width: 100% !important;
    height: 100% !important;
    max-height: 100%;
}

/* ═══ BANDEAU UTILISATEURS PAR RÔLE ═══ */
.vff-chart-card-strip {
    padding: 1.15rem 1.5rem;
}
.vff-strip-body {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 2rem;
    align-items: center;
    min-height: 180px;
}
.vff-strip-chart {
    position: relative;
    width: 100%;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.vff-strip-legend {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: .85rem;
}
.vff-strip-item {
    display: flex;
    align-items: center;
    gap: .7rem;
    padding: .85rem .9rem;
    border-radius: var(--radius-sm);
    background: #F9FAFA;
    border: 1px solid var(--border);
    border-left-width: 4px;
    transition: transform .2s var(--ease), box-shadow .2s var(--ease);
}
.vff-strip-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}
.vff-strip-dot {
    width: 12px; height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}
.vff-strip-label {
    font-size: .66rem;
    color: var(--muted-str);
    margin: 0;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .03em;
    line-height: 1.2;
}
.vff-strip-value {
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--vff-text);
    margin: .2rem 0 0;
    font-family: 'Montserrat', sans-serif;
    line-height: 1;
}

/* ═══ RECENT GRID ═══ */
.vff-recent-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
.vff-recent-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.1rem 1.15rem;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    gap: .85rem;
    transition: box-shadow .25s var(--ease), border-color .25s var(--ease);
}
.vff-recent-card:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--border-str);
}
.vff-recent-list {
    display: flex;
    flex-direction: column;
    gap: .25rem;
    max-height: 260px;
    overflow-y: auto;
    padding-right: .2rem;
}
.vff-recent-item {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .5rem .55rem;
    border-radius: 10px;
    transition: background .2s var(--ease);
}
.vff-recent-item:hover { background: #F2F5F4; }
.vff-recent-avatar,
.vff-recent-icon {
    width: 34px; height: 34px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #FFFFFF;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: .72rem;
    flex-shrink: 0;
    box-shadow: 0 2px 5px rgba(0,0,0,.08);
}
.vff-recent-icon {
    border-radius: 9px;
    font-size: .82rem;
}
.vff-recent-info { flex: 1; min-width: 0; }
.vff-recent-name {
    font-size: .78rem;
    font-weight: 700;
    color: var(--vff-text);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: 'Montserrat', sans-serif;
}
.vff-recent-sub {
    font-size: .68rem;
    color: var(--muted);
    margin: .15rem 0 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: 'Roboto', sans-serif;
}
.vff-recent-time {
    font-size: .65rem;
    color: var(--muted);
    white-space: nowrap;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
}
.vff-recent-empty {
    text-align: center;
    font-size: .75rem;
    color: var(--muted);
    padding: 1.5rem 0;
    margin: 0;
    font-family: 'Roboto', sans-serif;
    font-style: italic;
}

.vff-recent-list::-webkit-scrollbar { width: 5px; }
.vff-recent-list::-webkit-scrollbar-track { background: transparent; }
.vff-recent-list::-webkit-scrollbar-thumb {
    background: var(--vff-gray);
    border-radius: 4px;
}
.vff-recent-list { scrollbar-width: thin; scrollbar-color: var(--vff-gray) transparent; }

/* ═══ MODAL ═══ */
.vff-modal {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.vff-modal.is-open {
    display: flex;
    animation: vffFadeIn .25s var(--ease);
}
.vff-modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(45,41,38,.55);
    backdrop-filter: blur(4px);
}
.vff-modal-panel {
    position: relative;
    background: #fff;
    border-radius: var(--radius);
    width: 100%;
    max-width: 720px;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    animation: vffSlideUp .3s var(--ease);
    border: 1px solid var(--border-str);
}
.vff-modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.25rem;
    border-bottom: 2px solid var(--vff-primary);
    flex-shrink: 0;
    background: #F9FAFA;
}
.vff-modal-head h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 800;
    color: var(--vff-text);
    display: flex;
    align-items: center;
    gap: .55rem;
}
.vff-modal-head h3 i {
    color: #FFFFFF;
    background: var(--vff-primary);
    width: 28px; height: 28px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
}
.vff-modal-close {
    background: #F0F2F1;
    border: 1px solid var(--border);
    width: 34px; height: 34px;
    border-radius: 9px;
    cursor: pointer;
    color: var(--muted-str);
    transition: background .2s, color .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .9rem;
}
.vff-modal-close:hover {
    background: #E4E7E6;
    color: var(--vff-text);
}
.vff-modal-body {
    padding: .5rem .5rem 1rem;
    overflow-y: auto;
    flex: 1 1 auto;
}
.vff-modal-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Roboto', sans-serif;
    font-size: .82rem;
}
.vff-modal-table thead th {
    position: sticky;
    top: 0;
    background: #F0F3F2;
    text-align: left;
    padding: .75rem 1rem;
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--muted-str);
    border-bottom: 2px solid var(--border-str);
}
.vff-modal-table tbody td {
    padding: .65rem 1rem;
    border-bottom: 1px solid var(--border);
    color: var(--vff-text);
    vertical-align: middle;
}
.vff-modal-table tbody tr:last-child td { border-bottom: none; }
.vff-modal-table tbody tr:hover { background: #F9FAFA; }
.vff-modal-rank {
    color: var(--muted);
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: .72rem;
}
.vff-modal-value {
    text-align: right;
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    color: var(--vff-primary);
    font-size: .92rem;
}
.vff-modal-empty {
    text-align: center;
    color: var(--muted);
    padding: 2rem 1rem !important;
    font-style: italic;
}

@keyframes vffFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@keyframes vffSlideUp {
    from { opacity: 0; transform: translateY(12px) scale(.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* ═══ RESPONSIVE ═══ */
@media (max-width: 1400px) {
    .vff-kpi-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 1100px) {
    .vff-recent-grid { grid-template-columns: repeat(2, 1fr); }
    .vff-strip-body { grid-template-columns: 200px 1fr; gap: 1.25rem; }
    .vff-strip-legend { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .vff-dash { padding: .85rem .75rem 1.5rem; gap: .75rem; }
    .vff-kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .vff-recent-grid { grid-template-columns: 1fr; }
    .vff-kpi-value { font-size: 1.5rem; }
    .vff-dash-header { padding: 1rem 1.1rem; }
    .vff-dash-title { font-size: 1.05rem; }
    .vff-chart-body { min-height: 200px; max-height: 240px; }
    .vff-chart-body-tall { min-height: 260px; max-height: 320px; }

    .vff-strip-body {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    .vff-strip-chart { height: 160px; }
    .vff-strip-legend { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .vff-kpi-grid { grid-template-columns: 1fr; }
    .vff-role-pill span { display: none; }
    .vff-strip-legend { grid-template-columns: 1fr; }
    .vff-modal-table { font-size: .75rem; }
    .vff-modal-table thead th,
    .vff-modal-table tbody td { padding: .55rem .7rem; }
}

/* ════════════════════════════════════════════════
   ✅ ADAPTATION 1920×1080 @ 125%  (≈ 1536×864 CSS)
════════════════════════════════════════════════ */
@media (min-width: 1400px) and (max-width: 1600px) and (max-height: 900px) {

    .vff-dash {
        padding: .85rem 1rem 1.25rem;
        gap: .75rem;
    }

    .vff-dash-header { padding: .95rem 1.2rem; }
    .vff-dash-header-icon { width: 42px; height: 42px; font-size: 1.15rem; border-radius: 12px; }
    .vff-dash-title { font-size: 1.05rem; }
    .vff-dash-subtitle { font-size: .72rem; }
    .vff-avatar { width: 34px; height: 34px; font-size: .85rem; }
    .vff-role-pill { padding: .35rem .8rem; font-size: .68rem; }

    .vff-kpi-grid { gap: .65rem; }
    .vff-kpi-card { padding: .75rem .85rem; gap: .45rem; }
    .vff-kpi-label { font-size: .65rem; }
    .vff-kpi-value { font-size: 1.5rem; }
    .vff-kpi-icon { width: 34px; height: 34px; font-size: .9rem; border-radius: 9px; }
    .vff-kpi-foot { font-size: .66rem; }

    .vff-chart-card { padding: .9rem 1rem; gap: .6rem; border-radius: 14px; }
    .vff-chart-head { min-height: 26px; padding-bottom: .5rem; }
    .vff-chart-head h2 { font-size: .84rem; }
    .vff-chart-head h2 i { width: 22px; height: 22px; font-size: .72rem; border-radius: 6px; }
    .vff-chart-link { font-size: .68rem; padding: .35rem .75rem; }

    .vff-chart-card-strip { padding: .85rem 1.1rem; }
    .vff-strip-body {
        grid-template-columns: 180px 1fr;
        gap: 1.25rem;
        min-height: 140px;
    }
    .vff-strip-chart { height: 140px; }
    .vff-strip-legend { gap: .55rem; }
    .vff-strip-item { padding: .6rem .7rem; gap: .5rem; }
    .vff-strip-dot { width: 10px; height: 10px; }
    .vff-strip-label { font-size: .6rem; }
    .vff-strip-value { font-size: 1.05rem; }

    .vff-chart-body { min-height: 200px; max-height: 220px; }
    .vff-chart-body-tall { min-height: 220px; max-height: 240px; }

    .vff-recent-grid { gap: .75rem; }
    .vff-recent-card { padding: .85rem .95rem; gap: .5rem; border-radius: 14px; }
    .vff-recent-list { max-height: 170px; gap: .1rem; }
    .vff-recent-item { padding: .38rem .45rem; gap: .55rem; }
    .vff-recent-avatar,
    .vff-recent-icon { width: 28px; height: 28px; font-size: .62rem; }
    .vff-recent-icon { font-size: .72rem; }
    .vff-recent-name { font-size: .72rem; }
    .vff-recent-sub  { font-size: .62rem; }
    .vff-recent-time { font-size: .6rem; }

    .vff-alert { padding: .65rem .9rem; font-size: .78rem; }
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ══════════════════════════════════════════════════════════
       PALETTE STRICTE
    ══════════════════════════════════════════════════════════ */
    const PALETTE = {
        primary:   '#008C95',
        secondary: '#59BEC9',
        purple:    '#9B7EA4',
        brown:     '#C79674',
        salmon:    '#CCA1A6',
        gold:      '#D2B467',
        gray:      '#C4CEC2',
        text:      '#2D2926',
    };

    const SERIES = [
        PALETTE.primary,
        PALETTE.secondary,
        PALETTE.purple,
        PALETTE.brown,
        PALETTE.salmon,
        PALETTE.gold,
        PALETTE.gray,
    ];

    Chart.defaults.font.family = "'Roboto', -apple-system, sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#4A4F4D';
    Chart.defaults.plugins.tooltip = Object.assign(Chart.defaults.plugins.tooltip || {}, {
        backgroundColor: '#FFFFFF',
        titleColor: PALETTE.text,
        bodyColor: PALETTE.text,
        borderColor: '#CDD4D1',
        borderWidth: 1,
        padding: 12,
        cornerRadius: 10,
        titleFont: { family: "'Montserrat', sans-serif", weight: '700', size: 12 },
        bodyFont: { family: "'Roboto', sans-serif", size: 11 },
        displayColors: true,
        boxPadding: 6,
        usePointStyle: true,
        caretSize: 6,
        caretPadding: 8,
    });

    /* ══════════════════════════════════════════════════════════
       1) DOUGHNUT COMPACT — Utilisateurs par rôle (bandeau)
    ══════════════════════════════════════════════════════════ */
    const usersCtx = document.getElementById('usersChart')?.getContext('2d');
    if (usersCtx) {
        const data = [
            {{ $admins ?? 0 }},
            {{ $moderateurs ?? 0 }},
            {{ $moderateur_classique ?? 0 }},
            {{ $usersCount ?? 0 }},
        ];
        const labels = ['Administrateurs', 'Responsables Organismes', 'Responsables Structures', 'Utilisateurs'];
        const colors = [PALETTE.primary, PALETTE.secondary, PALETTE.purple, PALETTE.gold];
        const total = data.reduce((a, b) => a + b, 0);

        const centerText = {
            id: 'centerText',
            afterDraw(chart) {
                const { ctx, chartArea } = chart;
                if (!chartArea) return;
                const x = (chartArea.left + chartArea.right) / 2;
                const y = (chartArea.top + chartArea.bottom) / 2;
                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = "800 24px 'Montserrat', sans-serif";
                ctx.fillStyle = PALETTE.text;
                ctx.fillText(total, x, y - 4);
                ctx.font = "500 10px 'Roboto', sans-serif";
                ctx.fillStyle = '#4A4F4D';
                ctx.fillText('utilisateurs', x, y + 13);
                ctx.restore();
            }
        };

        new Chart(usersCtx, {
            type: 'doughnut',
            plugins: [centerText],
            data: {
                labels,
                datasets: [{
                    data,
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 10,
                    hoverBorderColor: '#FFFFFF',
                    hoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                animation: { duration: 700, easing: 'easeOutQuart' },
                layout: { padding: 6 },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label(ctx) {
                                const v = ctx.parsed;
                                const pct = total ? ((v / total) * 100).toFixed(1) : 0;
                                return `  ${ctx.label} : ${v} (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    /* ══════════════════════════════════════════════════════════
       2) BARRES HORIZONTALES — Top 6 + Autres
    ══════════════════════════════════════════════════════════ */
    const organismesCtx = document.getElementById('organismesChart')?.getContext('2d');
    if (organismesCtx) {
        const rawLabels = {!! json_encode($organismes->pluck('nom_organisme')->toArray() ?? []) !!};
        const rawData   = {!! json_encode($organismeStructures ?? []) !!};

        let pairs = rawLabels.map((label, i) => ({
            label: label ?? 'Sans nom',
            value: rawData[i] ?? 0,
        })).sort((a, b) => b.value - a.value);

        const TOP_N = 6;
        if (pairs.length > TOP_N) {
            const top = pairs.slice(0, TOP_N);
            const othersTotal = pairs.slice(TOP_N).reduce((s, p) => s + p.value, 0);
            const othersCount = pairs.length - TOP_N;
            if (othersTotal > 0 || othersCount > 0) {
                top.push({
                    label: `Autres (${othersCount} organismes)`,
                    value: othersTotal,
                    isOther: true,
                });
            }
            pairs = top;
        }

        const truncate = (str, n = 30) =>
            str && str.length > n ? str.slice(0, n - 1).trimEnd() + '…' : str;

        const labels      = pairs.map(p => truncate(p.label, 30));
        const values      = pairs.map(p => p.value);
        const fullLabels  = pairs.map(p => p.label);

        const backgroundColors = pairs.map((p, i) =>
            p.isOther ? PALETTE.gray : SERIES[i % SERIES.length]
        );

        new Chart(organismesCtx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Structures',
                    data: values,
                    backgroundColor: backgroundColors,
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.72,
                    categoryPercentage: 0.82,
                    maxBarThickness: 24,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 700, easing: 'easeOutQuart' },
                layout: { padding: { top: 4, right: 24, bottom: 4 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title(items) {
                                const i = items[0].dataIndex;
                                return fullLabels[i];
                            },
                            label(ctx) {
                                const v = ctx.parsed.x;
                                return `  ${v} structure${v > 1 ? 's' : ''}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            font: { size: 10, family: "'Roboto', sans-serif" },
                            color: '#4A4F4D',
                            padding: 4,
                        },
                        border: { display: false },
                        grid: { color: 'rgba(45,41,38,.06)', drawTicks: false },
                    },
                    y: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            font: { size: 10.5, family: "'Roboto', sans-serif" },
                            color: '#2D2926',
                            padding: 6,
                            autoSkip: false,
                            crossAlign: 'far',
                        }
                    }
                }
            }
        });
    }

    /* ══════════════════════════════════════════════════════════
       3) LIGNES — Activité (admin)
    ══════════════════════════════════════════════════════════ */
    @if(auth()->user()->role === 'admin')
    const activityCtx = document.getElementById('activityChart')?.getContext('2d');
    if (activityCtx) {

        const fill = (hex) => {
            const g = activityCtx.createLinearGradient(0, 0, 0, 300);
            g.addColorStop(0, hex + '40');
            g.addColorStop(1, hex + '00');
            return g;
        };

        const makeDataset = (label, data, color) => ({
            label,
            data,
            borderColor: color,
            backgroundColor: fill(color),
            borderWidth: 2.5,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#FFFFFF',
            pointBorderColor: color,
            pointBorderWidth: 2.5,
            pointRadius: 4,
            pointHoverRadius: 7,
            pointHoverBorderWidth: 3,
        });

        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($activityLabels) !!},
                datasets: [
                    makeDataset('Connexions',    {!! json_encode($activityConnexions) !!}, PALETTE.primary),
                    makeDataset('Créations',     {!! json_encode($activityCreations) !!},   PALETTE.secondary),
                    makeDataset('Modifications', {!! json_encode($activityUpdates) !!},     PALETTE.gold),
                    makeDataset('Suppressions',  {!! json_encode($activityDeletes) !!},     '#F03E3E'),
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 800, easing: 'easeOutQuart' },
                interaction: { mode: 'index', intersect: false },
                layout: { padding: { top: 8, right: 8 } },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            padding: 14,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 11, family: "'Roboto', sans-serif", weight: '600' },
                            color: PALETTE.text,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label(ctx) {
                                return `  ${ctx.dataset.label} : ${ctx.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            font: { size: 10, family: "'Roboto', sans-serif" },
                            color: '#4A4F4D',
                            padding: 8,
                        },
                        border: { display: false },
                        grid: { color: 'rgba(45,41,38,.06)', drawTicks: false },
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            font: { size: 10, family: "'Roboto', sans-serif" },
                            color: '#4A4F4D',
                            padding: 6,
                        }
                    }
                }
            }
        });
    }
    @endif

    /* ══════════════════════════════════════════════════════════
       4) MODAL — Ouverture / fermeture
    ══════════════════════════════════════════════════════════ */
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-modal-open');
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(el => {
        el.addEventListener('click', () => {
            const modal = el.closest('.vff-modal');
            if (modal) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.vff-modal.is-open').forEach(modal => {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            });
        }
    });
});
</script>
@endsection