@extends('base')

@section('title', 'Catégories du forum')

@section('content')
<div class="max-w-5xl mx-auto py-6 px-4">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold font-montserrat text-[#2D2926] mb-4 sm:mb-0">
            Catégories du forum
        </h1>
        @if(auth()->user()->role === "admin")
        <a href="{{ route('categories.create') }}"
           class="px-4 py-2 rounded-xl font-semibold shadow text-white bg-[#9B7EA4] hover:bg-[#8B6DA3] transition flex items-center gap-2">
           <i class="fas fa-plus-circle"></i> Ajouter une catégorie
        </a>
        @endif
    </div>

    <!-- Message succès -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-[#D2B467]/20 border-l-4 border-[#D2B467] text-[#2D2926] rounded shadow animate-fade-in flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Barre de recherche -->
    <div class="mb-4">
        <input type="text" id="searchCategory" placeholder="Rechercher une catégorie..."
               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#008C95] focus:border-[#008C95] transition duration-300 shadow-sm">
    </div>

    <!-- Tableau catégories stylisé -->
    <div class="bg-white shadow rounded-2xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-[#008C95]/20">
                <tr>
                    <th class="px-4 py-2 border-b text-[#2D2926] font-medium">id</th>
                    <th class="px-4 py-2 border-b text-[#2D2926] font-medium">Nom</th>
                    <th class="px-4 py-2 border-b text-[#2D2926] font-medium">Description</th>
                    <th class="px-4 py-2 border-b text-[#2D2926] font-medium">Créée le</th>
                    @if(auth()->user()?->role === "admin")
                    <th class="px-4 py-2 border-b text-[#2D2926] font-medium">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody id="categoriesTable">
                @forelse($categories as $category)
                <tr class="hover:bg-[#F4F1DE]/50 transition">
                    <td class="px-4 py-2 border-b">{{ $category->id }}</td>
                    <td class="px-4 py-2 border-b font-medium text-[#008C95]">{{ $category->name }}</td>
                    <td class="px-4 py-2 border-b">{{ $category->description ?? '—' }}</td>
                    <td class="px-4 py-2 border-b">{{ optional($category->created_at)->format('d/m/Y') ?? '-' }}</td>

                    @if(auth()->user()->role === "admin")
                    <td class="px-4 py-2 border-b flex gap-2">
                        <a href="{{ route('categories.edit', $category) }}"
                           class="px-2 py-1 bg-[#59BEC9] text-white rounded hover:bg-[#49B5C0] transition flex items-center gap-1">
                           <i class="fas fa-edit text-sm"></i> Modifier
                        </a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette catégorie ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-2 py-1 bg-[#C79674] text-white rounded hover:bg-[#B38664] transition flex items-center gap-1">
                                <i class="fas fa-trash text-sm"></i> Supprimer
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()?->is_admin ? 5 : 4 }}" class="px-4 py-4 text-center text-gray-500">
                        Aucune catégorie trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>

<!-- Recherche dynamique avec JavaScript -->
<script>
document.getElementById('searchCategory').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#categoriesTable tr');
    rows.forEach(row => {
        const name = row.children[1]?.textContent.toLowerCase() || '';
        row.style.display = name.includes(filter) ? '' : 'none';
    });
});
</script>

<!-- Styles d'animation -->
<style>
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
