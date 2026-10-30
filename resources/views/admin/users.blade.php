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
            @if(auth()->user()->role === 'admin')
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">Administrateur</span>
            @elseif(auth()->user()->role === 'moderateur')
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">Responsable organisme</span>
            @elseif(auth()->user()->role === 'moderateur_classique')
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">Responsable structure</span>
            @else
                <span class="text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">Membre</span>
            @endif
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
            data-search="{{ strtolower($user->prenom.' '.$user->name.' '.$user->email.' '.($user->structure->organisme->nom_organisme ?? '')) }}">
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
                    @if($user->role === 'admin')
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-[#255156] text-white">Administrateur</span>
                    @elseif($user->role === 'moderateur')
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-[#255156] text-white">Responsable organisme</span>
                    @elseif($user->role === 'moderateur_classique')
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-[#255156] text-white">Responsable structure</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-[#255156] text-white">Utilisateur</span>
                    @endif
                </div>
            </div>
            <!-- Structure de rattachement -->
            <div class="mt-1 text-xs text-gray-600">
                <i class="fas fa-building text-[#255156] mr-1"></i>
                @if($user->role === 'moderateur')
                    {{ $user->structure->organisme->nom_organisme ?? 'Aucun organisme' }}
                @else
                {{ $user->structure->organisme->nom_organisme ?? 'Aucune structure' }}
                @endif
                @if($user->structure)
                    <span class="text-gray-400">{{ $user->structure->ville ?? '' }} ({{ $user->structure->code_postal }}) {{ $user->structure->adresse }}</span>
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
                <button type="button"
                        class="show-reason-button bg-yellow-500 hover:bg-yellow-600 text-white w-7 h-7 rounded flex items-center justify-center"
                        data-block-reason="{{ $user->block_reason ?? '' }}" title="Voir motif">
                    <i class="bx bx-info-circle text-xs"></i>
                </button>
                @endif
                <button type="button"
                        class="edit-user-button bg-blue-500 hover:bg-blue-600 text-white w-7 h-7 rounded flex items-center justify-center"
                        data-user-id="{{ $user->id }}"
                        data-prenom="{{ $user->prenom }}"
                        data-nom="{{ $user->name }}"
                        data-email="{{ $user->email }}"
                        data-phone="{{ $user->phone }}"
                        data-adresse="{{ $user->adresse }}"
                        data-ville="{{ $user->ville }}"
                        data-code-postal="{{ $user->code_postal }}"
                        data-structure-id="{{ $user->id_structure }}"
                        data-organisme-id="{{ $user->structure->id_organisme ?? '' }}"
                        data-role="{{ $user->role }}"
                        title="Modifier">
                            <i class="bx bx-edit text-xs"></i>
                </button>
                @if($user->etatV !== 'bloqué')
                <button type="button"
                        class="open-block-modal-button bg-red-500 hover:bg-red-600 text-white w-7 h-7 rounded flex items-center justify-center"
                        data-user-id="{{ $user->id }}"
                        data-user-name="{{ $user->prenom }} {{ $user->name }}"
                        title="Bloquer">
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

<!-- MODAL MOTIF BLOCAGE -->
<div class="admin-user-modal fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center" id="reasonModal" style="z-index: 2000;">
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
<div class="admin-user-modal fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center" id="editModal" style="z-index: 2000;">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl mx-4">
        <div class="bg-[#255156] text-white px-4 py-3 flex justify-between items-center rounded-t-lg">
            <h3 class="font-semibold">
                <i class="fas fa-edit mr-2"></i>
                Modifier l'utilisateur
            </h3>
            <button onclick="closeEditModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4 md:p-5">
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')    
                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                    <div>
                        <label for="editPrenom" class="block text-xs text-gray-600 mb-1">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="prenom" id="editPrenom" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required readonly>
                    </div>
                    <div>
                        <label for="editNom" class="block text-xs text-gray-600 mb-1">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editNom" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required readonly>
                    </div>
                    <div>
                        <label for="editEmail" class="block text-xs text-gray-600 mb-1">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="editEmail" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required readonly>
                    </div>
                    <div>
                        <label for="editPhone" class="block text-xs text-gray-600 mb-1">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="editPhone" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required readonly>
                    </div>
                    <div class="col-span-2">
                        <label for="editAdresse" class="block text-xs text-gray-600 mb-1">Adresse <span class="text-danger">*</span></label>
                        <input type="text" name="adresse" id="editAdresse" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required readonly>
                    </div>
                    <div>
                        <label for="editVille" class="block text-xs text-gray-600 mb-1">Ville <span class="text-danger">*</span></label>
                        <input type="text" name="ville" id="editVille" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required readonly>
                    </div>
                    <div>
                        <label for="editCodePostal" class="block text-xs text-gray-600 mb-1">Code postal <span class="text-danger">*</span></label>
                        <input type="text" name="code_postal" id="editCodePostal" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required readonly>
                    </div>
                    <div>
                        <label for="editOrganisme" class="block text-xs text-gray-600 mb-1">Organisme <span class="text-danger">*</span></label>
                        <select required name="id_organisme" id="editOrganisme" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm">
                            <option value="">Aucun organisme</option>
                            @foreach($organismes as $organisme)
                                <option value="{{ $organisme->id }}">
                                    {{ $organisme->nom_organisme }} - {{ $organisme->ville }} ({{ $organisme->code_postal }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="editStructure" class="block text-xs text-gray-600 mb-1">Structure de rattachement <span class="text-danger">*</span></label>
                        <select required name="id_structure" id="editStructure" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm">
                            <option value="">Aucune structure</option>
                            @foreach($structures as $structure)
                                <option value="{{ $structure->id }}"
                                    data-organisme-id="{{ $structure->id_organisme }}">
                                    {{ $structure->organisme->nom_organisme ?? '-' }} - {{ $structure->ville }} ({{ $structure->code_postal }}) - {{ $structure->adresse }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderateur')
                    <div>
                        <label for="editRole" class="block text-xs text-gray-600 mb-1">Rôle <span class="text-danger">*</span></label>
                        <select required name="role" id="editRole" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm">
                            @if(auth()->user()->role === 'admin')
                                <option value="admin">Administrateur</option>
                                <option value="moderateur">Responsable organisme</option>
                            @endif
                            <option value="moderateur_classique">Responsable structure</option>
                            <option value="user">Utilisateur</option>
                        </select>
                    </div>
                    @endif
                <!-- Boutons -->
                <div class="col-span-2 flex justify-end gap-2 mt-1 pt-3 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#255156] hover:bg-[#1d4144] text-white rounded-lg text-sm">
                        Enregistrer
                    </button>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- MODAL BLOCAGE -->
<div class="admin-user-modal fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center" id="blockModal" style="z-index: 2000;">
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
        <div class="p-6">
            <form id="blockForm" method="POST" action="">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs text-gray-600 mb-1">Motif du blocage <span class="text-danger">*</span></label>
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
    document.querySelectorAll('.admin-user-modal').forEach(modal => {
        document.body.appendChild(modal);
    });

    // Stocker toutes les structures avec leurs données pour le filtrage dynamique
    const allStructures = @json($structures->map(function($structure) {
        return [
            'id' => $structure->id,
            'id_organisme' => $structure->id_organisme,
            'label' => ($structure->organisme->nom_organisme ?? '-') . ' - ' . $structure->ville . ' (' . $structure->code_postal . ') - ' . $structure->adresse
        ];
    }));

    // Recherche
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.user-card').forEach(card => {
            card.style.display = card.dataset.search.includes(term) ? '' : 'none';
        });
    });

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

    // Fonction pour filtrer les structures par organisme
    function filterStructuresByOrganisme(organismeId, selectedStructureId = null) {
        const structureSelect = document.getElementById('editStructure');
        
        // Vider la liste déroulante des structures
        structureSelect.innerHTML = '<option value="">Aucune structure</option>';
        
        if (!organismeId) {
            // Si aucun organisme sélectionné, afficher toutes les structures
            allStructures.forEach(structure => {
                const option = document.createElement('option');
                option.value = structure.id;
                option.textContent = structure.label;
                option.setAttribute('data-organisme-id', structure.id_organisme);
                structureSelect.appendChild(option);
            });
        } else {
            // Filtrer les structures par organisme
            const filteredStructures = allStructures.filter(s => s.id_organisme == organismeId);
            filteredStructures.forEach(structure => {
                const option = document.createElement('option');
                option.value = structure.id;
                option.textContent = structure.label;
                option.setAttribute('data-organisme-id', structure.id_organisme);
                structureSelect.appendChild(option);
            });
        } 
        // Restaurer la sélection si une structure est spécifiée
        if (selectedStructureId) {
            structureSelect.value = selectedStructureId;
        }
    }
    // MODAL MODIFICATION (avec tous les champs et gestion dynamique des structures)
    function openEditModal(userData) {
        document.getElementById('editPrenom').value = userData.prenom;
        document.getElementById('editNom').value = userData.nom;
        document.getElementById('editEmail').value = userData.email;
        document.getElementById('editPhone').value = userData.phone;
        document.getElementById('editAdresse').value = userData.adresse;
        document.getElementById('editVille').value = userData.ville;
        document.getElementById('editCodePostal').value = userData.codePostal;
        
        // Sélectionner l'organisme
        const organismeSelect = document.getElementById('editOrganisme');
        organismeSelect.value = userData.organismeId;
        
        // Filtrer les structures en fonction de l'organisme sélectionné
        filterStructuresByOrganisme(userData.organismeId, userData.structureId);
        
        if(document.getElementById('editRole')) {
            document.getElementById('editRole').value = userData.role;
        }
        
        document.getElementById('editForm').action = `/admin/users/${userData.id}`;
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

    document.querySelectorAll('.edit-user-button').forEach(button => {
        button.addEventListener('click', () => {
            openEditModal({
                id: button.dataset.userId,
                prenom: button.dataset.prenom,
                nom: button.dataset.nom,
                email: button.dataset.email,
                phone: button.dataset.phone,
                adresse: button.dataset.adresse,
                ville: button.dataset.ville,
                codePostal: button.dataset.codePostal,
                structureId: button.dataset.structureId,
                organismeId: button.dataset.organismeId,
                role: button.dataset.role
            });
        });
    });

    document.querySelectorAll('.show-reason-button').forEach(button => {
        button.addEventListener('click', () => showReason(button.dataset.blockReason));
    });

    document.querySelectorAll('.open-block-modal-button').forEach(button => {
        button.addEventListener('click', () => {
            openBlockModal(button.dataset.userId, button.dataset.userName);
        });
    });

    // Filtrage dynamique des structures rattachées en fonction de l'organisme
    document.getElementById('editOrganisme').addEventListener('change', function() {
        const organismeId = this.value;
        filterStructuresByOrganisme(organismeId);
    });

    // Fermeture avec Échap
    document.addEventListener('keydown', function(e) {
        if(e.key === 'Escape') {
            closeReasonModal();
            closeEditModal();
            closeBlockModal();
        }
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