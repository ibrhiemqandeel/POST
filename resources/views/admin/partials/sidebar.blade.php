{{--
  الشريط الجانبي الموحّد لكل صفحات لوحة التحكم — نفس الماركب المستخدم أصلاً
  في resources/views/dashboard.blade.php، استُخرج هنا كملف مشترك بدل تكراره
  في كل صفحة إدارة جديدة (Orders / Categories / Customers).
  يستقبل متغيّر $active لتحديد أي عنصر في القائمة يُظهر كـ "نشِط":
  'products' | 'orders' | 'customers' | 'categories'

  ملاحظة: هذا الجزء لا يفتح/يغلق <div class="app"> أو <main> — الصفحة
  المستدعية هي المسؤولة عن ذلك (راجع resources/views/admin/orders.blade.php
  كمثال) حتى يبقى الترميز متماسكاً وسهل القراءة في كل ملف.
--}}
@php($active = $active ?? 'products')

<div class="mobile-nav">
  <div class="brand">POST <span>Admin</span></div>
  <button onclick="toggleSidebar()">☰</button>
</div>

<aside class="sidebar" id="sidebar">
  <div class="brand">POST<span>لوحة التحكم — الإدارة</span></div>
  <nav class="nav">
    <a class="nav-item {{ $active === 'products' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">↳ المنتجات</a>
    <a class="nav-item {{ $active === 'orders' ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">↳ الطلبات</a>
    <a class="nav-item {{ $active === 'customers' ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">↳ العملاء</a>
    <div class="nav-item">↳ التقارير</div>
    <div class="nav-eyebrow">دروب شوبينغ</div>
    <div class="nav-item" onclick="typeof openCjModal === 'function' ? openCjModal() : (window.location = '{{ route('admin.dashboard') }}')">↳ استيراد من CJ Dropshipping</div>
    <div class="nav-item" onclick="typeof syncCjInventory === 'function' ? syncCjInventory() : (window.location = '{{ route('admin.dashboard') }}')">↳ مزامنة المخزون</div>
    <div class="nav-eyebrow">الإعدادات</div>
    <a class="nav-item {{ $active === 'categories' ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">↳ الفئات</a>
    <div class="nav-item">↳ الحساب</div>
  </nav>
  <div class="sidebar-foot">لوحة التحكم المخصصة لـ POST — تتزامن برمجياً عبر API.</div>
</aside>
