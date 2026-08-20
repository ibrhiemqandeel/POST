<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * إدارة الموردين (Multi-Supplier / Multi-Vendor / Dropshipping).
 * تُرجع صفحة HTML عند التصفح المباشر من الشريط الجانبي، وJSON عند AJAX —
 * بنفس نمط باقي صفحات لوحة التحكم (Orders / Categories / Customers).
 */
class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->wantsJson()) {
            return view('admin.suppliers');
        }

        $suppliers = Supplier::withCount('products')->orderBy('name')->get()->map(function (Supplier $s) {
            return [
                'id'                    => $s->id,
                'name'                  => $s->name,
                'country'               => $s->country,
                'platform'              => $s->platform,
                'contact_email'         => $s->contact_email,
                'contact_phone'         => $s->contact_phone,
                'currency'              => $s->currency,
                'default_shipping_cost' => (float) $s->default_shipping_cost,
                'shipping_days_min'     => $s->shipping_days_min,
                'shipping_days_max'     => $s->shipping_days_max,
                'is_active'             => (bool) $s->is_active,
                'notes'                 => $s->notes,
                'products_count'        => $s->products_count,
            ];
        });

        return response()->json([
            'success'   => true,
            'suppliers' => $suppliers,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        $supplier = Supplier::create($data);

        return response()->json([
            'success'  => true,
            'message'  => 'تمت إضافة المورد بنجاح.',
            'supplier' => $supplier,
        ], 201);
    }

    public function update(Request $request, Supplier $supplier): JsonResponse
    {
        $data = $this->validated($request, $supplier->id);

        if ($data['name'] !== $supplier->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $supplier->id);
        }

        $supplier->update($data);

        return response()->json([
            'success'  => true,
            'message'  => 'تم تحديث بيانات المورد.',
            'supplier' => $supplier,
        ]);
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $count = $supplier->products()->count();

        if ($count > 0) {
            return response()->json([
                'success' => false,
                'message' => "لا يمكن حذف هذا المورد لأنه مرتبط بـ {$count} منتج. أعد ربط منتجاته بمورد آخر أولاً.",
            ], 422);
        }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المورد.',
        ]);
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'                  => ['required', 'string', 'max:255', Rule::unique('suppliers', 'name')->ignore($ignoreId)],
            'country'               => 'nullable|string|max:100',
            'platform'              => 'nullable|string|max:100',
            'contact_email'         => 'nullable|email|max:255',
            'contact_phone'         => 'nullable|string|max:50',
            'currency'              => 'nullable|string|max:8',
            'default_shipping_cost' => 'nullable|numeric|min:0',
            'shipping_days_min'     => 'nullable|integer|min:0|max:365',
            'shipping_days_max'     => 'nullable|integer|min:0|max:365',
            'is_active'             => 'nullable|boolean',
            'notes'                 => 'nullable|string|max:2000',
        ]);
    }

    protected function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'supplier';
        $slug = $base;
        $i = 1;

        while (Supplier::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
