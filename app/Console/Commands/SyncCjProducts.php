<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CjService; // أو نستخدم Http مباشرة
use App\Models\Product;
use Illuminate\Support\Str;

class SyncCjProducts extends Command
{
    /**
     * اسم الأمر الذي سيتم تشغيله عبر artisan
     */
    protected $signature = 'app:sync-cj-products {--page=1} {--pageSize=20}';

    /**
     * وصف الأمر
     */
    protected $description = 'جلب المنتجات من CJ Dropshipping وتخزينها في قاعدة البيانات';

    public function handle()
    {
        $this->info('جاري جلب المنتجات من CJ Dropshipping...');

        // استدعاء CjService لجلب المنتجات (أو عبر Http)
        $cjService = new CjService();
        $page = (int) $this->option('page');
        $pageSize = (int) $this->option('pageSize');

        $response = $cjService->getProducts($page, $pageSize);

        // التأكد من استلام البيانات بشكل صحيح
        if (!isset($response['result']) || !$response['result']) {
            $this->error('فشل في جلب البيانات من CJ: ' . json_encode($response));
            return 1;
        }

        $productsList = $response['data']['list'] ?? [];
        $savedCount = 0;

        foreach ($productsList as $item) {
            // توليد SKU مؤقت في حال كان فارغاً لمنع خطأ الـ Unique Constraint
            $sku = $item['productSku'] ?? ('CJ-' . $item['pid']);

            Product::updateOrCreate(
                ['cj_pid' => $item['pid']], // البحث بناءً على cj_pid الموجود بالـ Migration
                [
                    'name'        => $item['productNameEn'] ?? $item['productName'] ?? 'منتج بدون اسم',
                    'description' => $item['remark'] ?? null,
                    'price'       => floatval($item['sellPrice'] ?? 0),
                    'image'       => $item['productImage'] ?? null,
                    'stock'       => $item['listedNum'] ?? 10, // القيمة الافتراضية للكمية
                    'sku'         => $sku,
                ]
            );

            $savedCount++;
        }

        $this->info("تمت العملية بنجاح! تم حفظ / تحديث {$savedCount} منتج.");
        return 0;
    }
}
