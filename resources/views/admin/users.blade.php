@extends('base')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Liste des utilisateurs</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @foreach($users as $user)
            <div class="flex flex-wrap items-center justify-between bg-white p-4 rounded-lg shadow hover:shadow-lg transition duration-200">
                <div class="flex-1 min-w-[200px]">
                    <p class="text-gray-700 font-semibold">Nom: <span class="font-normal">{{ $user->name }}</span></p>
                    <p class="text-gray-700 font-semibold">Email: <span class="font-normal">{{ $user->email }}</span></p>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <p class="text-gray-700 font-semibold">Ville: <span class="font-normal">{{ $user->ville }}</span></p>
                    <p class="text-gray-700 font-semibold">Adresse: <span class="font-normal">{{ $user->adresse }}</span></p>
                    <p class="text-gray-700 font-semibold">Code postal: <span class="font-normal">{{ $user->code_postal }}</span></p>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <p class="text-gray-700 font-semibold">Phone: <span class="font-normal">{{ $user->phone }}</span></p>
                    <p class="text-gray-700 font-semibold">Date de création: <span class="font-normal">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'Inconnue' }}</span></p>
                    <p>Charte acceptée: <span class="font-normal">{{ $user->chart ? 'Oui' : 'Non' }}</span></p>
                </div>
                <div class="flex-1 min-w-[100px] text-center">
                    <p class="text-gray-700 font-semibold">État: 
                        <span class="px-2 py-1 rounded-lg {{ $user->etatV === 'valider' ? 'bg-green-100 text-green-700' : ($user->etatV === 'bloqué' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ $user->etatV }}
                        </span>
                    </p>
                    <div class="mt-2 space-x-2">
                        @if($user->etatV !== 'valider')
                            <form action="{{ route('admin.users.validate', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition duration-200">Valider</button>
                            </form>
                        @endif
                        @if($user->etatV !== 'bloqué')
                            <button type="button"
                                onclick="openBlockModal({{ $user->id }}, '{{ $user->name }}')"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-200">
                                Bloquer
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal de blocage -->
<div id="blockModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg w-96 p-6 relative">
        <h3 class="text-lg font-semibold mb-4">Bloquer l'utilisateur <span id="modalUserName"></span></h3>
        <form id="blockForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label for="reason" class="block font-medium text-gray-700">Motif du blocage</label>
                <textarea name="reason" id="reason" required placeholder="Motif du blocage" class="w-full border border-gray-300 rounded-lg p-2"></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeBlockModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Bloquer</button>
            </div>
        </form>
        <button type="button" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700" onclick="closeBlockModal()">✕</button>
    </div>
</div>

<script>
function openBlockModal(userId, userName) {
    const modal = document.getElementById('blockModal');
    modal.classList.remove('hidden');
    document.getElementById('modalUserName').innerText = userName;
    const form = document.getElementById('blockForm');
    form.action = `/admin/users/${userId}/block`;
}

function closeBlockModal() {
    document.getElementById('blockModal').classList.add('hidden');
    document.getElementById('reason').value = '';
}
</script>
@endsection
