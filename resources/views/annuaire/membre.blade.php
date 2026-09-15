@extends('base')

@section('title', 'Annuaire des membres')

@section('head')
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="vff-annuaire">

    {{-- ══════════ EN-TÊTE ══════════ --}}
    <div class="vff-ann-head">
        <div class="vff-ann-head-left">
            <div class="vff-ann-head-icon">
                <i class='bx bx-group'></i>
            </div>
            <div>
                <h1 class="vff-ann-title">Annuaire des membres</h1>
                <p class="vff-ann-subtitle">
                    <i class='bx bx-info-circle'></i>
                    Gestion et consultation des membres
                </p>
            </div>
        </div>
        <div class="vff-ann-head-right">
            <div class="vff-ann-counter">
                <i class='bx bx-user-check'></i>
                <span id="resultsCount">0</span>
                <small>membres</small>
            </div>
            <button onclick="resetAllFilters()" id="resetAllBtn" class="vff-ann-reset is-hidden">
                <i class='bx bx-reset'></i> Réinitialiser
            </button>
        </div>
    </div>

    {{-- ══════════ FILTRES ══════════ --}}
    <div class="vff-ann-filters">
        <div class="vff-ann-filters-row">

            <div class="vff-ann-field vff-ann-field-search">
                <i class='bx bx-search'></i>
                <input type="text" id="search"
                       placeholder="Rechercher par nom, email, fonction, structure, ville...">
            </div>

            <div class="vff-ann-field">
                <i class='bx bx-map'></i>
                <select id="cityFilter">
                    <option value="">Toutes les villes</option>
                    @php
                        $villesUniques = collect();
                        foreach($membres as $membre) {
                            if($membre->structure && $membre->structure->ville) {
                                $ville = trim($membre->structure->ville);
                                if(!empty($ville) && !$villesUniques->has($ville)) {
                                    $villesUniques->put($ville, $ville);
                                }
                            }
                        }
                        $villesUniques = $villesUniques->sort();
                    @endphp
                    @foreach($villesUniques as $ville)
                        <option value="{{ $ville }}">{{ $ville }}</option>
                    @endforeach
                </select>
                <i class='bx bx-chevron-down vff-ann-field-chev'></i>
            </div>

            <div class="vff-ann-field">
                <i class='bx bx-sort-alt-2'></i>
                <select id="sortFilter">
                    <option value="name_asc">Nom (A-Z)</option>
                    <option value="name_desc">Nom (Z-A)</option>
                    <option value="city">Ville</option>
                </select>
                <i class='bx bx-chevron-down vff-ann-field-chev'></i>
            </div>
        </div>

        <div id="activeFilters" class="vff-ann-active-filters"></div>
    </div>

    {{-- ══════════ GRILLE ══════════ --}}
    <div class="vff-ann-grid" id="members-container">
        @foreach($membres as $membre)
            <div class="vff-mcard"
                 data-city="{{ strtolower($membre->structure->ville ?? '') }}"
                 data-name="{{ strtolower($membre->prenom . ' ' . $membre->name) }}"
                 data-email="{{ strtolower($membre->email) }}"
                 data-structure="{{ strtolower($membre->structure->organisme->nom_organisme ?? '') }}">

                {{-- Bandeau supérieur --}}
                <div class="vff-mcard-top"></div>

                <div class="vff-mcard-body">

                    {{-- En-tête --}}
                    <div class="vff-mcard-head">
                        <div class="vff-mcard-avatar">
                            {{ strtoupper(substr($membre->prenom, 0, 1)) }}{{ strtoupper(substr($membre->name, 0, 1)) }}
                        </div>
                        <div class="vff-mcard-head-txt">
                            <h2 class="vff-mcard-name">{{ $membre->prenom }} {{ $membre->name }}</h2>
                            <p class="vff-mcard-fonction">
                                <i class='bx bx-briefcase-alt-2'></i>
                                {{ $membre->fonction ?? 'Fonction non renseignée' }}
                            </p>
                        </div>
                    </div>

                    {{-- Infos --}}
                    <div class="vff-mcard-info">
                        <div class="vff-mcard-line">
                            <span class="vff-mcard-line-icon" style="background:#008C95;">
                                <i class='bx bx-envelope'></i>
                            </span>
                            <span class="vff-mcard-line-txt">{{ $membre->email }}</span>
                        </div>
                        <div class="vff-mcard-line">
                            <span class="vff-mcard-line-icon" style="background:#008C95;">
                                <i class='bx bx-phone'></i>
                            </span>
                            <span class="vff-mcard-line-txt">{{ $membre->phone ?? 'Non renseigné' }}</span>
                        </div>
                        <div class="vff-mcard-line">
                            <span class="vff-mcard-line-icon" style="background:#008C95;">
                                <i class='bx bx-building'></i>
                            </span>
                            <span class="vff-mcard-line-txt vff-mcard-line-strong">
                                {{ $membre->structure->organisme->nom_organisme ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="vff-mcard-line">
                            <span class="vff-mcard-line-icon" style="background:#008C95;">
                                <i class='fas fa-map'></i>
                            </span>
                            <span class="vff-mcard-line-txt vff-mcard-line-strong">
                                {{ $membre->structure->ville ?? 'Ville non renseignée' }}
                            </span>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="vff-mcard-foot">
                        <div class="vff-mcard-badges">
                            <span class="vff-badge vff-badge-active">
                                <span class="vff-badge-dot"></span>
                                Actif
                            </span>
                            @if($membre->structure && $membre->structure->ville)
                                <span class="vff-badge vff-badge-city">
                                    <i class='bx bx-map'></i>
                                    {{ $membre->structure->ville }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ══════════ AUCUN RÉSULTAT ══════════ --}}
    <div id="no-results" class="vff-ann-empty" style="display:none;">
        <div class="vff-ann-empty-card">
            <i class='bx bx-user-x'></i>
            <h3>Aucun membre trouvé</h3>
            <p>Aucun membre ne correspond à vos critères de recherche</p>
            <button onclick="resetAllFilters()" class="vff-ann-reset-btn">
                <i class='bx bx-reset'></i> Réinitialiser les filtres
            </button>
        </div>
    </div>

    {{-- ══════════ PAGINATION ══════════ --}}
    @if(method_exists($membres, 'links'))
        <div class="vff-ann-pagination">
            {{ $membres->links() }}
        </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════════
     STYLE
══════════════════════════════════════════════════════════════ --}}
<style>
:root {
    /* ═══ PALETTE STRICTE ═══ */
    --vff-primary:   #008C95;
    --vff-secondary: #59BEC9;
    --vff-purple:    #9B7EA4;
    --vff-brown:     #C79674;
    --vff-salmon:    #CCA1A6;
    --vff-gold:      #D2B467;
    --vff-gray:      #C4CEC2;
    --vff-text:      #2D2926;

    /* ═══ UI NETTE ET CONTRASTÉE ═══ */
    --bg:        #F2F4F3;
    --card:      #FFFFFF;
    --border:    #DDE2E0;
    --border-str:#CDD4D1;
    --muted:     #6B706E;
    --muted-str: #4A4F4D;
    --radius:    14px;
    --radius-sm: 10px;
    --shadow-sm: 0 1px 3px rgba(45,41,38,.08), 0 2px 6px rgba(45,41,38,.05);
    --shadow-md: 0 4px 14px rgba(45,41,38,.10), 0 8px 24px rgba(45,41,38,.08);
    --ease:      cubic-bezier(.4,0,.2,1);
}

