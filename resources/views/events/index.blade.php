@extends('base')
@section('title', 'Agenda partagé')
@section('content')
<div class="container mx-auto px-4 py-3">
    <!-- Messages de succès -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg" 
             role="alert" style="z-index: 9999; min-width: 300px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div> 
        <script>
            setTimeout(function() {
                const alert = document.querySelector('.alert-success');
                if (alert) { alert.classList.remove('show'); setTimeout(() => alert.remove(), 300); }
            }, 5000);
        </script>
    @endif

    <!-- Messages d'erreur -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg" 
             role="alert" style="z-index: 9999; min-width: 300px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div> 
        <script>
            setTimeout(function() {
                const alert = document.querySelector('.alert-danger');
                if (alert) { alert.classList.remove('show'); setTimeout(() => alert.remove(), 300); }
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
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            @if($event->type == 'réunion') bg-blue-100 text-blue-700
                            @elseif($event->type == 'formation') bg-purple-100 text-purple-700
                            @elseif($event->type == 'atelier') bg-orange-100 text-orange-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($event->type) }}
                        </span>
                        @if($event->date_debut >= now())
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">À venir</span>
                        @elseif($event->date_fin < now())
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs">Passé</span>
                        @endif
                    </div>                   
                    <h2 class="text-base font-semibold text-gray-800">{{ $event->titre }}</h2>                   
                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 mt-1">
                        <span><i class="fas fa-map-marker-alt text-gray-400 w-4 mr-1"></i>{{ $event->lieu ?? 'Lieu non précisé' }}</span>
                        <span><i class="fas fa-user text-gray-400 w-4 mr-1"></i>{{ $event->organisateur ?? 'Organisateur non précisé' }}</span>
                    </div>
                </div>
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
        <div class="mt-3">{{ $events->links() }}</div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALE SHOW (AFFICHAGE) - COMPACTE + REDIMENSIONNABLE -->
