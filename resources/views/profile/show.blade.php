@extends('base')

@section('title', 'Profil Utilisateur')

@section('content')
<div class="max-w-1xl mx-auto mt-0 px-0 relative">
    <!-- Header avec avatar et titre - Sticky amélioré -->
    <div class="sticky top-0.5 bg-white z-20 shadow-md rounded-t-xl mb-6 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center space-x-4">
                <!-- Avatar avec initiales -->
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                    {{ strtoupper(substr($user->prenom ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Profil de <span class="text-blue-600">{{ $user->prenom }} {{ $user->name }}</span>
                    </h1>
                    <p class="text-sm text-gray-500 flex items-center mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            @if($user->role === 'admin') bg-purple-100 text-purple-800
                            @elseif($user->role === 'gestionnaire') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800
                            @endif">
                            <i class="fas fa-user-tag mr-1"></i> {{ ucfirst($user->role ?? 'utilisateur') }}
                        </span>
                        <span class="ml-3 flex items-center text-gray-400">
                            <i class="fas fa-calendar-alt mr-1"></i> Membre depuis {{ $user->created_at?->format('d/m/Y') ?? 'N/A' }}
                        </span>
                    </p>
                </div>
            </div>
            <!-- Badge de statut -->
            <div class="hidden md:block">
                <span class="inline-flex items-center px-4 py-2 rounded-lg bg-green-50 text-green-700 border border-green-200">
                    <i class="fas fa-check-circle mr-2"></i> Compte actif
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 transform transition-all duration-500 animate-slideDown">
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg shadow-md flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6">
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg shadow-md">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <span class="font-semibold">Veuillez corriger les erreurs suivantes :</span>
                </div>
                <ul class="list-disc list-inside ml-6 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Contenu principal avec cartes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale - Formulaire -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                <!-- En-tête de carte -->
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200 flex items-center">
                    <i class="fas fa-user-edit text-blue-500 text-xl mr-3"></i>
                    <h2 class="text-lg font-semibold text-gray-800">Informations personnelles</h2>
                    <span class="ml-auto text-xs text-gray-500">
                        <i class="fas fa-lock-open text-green-500 mr-1"></i> Modifiable
                    </span>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="p-6" id="profileForm">
                    @csrf
                    @method('PUT')

                    <!-- Section identité -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4 flex items-center">
                            <i class="fas fa-id-card mr-2 text-blue-400"></i> Identité
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Prénom (nouveau champ) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-user mr-1 text-gray-400"></i> Prénom
                                </label>
                                @if(auth()->user()->role === 'admin')
                                    <input type="text" name="prenom" value="{{ old('prenom', $user->prenom ?? '') }}" 
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm">
                                @else
                                    <div class="relative group">
                                        <input type="text" name="prenom" value="{{ old('prenom', $user->prenom ?? '') }}" disabled
                                            class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-lg text-gray-600 cursor-not-allowed">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <div class="absolute bottom-full left-0 mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                                            <i class="fas fa-info-circle mr-1"></i> Contacter un administrateur pour modifier
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Nom -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-user mr-1 text-gray-400"></i> Nom
                                </label>
                                @if(auth()->user()->role === 'admin')
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm">
                                @else
                                    <div class="relative group">
                                        <input type="text" value="{{ $user->name }}" disabled
                                            class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50 rounded-lg text-gray-600 cursor-not-allowed">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <div class="absolute bottom-full left-0 mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2">
                                            ⚠️ Modification réservée aux administrateurs
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Section contact -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4 flex items-center">
                            <i class="fas fa-address-card mr-2 text-blue-400"></i> Contact
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-envelope mr-1 text-gray-400"></i> Email
                                </label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm"
                                    placeholder="exemple@email.com">
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-phone mr-1 text-gray-400"></i> Téléphone
                                </label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm"
                                    placeholder="01 23 45 67 89">
                            </div>
                        </div>
                    </div>

                    <!-- Section adresse -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-400"></i> Adresse
                        </h3>
                        <div class="space-y-4">
                            <!-- Adresse complète -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-road mr-1 text-gray-400"></i> Rue / Avenue
                                </label>
                                <input type="text" name="adresse" value="{{ old('adresse', $user->adresse) }}" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm"
                                    placeholder="Numéro et nom de rue">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Code Postal -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                                    <input type="text" name="code_postal" value="{{ old('code_postal', $user->code_postal) }}" 
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm"
                                        placeholder="75001">
                                </div>
                                
                                <!-- Ville -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                    <input type="text" name="ville" value="{{ old('ville', $user->ville) }}" 
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm"
                                        placeholder="Paris">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Barre d'actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-2 text-blue-400"></i>
                            Tous les champs sont modifiables sauf votre nom
                        </div>
                        <div class="flex space-x-3">
                            <button type="reset" 
                                class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium text-sm flex items-center">
                                <i class="fas fa-undo mr-2"></i> Réinitialiser
                            </button>
                            <button type="submit" 
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-medium text-sm flex items-center">
                                <i class="fas fa-save mr-2"></i> Mettre à jour
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Colonne latérale - Informations complémentaires -->
        <div class="lg:col-span-1">
            <!-- Carte sécurité -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100 mb-6">
                <div class="bg-gradient-to-r from-orange-50 to-yellow-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-orange-500 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Sécurité</h3>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-600">Dernière connexion</span>
                        <span class="text-sm font-semibold text-gray-800">
                            {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Première connexion' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-600">Compte créé le</span>
                        <span class="text-sm font-semibold text-gray-800">
                            {{ $user->created_at?->format('d/m/Y') ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="border-t border-gray-200 my-3"></div>
                    <a href="{{ route('password.request') }}" 
                        class="block w-full py-2.5 px-4 bg-gray-50 hover:bg-gray-100 text-blue-600 rounded-lg transition text-sm font-medium text-center">
                        <i class="fas fa-key mr-2"></i> Changer mon mot de passe
                    </a>
                </div>
            </div>

            <!-- Carte rôle et permissions -->
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-user-tag text-purple-500 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Rôle & Permissions</h3>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <span class="text-sm text-gray-600 w-24">Rôle actuel</span>
                        <span class="px-3 py-1.5 rounded-lg text-xs font-medium 
                            @if($user->role === 'admin') bg-purple-100 text-purple-800
                            @elseif($user->role === 'gestionnaire') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800
                            @endif">
                            <i class="fas fa-user-circle mr-1"></i> {{ ucfirst($user->role ?? 'utilisateur') }}
                        </span>
                    </div>
                    
                    @if($user->role !== 'admin')
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                            <div>
                                <p class="text-xs text-blue-700 font-medium">Vous ne pouvez pas modifier votre rôle</p>
                                <p class="text-xs text-blue-600 mt-1">
                                    Pour demander une évolution de vos droits, contactez l'équipe administrative.
                                </p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-crown text-purple-500 mr-2"></i>
                            <span class="text-xs text-purple-700 font-medium">Accès administrateur complet</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles personnalisés -->
<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slideDown {
        animation: slideDown 0.5s ease-out;
    }
    
    /* Amélioration du focus */
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Tooltip personnalisé */
    [data-tooltip] {
        position: relative;
        cursor: help;
    }
    
    [data-tooltip]:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 6px 10px;
        background: #1f2937;
        color: white;
        font-size: 0.75rem;
        border-radius: 6px;
        white-space: nowrap;
        display: none;
        z-index: 50;
    }
    
    [data-tooltip]:hover:before {
        display: block;
    }
    
   
</style>

<!-- Script pour animations et interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des inputs
    const inputs = document.querySelectorAll('#profileForm input:not([disabled])');
    const form = document.getElementById('profileForm');
    const resetBtn = document.querySelector('button[type="reset"]');

    inputs.forEach(input => {
        // Ajoute une icône de validation quand le champ est valide
        input.addEventListener('input', function() {
            if (this.checkValidity() && this.value.length > 0) {
                this.classList.add('border-green-500', 'bg-green-50');
                this.classList.remove('border-red-500', 'bg-red-50');
            } else if (this.value.length > 0) {
                this.classList.add('border-red-500', 'bg-red-50');
                this.classList.remove('border-green-500', 'bg-green-50');
            } else {
                this.classList.remove('border-red-500', 'bg-red-50', 'border-green-500', 'bg-green-50');
            }
        });

        // Validation à la perte du focus
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value) {
                this.classList.add('border-red-500', 'bg-red-50');
            }
        });
    });

    // Reset confirmation
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            if (!confirm('Voulez-vous vraiment annuler toutes vos modifications ?')) {
                e.preventDefault();
            }
        });
    }

    // Confirmation avant soumission
    form.addEventListener('submit', function(e) {
        const modified = Array.from(inputs).some(input => 
            input.value !== input.defaultValue && input.value !== ''
        );
        
        if (!modified) {
            e.preventDefault();
            alert('Aucune modification détectée.');
        }
    });

    // Formatage automatique du téléphone
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 2) value = value;
                else if (value.length <= 4) value = value.slice(0,2) + ' ' + value.slice(2);
                else if (value.length <= 6) value = value.slice(0,2) + ' ' + value.slice(2,4) + ' ' + value.slice(4);
                else if (value.length <= 8) value = value.slice(0,2) + ' ' + value.slice(2,4) + ' ' + value.slice(4,6) + ' ' + value.slice(6);
                else value = value.slice(0,2) + ' ' + value.slice(2,4) + ' ' + value.slice(4,6) + ' ' + value.slice(6,8) + ' ' + value.slice(8,10);
                this.value = value;
            }
        });
    }

    // Formatage automatique du code postal
    const cpInput = document.querySelector('input[name="code_postal"]');
    if (cpInput) {
        cpInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').slice(0,5);
        });
    }
});
</script>

<!-- Font Awesome si pas déjà inclus -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection