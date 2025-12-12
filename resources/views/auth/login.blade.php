@extends("base")

@section('title',"Se connecter")

@section('content')
 <div class="">
    @if ($errors->any())
     <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <strong class="font-bold">Erreur!</strong>
        <span class="block sm:inline">{{$errors->first()}}</span>   
     </div>   
    @endif
 </div>
 <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-center">Se connecter</h2>
    <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
        @csrf <!-- Token CSRF pour la sécurité -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" required value="{{old('email')}}"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
            @error('email')
                <span class="text-red-500 text-sm">email invalide</span>
            @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input type="password" name="password" id="password" required value="{{old('email')}}"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
            @error('email')
                <span class="text-red-500 text-sm">mot de passe invalide</span>
            @enderror
            <p>
                <a href="{{route('password.request')}}" class="text-blue-600 hover:underline text-sm">Mot de passe oublié ?</a>
            </p>
        </div>
        <div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Se connecter</button>
        </div>
        <p class="text-center text-sm text-gray-600">
            Pas encore de compte ? 
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Inscrivez-vous ici</a>.
        </p>
    </form>
</div>  
@endsection