@extends('base')

@section('title', 'Ajouter une catégorie')

@section('content')
<div class="max-w-2xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Ajouter une catégorie</h1>

    <form method="POST" action="{{ route('categories.store') }}" class="bg-white p-6 rounded-lg shadow">
        @csrf

        <div class="mb-4">
            <label class="block font-medium mb-2" for="name">Nom</label>
            <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded p-2" required>
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-2" for="description">Description</label>
            <textarea name="description" id="description" rows="4" class="w-full border border-gray-300 rounded p-2"></textarea>
            @error('description')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer</button>
        <a href="{{ route('categories.index') }}" class="ml-2 text-gray-600 hover:underline">Annuler</a>
    </form>
</div>
@endsection
