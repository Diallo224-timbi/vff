@extends('base')

@section('title', 'Charte d\'inscription')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-12">
    <div class="bg-white p-8 rounded-xl shadow-lg max-w-3xl w-full">
        <h1 class="text-2xl font-bold mb-4">Charte d'inscription</h1>
        <div class="overflow-y-auto max-h-[70vh] text-gray-700 space-y-4">
            <p>
                Bienvenue sur notre plateforme. En vous inscrivant, vous acceptez les règles suivantes :
            </p>
            <ul class="list-disc list-inside space-y-2">
                <li>Respecter les autres utilisateurs et leurs informations.</li>
                <li>Ne pas partager de contenu illégal ou offensant.</li>
                <li>Fournir des informations exactes et à jour.</li>
                <li>Accepter que vos données soient utilisées conformément à notre politique de confidentialité.</li>
                <li>Se conformer aux lois en vigueur dans votre région.</li>
            </ul>
            <p>
                En cochant la case "J'accepte la charte" lors de l'inscription, vous confirmez avoir lu et accepté ces conditions.
            </p>
            <p class="mt-4 text-sm text-gray-500">
                Cette charte peut être mise à jour régulièrement. Veuillez consulter cette page avant de vous inscrire.
            </p>
        </div>

        <div class="mt-6 text-right">
            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Retour à l'inscription
            </a>
        </div>
    </div>
</div>
@endsection
