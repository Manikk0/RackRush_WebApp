<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Product image model.
class ObrazokProduktu extends Model
{
    use HasFactory;

    // Database table name.
    protected $table = 'product_images';

    // Mass-assignable columns.
    protected $fillable = [
        'product_id',
        'url',
        'order',
    ];

    // Owning product relation.
    public function produkt()
    {
        return $this->belongsTo(Produkt::class, 'product_id');
    }
}
