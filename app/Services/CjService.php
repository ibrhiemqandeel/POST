<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CjService
{
    protected string $baseUrl = 'https://developers.cjdropshipping.com/api2.0/v1';

    /**
     * جلب Access Token
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
     * جلب المنتجات باستخدام Proxy تجنباً لـ Rate Limit الخاص بـ IP السيرفر
     */
    public function getProducts(int $pageNum = 1, int $pageSize = 20)
    {
        $cacheKey = "cj_products_{$pageNum}_{$pageSize}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $token = $this->getAccessToken();

        // إرسال الطلب عبر Proxy لتغيير الـ IP الخارج من Render
        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])->withOptions([
            // يمكن الاستعانة بأي البروكسيات المجانية المتاحة مثل ScraperAPI أو Fixie
            // 'proxy' => 'http://proxy-address:port',
            'timeout' => 15,
        ])->get("{$this->baseUrl}/product/list", [
            'pageNum'  => $pageNum,
            'pageSize' => $pageSize,
        ]);

        $data = $response->json();

        if (isset($data['result']) && $data['result'] === true) {
            Cache::put($cacheKey, $data, 3600);
        }

        return $data;
    }
}