/* ═══ WRAPPER ═══ */
.vff-annuaire {
    max-width: 1600px;
    margin: 0 auto;
    padding: 1.25rem 1.25rem 2rem;
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--vff-text);
    background: var(--bg);
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-height: 100vh;
}

.vff-annuaire h1,
.vff-annuaire h2,
.vff-annuaire h3 {
    font-family: 'Montserrat', -apple-system, sans-serif;
    letter-spacing: -0.015em;
}

/* ═══════════════════════════════════════════════
   EN-TÊTE
═══════════════════════════════════════════════ */
.vff-ann-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-radius: var(--radius);
    color: #fff;
    background: linear-gradient(135deg, #256156 0%, #008C95 50%, #046B73 100%);
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}
.vff-ann-head::after {
    content: '';
    position: absolute;
    top: -40%; right: -5%;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,.15), transparent 60%);
    pointer-events: none;
}
.vff-ann-head-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative; z-index: 1;
}
.vff-ann-head-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.22);
    border: 1px solid rgba(255,255,255,.28);
    font-size: 1.5rem;
    color: #fff;
}
.vff-ann-title {
    font-size: 1.25rem;
    font-weight: 800;
    margin: 0;
    color: #fff;
}
.vff-ann-subtitle {
    font-size: .78rem;
    margin: .15rem 0 0;
    color: rgba(255,255,255,.92);
    font-family: 'Roboto', sans-serif;
}
.vff-ann-subtitle i { margin-right: .25rem; }
.vff-ann-head-right {
    display: flex; align-items: center; gap: .65rem;
    position: relative; z-index: 1;
}
.vff-ann-counter {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .55rem 1rem;
    border-radius: 999px;
    background: rgba(255,255,255,.22);
    border: 1px solid rgba(255,255,255,.3);
    font-family: 'Montserrat', sans-serif;
    color: #fff;
}
.vff-ann-counter i { font-size: 1.1rem; }
.vff-ann-counter span { font-weight: 800; font-size: 1rem; }
.vff-ann-counter small {
    font-size: .72rem;
    font-weight: 500;
    opacity: .9;
    font-family: 'Roboto', sans-serif;
}

