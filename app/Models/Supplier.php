<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'country',
        'platform',
        'contact_email',
        'contact_phone',
        'currency',
        'default_shipping_cost',
        'shipping_days_min',
        'shipping_days_max',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active'             => 'boolean',
        'default_shipping_cost' => 'decimal:2',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * نطاق مدة الشحن كنص مقروء (مثلاً "7 - 15 يوم").
     */
    public function shippingRange(): ?string
    {
        if ($this->shipping_days_min && $this->shipping_days_max) {
            return "{$this->shipping_days_min} - {$this->shipping_days_max}";
        }

        return $this->shipping_days_min ? (string) $this->shipping_days_min : null;
    }
}
