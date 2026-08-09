<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * تحكّم كامل بالمنتجات من لوحة تحكم الأدمن (resources/views/dashboard.blade.php):
 * إضافة / تعديل / حذف، بما فيها السعر وسعر التكلفة والمخزون. هذا هو المكان
 * الذي أصبحت فيه لوحة التحكم متصلة فعلياً بقاعدة البيانات الحقيقية بدل
 * العمل على مصفوفة JS محلية غير محفوظة.
 */
class ProductController extends Controller
{
    /**
     * قائمة كل المنتجات (تُستخدم لملء جدول لوحة التحكم).
     */
    public function index(): JsonResponse
    {
        $products = Product::with('category')->latest()->get()->map(function (Product $product) {
            return $this->transform($product);
        });

        return response()->json([
            'success'  => true,
            'products' => $products,
        ]);
    }

    /**
     * إضافة منتج جديد من نافذة لوحة التحكم.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $data['sku'] = $this->generateUniqueSku();

        $product = Product::create($data);
        $product->load('category');

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة المنتج بنجاح.',
            'product' => $this->transform($product),
        ], 201);
    }

    /**
     * تعديل منتج موجود (الاسم، الفئة، السعر، سعر التكلفة، المخزون...).
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $this->validated($request);
        $product->update($data);
        $product->load('category');

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات المنتج بنجاح.',
            'product' => $this->transform($product),
        ]);
    }

    /**
     * حذف منتج نهائياً من الكتالوج.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنتج من الكتالوج.',
        ]);
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'category'          => 'required|string|in:women,kids,beauty,accessories',
            'image'             => 'nullable|string|max:2048',
            'supplier_platform' => 'nullable|string|max:255',
            'supplier_name'     => 'nullable|string|max:255',
            'cost_price'        => 'nullable|numeric|min:0',
            'price'             => 'required|numeric|min:0.01',
            'stock'             => 'nullable|integer|min:0',
            'sync_status'       => 'nullable|string|in:synced,pending,out',
        ]);

        $category = Category::where('slug', $data['category'])->first();

        return [
            'category_id'       => $category?->id,
            'name'              => $data['name'],
            'image'             => $data['image'] ?? null,
            'supplier_platform' => $data['supplier_platform'] ?? null,
            'supplier_name'     => $data['supplier_name'] ?? null,
            'cost_price'        => $data['cost_price'] ?? null,
            'price'             => $data['price'],
            'stock'             => $data['stock'] ?? 0,
            'sync_status'       => $data['sync_status'] ?? 'synced',
        ];
    }

    protected function generateUniqueSku(): string
    {
        do {
            $sku = 'ADM-' . strtoupper(Str::random(8));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }

    /**
     * تحويل المنتج لنفس شكل البيانات (id/name/cat/img/platform/supplier/
     * cost/price/stock/status) الذي تتوقعه واجهة لوحة التحكم الحالية بالضبط،
     * حتى تعمل الواجهة كما هي دون أي تعديل بصري.
     */
    protected function transform(Product $product): array
    {
        return [
            'id'       => $product->id,
            'name'     => $product->name,
            'cat'      => $product->category?->slug ?? 'women',
            'img'      => $product->image,
            'platform' => $product->supplier_platform ?? '—',
            'supplier' => $product->supplier_name ?? '—',
            'cost'     => (float) ($product->cost_price ?? 0),
            'price'    => (float) $product->price,
            'stock'    => (int) $product->stock,
            'status'   => $product->sync_status ?? 'synced',
        ];
    }
}
