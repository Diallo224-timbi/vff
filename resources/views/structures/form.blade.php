<form action="{{ $action }}" method="POST">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="mb-3">
        <label>Nom</label>
        <input type="text" name="nom_structure" class="form-control" value="{{ old('nom_structure', $structure->nom_structure ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label>Adresse</label>
        <input type="text" name="adresse" id="autocomplete" class="form-control" value="{{ old('adresse', $structure->adresse ?? '') }}" required placeholder="Commencez à taper l'adresse...">
        <div id="suggestions" class="border bg-white mt-1" style="position:absolute; z-index:1000;"></div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label>Ville</label>
            <input type="text" name="ville" id="locality" class="form-control" value="{{ old('ville', $structure->ville ?? '') }}">
        </div>
        <div class="col">
            <label>Code Postal</label>
            <input type="text" name="code_postal" id="postal_code" class="form-control" value="{{ old('code_postal', $structure->code_postal ?? '') }}">
        </div>
        <div class="col">
            <label>Pays</label>
            <input type="text" name="pays" id="country" class="form-control" value="{{ old('pays', $structure->pays ?? '') }}">
        </div>
    </div>

    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $structure->latitude ?? '') }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $structure->longitude ?? '') }}">

    <div class="mb-3">
        <label>Contact</label>
        <input type="text" name="contact" class="form-control" value="{{ old('contact', $structure->contact ?? '') }}">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $structure->email ?? '') }}">
    </div>

    <div class="mb-3">
        <label>Responsable</label>
        <input type="text" name="responsable" class="form-control" value="{{ old('responsable', $structure->responsable ?? '') }}">
    </div>

    <button type="submit" class="btn btn-success">Enregistrer</button>
</form>

<script>
const input = document.getElementById('autocomplete');
const suggestionsDiv = document.getElementById('suggestions');
let timer;

input.addEventListener('input', function() {
    const query = this.value;
    if(query.length < 3) { suggestionsDiv.innerHTML = ''; return; }

    clearTimeout(timer);
    timer = setTimeout(async () => {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&limit=5`);
        const data = await res.json();

        suggestionsDiv.innerHTML = data.map(place => `
            <div class="suggestion-item p-2 cursor-pointer hover:bg-gray-200" 
                 data-lat="${place.lat}" 
                 data-lon="${place.lon}" 
                 data-city="${place.address.city || place.address.town || ''}" 
                 data-postal="${place.address.postcode || ''}" 
                 data-country="${place.address.country || ''}">
                 ${place.display_name}
            </div>
        `).join('');

        document.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', function() {
                input.value = this.textContent;
                document.getElementById('latitude').value = this.dataset.lat;
                document.getElementById('longitude').value = this.dataset.lon;
                document.getElementById('locality').value = this.dataset.city;
                document.getElementById('postal_code').value = this.dataset.postal;
                document.getElementById('country').value = this.dataset.country;
                suggestionsDiv.innerHTML = '';
            });
        });

    }, 300); // petit délai pour éviter trop de requêtes
});
</script>
