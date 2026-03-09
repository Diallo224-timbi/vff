@extends('base')
@section('title', 'Calendrier des événements')
@section('content')

<style>
    /* Variables de couleurs modernisées */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --reunion-color: #3b82f6;
        --formation-color: #10b981;
        --atelier-color: #f59e0b;
        --autre-color: #ef4444;
        --hover-lift: translateY(-5px);
        --shadow-hover: 0 15px 35px -5px rgba(0,0,0,0.3);
        --bg-pattern: radial-gradient(circle at 25% 25%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                     radial-gradient(circle at 75% 75%, rgba(118, 75, 162, 0.05) 0%, transparent 50%);
    }

    body {
        background: var(--bg-pattern), #f8fafc;
        font-family: 'Inter', sans-serif;
    }
</style>

<!-- En-tête avec statistiques animées -->
<div class="container-fluid py-0">
    <!-- En-tête avec titre et boutons -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center mb-2 mb-sm-0">
                    <div class="rounded-circle bg-primary bg-gradient p-3 me-3 shadow-lg">
                        <i class="fas fa-calendar-alt fa-2x text-white"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1 fw-bold bg-gradient-primary text-gradient"><i class="bx bx-calendar"></i> Calendrier des événements</h1>
                        <p class="text-muted mb-0">
                            <i class="bx bx-info-circle"></i>
                            <span id="totalEventsCount">{{ count($events) }}</span> événements programmés
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('events.index') }}" class="btn btn-outline-primary rounded-pill px-2 shadow-sm hover-lift">
                        <i class="fas fa-list me-2"></i>Vue liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Légende interactive -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex flex-wrap gap-3">
                            <span class="badge-filter" data-type="all">
                                <span class="badge bg-secondary me-2">●</span>
                                Tous
                            </span>
                            <span class="badge-filter" data-type="réunion">
                                <span class="badge" style="background-color: var(--reunion-color);">●</span>
                                Réunion
                            </span>
                            <span class="badge-filter" data-type="formation">
                                <span class="badge" style="background-color: var(--formation-color);">●</span>
                                Formation
                            </span>
                            <span class="badge-filter" data-type="atelier">
                                <span class="badge" style="background-color: var(--atelier-color);">●</span>
                                Atelier
                            </span>
                            <span class="badge-filter" data-type="autre">
                                <span class="badge" style="background-color: var(--autre-color);">●</span>
                                Autre
                            </span>
                        </div>
                        <div class="mt-2 mt-sm-0">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-secondary refresh-calendar" title="Rafraîchir">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="calendar.today()" title="Aujourd'hui">
                                    <i class="fas fa-calendar-day"></i>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="calendar.changeView('dayGridMonth')" title="Vue mois">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="calendar.changeView('timeGridWeek')" title="Vue semaine">
                                    <i class="fas fa-calendar-week"></i>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="calendar.changeView('listWeek')" title="Vue liste">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier principal -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg animate__animated animate__fadeInUp">
                <div class="card-body p-0 p-md-2">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les détails d'événement -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 bg-gradient-primary text-white">
                <h5 class="modal-title" id="eventModalTitle">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Détails de l'événement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="eventModalBody">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Fermer
                </button>
                <a href="#" id="eventModalLink" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-eye me-2"></i>Voir détails
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Inclusion des bibliothèques -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/fr.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les événements
    var events = @json($events);
    console.log('Événements chargés:', events);

    if (!Array.isArray(events)) {
        console.error('Les événements ne sont pas au format attendu');
        events = [];
    }

    // Configuration du calendrier
    var calendarEl = document.getElementById('calendar');
    window.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        
        // Personnalisation de l'en-tête
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste'
        },
        
        // Événements avec couleurs personnalisées
        events: events.map(event => ({
            ...event,
            backgroundColor: getEventColor(event.type),
            borderColor: getEventColor(event.type),
            textColor: '#ffffff'
        })),
        
        // Options avancées
        height: 'auto',
        contentHeight: 'auto',
        displayEventTime: true,
        displayEventEnd: true,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        },
        
        // Animation des événements
        eventDidMount: function(info) {
            // Tooltip personnalisé avec popper.js
            if (info.event.title) {
                const tooltipContent = `
                    <div class="custom-tooltip">
                        <strong>${info.event.title}</strong><br>
                        <small>${info.event.extendedProps.type || 'Événement'}</small><br>
                        ${info.event.extendedProps.location ? `<small><i class="fas fa-map-marker-alt"></i> ${info.event.extendedProps.location}</small><br>` : ''}
                        <small><i class="far fa-clock"></i> ${info.event.start.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</small>
                    </div>
                `;
                
                // Utiliser Bootstrap tooltip
                new bootstrap.Tooltip(info.el, {
                    title: tooltipContent,
                    html: true,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body',
                    delay: {show: 200, hide: 100}
                });
            }
            
            // Animation au survol
            info.el.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02)';
                this.style.zIndex = '1000';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.3)';
            });
            
            info.el.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.zIndex = 'auto';
                this.style.boxShadow = 'none';
            });
        },
        
        // Clic sur événement
        eventClick: function(info) {
            showEventModal(info.event);
            info.jsEvent.preventDefault();
        },
        
        // Clic sur une date
        dateClick: function(info) {
            // Animation sur la cellule cliquée
            info.dayEl.style.transition = 'background-color 0.3s';
            info.dayEl.style.backgroundColor = 'rgba(102, 126, 234, 0.1)';
            setTimeout(() => {
                info.dayEl.style.backgroundColor = '';
            }, 300);
        },
        
        // Changement de vue
        datesSet: function(info) {
            // Animer le titre
            const titleEl = document.querySelector('.fc-toolbar-title');
            if (titleEl) {
                titleEl.classList.add('animate__animated', 'animate__fadeIn');
                setTimeout(() => {
                    titleEl.classList.remove('animate__animated', 'animate__fadeIn');
                }, 500);
            }
        },
        
        // Gestion du redimensionnement
        windowResize: function(view) {
            if (window.innerWidth < 768 && view.type === 'dayGridMonth') {
                calendar.changeView('listWeek');
            }
        }
    });

    calendar.render();

    // Fonction pour obtenir la couleur selon le type
    function getEventColor(type) {
        const colors = {
            'réunion': '#3b82f6',
            'formation': '#22c55e',
            'atelier': '#012725',
            'autre': '#6b7280'
        };
        return colors[type] || '#6b7280';
    }

    // Fonction pour afficher la modal
    function showEventModal(event) {
        const modal = new bootstrap.Modal(document.getElementById('eventModal'));
        
        document.getElementById('eventModalTitle').innerHTML = `
            <i class="fas fa-calendar-alt me-2"></i>${event.title}
        `;
        
        document.getElementById('eventModalBody').innerHTML = `
            <div class="row g-3">
                <div class="col-12">
                    <div class="p-3 rounded-3" style="background-color: ${getEventColor(event.extendedProps.type)}20;">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge me-2" style="background-color: ${getEventColor(event.extendedProps.type)}">
                                ${event.extendedProps.type || 'Non défini'}
                            </span>
                            ${event.extendedProps.location ? `
                                <span class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>${event.extendedProps.location}
                                </span>
                            ` : ''}
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Début</small>
                                <strong>${event.start.toLocaleDateString('fr-FR')} ${event.start.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Fin</small>
                                <strong>${event.end ? event.end.toLocaleDateString('fr-FR') + ' ' + event.end.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}) : 'Non défini'}</strong>
                            </div>
                        </div>
                        
                        ${event.extendedProps.organisateur ? `
                            <div class="mt-3">
                                <small class="text-muted d-block">Organisateur</small>
                                <strong>${event.extendedProps.organisateur}</strong>
                            </div>
                        ` : ''}
                        
                        ${event.extendedProps.nombre_places ? `
                            <div class="mt-3">
                                <small class="text-muted d-block">Places disponibles</small>
                                <strong>${event.extendedProps.nombre_places}</strong>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('eventModalLink').href = event.url;
        modal.show();
    }

    // Animation des compteurs
    function animateCounters() {
        document.querySelectorAll('.counter').forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.ceil(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };
            
            updateCounter();
        });
    }

    // Filtrer les événements par type
    document.querySelectorAll('.badge-filter').forEach(filter => {
        filter.addEventListener('click', function() {
            const type = this.dataset.type;
            
            // Mettre à jour l'UI
            document.querySelectorAll('.badge-filter').forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            
            // Filtrer les événements
            if (type === 'all') {
                calendar.getEvents().forEach(event => event.setProp('display', 'auto'));
            } else {
                calendar.getEvents().forEach(event => {
                    if (event.extendedProps.type === type) {
                        event.setProp('display', 'auto');
                    } else {
                        event.setProp('display', 'none');
                    }
                });
            }
            
            // Animation sur le calendrier
            calendarEl.classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => {
                calendarEl.classList.remove('animate__animated', 'animate__pulse');
            }, 500);
        });
    });

    // Rafraîchir le calendrier
    document.querySelector('.refresh-calendar').addEventListener('click', function() {
        this.querySelector('i').classList.add('fa-spin');
        calendar.refetchEvents();
        setTimeout(() => {
            this.querySelector('i').classList.remove('fa-spin');
        }, 500);
    });

    // Lancer les animations
    animateCounters();
});
</script>

