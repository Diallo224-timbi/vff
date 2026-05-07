@extends('base')

@section('title', 'Corbeille - Espace documentaire')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header text-white py-3" style="background: #255156; border: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-trash-alt me-2"></i>
                            <h4 class="d-inline-block mb-0 fw-bold">Corbeille</h4>
                            <p class="mt-1 mb-0 opacity-75 small">Ressources supprimées</p>
                        </div>
                        <!-- vider la corbeille -->
                        <div>
                            <form action="{{ route('resources.trash.empty') }}" method="POST" onsubmit="return confirm('⚠️ Attention ! Cette action est irréversible. Vider la corbeille ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background: #dc2626; color: white;">
                                    <i class="fas fa-trash-restore me-1"></i> Vider la corbeille
                                </button>
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('resources.index') }}" class="btn btn-sm btn-light text-[#255156]">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($resources->count() > 0)
                        <div class="mt-3">
                            @foreach($resources as $resource)
                            <div class="horizontal-resource-card resource-card mb-3">
                                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #fef2f2;">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="resource-icon-wrapper" style="width: 70px; height: 70px;">
                                                    @if($resource->is_link)
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                            <i class="fas fa-globe text-info fa-2x"></i>
                                                        </div>
                                                    @elseif($resource->is_image)
                                                        <div class="rounded-circle overflow-hidden w-100 h-100">
                                                            <img src="{{ Storage::url($resource->file_path) }}" alt="{{ $resource->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                        </div>
                                                    @else
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                            @php
                                                                $extension = strtolower($resource->file_type);
                                                            @endphp
                                                            @if(in_array($extension, ['pdf']))
                                                                <i class="bx bxs-file-pdf text-danger fa-2x"></i>
                                                            @elseif(in_array($extension, ['doc', 'docx', 'odt']))
                                                                <i class="fas fa-file-word text-primary fa-2x"></i>
                                                            @elseif(in_array($extension, ['xls', 'xlsx', 'csv']))
                                                                <i class="fas fa-file-excel text-success fa-2x"></i>
                                                            @else
                                                                <i class="fas fa-file text-secondary fa-2x"></i>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col">
                                                <div class="resource-info">
                                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                        <h6 class="fw-semibold mb-0">{{ $resource->title }}</h6>
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-trash me-1"></i>Supprimé
                                                        </span>
                                                    </div>
                                                    <p class="small text-muted mb-1">Supprimé le : {{ $resource->deleted_at->format('d/m/Y à H:i') }}</p>
                                                    @if($resource->description)
                                                        <p class="small text-muted mb-1">{{ \Illuminate\Support\Str::limit($resource->description, 100) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col-auto">
                                                <div class="d-flex gap-2">
                                                    <button onclick="restoreResource({{ $resource->id }})" class="btn btn-sm" style="background: #dcfce7; color: #16a34a;" title="Restaurer">
                                                        <i class="fas fa-trash-restore"></i> Restaurer
                                                    </button>
                                                    <button onclick="forceDeleteResource({{ $resource->id }})" class="btn btn-sm" style="background: #fee2e2; color: #dc2626;" title="Supprimer définitivement">
                                                        <i class="fas fa-trash-alt"></i> Supprimer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $resources->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-trash-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">La corbeille est vide</p>
                            <a href="{{ route('resources.index') }}" class="btn" style="background: #255156; color: white;">
                                <i class="fas fa-arrow-left me-1"></i> Retour à l'espace documentaire
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function restoreResource(id) {
    if (confirm('Restaurer cette ressource ?')) {
        fetch(`/resources/${id}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la restauration');
            }
        }).catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de connexion');
        });
    }
}

function forceDeleteResource(id) {
    if (confirm('Attention ! Cette action est irréversible. Supprimer définitivement cette ressource ?')) {
        fetch(`/resources/${id}/force-delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression définitive');
            }
        }).catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de connexion');
        });
    }
}
</script>

<style>
.horizontal-resource-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.horizontal-resource-card:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.resource-icon-wrapper {
    border-radius: 12px;
    overflow: hidden;
}
</style>
@endsection