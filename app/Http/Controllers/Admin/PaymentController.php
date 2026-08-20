<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * قسم المدفوعات: تفعيل طرق الدفع (الدفع عند الاستلام، تحويل بنكي...) وإعداداتها،
 * مع نظرة عامة على مدفوعات الطلبات الأخيرة وحالتها. قابل للتوسّع بإضافة بوابات
 * دفع لاحقاً (Stripe / PayPal) عبر نفس مخزن الإعدادات.
 */
class PaymentController extends Controller
{
    /** طرق الدفع المدعومة حالياً. */
    public const METHODS = [
        'cod'           => 'الدفع عند الاستلام',
        'bank_transfer' => 'تحويل بنكي',
    ];

    public function index(Request $request)
    {
        $recentPayments = Order::latest()->take(20)->get()->map(fn (Order $o) => [
            'id'             => $o->id,
            'customer'       => $o->shipping_name ?? $o->user?->name ?? 'زائر',
            'total'          => (float) $o->total,
            'payment_method' => $o->payment_method,
            'payment_status' => $o->payment_status,
            'status'         => $o->status,
            'date'           => $o->created_at->format('Y-m-d H:i'),
        ]);

        $paidTotal = (float) Order::where('payment_status', 'paid')->sum('total');
        $unpaidTotal = (float) Order::where('payment_status', 'unpaid')
            ->where('status', '!=', Order::STATUS_CANCELLED)->sum('total');

        return view('admin.payments', [
            'settings'       => Setting::allSettings(),
            'methods'        => self::METHODS,
            'recentPayments' => $recentPayments,
            'paidTotal'      => $paidTotal,
            'unpaidTotal'    => $unpaidTotal,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'payment_cod_enabled'           => 'nullable|boolean',
            'payment_bank_transfer_enabled' => 'nullable|boolean',
            'payment_bank_details'          => 'nullable|string|max:2000',
        ]);

        Setting::setMany([
            'payment_cod_enabled'           => $request->boolean('payment_cod_enabled') ? '1' : '0',
            'payment_bank_transfer_enabled' => $request->boolean('payment_bank_transfer_enabled') ? '1' : '0',
            'payment_bank_details'          => $data['payment_bank_details'] ?? null,
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'تم حفظ إعدادات الدفع.');
    }
}
