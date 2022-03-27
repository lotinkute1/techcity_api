<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'discount_percent',
        'product_id',
        'discount_id',
    ];
}
