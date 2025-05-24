<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table pour les images de produits
        Schema::create('produit_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->string('chemin_image');
            $table->string('alt_text')->nullable();
            $table->boolean('image_principale')->default(false);
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        // Table pour les tailles
        Schema::create('tailles', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // XS, S, M, L, XL, XXL, ou taille personnalisée
            $table->string('code')->unique(); // xs, s, m, l, xl, xxl
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Table pour les couleurs
        Schema::create('couleurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Rouge, Bleu, etc.
            $table->string('code_hex')->nullable(); // #FF0000
            $table->timestamps();
        });

        // Table pivot pour produit-tailles
        Schema::create('produit_tailles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('taille_id')->constrained()->onDelete('cascade');
            $table->integer('stock_taille')->default(0);
            $table->decimal('prix_supplement', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['produit_id', 'taille_id']);
        });

        // Table pivot pour produit-couleurs
        Schema::create('produit_couleurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('couleur_id')->constrained()->onDelete('cascade');
            $table->integer('stock_couleur')->default(0);
            $table->decimal('prix_supplement', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['produit_id', 'couleur_id']);
        });

    }

    public function down()
    {
        Schema::dropIfExists('produit_couleurs');
        Schema::dropIfExists('produit_tailles');
        Schema::dropIfExists('couleurs');
        Schema::dropIfExists('tailles');
        Schema::dropIfExists('produit_images');
    }
};