.vff-ann-reset {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .5rem .9rem;
    border-radius: 9px;
    background: #FFFFFF;
    color: var(--vff-primary);
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: .72rem;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,.15);
    transition: transform .2s var(--ease);
}
.vff-ann-reset:hover { transform: translateY(-1px); }
.vff-ann-reset.is-hidden { display: none; }

/* ═══════════════════════════════════════════════
   FILTRES
═══════════════════════════════════════════════ */
.vff-ann-filters {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem 1.15rem;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    gap: .75rem;
}
.vff-ann-filters-row {
    display: grid;
    grid-template-columns: 1fr 240px 200px;
    gap: .75rem;
}
.vff-ann-field {
    position: relative;
    display: flex;
    align-items: center;
}
.vff-ann-field > i:first-child {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--vff-primary);
    font-size: 1.15rem;
    pointer-events: none;
    z-index: 1;
}
.vff-ann-field-chev {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 1.15rem;
    pointer-events: none;
}
.vff-ann-field input,
.vff-ann-field select {
    width: 100%;
    padding: .8rem 1rem .8rem 2.75rem;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-str);
    background: #F9FAFA;
    color: var(--vff-text);
    font-size: .88rem;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    transition: border-color .2s var(--ease), box-shadow .2s var(--ease), background .2s;
    outline: none;
    appearance: none;
    cursor: pointer;
}
.vff-ann-field input { cursor: text; }
.vff-ann-field input::placeholder { color: var(--muted); font-weight: 400; }
.vff-ann-field input:focus,
.vff-ann-field select:focus {
    border-color: var(--vff-primary);
    background: #FFFFFF;
    box-shadow: 0 0 0 4px rgba(0,140,149,.14);
}

