@extends('base')

@section('title', 'Gestion des structures')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- En-tête avec logo -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2.5 rounded-xl" style="background: linear-gradient(135deg, #255156, #3a7378); box-shadow: 0 4px 12px rgba(37,81,86,0.3);">
                <i class='bx bx-building' style="font-size: 1.8rem; color: white;"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold" style="color: #255156;">Gestion des structures</h1>
                <p class="text-sm" style="color: #4a7a7f;">Gestion et administration des structures</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm" style="background: white; border: 1px solid #dceeec; color: #255156;">
                <i class='bx bx-building' style="margin-right: 0.5rem;"></i>
                Total : {{ $structures->total() }}
            </span>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="rounded-lg px-4 py-3 mb-4 flex items-center gap-3" 
             style="background: #e8f5e9; border-left: 4px solid #4caf50; color: #2e7d32;"
             x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)">
            <i class='bx bx-check-circle' style="font-size: 1.3rem;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @elseif(session('errors'))
        <div class="rounded-lg px-4 py-3 mb-4 flex items-center gap-3"
             style="background: #ffebee; border-left: 4px solid #ef5350; color: #c62828;"
             x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)">
            <i class='bx bx-error-circle' style="font-size: 1.3rem;"></i>
            <span>{{ session('errors') }}</span>
        </div>
    @endif

    <!-- Barre d'actions -->
    <div class="flex flex-col sm:flex-row items-center gap-3 mb-4">
        <button class="btn px-5 py-2.5 rounded-lg text-white font-medium transition-all flex items-center gap-2" 
                style="background: linear-gradient(135deg, #255156, #3a7378); box-shadow: 0 4px 12px rgba(37,81,86,0.25);"
                data-bs-toggle="modal" data-bs-target="#addModal"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(37,81,86,0.35)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(37,81,86,0.25)';">
            <i class='bx bx-plus-circle' style="font-size: 1.2rem;"></i>
            Ajouter une structure
        </button>
        <div class="flex-1 relative">
            <i class='bx bx-search' style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #7fa8ac; font-size: 1.1rem;"></i>
            <form method="GET" action="{{ route('structures.index') }}" class="w-full">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher une structure..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm focus:outline-none focus:ring-2 transition-all"
                       style="border: 1px solid #dceeec; background: #f8fcfc; color: #255156;"
                       onfocus="this.style.borderColor='#2d6268'; this.style.boxShadow='0 0 0 4px rgba(45,98,104,0.08)';"
                       onblur="this.style.borderColor='#dceeec'; this.style.boxShadow='none';">
            </form>
        </div>
    </div>

    <!-- Table avec en-tête fixe et logos adaptés -->
    <div class="overflow-auto border rounded-xl" style="max-height:550px; background: white; border: 1px solid #dceeec; box-shadow: 0 2px 12px rgba(0,0,0,0.04);">
        <table class="table table-bordered table-striped w-full" style="font-size: 0.9rem;">
            <thead class="sticky top-0 z-10" style="background: #255156; color: white;">
                <tr>
                    <th class="px-3 py-3 text-left font-semibold text-sm">Logo</th>
                    <th class="px-3 py-3 text-left font-semibold text-sm">Nom / Organisme</th>
                    <th class="px-3 py-3 text-left font-semibold text-sm">Adresse siège</th>
                    <th class="px-3 py-3 text-left font-semibold text-sm">Ville siège</th>
                    <th class="px-3 py-3 text-left font-semibold text-sm">Ville</th>
                    <th class="px-3 py-3 text-left font-semibold text-sm">Code Postal</th>
                    <th class="px-3 py-3 text-left font-semibold text-sm">Contact</th>
                    <th class="px-3 py-3 text-left font-semibold text-sm">Email</th>
                    <th class="px-3 py-3 text-left font-semibold text-sm">Type / Catégorie</th>
                    <th class="px-3 py-3 text-left font-semibold text-sm">Site internet</th>
                    @if(auth()->user()->role === 'admin')
                    <th class="px-3 py-3 text-left font-semibold text-sm">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($structures as $structure)
                    <tr class="hover:bg-gray-50 transition-colors" style="border-bottom: 1px solid #f0f6f5;">
                        <td class="px-3 py-3 align-middle">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-white flex-shrink-0"
                                 style="background: linear-gradient(135deg, #255156, #4a8599); box-shadow: 0 2px 8px rgba(37,81,86,0.2); font-size: 1rem;">
                                @php
                                    $nom = $structure->organisme ?? $structure->nom ?? 'S';
                                    $initiales = '';
                                    $mots = explode(' ', $nom);
                                    foreach($mots as $mot) {
                                        if(!empty($mot)) {
                                            $initiales .= strtoupper(substr($mot, 0, 1));
                                        }
                                        if(strlen($initiales) >= 2) break;
                                    }
                                    if(empty($initiales)) $initiales = 'S';
                                @endphp
                                {{ $initiales }}
                            </div>
                        </td>
                        <td class="px-3 py-3 align-middle font-medium" style="color: #255156;">{{ $structure->organisme ?? $structure->nom ?? '-' }}</td>
                        <td class="px-3 py-3 align-middle" style="color: #3d6f74;">{{ $structure->siege_adresse ?? '-' }}</td>
                        <td class="px-3 py-3 align-middle" style="color: #3d6f74;">{{ $structure->siege_ville ?? '-' }}</td>
                        <td class="px-3 py-3 align-middle" style="color: #3d6f74;">{{ $structure->ville ?? '-' }}</td>
                        <td class="px-3 py-3 align-middle" style="color: #3d6f74;">{{ $structure->code_postal ?? '-' }}</td>
                        <td class="px-3 py-3 align-middle" style="color: #3d6f74;">{{ $structure->contact ?? '-' }}</td>
                        <td class="px-3 py-3 align-middle" style="color: #3d6f74;">{{ $structure->email ?? '-' }}</td>
                        <td class="px-3 py-3 align-middle">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium" 
                                  style="background: #e8f3f2; color: #255156;">
                                {{ $structure->type_structure ?? $structure->categories ?? '-' }}
                            </span>
                        </td>
                        <td class="px-3 py-3 align-middle">
                            @if($structure->site)
                                <a href="{{ $structure->site }}" target="_blank" class="hover:underline flex items-center gap-1" 
                                   style="color: #4a8599; font-size: 0.85rem;">
                                    <i class='bx bx-link-external'></i>
                                    {{ Str::limit(preg_replace('#^https?://#', '', $structure->site), 25) }}
                                </a>
                            @else
                                <span style="color: #b0c8cb;">-</span>
                            @endif
                        </td>
                        @if(auth()->user()->role === 'admin')
                        <td class="px-3 py-3 align-middle">
                            <div class="flex items-center gap-2">
                                <button class="edit-btn px-3 py-1.5 rounded-lg text-xs font-medium transition-all flex items-center gap-1"
                                        style="background: #e8f3f2; color: #255156; border: 1px solid #dceeec;"
                                        data-id="{{ $structure->id }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal"
                                        onmouseover="this.style.background='#255156'; this.style.color='white'; this.style.borderColor='#255156';"
                                        onmouseout="this.style.background='#e8f3f2'; this.style.color='#255156'; this.style.borderColor='#dceeec';">
                                    <i class='bx bx-edit-alt'></i>
                                    Modifier
                                </button>
                                <form action="{{ route('structures.destroy', $structure) }}" method="POST" 
                                      onsubmit="return confirm('Voulez-vous vraiment supprimer cette structure ? Tous les utilisateurs rattachés seront également supprimés.')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all flex items-center gap-1"
                                            style="background: #ffebee; color: #c62828; border: 1px solid #ffcdd2;"
                                            onmouseover="this.style.background='#ef5350'; this.style.color='white'; this.style.borderColor='#ef5350';"
                                            onmouseout="this.style.background='#ffebee'; this.style.color='#c62828'; this.style.borderColor='#ffcdd2';">
                                        <i class='bx bx-trash-alt'></i>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $structures->links() }}
    </div>
