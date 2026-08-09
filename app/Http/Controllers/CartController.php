<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * إضافة منتج إلى سلة المستخدم الحالي (مسجل أو زائر).
     */
    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'nullable|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;
        $cart = Cart::current();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $product->price,
            ]);
        }

        $cart->load('items');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة المنتج إلى السلة.',
                'count'   => $cart->count(),
                'total'   => $cart->total(),
            ]);
        }

        return back()->with('success', 'تمت إضافة المنتج إلى السلة.');
    }

    /**
     * تحديث كمية عنصر في السلة.
     */
    public function update(Request $request, CartItem $item): JsonResponse|RedirectResponse
    {
        $this->authorizeItem($item);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $item->update(['quantity' => $data['quantity']]);

        $cart = $item->cart()->with('items')->first();

        if ($request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'line_total' => $item->lineTotal(),
                'count'      => $cart->count(),
                'total'      => $cart->total(),
            ]);
        }

        return back();
    }

    /**
     * حذف عنصر من السلة.
     */
    public function remove(Request $request, CartItem $item): JsonResponse|RedirectResponse
    {
        $this->authorizeItem($item);

        $cart = $item->cart;
        $item->delete();
        $cart->load('items');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count'   => $cart->count(),
                'total'   => $cart->total(),
            ]);
        }

        return back();
    }

    /**
     * تأكيد أن عنصر السلة المطلوب تعديله فعلاً يخص سلة المستخدم/الزائر
     * الحالي، لمنع أي شخص من تعديل أو حذف عناصر سلة غيره عبر تخمين الـ id.
     */
    protected function authorizeItem(CartItem $item): void
    {
        $currentCart = Cart::current();

        abort_unless($item->cart_id === $currentCart->id, 403);
    }
}
