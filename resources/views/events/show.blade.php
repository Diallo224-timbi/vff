@extends('base')

@section('title', $event->titre)

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-calendar-alt text-[#255156] mr-2"></i>
            {{ $event->titre }}
        </h1>
        <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-1"></i>Retour à l'agenda
        </a>
    </div>

    <!-- Contenu -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <!-- Badge type -->
                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium mb-4
                    @if($event->type == 'réunion') bg-blue-100 text-blue-700
                    @elseif($event->type == 'formation') bg-green-100 text-green-700
                    @elseif($event->type == 'atelier') bg-purple-100 text-purple-700
                    @else bg-gray-100 text-gray-700
                    @endif">
                    {{ ucfirst($event->type) }}
                </span>

                <!-- Description -->
                @if($event->description)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Description</h2>
                    <p class="text-gray-600 whitespace-pre-line">{{ $event->description }}</p>
                </div>
                @endif

                <!-- Participants (si admin) -->
                @if(auth()->user()->role === 'admin' && $event->inscriptions->count() > 0)
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Participants ({{ $event->nombre_inscrits }})</h2>
                    <div class="space-y-2 max-h-60 overflow-y-auto">
                        @foreach($event->inscriptions as $inscription)
                            @if($inscription->statut == 'inscrit')
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                                <div class="w-6 h-6 bg-[#255156] rounded-full flex items-center justify-center text-white text-[10px] font-bold">
                                    {{ strtoupper(substr($inscription->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-1 text-sm">
                                    <span class="font-medium">{{ $inscription->user->name ?? '' }} {{ $inscription->user->prenom ?? '' }}</span>
                                    <span class="text-gray-500 text-xs block">{{ $inscription->user->email ?? '' }}</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $inscription->created_at->format('d/m/Y') }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations</h2>
                
                <div class="space-y-3">
                    <!-- Date -->
                    <div class="flex items-start gap-2">
                        <i class="fas fa-calendar text-gray-400 w-5 mt-1"></i>
                        <div>
                            <div class="font-medium">{{ $event->date_debut->format('l d F Y') }}</div>
                            <div class="text-sm text-gray-600">{{ $event->date_debut->format('H:i') }} - {{ $event->date_fin->format('H:i') }}</div>
                        </div>
                    </div>

                    <!-- Lieu -->
                    @if($event->lieu)
                    <div class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-gray-400 w-5 mt-1"></i>
                        <div>
                            <div class="font-medium">Lieu</div>
                            <div class="text-sm text-gray-600">{{ $event->lieu }}</div>
                        </div>
                    </div>
                    @endif

                    <!-- Organisateur -->
                    @if($event->organisateur)
                    <div class="flex items-start gap-2">
                        <i class="fas fa-user text-gray-400 w-5 mt-1"></i>
                        <div>
                            <div class="font-medium">Organisateur</div>
                            <div class="text-sm text-gray-600">{{ $event->organisateur }}</div>
                        </div>
                    </div>
                    @endif

                    <!-- Places -->
                    @if($event->nombre_places)
                    <div class="flex items-start gap-2">
                        <i class="fas fa-users text-gray-400 w-5 mt-1"></i>
                        <div>
                            <div class="font-medium">Places</div>
                            <div class="text-sm text-gray-600">
                                {{ $event->nombre_inscrits }} / {{ $event->nombre_places }} inscrits
                                @if($event->places_restantes > 0)
                                    <span class="text-green-600">({{ $event->places_restantes }} restantes)</span>
                                @elseif($event->places_restantes === 0)
                                    <span class="text-red-600">(Complet)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Statut -->
                    <div class="flex items-start gap-2">
                        <i class="fas fa-clock text-gray-400 w-5 mt-1"></i>
                        <div>
                            <div class="font-medium">Statut</div>
                            <div class="text-sm">
                                @if($event->date_debut > now())
                                    <span class="text-green-600">À venir</span>
                                @elseif($event->date_fin < now())
                                    <span class="text-gray-900">Passé</span>
                                @else
                                    <span class="text-blue-600">En cours</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Actions -->
                <div class="mt-6 space-y-2">
                    @if(!$userInscription && $event->date_debut > now())
                        @if(!$event->est_complet)
                            <form method="POST" action="{{ route('events.inscrire', $event) }}">
                                @csrf
                                <button type="submit" class="w-full bg-[#255156] text-white px-4 py-2 rounded-lg hover:bg-[#1a3a3f]">
                                    <i class="fas fa-check mr-2"></i>S'inscrire
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full bg-gray-300 text-gray-600 px-4 py-2 rounded-lg cursor-not-allowed">
                                <i class="fas fa-times mr-2"></i>Complet
                            </button>
                        @endif
                    @elseif($userInscription)
                        <form method="POST" action="{{ route('events.desinscrire', $event) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600" onclick="return confirm('Confirmer la désinscription ?')">
                                <i class="fas fa-times mr-2"></i>Se désinscrire
                            </button>
                        </form>
                    @endif
                    @if(auth()->user()->role === 'admin' || auth()->user()->id == $event->cree_par)
                        <div class="flex gap-2">
                            <a href="{{ route('events.edit', $event) }}" class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-center hover:bg-gray-200">
                                <i class="fas fa-edit mr-1"></i>Modifier
                            </a>
                            <form method="POST" action="{{ route('events.destroy', $event) }}" class="flex-1" onsubmit="return confirm('Supprimer cet événement ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100">
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <!-- Info création -->
                <p class="text-xs text-gray-400 mt-4">
                    Créé par {{ $event->createur->name ?? 'Inconnu' }} le {{ $event->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection