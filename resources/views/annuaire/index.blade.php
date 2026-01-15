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
    <div class="overflow-xl-auto bg-white rounded-lg">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs ">
                <tr class="">
                    <th class="px-3 py-2 border border-gray-300">ID</th>
                    <th class="px-3 py-2 border border-gray-300">Nom</th>
                    <th class="px-3 py-2 border border-gray-300">Description</th>
                    <th class="px-3 py-2 border border-gray-300">Adresse</th>
                    <th class="px-3 py-2 border border-gray-300">Ville</th>
                    <th class="px-3 py-2 border border-gray-300">CP</th>
                    <th class="px-3 py-2 border border-gray-300">Latitude</th>
                    <th class="px-3 py-2 border border-gray-300">Longitude</th>
                    <th class="px-3 py-2 border border-gray-300">Contact</th>
                    <th class="px-3 py-2 border border-gray-300">Email</th>
                    <th class="px-3 py-2 border border-gray-300">Responsable</th>
                    <th class="px-3 py-2 text-center border border-gray-300">Membres</th>
                </tr>
            </thead>

            <tbody>
                @foreach($structures as $structure)
                <tr
                    x-show="match(@js($structure))"
                    x-transition.opacity
                    class="border-t hover:bg-blue-50 transition"
                >
                    <td class="px-3 py-2 border border-gray-300">{{ $structure->id }}</td>
                    <td class="px-3 py-2 font-semibold text-gray-800 border border-gray-300">
                        {{ $structure->nom_structure }}
                    </td>
                    <td class="px-3 py-2 border border-gray-300">{{ $structure->description }}</td>
                    <td class="px-3 py-2 border border-gray-300">{{ $structure->adresse }}</td>
                    <td class="px-3 py-2 border border-gray-300">{{ $structure->ville }}</td>
                    <td class="px-3 py-2 border border-gray-300">{{ $structure->code_postal }}</td>
                    <td class="px-3 py-2 border border-gray-300">{{ $structure->latitude }}</td>
                    <td class="px-3 py-2 border border-gray-300">{{ $structure->longitude }}</td>
                    <td class="px-3 py-2 border border-gray-300">{{ $structure->contact }}</td>
                    <td class="px-3 py-2 text-blue-600 border border-gray-300">
                        {{ $structure->email }}
                    </td>
                    <td class="px-3 py-2 border border-gray-300">{{ $structure->responsable }}</td>
                    <td class="px-3 py-2 text-center border border-gray-300">
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
