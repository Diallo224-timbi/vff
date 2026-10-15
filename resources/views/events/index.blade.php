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
            <a href="{{ route('events.calendrier', request()->only(['type', 'periode'])) }}" 
               class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-200 transition">
                <i class="fas fa-calendar-week mr-1"></i>Vue calendrier
            </a>
        
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur' || auth()->user()->role === 'user')
            <button onclick="openCreateModal()" class="bg-[#255156] text-white px-3 py-1.5 rounded-lg text-sm hover:bg-[#1a3a3f] transition">
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
            <button type="submit" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-sm hover:bg-gray-200 transition">
                <i class="fas fa-filter mr-1"></i>Filtrer
            </button>
            <a href="{{ route('events.index') }}" class="text-gray-500 text-sm px-3 py-1.5">Réinitialiser</a>
        </form>
    </div>
    
    <!-- Liste des événements -->
    <div class="grid gap-3">
        @forelse($events as $event)
        @php
            $canEdit = auth()->user()->role === 'admin' 
                    || auth()->user()->role === 'moderateur'
                    || auth()->user()->id === $event->cree_par;

            $eventShowData = [
                'titre'         => $event->titre,
                'description'   => $event->description,
                'date_debut'    => $event->date_debut->format('d/m/Y H:i'),
                'date_fin'      => $event->date_fin->format('d/m/Y H:i'),
                'type'          => $event->type,
                'lieu'          => $event->lieu,
                'organisateur'  => $event->organisateur,
                'created_at'    => $event->created_at->format('d/m/Y'),
                'cree_par'      => $event->createur->name ?? 'Inconnu',
                'piece_jointe'  => $event->piece_jointe ? asset('storage/' . $event->piece_jointe) : null,
            ];

            $eventEditData = [
                'titre'         => $event->titre,
                'description'   => $event->description,
                'date_debut'    => $event->date_debut->format('Y-m-d\TH:i'),
                'date_fin'      => $event->date_fin->format('Y-m-d\TH:i'),
                'type'          => $event->type,
                'lieu'          => $event->lieu,
                'organisateur'  => $event->organisateur,
            ];
        @endphp
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition p-3" id="event-{{ $event->id }}">
            <div class="flex flex-col md:flex-row md:items-center gap-3">
                <div class="flex items-center gap-2 md:w-44">
                    <div class="w-9 h-9 bg-[#255156] rounded-lg flex items-center justify-center text-white shrink-0">
                        <i class="bx bx-calendar-day"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-sm">Du {{ $event->date_debut->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">Au {{ $event->date_fin->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $event->date_debut->format('H:i') }} - {{ $event->date_fin->format('H:i') }}</div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            @if($event->type == 'réunion') bg-blue-100 text-blue-700
                            @elseif($event->type == 'formation') bg-emerald-100 text-emerald-700
                            @elseif($event->type == 'atelier') bg-amber-100 text-amber-700
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
                    {{-- Bouton Voir --}}
                    <button type="button"
                            onclick="openShowModal({{ $event->id }}, {{ Js::from($eventShowData) }})"
                            class="bg-[#255156] text-white px-2.5 py-1.5 rounded-lg text-sm hover:bg-[#1a3a3f] transition"
                            title="Voir les détails">
                        <i class="fas fa-eye"></i>
                    </button>                   
                    
                    @if($canEdit)
                        {{-- Bouton Modifier --}}
                        <button type="button"
                                onclick="openEditModal({{ $event->id }}, {{ Js::from($eventEditData) }}, '{{ route('events.update', $event->id) }}')"
                                class="bg-yellow-500 text-white px-2.5 py-1.5 rounded-lg text-sm hover:bg-yellow-600 transition"
                                title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        {{-- Bouton Supprimer --}}
                        <button type="button"
                                onclick="openDeleteModal({{ $event->id }}, {{ Js::from($event->titre) }}, '{{ route('events.destroy', $event->id) }}')"
                                class="bg-red-500 text-white px-2.5 py-1.5 rounded-lg text-sm hover:bg-red-600 transition"
                                title="Supprimer">
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
<!-- MODALE SHOW (AFFICHAGE) - CENTRÉE FORCÉE -->
<!-- ============================================ -->
<div id="showModal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); overflow-y: auto;">
    <!-- Conteneur de centrage -->
    <div style="display: flex !important; align-items: center !important; justify-content: center !important; min-height: 100vh !important; width: 100% !important; padding: 16px !important; margin: 0 !important;">
        <div id="showModalBox"
            style="background: #fff; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); display: flex; flex-direction: column; overflow: hidden; resize: both; width: 520px; min-width: 360px; max-width: 95vw; max-height: 85vh; margin: 0 auto;">

            <!-- Header compact -->
            <div style="padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-shrink: 0; border-bottom: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                    <span id="show_type"
                        style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; background: #f3f4f6; color: #374151;"></span>
                    <h2 id="show_titre" style="font-size: 14px; font-weight: 600; color: #1f2937; margin: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></h2>
                </div>
                <button type="button" onclick="closeShowModal()"
                        style="color: #9ca3af; background: none; border: none; cursor: pointer; padding: 4px; border-radius: 4px; flex-shrink: 0;">
                    <i class="fas fa-times" style="font-size: 14px;"></i>
                </button>
            </div>
            
            <!-- Corps compact -->
            <div style="padding: 20px; display: flex; flex-direction: column; gap: 12px; flex: 1; min-height: 0; overflow-y: auto;">
                
                <!-- Dates -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: #eff6ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                            <i class="fas fa-play" style="color: #3b82f6; font-size: 10px;"></i>
                        </div>
                        <div style="min-width: 0;">
                            <p style="font-size: 10px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 2px 0;">Début</p>
                            <p id="show_date_debut" style="font-size: 12px; font-weight: 600; color: #1f2937; margin: 0; line-height: 1.3;"></p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: #fef2f2; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                            <i class="fas fa-stop" style="color: #ef4444; font-size: 10px;"></i>
                        </div>
                        <div style="min-width: 0;">
                            <p style="font-size: 10px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 2px 0;">Fin</p>
                            <p id="show_date_fin" style="font-size: 12px; font-weight: 600; color: #1f2937; margin: 0; line-height: 1.3;"></p>
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px solid #f3f4f6;"></div>
                
                <!-- Lieu + Organisateur -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: #fff7ed; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                            <i class="fas fa-map-marker-alt" style="color: #f97316; font-size: 10px;"></i>
                        </div>
                        <div style="min-width: 0;">
                            <p style="font-size: 10px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 2px 0;">Lieu</p>
                            <p id="show_lieu" style="font-size: 12px; font-weight: 600; color: #1f2937; margin: 0; line-height: 1.3; word-wrap: break-word;"></p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: #faf5ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                            <i class="fas fa-user" style="color: #a855f7; font-size: 10px;"></i>
                        </div>
                        <div style="min-width: 0;">
                            <p style="font-size: 10px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 2px 0;">Organisateur</p>
                            <p id="show_organisateur" style="font-size: 12px; font-weight: 600; color: #1f2937; margin: 0; line-height: 1.3; word-wrap: break-word;"></p>
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px solid #f3f4f6;"></div>
                
                <!-- Description -->
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <div style="width: 28px; height: 28px; border-radius: 6px; background: #f9fafb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                        <i class="fas fa-align-left" style="color: #6b7280; font-size: 10px;"></i>
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <p style="font-size: 10px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 4px 0;">Description</p>
                        <div id="show_description" style="font-size: 12px; color: #374151; line-height: 1.6; white-space: pre-line; word-wrap: break-word;"></div>
                    </div>
                </div>
                
                <!-- Pièce jointe -->
                <div id="show_piece_jointe_wrapper" style="display: none;">
                    <div style="border-top: 1px solid #f3f4f6; padding-top: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 28px; height: 28px; border-radius: 6px; background: #ecfdf5; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-paperclip" style="color: #10b981; font-size: 10px;"></i>
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <p style="font-size: 10px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 2px 0;">Pièce jointe</p>
                                <a id="show_piece_jointe" href="#" target="_blank"
                                   style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #047857; text-decoration: none;">
                                    <i class="fas fa-download" style="font-size: 10px;"></i>
                                    <span>Télécharger</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer compact -->
            <div style="padding: 10px 20px; background: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 12px; font-size: 10px; color: #6b7280; min-width: 0;">
                    <span style="display: flex; align-items: center; gap: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <i class="fas fa-user-circle" style="color: #9ca3af;"></i>
                        <span id="show_cree_par" style="font-weight: 500; color: #4b5563;"></span>
                    </span>
                    <span style="display: flex; align-items: center; gap: 4px; flex-shrink: 0;">
                        <i class="far fa-calendar-alt" style="color: #9ca3af;"></i>
                        <span id="show_created_at" style="font-weight: 500; color: #4b5563;"></span>
                    </span>
                </div>
                <button type="button" onclick="closeShowModal()"
                        style="padding: 6px 12px; background: #255156; color: white; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; flex-shrink: 0;">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALE CRÉATION - CENTRÉE FORCÉE -->