/* Filtres actifs */
.vff-ann-active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
    min-height: 0;
}
.vff-ann-active-filters:empty { display: none; }
.vff-filter-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .35rem .75rem;
    border-radius: 999px;
    background: #E6F4F5;
    color: #005C63;
    border: 1px solid var(--vff-primary);
    font-size: .74rem;
    font-weight: 600;
    font-family: 'Montserrat', sans-serif;
}
.vff-filter-badge button {
    background: transparent;
    border: none;
    cursor: pointer;
    color: inherit;
    padding: 0;
    display: inline-flex;
    align-items: center;
    font-size: 1rem;
    transition: transform .15s var(--ease);
}
.vff-filter-badge button:hover { transform: scale(1.2); }

/* ═══════════════════════════════════════════════
   GRILLE MEMBRES
═══════════════════════════════════════════════ */
.vff-ann-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
}

/* ═══ CARTE MEMBRE ═══ */
.vff-mcard {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform .25s var(--ease), box-shadow .25s var(--ease), border-color .25s var(--ease);
    position: relative;
    cursor: pointer;
}
.vff-mcard:hover {
    transform: translateY(-3px);
    border-color: var(--vff-primary);
    box-shadow: var(--shadow-md);
}

/* Bandeau supérieur coloré (palette) */
.vff-mcard-top {
    height: 5px;
    background: linear-gradient(90deg,
        var(--vff-primary) 0%,
        var(--vff-secondary) 50%,
        var(--vff-purple) 100%);
    background-size: 200% 100%;
    transition: background-position .5s var(--ease);
}
.vff-mcard:hover .vff-mcard-top {
    background-position: -100% 0;
}

.vff-mcard-body {
    padding: .9rem 1rem 1rem;
    display: flex;
    flex-direction: column;
    gap: .8rem;
    flex: 1;
}

/* En-tête (avatar + nom) */
.vff-mcard-head {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.vff-mcard-avatar {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--vff-primary), var(--vff-secondary));
    color: #FFFFFF;
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: 1rem;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(0,140,149,.28);
    transition: transform .3s var(--ease);
}
.vff-mcard:hover .vff-mcard-avatar {
    transform: scale(1.06) rotate(-3deg);
}
.vff-mcard-head-txt { flex: 1; min-width: 0; }
.vff-mcard-name {
    font-size: .92rem;
    font-weight: 800;
    color: var(--vff-text);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.vff-mcard-fonction {
    font-size: .7rem;
    color: var(--muted-str);
    margin: .15rem 0 0;
    display: flex;
    align-items: center;
    gap: .3rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: 'Roboto', sans-serif;
}
.vff-mcard-fonction i { color: var(--vff-primary); font-size: .8rem; }

/* Lignes d'info */
.vff-mcard-info {
    display: flex;
    flex-direction: column;
    gap: .4rem;
}
.vff-mcard-line {
    display: flex;
    align-items: center;
    gap: .55rem;
    padding: .4rem .55rem;
    background: #F8FAFA;
    border-radius: 8px;
    border: 1px solid #F0F3F2;
}
.vff-mcard-line-icon {
    width: 22px; height: 22px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    color: #FFFFFF;
    font-size: .72rem;
    flex-shrink: 0;
}
.vff-mcard-line-txt {
    font-size: .74rem;
    color: var(--muted-str);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: 'Roboto', sans-serif;
    min-width: 0;
}
.vff-mcard-line-strong {
    color: var(--vff-text);
    font-weight: 600;
}

/* Footer */
.vff-mcard-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .5rem;
    padding-top: .7rem;
    border-top: 1px solid var(--border);
    margin-top: auto;
}
.vff-mcard-badges {
    display: flex;
    align-items: center;
    gap: .35rem;
    flex-wrap: wrap;
}
.vff-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .25rem .6rem;
    border-radius: 999px;
    font-size: .65rem;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap;
}
.vff-badge-active {
    background: #E6F4F5;
    color: #005C63;
}
.vff-badge-active .vff-badge-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--vff-primary);
    animation: vffPulse 2s infinite;
}
.vff-badge-city {
    background: #F5F0F8;
    color: #6A4F72;
}
.vff-badge-city i { font-size: .75rem; }

