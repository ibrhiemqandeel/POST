<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FrontController extends Controller
{
    public function index()
    {
        // جلب المنتجات وتخزينها في الكاش لمدة 3600 ثانية (ساعة)
        $products = Cache::remember('home_api_products', 3600, function () {
            try {
                // طلب البيانات من CJ Dropshipping API
                $response = Http::withHeaders([
                    'CJ-Access-Token' => config('services.cj.token', env('CJ_ACCESS_TOKEN')),
                ])->timeout(8)->get('https://developers.cjdropshipping.com/api2.0/v1/product/list', [
                    'pageNum'  => 1,
                    'pageSize' => 12,
                ]);

                if ($response->successful()) {
                    $responseData = $response->json();

                    // CJ يرجع المنتجات داخل ['data']['list']
                    if (isset($responseData['data']['list']) && !empty($responseData['data']['list'])) {
                        return $responseData['data']['list'];
                    }
                }
            } catch (\Exception $e) {
                // تسجيل الخطأ أو تجنب توقف الموقع في حال حدوث مشكلة شبكة
            }

            // Fallback: جلب البيانات من قاعدة البيانات المحلية في حال فشل الـ API
            return Product::latest()->take(12)->get()->toArray();
        });

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

    public function accessories()
    {
        return view('accessories', [
            'title'       => 'Accessories | POST',
            'description' => 'Discover accessories, A world that combines a mother\'s elegance with her child\'s happiness.',
        ]);
    }

    public function beauty()
    {
        return view('beauty', [
            'title'       => 'Beauty | POST',
            'description' => 'Discover beauty, A world that combines a mother\'s elegance with her child\'s happiness.',
        ]);
    }

    public function cart()
    {
        return view('cart', [
            'title'       => 'Cart | POST',
            'description' => 'Discover cart, A world that combines a mother\'s elegance with her child\'s happiness.',
        ]);
    }

    public function kids()
    {
        return view('kids', [
            'title'       => 'Kids | POST',
            'description' => 'Discover kids, A world that combines a mother\'s elegance with her child\'s happiness.',
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
