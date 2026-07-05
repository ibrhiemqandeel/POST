<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller; // استيراد المتحكم هنا

// تعريف الرابط الرئيسي للموقع
Route::get('/', [Controller::class, 'index'])->name('index');
