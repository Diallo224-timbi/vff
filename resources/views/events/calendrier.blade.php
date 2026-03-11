@extends('base')
@section('title', 'Calendrier des événements')
@section('content')

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
                    <!-- Bouton de partage amélioré -->
                    <div class="dropdown">
                        <button class="btn btn-primary rounded-pill px-3 shadow-sm hover-lift" 
                                type="button" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false">
                            <i class="fas fa-share-alt me-2"></i>Partager
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-2" style="min-width: 220px;">
                            <li>
                                <button class="dropdown-item rounded-3 mb-1" onclick="shareCalendar('email')">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Par email
                                </button>
                            </li>
                    
                            <li>
                                <button class="dropdown-item rounded-3 mb-1" onclick="shareCalendar('ical')">
                                    <i class="fas fa-calendar-plus me-2 text-warning"></i>Exporter iCal
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item rounded-3 mb-1" onclick="shareCalendar('print')">
                                    <i class="fas fa-print me-2 text-info"></i>Imprimer
                                </button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button class="dropdown-item rounded-3" onclick="shareCalendar('pdf')">
                                    <i class="fas fa-file-pdf me-2 text-danger"></i>Exporter PDF
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <a href="{{ route('events.index') }}" class="btn btn-outline-primary rounded-pill px-3 shadow-sm hover-lift">
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

<!-- Modal de notification -->
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

