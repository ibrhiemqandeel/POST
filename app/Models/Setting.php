<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * مخزن إعدادات المتجر (key/value) — روابط السوشيال ميديا، إعدادات الشحن
 * والدفع، ومعلومات المتجر. تُقرأ عبر Setting::get() وتُخزَّن في الكاش لتفادي
 * ضرب قاعدة البيانات في كل طلب.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    protected const CACHE_KEY = 'app_settings_all';

    /**
     * كل الإعدادات كـ [key => value] مع كاش.
     */
    public static function allSettings(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            // تحصين: لو لم تُطبَّق ميجريشنات الإعدادات بعد (عمود key/value مفقود)
            // أو تعذّر الوصول لقاعدة البيانات، نرجع مصفوفة فارغة فتعمل الصفحات
            // بالقيم الافتراضية بدل إسقاط الموقع كله بخطأ 500.
            try {
                return static::query()->pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                report($e);
                return [];
            }
        });
    }

    public static function get(string $key, $default = null)
    {
        $all = static::allSettings();

        return $all[$key] ?? $default;
    }

    /**
     * حفظ/تحديث إعداد واحد (upsert) وتفريغ الكاش.
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * حفظ مجموعة إعدادات دفعة واحدة.
     */
    public static function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget(self::CACHE_KEY);
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }
}
