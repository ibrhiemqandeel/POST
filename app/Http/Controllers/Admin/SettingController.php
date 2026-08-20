<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * إعدادات المتجر العامة + روابط السوشيال ميديا. تُخزَّن في جدول settings
 * (key/value) وتظهر في واجهة الموقع (الفوتر) وقابلة للتعديل بالكامل من هنا.
 */
class SettingController extends Controller
{
    /** مفاتيح السوشيال ميديا المدعومة (تُعرض روابطها في الفوتر). */
    public const SOCIAL_KEYS = [
        'social_instagram',
        'social_facebook',
        'social_tiktok',
        'social_youtube',
        'social_whatsapp',
        'social_twitter',
    ];

    /** مفاتيح معلومات المتجر العامة. */
    public const GENERAL_KEYS = [
        'store_name',
        'store_email',
        'store_phone',
        'store_address',
    ];

    public function index()
    {
        return view('admin.settings', [
            'settings' => Setting::allSettings(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name'       => 'nullable|string|max:255',
            'store_email'      => 'nullable|email|max:255',
            'store_phone'      => 'nullable|string|max:50',
            'store_address'    => 'nullable|string|max:500',
            'social_instagram' => 'nullable|url|max:255',
            'social_facebook'  => 'nullable|url|max:255',
            'social_tiktok'    => 'nullable|url|max:255',
            'social_youtube'   => 'nullable|url|max:255',
            'social_twitter'   => 'nullable|url|max:255',
            // واتساب: نقبل رقم دولي أو رابط wa.me
            'social_whatsapp'  => 'nullable|string|max:255',
        ]);

        $pairs = [];
        foreach (array_merge(self::GENERAL_KEYS, self::SOCIAL_KEYS) as $key) {
            $pairs[$key] = $data[$key] ?? null;
        }

        Setting::setMany($pairs);

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم حفظ الإعدادات بنجاح.');
    }
}
