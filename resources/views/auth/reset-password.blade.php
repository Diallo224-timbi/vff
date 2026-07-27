@extends("base")

@section("title", "Réinitialiser le mot de passe")

@section("content")

<div class="min-h-[calc(100vh-200px)] flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
        
        <!-- Carte principale -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10 border border-gray-100">
            
            <!-- Icône et titre -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto rounded-full bg-[#4fd1d9]/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#255156]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Nouveau mot de passe
                </h2>
                <p class="text-gray-500 text-sm mt-1">
                    Choisissez un mot de passe sécurisé
                </p>
            </div>

            <!-- Messages d'erreur généraux -->
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Formulaire -->
            <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Adresse email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#4fd1d9]/50 focus:border-[#4fd1d9] transition-all duration-200"
                            placeholder="exemple@email.fr"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Nouveau mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nouveau mot de passe
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#4fd1d9]/50 focus:border-[#4fd1d9] transition-all duration-200"
                            placeholder="••••••••"
                            required
                            minlength="8"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                            aria-label="Afficher/Masquer le mot de passe"
                        >
                            <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Indicateur de force -->
                    <div class="mt-2">
                        <div class="flex gap-1 h-1.5">
                            <div class="flex-1 rounded-full bg-gray-200" id="strengthBar1"></div>
                            <div class="flex-1 rounded-full bg-gray-200" id="strengthBar2"></div>
                            <div class="flex-1 rounded-full bg-gray-200" id="strengthBar3"></div>
                            <div class="flex-1 rounded-full bg-gray-200" id="strengthBar4"></div>
                            <div class="flex-1 rounded-full bg-gray-200" id="strengthBar5"></div>
                        </div>
                        <p id="passwordStrength" class="mt-1 text-sm text-gray-500"></p>
                    </div>

                    <!-- Liste des critères -->
                    <ul class="mt-2 space-y-1 text-xs" id="criteriaList">
                        <li id="criteriaLength" class="text-gray-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Minimum 8 caractères
                        </li>
                        <li id="criteriaLower" class="text-gray-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Une minuscule
                        </li>
                        <li id="criteriaUpper" class="text-gray-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Une majuscule
                        </li>
                        <li id="criteriaNumber" class="text-gray-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Un chiffre
                        </li>
                        <li id="criteriaSpecial" class="text-gray-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Un caractère spécial
                        </li>
                    </ul>
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Confirmer le mot de passe
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#4fd1d9]/50 focus:border-[#4fd1d9] transition-all duration-200"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                    <p id="confirmMatch" class="mt-1.5 text-sm"></p>
                </div>

                <!-- Bouton -->
                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-[#4fd1d9] to-[#255156] hover:from-[#5dd9e0] hover:to-[#2d6a6f] text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg hover:shadow-[#4fd1d9]/25 flex items-center justify-center gap-2 mt-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Réinitialiser le mot de passe
                </button>
            </form>

            <!-- Lien de retour -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-[#255156] transition-colors duration-200 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à la connexion
                </a>
            </div>

        </div>

        <!-- Footer -->
        <p class="text-center text-gray-400 text-xs mt-6">
            Plateforme Multi-Acteurs VFF 06 · Alpes-Maritimes
        </p>
    </div>
</div>

<script>
    // ============================================================
    // INDICATEUR DE FORCE DU MOT DE PASSE
    // ============================================================
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthText = document.getElementById('passwordStrength');
    const confirmMatch = document.getElementById('confirmMatch');

    // Critères
    const criteria = {
        length: document.getElementById('criteriaLength'),
        lower: document.getElementById('criteriaLower'),
        upper: document.getElementById('criteriaUpper'),
        number: document.getElementById('criteriaNumber'),
        special: document.getElementById('criteriaSpecial')
    };

    const strengthBars = [
        document.getElementById('strengthBar1'),
        document.getElementById('strengthBar2'),
        document.getElementById('strengthBar3'),
        document.getElementById('strengthBar4'),
        document.getElementById('strengthBar5')
    ];

    function updateCriteria(password) {
        const checks = {
            length: password.length >= 8,
            lower: /[a-z]/.test(password),
            upper: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };

        // Mettre à jour chaque critère
        for (const [key, isValid] of Object.entries(checks)) {
            const el = criteria[key];
            if (isValid) {
                el.className = 'text-green-600 flex items-center gap-1.5';
                el.innerHTML = `
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    ${el.textContent.trim()}
                `;
            } else {
                el.className = 'text-gray-400 flex items-center gap-1.5';
                el.innerHTML = `
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ${el.textContent.trim()}
                `;
            }
        }

        // Compter le nombre de critères validés
        const validCount = Object.values(checks).filter(Boolean).length;

        // Mettre à jour les barres
        strengthBars.forEach((bar, index) => {
            if (index < validCount) {
                if (validCount <= 2) {
                    bar.className = 'flex-1 rounded-full bg-red-500';
                } else if (validCount <= 3) {
                    bar.className = 'flex-1 rounded-full bg-yellow-500';
                } else {
                    bar.className = 'flex-1 rounded-full bg-green-500';
                }
            } else {
                bar.className = 'flex-1 rounded-full bg-gray-200';
            }
        });

        // Texte de force
        let text = '';
        let color = 'text-gray-500';
        if (validCount === 0 || validCount === 1) {
            text = 'Très faible';
            color = 'text-red-500';
        } else if (validCount === 2) {
            text = 'Faible';
            color = 'text-red-500';
        } else if (validCount === 3) {
            text = 'Moyen';
            color = 'text-yellow-600';
        } else if (validCount === 4) {
            text = 'Fort';
            color = 'text-green-600';
        } else {
            text = 'Très fort';
            color = 'text-green-600';
        }
        strengthText.className = `mt-1 text-sm ${color}`;
        strengthText.textContent = text;

        return validCount;
    }

    // Événement sur le champ mot de passe
    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        updateCriteria(password);
        checkConfirmation();
    });

    // Vérification de la confirmation
    function checkConfirmation() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (confirm.length === 0) {
            confirmMatch.textContent = '';
            confirmMatch.className = 'mt-1.5 text-sm';
            return;
        }

        if (password === confirm) {
            confirmMatch.textContent = '✅ Les mots de passe correspondent';
            confirmMatch.className = 'mt-1.5 text-sm text-green-600';
        } else {
            confirmMatch.textContent = '❌ Les mots de passe ne correspondent pas';
            confirmMatch.className = 'mt-1.5 text-sm text-red-600';
        }
    }

    confirmInput.addEventListener('input', checkConfirmation);

    // ============================================================
    // AFFICHER / MASQUER LE MOT DE PASSE
    // ============================================================
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            `;
        } else {
            passwordField.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
        }
    }

    // ============================================================
    // VALIDATION AVANT SOUMISSION
    // ============================================================
    const form = document.getElementById('resetPasswordForm');

    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const validCount = updateCriteria(password);

        if (validCount < 5) {
            e.preventDefault();
            alert('Veuillez respecter tous les critères de sécurité pour le mot de passe.');
            return;
        }

        if (password !== confirmInput.value) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            return;
        }
    });
</script>

@endsection