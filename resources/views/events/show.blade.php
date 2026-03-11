@extends('base')

@php
// Fonctions pour générer les URLs des calendriers
if (!function_exists('generateGoogleCalendarUrl')) {
    function generateGoogleCalendarUrl($event) {
        $start = $event->date_debut->format('Ymd\THis');
        $end = $event->date_fin->format('Ymd\THis');
        
        $params = [
            'action' => 'TEMPLATE',
            'text' => $event->titre,
            'dates' => $start . '/' . $end,
            'details' => $event->description ?? '',
            'location' => $event->lieu ?? '',
        ];
        
        return 'https://www.google.com/calendar/render?' . http_build_query($params);
    }
}
@endphp

@section('title', $event->titre)

@section('content')
<style>
:root {
    --primary: #255156;
    --primary-light: #3a7077;
    --primary-dark: #1a3a3f;
    --secondary: #f59e0b;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #3b82f6;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    from { transform: translateX(-20px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease-out;
}

.animate-slideIn {
    animation: slideIn 0.5s ease-out;
}

.hover-lift {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -10px rgba(37, 81, 86, 0.3);
}

.hover-scale {
    transition: transform 0.3s ease;
}

.hover-scale:hover {
    transform: scale(1.02);
}

/* Cards modernes */
.modern-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(37, 81, 86, 0.1);
    border-radius: 1.5rem;
    transition: all 0.3s ease;
}

.modern-card:hover {
    border-color: rgba(37, 81, 86, 0.2);
    box-shadow: 0 20px 30px -10px rgba(37, 81, 86, 0.15);
}

/* Badges dynamiques */
.type-badge {
    position: relative;
    overflow: hidden;
    padding: 0.5rem 1.5rem;
    border-radius: 2rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.type-badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
    transform: translateX(-100%);
    transition: transform 0.5s ease;
}

.type-badge:hover::before {
    transform: translateX(100%);
}

/* Info items avec icônes animées */
.info-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    border-radius: 1rem;
    background: linear-gradient(135deg, rgba(37, 81, 86, 0.05), rgba(37, 81, 86, 0.02));
    transition: all 0.3s ease;
    border: 1px solid rgba(37, 81, 86, 0.05);
}

.info-item:hover {
    background: linear-gradient(135deg, rgba(37, 81, 86, 0.08), rgba(37, 81, 86, 0.03));
    border-color: rgba(37, 81, 86, 0.15);
    transform: translateX(5px);
}

.info-icon {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 1rem;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.info-item:hover .info-icon {
    transform: rotate(360deg) scale(1.1);
}

/* Boutons d'action */
.action-button {
    position: relative;
    overflow: hidden;
    padding: 0.875rem 1.5rem;
    border-radius: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border: none;
    cursor: pointer;
}

.action-button::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.action-button:hover::after {
    width: 300px;
    height: 300px;
}

.action-button-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    box-shadow: 0 4px 15px -5px var(--primary);
}

.action-button-primary:hover {
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    box-shadow: 0 8px 25px -5px var(--primary);
}

.action-button-danger {
    background: linear-gradient(135deg, var(--danger), #f87171);
    color: white;
    box-shadow: 0 4px 15px -5px var(--danger);
}

.action-button-danger:hover {
    background: linear-gradient(135deg, #f87171, var(--danger));
    box-shadow: 0 8px 25px -5px var(--danger);
}

.action-button-outline {
    background: transparent;
    border: 2px solid var(--primary);
    color: var(--primary);
}

.action-button-outline:hover {
    background: var(--primary);
    color: white;
}

/* Dropdown élégant */
.calendar-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    margin-top: 0.5rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 20px 35px -10px rgba(0,0,0,0.2);
    border: 1px solid rgba(37, 81, 86, 0.1);
    z-index: 1000;
    overflow: hidden;
    animation: fadeIn 0.3s ease;
}

.calendar-dropdown a {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    transition: all 0.3s ease;
    color: #4a5568;
    text-decoration: none;
    border-left: 3px solid transparent;
}

.calendar-dropdown a:hover {
    background: linear-gradient(135deg, rgba(37, 81, 86, 0.05), rgba(37, 81, 86, 0.02));
    border-left-color: var(--primary);
    transform: translateX(5px);
}

/* Participant avatars */
.participant-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 1rem;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px -3px var(--primary);
}

.participant-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border-radius: 1rem;
    background: white;
    border: 1px solid rgba(37, 81, 86, 0.05);
    transition: all 0.3s ease;
    margin-bottom: 0.5rem;
}