<style>
    /* Styles généraux */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #012725 0%, #12626b 100%);
    }
    
    .text-gradient {
        background: linear-gradient(135deg, #012725, #1f6469);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Animations */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
    }
    
    .hover-scale {
        transition: transform 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: scale(1.05);
    }
    
    /* Badges filtres */
    .badge-filter {
        cursor: pointer;
        padding: 8px 16px;
        border-radius: 50px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .badge-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        background: #f8f9fa;
    }
    
    .badge-filter.active {
        background: linear-gradient(135deg, #011418 0%, #06667e 100%);
        color: white;
    }
    
    .badge-filter.active .badge {
        filter: brightness(2);
    }
    
    /* Tooltip personnalisé */
    .custom-tooltip {
        padding: 10px;
        max-width: 250px;
        font-size: 0.9rem;
    }
    
    .custom-tooltip strong {
        color: #136570;
        display: block;
        margin-bottom: 5px;
    }
    
    .custom-tooltip small {
        display: block;
        margin: 2px 0;
    }
    
    /* Calendrier */
    #calendar {
        min-height: 600px;
        background: white;
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    
    .fc-event {
        cursor: pointer;
        border-radius: 8px !important;
        padding: 4px 6px !important;
        margin: 2px 4px !important;
        font-size: 0.85em;
        font-weight: 500;
        border: none !important;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .fc-day-today {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%) !important;
    }
    
    .fc-day {
        transition: background-color 0.3s ease;
    }
    
    .fc-day:hover {
        background-color: rgba(102, 126, 234, 0.05) !important;
    }
    
    .fc-button {
        border-radius: 50px !important;
        padding: 8px 16px !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        border: none !important;
        background: linear-gradient(135deg, #2f5473 0%, #1a4658 100%) !important;
    }
    
    .fc-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4) !important;
    }
    
    .fc-button-primary:not(:disabled):active,
    .fc-button-primary:not(:disabled).fc-button-active {
        background: linear-gradient(135deg, #0daea4 0%, #10a6c0 100%) !important;
    }
    
    /* Responsive */
    @media (max-width: 767.98px) {
        #calendar {
            min-height: 400px;
        }
        
        .fc-toolbar {
            flex-direction: column;
            gap: 15px;
        }
        
        .fc-toolbar-title {
            font-size: 1.3rem !important;
        }
        
        .badge-filter {
            padding: 4px 8px;
            font-size: 0.8rem;
        }
    }
    
    /* Animation des compteurs */
    .counter {
        animation: countUp 2s ease-out;
    }
    
    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Style de la modal */
    .modal-content {
        border-radius: 20px;
        overflow: hidden;
    }
    
    .modal-header {
        padding: 1.5rem;
    }
    
    .modal-body {
        padding: 2rem;
    }
</style>
@endsection