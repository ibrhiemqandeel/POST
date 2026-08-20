{{--
  الشريط الجانبي الموحّد لكل صفحات لوحة التحكم.
  يستقبل متغيّر $active لتحديد العنصر النشط:
  'dashboard' | 'products' | 'orders' | 'customers' | 'suppliers'
  | 'payments' | 'shipping' | 'categories' | 'settings'
--}}
@php($active = $active ?? 'products')

<div class="mobile-nav">
  <div class="brand">POST <span>Admin</span></div>
  <button onclick="toggleSidebar()">☰</button>
</div>

<aside class="sidebar" id="sidebar">
  <div class="brand">POST<span>لوحة التحكم — الإدارة</span></div>
  <nav class="nav">
    <a class="nav-item {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">↳ لوحة القيادة</a>
    <a class="nav-item {{ $active === 'products' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}#products">↳ المنتجات</a>
    <a class="nav-item {{ $active === 'orders' ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">↳ الطلبات</a>
    <a class="nav-item {{ $active === 'customers' ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">↳ العملاء</a>

    <div class="nav-eyebrow">دروب شوبينغ</div>
    <a class="nav-item {{ $active === 'suppliers' ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}">↳ الموردون</a>
    <div class="nav-item" onclick="typeof openCjModal === 'function' ? openCjModal() : (window.location = '{{ route('admin.dashboard') }}')">↳ استيراد من CJ Dropshipping</div>
    <div class="nav-item" onclick="typeof syncCjInventory === 'function' ? syncCjInventory() : (window.location = '{{ route('admin.dashboard') }}')">↳ مزامنة المخزون</div>

    <div class="nav-eyebrow">المتجر</div>
    <a class="nav-item {{ $active === 'payments' ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">↳ المدفوعات</a>
    <a class="nav-item {{ $active === 'shipping' ? 'active' : '' }}" href="{{ route('admin.shipping.index') }}">↳ الشحن</a>
    <a class="nav-item {{ $active === 'categories' ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">↳ الفئات</a>

    <div class="nav-eyebrow">الإعدادات</div>
    <a class="nav-item {{ $active === 'settings' ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">↳ الإعدادات والسوشيال ميديا</a>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:4px">
      @csrf
      <button type="submit" class="nav-item" style="background:none;border:none;width:100%;text-align:right;cursor:pointer;font:inherit">↳ تسجيل الخروج</button>
    </form>
  </nav>
  <div class="sidebar-foot">لوحة التحكم المخصصة لـ POST — تتزامن برمجياً عبر API.</div>
</aside>