<!-- ============================================ -->
<div id="createModal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); overflow-y: auto;">
    <div style="display: flex !important; align-items: center !important; justify-content: center !important; min-height: 100vh !important; width: 100% !important; padding: 16px !important; margin: 0 !important;">
        <div style="background: white; border-radius: 16px; width: 100%; max-width: 512px; margin: 0 auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); max-height: 95vh; display: flex; flex-direction: column;">
            <div style="padding: 16px; overflow: hidden; display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-shrink: 0;">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0;">
                        <i class="fas fa-plus-circle" style="color: #255156; margin-right: 8px;"></i>Nouvel événement
                    </h3>
                    <button onclick="closeCreateModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer; font-size: 20px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('events.store') }}" method="POST" id="createForm" enctype="multipart/form-data" style="display: flex; flex-direction: column; min-height: 0;">
                    @csrf
                    <div style="display: flex; flex-direction: column; gap: 8px; overflow-y: auto; padding-right: 4px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Titre *</label>
                            <input type="text" name="titre" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Description</label>
                            <textarea name="description" rows="2" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none; resize: none;"></textarea>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Début *</label>
                                <input type="datetime-local" name="date_debut" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Fin *</label>
                                <input type="datetime-local" name="date_fin" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Type *</label>
                            <select name="type" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                                <option value="réunion">Réunion</option>
                                <option value="formation">Formation</option>
                                <option value="atelier">Atelier</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Lieu</label>
                                <input type="text" name="lieu" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Organisateur</label>
                                <input type="text" name="organisateur" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: row; justify-content: flex-end; gap: 8px; margin-top: 12px; padding-top: 12px; border-top: 1px solid #e5e7eb; flex-shrink: 0;">
                        <button type="button" onclick="closeCreateModal()" style="padding: 6px 12px; color: #4b5563; background: white; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; cursor: pointer;">Annuler</button>
                        <button type="submit" style="padding: 6px 12px; background: #255156; color: white; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-save"></i>Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALE ÉDITION - CENTRÉE FORCÉE -->
