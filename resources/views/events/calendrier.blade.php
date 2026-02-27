@extends('base')

@section('title', 'Calendrier des événements')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">
            <i class="fas fa-calendar-alt me-2"></i>
            Calendrier des événements
        </h1>
        <div>
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-list me-1"></i>Vue liste
            </a>
            @can('create', App\Models\Event::class)
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Nouvel événement
            </a>
            @endcan
        </div>
    </div>

    <!-- Filtres et légende -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <span class="fw-bold me-2">Filtrer par type :</span>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary filter-btn active" data-type="all">
                                Tous
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-btn" data-type="réunion">
                                <span class="badge rounded-circle p-2 me-1" style="background-color: #3b82f6"></span>
                                Réunions
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-btn" data-type="formation">
                                <span class="badge rounded-circle p-2 me-1" style="background-color: #22c55e"></span>
                                Formations
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-btn" data-type="atelier">
                                <span class="badge rounded-circle p-2 me-1" style="background-color: #a855f7"></span>
                                Ateliers
                            </button>
                            <button type="button" class="btn btn-outline-primary filter-btn" data-type="autre">
                                <span class="badge rounded-circle p-2 me-1" style="background-color: #6b7280"></span>
                                Autres
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold">Navigation :</span>
                        </div>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-secondary" id="prevMonth">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" id="today">
                                Aujourd'hui
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" id="nextMonth">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteneur principal -->
    <div class="row">
        <!-- Calendrier -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Cartes des événements du jour -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-day me-2"></i>
                        Événements du <span id="selectedDate"></span>
                    </h5>
                </div>
                <div class="card-body" id="eventsCards" style="max-height: 600px; overflow-y: auto;">
                    <!-- Les cartes seront chargées dynamiquement ici -->
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-calendar-plus fa-3x mb-3"></i>
                        <p>Sélectionnez une date pour voir les événements</p>
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Aperçu rapide
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="quickStats">
                        <!-- Les stats seront chargées dynamiquement -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
    #calendar {
        min-height: 600px;
        padding: 15px;
    }
    
    .fc-event {
        cursor: pointer;
        transition: transform 0.2s;
        border: none !important;
        padding: 2px 4px !important;
    }
    
    .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .fc-day-today {
        background-color: rgba(59, 130, 246, 0.05) !important;
    }
    
    .fc-day-selected {
        background-color: rgba(59, 130, 246, 0.1) !important;
    }
    
    .event-card {
        transition: all 0.2s;
        border-left: 4px solid transparent;
        margin-bottom: 12px;
    }
    
    .event-card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .event-card.reunion { border-left-color: #3b82f6; }
    .event-card.formation { border-left-color: #22c55e; }
    .event-card.atelier { border-left-color: #a855f7; }
    .event-card.autre { border-left-color: #6b7280; }
    
    .filter-btn.active {
        background-color: #0d6efd;
        color: white;
    }
    
    .filter-btn.active .badge {
        opacity: 0.8;
    }
    
    .badge-type {
        width: 12px;
        height: 12px;
        display: inline-block;
        border-radius: 50%;
        margin-right: 6px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const events = @json($events);
    let calendar;
    let currentFilter = 'all';
    let selectedDate = null;

    // Initialisation du calendrier
    const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: false, // On utilise nos propres boutons
        events: events,
        eventClick: function(info) {
            showEventDetails(info.event.id);
        },
        dateClick: function(info) {
            selectedDate = info.dateStr;
            document.getElementById('selectedDate').textContent = 
                new Date(info.dateStr).toLocaleDateString('fr-FR', {
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric'
                });
            
            // Mettre en évidence le jour sélectionné
            document.querySelectorAll('.fc-day').forEach(day => {
                day.classList.remove('fc-day-selected');
            });
            info.dayEl.classList.add('fc-day-selected');
            
            loadDayEvents(info.dateStr);
            updateQuickStats();
        },
        eventDidMount: function(info) {
            // Ajouter un tooltip
            info.el.setAttribute('title', info.event.title);
            
            // Appliquer le filtre initial
            if (currentFilter !== 'all' && info.event.extendedProps.type !== currentFilter) {
                info.el.style.display = 'none';
            }
        }
    });

    calendar.render();

    // Navigation personnalisée
    document.getElementById('prevMonth').addEventListener('click', function() {
        calendar.prev();
    });
    
    document.getElementById('nextMonth').addEventListener('click', function() {
        calendar.next();
    });
    
    document.getElementById('today').addEventListener('click', function() {
        calendar.today();
    });

    // Gestion des filtres
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Mise à jour du bouton actif
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Application du filtre
            currentFilter = this.dataset.type;
            filterEvents(currentFilter);
            
            // Recharger les événements du jour si une date est sélectionnée
            if (selectedDate) {
                loadDayEvents(selectedDate);
            }
            updateQuickStats();
        });
    });

    // Fonction de filtrage des événements
    function filterEvents(type) {
        const fcEvents = document.querySelectorAll('.fc-event');
        fcEvents.forEach(event => {
            const eventType = event.getAttribute('data-type');
            if (type === 'all' || eventType === type) {
                event.style.display = 'block';
            } else {
                event.style.display = 'none';
            }
        });
    }

    // Charger les événements d'une date spécifique
    function loadDayEvents(date) {
        const eventsContainer = document.getElementById('eventsCards');
        
        // Filtrer les événements de la date sélectionnée
        const dayEvents = events.filter(event => {
            const eventDate = event.start.split('T')[0];
            return eventDate === date;
        });

        if (dayEvents.length === 0) {
            eventsContainer.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-calendar-check fa-3x mb-3"></i>
                    <p>Aucun événement prévu à cette date</p>
                    <a href="{{ route('events.create') }}" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="fas fa-plus me-1"></i>Créer un événement
                    </a>
                </div>
            `;
            return;
        }

        // Trier les événements par heure
        dayEvents.sort((a, b) => a.start.localeCompare(b.start));

        // Appliquer le filtre
        const filteredEvents = currentFilter === 'all' 
            ? dayEvents 
            : dayEvents.filter(e => e.type === currentFilter);

        if (filteredEvents.length === 0) {
            eventsContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-filter fa-3x mb-3"></i>
                    <p>Aucun événement correspondant au filtre</p>
                </div>
            `;
            return;
        }

        // Générer les cartes
        let cardsHtml = '';
        filteredEvents.forEach(event => {
            const startTime = new Date(event.start).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });
            const endTime = new Date(event.end).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });

            cardsHtml += `
                <div class="card event-card ${event.type}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0">
                                <span class="badge-type" style="background-color: ${event.color}"></span>
                                ${event.title}
                            </h6>
                            <span class="badge bg-${getBadgeColor(event.type)}">${event.type}</span>
                        </div>
                        <p class="small text-muted mb-2">
                            <i class="far fa-clock me-1"></i>${startTime} - ${endTime}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small>
                                <i class="fas fa-users me-1"></i>
                                ${event.participants_count || 0} participant(s)
                            </small>
                            <a href="/events/${event.id}" class="btn btn-sm btn-outline-primary">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            `;
        });

        eventsContainer.innerHTML = cardsHtml;
    }

    // Mettre à jour les statistiques rapides
    function updateQuickStats() {
        const today = new Date().toISOString().split('T')[0];
        const thisMonth = new Date().toISOString().slice(0, 7);
        
        const stats = {
            today: events.filter(e => e.start.startsWith(today)).length,
            week: events.filter(e => {
                const eventDate = new Date(e.start);
                const today = new Date();
                const weekStart = new Date(today.setDate(today.getDate() - today.getDay()));
                const weekEnd = new Date(today.setDate(today.getDate() - today.getDay() + 6));
                return eventDate >= weekStart && eventDate <= weekEnd;
            }).length,
            month: events.filter(e => e.start.startsWith(thisMonth)).length,
            total: events.length
        };

        document.getElementById('quickStats').innerHTML = `
            <div class="col-6">
                <div class="border rounded p-3 text-center">
                    <div class="h4 mb-1">${stats.today}</div>
                    <small class="text-muted">Aujourd'hui</small>
                </div>
            </div>
            <div class="col-6">
                <div class="border rounded p-3 text-center">
                    <div class="h4 mb-1">${stats.week}</div>
                    <small class="text-muted">Cette semaine</small>
                </div>
            </div>
            <div class="col-6">
                <div class="border rounded p-3 text-center">
                    <div class="h4 mb-1">${stats.month}</div>
                    <small class="text-muted">Ce mois</small>
                </div>
            </div>
            <div class="col-6">
                <div class="border rounded p-3 text-center">
                    <div class="h4 mb-1">${stats.total}</div>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        `;
    }

    // Fonction utilitaire pour les couleurs des badges
    function getBadgeColor(type) {
        const colors = {
            'réunion': 'primary',
            'formation': 'success',
            'atelier': 'purple',
            'autre': 'secondary'
        };
        return colors[type] || 'secondary';
    }

    // Initialiser avec la date du jour
    const today = new Date().toISOString().split('T')[0];
    selectedDate = today;
    document.getElementById('selectedDate').textContent = 
        new Date().toLocaleDateString('fr-FR', {
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric'
        });
    loadDayEvents(today);
    updateQuickStats();
});
</script>
@endpush