.participant-item:hover {
    transform: translateX(5px);
    border-color: rgba(37, 81, 86, 0.2);
    box-shadow: 0 5px 15px -5px rgba(37, 81, 86, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .info-item {
        padding: 0.75rem;
    }
    
    .info-icon {
        width: 2rem;
        height: 2rem;
        font-size: 1rem;
    }
    
    .action-button {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }
    
    .type-badge {
        padding: 0.4rem 1rem;
        font-size: 0.7rem;
    }
}
</style>

<div class="container mx-auto px-4 py-6 md:py-8 max-w-7xl">
    <!-- Header avec animation -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 animate-fadeIn">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-[#255156] to-[#3a7077] rounded-2xl flex items-center justify-center shadow-lg hover-lift">
                <i class="fas fa-calendar-alt text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $event->titre }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="type-badge
                        @if($event->type == 'réunion') bg-blue-100 text-blue-700
                        @elseif($event->type == 'formation') bg-green-100 text-green-700
                        @elseif($event->type == 'atelier') bg-purple-100 text-purple-700
                        @else bg-gray-100 text-gray-700
                        @endif">
                        <i class="fas 
                            @if($event->type == 'réunion') fa-users
                            @elseif($event->type == 'formation') fa-graduation-cap
                            @elseif($event->type == 'atelier') fa-tools
                            @else fa-calendar
                            @endif"></i>
                        {{ ucfirst($event->type) }}
                    </span>
                    <span class="text-sm text-gray-500">
                        <i class="far fa-clock mr-1"></i>
                        @if($event->date_debut > now())
                            <span class="text-green-600">À venir</span>
                        @elseif($event->date_fin < now())
                            <span class="text-gray-600">Passé</span>
                        @else
                            <span class="text-blue-600">En cours</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <a href="{{ route('events.index') }}" class="group flex items-center gap-2 text-gray-600 hover:text-[#255156] transition-all px-4 py-2 rounded-xl hover:bg-gray-50">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span>Retour à l'agenda</span>
        </a>
    </div>
    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Colonne principale - Description -->
        <div class="lg:col-span-2 space-y-6 animate-slideIn">
            <div class="modern-card p-6 md:p-8">
                @if($event->description)
                <div class="prose max-w-none">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-align-left text-[#255156]"></i>
                        Description
                    </h2>
                    <p class="text-gray-600 whitespace-pre-line leading-relaxed">{{ $event->description }}</p>
                </div>
                @endif
            </div>
            <!-- Participants (si admin) -->
            @if(auth()->user()->role === 'admin' && $event->inscriptions->count() > 0)
            <div class="modern-card p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-[#255156]"></i>
                    Participants ({{ $event->nombre_inscrits }})
                </h2>
                <div class="space-y-2 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($event->inscriptions as $inscription)
                        @if($inscription->statut == 'inscrit')
                        <div class="participant-item">
                            <div class="participant-avatar">
                                {{ strtoupper(substr($inscription->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800">
                                    {{ $inscription->user->name ?? '' }} {{ $inscription->user->prenom ?? '' }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $inscription->user->email ?? '' }}</div>
                            </div>
                            <div class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
                                {{ $inscription->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne latérale - Informations -->
        <div class="lg:col-span-1 animate-slideIn" style="animation-delay: 0.2s;">
            <div class="modern-card p-6 sticky top-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-info-circle text-[#255156]"></i>
                    Informations
                </h2>
                
                <div class="space-y-4">
                    <!-- Date -->
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-gray-500">Date</div>
                            <div class="font-semibold text-gray-800">
                                {{ $event->date_debut->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $event->date_debut->format('H:i') }}</span>
                                <span class="mx-2">—</span>
                                <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $event->date_fin->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Lieu -->
                    @if($event->lieu)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-gray-500">Lieu</div>
                            <div class="font-semibold text-gray-800">{{ $event->lieu }}</div>
                            <button onclick="window.open('https://maps.google.com/?q={{ urlencode($event->lieu) }}')" class="text-sm text-[#255156] hover:underline mt-1 inline-flex items-center gap-1">
                                <i class="fas fa-external-link-alt text-xs"></i>
                                Voir sur la carte
                            </button>
                        </div>
                    </div>
                    @endif
                    <!-- Organisateur -->
                    @if($event->organisateur)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-gray-500">Organisateur</div>
                            <div class="font-semibold text-gray-800">{{ $event->organisateur }}</div>
                        </div>
                    </div>
                    @endif
                    <!-- Places -->
                    @if($event->nombre_places)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-gray-500">Places disponibles</div>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#255156] to-[#3a7077] rounded-full" 
                                         style="width: {{ min(100, ($event->nombre_inscrits / $event->nombre_places) * 100) }}%"></div>
                                </div>
                                <span class="text-sm font-medium {{ $event->places_restantes > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $event->nombre_inscrits }}/{{ $event->nombre_places }}
                                </span>
                            </div>
                            @if($event->places_restantes > 0)
                                <div class="text-sm text-green-600 mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    {{ $event->places_restantes }} place(s) restante(s)
                                </div>
                            @elseif($event->places_restantes === 0)
                                <div class="text-sm text-red-600 mt-1">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Complet
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                <!-- Actions -->
                <div class="mt-8 space-y-3">
                    <!-- Bouton Ajouter à l'agenda -->
                    @if($event->date_debut > now())
                    <div class="dropdown relative">
                        <button type="button" 
                                class="action-button action-button-primary w-full"
                                onclick="toggleCalendarDropdown()">
                            <i class="fas fa-calendar-plus"></i>
                            Ajouter à mon agenda
                            <i class="fas fa-chevron-down ml-auto text-sm"></i>
                        </button>
                        
                        <div id="calendarDropdown" class="calendar-dropdown hidden">
                            <a href="{{ generateGoogleCalendarUrl($event) }}" target="_blank">
                                <img src="https://www.google.com/favicon.ico" alt="Google" class="w-5 h-5">
                                <span>Google Calendar</span>
                                <i class="fas fa-external-link-alt ml-auto text-gray-400 text-sm"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                    <!-- Boutons d'inscription/désinscription -->
                    @if(!$userInscription && $event->date_debut > now())
                        @if(!$event->est_complet)
                            <form method="POST" action="{{ route('events.inscrire', $event) }}">
                                @csrf
                                <button type="submit" class="action-button action-button-primary w-full">
                                    <i class="fas fa-check-circle"></i>
                                    S'inscrire à l'événement
                                </button>
                            </form>
                        @else
                            <button disabled class="action-button w-full bg-gray-200 text-gray-500 cursor-not-allowed">
                                <i class="fas fa-times-circle"></i>
                                Événement complet
                            </button>
                        @endif                    
                    @elseif($userInscription && $event->date_debut > now())
                        <form method="POST" action="{{ route('events.desinscrire', $event) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-button action-button-danger w-full">
                                <i class="fas fa-times-circle"></i>
                                Se désinscrire
                            </button>
                        </form>
                    @endif
                    <!-- Boutons admin -->
                    @if(auth()->user()->role === 'admin' || auth()->user()->id == $event->cree_par)
                        <div class="flex gap-2 pt-4 border-t border-gray-100">
                            <a href="{{ route('events.edit', $event) }}" class="flex-1 action-button action-button-outline">
                                <i class="fas fa-edit"></i>
                                Modifier
                            </a>
                            <form method="POST" action="{{ route('events.destroy', $event) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full action-button action-button-outline !border-red-500 !text-red-500 hover:!bg-red-500 hover:!text-white" onclick="return confirm('Confirmer la suppression ?')">
                                    <i class="fas fa-trash"></i>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <!-- Info création -->
                <div class="mt-6 pt-4 border-t border-gray-100 text-xs text-gray-400 flex items-center gap-2">
                    <i class="fas fa-user-circle"></i>
                    Créé par {{ $event->createur->name ?? 'Inconnu' }} 
                    <span class="mx-1">•</span>
                    <i class="far fa-calendar-alt"></i>
                    {{ $event->created_at->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Script pour le dropdown -->
<script>
    function toggleCalendarDropdown() {
        const dropdown = document.getElementById('calendarDropdown');
        dropdown.classList.toggle('hidden');
        
        if (!dropdown.classList.contains('hidden')) {
            document.addEventListener('click', function closeDropdown(e) {
                if (!dropdown.contains(e.target) && !e.target.closest('button[onclick="toggleCalendarDropdown()"]')) {
                    dropdown.classList.add('hidden');
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }
    }

    // Animation smooth pour les boutons
    document.querySelectorAll('.action-button').forEach(button => {
        button.addEventListener('click', function(e) {
            let ripple = document.createElement('span');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;
            
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
</script>

    <style>
    /* Style pour l'effet ripple */
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #255156;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #3a7077;
    }

    /* Améliorations responsive */
    @media (max-width: 768px) {
        .modern-card {
            border-radius: 1rem;
            padding: 1.25rem;
        }
        
        .info-item {
            padding: 0.75rem;
        }
        
        .action-button {
            padding: 0.75rem 1rem;
        }
    }

    /* Loading animation */
    .loading {
        position: relative;
        overflow: hidden;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
</style>
@endsection