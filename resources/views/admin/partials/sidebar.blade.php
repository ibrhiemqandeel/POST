{{--
  الشريط الجانبي الموحّد لكل صفحات لوحة التحكم.
  يستقبل متغيّر $active من الـ layout (مصدره @section('active') في كل صفحة):
  'dashboard' | 'products' | 'orders' | 'customers' | 'suppliers'
  | 'payments' | 'shipping' | 'categories' | 'settings'
--}}
@php($active = $active ?? 'dashboard')
@php($adminName = auth()->user()?->name ?? 'الأدمن')

<div class="mobile-nav">
  <div class="brand">POST <span>Admin</span></div>
  <button onclick="toggleSidebar()" aria-label="القائمة">☰</button>
</div>

<aside class="sidebar" id="sidebar">
  <a href="{{ route('admin.dashboard') }}" class="brand" style="text-decoration:none">POST<span>لوحة التحكم — الإدارة</span></a>

  <nav class="nav">
    <a class="nav-item {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
      لوحة القيادة
    </a>
    <a class="nav-item {{ $active === 'products' ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4v10l8 4 8-4V7z"/><path d="M4 7l8 4 8-4M12 21V11"/></svg>
      المنتجات
    </a>
    <a class="nav-item {{ $active === 'orders' ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
      الطلبات
    </a>
    <a class="nav-item {{ $active === 'customers' ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      العملاء
    </a>

    <div class="nav-eyebrow">دروب شوبينغ</div>
    <a class="nav-item {{ $active === 'suppliers' ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      الموردون
    </a>
    <a class="nav-item" href="{{ route('admin.products.index') }}?import=1">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5M12 15V3"/></svg>
      استيراد من CJ Dropshipping
    </a>

    <div class="nav-eyebrow">المتجر</div>
    <a class="nav-item {{ $active === 'payments' ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
      المدفوعات
    </a>
    <a class="nav-item {{ $active === 'shipping' ? 'active' : '' }}" href="{{ route('admin.shipping.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      الشحن
    </a>
    <a class="nav-item {{ $active === 'categories' ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      الفئات
    </a>

    <div class="nav-eyebrow">الإعدادات</div>
    <a class="nav-item {{ $active === 'settings' ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      الإعدادات والسوشيال ميديا
    </a>

    <a class="nav-item" href="{{ url('/') }}" target="_blank">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6M10 14L21 3"/></svg>
      عرض المتجر
    </a>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:2px">
      @csrf
      <button type="submit" class="nav-item" style="background:none;border:none;width:100%;font:inherit;color:#C4C0B1">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
        تسجيل الخروج
      </button>
    </form>
  </nav>

  <div class="sidebar-user">
    <div class="avatar">{{ mb_substr($adminName, 0, 1) }}</div>
    <div>
      <div class="u-name">{{ $adminName }}</div>
      <div class="u-role">حساب إداري</div>
    </div>
  </div>
</aside>
