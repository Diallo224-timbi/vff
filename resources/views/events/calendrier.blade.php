@extends('base')
@section('title', 'Calendrier des événements')
@section('content')

<!-- En-tête -->
<div class="container-fluid py-0">
    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center mb-2 mb-sm-0">
                    <div class="rounded-circle bg-primary bg-gradient p-3 me-3 shadow-lg">
                        <i class="fas fa-calendar-alt fa-2x text-white"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1 fw-bold text-gradient">Calendrier des événements</h1>
                        <p class="text-muted mb-0">
                            <i class="bx bx-info-circle"></i>
                            <span id="totalEventsCount">{{ count($events) }}</span> événements programmés
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('events.index') }}" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
                        <i class="fas fa-list me-2"></i>Vue liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Légende -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex flex-wrap gap-3">
                            <span class="badge-filter active" data-type="all">
                                <span class="badge bg-secondary me-2">●</span>Tous
                            </span>
                            <span class="badge-filter" data-type="réunion">
                                <span class="badge" style="background-color: #3b82f6;">●</span>Réunion
                            </span>
                            <span class="badge-filter" data-type="formation">
                                <span class="badge" style="background-color: #22c55e;">●</span>Formation
                            </span>
                            <span class="badge-filter" data-type="atelier">
                                <span class="badge" style="background-color: #012725;">●</span>Atelier
                            </span>
                            <span class="badge-filter" data-type="autre">
                                <span class="badge" style="background-color: #6b7280;">●</span>Autre
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
                                <button class="btn btn-outline-secondary" onclick="calendar.changeView('dayGridMonth')" title="Mois">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="calendar.changeView('timeGridWeek')" title="Semaine">
                                    <i class="fas fa-calendar-week"></i>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="calendar.changeView('listWeek')" title="Liste">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-0 p-md-2">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #012725, #12626b); color: white;">
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
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Notification -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-3x text-success" id="notificationIcon"></i>
                </div>
                <h5 id="notificationTitle">Succès</h5>
                <p id="notificationMessage" class="text-muted mb-3">Opération réussie</p>
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Bibliothèques -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/fr.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var events = @json($events);
    console.log('Événements:', events);

    // Fonction couleur
    function getEventColor(type) {
        const colors = {
            'réunion': '#3b82f6',
            'formation': '#22c55e',
            'atelier': '#012725',
            'autre': '#6b7280'
        };
        return colors[type] || '#6b7280';
    }

    // Fonction pour formater les dates
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Calendrier
    var calendarEl = document.getElementById('calendar');
    window.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
        locale: 'fr',
        timeZone: 'local',
        
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        },
        
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        
        displayEventTime: true,
        displayEventEnd: true,
        allDayText: 'Toute la journée',
        noEventsText: 'Aucun événement',
        
        events: events.map(event => ({
            id: event.id,
            title: event.title,
            start: event.start,
            end: event.end,
            backgroundColor: getEventColor(event.type),
            borderColor: getEventColor(event.type),
            textColor: '#ffffff',
            extendedProps: {
                type: event.type,
                location: event.location,
                description: event.description,
                organisateur: event.organisateur,
                nombre_places: event.nombre_places,
                piece_jointe: event.piece_jointe,
                piece_jointe_nom: event.piece_jointe_nom,
                created_at: event.created_at,
                cree_par: event.cree_par_nom || 'Inconnu'
            }
        })),
        
        eventDidMount: function(info) {
            // Tooltip
            const tooltipContent = `
                <strong>${info.event.title}</strong><br>
                <small>${info.event.extendedProps.type || 'Événement'}</small><br>
                ${info.event.extendedProps.location ? `<small>📍 ${info.event.extendedProps.location}</small><br>` : ''}
                <small>🕐 ${info.event.startStr}</small>
            `;
            
            new bootstrap.Tooltip(info.el, {
                title: tooltipContent,
                html: true,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });
        },
        
        eventClick: function(info) {
            showEventModal(info.event);
            info.jsEvent.preventDefault();
        }
    });

    calendar.render();

    // === MODALE DÉTAILS ===
    function showEventModal(event) {
        const modal = new bootstrap.Modal(document.getElementById('eventModal'));
        
        const typeColors = {
            'réunion': 'bg-blue-100 text-blue-700',
            'formation': 'bg-green-100 text-green-700',
            'atelier': 'bg-teal-100 text-teal-700',
            'autre': 'bg-gray-100 text-gray-700'
        };
        const typeColor = typeColors[event.extendedProps.type] || typeColors['autre'];
        
        // Statut
        const now = new Date();
        const start = new Date(event.start);
        const end = new Date(event.end);
        let statutHtml = '';
        let statutColor = '';
        if (end < now) {
            statutHtml = 'Passé';
            statutColor = 'bg-secondary';
        } else if (start <= now && end >= now) {
            statutHtml = 'En cours';
            statutColor = 'bg-success';
        } else {
            statutHtml = 'À venir';
            statutColor = 'bg-primary';
        }
        
        // Pièce jointe
        let pieceJointeHtml = '';
        if (event.extendedProps.piece_jointe) {
            const fileName = event.extendedProps.piece_jointe_nom || 'Fichier joint';
            const fileUrl = `/storage/${event.extendedProps.piece_jointe}`;
            // Déterminer l'icône selon l'extension
            const ext = fileName.split('.').pop().toLowerCase();
            let icon = 'fa-file';
            if (['pdf'].includes(ext)) icon = 'fa-file-pdf';
            else if (['doc', 'docx'].includes(ext)) icon = 'fa-file-word';
            else if (['xls', 'xlsx'].includes(ext)) icon = 'fa-file-excel';
            else if (['png', 'jpg', 'jpeg', 'gif'].includes(ext)) icon = 'fa-file-image';
            else if (['zip', 'rar'].includes(ext)) icon = 'fa-file-archive';
            
            pieceJointeHtml = `
                <div class="mt-3 p-3 rounded-3" style="background: rgba(1, 39, 37, 0.05);">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas ${icon} fa-lg text-primary"></i>
                            <div>
                                <div class="fw-semibold text-dark">${fileName}</div>
                                <div class="text-muted small">Pièce jointe</div>
                            </div>
                        </div>
                        <a href="${fileUrl}" download class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="fas fa-download me-1"></i> Télécharger
                        </a>
                    </div>
                </div>
            `;
        }
        
        document.getElementById('eventModalTitle').innerHTML = `
            <i class="fas fa-calendar-alt me-2"></i> ${event.title}
        `;
        
        document.getElementById('eventModalBody').innerHTML = `
            <div class="row g-3">
                <div class="col-12">
                    <div class="p-4 rounded-3" style="background-color: ${getEventColor(event.extendedProps.type)}08; border: 1px solid ${getEventColor(event.extendedProps.type)}20;">
                        <!-- En-tête avec type et statut -->
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3 pb-2 border-bottom" style="border-color: ${getEventColor(event.extendedProps.type)}20 !important;">
                            <span class="badge ${typeColor} px-3 py-2">${event.extendedProps.type || 'Non défini'}</span>
                            <span class="badge ${statutColor} px-3 py-2">${statutHtml}</span>
                        </div>
                        
                        <!-- Dates -->
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <div class="p-2 rounded-3" style="background: rgba(0,0,0,0.03);">
                                    <small class="text-muted d-block">📅 Date de début</small>
                                    <strong class="text-dark">${formatDate(event.start)}</strong>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="p-2 rounded-3" style="background: rgba(0,0,0,0.03);">
                                    <small class="text-muted d-block">📅 Date de fin</small>
                                    <strong class="text-dark">${formatDate(event.end)}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations -->
                        <div class="row g-3">
                            ${event.extendedProps.location ? `
                                <div class="col-12 col-md-6">
                                    <div class="p-2 rounded-3" style="background: rgba(0,0,0,0.03);">
                                        <small class="text-muted d-block">📍 Lieu</small>
                                        <strong class="text-dark">${event.extendedProps.location}</strong>
                                    </div>
                                </div>
                            ` : ''}
                            
                            ${event.extendedProps.organisateur ? `
                                <div class="col-12 col-md-6">
                                    <div class="p-2 rounded-3" style="background: rgba(0,0,0,0.03);">
                                        <small class="text-muted d-block">👤 Organisateur</small>
                                        <strong class="text-dark">${event.extendedProps.organisateur}</strong>
                                    </div>
                                </div>
                            ` : ''}
                            
                            ${event.extendedProps.nombre_places ? `
                                <div class="col-12 col-md-6">
                                    <div class="p-2 rounded-3" style="background: rgba(0,0,0,0.03);">
                                        <small class="text-muted d-block">👥 Places disponibles</small>
                                        <strong class="text-dark">${event.extendedProps.nombre_places}</strong>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                        
                        <!-- Description -->
                        ${event.extendedProps.description ? `
                            <div class="mt-3 p-3 rounded-3" style="background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.05);">
                                <small class="text-muted d-block mb-1">📝 Description</small>
                                <p class="mb-0 text-dark" style="white-space: pre-line;">${event.extendedProps.description}</p>
                            </div>
                        ` : ''}
                        
                        <!-- Pièce jointe -->
                        ${pieceJointeHtml}
                        
                        <!-- Footer -->
                        <div class="mt-3 pt-2 border-top text-muted small d-flex flex-wrap gap-2" style="border-color: rgba(0,0,0,0.08) !important;">
                            <span><i class="fas fa-user-circle me-1"></i> Créé par ${event.extendedProps.cree_par || 'Inconnu'}</span>
                            <span class="d-none d-sm-inline">•</span>
                            <span><i class="far fa-calendar-alt me-1"></i> ${event.extendedProps.created_at ? new Date(event.extendedProps.created_at).toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit', year: 'numeric'}) : ''}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        modal.show();
    }

    // === FILTRES ===
    document.querySelectorAll('.badge-filter').forEach(filter => {
        filter.addEventListener('click', function() {
            const type = this.dataset.type;
            
            document.querySelectorAll('.badge-filter').forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            
            calendar.getEvents().forEach(event => {
                if (type === 'all' || event.extendedProps.type === type) {
                    event.setProp('display', 'auto');
                } else {
                    event.setProp('display', 'none');
                }
            });
        });
    });

    // === RAFRAÎCHIR ===
    document.querySelector('.refresh-calendar')?.addEventListener('click', function() {
        const icon = this.querySelector('i');
        icon.classList.add('fa-spin');
        calendar.refetchEvents();
        setTimeout(() => {
            icon.classList.remove('fa-spin');
            showNotification('Calendrier mis à jour', 'Les événements ont été rafraîchis', 'success');
        }, 500);
    });

    // === NOTIFICATION ===
    function showNotification(title, message, type = 'success') {
        const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
        const icon = document.getElementById('notificationIcon');
        const titleEl = document.getElementById('notificationTitle');
        const messageEl = document.getElementById('notificationMessage');
        
        icon.className = type === 'success' ? 'fas fa-check-circle fa-3x text-success' :
                         type === 'error' ? 'fas fa-times-circle fa-3x text-danger' :
                         'fas fa-exclamation-triangle fa-3x text-warning';
        
        titleEl.textContent = title;
        messageEl.textContent = message;
        modal.show();
        
        setTimeout(() => modal.hide(), 3000);
    }
});
</script>

