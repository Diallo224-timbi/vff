@extends('base')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des utilisateurs</h1>
            <p class="text-gray-500 mt-1 text-sm">Administration & validation des comptes</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600">{{ auth()->user()->role }}</span>
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <!-- SEARCH BAR -->
    <div class="mb-6">
        <div class="relative">
            <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
                id="searchInput"
                type="text"
                placeholder="Rechercher par nom, email, structure ou état…"
                class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700 text-sm"
            >
        </div>
    </div>
    <!-- message de succès -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                document.querySelector('.bg-green-100').remove();
            }, 5000);
        </script>
    @endif
    <!-- USERS LIST -->
    <div id="usersContainer" class="space-y-4">
        @foreach($users as $user)
        <div
            class="relative group user-card bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition-transform transform hover:scale-102 cursor-pointer"
            data-user-id="{{ $user->id }}"
            data-name="{{ strtolower($user->name) }}"
            data-prenom="{{ $user->prenom }}"
            data-email="{{ strtolower($user->email) }}"
            data-phone="{{ $user->phone ?? '' }}"
            data-structure="{{ strtolower($user->structure->nom_structure ?? '') }}"
            data-etatv="{{ strtolower($user->etatV) }}"
        >
            <!-- Carte cliquable vers profil -->
            <a href="{{ route('profile.show', $user->id) }}" class="absolute inset-0 z-10"></a>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 min-h-[140px] relative z-25 transition-all duration-300 ease-in-out">

                <!-- COL 1 : IDENTITÉ -->
                <div class="flex flex-col justify-between h-full card-content border border-gray-200 p-2 rounded">
                    <p class="card-text flex items-center gap-2 text-gray-900 font-semibold text-sm">
                        <i class="fa fa-user text-blue-600 text-lg"></i>
                        <strong>{{ $user->prenom }} {{ $user->name }}</strong>
                    </p>
                    <p class="w-16 h-16 rounded-full bg-blue-400 flex items-center justify-center text-white font-bold text-xl mx-auto mt-2">
                        {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->name, 0, 1)) }}
                    </p>
                    <strong class="text-xs"><i class="fa fa-user-tag text-blue-600">{{ $user->role }}</i> </strong>
                    <p class="card-text flex items-center gap-2 text-gray-700 text-sm mt-2">
                        <i class="fa fa-envelope text-blue-600 text-lg"></i>
                        <strong>Email:</strong>
                        <a href="mailto:{{ $user->email }}" class="hover:underline text-blue-600">{{ $user->email }}</a>
                    </p>
                </div>

                <!-- COL 2 : INFOS UTILISATEUR -->
                <div class="flex flex-col justify-between h-full text-xs text-gray-700 card-content space-y-1 border border-gray-200 p-2 rounded">
                    <p class="card-text flex items-center gap-3"><i class="fa fa-phone text-blue-600"></i> <strong>Téléphone:</strong> {{ $user->phone ?? '—' }}</p>
                    <p class="card-text flex items-center gap-3"><i class="fa fa-map-marker-alt text-blue-600"></i> <strong>Adresse:</strong></p>
                    <p class="card-text ml-6">
                        <a target="_blank"
                           href="https://www.google.com/maps/search/{{ urlencode($user->adresse . ', ' . $user->code_postal . ' ' . $user->ville) }}"
                           class="hover:underline text-blue-600">
                            {{ $user->adresse ?? '—' }}
                        </a>
                    </p>
                    <p class="card-text flex items-center gap-3"><i class="fa fa-city text-blue-600"></i> <strong>Ville:</strong> {{ $user->ville ?? '—' }}</p>
                    <p class="card-text flex items-center gap-3"><i class="fa fa-mail-bulk text-blue-600"></i> <strong>Code Postal:</strong> {{ $user->code_postal ?? '—' }}</p>
                    <p class="card-text flex items-center gap-3"><i class="fa fa-clock text-blue-600"></i> <strong>Créé le:</strong> {{ $user->created_at?->format('d/m/Y H:i') }}</p>
                </div>

                <!-- COL 3 : STRUCTURE -->
                <div class="flex flex-col justify-between h-full text-xs text-gray-700 card-content space-y-1 border border-gray-200 p-2 rounded md:col-span-1">
                    <p class="card-text flex items-center gap-3"><i class="fa fa-building text-blue-600"></i> <strong>Structure:</strong> {{ $user->structure->nom_structure ?? '—' }}</p>
                    <p class="card-text flex items-center gap-3"><i class="fa fa-user-tie text-blue-600"></i> <strong>Responsable:</strong> {{ $user->structure->responsable ?? '—' }}</p>
                    <p class="card-text flex items-center gap-3"><i class="fa fa-envelope text-blue-600"></i> <strong>Email:</strong>
                        <a href="mailto:{{ $user->structure->email ?? '' }}" class="hover:underline text-blue-600">{{ $user->structure->email ?? '—' }}</a>
                    </p>
                    <p class="card-text flex items-center gap-3"><i class="fa fa-map-marked-alt text-blue-600"></i> <strong>Adresse:</strong></p>
                    <p class="card-text ml-6">
                        <a target="_blank"
                           href="https://www.google.com/maps/search/{{ urlencode(
                                                                                    ($user->structure?->adresse ?? '') . ', ' .
                                                                                    ($user->structure?->code_postal ?? '') . ' ' .
                                                                                    ($user->structure?->ville ?? '')
                                                                    ) }}"
                           class="hover:underline text-blue-600">
                            {{ $user->structure->adresse ?? '—' }}, {{ $user->structure->code_postal ?? '' }} {{ $user->structure->ville ?? '' }}
                        </a>
                    </p>
                </div>

                <!-- COL 4 : ACTIONS -->
                <div class="flex flex-col justify-between h-full items-end text-xs z-30 w-65 card-content border border-gray-200 p-2 rounded space-y-1 md:col-span-1">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full font-medium
                        {{ $user->etatV === 'valider' ? 'bg-green-100 text-green-700' :
                           ($user->etatV === 'bloqué' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                        <i class="fa fa-circle text-[0.5rem]"></i> {{ ucfirst($user->etatV) }}
                    </span>

                    @if($user->etatV !== 'valider')
                    <form method="POST" action="{{ route('admin.users.validate', $user->id) }}">
                        @csrf
                        <button class="px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs flex items-center gap-1">
                            <i class="fa fa-check text-[0.6rem]"></i> Valider
                        </button>
                    </form>
                    @endif

                    @if($user->etatV === 'bloqué')
                    <button type="button" data-bs-toggle="modal" data-bs-target="#blockReasonModal"
                            data-reason="{{ $user->block_reason }}"
                            class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs flex items-center gap-1 block-reason-btn">
                        <i class="fa fa-info-circle text-[0.6rem]"></i> Motif du blocage
                    </button>
                    @endif

                    <button type="button" data-bs-toggle="modal" data-bs-target="#editUserModal"
                            data-user-id="{{ $user->id }}"
                            data-prenom="{{ $user->prenom }}"
                            data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}"
                            data-phone="{{ $user->phone }}"
                            data-structure-id="{{ $user->structure->id ?? '' }}"
                            data-structure-name="{{ $user->structure->nom_structure ?? '' }}"
                            data-adresse="{{ $user->adresse }}"
                            data-ville="{{ $user->ville }}"
                            data-code-postal="{{ $user->code_postal }}"
                            data-role="{{ $user->role }}"
                            class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs flex items-center gap-1 edit-user-btn">
                        <i class="fa fa-edit text-[0.6rem]"></i> Modifier
                    </button>

                    @if($user->etatV !== 'bloqué')
                    <button type="button" data-bs-toggle="modal" data-bs-target="#blockModal"
                            data-user-id="{{ $user->id }}"
                            data-user-name="{{ $user->name }}"
                            class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs flex items-center gap-1 block-user-btn">
                        <i class="fa fa-ban text-[0.6rem]"></i> Bloquer
                    </button>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- MODAL MODIFICATION UTILISATEUR -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h3 class="modal-title " id="editUserModalLabel">
                    Modifier l'utilisateur
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <form id="editUserForm" method="POST" action="{{ route('admin.users.update', 0) }}">
                    @csrf
                    @method('PUT')

                    <!-- Prénom / Nom -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modalPrenom" class="form-label">Prénom</label>
                            <input type="text" name="prenom" id="modalPrenom" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="modalName" class="form-label">Nom</label>
                            <input type="text" name="name" id="modalName" class="form-control" required>
                        </div>
                    </div>

                    <!-- Email / Téléphone -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modalEmail" class="form-label">Email</label>
                            <input type="email" name="email" id="modalEmail" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="modalPhone" class="form-label">Téléphone</label>
                            <input type="text" name="phone" id="modalPhone" class="form-control">
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="mb-3">
                        <label for="modalAdresse" class="form-label">Adresse</label>
                        <input type="text" name="adresse" id="modalAdresse" class="form-control">
                    </div>

                    <!-- Ville / Code postal -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modalVille" class="form-label">Ville</label>
                            <input type="text" name="ville" id="modalVille" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="modalCodePostal" class="form-label">Code postal</label>
                            <input type="text" name="code_postal" id="modalCodePostal" class="form-control">
                        </div>
                    </div>

                    <!-- Structure -->
                    <div class="mb-3">
                        <label for="modalStructure" class="form-label">Structure</label>
                        <select name="id_structure" id="modalStructure" class="form-select">
                            <option value="{{ $user->id_structure }}" id="modalStructureOption"></option>
                            @foreach($structures as $structure)
                                <option value="{{ $structure->id }}">
                                    {{ $structure->nom_structure }}
                                </option>
                            @endforeach
                        </select>
                    @if(@auth()->user()->role === 'admin')
                        <label for="modalRole" class="form-label">Rôle</label>
                        <select name="role" id="modalRole" class="form-select">
                            <option value="user">user</option>
                            <option value="moderateur">moderateur</option>
                            <option value="admin">admin</option>
                        </select>
                    @endif
                    </div>
                    
                </form>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Annuler
                </button>
                <button type="submit" form="editUserForm" class="btn btn-primary">
                    Enregistrer
                </button>
            </div>

        </div>
    </div>
</div>


<!-- MODAL BLOCAGE MOTIF -->
<div class="modal fade" id="blockReasonModal" tabindex="-1" aria-labelledby="blockReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blockReasonModalLabel">Motif du blocage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="blockReasonText" class="text-gray-700 whitespace-pre-line"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL BLOCAGE ADMIN -->
<div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blockModalLabel">
                    ⛔ Bloquer <span id="modalUserName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="blockForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="blockReason" class="form-label">Motif du blocage (visible par l'utilisateur)</label>
                        <textarea name="reason" id="blockReason" class="form-control" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="blockForm" class="btn btn-danger">Confirmer le blocage</button>
            </div>
        </div>
    </div>
</div>

<!-- JS SEARCH + MODAL HANDLERS -->
<script>
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', () => {
        const term = searchInput.value.toLowerCase();
        document.querySelectorAll('.user-card').forEach(card => {
            const name = card.dataset.name;
            const email = card.dataset.email;
            const structure = card.dataset.structure;
            const etatV = card.dataset.etatv;
            card.style.display = (name.includes(term) || email.includes(term) || structure.includes(term) || etatV.includes(term)) ? '' : 'none';
        });
    });

    // Gestionnaire pour le bouton "Motif du blocage"
    document.querySelectorAll('.block-reason-btn').forEach(button => {
        button.addEventListener('click', function() {
            const reason = this.getAttribute('data-reason');
            document.getElementById('blockReasonText').textContent = reason;
        });
    });

    // Gestionnaire pour le bouton "Modifier"
    document.querySelectorAll('.edit-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const prenom = this.getAttribute('data-prenom');
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone') || '';
            const structureId = this.getAttribute('data-structure-id') || '';
            const structureName = this.getAttribute('data-structure-name') || '';
            const adresse = this.getAttribute('data-adresse') || '';
            const ville = this.getAttribute('data-ville') || '';
            const codePostal = this.getAttribute('data-code-postal') || '';
            const role = this.getAttribute('data-role') || '';
            
            // Remplir le formulaire modal
            document.getElementById('modalPrenom').value = prenom;
            document.getElementById('modalName').value = name;
            document.getElementById('modalEmail').value = email;
            document.getElementById('modalPhone').value = phone;
            document.getElementById('modalStructureOption').value = structureId;
            document.getElementById('modalStructureOption').textContent = structureName || 'Aucune';
            document.getElementById('modalAdresse').value = adresse;
            document.getElementById('modalVille').value = ville;
            document.getElementById('modalCodePostal').value = codePostal;
            document.getElementById('modalRole').value = role;

            
            // Mettre à jour l'action du formulaire
            document.getElementById('editUserForm').action = `/admin/users/${userId}`;
        });
    });

    // Gestionnaire pour le bouton "Bloquer"
    document.querySelectorAll('.block-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            
            // Mettre à jour le nom dans le modal
            document.getElementById('modalUserName').textContent = userName;
            
            // Mettre à jour l'action du formulaire
            document.getElementById('blockForm').action = `/admin/users/${userId}/block`;
        });
    });

    // Réinitialiser les formulaires quand les modales sont fermées
    document.getElementById('editUserModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('editUserForm').reset();
    });
    
    document.getElementById('blockModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('blockForm').reset();
        document.getElementById('modalUserName').textContent = '';
    });
    
    document.getElementById('blockReasonModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('blockReasonText').textContent = '';
    });

    // Fonction pour focus sur une carte
    document.querySelectorAll('.user-card').forEach(card => {
        card.addEventListener('click', e => {
            if (e.target.closest('button, form, a[href]')) return;
            card.classList.toggle('card-focused');
        });
    });
</script>

<style>
    .card-focused {
        transform: scale(1.03);
        z-index: 50 !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        position: relative;
        overflow: hidden;
    }
    .card-focused .card-text {
        font-size: 0.95rem;
    }
    .card-focused .card-label {
        font-size: 0.75rem;
    }
    .card-focused::after {
        content: '';
        position: absolute;
        inset: 0;
        background-color: rgba(59, 130, 246, 0.1);
        pointer-events: none;
        border-radius: 0.5rem;
    }
    
    /* Style pour les modales Bootstrap */
    .modal-header {
        border-bottom: 1px solid #dee2e6;
        padding: 1rem;
    }
    
    .modal-footer {
        border-top: 1px solid #dee2e6;
        padding: 1rem;
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
        color: #374151;
    }
    
    .form-control {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@endsection