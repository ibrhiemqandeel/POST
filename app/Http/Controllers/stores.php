<?php

namespace App\Http\Controllers;

use App\Services\CjService;
use Illuminate\Http\Request;

class CjProductController extends Controller
{
    protected CjService $cjService;

    public function __construct(CjService $cjService)
    {
        $this->cjService = $cjService;
    }

    public function index(Request $request)
    {
        try {
            $page = $request->query('page', 1);
            $pageSize = $request->query('page_size', 20);

            $products = $this->cjService->getProducts($page, $pageSize);

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
