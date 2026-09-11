@extends('base')

@section('title', 'Agenda partagé')

@section('content')
<div class="container mx-auto px-4 py-3">
    <!-- Messages de succès -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg" 
             role="alert" 
             style="z-index: 9999; min-width: 300px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        
        <script>
            setTimeout(function() {
                const alert = document.querySelector('.alert-success');
                if (alert) {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        </script>
    @endif

    <!-- Messages d'erreur -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg" 
             role="alert" 
             style="z-index: 9999; min-width: 300px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        
        <script>
            setTimeout(function() {
                const alert = document.querySelector('.alert-danger');
                if (alert) {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        </script>
    @endif

    <!-- Messages de validation -->
    @if($errors->any())
        <div class="position-fixed top-0 end-0 m-3" style="z-index: 9999; max-width: 400px;">
            @foreach($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show shadow-lg mb-2" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>{{ $error }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endforeach
        </div>
        
        <script>
            setTimeout(function() {
                document.querySelectorAll('.alert-danger').forEach(alert => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 300);
                });
            }, 8000);
        </script>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-3">
        <h1 class="text-xl font-bold text-gray-800">
            <i class="fas fa-calendar-alt text-[#255156] mr-2"></i>
            Agenda partagé
        </h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('events.calendrier') }}" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-200">
                <i class="fas fa-calendar-week mr-1"></i>Vue calendrier
            </a>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur' || auth()->user()->role === 'user')
            <button onclick="openCreateModal()" class="bg-[#255156] text-white px-3 py-1.5 rounded-lg text-sm hover:bg-[#1a3a3f]">
                <i class="fas fa-plus mr-1"></i>Nouvel événement
            </button>
            @endif
        </div>
    </div>

    <!-- Filtres avec auto-submit -->
    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm mb-3">
        <form method="GET" action="{{ route('events.index') }}" class="flex flex-wrap gap-2" id="filterForm">
            <select name="type" class="border rounded-lg px-2 py-1.5 text-sm filter-select">
                <option value="">Tous les types</option>
                <option value="réunion" {{ request('type') == 'réunion' ? 'selected' : '' }}>Réunions</option>
                <option value="formation" {{ request('type') == 'formation' ? 'selected' : '' }}>Formations</option>
                <option value="atelier" {{ request('type') == 'atelier' ? 'selected' : '' }}>Ateliers</option>
                <option value="autre" {{ request('type') == 'autre' ? 'selected' : '' }}>Autres</option>
            </select>

            <select name="periode" class="border rounded-lg px-2 py-1.5 text-sm filter-select">
                <option value="">Toutes les périodes</option>
                <option value="a_venir" {{ request('periode') == 'a_venir' ? 'selected' : '' }}>À venir</option>
                <option value="passes" {{ request('periode') == 'passes' ? 'selected' : '' }}>Passés</option>
            </select>

            <button type="submit" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-200">
                <i class="fas fa-filter mr-1"></i>Filtrer
            </button>

            <a href="{{ route('events.index') }}" class="text-gray-500 text-sm px-3 py-1.5">Réinitialiser</a>
        </form>
    </div>

    <!-- Liste des événements -->
    <div class="grid gap-3">
        @forelse($events as $event)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition p-3" id="event-{{ $event->id }}">
            <div class="flex flex-col md:flex-row md:items-center gap-3">
                <!-- Date -->
                <div class="flex items-center gap-2 md:w-44">
                    <div class="w-9 h-9 bg-[#255156] rounded-lg flex items-center justify-center text-white shrink-0">
                        <i class="bx bx-calendar-day"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-sm">du {{ $event->date_debut->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">au {{ $event->date_fin->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $event->date_debut->format('H:i') }} - {{ $event->date_fin->format('H:i') }}</div>
                    </div>
                </div>

                <!-- Infos principales -->
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            @if($event->type == 'réunion') bg-blue-100 text-blue-700
                            @elseif($event->type == 'formation')
                            @elseif($event->type == 'atelier')
                            @else
                            @endif">
                            {{ ucfirst($event->type) }}
                        </span>
                        @if($event->date_debut >= now())
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">
                                À venir
                            </span>
                        @elseif($event->date_fin < now())
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs">
                                Passé
                            </span>
                        @endif
                    </div>
                    
                    <h2 class="text-base font-semibold text-gray-800">{{ $event->titre }}</h2>
                    
                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 mt-1">
                        <span><i class="fas fa-map-marker-alt text-gray-400 w-4 mr-1"></i>{{ $event->lieu ?? 'Lieu non précisé' }}</span>
                        <span><i class="fas fa-user text-gray-400 w-4 mr-1"></i>{{ $event->organisateur ?? 'Organisateur non précisé' }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 shrink-0">
                    <button onclick='openShowModal({{ $event->id }}, {{ json_encode([
                        "titre" => $event->titre,
                        "description" => $event->description,
                        "date_debut" => $event->date_debut->format("d/m/Y H:i"),
                        "date_fin" => $event->date_fin->format("d/m/Y H:i"),
                        "type" => $event->type,
                        "lieu" => $event->lieu,
                        "organisateur" => $event->organisateur,
                        "created_at" => $event->created_at->format("d/m/Y"),
                        "cree_par" => $event->createur->name ?? "Inconnu"
                    ]) }})' 
                            class="bg-[#255156] text-white px-2.5 py-1.5 rounded-lg text-sm hover:bg-[#1a3a3f]">
                        <i class="fas fa-eye"></i>
                    </button>
                   
                    @if(auth()->user()->role === 'admin' || auth()->user()->id === $event->cree_par)
                        <button onclick='openEditModal({{ $event->id }}, {{ json_encode([
                            "titre" => $event->titre,
                            "description" => $event->description,
                            "date_debut" => $event->date_debut->format("Y-m-d\TH:i"),
                            "date_fin" => $event->date_fin->format("Y-m-d\TH:i"),
                            "type" => $event->type,
                            "lieu" => $event->lieu,
                            "organisateur" => $event->organisateur
                        ]) }})' 
                                class="bg-yellow-500 text-white px-2.5 py-1.5 rounded-lg text-sm hover:bg-yellow-600">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick='openDeleteModal({{ $event->id }}, "{{ $event->titre }}")' 
                                class="bg-red-500 text-white px-2.5 py-1.5 rounded-lg text-sm hover:bg-red-600">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white p-6 rounded-lg border border-gray-200 text-center">
            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Aucun événement trouvé</p>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur')
            <button onclick="openCreateModal()" class="inline-block mt-4 text-[#255156] hover:underline">
                Créer le premier événement
            </button>
            @endif
        </div>
        @endforelse

        <!-- Pagination -->
        <div class="mt-3">
            {{ $events->links() }}
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALE SHOW (AFFICHAGE) -->
<!-- ============================================ -->
<div id="showModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4" style="display: none;">
    <div class="bg-white rounded-xl w-full max-w-2xl mx-auto max-h-[90vh] overflow-y-auto">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-[#255156]"></i>
                    Détails de l'événement
                </h3>
                <button onclick="closeShowModal()" class="text-gray-400 hover:text-gray-600 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-3">
                <!-- Titre -->
                <div class="border-b border-gray-100 pb-2">
                    <h2 id="show_titre" class="text-xl font-bold text-gray-800"></h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span id="show_type" class="px-2 py-0.5 rounded-full text-xs font-medium"></span>
                        <span id="show_statut" class="px-2 py-0.5 rounded-full text-xs font-medium"></span>
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <label class="text-sm font-medium text-gray-500">Description</label>
                    <p id="show_description" class="text-gray-700 mt-1 whitespace-pre-line text-sm"></p>
                </div>
                
                <!-- Infos -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date de début</label>
                        <p id="show_date_debut" class="text-gray-700 font-semibold text-sm"></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date de fin</label>
                        <p id="show_date_fin" class="text-gray-700 font-semibold text-sm"></p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Lieu</label>
                        <p id="show_lieu" class="text-gray-700 text-sm"></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Organisateur</label>
                        <p id="show_organisateur" class="text-gray-700 text-sm"></p>
                    </div>
                </div>
                
                <div class="pt-2 border-t border-gray-100 text-sm text-gray-400 flex items-center gap-2">
                    <i class="fas fa-user-circle"></i>
                    <span>Créé par <span id="show_cree_par"></span></span>
                    <span class="mx-1">•</span>
                    <i class="far fa-calendar-alt"></i>
                    <span>le <span id="show_created_at"></span></span>
                </div>
            </div>
            
            <div class="flex justify-end mt-4 pt-3 border-t border-gray-100">
                <button onclick="closeShowModal()" class="px-3 py-1.5 bg-[#255156] text-white rounded-lg hover:bg-[#1a3a3f] text-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALE CRÉATION -->
<!-- ============================================ -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4" style="display: none;">
    <div class="bg-white rounded-xl w-full max-w-lg mx-auto max-h-[90vh] overflow-y-auto">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-plus-circle text-[#255156] mr-2"></i>
                    Nouvel événement
                </h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('events.store') }}" method="POST" id="createForm" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                        <input type="text" name="titre" required 
                               class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" 
                                  class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Début *</label>
                            <input type="datetime-local" name="date_debut" required 
                                   class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fin *</label>
                            <input type="datetime-local" name="date_fin" required 
                                   class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" required 
                                class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                            <option value="réunion">Réunion</option>
                            <option value="formation">Formation</option>
                            <option value="atelier">Atelier</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                            <input type="text" name="lieu" 
                                   class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Organisateur</label>
                            <input type="text" name="organisateur" 
                                   class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pièce jointe</label>
                        <input type="file" name="piece_jointe" 
                               accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                               class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-[#255156] file:text-white hover:file:bg-[#1a3a3f]">
                        <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, PNG, JPG (Max 5MB)</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4 pt-3 border-t">
                    <button type="button" onclick="closeCreateModal()" 
                            class="px-3 py-1.5 text-gray-600 border rounded-lg hover:bg-gray-50 text-sm order-2 sm:order-1">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-3 py-1.5 bg-[#255156] text-white rounded-lg hover:bg-[#1a3a3f] text-sm flex items-center justify-center gap-2 order-1 sm:order-2">
                        <i class="fas fa-save"></i>
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALE ÉDITION -->
<!-- ============================================ -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4" style="display: none;">
    <div class="bg-white rounded-xl w-full max-w-lg mx-auto max-h-[90vh] overflow-y-auto">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-edit text-[#255156] mr-2"></i>
                    Modifier l'événement
                </h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                        <input type="text" name="titre" id="edit_titre" required 
                               class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="3" 
                                  class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Début *</label>
                            <input type="datetime-local" name="date_debut" id="edit_date_debut" required 
                                   class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fin *</label>
                            <input type="datetime-local" name="date_fin" id="edit_date_fin" required 
                                   class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" id="edit_type" required 
                                class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                            <option value="réunion">Réunion</option>
                            <option value="formation">Formation</option>
                            <option value="atelier">Atelier</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                            <input type="text" name="lieu" id="edit_lieu" 
                                   class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Organisateur</label>
                            <input type="text" name="organisateur" id="edit_organisateur" 
                                   class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pièce jointe</label>
                        <input type="file" name="piece_jointe" id="edit_piece_jointe"
                               accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                               class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-[#255156] file:text-white hover:file:bg-[#1a3a3f]">
                        <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, PNG, JPG (Max 5MB)</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4 pt-3 border-t">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-3 py-1.5 text-gray-600 border rounded-lg hover:bg-gray-50 text-sm order-2 sm:order-1">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-3 py-1.5 bg-[#255156] text-white rounded-lg hover:bg-[#1a3a3f] text-sm flex items-center justify-center gap-2 order-1 sm:order-2">
                        <i class="fas fa-save"></i>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALE SUPPRESSION -->
<!-- ============================================ -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4" style="display: none;">
    <div class="bg-white rounded-xl w-full max-w-md mx-auto">
        <div class="p-4 text-center">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-trash text-red-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Confirmer la suppression</h3>
            <p class="text-gray-600 text-sm mb-4">
                Êtes-vous sûr de vouloir supprimer l'événement <br>
                <span id="delete_titre" class="font-semibold text-gray-800"></span> ?
                <br><span class="text-xs text-red-500">Cette action est irréversible.</span>
            </p>
            
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-1.5 text-gray-600 border rounded-lg hover:bg-gray-50 text-sm">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i>
                        Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes pulse-text {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.05); }
    100% { opacity: 1; transform: scale(1); }
}

.animate-en-cours {
    animation: pulse-text 1.5s ease-in-out infinite;
}

#createModal, #editModal, #showModal, #deleteModal {
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.modal-open {
    overflow: hidden;
}

/* ============================================
   COMPACTAGE POUR 1920x1080 @ 125% (~1536x864 CSS)
   ============================================ */

/* Ajustements fins des contrôles de formulaire Tailwind */
@media screen and (min-width: 1500px) and (max-width: 1600px)
              and (min-height: 850px) and (max-height: 900px) {

    /* Événements : padding encore plus compact */
    #event-{{ $event->id ?? '' }} {
        padding: 0.6rem !important;
    }

    /* Selects filtres */
    .filter-select {
        padding: 5px 10px !important;
        font-size: 0.82rem !important;
    }

    /* Boutons actions dans les cartes */
    button[onclick^="openShowModal"],
    button[onclick^="openEditModal"],
    button[onclick^="openDeleteModal"] {
        padding: 5px 10px !important;
        font-size: 0.8rem !important;
    }

    /* Modales : padding réduit */
    #createModal .p-4,
    #editModal .p-4,
    #showModal .p-4,
    #deleteModal .p-4 {
        padding: 1rem !important;
    }
}
</style>

<script>
// === GESTION DES FILTRES AUTO ===
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
});

