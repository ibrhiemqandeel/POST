<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * قائمة كل الطلبات (لملء جدول لوحة تحكم الطلبات).
     * تُرجع صفحة HTML عند التصفح المباشر من المتصفح (رابط الشريط الجانبي)،
     * وتُرجع JSON عند طلبات AJAX (نفس نمط apiRequest المستخدم في dashboard.blade.php).
     */
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items'])->latest()->get()->map(function (Order $order) {
            return $this->transform($order);
        });

        if (! $request->wantsJson()) {
            return view('admin.orders');
        }

        return response()->json([
            'success' => true,
            'orders'  => $orders,
        ]);
    }

    /**
     * تفاصيل طلب واحد (مع المنتجات).
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['user', 'items.product']);

        return response()->json([
            'success' => true,
            'order'   => $this->transform($order, withItems: true),
        ]);
    }

    /**
     * تغيير حالة الطلب (pending/processing/shipped/delivered/cancelled).
     */
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|string|in:' . implode(',', Order::STATUSES),
        ]);

        $order->update(['status' => $data['status']]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الطلب.',
            'order'   => $this->transform($order->fresh(['user', 'items'])),
        ]);
    }

    protected function transform(Order $order, bool $withItems = false): array
    {
        $data = [
            'id'             => $order->id,
            'customer_name'  => $order->shipping_name ?? $order->user?->name ?? 'زائر',
            'customer_email' => $order->shipping_email ?? $order->user?->email ?? '—',
            'items_count'    => $order->items->sum('quantity'),
            'total'          => (float) $order->total,
            'status'         => $order->status,
            'date'           => $order->created_at->format('Y-m-d H:i'),
        ];

        if ($withItems) {
            $data['shipping_phone'] = $order->shipping_phone;
            $data['shipping_city'] = $order->shipping_city;
            $data['shipping_address'] = $order->shipping_address;
            $data['notes'] = $order->notes;
            $data['subtotal'] = (float) $order->subtotal;
            $data['shipping_total'] = (float) $order->shipping_total;
            $data['payment_method'] = $order->payment_method;
            $data['payment_status'] = $order->payment_status;
            $data['items'] = $order->items->map(fn ($item) => [
                'name'     => $item->product_name,
                'supplier' => $item->supplier_name ?: 'غير محدد',
                'quantity' => $item->quantity,
                'price'    => (float) $item->price,
                'cost'     => (float) ($item->cost_price ?? 0),
                'total'    => $item->lineTotal(),
            ]);

            // تجميع العناصر حسب المورد — طلب واحد قد يشمل أكثر من مورد.
            $data['suppliers'] = $order->items
                ->groupBy(fn ($item) => $item->supplier_name ?: 'غير محدد')
                ->map(fn ($items, $name) => [
                    'supplier' => $name,
                    'items'    => $items->count(),
                    'units'    => $items->sum('quantity'),
                    'cost'     => (float) $items->sum(fn ($i) => (float) ($i->cost_price ?? 0) * $i->quantity),
                ])->values();
        }

        return $data;
    }
}
