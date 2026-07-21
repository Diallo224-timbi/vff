<?php

namespace App\Http\Controllers;

use App\Models\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchemaController extends Controller
{
    public function index(Request $request)
    {
        $query = Schema::where('user_id', auth()->id());
        
        if ($request->has('gt')) {
            $query->where(function($q) use ($request) {
                $q->where('category', $request->gt)
                  ->orWhere('sub_category', $request->gt);
            });
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'sub_category' => 'nullable|string',
            'file' => 'required|file|max:20480|mimes:pdf'
        ]);

        $schema = new Schema();
        $schema->user_id = auth()->id();
        $schema->title = $request->title;
        $schema->description = $request->description;
        $schema->category = $request->category;
        $schema->sub_category = $request->sub_category;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('schemas', 'public');
            $schema->file_path = $path;
            $schema->file_name = $file->getClientOriginalName();
        }

        // Stocker les données en JSON
        $schema->data = json_encode([
            'elements' => [],
            'appState' => [],
            'files' => []
        ]);

        $schema->save();

        return redirect()->route('resources.index')->with('success', 'Compte rendu ajouté avec succès');
    }

    public function show(Schema $schema)
    {
        return response()->json(['success' => true, 'schema' => $schema]);
    }

    public function update(Request $request, Schema $schema)
    {
        $schema->update(['data' => $request->data]);
        return response()->json(['success' => true]);
    }

    public function destroy(Schema $schema)
    {
        if ($schema->file_path) {
            Storage::disk('public')->delete($schema->file_path);
        }
        $schema->delete();
        return response()->json(['success' => true]);
    }
}