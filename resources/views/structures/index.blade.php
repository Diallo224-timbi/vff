@extends('base')

@section('title', 'Gestion des structures')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Gestion des structures</h1>

    <!-- Message succès -->
    @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4"
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"    
        >
            {{ session('success') }}
        </div>
    @elseif(session('errors'))
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4 shadow transition duration-500"
        >
            {{ session('errors') }}
        </div>
    @endif

    <!-- Bouton ajouter structure -->
    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addModal">
        Ajouter une structure
    </button>

    <!-- Recherche -->
    <form method="GET" action="{{ route('structures.index') }}" class="mb-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="border rounded w-full px-3 py-2">
    </form>

    <!-- Table avec en-tête fixe -->
    <div class="overflow-auto border rounded" style="max-height:500px;">
        <table class="table table-bordered table-striped w-full">
            <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                    <th>Nom / Organisme</th>
                    <th>Adresse siège</th>
                    <th>Ville siège</th>
                    <th>Ville</th>
                    <th>Code Postal</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Type / Catégorie</th>
                    <th>site internet</th>
                    @if(auth()->user()->role === 'admin')
                    <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($structures as $structure)
                    <tr>
                        <td>{{ $structure->organisme }}</td>
                        <td>{{ $structure->siege_adresse }}</td>
                        <td>{{ $structure->siege_ville }}</td>
                        <td>{{ $structure->ville }}</td>
                        <td>{{ $structure->code_postal }}</td>
                        <td>{{ $structure->contact ?? '-' }}</td>
                        <td>{{ $structure->email ?? '-' }}</td>
                        <td>{{ $structure->type_structure ?? $structure->categories ?? '-' }}</td>
                        <td>
                            @if($structure->site)
                                <a href="{{ $structure->site }}" target="_blank" class="text-blue-600 underline">
                                    {{ Str::limit($structure->site, 30) }}
                                </a>
                            @else
                                -
                            @endif
                        @if(auth()->user()->role === 'admin')
                        <td class="d-flex gap-2">
                            <!-- Modifier -->
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $structure->id }}" data-bs-toggle="modal" data-bs-target="#editModal">
                                Modifier
                            </button>

                            <!-- Supprimer -->
                            <form action="{{ route('structures.destroy', $structure) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ! attention tous les utilisateurs rattachés se suppriment aussi ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ajouter une structure</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modifier la structure</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="editModalBody">
        <!-- Formulaire chargé dynamiquement via fetch -->
      </div>
    </div>
  </div>
</div>
@endif
@endsection

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
</script>
@endsection