<!-- ============================================ -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); overflow-y: auto;">
    <div style="display: flex !important; align-items: center !important; justify-content: center !important; min-height: 100vh !important; width: 100% !important; padding: 16px !important; margin: 0 !important;">
        <div style="background: white; border-radius: 16px; width: 100%; max-width: 512px; margin: 0 auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); max-height: 95vh; display: flex; flex-direction: column;">
            <div style="padding: 16px; overflow: hidden; display: flex; flex-direction: column;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-shrink: 0;">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0;">
                        <i class="fas fa-edit" style="color: #255156; margin-right: 8px;"></i>Modifier l'événement
                    </h3>
                    <button onclick="closeEditModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer; font-size: 20px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; min-height: 0;">
                    @csrf
                    @method('PUT')
                    <div style="display: flex; flex-direction: column; gap: 8px; overflow-y: auto; padding-right: 4px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Titre *</label>
                            <input type="text" name="titre" id="edit_titre" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Description</label>
                            <textarea name="description" id="edit_description" rows="2" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none; resize: none;"></textarea>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Début *</label>
                                <input type="datetime-local" name="date_debut" id="edit_date_debut" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Fin *</label>
                                <input type="datetime-local" name="date_fin" id="edit_date_fin" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Type *</label>
                            <select name="type" id="edit_type" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                                <option value="réunion">Réunion</option>
                                <option value="formation">Formation</option>
                                <option value="atelier">Atelier</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Lieu</label>
                                <input type="text" name="lieu" id="edit_lieu" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Organisateur</label>
                                <input type="text" name="organisateur" id="edit_organisateur" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 4px;">Pièce jointe</label>
                            <input type="file" name="piece_jointe" id="edit_piece_jointe" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 6px 10px; font-size: 14px; outline: none;">
                            <p style="font-size: 12px; color: #9ca3af; margin-top: 4px;">PDF, DOC, DOCX, PNG, JPG (Max 5MB) — Laisser vide pour conserver l'actuelle</p>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: row; justify-content: flex-end; gap: 8px; margin-top: 12px; padding-top: 12px; border-top: 1px solid #e5e7eb; flex-shrink: 0;">
                        <button type="button" onclick="closeEditModal()" style="padding: 6px 12px; color: #4b5563; background: white; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; cursor: pointer;">Annuler</button>
                        <button type="submit" style="padding: 6px 12px; background: #255156; color: white; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-save"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALE SUPPRESSION - CENTRÉE FORCÉE -->
