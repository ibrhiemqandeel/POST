<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        return view('index', [
            'PageTitle' => 'Index | POST',
            'PageDescription' => 'Discover Index,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function about()
    {
        return view('about', [
            'PageTitle' => 'about | POST',
            'PageDescription' => 'Discover about,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function accessories()
    {
        return view('accessories', [
            'PageTitle' => 'accessories | POST',
            'PageDescription' => 'Discover accessories,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function beauty()
    {
        return view('beauty', [
            'PageTitle' => 'beauty | POST',
            'PageDescription' => 'Discover beauty,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function cart()
    {
        return view('cart', [
            'PageTitle' => 'cart | POST',
            'PageDescription' => 'Discover cart,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function kids()
    {
        return view('kids', [
            'PageTitle' => 'kids | POST',
            'PageDescription' => 'Discover kids,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function product()
    {
        return view('product', [
            'PageTitle' => 'product | POST',
            'PageDescription' => 'Discover product,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function women()
    {
        return view('women', [
            'PageTitle' => 'women | POST',
            'PageDescription' => 'Discover women,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function muster()
    {
        return view('muster', [
            'PageTitle' => 'muster | POST',
            'PageDescription' => 'Discover muster,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
    public function login()
    {
        return view('login', [
            'PageTitle' => 'login | POST',
            'PageDescription' => 'Discover login,A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
}
