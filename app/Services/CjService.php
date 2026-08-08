<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CjService
{
    protected string $baseUrl = 'https://developers.cjdropshipping.com/api2.0/v1';

    /**
     * الحصول على الـ Access Token وتخزينه موقتاً لتقليل عدد الطلبات
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

            throw new \Exception('Failed to retrieve CJ Access Token: ' . $response->body());
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
}
