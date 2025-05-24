<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class BoutiqueController extends Controller
{

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutiques = $user->boutiques()->with(['categories', 'produits'])->get();

        return response()->json($boutiques);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'image_couverture' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:5120', // 5MB max
            'lien_acces' => 'nullable|url',
            'devise' => 'nullable|in:FCFA,EUR,USD,GBP',
            'visible' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = JWTAuth::parseToken()->authenticate();

        $boutiqueData = [
            'user_id' => $user->id,
            'nom' => $request->nom,
            'description' => $request->description,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'lien_acces' => $request->lien_acces,
            'devise' => $request->devise ?? 'FCFA',
            'visible' => $request->visible ?? true,
        ];

        // Gestion de l'upload du logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('boutiques/logos', 'public');
            $boutiqueData['logo'] = $logoPath;
        }

        // Gestion de l'upload de l'image de couverture
        if ($request->hasFile('image_couverture')) {
            $couverturePath = $request->file('image_couverture')->store('boutiques/couvertures', 'public');
            $boutiqueData['image_couverture'] = $couverturePath;
        }

        $boutique = Boutique::create($boutiqueData);

        // Créer une catégorie par défaut "Non catégorisé"
        Categorie::create([
            'boutique_id' => $boutique->id,
            'nom' => 'Non catégorisé',
            'description' => 'Catégorie par défaut pour les produits sans catégorie'
        ]);

        return response()->json([
            'message' => 'Boutique créée avec succès',
            'boutique' => $boutique->load(['categories', 'produits'])
        ], 201);
    }

    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->with(['categories', 'produits'])->find($id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        return response()->json($boutique);
    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'adresse' => 'nullable|string',
            'telephone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'image_couverture' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:5120',
            'lien_acces' => 'nullable|url',
            'devise' => 'nullable|in:FCFA,EUR,USD,GBP',
            'visible' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updateData = $request->only(['nom', 'description', 'adresse', 'telephone', 'lien_acces', 'devise', 'visible']);

        // Gestion de l'upload du nouveau logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo s'il existe
            if ($boutique->logo) {
                Storage::disk('public')->delete($boutique->logo);
            }
            $logoPath = $request->file('logo')->store('boutiques/logos', 'public');
            $updateData['logo'] = $logoPath;
        }

        // Gestion de l'upload de la nouvelle image de couverture
        if ($request->hasFile('image_couverture')) {
            // Supprimer l'ancienne image s'il existe
            if ($boutique->image_couverture) {
                Storage::disk('public')->delete($boutique->image_couverture);
            }
            $couverturePath = $request->file('image_couverture')->store('boutiques/couvertures', 'public');
            $updateData['image_couverture'] = $couverturePath;
        }

        $boutique->update($updateData);

        return response()->json([
            'message' => 'Boutique mise à jour avec succès',
            'boutique' => $boutique
        ]);
    }

    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        // Supprimer les images associées
        if ($boutique->logo) {
            Storage::disk('public')->delete($boutique->logo);
        }
        if ($boutique->image_couverture) {
            Storage::disk('public')->delete($boutique->image_couverture);
        }

        $boutique->delete();

        return response()->json(['message' => 'Boutique supprimée avec succès']);
    }

    // Méthode pour changer la visibilité
    public function toggleVisibility($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $boutique->update(['visible' => !$boutique->visible]);

        return response()->json([
            'message' => 'Visibilité de la boutique mise à jour',
            'boutique' => $boutique
        ]);
    }

    // Méthode pour obtenir les boutiques publiques (pour un marketplace par exemple)
    public function publicBoutiques()
    {
        $boutiques = Boutique::visible()
            ->with(['categories', 'produits'])
            ->get();

        return response()->json($boutiques);
    }
}
