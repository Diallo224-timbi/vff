@extends('base')
@section('title','Modifier un organisme')
@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Modifier un Organisme</h1>
    <form action="{{ route('organismes.update', $organisme->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de l'organisme</label>
            <input type="text" class="form-control" id="nom" name="nom_organisme" value="{{ $organisme->nom_organisme }}" required>
        </div>
        <div class="mb-3">
            <label for="signification" class="form-label">Description</label>
            <textarea class="form-control" id="signification" name="signification" rows="3" required>{{ $organisme->signification }}</textarea>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse" value="{{ $organisme->adresse }}" required>
        </div>
        <div class="mb-3">
            <label for="code_postal" class="form-label">Code postal</label>
            <input type="text" class="form-control" id="code_postal" name="code_postal" value="{{ $organisme->code_postal }}" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" value="{{ $organisme->ville }}" required>
        </div>
        <div class="mb-3">
            <label for="site_web" class="form-label">Site web</label>
            <input type="text" class="form-control" id="site_web" name="site_web" value="{{ $organisme->site_web }}">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('organismes.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection