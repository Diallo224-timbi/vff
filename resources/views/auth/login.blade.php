@extends('base')

@section('title', 'Se connecter | Plateforme Multi-Acteurs')

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row bg-gradient-to-br from-[#E3FCFF] to-[#B3D2D4] font-sans" style="font-family: 'Montserrat', sans-serif;">

    <!-- PARTIE HAUTE : FORMULAIRE (mobile) / GAUCHE (desktop) -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 py-6 lg:py-0">
        
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 sm:p-8 border border-[#59BEC9]/20 my-4 lg:my-0">
            
            <!-- Logo / Titre charté -->
            <div class="text-center mb-4 sm:mb-6">
                <span class="text-xl sm:text-2xl font-bold tracking-tight text-[#173235] block">PLATEFORME</span>
                <span class="text-lg sm:text-xl font-light tracking-wider text-[#2D6268] block">MULTI-ACTEURS</span>
                <div class="w-12 h-0.5 bg-[#59BEC9] mx-auto mt-2 sm:mt-3"></div>
            </div>

            <h2 class="text-lg sm:text-xl font-semibold text-center text-[#173235] mb-4 sm:mb-6">
                Se connecter
            </h2>

            @if ($errors->any())
                <div class="mb-4 sm:mb-5 bg-[#E3FCFF] border-l-4 border-[#41797F] text-[#173235] px-3 sm:px-4 py-2 sm:py-3 rounded-r-lg text-xs sm:text-sm flex items-start">
                    <span class="mr-2 text-[#41797F]">⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4 sm:space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-[#173235] uppercase tracking-wider mb-1">
                        Adresse e-mail
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-[#59BEC9] text-sm"><i class='bx bx-envelope'></i></span>
                        </div>
                        <input type="email"
                               name="email"
                               required
                               value="{{ old('email') }}"
                               class="w-full pl-10 pr-4 py-2 sm:py-2.5 rounded-lg border-2 border-[#B3D2D4] focus:border-[#008C95] focus:ring-2 focus:ring-[#008C95]/20 text-xs sm:text-sm text-[#173235] placeholder-[#8EC0C6] bg-white transition"
                               placeholder="votre@email.fr">
                    </div>
                </div>

                <!-- Mot de passe -->
                <div>
                    <label class="block text-xs font-semibold text-[#173235] uppercase tracking-wider mb-1">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-[#59BEC9] text-sm"><i class='bx bx-lock'></i></span>
                        </div>
                        <input type="password"
                               name="password"
                               id="password"
                               required
                               class="w-full pl-10 pr-12 py-2 sm:py-2.5 rounded-lg border-2 border-[#B3D2D4] focus:border-[#008C95] focus:ring-2 focus:ring-[#008C95]/20 text-xs sm:text-sm text-[#173235] placeholder-[#8EC0C6] bg-white transition"
                               placeholder="••••••••">
                        <button type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-2 sm:top-2.5 text-[#59BEC9] hover:text-[#008C95] transition">
                            <span class="text-sm">👁️</span>
                        </button>
                    </div>

                    <div class="mt-1 sm:mt-2 text-right">
                        <a href="{{ route('password.request') }}"
                           class="text-xs text-[#41797F] hover:text-[#008C95] hover:underline font-medium">
                            Mot de passe oublié ?
                        </a>
                    </div>
                </div>

                <!-- Checkbox "Rester connecté" -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="remember" 
                           id="remember"
                           class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#008C95] border-2 border-[#B3D2D4] rounded focus:ring-[#008C95] focus:ring-2">
                    <label for="remember" class="ml-2 text-xs text-[#2D6268] font-medium">
                        Rester connecté
                    </label>
                </div>

                <!-- Bouton de connexion -->
                <button type="submit"
                        class="w-full py-2 sm:py-2.5 rounded-lg bg-[#008C95] hover:bg-[#2D6268] text-white font-semibold text-xs sm:text-sm transition-all duration-300 transform hover:scale-[1.02] shadow-md hover:shadow-xl flex items-center justify-center space-x-2 mt-2">
                    <span><i class='bx bx-log-in'></i></span>
                    <span>Se connecter</span>
                </button>

                <!-- Lien inscription -->
                <div class="text-center text-xs text-[#2D6268] pt-3 sm:pt-4 border-t border-[#B3D2D4] mt-3 sm:mt-4">
                    Vous découvrez la Plateforme ?
                    <a href="{{ route('register') }}"
                       class="text-[#008C95] font-bold hover:underline ml-1">
                        Créer un compte
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- PARTIE BASSE : IMAGE (mobile) / DROITE (desktop) -->
    <div class="w-full lg:w-1/2 relative bg-gradient-to-br from-[#008C95] to-[#173235] overflow-hidden min-h-[250px] sm:min-h-[300px] lg:min-h-0 flex items-center justify-center">
        
        <!-- Éléments graphiques charte (cercles) -->
        <div class="absolute -top-20 -right-20 w-48 sm:w-64 h-48 sm:h-64 rounded-full bg-[#59BEC9] opacity-20"></div>
        <div class="absolute -bottom-20 -left-20 w-64 sm:w-80 h-64 sm:h-80 rounded-full bg-[#2D6268] opacity-20"></div>
        <div class="absolute inset-0 opacity-10" 
             style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 12px 12px sm:15px 15px;">
        </div>
        
        <!-- Image en arrière-plan avec overlay -->
        <img src="{{ asset('img/1photo.png') }}" 
             alt="Image connexion"
             class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
        
        <!-- Contenu texte par-dessus l'image -->
        <div class="relative z-10 text-center text-white px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-0">
            <!-- Logo version blanche -->
            <div class="mb-3 sm:mb-4 lg:mb-6">
                <span class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight block drop-shadow-lg">PLATEFORME</span>
                <span class="text-lg sm:text-xl lg:text-2xl font-light tracking-wider block drop-shadow-lg">MULTI-ACTEURS</span>
            </div>
            
            <!-- Message inspirant -->
            <div class="my-3 sm:my-4 lg:my-8">
                <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 mx-auto rounded-full bg-white/20 flex items-center justify-center mb-2 sm:mb-3 lg:mb-4 backdrop-blur-sm">
                    <span class="text-2xl sm:text-3xl lg:text-4xl">🤝</span>
                </div>
                <p class="text-base sm:text-lg lg:text-xl font-light italic">"Construisons ensemble</p>
                <p class="text-lg sm:text-xl lg:text-2xl font-semibold mt-1">une société inclusive</p>
                <p class="text-base sm:text-lg lg:text-xl font-light italic mt-1">et collaborative"</p>
            </div>
            
            <!-- Pictogrammes charte -->
           
        </div>
    </div>
</div>

<!-- Script pour toggle password -->
<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}

// Gestion responsive du scroll
document.addEventListener('DOMContentLoaded', function() {
    function handleResponsive() {
        if (window.innerWidth < 1024) {
            document.body.style.overflow = 'auto';
            document.body.style.height = 'auto';
        } else {
            document.body.style.overflow = 'hidden';
            document.body.style.height = '100vh';
        }
    }
    
    // Initialisation
    handleResponsive();
    
    // Écouteur de redimensionnement
    window.addEventListener('resize', handleResponsive);
});
</script>

<!-- Ajout police Montserrat -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Couleurs charte PMAVFF */
    .bg-charte-1 { background-color: #59BEC9; }
    .bg-charte-2 { background-color: #008C95; }
    .bg-charte-3 { background-color: #173235; }
    .bg-charte-4 { background-color: #2D6268; }
    .bg-charte-5 { background-color: #41797F; }
    .bg-charte-6 { background-color: #8EC0C6; }
    .bg-charte-7 { background-color: #B3D2D4; }
    .bg-charte-8 { background-color: #E3FCFF; }
    .text-charte-1 { color: #59BEC9; }
    .text-charte-2 { color: #008C95; }
    .text-charte-3 { color: #173235; }
    .border-charte-2 { border-color: #008C95; }
    .border-charte-7 { border-color: #B3D2D4; }
    
    /* Reset et responsive */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Montserrat', sans-serif;
        overflow-x: hidden;
    }
    
    /* Mobile first */
    .min-h-screen {
        min-height: 100vh;
        height: auto;
    }
    
    /* Desktop */
    @media (min-width: 1024px) {
        .min-h-screen {
            height: 100vh;
            min-height: 0;
        }
    }
    
    /* Animations douces */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Focus visible */
    input:focus, button:focus {
        outline: 2px solid transparent;
        outline-offset: 2px;
    }
    
    /* Placeholder */
    ::placeholder {
        color: #8EC0C6;
        font-size: 0.75rem;
    }
    
    @media (min-width: 640px) {
        ::placeholder {
            font-size: 0.875rem;
        }
    }
</style>

<!-- Fallback Tailwind CDN si nécessaire -->
<script src="https://cdn.tailwindcss.com"></script>
@endsection