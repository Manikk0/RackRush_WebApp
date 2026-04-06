<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObrazokProduktu extends Model
{
    use HasFactory;

    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'url',
        'order',
    ];

    public function produkt()
    {
        return $this->belongsTo(Produkt::class, 'product_id');
    }
}
