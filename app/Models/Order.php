<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // حالات الطلب المعتمدة في المشروع
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_PROCESSING,
        self::STATUS_SHIPPED,
        self::STATUS_DELIVERED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'user_id',
        'status',
        'subtotal',
        'shipping_total',
        'total',
        'payment_method',
        'payment_status',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_city',
        'shipping_address',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * تجميع عناصر الطلب حسب المورد — يمكّن من التعامل مع طلب واحد يحوي
     * منتجات من أكثر من مورد (إرسال أمر شراء لكل مورد على حدة).
     */
    public function itemsBySupplier()
    {
        return $this->items->groupBy(fn (OrderItem $item) => $item->supplier_name ?: 'غير محدد');
    }
}
