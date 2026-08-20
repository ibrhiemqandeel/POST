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
        'supplier_id',
        'name',
        'description',
        'price',
        'cost_price',
        'shipping_cost',
        'shipping_days',
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * هامش الربح لكل قطعة (سعر البيع - التكلفة). مفيد للوحة التحكم والتقارير.
     */
    public function profit(): float
    {
        return (float) $this->price - (float) ($this->cost_price ?? 0);
    }
}
