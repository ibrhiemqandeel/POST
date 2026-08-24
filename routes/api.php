<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotOrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| لارافيل يضيف تلقائياً بادئة '/api' لكل المسارات الموجودة في هذا الملف.
|
| ملاحظة: مسارات CJ (products / import-product) أُزيلت من هنا لأنها كانت
| مكرّرة وغير محمية؛ النسخة الفعّالة منها محميّة تحت admin/cj/* في web.php.
|
*/

// استقبال طلبات بوت واتساب وإنشاؤها في لوحة الأدمن (جاهزة للإسناد).
// محمي بتوكن سرّي BOT_API_TOKEN يُرسل في ترويسة X-Bot-Token.
// => POST /api/bot/orders
Route::post('/bot/orders', [BotOrderController::class, 'store']);
