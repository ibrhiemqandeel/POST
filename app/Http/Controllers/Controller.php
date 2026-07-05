<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function index()
    {
        return view('index', [
            'PageTitle' => 'Index | POST',
            'PageDescription' => 'A world that combines a mothers elegance with her childs happiness.'
        ]);
    }
}
