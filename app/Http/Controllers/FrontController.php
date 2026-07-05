<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        return view('index', [
            'PageTitle' => 'Index | POST',
            'PageDescription' => 'A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
}
