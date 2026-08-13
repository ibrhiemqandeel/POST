<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * قائمة طلبات المستخدم الحالي فقط (تُستخدم من صفحة "حسابي").
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items')
            ->latest()
            ->get();

        return view('orders.index', [
            'title'       => 'My Orders | POST',
            'description' => 'View your order history.',
            'orders'      => $orders,
        ]);
    }

    /**
     * تفاصيل طلب واحد — يجب أن يخص المستخدم الحالي فقط (منع IDOR: لا يمكن
     * لأي مستخدم مشاهدة تفاصيل طلب مستخدم آخر عبر تخمين الرقم بالرابط)،
     * إلا إذا كان أدمن.
     */
    public function show(Order $order)
    {
        abort_unless(
            $order->user_id === auth()->id() || auth()->user()?->is_admin,
            403
        );

        $order->load('items.product');

        return view('orders.show', [
            'title'       => 'Order #' . $order->id . ' | POST',
            'description' => 'Order details.',
            'order'       => $order,
        ]);
    }
}