// === MODALE SHOW (AFFICHAGE) ===
function openShowModal(eventId, eventData) {
    document.getElementById('show_titre').textContent = eventData.titre || '';
    document.getElementById('show_description').textContent = eventData.description || 'Aucune description';
    document.getElementById('show_date_debut').textContent = eventData.date_debut || '';
    document.getElementById('show_date_fin').textContent = eventData.date_fin || '';
    document.getElementById('show_lieu').textContent = eventData.lieu || 'Non précisé';
    document.getElementById('show_organisateur').textContent = eventData.organisateur || 'Non précisé';
    document.getElementById('show_cree_par').textContent = eventData.cree_par || 'Inconnu';
    document.getElementById('show_created_at').textContent = eventData.created_at || '';
    
    // Type
    const typeBadge = document.getElementById('show_type');
    const type = eventData.type || 'autre';
    const typeColors = {
        'réunion': 'bg-blue-100 text-blue-700',
        'formation': 'bg-purple-100 text-purple-700',
        'atelier': 'bg-orange-100 text-orange-700',
        'autre': 'bg-gray-100 text-gray-700'
    };
    typeBadge.textContent = type.charAt(0).toUpperCase() + type.slice(1);
    typeBadge.className = 'px-2 py-0.5 rounded-full text-xs font-medium ' + (typeColors[type] || typeColors['autre']);
    
    // Statut
    const statutBadge = document.getElementById('show_statut');
    if (eventData.date_debut) {
        statutBadge.textContent = '';
        statutBadge.className = 'hidden';
    }
    
    document.getElementById('showModal').style.display = 'flex';
    document.body.classList.add('modal-open');
}

