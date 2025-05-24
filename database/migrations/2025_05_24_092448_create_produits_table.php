<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained()->onDelete('cascade');
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2);
            $table->integer('stock')->default(0);
            $table->json('images')->nullable();
            $table->boolean('visible')->default(true);
            $table->json('tailles_disponibles')->nullable();
            $table->json('couleurs_disponibles')->nullable();
            $table->string('sku')->nullable(); // Code produit
            $table->decimal('poids', 8, 3)->nullable(); // en kg
            $table->json('dimensions')->nullable(); // {longueur, largeur, hauteur}
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
