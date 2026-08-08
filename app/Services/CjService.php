<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CjService
{
    protected string $baseUrl = 'https://developers.cjdropshipping.com/api2.0/v1';

    /**
     * جلب Access Token وتخزينه في الكاش
     */
    public function getAccessToken()
    {
        return Cache::remember('cj_access_token', 8000, function () {
            $response = Http::post("{$this->baseUrl}/authentication/getAccessToken", [
                'email'  => config('services.cj.email'),
                'apiKey' => config('services.cj.api_key'),
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['data']['accessToken'])) {
                return $data['data']['accessToken'];
            }

            throw new \Exception('فشل جلب التوكن من CJ: ' . ($data['message'] ?? $response->body()));
        });
    }

    /**
     * جلب قائمة المنتجات (حفظ النجاح فقط في الكاش)
     */
    public function getProducts(int $pageNum = 1, int $pageSize = 20)
    {
        $cacheKey = "cj_products_{$pageNum}_{$pageSize}";

        // إذا كانت البيانات موجودة في الكاش سابقاً، ارجع بها
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])->get("{$this->baseUrl}/product/list", [
            'pageNum'  => $pageNum,
            'pageSize' => $pageSize,
        ]);

        $data = $response->json();

        // تخزين النتيجة في الكاش فقط إذا كان الطلب ناجحاً
        if (isset($data['result']) && $data['result'] === true) {
            Cache::put($cacheKey, $data, 3600); // 1 hour
        }

        return $data;
    }

    /**
     * جلب تفاصيل منتج معين (حفظ النجاح فقط)
     */
    public function getProductDetail(string $pid)
    {
        $cacheKey = "cj_product_detail_{$pid}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])->get("{$this->baseUrl}/product/query", [
            'pid' => $pid,
        ]);

        $data = $response->json();

        if (isset($data['result']) && $data['result'] === true) {
            Cache::put($cacheKey, $data, 86400); // 24 hours
        }

        return $data;
    }
}
