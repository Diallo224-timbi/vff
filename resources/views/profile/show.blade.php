@extends('base')

@section('title', 'Profil Utilisateur')

@section('content')
<div class="max-w-3xl mx-auto mt-8 relative">
    <!-- Titre sticky -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center sticky top-16 bg-white z-10 shadow-md py-4">
        Profil de {{ $user->name }}
    </h1>

    <div class="bg-white shadow-lg rounded-xl p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6" id="profileForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nom (non modifiable) -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Nom</label>
                    <input type="text" value="{{ $user->name }}" disabled 
                        class="w-full border border-gray-300 rounded-lg p-3 bg-gray-100 cursor-not-allowed">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Téléphone</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <!-- Adresse -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Adresse</label>
                    <input type="text" name="adresse" value="{{ $user->adresse }}" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <!-- Ville -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Ville</label>
                    <input type="text" name="ville" value="{{ $user->ville }}" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>

                <!-- Code Postal -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Code Postal</label>
                    <input type="text" name="code_postal" value="{{ $user->code_postal }}" 
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                </div>
            </div>

            <!-- Bouton -->
            <div class="text-center mt-6">
                <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow transition duration-200">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script pour animation et mise en valeur des inputs -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('#profileForm input');

    inputs.forEach(input => {
        // Ajoute un effet au focus
        input.addEventListener('focus', () => {
            input.classList.add('ring-4', 'ring-blue-200', 'transition', 'duration-300');
        });
        input.addEventListener('blur', () => {
            input.classList.remove('ring-4', 'ring-blue-200', 'transition', 'duration-300');
        });
    });
});
</script>
@endsection
