<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FrontController extends Controller
{
    public function index()
    {
        // 1. جلب المنتجات من الكاش
        $products = Cache::remember('home_api_products', 3600, function () {
            try {
                $cjToken = config('services.cj.token', env('CJ_ACCESS_TOKEN'));

                // طلب البيانات من CJ Dropshipping API
                $response = Http::withHeaders([
                    'CJ-Access-Token' => $cjToken,
                ])->timeout(10)->get('https://developers.cjdropshipping.com/api2.0/v1/product/list', [
                    'pageNum'  => 1,
                    'pageSize' => 12,
                ]);

                if ($response->successful()) {
                    $responseData = $response->json();

                    // التحقق من استجابة CJ (تأكد من أن code يُرجع 200 أو المكون data ليس فارغاً)
                    if (isset($responseData['data']['list']) && !empty($responseData['data']['list'])) {
                        return $responseData['data']['list'];
                    }
                }
            } catch (\Exception $e) {
                Log::error('CJ API Error: ' . $e->getMessage());
            }

            // Fallback 1: جلب البيانات من قاعدة البيانات المحلية
            $localProducts = Product::latest()->take(12)->get()->toArray();

            if (!empty($localProducts)) {
                return $localProducts;
            }

            // Fallback 2: بيانات افتراضية مؤقتة في حال كانت قاعدة البيانات والـ API فارغين
            return [
                [
                    'pid' => '1',
                    'productNameEn' => 'Silk Linen Tailored Dress',
                    'productImage' => '',
                    'sellPrice' => 180.00,
                    'categoryName' => 'Women',
                    'tag' => 'NEW'
                ],
                [
                    'pid' => '2',
                    'productNameEn' => 'Cotton Ribbed Kids Sweater',
                    'productImage' => '',
                    'sellPrice' => 65.00,
                    'categoryName' => 'Children',
                    'tag' => 'BESTSELLER'
                ],
                [
                    'pid' => '3',
                    'productNameEn' => 'Hydrating Botanical Facial Oil',
                    'productImage' => '',
                    'sellPrice' => 42.00,
                    'categoryName' => 'Beauty',
                    'tag' => 'ORGANIC'
                ],
                [
                    'pid' => '4',
                    'productNameEn' => 'Handcrafted Leather Crossbody',
                    'productImage' => '',
                    'sellPrice' => 210.00,
                    'categoryName' => 'Accessories',
                    'tag' => 'LIMITED'
                ]
            ];
        });

        // 2. إذا عادت النتيجة فارغة لأي سبب، قم بحذف الكاش فوراً لإعادة المحاولة في الطلب القادم
        if (empty($products)) {
            Cache::forget('home_api_products');
        }

        return view('index', [
            'title'       => 'Index | POST',
            'description' => 'Discover Index, A world that combines a mother\'s elegance with her child\'s happiness.',
            'products'    => $products,
        ]);
    }

    public function about()
    {
        return view('about', [
            'title'       => 'About | POST',
            'description' => 'Discover about, A world that combines a mother\'s elegance with her child\'s happiness.',
        ]);
    }

    /**
     * جلب منتجات فئة معينة عبر الـ slug (women / kids / beauty / accessories)
     * من قاعدة البيانات الحقيقية بدل الاعتماد على مصفوفة منتجات ثابتة بالـ JS.
     */
    protected function productsByCategory(string $slug)
    {
        return Product::whereHas('category', fn ($q) => $q->where('slug', $slug))
            ->latest()
            ->get();
    }

    public function accessories()
    {
        return view('accessories', [
            'title'       => 'Accessories | POST',
            'description' => 'Discover accessories, A world that combines a mother\'s elegance with her child\'s happiness.',
            'products'    => $this->productsByCategory('accessories'),
        ]);
    }

    public function beauty()
    {
        return view('beauty', [
            'title'       => 'Beauty | POST',
            'description' => 'Discover beauty, A world that combines a mother\'s elegance with her child\'s happiness.',
            'products'    => $this->productsByCategory('beauty'),
        ]);
    }

    public function cart()
    {
        $cart = Cart::current()->load('items.product');

        return view('cart', [
            'title'       => 'Cart | POST',
            'description' => 'Discover cart, A world that combines a mother\'s elegance with her child\'s happiness.',
            'cart'        => $cart,
        ]);
    }

    public function kids()
    {
        return view('kids', [
            'title'       => 'Kids | POST',
            'description' => 'Discover kids, A world that combines a mother\'s elegance with her child\'s happiness.',
            'products'    => $this->productsByCategory('kids'),
        ]);
    }

    public function product()
    {
        return view('product', [
            'title'       => 'Product | POST',
            'description' => 'Discover product, A world that combines a mother\'s elegance with her child\'s happiness.',
        ]);
    }

    public function women()
    {
        return view('women', [
            'title'       => 'Women | POST',
            'description' => 'Discover women, A world that combines a mother\'s elegance with her child\'s happiness.',
            'products'    => $this->productsByCategory('women'),
        ]);
    }

    public function muster()
    {
        return view('muster', [
            'title'       => 'Muster | POST',
            'description' => 'Discover muster, A world that combines a mother\'s elegance with her child\'s happiness.',
        ]);
    }

    public function login()
    {
        return view('login', [
            'title'       => 'Login | POST',
            'description' => 'Discover login, A world that combines a mother\'s elegance with her child\'s happiness.',
        ]);
    }

    public function dashboard()
    {
        return view('dashboard', [
            'title'       => 'Dashboard | POST',
            'description' => 'Discover dashboard, A world that combines a mother\'s elegance with her child\'s happiness.',
        ]);
    }

    public function signup()
    {
        return view('signup', [
            'title'       => 'Signup | POST',
            'description' => 'Discover signup, A world that combines a mother\'s elegance with her child\'s happiness.',
        ]);
    }
}
