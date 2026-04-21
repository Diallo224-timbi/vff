@extends('base')
@section('title','detail d\'un organisme')
@section('content')
<!-- Vue pour afficher les détails d'un organisme -->
<div class="container mt-4">
    <h1 class="mb-4">Détails de l'Organisme</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $organisme->nom_organisme }}</h5>
            <p class="card-text"><strong>Description:</strong> {{ $organisme->signification }}</p>
            <p class="card-text"><strong>Adresse:</strong> {{ $organisme->adresse }}</p>
            <p class="card-text"><strong>Code postal:</strong> {{ $organisme->code_postal }}</p>
            <p class="card-text"><strong>Ville:</strong> {{ $organisme->ville }}</p>
            @if($organisme->site_web)
            <p class="card-text"><strong>Site web:</strong> <a href="{{ $organisme->site_web }}" target="_blank">{{ $organisme->site_web }}</a></p>
            @endif
            <a href="{{ route('organismes.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </div>
@endsection