<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;

/**
 * إعدادات الشحن القابلة للتوسّع: نمط الشحن (ثابت / حسب المورد)، السعر الثابت،
 * وحدّ الشحن المجاني. تظهر أيضاً شحن كل مورد (يُدار من صفحة الموردين).
 */
class ShippingController extends Controller
{
    public function index()
    {
        return view('admin.shipping', [
            'settings'  => Setting::allSettings(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'shipping_mode'           => 'required|in:flat,per_supplier',
            'shipping_flat_rate'      => 'nullable|numeric|min:0',
            'shipping_free_threshold' => 'nullable|numeric|min:0',
        ]);

        Setting::setMany([
            'shipping_mode'           => $data['shipping_mode'],
            'shipping_flat_rate'      => $data['shipping_flat_rate'] ?? 0,
            'shipping_free_threshold' => $data['shipping_free_threshold'] ?? 0,
        ]);

        return redirect()->route('admin.shipping.index')
            ->with('success', 'تم حفظ إعدادات الشحن.');
    }
}
