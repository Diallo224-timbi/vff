@extends("base")

@section('title',"Se connecter")

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#f1f1f1] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border border-[#ffffff] transform transition-all duration-300 hover:shadow-2xl">
        
        <!-- Logo / titre -->
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-[#008C95]">
                Se connecter
            </h2>
            <p class="mt-2 text-sm text-[#2D2926]">
                Accédez à votre compte
            </p>
        </div>

        @if ($errors->any())
        <div class="bg-[#F03E3E]/20 border-l-4 border-[#F03E3E] p-4 rounded-lg animate-pulse" role="alert">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-[#F03E3E] mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium text-[#F03E3E]">{{ $errors->first() }}</span>
            </div>
        </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('login.submit') }}" method="POST">
            @csrf
            
            <div class="space-y-5">

                <!-- Email -->
                <div class="relative">
                    <label for="email" class="block text-sm font-medium text-[#2D2926] mb-1">
                        Adresse email
                    </label>
                    <div class="flex items-center border border-[#C4CEC2] rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-[#008C95] focus-within:border-[#008C95] transition">
                        <i class="bx bx-envelope text-[#C4CEC2] text-lg mr-2"></i>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               required 
                               value="{{ old('email') }}"
                               class="w-full outline-none text-[#2D2926]"
                               placeholder="votre@email.com">
                    </div>
                    @error('email')
                    <p class="mt-1 text-sm text-[#F03E3E] flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Email invalide
                    </p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-[#2D2926] mb-1">
                        Mot de passe
                    </label>
                    <div class="flex items-center border border-[#C4CEC2] rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-[#008C95] focus-within:border-[#008C95] transition">
                        <i class="bx bx-lock text-[#C4CEC2] text-lg mr-2"></i>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required
                               class="w-full outline-none text-[#2D2926]"
                               placeholder="Votre mot de passe">
                        <button type="button" 
                                onclick="togglePasswordVisibility()"
                                class="ml-2 text-[#C4CEC2] hover:text-[#008C95] transition">
                            <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="mt-1 text-sm text-[#F03E3E] flex items-center">
                        Mot de passe invalide
                    </p>
                    @enderror

                    <!-- Mot de passe oublié -->
                    <div class="mt-2 text-right">
                        <a href="{{ route('password.request') }}" 
                           class="text-sm text-[#008C95] hover:text-[#59BEC9] font-medium transition">
                            Mot de passe oublié ?
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bouton de connexion -->
            <div>
                <button type="submit" 
                        class="w-full py-3 px-4 rounded-lg text-white bg-gradient-to-r from-[#008C95] to-[#59BEC9] hover:from-[#05978A] hover:to-[#51BEC9] font-medium transition transform hover:scale-[1.02] active:scale-[0.98] shadow-md hover:shadow-lg">
                    Se connecter
                </button>
            </div>

            <!-- Lien d'inscription -->
            <div class="text-center pt-4 border-t border-[#C4CEC2]">
                <p class="text-sm text-[#2D2926]">
                    Pas encore de compte ? 
                    <a href="{{ route('register') }}" 
                       class="font-medium text-[#008C95] hover:text-[#59BEC9] transition">
                        Inscrivez-vous ici
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
}
</script>
@endsection
