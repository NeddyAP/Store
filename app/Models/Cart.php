<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'id_user', 'id_product', 'color', 'qty', 'total',
    ];

    public function scopeForUser($query, $userId)
    {
        return $query->where('id_user', $userId)
            ->join('products', 'products.id', '=', 'carts.id_product')
            ->select('carts.*', 'products.name', 'products.price', 'products.new_price', 'products.img', 'products.category');
    }
}
