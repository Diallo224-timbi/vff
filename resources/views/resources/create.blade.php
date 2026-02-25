@extends('base')

@section('title', 'Ajouter une ressource')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-[#255156] to-[#8bbdc3] text-white p-4">
                <h1 class="text-xl font-bold">📤 Ajouter une ressource</h1>
            </div>   
            <form action="{{ route('resources.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="p-6">
                @csrf
                
                <div class="space-y-4">
                    <!-- Titre -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Titre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3] @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>    
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3] @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Fichier -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Fichier <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#8bbdc3] transition-colors">
                            <input type="file" 
                                   id="file" 
                                   name="file" 
                                   required
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.svg,.webp,.mp4,.webm,.avi,.mov,.mkv,.txt"
                                   class="hidden"
                                   onchange="updateFileName(this)">
                            <button type="button" 
                                    onclick="document.getElementById('file').click()"
                                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Choisir un fichier
                            </button>
                            <p id="file-name" class="text-sm text-gray-500 mt-2">Aucun fichier sélectionné</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Types acceptés : Images, Vidéos, Documents PDF/Word/Excel. Max 20 Mo
                        </p>
                        @error('file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Catégorie -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="category" 
                                required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3] @error('category') border-red-500 @enderror">
                            <option value="">Sélectionner une catégorie</option>
                            <option value="procedure" {{ old('category') == 'procedure' ? 'selected' : '' }}>📋 Procédure</option>
                            <option value="outil" {{ old('category') == 'outil' ? 'selected' : '' }}>🛠️ Outil</option>
                            <option value="fiche_reflexe" {{ old('category') == 'fiche_reflexe' ? 'selected' : '' }}>⚡ Fiche réflexe</option>
                            <option value="ressource" {{ old('category') == 'ressource' ? 'selected' : '' }}>📄 Ressource générale</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>   
                    <!-- Thème -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thème</label>
                        <input type="text" 
                               name="theme" 
                               value="{{ old('theme') }}"
                               placeholder="Ex: Violences conjugales, Juridique, Social..."
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                    </div>
                    
                    <!-- Service -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Service concerné</label>
                        <input type="text" 
                               name="service" 
                               value="{{ old('service') }}"
                               placeholder="Ex: Social, Justice, Santé..."
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#8bbdc3]">
                    </div>
                </div>
                
                <!-- Boutons -->
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <a href="{{ route('resources.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#255156] text-white rounded-lg hover:bg-[#1d4144] transition-colors">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0] ? input.files[0].name : 'Aucun fichier sélectionné';
    document.getElementById('file-name').textContent = fileName;
}
</script>
@endsection