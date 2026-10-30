@extends('base')
@section('title', 'Modifier un organisme')
@section('content')

<div class="modal fade" id="organismeEditModal" tabindex="-1" aria-labelledby="organismeEditModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable organisme-edit-dialog">
        <form class="modal-content organisme-edit-modal" action="{{ route('organismes.update', $organisme->id) }}" method="POST" id="organismeForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header text-white organisme-edit-modal-header">
                <div>
                    <h1 class="modal-title fs-5" id="organismeEditModalTitle">
                        <i class="fas fa-edit me-2" aria-hidden="true"></i>Modifier l'organisme
                    </h1>
                    <small class="opacity-75">{{ $organisme->nom_organisme }}</small>
                </div>
                @if($organisme->logo_path && file_exists(storage_path('app/public/' . $organisme->logo_path)))
                    <button type="button" class="organisme-header-logo" aria-label="Agrandir le logo de {{ $organisme->nom_organisme }}"
                        onclick="openLogoZoom(@js(asset('storage/' . $organisme->logo_path)), @js($organisme->nom_organisme))">
                        <img src="{{ asset('storage/' . $organisme->logo_path) }}" alt="">
                    </button>
                @else
                    <span class="organisme-header-logo organisme-header-logo-empty" aria-hidden="true">
                        <i class="fas fa-building"></i>
                    </span>
                @endif
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body organisme-edit-modal-body">
                @if(session('error'))
                    <div class="alert alert-danger py-2 mb-2" role="alert">{{ session('error') }}</div>
                @endif
                <div class="organisme-edit-section">
            
                    <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <label for="nom" class="form-label fw-semibold mb-1">
                            <i class="fas fa-tag me-1" aria-hidden="true"></i>Nom de l'organisme <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nom" name="nom_organisme"
                               value="{{ old('nom_organisme', $organisme->nom_organisme) }}" placeholder="Ex : Association culturelle" required>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="site_web" class="form-label fw-semibold mb-1">
                            <i class="fas fa-globe me-1" aria-hidden="true"></i>Site web <span class="text-danger">*</span>
                        </label>
                        <input type="url" class="form-control" id="site_web" name="site_web"
                               value="{{ old('site_web', $organisme->site_web) }}" placeholder="https://exemple.org" required>
                    </div>
                    <div class="col-12">
                        <label for="signification" class="form-label fw-semibold mb-1">
                            <i class="fas fa-align-left me-1" aria-hidden="true"></i>Description <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="signification" name="signification" rows="3"
                                placeholder="Décrivez la mission et les activités de l'organisme..." required>{{ old('signification', $organisme->signification) }}</textarea>
                    </div>
                    <div class="col-12 organisme-logo-upload">
                        <label class="form-label fw-semibold mb-1" for="logo">
                            <i class="fas fa-image me-1" aria-hidden="true"></i>Remplacer le logo
                        </label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                    </div>
                    </div>
                </div>
                <div class="organisme-edit-section">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label for="adresse" class="form-label fw-semibold mb-1">
                                <i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i>Adresse <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="adresse" name="adresse"
                                   value="{{ old('adresse', $organisme->adresse) }}" placeholder="Numéro et nom de rue" required>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="code_postal" class="form-label fw-semibold mb-1">
                                <i class="fas fa-hashtag me-1" aria-hidden="true"></i>Code postal <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="code_postal" name="code_postal"
                                   value="{{ old('code_postal', $organisme->code_postal) }}" placeholder="06000" maxlength="5" required>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="ville" class="form-label fw-semibold mb-1">
                                <i class="fas fa-city me-1" aria-hidden="true"></i>Ville <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="ville" name="ville"
                                   value="{{ old('ville', $organisme->ville) }}" placeholder="Nice" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer organisme-edit-modal-footer">
                <a href="{{ route('organismes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1" aria-hidden="true"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary organisme-edit-submit" id="submitBtn">
                    <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL ZOOM LOGO -->
<div class="modal fade" id="logoZoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 1rem;">
            <div class="modal-body p-4 text-center">
                <button type="button" class="btn-close btn-close-white float-end" data-bs-dismiss="modal"></button>
                <div class="mt-2">
                    <img id="logoZoomImage" src="" alt="Logo agrandi"
                        style="max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 0.5rem;">
                    <p id="logoZoomTitle" class="text-white mt-3 font-semibold"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles CSS -->
