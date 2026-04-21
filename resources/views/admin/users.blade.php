@extends('base')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Messages de succès -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg shadow-sm"
             x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif
    <!-- HEADER avec bouton statistiques -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold text-gray-800">
            <i class="fas fa-users text-[#255156] mr-2"></i>
            Gestion des utilisateurs
        </h1>
        <div class="flex items-center gap-2">
            <button onclick="openStatsModal()" class="bg-[#255156] hover:bg-[#1d4144] text-white px-3 py-1.5 rounded-lg text-sm flex items-center gap-1">
                <i class="fas fa-chart-pie"></i>
                Statistiques
            </button>
            <span class="text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">{{ auth()->user()->role }}</span>
            <div class="w-8 h-8 rounded-full bg-[#255156] flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
    <!-- RECHERCHE -->
    <div class="mb-4">
        <input
            id="searchInput"
            type="text"
            placeholder="Rechercher par nom, email, structure..."
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-[#255156] focus:outline-none">
    </div>

    <!-- LISTE DES UTILISATEURS -->
    <div id="usersContainer" class="space-y-2">
        @forelse($users as $user)
        <div class="user-card bg-white border border-gray-200 rounded-lg p-3 hover:shadow-sm transition-shadow"
             data-search="{{ strtolower($user->prenom.' '.$user->name.' '.$user->email.' '.($user->structure->organisme ?? '')) }}">

            <!-- Infos principales -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#255156] flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-medium text-sm">{{ $user->prenom }} {{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <!-- État -->
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $user->etatV === 'valider' ? 'bg-green-100 text-green-700' :
                           ($user->etatV === 'bloqué' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($user->etatV) }}
                    </span>
                    <!-- Rôle -->
                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs">
                        {{ $user->role }}
                    </span>
                </div>
            </div>

            <!-- Structure de rattachement -->
            <div class="mt-1 text-xs text-gray-600">
                <i class="fas fa-building text-[#255156] mr-1"></i>
                {{ $user->structure->organisme ?? 'Aucune structure' }}
                @if($user->structure)
                    <span class="text-gray-400">{{ $user->structure->ville ?? '' }} ({{ $user->structure->code_postal }})</span>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-1 mt-2 pt-2 border-t border-gray-100">
                @if($user->etatV !== 'valider')
                <form method="POST" action="{{ route('admin.users.validate', $user->id) }}" class="inline">
                    @csrf
                    <button class="bg-green-500 hover:bg-green-600 text-white w-7 h-7 rounded flex items-center justify-center" title="Valider">
                        <i class="bx bx-check text-xs"></i>
                    </button>
                </form>
                @endif

                @if($user->etatV === 'bloqué')
                <button type="button" onclick="showReason('{{ addslashes($user->block_reason) }}')"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white w-7 h-7 rounded flex items-center justify-center" title="Voir motif">
                    <i class="bx bx-info-circle text-xs"></i>
                </button>
                @endif

                <button type="button" onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->prenom) }}', '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->adresse }}', '{{ $user->ville }}', '{{ $user->code_postal }}', '{{ $user->id_structure }}', '{{ $user->role }}')"
                        class="bg-blue-500 hover:bg-blue-600 text-white w-7 h-7 rounded flex items-center justify-center" title="Modifier">
                    <i class="bx bx-edit text-xs"></i>
                </button>

                @if($user->etatV !== 'bloqué')
                <button type="button" onclick="openBlockModal({{ $user->id }}, '{{ addslashes($user->prenom) }} {{ addslashes($user->name) }}')"
                        class="bg-red-500 hover:bg-red-600 text-white w-7 h-7 rounded flex items-center justify-center" title="Bloquer">
                    <i class="bx bx-block text-xs"></i>
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-500">
            Aucun utilisateur trouvé
        </div>
        @endforelse
    </div>
</div>

