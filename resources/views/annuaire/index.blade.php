@extends('base')

@section('title', 'Annuaire des structures')

@section('content')
<div 
    class="container mx-auto px-4 py-6"
    x-data="annuaire()"
>

    <!-- Titre -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3 md:mb-0">
            Annuaire des structures
        </h1>

        <!-- Actions -->
        <div class="flex space-x-2">
            <a href="{{ route('annuaire.export.csv') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                Export CSV
            </a>
            <a href="{{ route('annuaire.export.pdf') }}"
               class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 transition">
                Export PDF
            </a>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="mb-4">
        <input
            type="text"
            x-model="search"
            placeholder="Rechercher par nom, ville, responsable, email..."
            class="w-full md:w-1/2 px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none transition"
        >
    </div>

    <!-- Table responsive -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Nom</th>
                    <th class="px-3 py-2">Adresse</th>
                    <th class="px-3 py-2">Ville</th>
                    <th class="px-3 py-2">CP</th>
                    <th class="px-3 py-2">Contact</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Responsable</th>
                    <th class="px-3 py-2 text-center">Membres</th>
                </tr>
            </thead>

            <tbody>
                @foreach($structures as $structure)
                <tr
                    x-show="match(@js($structure))"
                    x-transition.opacity
                    class="border-t hover:bg-blue-50 transition"
                >
                    <td class="px-3 py-2">{{ $structure->id }}</td>
                    <td class="px-3 py-2 font-semibold text-gray-800">
                        {{ $structure->nom_structure }}
                    </td>
                    <td class="px-3 py-2">{{ $structure->adresse }}</td>
                    <td class="px-3 py-2">{{ $structure->ville }}</td>
                    <td class="px-3 py-2">{{ $structure->code_postal }}</td>
                    <td class="px-3 py-2">{{ $structure->contact }}</td>
                    <td class="px-3 py-2 text-blue-600">
                        {{ $structure->email }}
                    </td>
                    <td class="px-3 py-2">{{ $structure->responsable }}</td>
                    <td class="px-3 py-2 text-center">
                        <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                            {{ $structure->members_count() }} 
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- Alpine JS -->
<script>
function annuaire() {
    return {
        search: '',

        match(structure) {
            if (this.search === '') return true;

            const term = this.search.toLowerCase();

            return (
                structure.nom_structure?.toLowerCase().includes(term) ||
                structure.ville?.toLowerCase().includes(term) ||
                structure.responsable?.toLowerCase().includes(term) ||
                structure.email?.toLowerCase().includes(term)
            );
        }
    }
}
</script>
@endsection
