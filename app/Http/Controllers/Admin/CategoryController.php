<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->orderBy('name')->get()->map(function (Category $category) {
            return [
                'id'            => $category->id,
                'name'          => $category->name,
                'slug'          => $category->slug,
                'products_count' => $category->products_count,
            ];
        });

        if (! $request->wantsJson()) {
            return view('admin.categories');
        }

        return response()->json([
            'success'    => true,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name']),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'تمت إضافة التصنيف بنجاح.',
            'category' => $category,
        ], 201);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        // لا نغيّر الـ slug إذا كان الاسم لم يتغيّر، حتى لا تنكسر روابط
        // موجودة (صفحات /women /kids ... ترتبط بالـ slug الأصلي وليس بالاسم).
        if ($data['name'] !== $category->name) {
            $category->slug = $this->uniqueSlug($data['name'], $category->id);
        }

        $category->name = $data['name'];
        $category->save();

        return response()->json([
            'success'  => true,
            'message'  => 'تم تحديث التصنيف بنجاح.',
            'category' => $category,
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $productsCount = $category->products()->count();

        if ($productsCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "لا يمكن حذف هذا التصنيف لأنه مرتبط بـ {$productsCount} منتج. أعد تصنيف المنتجات أولاً ثم احذف التصنيف.",
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التصنيف.',
        ]);
    }

    protected function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (Category::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
