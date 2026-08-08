<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CjService
{
    protected string $baseUrl = 'https://developers.cjdropshipping.com/api2.0/v1';

    /**
     * جلب الخيارات المخصصة لطلبات HTTP (مثل Proxy و Timeout)
     */
    protected function getHttpOptions(): array
    {
        $options = [
            'timeout' => 15,
        ];

        // في حال تم ضبط عنوان بروكسي في ملف .env سيتم استخدامه تلقائياً
        if (env('CJ_PROXY')) {
            $options['proxy'] = env('CJ_PROXY');
        }

        return $options;
    }

    /**
     * جلب Access Token وتخزينه في الكاش
     */
    public function getAccessToken()
    {
        return Cache::remember('cj_access_token', 8000, function () {
            $response = Http::withOptions($this->getHttpOptions())
                ->post("{$this->baseUrl}/authentication/getAccessToken", [
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
     * جلب قائمة المنتجات عبر البروكسي وتخزين الاستجابات الناجحة فقط
     */
    public function getProducts(int $pageNum = 1, int $pageSize = 20)
    {
        $cacheKey = "cj_products_{$pageNum}_{$pageSize}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])->withOptions($this->getHttpOptions())
          ->get("{$this->baseUrl}/product/list", [
              'pageNum'  => $pageNum,
              'pageSize' => $pageSize,
          ]);

        $data = $response->json();

        // تخزين النتيجة في الكاش فقط إذا كان الطلب ناجحاً من CJ
        if (isset($data['result']) && $data['result'] === true) {
            Cache::put($cacheKey, $data, 3600);
        }

        return $data;
    }

    /**
     * جلب تفاصيل منتج معين عبر الـ PID
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
        ])->withOptions($this->getHttpOptions())
          ->get("{$this->baseUrl}/product/query", [
              'pid' => $pid,
          ]);

        $data = $response->json();

        if (isset($data['result']) && $data['result'] === true) {
            Cache::put($cacheKey, $data, 86400);
        }

        return $data;
    }
}
