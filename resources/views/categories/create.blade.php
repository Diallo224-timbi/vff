@extends('base')

@section('title', 'Ajouter une catégorie')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-[#2D2926]">Créer une catégorie</h1>
        <p class="text-gray-500 text-sm">Ajoutez une nouvelle catégorie pour organiser le forum</p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <!-- Bandeau -->
        <div class="px-6 py-4 text-white" style="background: linear-gradient(135deg, #255156, #1e7c86);">
            <h2 class="font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-folder-plus"></i> Nouvelle catégorie
            </h2>
        </div>
        <!-- Form -->
        <form method="POST" action="{{ route('categories.store') }}" class="p-6 space-y-5">
            @csrf
            <!-- Nom -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nom de la catégorie
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name"
                    placeholder="Ex: Annonces, Aide, Discussions..."
                    class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#008C95] focus:border-transparent outline-none transition"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Description
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4"
                    placeholder="Décrivez brièvement cette catégorie..."
                    class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#008C95] focus:border-transparent outline-none transition resize-none"
                ></textarea>

                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4">

                <a href="{{ route('forum.index') }}" 
                   class="text-gray-500 hover:text-gray-700 transition text-sm">
                    ← retour
                </a>

                <button 
                    type="submit"
                    class="bg-[#255156] text-white px-5 py-2 rounded-xl font-semibold shadow hover:scale-105 hover:bg-[#1e4347] transition duration-300 flex items-center gap-2"
                >
                    <i class="fas fa-check"></i> Créer
                </button>

            </div>
        </form>
    </div>
</div>
@endsection