@extends("base")

@section("title", "Réinitialiser le mot de passe")

@section("content")

<div class="max-w-md mx-auto bg-white shadow p-6 rounded">
    <h2 class="text-2xl font-bold text-center mb-4">Nouveau mot de passe</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <label>Email</label>
        <input type="email" name="email" class="w-full mt-1 p-2 border rounded" required>
        @error('email') <p class="text-red-600">{{ $message }}</p> @enderror

        <label class="mt-3">Nouveau mot de passe</label>
        <input type="password" name="password" class="w-full mt-1 p-2 border rounded" required>

        <label class="mt-3">Confirmer</label>
        <input type="password" name="password_confirmation" class="w-full mt-1 p-2 border rounded" required>
        @error('password') <p class="text-red-600">{{ $message }}</p> @enderror

        <button class="w-full bg-blue-600 text-white py-2 mt-4 rounded">
            Réinitialiser
        </button>
    </form>
</div>

@endsection
