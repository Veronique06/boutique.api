<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Boutique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategorieController extends Controller
{

    public function index($boutique_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($boutique_id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $categories = $boutique->categories()->with('produits')->get();

        return response()->json($categories);
    }

    public function store(Request $request, $boutique_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($boutique_id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $categorie = Categorie::create([
            'boutique_id' => $boutique->id,
            'nom' => $request->nom,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Catégorie créée avec succès',
            'categorie' => $categorie
        ], 201);
    }

    public function show($boutique_id, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($boutique_id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $categorie = $boutique->categories()->with('produits')->find($id);

        if (!$categorie) {
            return response()->json(['error' => 'Catégorie non trouvée'], 404);
        }

        return response()->json($categorie);
    }

    public function update(Request $request, $boutique_id, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($boutique_id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $categorie = $boutique->categories()->find($id);

        if (!$categorie) {
            return response()->json(['error' => 'Catégorie non trouvée'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $categorie->update($request->only(['nom', 'description']));

        return response()->json([
            'message' => 'Catégorie mise à jour avec succès',
            'categorie' => $categorie
        ]);
    }

    public function destroy($boutique_id, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($boutique_id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $categorie = $boutique->categories()->find($id);

        if (!$categorie) {
            return response()->json(['error' => 'Catégorie non trouvée'], 404);
        }

        // Vérifier si c'est la catégorie par défaut
        if ($categorie->nom === 'Non catégorisé') {
            return response()->json(['error' => 'Impossible de supprimer la catégorie par défaut'], 422);
        }

        // Déplacer les produits vers la catégorie "Non catégorisé"
        $categorieDefaut = $boutique->categories()->where('nom', 'Non catégorisé')->first();
        $categorie->produits()->update(['categorie_id' => $categorieDefaut->id]);

        $categorie->delete();

        return response()->json(['message' => 'Catégorie supprimée avec succès']);
    }
}