<style>
/* Styles généraux */
.bg-gradient-primary {
    background: linear-gradient(135deg, #012725, #12626b);
}
.text-gradient {
    background: linear-gradient(135deg, #012725, #1f6469);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
}

/* Badges filtres */
.badge-filter {
    cursor: pointer;
    padding: 6px 14px;
    border-radius: 50px;
    transition: all 0.3s ease;
    background: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    font-size: 0.85rem;
}
.badge-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    background: #f8f9fa;
}
.badge-filter.active {
    background: #012725;
    color: white;
}
.badge-filter.active .badge {
    filter: brightness(2);
}

/* Calendrier */
#calendar {
    min-height: 600px;
    background: white;
    border-radius: 15px;
    padding: 10px;
}
.fc-event {
    cursor: pointer;
    border-radius: 6px !important;
    padding: 3px 6px !important;
    font-size: 0.8em;
    font-weight: 500;
    border: none !important;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.fc-day-today {
    background: rgba(1, 39, 37, 0.05) !important;
}
.fc-button {
    border-radius: 50px !important;
    padding: 6px 14px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    border: none !important;
    background: #012725 !important;
}
.fc-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(1, 39, 37, 0.3) !important;
}
.fc-button-primary:not(:disabled):active,
.fc-button-primary:not(:disabled).fc-button-active {
    background: #12626b !important;
}

/* Modal */
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
.modal-footer {
    padding: 1rem 1.5rem;
}

/* Responsive */
@media (max-width: 767.98px) {
    #calendar {
        min-height: 400px;
        padding: 5px;
    }
    .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }
    .fc-toolbar-title {
        font-size: 1.1rem !important;
    }
    .badge-filter {
        padding: 4px 10px;
        font-size: 0.75rem;
    }
    .modal-body {
        padding: 1rem;
    }
}

/* Variables */
:root {
    --reunion-color: #3b82f6;
    --formation-color: #22c55e;
    --atelier-color: #012725;
    --autre-color: #6b7280;
}
</style>
@endsection