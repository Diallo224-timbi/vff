<div>
    <h1>Ressource importante ajoutée : {{ $resource->title }}</h1>
    <p>Une nouvelle ressource a été marquée comme importante :</p>
    <ul>
        <li><strong>Titre :</strong> {{ $resource->title }}</li>
        <li><strong>Description :</strong> {{ $resource->description }}</li>
        <li><strong>Catégorie :</strong> {{ $resource->category }}</li>
        @if($resource->link_url)
            <li><strong>Lien :</strong> <a href="{{ $resource->link_url }}">{{ $resource->link_url }}</a></li>
        @endif
    </ul>
</div>