<!-- ============================================ -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); overflow-y: auto;">
    <div style="display: flex !important; align-items: center !important; justify-content: center !important; min-height: 100vh !important; width: 100% !important; padding: 16px !important; margin: 0 !important;">
        <div style="background: white; border-radius: 16px; width: 100%; max-width: 448px; margin: 0 auto; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="padding: 16px; text-align: center;">
                <div style="width: 56px; height: 56px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                    <i class="fas fa-trash" style="color: #ef4444; font-size: 20px;"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0 0 8px 0;">Confirmer la suppression</h3>
                <p style="color: #4b5563; font-size: 14px; margin-bottom: 16px;">
                    Êtes-vous sûr de vouloir supprimer l'événement <br>
                    <span id="delete_titre" style="font-weight: 600; color: #1f2937;"></span> ?
                    <br><span style="font-size: 12px; color: #ef4444;">Cette action est irréversible.</span>
                </p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div style="display: flex; flex-direction: row; justify-content: center; gap: 12px;">
                        <button type="button" onclick="closeDeleteModal()" style="padding: 6px 16px; color: #4b5563; background: white; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; cursor: pointer;">Annuler</button>
                        <button type="submit" style="padding: 6px 16px; background: #ef4444; color: white; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-trash"></i>Supprimer
                        </button>
                    </div>
                </form>
            </div>
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
.modal-open { overflow: hidden !important; }

/* FORCE LE CENTRAGE ABSOLU */
#createModal, #editModal, #showModal, #deleteModal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 999999 !important;
    margin: 0 !important;
    padding: 0 !important;
    transform: none !important;
}

/* Force le conteneur interne à centrer */
#createModal > div, #editModal > div, #showModal > div, #deleteModal > div {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 100vh !important;
    width: 100% !important;
    margin: 0 !important;
}