<style>
    .organisme-edit-modal {
        border: 0;
        border-radius: 0.9rem;
        overflow: hidden;
    }

    .organisme-edit-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1rem;
        background: #255156;
    }

    .organisme-edit-modal-header .modal-title {
        color: #fff;
    }

    .organisme-edit-modal-header > div {
        min-width: 0;
        flex: 1 1 auto;
    }

    .organisme-header-logo {
        display: inline-flex;
        width: 2.75rem;
        height: 2.75rem;
        flex: 0 0 2.75rem;
        align-items: center;
        justify-content: center;
        margin: 0 1rem;
        padding: 0.15rem;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.55);
        border-radius: 0.55rem;
        background: rgba(255, 255, 255, 0.95);
        cursor: pointer;
    }

    .organisme-header-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .organisme-header-logo-empty {
        color: #255156;
        font-size: 1.25rem;
    }

    .organisme-edit-modal-body {
        padding: 1rem;
    }

    .organisme-edit-section + .organisme-edit-section {
        margin-top: 1.25rem;
    }

    .organisme-edit-section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.45rem;
        border-bottom: 1px solid #e5eeee;
        color: #255156;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.045em;
        text-transform: uppercase;
    }

    .organisme-edit-section-title i {
        font-size: 0.9rem;
    }

    .organisme-edit-modal-body .form-label {
        margin-bottom: 0.25rem;
        color: #333;
        font-size: 0.92rem;
    }

    .organisme-edit-modal-body .form-control {
        min-width: 0;
        padding: 0.4rem 0.7rem;
    }

    .organisme-edit-modal-body input[type="file"] {
        display: block;
        width: 100%;
        height: 2.15rem;
        padding: 0.2rem 0.35rem;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-size: 0.72rem;
        line-height: 1.6;
    }

    .organisme-edit-modal-body input[type="file"]::file-selector-button {
        margin: -0.1rem 0.35rem -0.1rem -0.1rem;
        padding: 0.2rem 0.35rem;
        border: 0;
        border-radius: 0.25rem;
        background: #eaf2f1;
        color: #255156;
        white-space: nowrap;
        font-size: 0.72rem;
    }

    .organisme-edit-modal-body .form-control:focus {
        border-color: #255156;
        box-shadow: 0 0 0 0.15rem rgba(37, 81, 86, 0.15);
    }

    .organisme-edit-modal-body .form-control::placeholder {
        color: #adb5bd;
        font-size: 0.9rem;
    }

    .organisme-edit-modal-footer {
        padding: 0.75rem 1rem;
    }

    .organisme-edit-submit {
        border-color: #255156;
        background: #255156;
    }

    .organisme-edit-submit:hover,
    .organisme-edit-submit:focus {
        border-color: #1a3a3f;
        background: #1a3a3f;
    }

    @media (max-width: 575.98px) {
        .organisme-edit-dialog {
            margin: 0.5rem;
        }

        .organisme-edit-modal-body {
            padding: 0.75rem;
        }

        .organisme-header-logo {
            width: 2.25rem;
            height: 2.25rem;
            flex-basis: 2.25rem;
            margin: 0 0.5rem;
        }

        .organisme-edit-section + .organisme-edit-section {
            margin-top: 1rem;
        }
    }
</style>

<!-- Script pour la validation en temps réel -->
<script>
    function openLogoZoom(imageUrl, title) {
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('logoZoomModal'));
        const img = document.getElementById('logoZoomImage');
        const titleEl = document.getElementById('logoZoomTitle');
        if (imageUrl && imageUrl !== '') {
            img.src = imageUrl;
            img.style.display = 'block';
            titleEl.textContent = title || 'Logo';
        } else {
            img.style.display = 'none';
            titleEl.textContent = 'Aucun logo disponible';
        }
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const editModalElement = document.getElementById('organismeEditModal');
        const logoModalElement = document.getElementById('logoZoomModal');
        const codePostalInput = document.getElementById('code_postal');
        const siteWebInput = document.getElementById('site_web');
        const form = document.getElementById('organismeForm');

        if (editModalElement) {
            document.body.appendChild(editModalElement);
            const editModal = bootstrap.Modal.getOrCreateInstance(editModalElement);
            editModalElement.addEventListener('hidden.bs.modal', function() {
                window.location.assign(@json(route('organismes.index')));
            }, { once: true });
            editModal.show();
        }

        if (logoModalElement) {
            document.body.appendChild(logoModalElement);
        }

        codePostalInput?.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
        });

        siteWebInput?.addEventListener('input', function() {
            const isValidHttpUrl = /^https?:\/\/.+/i.test(this.value);
            this.setCustomValidity(this.value && !isValidHttpUrl
                ? 'Veuillez saisir une URL valide commençant par http:// ou https://'
                : '');
        });

        form?.addEventListener('submit', function() {
            const button = document.getElementById('submitBtn');
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i>Enregistrement...';
            button.disabled = true;
        });
    });
</script>

@endsection