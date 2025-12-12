@extends("base")

@section("title", "Mot de passe oublié")

@section("content")

<div class="max-w-md mx-auto bg-white shadow p-6 rounded">
    <h2 class="text-2xl font-bold text-center mb-4">Mot de passe oublié</h2>

    @if(session('success'))
        <p class="text-green-600">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email" class="w-full mt-1 p-2 border rounded" required>

        @error('email')
            <p class="text-red-600">{{ $message }}</p>
        @enderror

        <button class="w-full bg-blue-600 text-white py-2 mt-4 rounded">
            Envoyer le lien
        </button>
    </form>
</div>

@endsection
