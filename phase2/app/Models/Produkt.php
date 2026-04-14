<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Product model with pricing and display helpers.
class Produkt extends Model
{
    use HasFactory;

    // Database table name.
    protected $table = 'products';

    // Mass-assignable columns.
    protected $fillable = [
        'category_id',
        'product_code',
        'name',
        'price',
        'quantity',
        'unit',
        'description',
        'recipe',
        'discount',
        'sold_count',
        'country_of_origin',
        'is_bio',
        'is_plastic_free',
        'allergens',
    ];

    // Cast rules for numeric/boolean columns.
    protected $casts = [
        'price' => 'float',
        'quantity' => 'float',
        'discount' => 'integer',
        'sold_count' => 'float',
        'is_bio' => 'boolean',
        'is_plastic_free' => 'boolean',
    ];

    // Category relation for this product.
    public function kategoria()
    {
        return $this->belongsTo(Kategoria::class, 'category_id');
    }

    // Product images ordered by "order" field.
    public function obrazky()
    {
        return $this->hasMany(ObrazokProduktu::class, 'product_id')->orderBy('order');
    }

    // Main image relation (order = 0).
    public function hlavnyObrazok()
    {
        return $this->hasOne(ObrazokProduktu::class, 'product_id')->where('order', 0)->latestOfMany();
    }

    // Computed final price after discount.
    public function getCenaPoZlaveAttribute(): float
    {
        $price = (float) $this->price;
        $discount = max(0, min(100, (int) $this->discount));

        return round($price * (1 - ($discount / 100)), 2);
    }

    // Computed display quantity (e.g. g/ml formatting).
    public function getMnozstvoDisplayAttribute(): string
    {
        $amount = (float) $this->quantity;
        $unit = $this->unit;

        if ($unit === 'kg' && $amount > 0 && $amount < 1) {
            return (string) (int) round($amount * 1000) . 'g';
        }

        if ($unit === 'l' && $amount > 0 && $amount < 1) {
            return (string) (int) round($amount * 1000) . 'ml';
        }

        if (fmod($amount, 1.0) === 0.0) {
            return (string) (int) $amount . $unit;
        }

        return rtrim(rtrim(number_format($amount, 3, '.', ''), '0'), '.') . $unit;
    }

    // Computed unit price label.
    public function getCenaNaJednotkuAttribute(): ?string
    {
        $amount = (float) $this->quantity;
        if ($amount <= 0) {
            return null;
        }

        $price = $this->cena_po_zlave;

        return number_format($price / $amount, 2) . '€/' . $this->unit;
    }
}
