@extends("base")

@section("title", "Réinitialiser le mot de passe")

@section("content")

<div class="max-w-md mx-auto bg-white shadow p-6 rounded">
    <h2 class="text-2xl font-bold text-center mb-4">Nouveau mot de passe</h2>

    <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <label>Email</label>
        <input type="email" name="email" class="w-full mt-1 p-2 border rounded" required>
        @error('email') <p class="text-red-600">{{ $message }}</p> @enderror

        <label class="mt-3">Nouveau mot de passe</label>
        <input type="password" name="password" id="password" class="w-full mt-1 p-2 border rounded" required>

        <label class="mt-3">Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation" class="w-full mt-1 p-2 border rounded" required>
        @error('password') <p class="text-red-600">{{ $message }}</p> @enderror

        <!-- Indicateur de force -->
        <p id="passwordStrength" class="mt-1 text-sm"></p>

        <button type="submit" class="w-full bg-[#255156] text-white py-2 mt-4 rounded">
            Réinitialiser
        </button>
    </form>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const strengthText = document.getElementById('passwordStrength');
    const form = document.getElementById('resetPasswordForm');

    // Fonction pour vérifier la force du mot de passe
    function checkPasswordStrength(password) {
        const regexLower = /[a-z]/;
        const regexUpper = /[A-Z]/;
        const regexNumber = /[0-9]/;
        const regexSpecial = /[!@#$%^&*(),.?":{}|<>]/;
        let strength = 0;

        if (regexLower.test(password)) strength++;
        if (regexUpper.test(password)) strength++;
        if (regexNumber.test(password)) strength++;
        if (regexSpecial.test(password)) strength++;
        if (password.length >= 8) strength++;

        return strength;
    }

    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const strength = checkPasswordStrength(password);

        // Changer couleur bordure
        if (strength < 4) {
            passwordInput.classList.add('border-red-500');
            passwordInput.classList.remove('border-yellow-500', 'border-green-500');
        } else if (strength === 4) {
            passwordInput.classList.add('border-yellow-500');
            passwordInput.classList.remove('border-red-500', 'border-green-500');
        } else {
            passwordInput.classList.add('border-green-500');
            passwordInput.classList.remove('border-red-500', 'border-yellow-500');
        }

        // Texte indicateur
        let text = '';
        if (strength < 4) text = 'Mot de passe faible';
        else if (strength === 4) text = 'Mot de passe moyen';
        else text = 'Mot de passe fort';

        strengthText.textContent = text;
    });

    // Validation avant soumission
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const regexLower = /[a-z]/;
        const regexUpper = /[A-Z]/;
        const regexNumber = /[0-9]/;
        const regexSpecial = /[!@#$%^&*(),.?":{}|<>]/;

        if (
            password.length < 8 ||
            !regexLower.test(password) ||
            !regexUpper.test(password) ||
            !regexNumber.test(password) ||
            !regexSpecial.test(password)
        ) {
            e.preventDefault();
            alert('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.');
        }
    });
</script>

@endsection