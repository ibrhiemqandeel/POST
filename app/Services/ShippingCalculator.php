<?php

namespace App\Services;

use App\Models\Setting;

/**
 * حساب الشحن — قابل للتوسّع. حالياً يدعم:
 *  - شحن ثابت (flat rate) على مستوى المتجر
 *  - شحن مجاني فوق حدّ معيّن للسلة (free shipping threshold)
 *  - جمع تكلفة شحن كل مورد على حدة عند تفعيل "شحن حسب المورد"
 * يمكن لاحقاً إضافة مناطق شحن (zones) أو شركات شحن دون تغيير الواجهة، لأن كل
 * القيم تُقرأ من جدول settings.
 */
class ShippingCalculator
{
    /**
     * @param  \Illuminate\Support\Collection  $items  عناصر السلة (CartItem مع product)
     * @return float  تكلفة الشحن الإجمالية
     */
    public function forItems($items, float $subtotal): float
    {
        // شحن مجاني فوق الحد
        $freeThreshold = (float) Setting::get('shipping_free_threshold', 0);
        if ($freeThreshold > 0 && $subtotal >= $freeThreshold) {
            return 0.0;
        }

        $mode = Setting::get('shipping_mode', 'flat'); // flat | per_supplier

        if ($mode === 'per_supplier') {
            return $this->perSupplier($items);
        }

        return (float) Setting::get('shipping_flat_rate', 0);
    }

    /**
     * تكلفة شحن = مجموع تكلفة شحن كل مورد مرة واحدة (أعلى تكلفة شحن لمنتجاته)،
     * حتى لا نضاعف الشحن لعدة منتجات من نفس المورد.
     */
    protected function perSupplier($items): float
    {
        $bySupplier = [];

        foreach ($items as $item) {
            $product = $item->product;
            if (! $product) {
                continue;
            }

            $supplierKey = $product->supplier_id ?? 'none';
            $ship = (float) ($product->shipping_cost
                ?? $product->supplier?->default_shipping_cost
                ?? 0);

            // نأخذ أعلى قيمة شحن لكل مورد (تُدفع مرة واحدة لكل شحنة مورد)
            $bySupplier[$supplierKey] = max($bySupplier[$supplierKey] ?? 0, $ship);
        }

        $total = array_sum($bySupplier);

        // في حال لم يُعرَّف أي شحن للموردين نرجع للـ flat rate كحد أدنى
        if ($total <= 0) {
            return (float) Setting::get('shipping_flat_rate', 0);
        }

        return $total;
    }
}