.vff-mcard-see {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    font-size: .72rem;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    color: var(--vff-primary);
    opacity: 0;
    transform: translateX(-6px);
    transition: opacity .25s var(--ease), transform .25s var(--ease);
}
.vff-mcard:hover .vff-mcard-see {
    opacity: 1;
    transform: translateX(0);
}

@keyframes vffPulse {
    0%, 100% { opacity: 1; }
    50%      { opacity: .4; }
}

/* ═══════════════════════════════════════════════
   AUCUN RÉSULTAT
═══════════════════════════════════════════════ */
.vff-ann-empty {
    display: flex;
    justify-content: center;
    padding: 2rem 1rem;
}
.vff-ann-empty-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 2rem 2.5rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
    max-width: 420px;
}
.vff-ann-empty-card i {
    font-size: 3.5rem;
    color: var(--vff-gray);
}
.vff-ann-empty-card h3 {
    margin: .75rem 0 .35rem;
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--vff-text);
}
.vff-ann-empty-card p {
    margin: 0 0 1.2rem;
    font-size: .82rem;
    color: var(--muted);
}
.vff-ann-reset-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .65rem 1.1rem;
    border-radius: 10px;
    background: var(--vff-primary);
    color: #FFFFFF;
    border: none;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: .78rem;
    transition: background .2s var(--ease), transform .15s var(--ease);
}
.vff-ann-reset-btn:hover {
    background: #00707A;
    transform: translateY(-1px);
}

/* ═══════════════════════════════════════════════
   PAGINATION
═══════════════════════════════════════════════ */
.vff-ann-pagination {
    margin-top: .5rem;
    display: flex;
    justify-content: center;
}
.vff-ann-pagination .pagination {
    display: flex;
    gap: .35rem;
    flex-wrap: wrap;
    list-style: none;
    padding: 0;
    margin: 0;
}
.vff-ann-pagination .page-item { list-style: none; }
.vff-ann-pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 .75rem;
    border-radius: 9px;
    background: #FFFFFF;
    color: var(--vff-text);
    text-decoration: none;
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    font-size: .82rem;
    border: 1px solid var(--border-str);
    transition: all .2s var(--ease);
}
.vff-ann-pagination .page-link:hover {
    background: var(--vff-primary);
    color: #FFFFFF;
    border-color: var(--vff-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,140,149,.25);
}
.vff-ann-pagination .active .page-link {
    background: var(--vff-primary);
    color: #FFFFFF;
    border-color: var(--vff-primary);
    box-shadow: 0 4px 12px rgba(0,140,149,.25);
}
.vff-ann-pagination .disabled .page-link {
    color: var(--muted);
    background: #F4F6F5;
    cursor: not-allowed;
    opacity: .6;
}

