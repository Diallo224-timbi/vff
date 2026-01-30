@extends("base")

@section('title',"S'inscrire")

@section('content')
<div class="min-h-screen flex flex-col items-center justify-start bg-gray-50 py-12 px-4">

    <!-- Messages de feedback -->
    <div class="w-full max-w-3xl space-y-2 mb-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>   
            </div>   
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>   
            </div>   
        @endif
    </div>

    <!-- Form container -->
    <div class="w-full max-w-3xl bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-6">S'inscrire</h2>

        <!-- Étape 1 : Saisie email -->
        @if(!session('email_sent') && !session('code_verified'))
            <form action="{{ route('sendVerificationCode') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Votre email">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200">Envoyer le code</button>
            </form>
        @endif

        <!-- Étape 2 : Saisie code -->
        @if(session('email_sent') && !session('code_verified'))
            <form action="{{ route('verifyCode') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Code de validation</label>
                    <input type="text" name="code" id="code" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Entrez le code reçu par email">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200">Valider le code</button>
                @if(session('code_error'))
                    <p class="text-red-500 text-sm mt-1">{{ session('code_error') }}</p>
                @endif
            </form>
        @endif

        <!-- Étape 3 : Formulaire complet -->
        @if(session('code_verified'))
            <form action="{{ route('registration.register') }}" method="POST" class="space-y-6 mt-4" id="registrationForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" name="name" id="name" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <label for="prenom" class="block text-sm font-medium text-gray-700 mt-4">Prénom</label>
                        <input type="text" name="prenom" id="prenom" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500">
                        @error('prenom')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                        <input type="text" name="phone" id="phone" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ session('email_to_verify') }}">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmer Email -->
                    <div>
                        <label for="confirmEmail" class="block text-sm font-medium text-gray-700">Confirmer votre email</label>
                        <input type="email" name="confirmEmail" id="confirmEmail" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ session('email_to_verify') }}">
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500"
                            oninput="checkPasswordStrength()">
                        <div class="h-2 mt-1 rounded bg-gray-200 overflow-hidden">
                            <div id="password-strength" class="h-2 w-0 bg-red-500 transition-all"></div>
                        </div>
                        <p id="password-text" class="text-sm mt-1"></p>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <input type="text" name="adresse" id="adresse" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500">
                        @error('adresse')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ville -->
                    <div>
                        <label for="ville" class="block text-sm font-medium text-gray-700">Ville</label>
                        <input type="text" name="ville" id="ville" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500">
                        @error('ville')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code postal -->
                    <div>
                        <label for="code_postal" class="block text-sm font-medium text-gray-700">Code postal</label>
                        <input type="text" name="code_postal" id="code_postal" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500">
                        @error('code_postal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
               <!-- Responsable ? -->
                <div class="mt-4">
                    <p class="block text-sm font-medium text-gray-700">Souhaitez-vous devenir responsable d'une structure ?</p>
                    <div class="flex items-center space-x-4 mt-2">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="is_responsable" value="1" class="responsable-radio" required>
                            <span>Oui</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="is_responsable" value="0" class="responsable-radio" required>
                            <span>Non</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        En répondant "Oui", vous vous engagez à être responsable d'une structure. Sinon, l’administrateur pourra bloquer votre compte et merci d’envoyer votre demande d’adhésion par mail.
                    </p>
                    @error('is_responsable')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Structure -->
                <div class="mt-4" id="structureField">
                    <label for="id_structure" class="block text-sm font-medium text-gray-700">Structure</label>
                    <select name="id_structure" id="id_structure" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Sélectionnez votre structure --</option>
                        @foreach($structures as $structure)
                            <option value="{{ $structure->id }}">{{ $structure->nom_structure }}</option>
                        @endforeach
                    </select>
                    @error('id_structure')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Chart opt-in -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" name="chart" id="charte_accepted" class="h-4 w-4 text-blue-600 border-gray-300 rounded" required value="1">
                    <label for="charte_accepted" class="text-sm text-gray-700">
                        J'accepte la <a href="{{ route('charte') }}" class="text-blue-600 underline">charte</a>
                    </label>
                </div>
                @error('charte_accepted')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Bouton -->
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                        S'inscrire
                    </button>
                </div>

                <p class="text-center text-sm text-gray-600 mt-2">
                    Déjà un compte ? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>.
                </p>
            </form>
        @endif
    </div>
</div>

<!-- Script pour la force du mot de passe -->
@if(session('code_verified'))
<script>
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('password-strength');
    const strengthText = document.getElementById('password-text');

    let strength = 0;
    if (password.length >= 8) strength += 1;
    if (/[A-Z]/.test(password)) strength += 1;
    if (/[0-9]/.test(password)) strength += 1;
    if (/[\W]/.test(password)) strength += 1;

    switch(strength) {
        case 0:
        case 1:
            strengthBar.style.width = '25%';
            strengthBar.className = 'h-2 bg-red-500 transition-all';
            strengthText.textContent = 'Très faible';
            strengthText.className = 'text-red-500 text-sm';
            break;
        case 2:
            strengthBar.style.width = '50%';
            strengthBar.className = 'h-2 bg-yellow-400 transition-all';
            strengthText.textContent = 'Faible';
            strengthText.className = 'text-yellow-500 text-sm';
            break;
        case 3:
            strengthBar.style.width = '75%';
            strengthBar.className = 'h-2 bg-blue-400 transition-all';
            strengthText.textContent = 'Bonne';
            strengthText.className = 'text-blue-500 text-sm';
            break;
        case 4:
            strengthBar.style.width = '100%';
            strengthBar.className = 'h-2 bg-green-500 transition-all';
            strengthText.textContent = 'Très bonne';
            strengthText.className = 'text-green-500 text-sm';
            break;
    }
}
// Script pour afficher/masquer le champ structure
    document.addEventListener('DOMContentLoaded', () => {
        const radios = document.querySelectorAll('.responsable-radio');
        const structureField = document.getElementById('structureField');

        const toggleStructureField = () => {
            const selected = document.querySelector('.responsable-radio:checked');
            if (selected && selected.value === "1") {
                // Si oui → masquer le champ structure
                structureField.style.display = 'none';
            } else {
                // Si non → afficher le champ structure
                structureField.style.display = 'block';
            }
        };

        // Au changement
        radios.forEach(radio => radio.addEventListener('change', toggleStructureField));

        // Initialisation
        toggleStructureField();
    });


</script>
@endif
@endsection
