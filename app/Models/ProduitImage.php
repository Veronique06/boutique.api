<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'chemin_image',
        'alt_text',
        'image_principale',
        'ordre'
    ];

    protected $casts = [
        'image_principale' => 'boolean',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->chemin_image);
    }
}
