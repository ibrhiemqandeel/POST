<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * استقبال طلبات بوت واتساب (Yalla Delivery) وإنشاء طلب حقيقي في لوحة الأدمن
 * بحالة "pending" (قيد الانتظار = جاهز للإسناد). البوت يرسل الطلب بعد تأكيد
 * العميل، فيظهر مباشرةً في صفحة إدارة الطلبات جاهزاً للإسناد لمندوب.
 *
 * الحماية: توكن سرّي مشترك (BOT_API_TOKEN) يُرسله البوت في ترويسة X-Bot-Token.
 */
class BotOrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // تحقق من التوكن السرّي — بدونه (أو بقيمة خاطئة) يُرفض الطلب.
        $expected = env('BOT_API_TOKEN');
        $provided = $request->header('X-Bot-Token');

        if (empty($expected) || ! is_string($provided) || ! hash_equals($expected, $provided)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $data = $request->validate([
            'ref'            => 'nullable|string|max:64',
            'customer_name'  => 'required|string|max:255',
            'whatsapp'       => 'nullable|string|max:32',
            'service_label'  => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'currency'       => 'nullable|string|max:8',
            'distance_km'    => 'nullable|numeric|min:0',
            'eta_minutes'    => 'nullable|integer|min:0',
            'delivery_code'  => 'nullable|string|max:16',
            'payment_method' => 'nullable|string|max:64',
            'delivery_time'  => 'nullable|string|max:255',
            'pickup'         => 'nullable|array',
            'dropoff'        => 'nullable|array',
            'package_note'   => 'nullable|string|max:2000',
            'vehicle_label'  => 'nullable|string|max:64',
        ]);

        $pickup  = $data['pickup'] ?? [];
        $dropoff = $data['dropoff'] ?? [];

        // عنوان التسليم يملأ حقول الشحن القياسية في لوحة الأدمن.
        $shippingCity = $dropoff['neighborhood'] ?? null;
        $shippingAddress = $this->formatAddress($dropoff);

        $order = Order::create([
            'user_id'         => null, // عميل واتساب (زائر)
            'status'          => Order::STATUS_PENDING, // جاهز للإسناد
            'subtotal'        => $data['price'],
            'shipping_total'  => $data['price'],
            'total'           => $data['price'],
            'payment_method'  => $data['payment_method'] ?? 'bank_transfer',
            'payment_status'  => 'unpaid', // يؤكده الأدمن بعد مراجعة إشعار الحوالة
            'shipping_name'   => $data['customer_name'],
            'shipping_email'  => null,
            'shipping_phone'  => $data['whatsapp'] ?? ($dropoff['contactPhone'] ?? null),
            'shipping_city'   => $shippingCity,
            'shipping_address' => $shippingAddress,
            'notes'           => $this->buildNotes($data, $pickup, $dropoff),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'تم إنشاء الطلب في لوحة الأدمن (جاهز للإسناد).',
            'order_id' => $order->id,
        ], 201);
    }

    /**
     * تجميع عنوان قابل للقراءة من مكوّنات نقطة العنوان.
     */
    protected function formatAddress(array $loc): ?string
    {
        $parts = array_filter([
            $loc['neighborhood'] ?? null,
            $loc['street'] ?? null,
            $loc['details'] ?? null,
        ]);

        return $parts ? implode('، ', $parts) : null;
    }

    /**
     * تفاصيل الطلب الكاملة تُحفظ في notes لتظهر في صفحة تفاصيل الطلب بالأدمن
     * (نظام الطلبات الحالي لا يملك حقولاً مخصّصة للاستلام/التسليم، فنعرض كل
     * بيانات التوصيل بشكل منسّق ومقروء هنا).
     */
    protected function buildNotes(array $data, array $pickup, array $dropoff): string
    {
        $currency = $data['currency'] ?? '₪';
        $lines = [];

        $lines[] = '🛵 طلب واتساب (Yalla Delivery)';
        if (! empty($data['ref'])) {
            $lines[] = 'المرجع: ' . $data['ref'];
        }
        if (! empty($data['service_label'])) {
            $lines[] = 'الخدمة: ' . $data['service_label'];
        }
        $lines[] = '';

        $lines[] = '📍 الاستلام:';
        $lines[] = '   ' . ($this->formatAddress($pickup) ?: '—');
        if (! empty($pickup['note'])) {
            $lines[] = '   ملاحظة: ' . $pickup['note'];
        }
        if (! empty($pickup['contactName']) || ! empty($pickup['contactPhone'])) {
            $lines[] = '   جهة الاتصال: ' . trim(($pickup['contactName'] ?? '') . ' — ' . ($pickup['contactPhone'] ?? ''), ' —');
        }
        $lines[] = '';

        $lines[] = '🎯 التسليم:';
        $lines[] = '   ' . ($this->formatAddress($dropoff) ?: '—');
        if (! empty($dropoff['note'])) {
            $lines[] = '   ملاحظة: ' . $dropoff['note'];
        }
        if (! empty($dropoff['contactName']) || ! empty($dropoff['contactPhone'])) {
            $lines[] = '   جهة الاتصال: ' . trim(($dropoff['contactName'] ?? '') . ' — ' . ($dropoff['contactPhone'] ?? ''), ' —');
        }
        $lines[] = '';

        if (! empty($data['package_note'])) {
            $lines[] = '📦 الشحنة: ' . $data['package_note'];
        }
        if (! empty($data['vehicle_label'])) {
            $lines[] = $data['vehicle_label'];
        }
        if (isset($data['distance_km'])) {
            $lines[] = '🚗 المسافة التقريبية: ' . $data['distance_km'] . ' كم';
        }
        if (isset($data['eta_minutes'])) {
            $lines[] = '⏱️ الوقت التقديري: ' . $data['eta_minutes'] . ' دقيقة';
        }
        $lines[] = '💵 سعر التوصيل: ' . $data['price'] . ' ' . $currency;
        if (! empty($data['payment_method'])) {
            $lines[] = '💳 الدفع: ' . $data['payment_method'];
        }
        if (! empty($data['delivery_time'])) {
            $lines[] = '⏰ وقت التوصيل: ' . $data['delivery_time'];
        }
        if (! empty($data['delivery_code'])) {
            $lines[] = '🔐 كود التسليم: ' . $data['delivery_code'];
        }

        return implode("\n", $lines);
    }
}
