<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;

// Routes d'authentification
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

    // Récupérer mes propres informations depuis mon token JWT
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::get('public/boutiques', [BoutiqueController::class, 'publicBoutiques']);
Route::get('tailles', [ProduitController::class, 'getTailles']);
Route::get('couleurs', [ProduitController::class, 'getCouleurs']);

Route::middleware('auth:api')->group(function () {

    // Routes des boutiques
    Route::apiResource('boutiques', BoutiqueController::class);
    Route::put('boutiques/{id}/toggle-visibility', [BoutiqueController::class, 'toggleVisibility']);

    // Routes des catégories
    Route::prefix('boutiques/{boutique_id}')->group(function () {
        Route::get('/categories', [CategorieController::class, 'index']);
        Route::post('/categories', [CategorieController::class, 'store']);
        Route::get('/categories/{id}', [CategorieController::class, 'show']);
        Route::put('/categories/{id}', [CategorieController::class, 'update']);
        Route::delete('/categories/{id}', [CategorieController::class, 'destroy']);
    });

    // Routes des produits
    Route::prefix('boutiques/{boutique_id}')->group(function () {
        Route::get('/produits', [ProduitController::class, 'index']);
        Route::post('/produits', [ProduitController::class, 'store']);
        Route::get('/produits/{id}', [ProduitController::class, 'show']);
        Route::put('/produits/{id}', [ProduitController::class, 'update']);
        Route::delete('/produits/{id}', [ProduitController::class, 'destroy']);
    });

    // Routes spécifiques aux produits
    Route::prefix('produits')->group(function () {
        Route::post('/{id}/images', [ProduitController::class, 'addImages']);
        Route::delete('/images/{image_id}', [ProduitController::class, 'deleteImage']);
        Route::put('/images/{image_id}/principal', [ProduitController::class, 'setImagePrincipale']);
    });
});
