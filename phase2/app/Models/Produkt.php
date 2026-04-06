<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produkt extends Model
{
    use HasFactory;

    protected $table = 'products';

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
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'float',
        'discount' => 'integer',
        'sold_count' => 'float',
    ];

    public function kategoria()
    {
        return $this->belongsTo(Kategoria::class, 'category_id');
    }

    public function obrazky()
    {
        return $this->hasMany(ObrazokProduktu::class, 'product_id')->orderBy('order');
    }

    public function hlavnyObrazok()
    {
        return $this->hasOne(ObrazokProduktu::class, 'product_id')->where('order', 0)->latestOfMany();
    }

    public function getCenaPoZlaveAttribute(): float
    {
        $price = (float) $this->price;
        $discount = max(0, min(100, (int) $this->discount));

        return round($price * (1 - ($discount / 100)), 2);
    }

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
