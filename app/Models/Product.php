<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * الحقول المسموح بتعبئتها تلقائياً عبر Mass Assignment
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
        'sku',
        'cj_pid',
    ];
}
