@extends('base')
@section('title', 'Modifier la structure')
@section('content')
@include('structures.form', [
    'structure' => $structure, 
    'action' => route('structures.update', $structure->id),
    'method' => 'PUT'
])
@endsection
