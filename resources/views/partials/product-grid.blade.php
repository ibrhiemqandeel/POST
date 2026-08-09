{{--
    شبكة منتجات مشتركة تُستخدم في صفحات الفئات (Women/Kids/Beauty/Accessories)
    بنفس تصميم بطاقة المنتج (.prod-card) المستخدمة أصلاً في الصفحة الرئيسية
    (resources/views/index.blade.php)، لكن مربوطة الآن بمنتجات حقيقية من
    قاعدة البيانات بدل مصفوفة PRODUCTS الوهمية في public/app.js.

    Props:
    - $products: Collection من App\Models\Product
    - $countSelector (اختياري): معرّف عنصر عدّاد النتائج (مثال: #womenCount)
--}}
@forelse($products as $product)
    <a class="prod-card" href="{{ url('/products/'.$product->id) }}">
        <div class="prod-card__img"
            style="background: {{ !empty($product->image) ? "url('".e($product->image)."') center/cover" : 'linear-gradient(150deg,#E6B6A2,#B0715C)' }};">
        </div>
        <div class="prod-card__name">{{ $product->name }}</div>
        <div class="prod-card__meta">
            <span>{{ $product->category?->name ?? 'POST' }}</span>
            <span class="prod-card__price">${{ number_format((float) $product->price, 2) }}</span>
        </div>
    </a>
@empty
    <p class="muted">لا توجد منتجات متاحة حالياً في هذا القسم.</p>
@endforelse

@isset($countSelector)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const el = document.querySelector('{{ $countSelector }}');
            if (el) el.textContent = '{{ $products->count() }} pieces';
        });
    </script>
@endisset
