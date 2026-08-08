<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CjProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| لارافيل يضيف تلقائياً بادئة '/api' لكل المسارات الموجودة في هذا الملف.
| وبالتالي سينتج لدينا المسارات التالية:
| GET  /api/cj/products
| POST /api/cj/import-product
|
*/

Route::get('/cj/products', [CjProductController::class, 'index']);
Route::post('/cj/import-product', [CjProductController::class, 'importProduct']);
