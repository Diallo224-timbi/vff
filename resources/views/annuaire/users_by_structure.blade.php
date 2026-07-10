@extends('base')

@section('title', 'Utilisateurs par structure')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Carte principale -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <!-- En-tête -->
                <div class="card-header text-white py-3" style="background: #145f68; border: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-users me-2"></i>
                            <h4 class="d-inline-block mb-0 fw-bold">Utilisateurs par structure</h4>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-user me-1"></i> Total : {{ $totalUsers ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Messages de session -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Barre de recherche et filtres -->
                    <div class="mb-4">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-6">
                                <div class="input-group" style="border-radius: 10px; overflow: hidden;">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" id="searchUser" class="form-control border-start-0" 
                                           placeholder="Rechercher un utilisateur par nom, email ou structure...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select id="filterRole" class="form-select">
                                    <option value="">Tous les rôles</option>
                                    <option value="user">Utilisateur</option>
                                    <option value="admin">Administrateur</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button onclick="exportUsers()" class="btn w-100" style="background: #255156; color: white; border-radius: 10px;">
                                    <i class="fas fa-file-export me-1"></i> Exporter
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des structures -->
                    @forelse($structures as $structure)
                    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                        <!-- En-tête de structure -->
                        <div class="card-header" style="background: #f8f9fa; border-bottom: 2px solid #145f68;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-building text-primary me-2"></i>
                                    <h5 class="d-inline-block mb-0 fw-bold">{{ $structure->name }}</h5>
                                    <span class="badge bg-primary ms-2">{{ $structure->users->count() }} utilisateurs</span>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary" onclick="toggleStructure('{{ $structure->id }}')">
                                    <i class="fas fa-chevron-down" id="chevron-{{ $structure->id }}"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Corps de structure -->
                        <div class="card-body p-0" id="structure-{{ $structure->id }}">
                            @if($structure->users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead style="background: #f8f9fa;">
                                        <tr>
                                            <th>Utilisateur</th>
                                            <th>Email</th>
                                            <th>Rôle</th>
                                            <th>Date d'inscription</th>
                                            <th>Statut</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($structure->users as $user)
                                        <tr class="user-row" 
                                            data-name="{{ strtolower($user->name) }}"
                                            data-email="{{ strtolower($user->email) }}"
                                            data-role="{{ $user->role }}"
                                            data-structure="{{ strtolower($structure->name) }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-2" style="width: 35px; height: 35px; background: {{ $user->role === 'admin' ? '#dc3545' : '#145f68' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="fw-semibold">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->role === 'admin')
                                                    <span class="badge" style="background: #dc3545; color: white;">
                                                        <i class="fas fa-shield-alt me-1"></i> Admin
                                                    </span>
                                                @elseif($user->role === 'super_admin')
                                                    <span class="badge" style="background: #ffc107; color: #000;">
                                                        <i class="fas fa-crown me-1"></i> Super Admin
                                                    </span>
                                                @else
                                                    <span class="badge" style="background: #145f68; color: white;">
                                                        <i class="fas fa-user me-1"></i> Utilisateur
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</td>
                                            <td>
                                                @if($user->is_active ?? true)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> Actif
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> Inactif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm" style="background: #c7d2fe; color: #3730a3;" title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button onclick="toggleUserStatus({{ $user->id }})" class="btn btn-sm" style="background: #fef3c7; color: #d97706;" title="{{ ($user->is_active ?? true) ? 'Désactiver' : 'Activer' }}">
                                                            <i class="fas {{ ($user->is_active ?? true) ? 'fa-pause' : 'fa-play' }}"></i>
                                                        </button>
                                                        @if(auth()->user()->id !== $user->id)
                                                            <button onclick="deleteUser({{ $user->id }})" class="btn btn-sm" style="background: #fee2e2; color: #dc2626;" title="Supprimer">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Aucun utilisateur dans cette structure</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune structure disponible</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL MODIFICATION -->
<div id="editUserModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header" style="background: #255156; color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editUserId" name="user_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text" id="editName" name="name" required class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" id="editEmail" name="email" required class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rôle</label>
                        <select id="editRole" name="role" class="form-select">
                            <option value="user">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Structure</label>
                        <select id="editStructure" name="structure_id" class="form-select">
                            @foreach($structures as $structure)
                                <option value="{{ $structure->id }}">{{ $structure->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="editActive" name="is_active" value="1">
                        <label class="form-check-label fw-semibold" for="editActive">Compte actif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn" style="background: #255156; color: white;">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .avatar-circle {
        font-size: 14px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .user-row.hidden {
        display: none;
    }
    
    .card-header .badge {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }
    
    .table td {
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.8rem;
        }
        .btn-group .btn {
            padding: 0.25rem 0.4rem;
            font-size: 0.7rem;
        }
    }
</style>

<script>
    // Données des structures pour le filtrage
    const structures = @json($structures);
    
    // Recherche et filtres
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchUser');
        const filterRole = document.getElementById('filterRole');
        
        function filterUsers() {
            const searchTerm = searchInput.value.toLowerCase();
            const roleFilter = filterRole.value;
            
            document.querySelectorAll('.user-row').forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const structure = row.dataset.structure || '';
                const role = row.dataset.role || '';
                
                const matchSearch = name.includes(searchTerm) || 
                                   email.includes(searchTerm) || 
                                   structure.includes(searchTerm);
                const matchRole = !roleFilter || role === roleFilter;
                
                row.style.display = (matchSearch && matchRole) ? '' : 'none';
            });
            
            // Mettre à jour les compteurs
            updateCounters();
        }
        
        function updateCounters() {
            document.querySelectorAll('.card.mb-4').forEach(card => {
                const visibleRows = card.querySelectorAll('.user-row[style*="display: none"]');
                const totalRows = card.querySelectorAll('.user-row').length;
                const visibleCount = totalRows - visibleRows.length;
                
                const badge = card.querySelector('.badge.bg-primary');
                if (badge) {
                    badge.textContent = `${visibleCount} utilisateur${visibleCount > 1 ? 's' : ''}`;
                }
            });
        }
        
        searchInput.addEventListener('input', filterUsers);
        filterRole.addEventListener('change', filterUsers);
    });
    
    // Toggle structure
    window.toggleStructure = function(structureId) {
        const body = document.getElementById(`structure-${structureId}`);
        const chevron = document.getElementById(`chevron-${structureId}`);
        
        if (body.style.display === 'none') {
            body.style.display = '';
            chevron.classList.remove('fa-chevron-right');
            chevron.classList.add('fa-chevron-down');
        } else {
            body.style.display = 'none';
            chevron.classList.remove('fa-chevron-down');
            chevron.classList.add('fa-chevron-right');
        }
    };
    
    // Modifier utilisateur
    window.editUser = function(userId) {
        fetch(`/users/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editUserId').value = data.id;
                document.getElementById('editName').value = data.name;
                document.getElementById('editEmail').value = data.email;
                document.getElementById('editRole').value = data.role;
                document.getElementById('editStructure').value = data.structure_id;
                document.getElementById('editActive').checked = data.is_active ?? true;
                document.getElementById('editUserForm').action = `/users/${userId}`;
                
                const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                modal.show();
            })
            .catch(error => {
                alert('Erreur lors du chargement des données');
                console.error('Erreur:', error);
            });
    };
    
    // Toggle statut utilisateur
    window.toggleUserStatus = function(userId) {
        if (!confirm('Voulez-vous changer le statut de cet utilisateur ?')) return;
        
        fetch(`/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors du changement de statut');
            }
        })
        .catch(error => {
            alert('Erreur de connexion');
            console.error('Erreur:', error);
        });
    };
    
    // Supprimer utilisateur
    window.deleteUser = function(userId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) return;
        
        fetch(`/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la suppression');
            }
        })
        .catch(error => {
            alert('Erreur de connexion');
            console.error('Erreur:', error);
        });
    };
    
    // Exporter les utilisateurs
    window.exportUsers = function() {
        window.location.href = '{{ route("users.export") }}';
    };
</script>
@endsection