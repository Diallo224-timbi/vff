@extends("base")

@section('title',"S'inscrire")

@section('content')
<div class="">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <strong class="font-bold">Succès!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>   
        </div>   
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <strong class="font-bold">Erreur!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>   
        </div>   
    @endif
</div>

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-center">S'inscrire</h2>

    <!-- Étape 1 : Saisie email -->
    @if(!session('email_sent') && !session('code_verified'))
        <form action="{{ route('sendVerificationCode') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Votre email">
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Envoyer le code</button>
            </div>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </form>
    @endif

    <!-- Étape 2 : Saisie code -->
    @if(session('email_sent') && !session('code_verified'))
        <form action="{{ route('verifyCode') }}" method="POST" class="space-y-4 mt-4">
            @csrf
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Code de validation</label>
                <input type="text" name="code" id="code" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Entrez le code reçu par email">
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Valider le code</button>
            </div>
            @if(session('code_error'))
                <p class="text-red-500 text-sm mt-1">{{ session('code_error') }}</p>
            @endif
        </form>
    @endif

    <!-- Étape 3 : Formulaire complet -->
    @if(session('code_verified'))
        <form action="{{ route('registration.register') }}" method="POST" class="space-y-4 mt-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="name" id="name" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input type="text" name="phone" id="phone" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ session('email_to_verify') }}">
            </div>
            <div>
                <label for="confirmEmail" class="block text-sm font-medium text-gray-700">Confirmer votre email</label>
                <input type="email" name="confirmEmail" id="confirmEmail" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ session('email_to_verify') }}">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">S'inscrire</button>
            </div>
            <p class="text-center text-sm text-gray-600">
                Déjà un compte ? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>.
            </p>
        </form>
    @endif
</div>
@endsection
