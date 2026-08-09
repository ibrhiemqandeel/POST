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
        'category_id',
        'name',
        'description',
        'price',
        'cost_price',
        'supplier_platform',
        'supplier_name',
        'sync_status',
        'image',
        'stock',
        'sku',
        'cj_pid',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
