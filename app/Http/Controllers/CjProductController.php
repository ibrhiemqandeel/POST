<?php

namespace App\Http\Controllers;

use App\Services\CjService;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class CjProductController extends Controller
{
    protected CjService $cjService;

    public function __construct(CjService $cjService)
    {
        $this->cjService = $cjService;
    }

    /**
     * جلب قائمة المنتجات من CJ مع إمكانية التصفح (Pagination)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $page = max(1, (int) $request->query('page', 1));
            $pageSize = min(100, max(1, (int) $request->query('page_size', 20)));

            $products = $this->cjService->getProducts($page, $pageSize);

            return response()->json([
                'success' => true,
                'data' => $products,
            ], 200);

        } catch (Throwable $e) {
            Log::error('CJ getProducts failed', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب المنتجات من CJ Dropshipping',
            ], 500);
        }
    }

    /**
     * استيراد منتج معين بناءً على PID وتخزينه/تحديثه في قاعدة بيانات متجرك
     */
    public function importProduct(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pid' => 'required|string',
        ]);

        try {
            // 1. جلب تفاصيل المنتج من خدمة CJ
            $cjData = $this->cjService->getProductDetail($validated['pid']);

            if (empty($cjData['result']) || empty($cjData['data'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'المنتج غير موجود في CJ Dropshipping',
                ], 404);
            }

            $item = $cjData['data'];

            // تحقق من وجود الحقول الأساسية قبل الحفظ
            if (empty($item['productSku']) || empty($item['productNameEn'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات المنتج غير مكتملة من CJ Dropshipping',
                ], 422);
            }

            // 2. حفظ المنتج في جدول المنتجات بالموقع
            $product = Product::updateOrCreate(
                ['sku' => $item['productSku']],
                [
                    'name'        => $item['productNameEn'],
                    'description' => $item['description'] ?? '',
                    'price'       => (float) ($item['sellPrice'] ?? 0),
                    'image'       => $item['productImage'] ?? null,
                    'stock'       => $item['inventoryNum'] ?? 10, // كان خطأ: يستخدم packingWeight بدل المخزون الفعلي
                    'cj_pid'      => $item['pid'] ?? $validated['pid'],
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد المنتج بنجاح إلى قاعدة البيانات!',
                'product' => $product,
            ], 200);

        } catch (Throwable $e) {
            Log::error('CJ importProduct failed', [
                'pid'     => $validated['pid'] ?? null,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد المنتج',
            ], 500);
        }
    }
}