<!-- ============================================ -->
<div id="showModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 p-4 overflow-y-auto" style="display: none;">
    <!-- Wrapper pour espace sous navbar + centrage horizontal -->
    <div class="flex justify-center pt-24 pb-8 min-h-full">
        <div id="showModalBox" class="bg-white rounded-xl shadow-2xl flex flex-col resize overflow-auto min-w-[400px] min-h-[320px] max-w-[95vw] max-h-[75vh] w-[520px] h-auto">
            
            <!-- Header unifié : Type + Titre + Fermer -->
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-start gap-3 shrink-0 bg-gradient-to-r from-[#255156] to-[#1a3a3f]">
                <div class="flex-1 min-w-0">
                    <span id="show_type" class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider mb-1 bg-white/20 text-white"></span>
                    <h2 id="show_titre" class="text-base font-bold text-white leading-snug"></h2>
                </div>
                <button onclick="closeShowModal()" class="text-white/70 hover:text-white transition shrink-0 mt-0.5">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Corps compact -->
            <div class="p-4 space-y-3 flex-1 overflow-y-auto">
                
                <!-- Infos en 2 colonnes -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- Colonne gauche : Dates -->
                    <div class="space-y-2.5">
                        <div class="flex items-start gap-2">
                            <div class="w-7 h-7 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fas fa-play text-[10px]"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Début</p>
                                <p id="show_date_debut" class="text-sm font-semibold text-gray-800 leading-tight"></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <div class="w-7 h-7 rounded-md bg-red-50 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fas fa-stop text-[10px]"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Fin</p>
                                <p id="show_date_fin" class="text-sm font-semibold text-gray-800 leading-tight"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite : Lieu + Organisateur -->
                    <div class="space-y-2.5">
                        <div class="flex items-start gap-2">
                            <div class="w-7 h-7 rounded-md bg-orange-50 text-orange-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fas fa-map-marker-alt text-[11px]"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Lieu</p>
                                <p id="show_lieu" class="text-sm text-gray-800 leading-tight"></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <div class="w-7 h-7 rounded-md bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fas fa-user text-[11px]"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Organisateur</p>
                                <p id="show_organisateur" class="text-sm text-gray-800 leading-tight"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description dans un encadré discret -->
                <div class="bg-gray-50 rounded-lg border border-gray-100 p-3">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Description</p>
                    <div class="text-sm text-gray-700 whitespace-pre-line leading-relaxed" id="show_description"></div>
                </div>
            </div>

            <!-- Footer ultra compact -->
            <div class="px-4 py-2.5 bg-gray-50 border-t border-gray-200 flex items-center justify-between gap-3 shrink-0">
                <div class="flex items-center gap-3 text-[11px] text-gray-500 min-w-0">
                    <span class="flex items-center gap-1 truncate">
                        <i class="fas fa-user-circle"></i>
                        <span id="show_cree_par" class="font-medium text-gray-700 truncate"></span>
                    </span>
                    <span class="flex items-center gap-1 shrink-0">
                        <i class="far fa-calendar-alt"></i>
                        <span id="show_created_at" class="font-medium text-gray-700"></span>
                    </span>
                </div>
                <button onclick="closeShowModal()" class="px-3 py-1.5 bg-[#255156] text-white rounded-lg hover:bg-[#1a3a3f] text-xs font-semibold transition shrink-0">
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
                    <i class="fas fa-plus-circle text-[#255156] mr-2"></i>Nouvel événement
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
                        <input type="text" name="titre" required class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Début *</label>
                            <input type="datetime-local" name="date_debut" required class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fin *</label>
                            <input type="datetime-local" name="date_fin" required class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" required class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                            <option value="réunion">Réunion</option>
                            <option value="formation">Formation</option>
                            <option value="atelier">Atelier</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                            <input type="text" name="lieu" class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Organisateur</label>
                            <input type="text" name="organisateur" class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pièce jointe</label>
                        <input type="file" name="piece_jointe" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                               class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-[#255156] file:text-white hover:file:bg-[#1a3a3f]">
                        <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, PNG, JPG (Max 5MB)</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4 pt-3 border-t">
                    <button type="button" onclick="closeCreateModal()" class="px-3 py-1.5 text-gray-600 border rounded-lg hover:bg-gray-50 text-sm order-2 sm:order-1">Annuler</button>
                    <button type="submit" class="px-3 py-1.5 bg-[#255156] text-white rounded-lg hover:bg-[#1a3a3f] text-sm flex items-center justify-center gap-2 order-1 sm:order-2">
                        <i class="fas fa-save"></i>Créer
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
                    <i class="fas fa-edit text-[#255156] mr-2"></i>Modifier l'événement
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
                        <input type="text" name="titre" id="edit_titre" required class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Début *</label>
                            <input type="datetime-local" name="date_debut" id="edit_date_debut" required class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fin *</label>
                            <input type="datetime-local" name="date_fin" id="edit_date_fin" required class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" id="edit_type" required class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                            <option value="réunion">Réunion</option>
                            <option value="formation">Formation</option>
                            <option value="atelier">Atelier</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                            <input type="text" name="lieu" id="edit_lieu" class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Organisateur</label>
                            <input type="text" name="organisateur" id="edit_organisateur" class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pièce jointe</label>
                        <input type="file" name="piece_jointe" id="edit_piece_jointe" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                               class="w-full border rounded-lg px-2.5 py-1.5 text-sm focus:ring-2 focus:ring-[#255156] focus:border-transparent file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-[#255156] file:text-white hover:file:bg-[#1a3a3f]">
                        <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, PNG, JPG (Max 5MB)</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4 pt-3 border-t">
                    <button type="button" onclick="closeEditModal()" class="px-3 py-1.5 text-gray-600 border rounded-lg hover:bg-gray-50 text-sm order-2 sm:order-1">Annuler</button>
                    <button type="submit" class="px-3 py-1.5 bg-[#255156] text-white rounded-lg hover:bg-[#1a3a3f] text-sm flex items-center justify-center gap-2 order-1 sm:order-2">
                        <i class="fas fa-save"></i>Mettre à jour
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
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-1.5 text-gray-600 border rounded-lg hover:bg-gray-50 text-sm">Annuler</button>
                    <button type="submit" class="px-4 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i>Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
#createModal, #editModal, #showModal, #deleteModal {
    animation: fadeIn 0.2s ease-in-out;
}
.modal-open { overflow: hidden; }

/* Poignée de redimensionnement visible sur la modale Show */
#showModalBox {
    resize: both;
}
#showModalBox::-webkit-resizer {
    background: linear-gradient(135deg, transparent 45%, #255156 45%, #255156 55%, transparent 55%, transparent 70%, #255156 70%, #255156 80%, transparent 80%);
    border-bottom-right-radius: 12px;
    width: 18px;
    height: 18px;
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
    document.getElementById('show_description').textContent = eventData.description || 'Aucune description fournie.';
    document.getElementById('show_date_debut').textContent = eventData.date_debut || '';
    document.getElementById('show_date_fin').textContent = eventData.date_fin || '';
    document.getElementById('show_lieu').textContent = eventData.lieu || 'Non précisé';
    document.getElementById('show_organisateur').textContent = eventData.organisateur || 'Non précisé';
    document.getElementById('show_cree_par').textContent = eventData.cree_par || 'Inconnu';
    document.getElementById('show_created_at').textContent = eventData.created_at || '';
    
    // Badge Type
    const typeBadge = document.getElementById('show_type');
    typeBadge.textContent = eventData.type || 'autre';
    
    // Réinitialiser la taille à l'ouverture
    const box = document.getElementById('showModalBox');
    box.style.width = '520px';
    box.style.height = 'auto';
    
    document.getElementById('showModal').style.display = 'block';
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
    const modalIds = ['createModal', 'editModal', 'showModal', 'deleteModal'];
    modalIds.forEach(id => {
        const modal = document.getElementById(id);
        if (event.target === modal) {
            if (id === 'showModal') {
                closeShowModal();
            } else {
                modal.style.display = 'none';
                document.body.classList.remove('modal-open');
                if (id === 'createModal') document.getElementById('createForm').reset();
            }
        }
    });
});

// Empêcher la fermeture en cliquant à l'intérieur
document.querySelectorAll('#createModal > div, #editModal > div, #showModalBox, #deleteModal > div').forEach(modal => {
    modal.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
</script>

@endsection