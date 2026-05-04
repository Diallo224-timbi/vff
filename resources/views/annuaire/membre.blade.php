@extends('base')
@section('title', 'Annuaire des membres')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center mb-8 bg-gradient-to-r from-[#4a8599] to-[#255156] bg-clip-text text-transparent">
            Annuaire des membres
        </h1>
        <!-- Barre de recherche -->
        <div class="max-w-2xl mx-auto mb-8">
            <div class="flex p-1 bg-white rounded-xl shadow-lg">
                <input type="text" id="search" name="search"
                       placeholder="Rechercher un membre..."
                       class="flex-1 px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
        </div>
        <!-- Liste des membres -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="members-container">
            @foreach($membres as $membre)
                <div class="member-card bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
                    <div class="h-1 bg-gradient-to-r from-[#1780a3] to-[#255156]"></div>
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-3 text-gray-800 group-hover:text-blue-600 transition-colors">
                            {{ $membre->name }} {{ $membre->prenom }}
                        </h2>
                        <div class="space-y-2">
                            <p class="text-gray-600 flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span><strong>Email :</strong> <span class="member-email">{{ $membre->email }}</span></span>
                            </p>
                            <p class="text-gray-600 flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>
                                    <strong>Structure :</strong>
                                    <span class="member-structure">
                                        {{ $membre->structure->organisme->nom_organisme ?? 'N/A' }}
                                        {{ $membre->structure->ville ?? '' }}
                                    </span>
                                </span>
                            </p>
                            <p class="text-gray-600 flex items-start">
                                <svg class="w-5 h-5 mr-2 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span><strong>Téléphone :</strong> <span class="member-phone">{{ $membre->phone }}</span></span>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Aucun résultat -->
        <div id="no-results" class="text-center py-12 hidden">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun membre trouvé</h3>
            <p class="text-gray-500">Essayez de modifier vos critères de recherche</p>
        </div>
        <!-- Pagination -->
        <div class="mt-8">
            @if(method_exists($membres, 'links'))
                <div class="flex justify-center">
                    {{ $membres->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
<script>
    const searchInput = document.querySelector('#search');
    const memberCards = document.querySelectorAll('.member-card');
    const noResultsDiv = document.querySelector('#no-results');

    function filterMembers() {
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;

        memberCards.forEach(card => {
            const name = card.querySelector('h2').textContent.toLowerCase();
            const email = card.querySelector('.member-email').textContent.toLowerCase();
            const phone = card.querySelector('.member-phone').textContent.toLowerCase();
            const structure = card.querySelector('.member-structure').textContent.toLowerCase();

            const matchesSearch =
                name.includes(searchTerm) ||
                email.includes(searchTerm) ||
                phone.includes(searchTerm) ||
                structure.includes(searchTerm);

            if (matchesSearch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        noResultsDiv.classList.toggle('hidden', visibleCount !== 0);
    }

    let timeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(filterMembers, 200);
    });

    filterMembers();
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.member-card { animation: fadeIn 0.4s ease-out; }
</style>

@endsection
