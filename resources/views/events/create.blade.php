@extends('base')

@section('title', 'Créer un événement')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-plus-circle text-[#255156] mr-2"></i>
            Créer un événement
        </h1>
        <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-1"></i>Retour
        </a>
    </div>

    <!-- Formulaire -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 max-w-3xl mx-auto">
        <form method="POST" action="{{ route('events.store') }}">
            @csrf

            <div class="grid gap-4">
                <!-- Titre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre de l'événement *</label>
                    <input type="text" name="titre" value="{{ old('titre') }}" required
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#255156]">
                    @error('titre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type d'événement *</label>
                    <select name="type" required class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#255156]">
                        <option value="">Sélectionner</option>
                        <option value="réunion" {{ old('type') == 'réunion' ? 'selected' : '' }}>Réunion</option>
                        <option value="formation" {{ old('type') == 'formation' ? 'selected' : '' }}>Formation</option>
                        <option value="atelier" {{ old('type') == 'atelier' ? 'selected' : '' }}>Atelier</option>
                        <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date et heure de début *</label>
                        <input type="datetime-local" name="date_debut" value="{{ old('date_debut') }}" required
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#255156]">
                        @error('date_debut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date et heure de fin *</label>
                        <input type="datetime-local" name="date_fin" value="{{ old('date_fin') }}" required
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#255156]">
                        @error('date_fin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Lieu et organisateur -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                        <input type="text" name="lieu" value="{{ old('lieu') }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#255156]">
                        @error('lieu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organisateur</label>
                        <input type="text" name="organisateur" value="{{ old('organisateur') }}"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#255156]">
                        @error('organisateur') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Nombre de places -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de places (laisser vide pour illimité)</label>
                    <input type="number" name="nombre_places" value="{{ old('nombre_places') }}" min="1"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#255156]">
                    @error('nombre_places') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="5" 
                              class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#255156]">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Boutons -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('events.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" class="px-4 py-2 bg-[#255156] text-white rounded-lg hover:bg-[#1a3a3f]">
                        <i class="fas fa-save mr-2"></i>Créer l'événement
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection