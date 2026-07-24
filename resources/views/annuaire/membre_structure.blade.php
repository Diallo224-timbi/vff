@extends('base')

@section('title', 'Annuaire - Membre Structure')

@section('content')
<div class="container py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-dark">
                <i class="fas fa-users text-[#156255] me-2"></i>
                Membres de la structure
            </h1>
            <p class="text-muted mb-0">
                Liste des {{ $membres->count() }} membres appartenant {{ $structure->organisme->nom_organisme }} {{ $structure->code_postal }}.
            </p>
        </div>
        <a href="{{ route('annuaire.index') }}" class="btn" style="background: #145f68; color: white; border-radius: 10px;">
            <i class="fas fa-arrow-left me-2"></i>
            Retour à l'annuaire
        </a>
    </div>

    <!-- Carte principale -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header text-white py-3" style="background: #145f68; border: none;">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <i class="fas fa-address-card me-2"></i>
                    <h5 class="d-inline-block mb-0 fw-bold">Liste des membres</h5>
                </div>
                <div>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-users me-1"></i> {{ $membres->count() }} membres
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Barre de recherche et filtres -->
            <div class="p-3 border-bottom" style="background: #f8f9fa;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group" style="border-radius: 10px; overflow: hidden;">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="searchMember" class="form-control border-start-0"
                                   placeholder="Rechercher par nom, prénom, email...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau responsive -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="membersTable" style="border-radius: 10px; overflow: hidden;">
                    <thead style="background: #f1f5f9; border-bottom: 2px solid #145f68;">
                        <tr>
                            <th style="width: 50px;" class="text-center">#</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th style="width: 120px;" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($membres as $index => $membre)
                            <tr class="member-row">
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-[#156255] text-white d-flex align-items-center justify-content-center" 
                                             style="width: 36px; height: 36px; font-size: 14px; font-weight: bold; flex-shrink: 0;">
                                            {{ strtoupper(substr($membre->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-semibold">{{ $membre->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $membre->prenom }}</td>
                                <td>
                                    <a href="mailto:{{ $membre->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope text-[#156255] me-1"></i>
                                        {{ $membre->email }}
                                    </a>
                                </td>
                                <td>
                                    @if($membre->phone)
                                        <a href="tel:{{ $membre->phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone text-success me-1"></i>
                                            {{ $membre->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">Non renseigné</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button onclick="viewMember({{ $membre->id }})" class="btn btn-outline-primary" title="Voir le profil">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="sendEmail({{ $membre->id }})" class="btn btn-outline-info" title="Envoyer un email">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-users-slash fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">Aucun membre trouvé dans cette structure.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pied de tableau -->
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap" style="background: #f8f9fa;">
                <span class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    {{ $membres->count() }} membre(s) affiché(s)
                </span>
                <div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour le profil du membre -->
<div id="memberModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header" style="background: #145f68; color: white; border: none;">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i> Profil du membre
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="memberDetails">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 80px; height: 80px; font-size: 30px; font-weight: bold;">
                        <span id="memberInitials">JD</span>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Nom</label>
                        <p class="fw-semibold mb-0" id="memberNom">-</p>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small fw-semibold">Prénom</label>
                        <p class="fw-semibold mb-0" id="memberPrenom">-</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Email</label>
                        <p class="mb-0" id="memberEmail">-</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small fw-semibold">Téléphone</label>
                        <p class="mb-0" id="memberTelephone">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button onclick="sendEmailFromModal()" class="btn" style="background: #145f68; color: white;">
                    <i class="fas fa-envelope me-1"></i> Envoyer un email
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    .table {
        font-size: 0.9rem;
    }
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 12px 15px;
        color: #1a1a2e;
    }
    .table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
    }
    .table tbody tr {
        transition: background 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-group .btn {
        border-radius: 8px !important;
        margin: 0 2px;
    }
    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }

    .pagination {
        margin-bottom: 0;
    }
    .pagination .page-item.active .page-link {
        background-color: #145f68;
        border-color: #145f68;
    }
    .pagination .page-link {
        color: #145f68;
        border-radius: 8px !important;
        margin: 0 2px;
    }
    .pagination .page-link:hover {
        color: #0d454b;
    }

    /* Animation pour les lignes */
    .member-row {
        animation: fadeInUp 0.3s ease;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Style des badges dans le tableau */
    .badge-role {
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table thead {
            display: none;
        }
        .table tbody td {
            display: block;
            padding: 8px 12px;
            border-bottom: none;
        }
        .table tbody tr {
            border-bottom: 2px solid #e9ecef;
            display: block;
        }
        .table tbody td:first-child {
            padding-top: 15px;
        }
        .table tbody td:last-child {
            padding-bottom: 15px;
            border-bottom: none;
        }
        .table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            display: inline-block;
            width: 120px;
            color: #6c757d;
        }
        .table tbody td .d-flex {
            display: inline-flex !important;
        }
    }
</style>

<script>
    // Recherche dans le tableau
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchMember');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.member-row');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });

    // Voir le profil du membre
    let currentMember = null;

    function viewMember(id) {
        // Simulation - remplacer par un appel AJAX réel
        const membres = @json($membres);
        const member = membres.find(m => m.id === id);
        
        if (member) {
            currentMember = member;
            document.getElementById('memberInitials').textContent = 
                (member.prenom?.[0] || '') + (member.name?.[0] || '');
            document.getElementById('memberNom').textContent = member.name || '-';
            document.getElementById('memberPrenom').textContent = member.prenom || '-';
            document.getElementById('memberEmail').textContent = member.email || '-';
            document.getElementById('memberTelephone').textContent = member.phone || 'Non renseigné';
            
            const modal = new bootstrap.Modal(document.getElementById('memberModal'));
            modal.show();
        }
    }

    function sendEmail(id) {
        const membres = @json($membres);
        const member = membres.find(m => m.id === id);
        if (member && member.email) {
            window.location.href = `mailto:${member.email}`;
        } else {
            alert('Aucune adresse email disponible pour ce membre.');
        }
    }

    function sendEmailFromModal() {
        if (currentMember && currentMember.email) {
            window.location.href = `mailto:${currentMember.email}`;
        } else {
            alert('Aucune adresse email disponible pour ce membre.');
        }
    }

    // Exporter en CSV
    function exportTable() {
        const rows = document.querySelectorAll('.member-row');
        let csv = 'Nom,Prénom,Email,Téléphone\n';
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 0) {
                // Ignorer la première cellule (numéro) et la dernière (actions)
                const nom = cells[1]?.textContent.trim() || '';
                const prenom = cells[2]?.textContent.trim() || '';
                const email = cells[3]?.textContent.trim() || '';
                const telephone = cells[4]?.textContent.trim() || '';
                csv += `"${nom}","${prenom}","${email}","${telephone}"\n`;
            }
        });
        
        // Télécharger le fichier
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'membres_structure.csv';
        link.click();
    }
</script>
@endsection