/* ═══════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════ */
@media (max-width: 1400px) {
    .vff-ann-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
@media (max-width: 1100px) {
    .vff-ann-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .vff-ann-filters-row { grid-template-columns: 1fr 1fr; }
    .vff-ann-field-search { grid-column: 1 / -1; }
}
@media (max-width: 640px) {
    .vff-annuaire { padding: .85rem .75rem 1.5rem; gap: .75rem; }
    .vff-ann-grid { grid-template-columns: 1fr; }
    .vff-ann-head { padding: 1rem 1.1rem; }
    .vff-ann-title { font-size: 1.05rem; }
    .vff-ann-filters-row { grid-template-columns: 1fr; }
    .vff-ann-counter small { display: none; }
}

/* ════════════════════════════════════════════════════════════
   ✅ ADAPTATION 1920×1080 @ 125%  (≈ 1536×864 CSS)
   → 5 colonnes, tout reste compact et lisible à l'écran
════════════════════════════════════════════════════════════ */
@media (min-width: 1400px) and (max-width: 1600px) and (max-height: 900px) {

    .vff-annuaire {
        padding: .85rem 1rem 1.25rem;
        gap: .75rem;
    }

    /* Grille : 5 colonnes en 1536×864 */
    .vff-ann-grid {
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: .7rem;
    }

    /* En-tête compact */
    .vff-ann-head { padding: .9rem 1.2rem; }
    .vff-ann-head-icon { width: 42px; height: 42px; font-size: 1.3rem; border-radius: 12px; }
    .vff-ann-title { font-size: 1.05rem; }
    .vff-ann-subtitle { font-size: .72rem; }
    .vff-ann-counter { padding: .45rem .85rem; }
    .vff-ann-counter span { font-size: .9rem; }
    .vff-ann-counter small { font-size: .68rem; }

    /* Filtres compacts */
    .vff-ann-filters { padding: .8rem 1rem; gap: .55rem; }
    .vff-ann-filters-row { grid-template-columns: 1fr 200px 170px; gap: .6rem; }
    .vff-ann-field input,
    .vff-ann-field select {
        padding: .65rem .9rem .65rem 2.5rem;
        font-size: .82rem;
    }
    .vff-ann-field > i:first-child { font-size: 1rem; left: 12px; }
    .vff-ann-field-chev { font-size: 1rem; right: 12px; }

    /* Cartes membres compactes */
    .vff-mcard-body { padding: .75rem .8rem .85rem; gap: .6rem; }
    .vff-mcard-avatar { width: 40px; height: 40px; font-size: .88rem; border-radius: 10px; }
    .vff-mcard-name { font-size: .84rem; }
    .vff-mcard-fonction { font-size: .64rem; }

    .vff-mcard-info { gap: .3rem; }
    .vff-mcard-line { padding: .32rem .45rem; gap: .45rem; }
    .vff-mcard-line-icon { width: 19px; height: 19px; font-size: .64rem; border-radius: 5px; }
    .vff-mcard-line-txt { font-size: .68rem; }

    .vff-mcard-foot { padding-top: .55rem; gap: .4rem; }
    .vff-badge { padding: .2rem .5rem; font-size: .58rem; }
    .vff-badge-city i { font-size: .68rem; }
    .vff-mcard-see { font-size: .66rem; }

    /* Pagination compacte */
    .vff-ann-pagination .page-link {
        min-width: 34px;
        height: 34px;
        font-size: .78rem;
    }

    /* Empty state */
    .vff-ann-empty-card { padding: 1.5rem 2rem; }
    .vff-ann-empty-card i { font-size: 3rem; }
    .vff-ann-empty-card h3 { font-size: 1rem; }
    .vff-ann-empty-card p  { font-size: .78rem; }
}

/* Très grands écrans (>1600px) : 5 colonnes aussi */
@media (min-width: 1600px) {
    .vff-ann-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }
}

/* Scrollbar douce et discrète */
.vff-annuaire ::-webkit-scrollbar { width: 6px; height: 6px; }
.vff-annuaire ::-webkit-scrollbar-track { background: transparent; }
.vff-annuaire ::-webkit-scrollbar-thumb {
    background: var(--vff-gray);
    border-radius: 6px;
}
.vff-annuaire ::-webkit-scrollbar-thumb:hover { background: var(--vff-secondary); }
</style>

