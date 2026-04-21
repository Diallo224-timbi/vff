@extends('base')
@section('title','Espace des organismes')
@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Liste des Organismes</h1>
    <a href="{{ route('organismes.create') }}" class="btn btn-primary mb-3 bg-[#255156] to-[#255170]">Ajouter un Organisme</a>
    <table class="table table-bordered">
        <!-- barre de recherche pour filtrer les organismes par nom ou ville -->
        <div class="mb-3">
            <input type="text" class="form-control" id="search" placeholder="Rechercher par nom ou ville...">
        </div>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Adresse</th>
                <th>Code postal</th>
                <th>Ville</th>
                <th>Site web</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($organismes as $organisme)
            <tr>
                <td >{{ $organisme->id }}</td>
                <td>{{ $organisme->nom_organisme }}</td>
                <td>{{ $organisme->signification }}</td>
                <td>{{ $organisme->adresse }}</td>
                <td>{{ $organisme->code_postal }}</td>
                <td>{{ $organisme->ville }}</td>
                <td>
                    @if($organisme->site_web)
                    <a href="{{ $organisme->site_web }}" target="_blank" class="btn btn-secondary"><i class="bx bx-link"></i></a>
                    @endif
                <td>
                    <a href="{{ route('organismes.show', $organisme->id) }}" class="btn btn-info"><i class="bx bx-show"></i></a>
                    <a href="{{ route('organismes.edit', $organisme->id) }}" class="btn btn-warning"><i class="bx bx-edit"></i></a>
                    <form action="{{ route('organismes.destroy', $organisme->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet organisme ?')"><i class="bx bx-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>  
</div>
@endsection
<script>
    // Script pour confirmer la suppression d'un organisme
    document.querySelectorAll('.btn-danger').forEach(button => {
        button.addEventListener('click', function(event) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet organisme ?')) {
                event.preventDefault();
            }
        });
    });
    // script pour afficher les messages de succès ou d'erreur en mode modal
     @if(session('success'))
        alert("{{ session('success') }}");  
    @endif
    @if(session('error'))
        alert("{{ session('error') }}");
    @endif
// script pour filtrer les organismes par nom ou ville
    document.getElementById('search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const name = row.children[1].textContent.toLowerCase();
            const city = row.children[5].textContent.toLowerCase();
            if (name.includes(searchTerm) || city.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }); 
</script>