<!-- MODAL STATISTIQUES -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50" id="statsModal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
        <div class="bg-[#255156] text-white px-4 py-3 flex justify-between items-center">
            <h3 class="font-semibold">
                <i class="fas fa-chart-pie mr-2"></i>
                Statistiques des utilisateurs
            </h3>
            <button onclick="closeStatsModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            @php
                $total = $users->count();
                $valides = $users->where('etatV', 'valider')->count();
                $attente = $users->where('etatV', 'attente')->count();
                $bloques = $users->where('etatV', 'bloqué')->count();
                $admins = $users->where('role', 'admin')->count();
                $moderateurs = $users->where('role', 'moderateur')->count();
                $utilisateurs = $users->where('role', 'user')->count();
            @endphp

            <!-- Cartes stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <div class="text-2xl font-bold text-[#255156]">{{ $total }}</div>
                    <div class="text-xs text-gray-600">Total</div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $valides }}</div>
                    <div class="text-xs text-gray-600">Validés</div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $attente }}</div>
                    <div class="text-xs text-gray-600">En attente</div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $bloques }}</div>
                    <div class="text-xs text-gray-600">Bloqués</div>
                </div>
            </div>

            <!-- Répartition par rôle -->
            <div class="border-t border-gray-200 pt-3">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Répartition par rôle</h4>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <span class="text-xs w-24">Administrateurs</span>&nbsp
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-[#255156]" style="width: {{ $total > 0 ? ($admins/$total)*100 : 0 }}%"></div>
                        </div>
                        <span class="text-xs w-12 text-right">{{ $admins }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-xs w-24">Modérateurs</span>&nbsp
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-[#8bbdc3]" style="width: {{ $total > 0 ? ($moderateurs/$total)*100 : 0 }}%"></div>
                        </div>
                        <span class="text-xs w-12 text-right">{{ $moderateurs }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-xs w-24">Utilisateurs</span>&nbsp
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gray-400" style="width: {{ $total > 0 ? ($utilisateurs/$total)*100 : 0 }}%"></div>
                        </div>
                        <span class="text-xs w-12 text-right">{{ $utilisateurs }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 flex justify-end">
            <button onclick="closeStatsModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm">
                Fermer
            </button>
        </div>
    </div>
</div>

<!-- MODAL MOTIF BLOCAGE -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50" id="reasonModal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="bg-yellow-600 text-white px-4 py-3 flex justify-between items-center">
            <h3 class="font-semibold">
                <i class="fas fa-info-circle mr-2"></i>
                Motif du blocage
            </h3>
            <button onclick="closeReasonModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <p id="reasonText" class="text-gray-700"></p>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 flex justify-end">
            <button onclick="closeReasonModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm">
                Fermer
            </button>
        </div>
    </div>
</div>

<!-- MODAL MODIFICATION  -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50" id="editModal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="bg-[#255156] text-white px-4 py-3 flex justify-between items-center sticky top-0">
            <h3 class="font-semibold">
                <i class="fas fa-edit mr-2"></i>
                Modifier l'utilisateur
            </h3>
            <button onclick="closeEditModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                
                <!-- Identité -->
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Prénom</label>
                        <input type="text" name="prenom" id="editPrenom" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Nom</label>
                        <input type="text" name="name" id="editNom" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                    </div>
                </div>
                
                <!-- Contact -->
                <div class="mb-3">
                    <label class="block text-xs text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" id="editEmail" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                
                <div class="mb-3">
                    <label class="block text-xs text-gray-600 mb-1">Téléphone</label>
                    <input type="text" name="phone" id="editPhone" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                
                <!-- Adresse -->
                <div class="mb-3">
                    <label class="block text-xs text-gray-600 mb-1">Adresse</label>
                    <input type="text" name="adresse" id="editAdresse" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Ville</label>
                        <input type="text" name="ville" id="editVille" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Code postal</label>
                        <input type="text" name="code_postal" id="editCodePostal" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                </div>
                
                <!-- Structure de rattachement -->
                <div class="mb-3">
                    <label class="block text-xs text-gray-600 mb-1">Structure de rattachement</label>
                    <select name="id_structure" id="editStructure" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Aucune structure</option>
                        @foreach($structures as $structure)
                            <option value="{{ $structure->id }}" {{ old('id_structure') == $structure->id ? 'selected' : '' }}>
                                {{ $structure->organisme }} - {{ $structure->ville }} ({{ $structure->code_postal }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Rôle (admin seulement) -->
                @if(auth()->user()->role === 'admin')
                <div class="mb-3">
                    <label class="block text-xs text-gray-600 mb-1">Rôle</label>
                    <select name="role" id="editRole" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="user">Utilisateur</option>
                        <option value="moderateur">Modérateur</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                @endif
                
                <!-- Boutons -->
                <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#255156] hover:bg-[#1d4144] text-white rounded-lg text-sm">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL BLOCAGE -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50" id="blockModal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="bg-red-600 text-white px-4 py-3 flex justify-between items-center">
            <h3 class="font-semibold">
                <i class="fas fa-ban mr-2"></i>
                Bloquer <span id="blockUserName"></span>
            </h3>
            <button onclick="closeBlockModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <form id="blockForm" method="POST" action="">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs text-gray-600 mb-1">Motif du blocage</label>
                    <textarea name="reason" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" rows="3" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeBlockModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">
                        Bloquer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Recherche
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.user-card').forEach(card => {
            card.style.display = card.dataset.search.includes(term) ? '' : 'none';
        });
    });

    // MODAL STATISTIQUES
    function openStatsModal() {
        document.getElementById('statsModal').classList.remove('hidden');
        document.getElementById('statsModal').classList.add('flex');
    }
    
    function closeStatsModal() {
        document.getElementById('statsModal').classList.add('hidden');
        document.getElementById('statsModal').classList.remove('flex');
    }

    // MODAL MOTIF
    function showReason(reason) {
        document.getElementById('reasonText').textContent = reason || 'Aucun motif spécifié';
        document.getElementById('reasonModal').classList.remove('hidden');
        document.getElementById('reasonModal').classList.add('flex');
    }
    
    function closeReasonModal() {
        document.getElementById('reasonModal').classList.add('hidden');
        document.getElementById('reasonModal').classList.remove('flex');
    }

    // MODAL MODIFICATION (avec tous les champs)
    function openEditModal(id, prenom, nom, email, phone, adresse, ville, codePostal, structureId, role) {
        document.getElementById('editPrenom').value = prenom;
        document.getElementById('editNom').value = nom;
        document.getElementById('editEmail').value = email;
        document.getElementById('editPhone').value = phone || '';
        document.getElementById('editAdresse').value = adresse || '';
        document.getElementById('editVille').value = ville || '';
        document.getElementById('editCodePostal').value = codePostal || '';
        document.getElementById('editStructure').value = structureId || '';
        
        if(document.getElementById('editRole')) {
            document.getElementById('editRole').value = role;
        }
        
        document.getElementById('editForm').action = `/admin/users/${id}`;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    // MODAL BLOCAGE
    function openBlockModal(userId, userName) {
        document.getElementById('blockUserName').textContent = userName;
        document.getElementById('blockForm').action = `/admin/users/${userId}/block`;
        document.getElementById('blockModal').classList.remove('hidden');
        document.getElementById('blockModal').classList.add('flex');
    }
    
    function closeBlockModal() {
        document.getElementById('blockModal').classList.add('hidden');
        document.getElementById('blockModal').classList.remove('flex');
    }

    // Fermeture avec Échap
    document.addEventListener('keydown', function(e) {
        if(e.key === 'Escape') {
            closeStatsModal();
            closeReasonModal();
            closeEditModal();
            closeBlockModal();
        }
    });

    // Fermeture en cliquant sur le fond
    document.getElementById('statsModal').addEventListener('click', function(e) {
        if(e.target === this) closeStatsModal();
    });
    
    document.getElementById('reasonModal').addEventListener('click', function(e) {
        if(e.target === this) closeReasonModal();
    });
    
    document.getElementById('editModal').addEventListener('click', function(e) {
        if(e.target === this) closeEditModal();
    });
    
    document.getElementById('blockModal').addEventListener('click', function(e) {
        if(e.target === this) closeBlockModal();
    });
</script>
@endsection