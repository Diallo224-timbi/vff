@extends('base')
@section('title', 'Ajouter un organisme')
@section('content')

<div class="modal fade" id="organismeCreateModal" tabindex="-1" aria-labelledby="organismeCreateModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable organisme-create-dialog">
        <form class="modal-content organisme-create-modal" action="{{ route('organismes.store') }}" method="POST" id="organismeForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-header text-white organisme-create-modal-header">
                <h1 class="modal-title fs-5" id="organismeCreateModalTitle">
                    <i class="fas fa-building me-2" aria-hidden="true"></i>Ajouter un organisme
                </h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body organisme-create-modal-body">
                @if(session('error'))
                    <div class="alert alert-danger py-2 mb-2" role="alert">{{ session('error') }}</div>
                @endif
                <div class="row g-2">
                    
                        <div class="col-12 col-md-5">
                            <label for="logo" class="form-label fw-semibold mb-1">
                                <i class="fas fa-image me-1" aria-hidden="true"></i>Logo
                            </label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        </div>
                        <div class="col-12 col-md-7">
                            <label for="nom" class="form-label fw-semibold mb-1">
                                <i class="fas fa-tag me-1" aria-hidden="true"></i>Nom de l'organisme <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="nom" name="nom_organisme" value="{{ old('nom_organisme') }}"
                                placeholder="Ex : Association culturelle" required>
                        </div>
                        <div class="col-12">
                            <label for="signification" class="form-label fw-semibold mb-1">
                                <i class="fas fa-align-left me-1" aria-hidden="true"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="signification" name="signification" rows="2"
                                    placeholder="Décrivez la mission et les activités de l'organisme..." required>{{ old('signification') }}</textarea>
                        </div>
                        <div class="col-12 col-md-8">
                            <label for="adresse" class="form-label fw-semibold mb-1">
                                <i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i>Adresse <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="adresse" name="adresse" value="{{ old('adresse') }}"
                                   placeholder="Numéro et nom de rue" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="code_postal" class="form-label fw-semibold mb-1">
                                <i class="fas fa-hashtag me-1" aria-hidden="true"></i>Code postal <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="code_postal" name="code_postal" value="{{ old('code_postal') }}"
                                   placeholder="06000" maxlength="5" required>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="ville" class="form-label fw-semibold mb-1">
                                <i class="fas fa-city me-1" aria-hidden="true"></i>Ville <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="ville" name="ville" value="{{ old('ville') }}" placeholder="Nice" required>
                        </div>
                        <div class="col-12 col-md-7">
                            <label for="site_web" class="form-label fw-semibold mb-1">
                                <i class="fas fa-globe me-1" aria-hidden="true"></i>Site web
                            </label>
                            <input type="url" class="form-control" id="site_web" name="site_web" value="{{ old('site_web') }}"
                                   placeholder="https://exemple.org">
                        </div>
                    </div>
                </div>
                <div class="modal-footer organisme-create-modal-footer">
                    <a href="{{ route('organismes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1" aria-hidden="true"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary organisme-create-submit" id="submitBtn">
                        <i class="fas fa-save me-1" aria-hidden="true"></i>Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .organisme-create-modal {
        border: 0;
        border-radius: 0.9rem;
        overflow: hidden;
    }

    .organisme-create-modal-header {
        padding: 0.85rem 1rem;
        background: #255156;
    }

    .organisme-create-modal-body {
        padding: 1rem;
    }

    .organisme-create-modal-body .form-label {
        margin-bottom: 0.25rem;
        color: #333;
        font-size: 0.92rem;
    }

    .organisme-create-modal-body .form-control {
        min-width: 0;
        padding: 0.4rem 0.7rem;
    }

    .organisme-create-modal-body .form-control:focus {
        border-color: #255156;
        box-shadow: 0 0 0 0.15rem rgba(37, 81, 86, 0.15);
    }

    .organisme-create-modal-body .form-control::placeholder {
        color: #adb5bd;
        font-size: 0.9rem;
    }

    .organisme-create-modal-footer {
        padding: 0.75rem 1rem;
    }

    .organisme-create-submit {
        border-color: #255156;
        background: #255156;
    }

    .organisme-create-submit:hover,
    .organisme-create-submit:focus {
        border-color: #1a3a3f;
        background: #1a3a3f;
    }

    @media (max-width: 575.98px) {
        .organisme-create-dialog {
            margin: 0.5rem;
        }

        .organisme-create-modal-body {
            padding: 0.75rem;
        }
    }
</style>

<!-- Script pour la validation en temps réel -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('organismeCreateModal');
        const postalCodeInput = document.getElementById('code_postal');
        const websiteInput = document.getElementById('site_web');
        const form = document.getElementById('organismeForm');

        if (modalElement) {
            document.body.appendChild(modalElement);
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            modalElement.addEventListener('hidden.bs.modal', function() {
                window.location.assign(@json(route('organismes.index')));
            }, { once: true });
            modal.show();
        }

        postalCodeInput?.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
        });

        websiteInput?.addEventListener('input', function() {
            const isValidHttpUrl = /^https?:\/\/.+/i.test(this.value);
            this.setCustomValidity(this.value && !isValidHttpUrl
                ? 'Veuillez saisir une URL valide commençant par http:// ou https://'
                : '');
        });

        form?.addEventListener('submit', function() {
            const button = document.getElementById('submitBtn');
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i>Ajout...';
            button.disabled = true;
        });
    });
</script>

@endsection