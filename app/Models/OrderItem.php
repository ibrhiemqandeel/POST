<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'supplier_id',
        'supplier_name',
        'product_name',
        'quantity',
        'price',
        'cost_price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lineTotal(): float
    {
        return (float) $this->price * (int) $this->quantity;
    }
}
