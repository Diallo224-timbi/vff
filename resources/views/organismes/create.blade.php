@extends('base')
@section('title','Ajouter un organisme')
@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Ajouter un Organisme</h1>
    <form action="{{ route('organismes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de l'organisme</label>
            <input type="text" class="form-control" id="nom" name="nom_organisme" required>
        </div>
        <div class="mb-3">
            <label for="signification" class="form-label">Description</label>
            <textarea class="form-control" id="signification" name="signification" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse" required>
        </div>
        <div class="mb-3">
            <label for="code_postal" class="form-label">Code postal</label>
            <input type="text" class="form-control" id="code_postal" name="code_postal" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" required>
        </div>
        <div class="mb-3">
            <label for="site_web" class="form-label">Site web</label>
            <input type="text" class="form-control" id="site_web" name="site_web">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="{{ route('organismes.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection