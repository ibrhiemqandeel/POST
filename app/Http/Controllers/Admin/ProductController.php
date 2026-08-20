<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
        $products = Product::with(['category', 'supplier'])->latest()->get()->map(function (Product $product) {
            return $this->transform($product);
        });

        return response()->json([
            'success'    => true,
            'products'   => $products,
            // نُرسل الفئات والموردين الحقيقيين حتى تبني الواجهة قوائمها ديناميكياً
            // بدل قائمة ثابتة (women/kids/... فقط) لا تعرف بالفئات المُضافة لاحقاً.
            'categories' => Category::orderBy('name')->get(['id', 'name', 'slug']),
            'suppliers'  => Supplier::orderBy('name')->get(['id', 'name', 'country', 'platform']),
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
        $product->load(['category', 'supplier']);

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
        $product->load(['category', 'supplier']);

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
            // التحقق ديناميكي مقابل الفئات الموجودة فعلاً في قاعدة البيانات،
            // فلا تنكسر الإضافة عند إنشاء فئة جديدة من لوحة التحكم.
            'category'          => ['required', 'string', Rule::exists('categories', 'slug')],
            'description'       => 'nullable|string|max:5000',
            'image'             => 'nullable|string|max:2048',
            'supplier_id'       => ['nullable', Rule::exists('suppliers', 'id')],
            'supplier_platform' => 'nullable|string|max:255',
            'supplier_name'     => 'nullable|string|max:255',
            'cost_price'        => 'nullable|numeric|min:0',
            'shipping_cost'     => 'nullable|numeric|min:0',
            'shipping_days'     => 'nullable|integer|min:0|max:365',
            'price'             => 'required|numeric|min:0.01',
            'stock'             => 'nullable|integer|min:0',
            'sync_status'       => 'nullable|string|in:synced,pending,out',
        ]);

        $category = Category::where('slug', $data['category'])->first();

        // عند ربط مورد حقيقي، نأخذ اسمه ومنصته ودولته كنسخة نصية للمنتج تلقائياً
        // (تبقى قابلة للتجاوز يدوياً إن أُرسلت صراحةً).
        $supplierPlatform = $data['supplier_platform'] ?? null;
        $supplierName     = $data['supplier_name'] ?? null;

        if (! empty($data['supplier_id'])) {
            $supplier = Supplier::find($data['supplier_id']);
            if ($supplier) {
                $supplierName     = $supplierName ?: $supplier->name;
                $supplierPlatform = $supplierPlatform ?: $supplier->platform;
            }
        }

        return [
            'category_id'       => $category?->id,
            'supplier_id'       => $data['supplier_id'] ?? null,
            'name'              => $data['name'],
            'description'       => $data['description'] ?? null,
            'image'             => $data['image'] ?? null,
            'supplier_platform' => $supplierPlatform,
            'supplier_name'     => $supplierName,
            'cost_price'        => $data['cost_price'] ?? null,
            'shipping_cost'     => $data['shipping_cost'] ?? null,
            'shipping_days'     => $data['shipping_days'] ?? null,
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
            'id'            => $product->id,
            'name'          => $product->name,
            'description'   => $product->description,
            'cat'           => $product->category?->slug ?? '—',
            'img'           => $product->image,
            'supplier_id'   => $product->supplier_id,
            'platform'      => $product->supplier?->platform ?? $product->supplier_platform ?? '—',
            'supplier'      => $product->supplier?->name ?? $product->supplier_name ?? '—',
            'country'       => $product->supplier?->country,
            'cost'          => (float) ($product->cost_price ?? 0),
            'shipping_cost' => (float) ($product->shipping_cost ?? 0),
            'shipping_days' => $product->shipping_days,
            'price'         => (float) $product->price,
            'stock'         => (int) $product->stock,
            'status'        => $product->sync_status ?? 'synced',
        ];
    }
}
