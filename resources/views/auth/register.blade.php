@extends('base')

@section('title', "Rejoindre la Plateforme Multi-Acteurs")

@section('content')
<div class="bg-gradient-to-br from-[#E3FCFF] to-[#B3D2D4] flex items-start justify-center p-3 font-sans min-h-screen" style="font-family: 'Montserrat', sans-serif;">

    <!-- Carte légèrement plus grande -->
    <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row border border-[#59BEC9]/20 mt-3 md:mt-6">

        <!-- Section image - taille augmentée -->
        <div class="md:w-1/5 bg-gradient-to-br from-[#255156] to-[#173235] relative hidden md:flex flex-col items-center justify-start p-4 overflow-hidden">
            <div class="absolute -top-10 -right-10 w-36 h-36 rounded-full bg-[#59BEC9] opacity-20"></div>
            <div class="relative z-10 text-center text-white w-full">
                <span class="text-lg font-bold tracking-tight block">PLATEFORME</span>
                <span class="text-sm font-light tracking-wider block">MULTI-ACTEURS</span>
                <div class="w-12 h-12 mx-auto my-3 rounded-full bg-white/20 flex items-center justify-center">
                    <span class="text-2xl">🤝</span>
                </div>
                <p class="text-xs font-light italic leading-tight">"Construisons<br>ensemble"</p>
            </div>
        </div>

        <!-- Section formulaire - taille augmentée -->
        <div class="md:w-4/5 p-4 md:p-3 bg-white">
            
            <!-- Mobile header - plus grand -->
            <div class="md:hidden mb-3 bg-gradient-to-r from-[#255156] to-[#2D6268] p-3 rounded-lg text-white text-center">
                <span class="text-base font-bold">PLATEFORME MULTI-ACTEURS</span>
            </div>

            <!-- En-tête - plus grand -->
            <div class="mb-3 border-b border-[#B3D2D4] pb-2">
                <h2 class="text-lg font-bold text-[#173235]">
                    @if(session('code_verified'))
                        Finaliser votre inscription
                    @else
                        S'inscrire
                    @endif
                </h2>
            </div>

            <!-- Messages feedback - plus grands -->
            @if (session('success') || session('error'))
                <div class="mb-3">
                    @if (session('success'))
                        <div class="bg-[#B3D2D4]/30 border-l-4 border-[#255156] text-[#173235] px-3 py-1.5 text-sm">
                            ✓{{ session('success') }}
                        </div>   
                    @endif
                    @if (session('error'))
                        <div class="bg-[#E3FCFF] border-l-4 border-[#41797F] text-[#173235] px-3 py-1.5 text-sm">
                            ⚠️ {{ session('error') }}
                        </div>   
                    @endif
                </div>
            @endif

            <!-- ÉTAPE 1 : Email - taille augmentée -->
            @if(!session('email_sent') && !session('code_verified'))
                <form action="{{ route('sendVerificationCode') }}" method="POST">
                    @csrf
                    <div class="flex items-end gap-3">
                        <div class="flex-1">
                            <input type="email" name="email" required
                                class="w-full border border-[#B3D2D4] rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-[#255156] focus:border-[#255156]"
                                placeholder="votre@email.fr">
                            @error('email')<p class="text-[#41797F] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="bg-[#255156] hover:bg-[#2D6268] text-white text-sm font-semibold py-2 px-4 rounded-lg whitespace-nowrap">
                            <i class='bx bx-send'></i> Envoyer code  
                        </button>
                    </div>
                </form>
            @endif

            <!-- ÉTAPE 2 -->
            @if(session('email_sent') && !session('code_verified'))
                <form action="{{ route('verifyCode') }}" method="POST">
                    @csrf
                    <div class="bg-[#E3FCFF] p-3 rounded-lg mb-4 border border-[#8EC0C6]">
                        <p class="text-[#173235] text-sm flex items-center gap-2">
                            <span><i class='bx bx-envelope'></i></span> Code envoyé à <span class="font-semibold" title="{{ substr(session('email_to_verify'), -200) }}">{{ substr(session('email_to_verify'), 0, 3) }}...{{ substr(session('email_to_verify'), -10) }}</span>
                        </p>
                    </div>
                    <div class="flex items-end gap-3">
                        <div class="flex-1">
                            <input type="text" name="code" required
                                class="w-full border border-[#B3D2D4] rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-[#255156]"
                                placeholder="Code 6 chiffres"
                                maxlength="6">
                        </div>
                        <button type="submit" class="bg-[#41797F] hover:bg-[#2D6268] text-white text-sm font-semibold py-2 px-4 rounded-lg">
                            ✓ Valider
                        </button>
                    </div>
                    @if(session('code_error'))
                        <p class="text-[#41797F] text-xs mt-2 bg-[#E3FCFF] p-2 rounded border border-[#8EC0C6]">
                            ⚠️ {{ session('code_error') }}
                        </p>
                    @endif
                </form>
            @endif

            <!-- ÉTAPE 3 : Finalisation inscription -->
            @if(session('code_verified'))

                <!-- Bloc global pour toutes les erreurs de validation -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Messages success/code_error -->
                @if(session('code_error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('code_error') }}
                    </div>
                @elseif(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('registration.register') }}" method="POST" id="registrationForm">
                    @csrf

                    <!-- Grille 3 colonnes -->
                    <div class="grid grid-cols-3 gap-2 mb-2">
                        <div>
                            <input type="text" name="name" placeholder="Nom" required
                                class="w-full border border-[#B3D2D4] rounded-lg p-2 text-sm focus:ring-1 focus:ring-[#255156]">
                            @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <input type="text" name="prenom" placeholder="Prénom" required
                                class="w-full border border-[#B3D2D4] rounded-lg p-2 text-sm focus:ring-1 focus:ring-[#255156]">
                            @error('prenom')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <input type="text" name="phone" placeholder="Téléphone" required
                                class="w-full border border-[#B3D2D4] rounded-lg p-2 text-sm focus:ring-1 focus:ring-[#255156]">
                            @error('phone')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Email et confirmation -->
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <input type="email" name="email" value="{{ session('email_to_verify') }}" readonly
                                class="w-full border border-[#B3D2D4] rounded-lg p-2 text-sm bg-[#E3FCFF]/30">
                        </div>
                        <div>
                            <input type="email" name="confirmEmail" placeholder="Confirmer email" required
                                class="w-full border border-[#B3D2D4] rounded-lg p-2 text-sm focus:ring-1 focus:ring-[#255156]">
                            @error('confirmEmail')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Mot de passe et adresse -->
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <input type="password" name="password" id="password" placeholder="Mot de passe" required
                                class="w-full border border-[#B3D2D4] rounded-lg p-2 text-sm focus:ring-1 focus:ring-[#255156]"
                                oninput="checkPasswordStrength()">
                            @error('password')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror

                            <div class="h-1.5 mt-1 rounded-full bg-[#E3FCFF] overflow-hidden">
                                <div id="password-strength" class="h-1.5 w-0 transition-all"></div>
                            </div>
                            <p id="password-text" class="text-xs mt-1"></p>
                        </div>
                        <div>
                            <input type="text" name="adresse" placeholder="Adresse" required
                                class="w-full border border-[#B3D2D4] rounded-lg p-2 text-sm focus:ring-1 focus:ring-[#255156]">
                            @error('adresse')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Ville et code postal -->
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <input type="text" name="ville" placeholder="Ville" required
                                class="w-full border border-[#B3D2D4] rounded-lg p-2 text-sm focus:ring-1 focus:ring-[#255156]">
                            @error('ville')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <input type="text" name="code_postal" placeholder="Code postal" required
                                class="w-full border border-[#B3D2D4] rounded-lg p-2 text-sm focus:ring-1 focus:ring-[#255156]">
                            @error('code_postal')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Responsable structure -->
                    <div class="bg-[#E3FCFF] p-3 rounded-lg border border-[#8EC0C6] mb-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-[#173235]">Responsable structure ?</span>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="is_responsable" value="1" class="responsable-radio w-4 h-4 text-[#255156]">
                                    <span class="text-sm text-[#173235]">Oui</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="is_responsable" value="0" class="responsable-radio w-4 h-4 text-[#255156]" checked>
                                    <span class="text-sm text-[#173235]">Non</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Sélection structure  -->
                    <div id="structureField" class="mb-2">
                        <!-- Sélection de l'organisme parent -->
                        <select name="id_organisme" id="editOrganisme" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-2">
                        <option value="">Aucun organisme</option>
                        @foreach($organismes as $organisme)
                            <option value="{{ $organisme->id }}" >
                                {{ $organisme->nom_organisme }} - {{ $organisme->ville }} -({{ $organisme->code_postal }})
                            </option>
                        @endforeach
                    </select>
                    <select name="id_structure" id="editStructure" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Aucune structure</option>
                        @foreach($structures as $structure)
                            <option value="{{ $structure->id}}" 
                                data-organisme-id="{{ $structure->id_organisme }}">
                                {{ $structure->organisme->nom_organisme ?? '-' }} - {{ $structure->ville }} ({{ $structure->code_postal }}) - {{ $structure->adresse }}
                            </option>
                        @endforeach
                    </select>
                        @error('id_structure')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Charte -->
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="chart" id="charte_accepted" class="w-4 h-4 text-[#255156] border-[#B3D2D4] rounded focus:ring-[#255156]" required value="1">
                            <label for="charte_accepted" class="text-sm text-[#173235]">
                                J'accepte la <a href="{{ route('charte') }}" class="text-[#255156] font-semibold hover:underline">charte</a>
                            </label>
                            @error('chart')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="bg-[#255156] hover:bg-[#2D6268] text-white font-semibold py-2 px-6 rounded-lg text-sm shadow">
                            <i class='bx bx-check-circle'></i> Finaliser
                        </button>
                    </div>

                    <!-- Lien login -->
                    <p class="text-center text-xs text-[#2D6268] mt-3 pt-2 border-t border-[#B3D2D4]/30">
                        Déjà membre ? <a href="{{ route('login') }}" class="text-[#255156] font-semibold hover:underline">Se connecter</a>
                    </p>
                </form>
            @endif
        </div>
    </div>
</div>
<!-- Script -->
@if(session('code_verified'))
<script>
    function checkPasswordStrength() {
        const pwd = document.getElementById('password').value;
        const bar = document.getElementById('password-strength');
        const txt = document.getElementById('password-text');
        let strength = 0;
        if (pwd.length >= 8) strength++;
        if (/[A-Z]/.test(pwd)) strength++;
        if (/[0-9]/.test(pwd)) strength++;
        if (/[\W_]/.test(pwd)) strength++;
        
        const config = {
            0: { w: '25%', c: 'bg-[#41797F]', t: 'Très faible' },
            1: { w: '25%', c: 'bg-[#41797F]', t: 'Très faible' },
            2: { w: '50%', c: 'bg-[#59BEC9]', t: 'Faible' },
            3: { w: '75%', c: 'bg-[#255156]', t: 'Bon' },
            4: { w: '100%', c: 'bg-[#173235]', t: 'Très bon' }
        };
        const cfg = config[strength] || config[0];
        bar.style.width = cfg.w;
        bar.className = `h-1.5 ${cfg.c} transition-all duration-300`;
        txt.textContent = cfg.t;
        txt.className = `text-xs ${cfg.c.replace('bg-', 'text-')} font-medium`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const radios = document.querySelectorAll('.responsable-radio');
        const structureField = document.getElementById('structureField');
        const toggle = () => {
            const selected = document.querySelector('.responsable-radio:checked');
            structureField.style.display = selected?.value === "1" ? 'none' : 'block';
        };
        radios.forEach(r => r.addEventListener('change', toggle));
        toggle();
        
        // Formatage téléphone
        const phoneInput = document.querySelector('input[name="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) value = value.slice(0, 10);
                const groups = value.match(/.{1,2}/g);
                if (groups) e.target.value = groups.join(' ');
            });
        }
    });

    // Validation du mot de passe avant soumission
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const pwd = document.getElementById('password').value;
        const errors = [];
        if (pwd.length < 8) errors.push('Le mot de passe doit contenir au moins 8 caractères.');
        if (!/[A-Z]/.test(pwd)) errors.push('Le mot de passe doit contenir au moins une lettre majuscule.');
        if (!/[0-9]/.test(pwd)) errors.push('Le mot de passe doit contenir au moins un chiffre.');
        if (!/[\W_]/.test(pwd)) errors.push('Le mot de passe doit contenir au moins un caractère spécial.');
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });
// Filtrage dynamique des structure rataché en fonction de leur organisme parent
    document.getElementById('editOrganisme').addEventListener('change', function() {
        const selectedOrganisme = this.value;
        const structureSelect = document.getElementById('editStructure');
        const options = structureSelect.querySelectorAll('option');
        options.forEach(option => {
            const orgId = option.getAttribute('data-organisme-id');
            if (!selectedOrganisme || orgId === selectedOrganisme) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
        structureSelect.value = '';
    });       
</script>
@endif

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection