@extends('base')

@section('title', 'Carte des structures')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Carte des structures</h1>

    <!-- Filtre de recherche -->
    <input type="text" id="mapSearch" placeholder="Filtrer par nom, ville ou responsable..." 
           class="border rounded w-full px-3 py-2 mb-4 focus:ring focus:ring-blue-200 focus:outline-none transition">

    <!-- Carte responsive -->
    <div id="map" class="w-full h-[400px] md:h-[600px] lg:h-[800px] rounded border"></div>
</div>
@endsection

@section('scripts')
<!-- Leaflet CSS et JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    // Initialisation de la carte centrée sur la France
    const map = L.map('map').setView([46.6, 2.5], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Données des structures
    const structures = @json($structures);

    // Tableau pour stocker les markers
    let markers = [];

    // Fonction pour ajouter un marker
    function addMarker(s) {
        if(s.latitude && s.longitude) {
            const marker = L.marker([s.latitude, s.longitude]).addTo(map);
            marker.bindPopup(`
                <strong>${s.nom_structure}</strong><br>
                ${s.adresse}<br>
                ${s.ville} ${s.code_postal}<br>
                ${s.pays || ''}<br>
                Contact: ${s.contact || ''}<br>
                Email: <a href="mailto:${s.email}">${s.email}</a><br>
                Responsable: ${s.responsable || ''}<br>
                <a href="https://www.google.com/maps/search/?api=1&query=${s.latitude},${s.longitude}" target="_blank">Voir sur Google Maps</a>
            `);
            markers.push({ marker, data: s });
        }
    }

    // Ajouter tous les markers au départ
    structures.forEach(addMarker);

    // Filtre dynamique
    const mapSearch = document.getElementById('mapSearch');
    mapSearch.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        markers.forEach(({ marker, data }) => {
            const match = data.nom_structure.toLowerCase().includes(filter) ||
                          (data.ville && data.ville.toLowerCase().includes(filter)) ||
                          (data.responsable && data.responsable.toLowerCase().includes(filter));
            if(match) {
                marker.addTo(map);
            } else {
                map.removeLayer(marker);
            }
        });
    });

    // Redimensionnement responsive
    window.addEventListener('resize', () => {
        map.invalidateSize();
    });
});
</script>
@endsection
