@extends('base')

@section('title', 'Mon profil')

@section('content')
@php
    $structure = $user->structure;
    $organisme = $structure?->organisme;
    $roleLabel = match ($user->role) {
        'admin' => 'Administrateur',
        'moderateur' => 'Responsable d’organisme',
        'moderateur_classique' => 'Responsable de structure',
        default => 'Utilisateur',
    };
    $initiales = mb_strtoupper(mb_substr($user->prenom ?? $user->name ?? '?', 0, 1) . mb_substr($user->name ?? '', 0, 1));
@endphp

<main class="container py-3 profile-page">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="status" aria-live="polite">
                    <i class="fa-solid fa-circle-check me-2" aria-hidden="true"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer le message"></button>
                </div>
            @endif

            <section class="card border-0 shadow-sm mb-3 profile-summary-card" aria-labelledby="profile-title">
                <div class="card-body px-4 py-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                        <div class="profile-avatar flex-shrink-0" aria-hidden="true">{{ $initiales }}</div>
                        <div class="flex-grow-1">
                            <p class="text-uppercase small fw-semibold text-secondary mb-1">Mon espace</p>
                            <h1 id="profile-title" class="h3 mb-1">
                                {{ $user->prenom }} {{ $user->name }}
                            </h1>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="badge profile-role-badge">{{ $roleLabel }}</span>
                                <span class="text-secondary small">
                                    <i class="fa-solid fa-calendar-days me-1" aria-hidden="true"></i>
                                    Membre depuis {{ $user->created_at?->format('d/m/Y') ?? 'date inconnue' }}
                                </span>
                            </div>
                        </div>
                        <button type="button" class="btn profile-primary-btn px-4" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fa-solid fa-pen-to-square me-2" aria-hidden="true"></i>
                            Modifier mon profil
                        </button>
                    </div>
                </div>
            </section>

            <div class="row g-3">
                <div class="col-12 col-lg-7">
                    <section class="card border-0 shadow-sm h-100" aria-labelledby="personal-info-title">
                        <div class="card-header profile-card-header border-bottom py-2">
                            <h2 id="personal-info-title" class="h6 mb-0">
                                <i class="fa-solid fa-user me-2 profile-accent" aria-hidden="true"></i>
                                Informations personnelles
                            </h2>
                        </div>
                        <div class="card-body px-3 py-2">
                            <dl class="row mb-0 profile-details">
                                <dt class="col-sm-4">Adresse e-mail</dt>
                                <dd class="col-sm-8"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></dd>

                                <dt class="col-sm-4">Téléphone</dt>
                                <dd class="col-sm-8">{{ $user->phone ?: 'Non renseigné' }}</dd>

                                <dt class="col-sm-4">Fonction</dt>
                                <dd class="col-sm-8">{{ $user->fonction ?: 'Non renseignée' }}</dd>

                                <dt class="col-sm-4">Adresse</dt>
                                <dd class="col-sm-8">
                                    @if($user->adresse || $user->code_postal || $user->ville)
                                        {{ $user->adresse }}@if($user->adresse && ($user->code_postal || $user->ville)), @endif{{ $user->code_postal }} {{ $user->ville }}
                                    @else
                                        Non renseignée
                                    @endif
                                </dd>

                                <dt class="col-sm-4">Structure (ville)</dt>
                                <dd class="col-sm-8">{{ $structure?->ville ?: 'Non renseignée' }}</dd>

                                <dt class="col-sm-4">Organisme</dt>
                                <dd class="col-sm-8">{{ $organisme?->nom_organisme ?? 'Non renseigné' }}</dd>

                                <dt class="col-sm-4">Notifications</dt>
                                <dd class="col-sm-8">{{ $user->notification ? 'Activées' : 'Désactivées' }}</dd>
                            </dl>
                        </div>
                    </section>
                </div>

                <div class="col-12 col-lg-5">
                    <section class="card border-0 shadow-sm mb-4" aria-labelledby="security-title">
                        <div class="card-header profile-card-header border-bottom py-2">
                            <h2 id="security-title" class="h6 mb-0">
                                <i class="fa-solid fa-shield-halved me-2 profile-accent" aria-hidden="true"></i>
                                Sécurité du compte
                            </h2>
                        </div>
                        <div class="card-body p-3">
                            <p class="mb-2">
                                <span class="d-block text-secondary small">Dernière connexion</span>
                                <span class="fw-semibold">
                                    {{ $user->last_login_at ? \Illuminate\Support\Carbon::parse($user->last_login_at)->format('d/m/Y à H:i') : 'Première connexion' }}
                                </span>
                            </p>
                            <a href="{{ route('password.request') }}" class="btn profile-outline-btn w-100">
                                <i class="fa-solid fa-key me-2" aria-hidden="true"></i>
                                Changer mon mot de passe
                            </a>
                        </div>
                    </section>

                    <section class="card border-0 shadow-sm" aria-labelledby="role-title">
                        <div class="card-header profile-card-header border-bottom py-2">
                            <h2 id="role-title" class="h6 mb-0">
                                <i class="fa-solid fa-user-shield me-2 profile-accent" aria-hidden="true"></i>
                                Rôle et accès
                            </h2>
                        </div>
                        <div class="card-body p-3">
                            <p class="mb-2"><span class="fw-semibold">Rôle :</span> {{ $roleLabel }}</p>
                            @if($user->role === 'admin')
                                <p class="mb-0 text-secondary">Votre compte dispose des accès administrateur.</p>
                            @elseif($user->role === 'moderateur')
                                <p class="mb-0 text-secondary">Votre compte permet de gérer votre organisme et ses structures.</p>
                            @elseif($user->role === 'moderateur_classique')
                                <p class="mb-0 text-secondary">Votre compte permet de gérer votre structure et ses membres.</p>
                            @else
                                <p class="mb-0 text-secondary">Vous pouvez gérer vos informations personnelles et consulter les services auxquels vous avez accès.</p>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileTitle" aria-describedby="editProfileDescription">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable profile-edit-dialog">
        <div class="modal-content profile-edit-content">
            <form action="{{ route('profile.update') }}" method="POST" id="profileForm" class="profile-edit-form">
                @csrf
                @method('PUT')

                <div class="modal-header profile-edit-header text-white">
                    <div>
                        <h2 class="modal-title fs-5" id="editProfileTitle">Modifier mon profil</h2>
                        <p id="editProfileDescription" class="small mb-0">Les champs marqués d’un astérisque sont obligatoires.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer la fenêtre de modification"></button>
                </div>

                <div class="modal-body profile-edit-body">
                    @if($errors->any())
                        <div class="alert alert-danger" id="profileErrorSummary" role="alert" aria-live="assertive" tabindex="-1">
                            <h3 class="h6 alert-heading">Le profil n’a pas été enregistré.</h3>
                            <p class="mb-2">Corrigez les erreurs suivantes :</p>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <fieldset class="mb-3">
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="profile-prenom" class="form-label">Prénom <span class="text-danger" aria-hidden="true">*</span><span class="visually-hidden"> (obligatoire)</span></label>
                                <input id="profile-prenom" name="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom', $user->prenom) }}" autocomplete="given-name" maxlength="255" required aria-required="true" @error('prenom') aria-invalid="true" aria-describedby="profile-prenom-error" @enderror>
                                @error('prenom')<div class="invalid-feedback" id="profile-prenom-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="profile-name" class="form-label">Nom <span class="text-danger" aria-hidden="true">*</span><span class="visually-hidden"> (obligatoire)</span></label>
                                <input id="profile-name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" autocomplete="family-name" maxlength="255" required aria-required="true" @error('name') aria-invalid="true" aria-describedby="profile-name-error" @enderror>
                                @error('name')<div class="invalid-feedback" id="profile-name-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="profile-fonction" class="form-label">Fonction</label>
                                <input id="profile-fonction" name="fonction" type="text" class="form-control @error('fonction') is-invalid @enderror" value="{{ old('fonction', $user->fonction) }}" autocomplete="organization-title" maxlength="255" @error('fonction') aria-invalid="true" aria-describedby="profile-fonction-error" @enderror>
                                @error('fonction')<div class="invalid-feedback" id="profile-fonction-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="profile-email" class="form-label">Adresse e-mail <span class="text-danger" aria-hidden="true">*</span><span class="visually-hidden"> (obligatoire)</span></label>
                                <input id="profile-email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" autocomplete="email" maxlength="255" required aria-required="true" @error('email') aria-invalid="true" aria-describedby="profile-email-error" @enderror>
                                @error('email')<div class="invalid-feedback" id="profile-email-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="profile-phone" class="form-label">Téléphone</label>
                                <input id="profile-phone" name="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" autocomplete="tel" maxlength="20" @error('phone') aria-invalid="true" aria-describedby="profile-phone-error" @enderror>
                                @error('phone')<div class="invalid-feedback" id="profile-phone-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="row g-3">
                            <div class="col-12 col-md-5">
                                <label for="profile-adresse" class="form-label">Adresse</label>
                                <input id="profile-adresse" name="adresse" type="text" class="form-control @error('adresse') is-invalid @enderror" value="{{ old('adresse', $user->adresse) }}" autocomplete="street-address" maxlength="255" @error('adresse') aria-invalid="true" aria-describedby="profile-adresse-error" @enderror>
                                @error('adresse')<div class="invalid-feedback" id="profile-adresse-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="profile-code-postal" class="form-label">Code postal</label>
                                <input id="profile-code-postal" name="code_postal" type="text" class="form-control @error('code_postal') is-invalid @enderror" value="{{ old('code_postal', $user->code_postal) }}" autocomplete="postal-code" maxlength="20" @error('code_postal') aria-invalid="true" aria-describedby="profile-code-postal-error" @enderror>
                                @error('code_postal')<div class="invalid-feedback" id="profile-code-postal-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="profile-ville" class="form-label">Ville</label>
                                <input id="profile-ville" name="ville" type="text" class="form-control @error('ville') is-invalid @enderror" value="{{ old('ville', $user->ville) }}" autocomplete="address-level2" maxlength="100" @error('ville') aria-invalid="true" aria-describedby="profile-ville-error" @enderror>
                                @error('ville')<div class="invalid-feedback" id="profile-ville-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <input type="hidden" name="notification" value="0">
                                <div class="form-check">
                                    <input id="profile-notification" name="notification" type="checkbox" class="form-check-input @error('notification') is-invalid @enderror" value="1" @checked((bool) old('notification', $user->notification)) @error('notification') aria-invalid="true" aria-describedby="profile-notification-error" @enderror>
                                    <label for="profile-notification" class="form-check-label">Recevoir les notifications concernant les ressources</label>
                                    @error('notification')<div class="invalid-feedback" id="profile-notification-error">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="modal-footer profile-edit-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn profile-primary-btn">
                        <i class="fa-solid fa-floppy-disk me-2" aria-hidden="true"></i>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .profile-page {
        --profile-deep: #255156;
        --profile-dark: #1a3c40;
        --profile-teal: #0a9dad;
        --profile-soft: #e9f7f6;
        --profile-accent: #54a7b1;
    }

    .profile-page > .row > div > .card {
        border-radius: 0.85rem;
    }

    .profile-page {
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
    }

    .profile-summary-card .profile-avatar {
        width: 3.75rem;
        height: 3.75rem;
        font-size: 1.2rem;
    }

    .profile-summary-card .profile-primary-btn {
        padding: 0.5rem 1rem !important;
    }

    .profile-page .card {
        overflow: hidden;
    }

    .profile-page .card-header {
        background: linear-gradient(110deg, var(--profile-deep), var(--profile-teal));
        color: #fff;
        border-color: rgba(255, 255, 255, 0.18) !important;
    }

    .profile-page .card-header h2 {
        color: #fff;
    }

    .profile-page .profile-accent {
        color: #fff;
    }

    .profile-page #profile-title {
        color: var(--profile-deep);
    }

    .profile-page .profile-avatar {
        background: linear-gradient(135deg, var(--profile-deep), var(--profile-accent));
    }

    .profile-page .profile-role-badge {
        background-color: var(--profile-deep);
        color: #fff;
    }

    .profile-page .profile-primary-btn {
        border: 1px solid var(--profile-deep);
        background: var(--profile-deep);
        color: #fff;
    }

    .profile-page .profile-primary-btn:hover {
        border-color: var(--profile-dark);
        background: var(--profile-dark);
        color: #fff;
    }

    .profile-page .profile-outline-btn {
        border-color: var(--profile-deep);
        color: var(--profile-deep);
    }

    .profile-page .profile-outline-btn:hover {
        border-color: var(--profile-deep);
        background: var(--profile-deep);
        color: #fff;
    }

    .profile-page a:not(.btn) {
        color: var(--profile-deep);
    }

    .profile-avatar {
        display: grid;
        width: 4.5rem;
        height: 4.5rem;
        place-items: center;
        border-radius: 50%;
        background: #255156;
        color: #fff;
        font-size: 1.4rem;
        font-weight: 700;
    }

    .profile-details dt,
    .profile-details dd {
        margin-bottom: 0.55rem;
        overflow-wrap: anywhere;
    }

    .profile-details dt {
        color: #495057;
    }

    .profile-page .card-header h2 {
        line-height: 1.3;
    }

    .profile-page .card-body > p:last-child {
        margin-bottom: 0;
    }

    .profile-edit-dialog {
        width: min(820px, calc(100vw - 2rem));
        max-width: 820px !important;
        margin: 1rem auto;
    }

    .profile-edit-content {
        --profile-deep: #255156;
        --profile-dark: #1a3c40;
        --profile-teal: #0a9dad;
        --profile-soft: #e9f7f6;
        --profile-accent: #54a7b1;
        max-height: calc(100dvh - 2rem);
        overflow: hidden;
        border: 0;
        border-radius: 0.85rem;
    }

    .profile-edit-content .profile-primary-btn {
        border-color: var(--profile-deep);
        background: var(--profile-deep);
        color: #fff;
    }

    .profile-edit-content .profile-primary-btn:hover {
        border-color: var(--profile-dark);
        background: var(--profile-dark);
        color: #fff;
    }

    .profile-edit-form {
        display: flex;
        min-height: 0;
        max-height: calc(100dvh - 2rem);
        flex-direction: column;
    }

    .profile-edit-header,
    .profile-edit-footer {
        flex: 0 0 auto;
        padding: 0.85rem 1.25rem;
        background-color: #fff;
    }

    .profile-edit-header {
        border-bottom: 1px solid var(--profile-teal);
        background: linear-gradient(110deg, var(--profile-deep), var(--profile-teal));
    }

    .profile-edit-header p {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .profile-edit-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .profile-edit-header .modal-title {
        font-size: 1.2rem;
        line-height: 1.35;
    }

    .profile-edit-header p {
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .profile-edit-body {
        min-height: 0;
        overflow-y: auto;
        padding: 1.1rem 1.25rem;
        font-size: 0.95rem;
    }

    .profile-edit-body fieldset {
        min-width: 0;
    }

    .profile-edit-body legend {
        float: none;
        width: auto;
        border-color: #b7dadd !important;
        color: var(--profile-deep);
        font-size: 1rem;
        line-height: 1.4;
    }

    .profile-edit-body .form-label {
        margin-bottom: 0.3rem;
        font-size: 0.9rem;
        line-height: 1.35;
    }

    .profile-edit-body .form-control {
        min-height: 2.65rem;
        padding: 0.5rem 0.75rem;
        border-color: #b8cdd0;
        font-size: 0.95rem;
    }

    .profile-edit-body .form-control:focus,
    .profile-edit-body .form-check-input:checked {
        border-color: var(--profile-teal);
    }

    .profile-edit-body .form-check-input:checked {
        background-color: var(--profile-deep);
    }

    .profile-edit-footer {
        justify-content: flex-end;
        gap: 0.5rem;
        border-top: 1px solid #b7dadd;
        background: #f4fbfb;
    }

    .form-control:focus,
    .form-check-input:focus,
    .btn:focus-visible,
    .btn-close:focus-visible {
        outline: 3px solid #f2c94c;
        outline-offset: 2px;
        box-shadow: none;
    }

    .profile-page :is(.btn, .form-control, .form-check-input):focus-visible {
        outline: 3px solid #f2c94c;
        outline-offset: 2px;
        box-shadow: 0 0 0 2px var(--profile-deep);
    }

    .profile-edit-content :is(.btn, .form-control, .form-check-input):focus-visible {
        outline: 3px solid #f2c94c;
        outline-offset: 2px;
        box-shadow: 0 0 0 2px var(--profile-deep);
    }

    @media (max-width: 575.98px) {
        .profile-edit-dialog {
            width: calc(100vw - 1rem);
            max-width: none !important;
            margin: 0.5rem auto;
        }

        .profile-edit-content,
        .profile-edit-form {
            max-height: calc(100dvh - 1rem);
        }

        .profile-edit-header,
        .profile-edit-footer {
            padding: 0.75rem;
        }

        .profile-edit-body {
            padding: 0.85rem 0.75rem;
        }

        .profile-edit-footer .btn {
            flex: 1 1 auto;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('editProfileModal');
    const errorSummary = document.getElementById('profileErrorSummary');

    if (modalElement && errorSummary && window.bootstrap) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        modalElement.addEventListener('shown.bs.modal', function () {
            errorSummary.focus();
        }, { once: true });
        modal.show();
    }
});
</script>
@endsection