</div>

<!-- MODAL AJOUT -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
      <div class="modal-header" style="border-bottom: 2px solid #e8f3f2; padding: 1.25rem 1.5rem;">
        <h5 class="modal-title flex items-center gap-2" style="color: #255156; font-weight: 700;">
            <i class='bx bx-plus-circle' style="font-size: 1.5rem; color: #4a8599;"></i>
            Ajouter une structure
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding: 1.5rem;">
        @include('structures.form', [
            'structure' => new \App\Models\structures,
            'action' => route('structures.store'),
            'method' => 'POST'
        ])
      </div>
    </div>
  </div>
</div>

<!-- MODAL MODIFIER -->
@if(auth()->user()->role === 'admin')
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
      <div class="modal-header" style="border-bottom: 2px solid #e8f3f2; padding: 1.25rem 1.5rem;">
        <h5 class="modal-title flex items-center gap-2" style="color: #255156; font-weight: 700;">
            <i class='bx bx-edit-alt' style="font-size: 1.5rem; color: #4a8599;"></i>
            Modifier la structure
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding: 1.5rem;">
        @include('structures.edit', [
            'structure' => $structure, 
            'action' => route('structures.update', $structure->id),
            'method' => 'PUT'
        ])
      </div>
    </div>
  </div>
