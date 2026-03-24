@extends('base')
@section('title', 'Modifier la structure')
@section('content')
<!-- titre du formulaire -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Modifier la structure</h1>
    <a href="{{ route('structures.index') }}" class="btn btn-secondary">Retour à la liste</a>
</div>

@include('structures.form', [
    'structure' => $structure, 
    'action' => route('structures.update', $structure->id),
    'method' => 'PUT'
])
@endsection
