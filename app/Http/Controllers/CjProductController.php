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
     * جلب المنتجات من CJ
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $page = max(1, (int) $request->query('page', 1));
            $pageSize = min(100, max(1, (int) $request->query('page_size', 20)));

            $products = $this->cjService->getProducts($page, $pageSize);

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
     * استيراد منتج وحفظه في قاعدة البيانات
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
                    'message' => 'المنتج غير موجود في CJ Dropshipping'
                ], 404);
            }

            $item = $cjData['data'];

            $product = Product::updateOrCreate(
                ['sku' => $item['productSku']],
                [
                    'name'        => $item['productNameEn'],
                    'description' => $item['description'] ?? '',
                    'price'       => $item['sellPrice'],
                    'image'       => $item['productImage'],
                    'stock'       => $item['packingWeight'] ?? 10,
                    'cj_pid'      => $item['pid'],
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد المنتج بنجاح إلى قاعدة البيانات!',
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
