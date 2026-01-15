<form action="{{ $action }}" method="POST" autocomplete="off">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    {{-- Nom --}}
    <div class="mb-3">
        <label>Nom de la structure</label>
        <input type="text" name="nom_structure" class="form-control"
               value="{{ old('nom_structure', $structure->nom_structure ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $structure->description ?? '') }}</textarea>
    </div>
    {{-- Adresse --}}
    <div class="mb-3 position-relative">
        <label>Adresse</label>
        <input type="text"
               name="adresse"
               id="autocomplete"
               class="form-control"
               value="{{ old('adresse', $structure->adresse ?? '') }}"
               placeholder="Commencez à taper l'adresse..."
               required>

        <div id="suggestions"
             class="border bg-white w-100"
             style="position:absolute; z-index:1000;"></div>
    </div>

    {{-- Ville / CP / Pays --}}
    <div class="row mb-3">
        <div class="col">
            <label>Ville</label>
            <input type="text" name="ville" id="locality" class="form-control"
                   value="{{ old('ville', $structure->ville ?? '') }}">
        </div>
        <div class="col">
            <label>Code Postal</label>
            <input type="text" name="code_postal" id="postal_code" class="form-control"
                   value="{{ old('code_postal', $structure->code_postal ?? '') }}">
        </div>
        <div class="col">
            <label>Pays</label>
            <input type="text" name="pays" id="country" class="form-control"
                   value="{{ old('pays', $structure->pays ?? '') }}">
        </div>
    </div>

    {{-- Lat / Lon --}}
    <input type="hidden" name="latitude" id="latitude"
           value="{{ old('latitude', $structure->latitude ?? '') }}">
    <input type="hidden" name="longitude" id="longitude"
           value="{{ old('longitude', $structure->longitude ?? '') }}">

    {{-- Contact --}}
    <div class="mb-3">
        <label>Contact</label>
        <input type="text" name="contact" class="form-control"
               value="{{ old('contact', $structure->contact ?? '') }}">
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email', $structure->email ?? '') }}">
    </div>

    {{-- Responsable --}}
    <div class="mb-3">
        <label>Responsable</label>
        <input type="text" name="responsable" class="form-control"
               value="{{ old('responsable', $structure->responsable ?? '') }}">
    </div>

    <button type="submit" class="btn btn-success">Enregistrer</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', async () => {

    const input = document.getElementById('autocomplete');
    const suggestionsDiv = document.getElementById('suggestions');

    const lat = document.getElementById('latitude');
    const lon = document.getElementById('longitude');
    const city = document.getElementById('locality');
    const postal = document.getElementById('postal_code');
    const country = document.getElementById('country');

    let debounce;

    async function geocodeAddress(query) {
        const res = await fetch(
            `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=1&q=${encodeURIComponent(query)}`
        );
        const data = await res.json();

        if (!data.length) return;

        const place = data[0];
        lat.value = place.lat;
        lon.value = place.lon;
        city.value = place.address.city || place.address.town || place.address.village || '';
        postal.value = place.address.postcode || '';
        country.value = place.address.country || '';
    }

    // ✅ AUTO-GEOCODAGE EN MODIFICATION
    if (input.value.length > 3 && !lat.value) {
        await geocodeAddress(input.value);
    }

    // ✅ AUTOCOMPLETE NORMAL
    input.addEventListener('input', () => {
        const query = input.value.trim();

        lat.value = '';
        lon.value = '';

        if (query.length < 3) {
            suggestionsDiv.innerHTML = '';
            return;
        }

        clearTimeout(debounce);

        debounce = setTimeout(async () => {
            const res = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=5&q=${encodeURIComponent(query)}`
            );

            const places = await res.json();

            suggestionsDiv.innerHTML = places.map(place => `
                <div class="suggestion-item p-2"
                     style="cursor:pointer"
                     data-lat="${place.lat}"
                     data-lon="${place.lon}"
                     data-city="${place.address.city || place.address.town || place.address.village || ''}"
                     data-postal="${place.address.postcode || ''}"
                     data-country="${place.address.country || ''}">
                    ${place.display_name}
                </div>
            `).join('');

            document.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', () => {
                    input.value = item.textContent.trim();
                    lat.value = item.dataset.lat;
                    lon.value = item.dataset.lon;
                    city.value = item.dataset.city;
                    postal.value = item.dataset.postal;
                    country.value = item.dataset.country;
                    suggestionsDiv.innerHTML = '';
                });
            });

        }, 300);
    });

    document.addEventListener('click', (e) => {
        if (!input.contains(e.target)) {
            suggestionsDiv.innerHTML = '';
        }
    });
});
</script>