{{-- ══════════════════════════════════════════════════════════════
     SCRIPT
══════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('search');
    const cityFilter  = document.getElementById('cityFilter');
    const sortFilter  = document.getElementById('sortFilter');
    const memberCards = document.querySelectorAll('.vff-mcard');
    const noResults   = document.getElementById('no-results');
    const activeFiltersDiv = document.getElementById('activeFilters');
    const resultsCountSpan = document.getElementById('resultsCount');
    const resetAllBtn = document.getElementById('resetAllBtn');

    let currentFilters = { search: '', city: '', sort: 'name_asc' };
    let searchTimeout = null;

    /* ═══ TRI ═══ */
    function sortMembers(sortType) {
        const container = document.getElementById('members-container');
        const cards = Array.from(memberCards);

        cards.sort((a, b) => {
            const nameA = a.dataset.name || '';
            const nameB = b.dataset.name || '';
            const cityA = a.dataset.city || '';
            const cityB = b.dataset.city || '';
            switch(sortType) {
                case 'name_asc':  return nameA.localeCompare(nameB);
                case 'name_desc': return nameB.localeCompare(nameA);
                case 'city':      return cityA.localeCompare(cityB);
                default:          return 0;
            }
        });
        cards.forEach(card => container.appendChild(card));
    }

    /* ═══ BADGES DE FILTRES ACTIFS ═══ */
    function updateActiveFilters() {
        activeFiltersDiv.innerHTML = '';
        let hasFilters = false;

        if (currentFilters.city) {
            const opt = cityFilter.options[cityFilter.selectedIndex];
            addFilterBadge('📍 ' + opt.text, () => {
                cityFilter.value = '';
                currentFilters.city = '';
                applyFilters();
            });
            hasFilters = true;
        }
        if (currentFilters.search) {
            addFilterBadge('🔍 "' + currentFilters.search + '"', () => {
                searchInput.value = '';
                currentFilters.search = '';
                applyFilters();
            });
            hasFilters = true;
        }
        resetAllBtn.classList.toggle('is-hidden', !hasFilters);
    }

    function addFilterBadge(text, onRemove) {
        const badge = document.createElement('span');
        badge.className = 'vff-filter-badge';
        badge.innerHTML = `${text} <button aria-label="Retirer"><i class='bx bx-x'></i></button>`;
        badge.querySelector('button').addEventListener('click', onRemove);
        activeFiltersDiv.appendChild(badge);
    }

    /* ═══ APPLICATION DES FILTRES ═══ */
    function applyFilters() {
        currentFilters.search = searchInput.value.toLowerCase().trim();
        currentFilters.city   = cityFilter.value;
        currentFilters.sort   = sortFilter.value;

        let visibleCount = 0;
        const searchTerms = currentFilters.search.split(/\s+/).filter(t => t.length > 0);

        memberCards.forEach(card => {
            const name      = card.dataset.name || '';
            const email     = card.dataset.email || '';
            const structure = card.dataset.structure || '';
            const city      = card.dataset.city || '';
            const textContent = card.textContent.toLowerCase();

            let matchSearch = !currentFilters.search;
            if (currentFilters.search) {
                matchSearch = searchTerms.every(term =>
                    name.includes(term) ||
                    email.includes(term) ||
                    structure.includes(term) ||
                    city.includes(term) ||
                    textContent.includes(term)
                );
            }
            const matchCity = !currentFilters.city ||
                             city === currentFilters.city.toLowerCase();

            if (matchSearch && matchCity) {
                card.style.display = '';
                visibleCount++;
                card.style.opacity = '0';
                card.style.transform = 'scale(.95) translateY(8px)';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1) translateY(0)';
                }, 40 + visibleCount * 18);
            } else {
                card.style.display = 'none';
            }
        });

        sortMembers(currentFilters.sort);
        resultsCountSpan.textContent = visibleCount;
        noResults.style.display = visibleCount > 0 ? 'none' : 'flex';
        updateActiveFilters();
    }

    /* ═══ RÉINITIALISATION ═══ */
    function resetAllFilters() {
        searchInput.value = '';
        cityFilter.value = '';
        sortFilter.value = 'name_asc';
        currentFilters = { search: '', city: '', sort: 'name_asc' };
        applyFilters();
        searchInput.focus();
    }
    window.resetAllFilters = resetAllFilters;

    /* ═══ ÉVÉNEMENTS ═══ */
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 200);
    });
    cityFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            resetAllFilters();
            searchInput.blur();
        }
    });

    /* ═══ INITIALISATION ═══ */
    applyFilters();
});
</script>
@endsection