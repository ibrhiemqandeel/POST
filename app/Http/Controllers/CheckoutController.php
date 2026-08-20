<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Services\ShippingCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * عرض صفحة إتمام الطلب مع محتويات السلة الحالية + حساب الشحن وطرق الدفع.
     */
    public function show(ShippingCalculator $shipping)
    {
        $cart = Cart::current()->load('items.product.supplier');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'سلتك فارغة. أضف منتجات أولاً قبل إتمام الطلب.');
        }

        $subtotal = $cart->total();
        $shippingTotal = $shipping->forItems($cart->items, $subtotal);

        return view('checkout', [
            'title'          => 'Checkout | POST',
            'description'    => 'Complete your order.',
            'cart'           => $cart,
            'subtotal'       => $subtotal,
            'shippingTotal'  => $shippingTotal,
            'grandTotal'     => $subtotal + $shippingTotal,
            'paymentMethods' => $this->enabledPaymentMethods(),
        ]);
    }

    /**
     * طرق الدفع المفعّلة من الإعدادات (افتراضياً الدفع عند الاستلام مفعّل).
     */
    protected function enabledPaymentMethods(): array
    {
        $methods = [];
        if (Setting::get('payment_cod_enabled', '1') !== '0') {
            $methods['cod'] = 'الدفع عند الاستلام';
        }
        if (Setting::get('payment_bank_transfer_enabled', '0') === '1') {
            $methods['bank_transfer'] = 'تحويل بنكي';
        }
        // ضمان وجود طريقة واحدة على الأقل
        if (empty($methods)) {
            $methods['cod'] = 'الدفع عند الاستلام';
        }

        return $methods;
    }

    /**
     * إنشاء الطلب فعلياً: تحقق من المخزون، أنشئ Order وOrderItems داخل
     * Transaction واحدة، حدّث المخزون، وأفرغ السلة — كل هذا معاً أو لا شيء
     * منه إطلاقاً (حتى لا يظهر الطلب "ناجح" رغم فشل جزء من العملية).
     */
    public function store(Request $request, ShippingCalculator $shipping)
    {
        $enabledMethods = array_keys($this->enabledPaymentMethods());

        $data = $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_email'   => 'required|email|max:255',
            'shipping_phone'   => 'required|string|max:50',
            'shipping_city'    => 'required|string|max:255',
            'shipping_address' => 'required|string|max:1000',
            'payment_method'   => 'nullable|in:' . implode(',', $enabledMethods),
            'notes'            => 'nullable|string|max:1000',
        ]);

        $cart = Cart::current()->load('items.product.supplier');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'سلتك فارغة.');
        }

        try {
            $order = DB::transaction(function () use ($cart, $data, $shipping, $enabledMethods) {
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

                $subtotal = $cart->items->sum(fn ($item) => $item->price * $item->quantity);
                $shippingTotal = $shipping->forItems($cart->items, $subtotal);
                $total = $subtotal + $shippingTotal;

                $paymentMethod = $data['payment_method'] ?? ($enabledMethods[0] ?? 'cod');

                $order = Order::create([
                    'user_id'          => auth()->id(),
                    'status'           => Order::STATUS_PENDING,
                    'subtotal'         => $subtotal,
                    'shipping_total'   => $shippingTotal,
                    'total'            => $total,
                    'payment_method'   => $paymentMethod,
                    'payment_status'   => 'unpaid',
                    'shipping_name'    => $data['shipping_name'],
                    'shipping_email'   => $data['shipping_email'],
                    'shipping_phone'   => $data['shipping_phone'],
                    'shipping_city'    => $data['shipping_city'],
                    'shipping_address' => $data['shipping_address'],
                    'notes'            => $data['notes'] ?? null,
                ]);

                foreach ($cart->items as $item) {
                    $product = $item->product;

                    // لقطة المورد والتكلفة على مستوى السطر — يسمح لطلب واحد أن
                    // يحوي منتجات من أكثر من مورد (Multi-Supplier Order).
                    OrderItem::create([
                        'order_id'      => $order->id,
                        'product_id'    => $item->product_id,
                        'supplier_id'   => $product?->supplier_id,
                        'supplier_name' => $product?->supplier?->name ?? $product?->supplier_name,
                        'product_name'  => $product->name ?? 'منتج محذوف',
                        'quantity'      => $item->quantity,
                        'price'         => $item->price,
                        'cost_price'    => $product?->cost_price,
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