</div>
@endif

<style>
    /* Style net et précis pour les modals */
    .modal-content {
        border-radius: 1rem !important;
        background: white !important;
    }
    
    .modal-header {
        border-bottom: 2px solid #e8f3f2 !important;
        background: white !important;
    }
    
    .modal-body {
        background: white !important;
    }
    
    .modal-footer {
        border-top: 2px solid #e8f3f2 !important;
        background: white !important;
    }
    
    /* Style pour les selects dans les formulaires */
    select.form-control, 
    select.form-select {
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23255156' d='M6 8L1 3h10z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 12px !important;
        padding-right: 36px !important;
        border: 1px solid #dceeec !important;
        border-radius: 0.5rem !important;
        background-color: #f8fcfc !important;
        color: #255156 !important;
        font-size: 0.95rem !important;
        transition: all 0.2s ease !important;
    }
    
    select.form-control:focus,
    select.form-select:focus {
        border-color: #2d6268 !important;
        box-shadow: 0 0 0 4px rgba(45,98,104,0.08) !important;
        outline: none !important;
    }
    
    /* Style pour les inputs dans les formulaires */
    input.form-control {
        border: 1px solid #dceeec !important;
        border-radius: 0.5rem !important;
        background-color: #f8fcfc !important;
        color: #255156 !important;
        font-size: 0.95rem !important;
        transition: all 0.2s ease !important;
        padding: 0.6rem 0.75rem !important;
    }
    
    input.form-control:focus {
        border-color: #2d6268 !important;
        box-shadow: 0 0 0 4px rgba(45,98,104,0.08) !important;
        outline: none !important;
    }
    
    /* Style pour les boutons dans les formulaires */
    .btn-primary {
        background: linear-gradient(135deg, #255156, #3a7378) !important;
        border: none !important;
        color: white !important;
        font-weight: 600 !important;
        padding: 0.6rem 1.5rem !important;
        border-radius: 0.5rem !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 4px 12px rgba(37,81,86,0.25) !important;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(37,81,86,0.35) !important;
    }
    
    .btn-secondary {
        background: #e8f3f2 !important;
        border: 1px solid #dceeec !important;
        color: #255156 !important;
        font-weight: 600 !important;
        padding: 0.6rem 1.5rem !important;
        border-radius: 0.5rem !important;
        transition: all 0.2s ease !important;
    }
    
    .btn-secondary:hover {
        background: #d4ecea !important;
    }
    
    /* Style pour la pagination */
    .pagination {
        display: flex;
        gap: 0.35rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .pagination .page-item {
        list-style: none;
    }
    .pagination .page-link {
        padding: 0.45rem 0.9rem;
        border-radius: 0.5rem;
        background: white;
        color: #255156;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 0.9rem;
        border: 1px solid #dceeec;
        font-weight: 500;
    }
    .pagination .page-link:hover {
        background: #255156;
        color: white;
        border-color: #255156;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37,81,86,0.2);
    }
    .pagination .active .page-link {
        background: #255156;
        color: white;
        border-color: #255156;
        box-shadow: 0 4px 12px rgba(37,81,86,0.2);
    }
    .pagination .disabled .page-link {
        color: #b0c8cb;
        cursor: not-allowed;
        background: #f8fcfc;
    }
    
    /* Tableau - alternance des couleurs */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #fafdfd;
    }
    .table-striped tbody tr:nth-of-type(even) {
        background-color: #ffffff;
    }
    
    /* Scrollbar personnalisée */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #e8f3f2;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb {
        background: #4a8599;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #255156;
    }
</style>

@section('scripts')
<script>
    // Edit modal
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            fetch(`/structures/${id}/edit`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('editModalBody').innerHTML = html;
                })
                .catch(err => console.error(err));
        });
    });

    // Fermeture automatique des modals au clic extérieur
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            const modal = bootstrap.Modal.getInstance(e.target);
            if (modal) modal.hide();
        }
    });
</script>
@endsection