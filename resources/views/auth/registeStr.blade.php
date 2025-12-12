@extends('base')

@section('title', 'Création de Compte')

@section('content')
<div class="container mx-auto py-10 px-4 max-w-3xl">

    <!-- Card principale -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">

        <!-- Header -->
        <div class="bg-blue-600 text-white text-center py-4">
            <h1 class="text-2xl font-semibold flex items-center justify-center space-x-2">
                <i class='bx bx-user-plus'></i>
                <span>Création de Compte</span>
            </h1>
        </div>

        <!-- Barre de progression -->
        <div class="px-6 py-4">
            <div class="w-full bg-gray-200 h-2 rounded-full mb-4 overflow-hidden">
                <div id="progressBar" class="h-2 rounded-full w-1/4 transition-all"
                     style="background: linear-gradient(90deg, #3b82f6, #60a5fa); box-shadow: 0 0 8px #3b82f6;"></div>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
                <span class="step font-semibold">Infos Personnelles</span>
                <span class="step">Coordonnées</span>
                <span class="step">Sécurité</span>
                <span class="step">Confirmation</span>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="px-6 py-6">
            <form id="accountForm" method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Étape 1 : Infos Personnelles -->
                <div class="step-content" id="step1">
                    <h3 class="text-lg font-semibold mb-4 flex items-center space-x-2">
                        <i class='bx bx-user text-blue-600'></i>
                        <span>Informations Personnelles</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="firstName" class="block text-sm font-medium mb-1">Prénom *</label>
                            <input type="text" id="firstName" name="firstName" required class="w-full px-3 py-2 border rounded-md">
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium mb-1">Nom *</label>
                            <input type="text" id="lastName" name="lastName" required class="w-full px-3 py-2 border rounded-md">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="birthDate" class="block text-sm font-medium mb-1">Date de naissance *</label>
                        <input type="date" id="birthDate" name="birthDate" required class="w-full px-3 py-2 border rounded-md">
                    </div>

                    <div class="mt-4">
                        <label for="gender" class="block text-sm font-medium mb-1">Genre *</label>
                        <select id="gender" name="gender" required class="w-full px-3 py-2 border rounded-md">
                            <option value="">Sélectionnez...</option>
                            <option value="male">Masculin</option>
                            <option value="female">Féminin</option>
                            <option value="other">Autre</option>
                            <option value="prefer-not-to-say">Je préfère ne pas répondre</option>
                        </select>
                    </div>
                </div>

                <!-- Étape 2 : Coordonnées -->
                <div class="step-content hidden" id="step2">
                    <h3 class="text-lg font-semibold mb-4 flex items-center space-x-2">
                        <i class='bx bx-envelope text-blue-600'></i>
                        <span>Coordonnées</span>
                    </h3>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium mb-1">Email *</label>
                        <input type="email" id="email" name="email" required class="w-full px-3 py-2 border rounded-md">
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium mb-1">Téléphone *</label>
                        <input type="tel" id="phone" name="phone" required class="w-full px-3 py-2 border rounded-md">
                    </div>
                </div>

                <!-- Étape 3 : Sécurité -->
                <div class="step-content hidden" id="step3">
                    <h3 class="text-lg font-semibold mb-4 flex items-center space-x-2">
                        <i class='bx bx-lock text-blue-600'></i>
                        <span>Sécurité du compte</span>
                    </h3>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium mb-1">Mot de passe *</label>
                        <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded-md">
                        <div class="mt-1 h-2 rounded-full bg-gray-200 overflow-hidden">
                            <div id="passwordStrength" class="h-2 w-0 rounded-full transition-all"></div>
                        </div>
                        <p id="passwordText" class="text-sm mt-1 font-medium"></p>
                    </div>

                    <div class="mb-4">
                        <label for="confirmPassword" class="block text-sm font-medium mb-1">Confirmer le mot de passe *</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required class="w-full px-3 py-2 border rounded-md">
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="terms" name="terms" required class="mr-2">
                            <span>J'accepte la <a href="#" class="text-blue-600 underline">charte d'utilisation</a> *</span>
                        </label>
                    </div>
                </div>

                <!-- Étape 4 : Confirmation / Synthèse -->
                <div class="step-content hidden text-center" id="step4">
                    <h3 class="text-lg font-semibold mb-4 flex items-center justify-center space-x-2">
                        <i class='bx bx-check-circle text-green-600'></i>
                        <span>Confirmation</span>
                    </h3>
                    <p class="text-gray-600 mb-4">Vérifiez vos informations avant de soumettre.</p>
                    <div id="summary" class="text-left bg-gray-100 p-4 rounded-md mb-4"></div>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Créer le compte</button>
                </div>

                <!-- Navigation étapes -->
                <div class="flex justify-between mt-6">
                    <button type="button" id="prevBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400" disabled>Précédent</button>
                    <button type="button" id="nextBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Suivant</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const steps = document.querySelectorAll('.step-content');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const progressBar = document.getElementById('progressBar');
    const summaryDiv = document.getElementById('summary');
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordText = document.getElementById('passwordText');
    const totalSteps = steps.length;
    let currentStep = 0;

    function showStep(index) {
        steps.forEach((s,i) => s.classList.toggle('hidden', i!==index));
        prevBtn.disabled = index===0;
        nextBtn.classList.toggle('hidden', index===totalSteps-1);

        // Progression barre
        const percent = ((index) / (totalSteps-1))*100;
        progressBar.style.width = percent + '%';

        // Générer synthèse si dernière étape
        if(index === totalSteps-1){
            generateSummary();
        }
    }

    function validateStep(stepIndex){
        const step = steps[stepIndex];
        const inputs = step.querySelectorAll('input, select');
        for(const input of inputs){
            if(input.hasAttribute('required') && !input.value.trim()){
                alert(`Veuillez remplir le champ "${input.name}" avant de continuer`);
                return false;
            }
            if(input.type === 'checkbox' && input.hasAttribute('required') && !input.checked){
                alert('Vous devez accepter la charte pour continuer.');
                return false;
            }
        }
        return true;
    }

    function generateSummary(){
        const fields = [
            {label:"Prénom", id:"firstName"},
            {label:"Nom", id:"lastName"},
            {label:"Date de naissance", id:"birthDate"},
            {label:"Genre", id:"gender"},
            {label:"Email", id:"email"},
            {label:"Téléphone", id:"phone"}
        ];
        let html="";
        fields.forEach(f=>{
            const el=document.getElementById(f.id);
            let val = el.value;
            if(f.id==="birthDate") val = val ? new Date(val).toLocaleDateString('fr-FR') : '';
            html+=`<p><strong>${f.label}:</strong> ${val}</p>`;
        });
        summaryDiv.innerHTML = html;
    }

    function checkPasswordStrength(){
        const val = passwordInput.value;
        let strength = 0;
        if(val.length >= 6) strength++;
        if(/[A-Z]/.test(val)) strength++;
        if(/[0-9]/.test(val)) strength++;
        if(/[\W]/.test(val)) strength++;
        
        let color = 'red';
        let text = 'Faible';
        let width = (strength/4)*100 + '%';
        if(strength === 2) { color='orange'; text='Moyen'; }
        if(strength >=3) { color='green'; text='Fort'; }

        passwordStrength.style.width = width;
        passwordStrength.style.backgroundColor = color;
        passwordText.textContent = text;
        passwordText.style.color = color;
    }

    passwordInput.addEventListener('input', checkPasswordStrength);

    nextBtn.addEventListener('click', ()=>{
        if(validateStep(currentStep)){
            if(currentStep<totalSteps-1){
                currentStep++;
                showStep(currentStep);
            }
        }
    });

    prevBtn.addEventListener('click', ()=>{
        if(currentStep>0){
            currentStep--;
            showStep(currentStep);
        }
    });

    showStep(currentStep);
});
</script>
@endsection
