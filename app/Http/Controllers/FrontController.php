<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        return view('index', [
            'title' => 'Index | POST',
            'description' => 'Discover Index,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function about()
    {
        return view('about', [
            'title' => 'about | POST',
            'description' => 'Discover about,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function accessories()
    {
        return view('accessories', [
            'title' => 'accessories | POST',
            'description' => 'Discover accessories,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function beauty()
    {
        return view('beauty', [
            'title' => 'beauty | POST',
            'description' => 'Discover beauty,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function cart()
    {
        return view('cart', [
            'title' => 'cart | POST',
            'description' => 'Discover cart, a world that combines a mother\'s elegance with her child\'s happiness.'
        ]);
    }
    public function kids()
    {
        return view('kids', [
            'title' => 'kids | POST',
            'description' => 'Discover kids, a world that combines a mother\'s elegance with her child\'s happiness.'
        ]);
    }
    public function product()
    {
        return view('product', [
            'title' => 'product | POST',
            'description' => 'Discover product,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function women()
    {
        return view('women', [
            'title' => 'women | POST',
            'description' => 'Discover women,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function muster()
    {
        return view('muster', [
            'title' => 'muster | POST',
            'description' => 'Discover muster,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function login()
    {
        return view('login', [
            'title' => 'login | POST',
            'description' => 'Discover login,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function dashboard()
    {
        return view('dashboard', [
            'title' => 'dashboard | POST',
            'description' => 'Discover dashboard,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
}
