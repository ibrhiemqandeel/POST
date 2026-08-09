<?php

namespace App\Http\Controllers;

use App\Services\CjService;
use Illuminate\Http\Request;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;

class CjProductController extends Controller
{
    protected CjService $cjService;

    public function __construct(CjService $cjService)
    {
        $this->cjService = $cjService;
    }

    /**
     * جلب قائمة المنتجات من CJ Dropshipping مع حساب السعر الخاص بك قبل العرض
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $page = max(1, (int) $request->query('page', 1));
            $pageSize = min(100, max(1, (int) $request->query('page_size', 20)));

            $products = $this->cjService->getProducts($page, $pageSize);

            if (isset($products['result']) && $products['result'] === false) {
                return response()->json([
                    'success' => false,
                    'message' => $products['message'] ?? 'حدث خطأ أثناء التواصل مع CJ Dropshipping',
                    'data'    => $products
                ], 429);
            }

            // تعديل أسعار المنتجات المجلوبة في القائمة قبل إرجاعها
            if (isset($products['data']['list']) && is_array($products['data']['list'])) {
                $profitMargin = 0.30; // نسبة الربح (30%)

                foreach ($products['data']['list'] as &$item) {
                    $originalPrice = (float) ($item['sellPrice'] ?? $item['productPrice'] ?? 0);
                    $item['wholesale_price'] = $originalPrice; // الاحتفاظ بسعر الجملة للرجوع إليه
                    $item['sellPrice'] = round($originalPrice * (1 + $profitMargin), 2); // السعر الجديد المعروض
                }
            }

            return response()->json([
                'success' => true,
                'data'    => $products,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب المنتجات من CJ Dropshipping',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * استيراد منتج معين وحفظه بالسعر الجديد في قاعدة البيانات
     */
    public function importProduct(Request $request): JsonResponse
    {
        $request->validate([
            'pid' => 'required|string',
        ]);

        try {
            $cjData = $this->cjService->getProductDetail($request->pid);

            if (empty($cjData['result']) || empty($cjData['data'])) {
                return response()->json([
                    'success' => false,
                    'message' => $cjData['message'] ?? 'المنتج غير موجود في CJ Dropshipping'
                ], 404);
            }

            $item = $cjData['data'];

            // قراءة السكيو أو التوليد التلقائي
            $sku = $item['productSku'] ?? $item['sku'] ?? ('CJ-' . ($item['pid'] ?? time()));

            // --- حساب سعرك الخاص (هامش الربح) ---
            $wholesalePrice = (float) ($item['sellPrice'] ?? $item['productPrice'] ?? 0);

            $profitMargin = 0.30; // 30% نسبة ربح
            $additionalFee = 0.00; // إضافة مبلغ ثابت إن أردت (مثلاً 5 دولار)

            $myPrice = round(($wholesalePrice * (1 + $profitMargin)) + $additionalFee, 2);

            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name'        => $item['productNameEn'] ?? $item['productName'] ?? 'منتج جديد',
                    'description' => $item['description'] ?? '',
                    'price'       => $myPrice, // حفظ سعر البيع الخاص بك بدلاً من سعر الجملة
                    'image'       => $item['productImage'] ?? '',
                    'stock'       => $item['packingWeight'] ?? 10,
                    'cj_pid'      => $item['pid'] ?? $request->pid,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد المنتج بنجاح وتحديد سعر البيع!',
                'product' => $product
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد المنتج',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
