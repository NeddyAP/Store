<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, \Conner\Tagging\Taggable;
    protected $table = 'products';
    protected $fillable = [
        'name', 'category', 'price', 'new_price', 'spec', 'qty', 'sold', 'view', 'status', 'img', 'desc', 'color'
    ];
}