#showModalBox {
    resize: both;
    overflow: hidden;
}
#showModalBox::-webkit-resizer {
    background: linear-gradient(135deg,
        transparent 45%, #255156 45%, #255156 55%,
        transparent 55%, transparent 70%,
        #255156 70%, #255156 80%, transparent 80%);
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

    const descriptionEl = document.getElementById('show_description');
    if (eventData.description && eventData.description.trim() !== '') {
        descriptionEl.textContent = eventData.description;
        descriptionEl.style.fontStyle = 'normal';
        descriptionEl.style.color = '#374151';
    } else {
        descriptionEl.textContent = 'Aucune description fournie.';
        descriptionEl.style.fontStyle = 'italic';
        descriptionEl.style.color = '#9ca3af';
    }

    document.getElementById('show_date_debut').textContent = eventData.date_debut || '';
    document.getElementById('show_date_fin').textContent = eventData.date_fin || '';
    document.getElementById('show_lieu').textContent = eventData.lieu || 'Non précisé';
    document.getElementById('show_organisateur').textContent = eventData.organisateur || 'Non précisé';
    document.getElementById('show_cree_par').textContent = eventData.cree_par || 'Inconnu';
    document.getElementById('show_created_at').textContent = eventData.created_at || '';

    const pjWrapper = document.getElementById('show_piece_jointe_wrapper');
    const pjLink = document.getElementById('show_piece_jointe');
    if (eventData.piece_jointe) {
        pjWrapper.style.display = 'block';
        pjLink.href = eventData.piece_jointe;
    } else {
        pjWrapper.style.display = 'none';
        pjLink.href = '#';
    }

    const typeBadge = document.getElementById('show_type');
    const type = (eventData.type || 'autre').toLowerCase();
    typeBadge.textContent = type;

    const typeColors = {
        'réunion':   { bg: '#eff6ff', color: '#1d4ed8' },
        'formation': { bg: '#ecfdf5', color: '#047857' },
        'atelier':   { bg: '#fffbeb', color: '#b45309' },
        'autre':     { bg: '#f3f4f6', color: '#374151' },
    };
    const colors = typeColors[type] || typeColors['autre'];
    typeBadge.style.background = colors.bg;
    typeBadge.style.color = colors.color;

    const box = document.getElementById('showModalBox');
    box.style.width = '520px';
    box.style.maxHeight = '85vh';

    document.getElementById('showModal').style.display = 'block';
    document.body.classList.add('modal-open');
}

function closeShowModal() {
    document.getElementById('showModal').style.display = 'none';
    document.body.classList.remove('modal-open');
}

// === MODALE CRÉATION ===
function openCreateModal() {
    document.getElementById('createModal').style.display = 'block';
    document.body.classList.add('modal-open');
}
function closeCreateModal() {
    document.getElementById('createModal').style.display = 'none';
    document.body.classList.remove('modal-open');
    document.getElementById('createForm').reset();
}

// === MODALE ÉDITION ===
function openEditModal(eventId, eventData, updateUrl) {
    document.getElementById('edit_titre').value = eventData.titre || '';
    document.getElementById('edit_description').value = eventData.description || '';
    document.getElementById('edit_date_debut').value = eventData.date_debut || '';
    document.getElementById('edit_date_fin').value = eventData.date_fin || '';
    document.getElementById('edit_type').value = eventData.type || 'réunion';
    document.getElementById('edit_lieu').value = eventData.lieu || '';
    document.getElementById('edit_organisateur').value = eventData.organisateur || '';
    
    const form = document.getElementById('editForm');
    form.action = updateUrl || `/events/${eventId}`;
    
    document.getElementById('edit_piece_jointe').value = '';
    
    document.getElementById('editModal').style.display = 'block';
    document.body.classList.add('modal-open');
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.body.classList.remove('modal-open');
}

// === MODALE SUPPRESSION ===
function openDeleteModal(eventId, titre, deleteUrl) {
    document.getElementById('delete_titre').textContent = titre || '';
    document.getElementById('deleteForm').action = deleteUrl || `/events/${eventId}`;
    document.getElementById('deleteModal').style.display = 'block';
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