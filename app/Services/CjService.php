<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CjService
{
    protected string $baseUrl = 'https://developers.cjdropshipping.com/api2.0/v1';

    /**
     * الحصول على Access Token وتخزينه في الكاش لتقليل عدد الطلبات
     */
    public function getAccessToken()
    {
        return Cache::remember('cj_access_token', 8000, function () {
            $response = Http::post("{$this->baseUrl}/authentication/getAccessToken", [
                'email'  => config('services.cj.email'),
                'apiKey' => config('services.cj.api_key'),
            ]);

            if ($response->successful() && isset($response->json()['data']['accessToken'])) {
                return $response->json()['data']['accessToken'];
            }

            throw new \Exception('فشل في جلب Access Token من CJ Dropshipping: ' . $response->body());
        });
    }

    /**
     * جلب قائمة المنتجات من CJ
     */
    public function getProducts(int $pageNum = 1, int $pageSize = 20)
    {
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])->get("{$this->baseUrl}/product/list", [
            'pageNum'  => $pageNum,
            'pageSize' => $pageSize,
        ]);

        return $response->json();
    }

    /**
     * جلب تفاصيل منتج معين عبر الـ PID
     */
    public function getProductDetail(string $pid)
    {
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'CJ-Access-Token' => $token,
        ])->get("{$this->baseUrl}/product/query", [
            'pid' => $pid,
        ]);

        return $response->json();
    }
}
