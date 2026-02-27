@extends('base')

@section('title', 'Agenda partagé')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-calendar-alt text-[#255156] mr-2"></i>
            Agenda partagé
        </h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('events.calendrier') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">
                <i class="fas fa-calendar-week mr-2"></i>Vue calendrier
            </a>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur')
            <a href="{{ route('events.create') }}" class="bg-[#255156] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#1a3a3f]">
                <i class="fas fa-plus mr-2"></i>Nouvel événement
            </a>
            @endif
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6">
        <form method="GET" action="{{ route('events.index') }}" class="flex flex-wrap gap-3">
            <select name="type" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Tous les types</option>
                <option value="réunion" {{ request('type') == 'réunion' ? 'selected' : '' }}>Réunions</option>
                <option value="formation" {{ request('type') == 'formation' ? 'selected' : '' }}>Formations</option>
                <option value="atelier" {{ request('type') == 'atelier' ? 'selected' : '' }}>Ateliers</option>
                <option value="autre" {{ request('type') == 'autre' ? 'selected' : '' }}>Autres</option>
            </select>

            <select name="periode" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Toutes les périodes</option>
                <option value="a_venir" {{ request('periode') == 'a_venir' ? 'selected' : '' }}>À venir</option>
                <option value="passes" {{ request('periode') == 'passes' ? 'selected' : '' }}>Passés</option>
            </select>

            <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">
                <i class="fas fa-filter mr-2"></i>Filtrer
            </button>

            <a href="{{ route('events.index') }}" class="text-gray-500 text-sm px-4 py-2">Réinitialiser</a>
        </form>
    </div>

    <!-- Liste des événements -->
    <div class="grid gap-4">
        @forelse($events as $event)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition p-4">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <!-- Date -->
                <div class="flex items-center gap-2 md:w-48">
                    <div class="w-10 h-10 bg-[#255156] rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <div class="font-semibold">{{ $event->date_debut->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $event->date_debut->format('H:i') }} - {{ $event->date_fin->format('H:i') }}</div>
                    </div>
                </div>

                <!-- Infos principales -->
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            @if($event->type == 'réunion') bg-blue-100 text-blue-700
                            @elseif($event->type == 'formation') bg-green-100 text-green-700
                            @elseif($event->type == 'atelier') bg-purple-100 text-purple-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($event->type) }}
                        </span>
                        @if($event->date_debut > now())
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">À venir</span>
                        @elseif($event->date_fin < now())
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs">Passé</span>
                        @else
                            <span class="nline-flex items-center gap-2 
                                        bg-amber-300 text-amber-800 
                                        px-3 py-1 rounded-full 
                                        font-semibold animate-en-cours animate-en-cours">En cours</span>
                        @endif
                    </div>
                    
                    <h2 class="text-lg font-semibold text-gray-800">{{ $event->titre }}</h2>
                    
                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 mt-1">
                        <span><i class="fas fa-map-marker-alt text-gray-400 w-4 mr-1"></i>{{ $event->lieu ?? 'Lieu non précisé' }}</span>
                        <span><i class="fas fa-user text-gray-400 w-4 mr-1"></i>{{ $event->organisateur ?? 'Organisateur non précisé' }}</span>
                        @if($event->nombre_places)
                            <span><i class="fas fa-users text-gray-400 w-4 mr-1"></i>{{ $event->nombre_inscrits }}/{{ $event->nombre_places }} places</span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('events.show', $event) }}" class="bg-[#255156] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#1a3a3f]">
                        Voir détails
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white p-8 rounded-lg border border-gray-200 text-center">
            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Aucun événement trouvé</p>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur')
            <a href="{{ route('events.create') }}" class="inline-block mt-4 text-[#255156] hover:underline">
                Créer le premier événement
            </a>
            @endif
        </div>
        @endforelse

        <!-- Pagination -->
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>
</div>
<style>
@keyframes pulse-text {
    0% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.6;
        transform: scale(1.05);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-en-cours {
    animation: pulse-text 1.5s ease-in-out infinite;
}
</style>
@endsection

