<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'description',
        'price',
        'stock_amount',
        'img',
        'img1',
        'img2',
        'img3',
        'img4',
        'ship_id',
        'user_id'
    ];
}
