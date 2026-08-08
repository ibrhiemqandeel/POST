<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // 1. استدعاء الموديل

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(12);

        // 2. إرجاع العرض وتمرير المنتجات مع العناوين بشكل صحيح
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
