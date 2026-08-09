<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * عرض صفحة المنتجات أو إرجاعها كـ JSON للـ API
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('supplier')) {
            $query->where('supplier_name', $request->supplier);
        }

        $products = $query->latest()->paginate(12);

        // إذا كان الطلب يتوقع JSON (مثل طلبات AJAX أو API)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data'    => $products->items(),
                'meta'    => [
                    'current_page' => $products->currentPage(),
                    'last_page'    => $products->lastPage(),
                    'total'        => $products->total(),
                ]
            ], 200);
        }

        // للطلبات العادية من المتصفح: عرض صفحة Blade وتمرير المنتجات
        return view('product', compact('products'));
    }

    /**
     * عرض تفاصيل منتج معين
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'data' => $product]);
        }

        // ملاحظة: كانت هذه الدالة تستدعي view غير موجودة (products.show) وتسبب
        // خطأ 500 عند فتح أي منتج. تم توحيدها مع نفس صفحة المنتج المستخدمة في
        // باقي المشروع (resources/views/product.blade.php).
        return view('product', compact('product'));
    }
}
