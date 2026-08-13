<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * عرض صفحة إتمام الطلب مع محتويات السلة الحالية.
     */
    public function show()
    {
        $cart = Cart::current()->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'سلتك فارغة. أضف منتجات أولاً قبل إتمام الطلب.');
        }

        return view('checkout', [
            'title'       => 'Checkout | POST',
            'description' => 'Complete your order.',
            'cart'        => $cart,
        ]);
    }

    /**
     * إنشاء الطلب فعلياً: تحقق من المخزون، أنشئ Order وOrderItems داخل
     * Transaction واحدة، حدّث المخزون، وأفرغ السلة — كل هذا معاً أو لا شيء
     * منه إطلاقاً (حتى لا يظهر الطلب "ناجح" رغم فشل جزء من العملية).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_email'   => 'required|email|max:255',
            'shipping_phone'   => 'required|string|max:50',
            'shipping_city'    => 'required|string|max:255',
            'shipping_address' => 'required|string|max:1000',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $cart = Cart::current()->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'سلتك فارغة.');
        }

        try {
            $order = DB::transaction(function () use ($cart, $data) {
                // إعادة التحقق من المخزون داخل الـ Transaction (وليس فقط عند
                // الإضافة للسلة) لمنع بيع كمية أكبر من المتوفر فعلياً.
                foreach ($cart->items as $item) {
                    $product = $item->product()->lockForUpdate()->first();

                    if (! $product) {
                        throw new \RuntimeException("أحد المنتجات في سلتك لم يعد متوفراً.");
                    }

                    if ($product->stock < $item->quantity) {
                        throw new \RuntimeException("الكمية المطلوبة من \"{$product->name}\" غير متوفرة في المخزون (المتاح: {$product->stock}).");
                    }
                }

                $total = $cart->items->sum(fn ($item) => $item->price * $item->quantity);

                $order = Order::create([
                    'user_id'          => auth()->id(),
                    'status'           => Order::STATUS_PENDING,
                    'total'            => $total,
                    'shipping_name'    => $data['shipping_name'],
                    'shipping_email'   => $data['shipping_email'],
                    'shipping_phone'   => $data['shipping_phone'],
                    'shipping_city'    => $data['shipping_city'],
                    'shipping_address' => $data['shipping_address'],
                    'notes'            => $data['notes'] ?? null,
                ]);

                foreach ($cart->items as $item) {
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $item->product_id,
                        'product_name' => $item->product->name ?? 'منتج محذوف',
                        'quantity'     => $item->quantity,
                        'price'        => $item->price,
                    ]);

                    // خصم الكمية من المخزون
                    $item->product()->decrement('stock', $item->quantity);
                }

                // إفراغ السلة بعد نجاح الطلب بالكامل
                $cart->items()->delete();

                return $order;
            });

            return redirect()->route('orders.show', $order)->with('success', 'تم إنشاء طلبك بنجاح!');

        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors(['stock' => $e->getMessage()]);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء إتمام الطلب. حاول مرة أخرى.']);
        }
    }
}