<!-- Inclusion des bibliothèques -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/fr.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les événements avec les dates/heures originales de la BDD
    var events = @json($events);
    console.log('Événements chargés depuis la BDD:', events);

    if (!Array.isArray(events)) {
        console.error('Les événements ne sont pas au format attendu');
        events = [];
    }

    // Configuration du calendrier
    var calendarEl = document.getElementById('calendar');
    window.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
        locale: 'fr',
        timeZone: 'UTC', // Force UTC pour éviter les conversions
        
        // Personnalisation de l'en-tête
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste'
        },
        
        // FORMAT DE L'HEURE - Garde le format original
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
            meridiem: false
        },
        
        // Options d'affichage
        displayEventTime: true,
        displayEventEnd: true,
        allDayText: 'Toute la journée',
        noEventsText: 'Aucun événement',
        
        // Événements avec leurs dates/heures originales
        events: events.map(event => {
            // Extraire l'heure de la chaîne ISO pour l'afficher séparément
            let heureDebut = '';
            if (event.start) {
                const timePart = event.start.split('T')[1];
                if (timePart) {
                    heureDebut = timePart.substring(0, 5); // HH:MM
                }
            }
            
            return {
                id: event.id,
                title: event.title,
                start: event.start, // Garde la chaîne exacte de la BDD
                end: event.end,     // Garde la chaîne exacte de la BDD
                backgroundColor: getEventColor(event.type),
                borderColor: getEventColor(event.type),
                textColor: '#ffffff',
                extendedProps: {
                    type: event.type,
                    location: event.location,
                    description: event.description,
                    organisateur: event.organisateur,
                    nombre_places: event.nombre_places,
                    heure_debut: heureDebut, // Heure extraite
                    start_raw: event.start,
                    end_raw: event.end
                }
            };
        }),
        
        // Personnalisation de l'affichage des événements
        eventDidMount: function(info) {
            // Ajouter l'heure dans le titre si nécessaire
            const heureDebut = info.event.extendedProps.heure_debut;
            if (heureDebut) {
                // Vous pouvez personnaliser l'affichage ici
            }
            
            // Tooltip avec l'heure exacte
            if (info.event.title) {
                const heureDebut = info.event.extendedProps.heure_debut;
                const tooltipContent = `
                    <div class="custom-tooltip">
                        <strong>${info.event.title}</strong><br>
                        <small>${info.event.extendedProps.type || 'Événement'}</small><br>
                        ${info.event.extendedProps.location ? `<small><i class="fas fa-map-marker-alt"></i> ${info.event.extendedProps.location}</small><br>` : ''}
                        <small><i class="far fa-clock"></i> ${heureDebut || 'Heure non définie'}</small>
                    </div>
                `;
                
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
            info.dayEl.style.transition = 'background-color 0.3s';
            info.dayEl.style.backgroundColor = 'rgba(102, 126, 234, 0.1)';
            setTimeout(() => {
                info.dayEl.style.backgroundColor = '';
            }, 300);
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

    // Fonction pour afficher la modal avec les heures exactes de la BDD
    function showEventModal(event) {
        const modal = new bootstrap.Modal(document.getElementById('eventModal'));
        
        // Récupérer les valeurs brutes
        const startRaw = event.extendedProps.start_raw;
        const endRaw = event.extendedProps.end_raw;
        const heureDebut = event.extendedProps.heure_debut;
        
        // Extraire la date
        let dateDebut = '';
        if (startRaw) {
            const datePart = startRaw.split('T')[0];
            if (datePart) {
                const [year, month, day] = datePart.split('-');
                dateDebut = `${day}/${month}/${year}`;
            }
        }
        
        // Extraire la date et heure de fin
        let dateFin = 'Non défini';
        let heureFin = '';
        if (endRaw) {
            const datePart = endRaw.split('T')[0];
            const timePart = endRaw.split('T')[1];
            if (datePart) {
                const [year, month, day] = datePart.split('-');
                dateFin = `${day}/${month}/${year}`;
            }
            if (timePart) {
                heureFin = timePart.substring(0, 5);
                dateFin += ` à ${heureFin}`;
            }
        }
        
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
                                <strong>${startRaw}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Fin</small>
                                <strong>${endRaw}</strong>
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
                        
                        ${event.extendedProps.description ? `
                            <div class="mt-3">
                                <small class="text-muted d-block">Description</small>
                                <p class="mb-0">${event.extendedProps.description}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('eventModalLink').href = event.url;
        modal.show();
    }

    // ============ FONCTIONS DE PARTAGE ============

    // Fonction principale de partage
    window.shareCalendar = function(method) {
        switch(method) {
            case 'email':
                shareByEmail();
                break;
            case 'link':
                shareByLink();
                break;
            case 'ical':
                exportICal();
                break;
            case 'print':
                printCalendar();
                break;
            case 'pdf':
                exportPDF();
                break;
            
        }
    }

    // Partage par email
    function shareByEmail() {
        const events = calendar.getEvents();
        const eventsList = events.map(event => {
            const heureDebut = event.extendedProps.heure_debut;
            const startRaw = event.extendedProps.start_raw;
            let dateDebut = '';
            if (startRaw) {
                const datePart = startRaw.split('T')[0];
                if (datePart) {
                    const [year, month, day] = datePart.split('-');
                    dateDebut = `${day}/${month}/${year}`;
                }
            }
            return `- ${event.title} (${startRaw} à ${endRaw || ' - '})`;
        }).join('\n');
        
        const subject = encodeURIComponent('Calendrier des événements');
        const body = encodeURIComponent(
            `Bonjour,\n\n` +
            `Je souhaite partager avec vous le calendrier des événements :\n` +
            `${window.location.href}\n\n` +
            `Résumé des événements :\n${eventsList}\n\n` +
            `Cordialement`
        );
        
        window.location.href = `mailto:?subject=${subject}&body=${body}`;
        
        showNotification('Client email ouvert', 'Votre client email a été ouvert', 'success');
    }

    // Partage par lien
    function shareByLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            showNotification('Lien copié !', 'Le lien du calendrier a été copié dans le presse-papier', 'success');
        }).catch(() => {
            showNotification('Erreur', 'Impossible de copier le lien', 'error');
        });
    }

    // Export iCal
    function exportICal() {
        const events = calendar.getEvents();
        let icalContent = `BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Calendar App//FR
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:Calendrier des événements
X-WR-TIMEZONE:Europe/Paris
`;
        
        events.forEach(event => {
            const startRaw = event.extendedProps.start_raw;
            const endRaw = event.extendedProps.end_raw || event.extendedProps.start_raw;
            
            // Formater pour iCal
            const startICal = startRaw.replace(/[-:]/g, '').replace('T', '').substring(0, 15) + 'Z';
            const endICal = endRaw.replace(/[-:]/g, '').replace('T', '').substring(0, 15) + 'Z';
            
            const summary = event.title.replace(/[;,]/g, '\\$&');
            const description = (event.extendedProps.description || '').replace(/[;,]/g, '\\$&');
            const location = (event.extendedProps.location || '').replace(/[;,]/g, '\\$&');
            
            icalContent += `BEGIN:VEVENT
UID:${event.id || Math.random().toString(36).substring(2)}@${window.location.hostname}
DTSTAMP:${new Date().toISOString().replace(/[-:]/g, '').split('.')[0]}Z
DTSTART:${startICal}
DTEND:${endICal}
SUMMARY:${summary}
DESCRIPTION:${description}
LOCATION:${location}
END:VEVENT
`;
        });
        
        icalContent += 'END:VCALENDAR';
        
        downloadFile(icalContent, 'calendrier.ics', 'text/calendar');
        showNotification('Export réussi', 'Le fichier iCal a été généré', 'success');
    }

    // Export CSV
    function exportCSV() {
        const events = calendar.getEvents();
        
        // En-têtes CSV
        let csvContent = "Titre,Type,Date début,Heure début,Date fin,Heure fin,Lieu,Organisateur,Description\n";
        
        events.forEach(event => {
            const startRaw = event.extendedProps.start_raw;
            const endRaw = event.extendedProps.end_raw;
            const heureDebut = event.extendedProps.heure_debut;
            
            let dateDebut = '';
            if (startRaw) {
                const datePart = startRaw.split('T')[0];
                if (datePart) {
                    const [year, month, day] = datePart.split('-');
                    dateDebut = `${day}/${month}/${year}`;
                }
            }
            
            let dateFin = '';
            let heureFin = '';
            if (endRaw) {
                const datePart = endRaw.split('T')[0];
                const timePart = endRaw.split('T')[1];
                if (datePart) {
                    const [year, month, day] = datePart.split('-');
                    dateFin = `${day}/${month}/${year}`;
                }
                if (timePart) {
                    heureFin = timePart.substring(0, 5);
                }
            }
            
            const row = [
                `"${event.title.replace(/"/g, '""')}"`,
                `"${event.extendedProps.type || ''}"`,
                `"${dateDebut}"`,
                `"${heureDebut}"`,
                `"${dateFin}"`,
                `"${heureFin}"`,
                `"${(event.extendedProps.location || '').replace(/"/g, '""')}"`,
                `"${(event.extendedProps.organisateur || '').replace(/"/g, '""')}"`,
                `"${(event.extendedProps.description || '').replace(/"/g, '""')}"`
            ].join(',');
            
            csvContent += row + '\n';
        });
        
        downloadFile(csvContent, 'calendrier.csv', 'text/csv;charset=utf-8;');
        showNotification('Export réussi', 'Le fichier CSV a été généré', 'success');
    }

    // Export JSON
    function exportJSON() {
        const events = calendar.getEvents().map(event => {
            const startRaw = event.extendedProps.start_raw;
            const endRaw = event.extendedProps.end_raw;
            const heureDebut = event.extendedProps.heure_debut;
            
            let dateDebut = '';
            if (startRaw) {
                const datePart = startRaw.split('T')[0];
                if (datePart) {
                    const [year, month, day] = datePart.split('-');
                    dateDebut = `${day}/${month}/${year}`;
                }
            }
            
            let dateFin = '';
            let heureFin = '';
            if (endRaw) {
                const datePart = endRaw.split('T')[0];
                const timePart = endRaw.split('T')[1];
                if (datePart) {
                    const [year, month, day] = datePart.split('-');
                    dateFin = `${day}/${month}/${year}`;
                }
                if (timePart) {
                    heureFin = timePart.substring(0, 5);
                }
            }
            
            return {
                id: event.id,
                title: event.title,
                type: event.extendedProps.type,
                date_debut: dateDebut,
                heure_debut: heureDebut,
                date_fin: dateFin,
                heure_fin: heureFin,
                location: event.extendedProps.location,
                organisateur: event.extendedProps.organisateur,
                description: event.extendedProps.description,
                nombre_places: event.extendedProps.nombre_places,
                // Valeurs brutes
                start_brut: startRaw,
                end_brut: endRaw
            };
        });
        
        const jsonContent = JSON.stringify(events, null, 2);
        downloadFile(jsonContent, 'calendrier.json', 'application/json');
        showNotification('Export réussi', 'Le fichier JSON a été généré', 'success');
    }

    // Export PDF
    function exportPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const events = calendar.getEvents();
        
        // Titre
        doc.setFontSize(20);
        doc.setTextColor(1, 39, 37);
        doc.text('Calendrier des événements', 20, 20);
        
        // Date d'export
        doc.setFontSize(10);
        doc.setTextColor(100, 100, 100);
        doc.text(`Exporté le ${new Date().toLocaleDateString('fr-FR')}`, 20, 30);
        
        // Tableau des événements avec heures exactes
        const tableData = events.map(event => {
            const heureDebut = event.extendedProps.heure_debut;
            const startRaw = event.extendedProps.start_raw;
            let dateDebut = '';
            if (startRaw) {
                const datePart = startRaw.split('T')[0];
                if (datePart) {
                    const [year, month, day] = datePart.split('-');
                    dateDebut = `${day}/${month}/${year}`;
                }
            }
            
            return [
                event.title,
                event.extendedProps.type || '',
                `${dateDebut} ${heureDebut || ''}`,
                event.extendedProps.location || ''
            ];
        });
        
        doc.autoTable({
            startY: 40,
            head: [['Titre', 'Type', 'Date et heure', 'Lieu']],
            body: tableData,
            theme: 'striped',
            headStyles: {
                fillColor: [1, 39, 37],
                textColor: [255, 255, 255],
                fontStyle: 'bold'
            },
            alternateRowStyles: {
                fillColor: [240, 240, 240]
            }
        });
        
        doc.save('calendrier.pdf');
        showNotification('Export réussi', 'Le fichier PDF a été généré', 'success');
    }

    // Impression
    function printCalendar() {
        const events = calendar.getEvents();
        const printWindow = window.open('', '_blank');
        
        printWindow.document.write(`
            <html>
                <head>
                    <title>Calendrier des événements</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h1 { color: #012725; }
                        .event { 
                            border: 1px solid #ddd; 
                            padding: 15px; 
                            margin-bottom: 10px; 
                            border-radius: 5px;
                            page-break-inside: avoid;
                        }
                        .event-header { 
                            background: #012725; 
                            color: white; 
                            padding: 10px; 
                            margin: -15px -15px 15px -15px;
                            border-radius: 5px 5px 0 0;
                        }
                        .event-type { 
                            display: inline-block; 
                            padding: 3px 10px; 
                            border-radius: 3px; 
                            color: white;
                            font-size: 12px;
                        }
                        .label { font-weight: bold; color: #666; }
                        .heure-exacte {
                            font-size: 16px;
                            font-weight: bold;
                            color: #012725;
                        }
                        @media print {
                            .event { break-inside: avoid; }
                        }
                    </style>
                </head>
                <body>
                    <h1>Calendrier des événements</h1>
                    <p>Généré le ${new Date().toLocaleDateString('fr-FR')}</p>
                    <hr>
        `);
        
        events.sort((a, b) => a.start - b.start).forEach(event => {
            const typeColor = getEventColor(event.extendedProps.type);
            const startRaw = event.extendedProps.start_raw;
            const heureDebut = event.extendedProps.heure_debut;
            
            let dateDebut = '';
            if (startRaw) {
                const datePart = startRaw.split('T')[0];
                if (datePart) {
                    const [year, month, day] = datePart.split('-');
                    dateDebut = `${day}/${month}/${year}`;
                }
            }
            
            printWindow.document.write(`
                <div class="event">
                    <div class="event-header">
                        <h3>${event.title}</h3>
                    </div>
                    <div style="padding: 10px;">
                        <span class="event-type" style="background-color: ${typeColor}">
                            ${event.extendedProps.type || 'Non défini'}
                        </span>
                        <p class="heure-exacte">${startRaw} au ${event.extendedProps.end_raw || '-'}</p>
                        ${event.extendedProps.location ? `<p><span class="label">Lieu :</span> ${event.extendedProps.location}</p>` : ''}
                        ${event.extendedProps.organisateur ? `<p><span class="label">Organisateur :</span> ${event.extendedProps.organisateur}</p>` : ''}
                        ${event.extendedProps.description ? `<p><span class="label">Description :</span> ${event.extendedProps.description}</p>` : ''}
                       
                    </div>
                </div>
            `);
        });
        
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        setTimeout(() => {
            printWindow.print();
        }, 500);
    }

    // Fonctions utilitaires
    function downloadFile(content, filename, type) {
        const blob = new Blob([content], { type: type });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
        URL.revokeObjectURL(link.href);
    }

    function showNotification(title, message, type = 'success') {
        const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
        const icon = document.getElementById('notificationIcon');
        const titleEl = document.getElementById('notificationTitle');
        const messageEl = document.getElementById('notificationMessage');
        
        if (type === 'success') {
            icon.className = 'fas fa-check-circle fa-3x text-success';
        } else if (type === 'error') {
            icon.className = 'fas fa-times-circle fa-3x text-danger';
        } else if (type === 'warning') {
            icon.className = 'fas fa-exclamation-triangle fa-3x text-warning';
        }
        
        titleEl.textContent = title;
        messageEl.textContent = message;
        modal.show();
        
        setTimeout(() => {
            modal.hide();
        }, 3000);
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
            
            document.querySelectorAll('.badge-filter').forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            
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
            showNotification('Calendrier mis à jour', 'Les événements ont été rafraîchis', 'success');
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
    
    /* Dropdown personnalisé */
    .dropdown-menu {
        animation: fadeInDown 0.3s ease;
        border-radius: 15px;
    }
    
    .dropdown-item {
        border-radius: 10px;
        transition: all 0.3s ease;
        padding: 10px 15px;
    }
    
    .dropdown-item:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transform: translateX(5px);
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
    
    /* Badge de type d'événement */
    .event-type-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
        color: white;
    }
    
    /* Style pour les heures dans le calendrier */
    .fc-event-time {
        font-weight: 500;
        color: rgba(255,255,255,0.9);
    }
    
    .fc-event-title {
        font-weight: 600;
    }
    
    /* Mise en évidence de l'heure */
    .heure-exacte {
        font-size: 1.1em;
        font-weight: bold;
        color: #012725;
        background: #f0f8ff;
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-block;
    }
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
@endsection