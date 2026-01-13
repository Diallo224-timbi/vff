<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
     // Liste des catégories
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    // Formulaire de création
    public function create()
    {
        return view('categories.create');
    }

    // Stocke une catégorie
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès !');
    }
    // Supprime une catégorie
    public function destroy(Category $category)
{
    // Supprimer tous les threads de cette catégorie
    $category->threads()->delete();

    // Supprimer la catégorie
    $category->delete();

    return redirect()->route('categories.index')
                     ->with('success', 'Catégorie et ses threads supprimés.');
}
    // Formulaire d'édition
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Met à jour une catégorie
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès !');
    }
}
