<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Boutique;
use App\Models\ProduitImage;
use App\Models\Taille;
use App\Models\Couleur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class ProduitController extends Controller
{

    public function index($boutique_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($boutique_id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $produits = $boutique->produits()
            ->with(['categorie', 'produitImages', 'tailles', 'couleurs'])
            ->get();

        return response()->json($produits);
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
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categorie_id' => 'nullable|exists:categories,id',
            'visible' => 'nullable|boolean',
            'sku' => 'nullable|string|unique:produits,sku',
            'poids' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'dimensions.longueur' => 'nullable|numeric|min:0',
            'dimensions.largeur' => 'nullable|numeric|min:0',
            'dimensions.hauteur' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'tailles' => 'nullable|array',
            'tailles.*' => 'exists:tailles,id',
            'couleurs' => 'nullable|array',
            'couleurs.*' => 'exists:couleurs,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Si pas de catégorie spécifiée, utiliser la catégorie par défaut
        $categorie_id = $request->categorie_id;
        if (!$categorie_id) {
            $categorieDefaut = $boutique->categories()->where('nom', 'Non catégorisé')->first();
            $categorie_id = $categorieDefaut->id;
        }

        // Générer un SKU automatique s'il n'est pas fourni
        $sku = $request->sku ?: $this->generateSku($boutique, $request->nom);

        $produit = Produit::create([
            'boutique_id' => $boutique->id,
            'categorie_id' => $categorie_id,
            'nom' => $request->nom,
            'description' => $request->description,
            'prix' => $request->prix,
            'stock' => $request->stock,
            'visible' => $request->visible ?? true,
            'sku' => $sku,
            'poids' => $request->poids,
            'dimensions' => $request->dimensions,
        ]);

        // Gérer les images
        if ($request->hasFile('images')) {
            $this->handleImageUploads($produit, $request->file('images'));
        }

        // Associer les tailles
        if ($request->tailles) {
            $produit->tailles()->attach($request->tailles);
        }

        // Associer les couleurs
        if ($request->couleurs) {
            $produit->couleurs()->attach($request->couleurs);
        }

        return response()->json([
            'message' => 'Produit créé avec succès',
            'produit' => $produit->load(['categorie', 'produitImages', 'tailles', 'couleurs'])
        ], 201);
    }

    public function show($boutique_id, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($boutique_id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $produit = $boutique->produits()
            ->with(['categorie', 'produitImages', 'tailles', 'couleurs'])
            ->find($id);

        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvée'], 404);
        }

        return response()->json($produit);
    }

    public function update(Request $request, $boutique_id, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($boutique_id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $produit = $boutique->produits()->find($id);

        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvée'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'categorie_id' => 'nullable|exists:categories,id',
            'visible' => 'nullable|boolean',
            'sku' => 'nullable|string|unique:produits,sku,' . $produit->id,
            'poids' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'tailles' => 'nullable|array',
            'couleurs' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updateData = $request->only([
            'nom',
            'description',
            'prix',
            'stock',
            'categorie_id',
            'visible',
            'sku',
            'poids',
            'dimensions'
        ]);

        $produit->update($updateData);

        // Gérer les nouvelles images
        if ($request->hasFile('images')) {
            $this->handleImageUploads($produit, $request->file('images'));
        }

        // Mettre à jour les tailles
        if ($request->has('tailles')) {
            $produit->tailles()->sync($request->tailles);
        }

        // Mettre à jour les couleurs
        if ($request->has('couleurs')) {
            $produit->couleurs()->sync($request->couleurs);
        }

        return response()->json([
            'message' => 'Produit mis à jour avec succès',
            'produit' => $produit->load(['categorie', 'produitImages', 'tailles', 'couleurs'])
        ]);
    }

    public function destroy($boutique_id, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $boutique = $user->boutiques()->find($boutique_id);

        if (!$boutique) {
            return response()->json(['error' => 'Boutique non trouvée'], 404);
        }

        $produit = $boutique->produits()->find($id);

        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvée'], 404);
        }

        // Supprimer toutes les images associées
        foreach ($produit->produitImages as $image) {
            Storage::disk('public')->delete($image->chemin_image);
        }

        $produit->delete();

        return response()->json(['message' => 'Produit supprimé avec succès']);
    }

    // Méthodes utilitaires
    private function generateSku($boutique, $nomProduit)
    {
        $prefix = strtoupper(substr($boutique->nom, 0, 3));
        $suffix = strtoupper(substr($nomProduit, 0, 3));
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        return $prefix . '-' . $suffix . '-' . $random;
    }

    private function handleImageUploads($produit, $images)
    {
        foreach ($images as $index => $image) {
            $path = $image->store('produits/' . $produit->id, 'public');

            ProduitImage::create([
                'produit_id' => $produit->id,
                'chemin_image' => $path,
                'alt_text' => $produit->nom,
                'image_principale' => $index === 0, // La première image est la principale
                'ordre' => $index,
            ]);
        }
    }

    // API pour obtenir les tailles et couleurs disponibles
    public function getTailles()
    {
        $tailles = Taille::all();
        return response()->json($tailles);
    }

    public function getCouleurs()
    {
        $couleurs = Couleur::all();
        return response()->json($couleurs);
    }
}