function closeShowModal() {
    document.getElementById('showModal').style.display = 'none';
    document.body.classList.remove('modal-open');
}

// === MODALE CRÉATION ===
function openCreateModal() {
    document.getElementById('createModal').style.display = 'flex';
    document.body.classList.add('modal-open');
}

function closeCreateModal() {
    document.getElementById('createModal').style.display = 'none';
    document.body.classList.remove('modal-open');
    document.getElementById('createForm').reset();
}

// === MODALE ÉDITION ===
function openEditModal(eventId, eventData) {
    document.getElementById('edit_titre').value = eventData.titre || '';
    document.getElementById('edit_description').value = eventData.description || '';
    document.getElementById('edit_date_debut').value = eventData.date_debut || '';
    document.getElementById('edit_date_fin').value = eventData.date_fin || '';
    document.getElementById('edit_type').value = eventData.type || 'réunion';
    document.getElementById('edit_lieu').value = eventData.lieu || '';
    document.getElementById('edit_organisateur').value = eventData.organisateur || '';
    
    document.getElementById('editForm').action = `/events/${eventId}`;
    
    document.getElementById('editModal').style.display = 'flex';
    document.body.classList.add('modal-open');
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.body.classList.remove('modal-open');
}

// === MODALE SUPPRESSION ===
function openDeleteModal(eventId, titre) {
    document.getElementById('delete_titre').textContent = titre || '';
    document.getElementById('deleteForm').action = `/events/${eventId}`;
    
    document.getElementById('deleteModal').style.display = 'flex';
    document.body.classList.add('modal-open');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.body.classList.remove('modal-open');
}

// === FERMETURE EN CLIQUANT EN DEHORS ===
document.addEventListener('click', function(event) {
    const modals = ['createModal', 'editModal', 'showModal', 'deleteModal'];
    modals.forEach(id => {
        const modal = document.getElementById(id);
        if (event.target === modal) {
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
            if (id === 'createModal') document.getElementById('createForm').reset();
        }
    });
});

// Empêcher la fermeture en cliquant à l'intérieur
document.querySelectorAll('#createModal .bg-white, #editModal .bg-white, #showModal .bg-white, #deleteModal .bg-white').forEach(modal => {
    modal.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>

@endsection