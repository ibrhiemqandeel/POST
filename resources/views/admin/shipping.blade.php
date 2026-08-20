<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>POST — إعدادات الشحن</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
@include('admin.partials.style')
</head>
<body>

<div class="app">
  @include('admin.partials.sidebar', ['active' => 'shipping'])

  <main class="main">
    <div class="topbar">
      <div>
        <h1 class="page-title">إعدادات الشحن</h1>
        <div class="page-sub">نظام شحن قابل للتوسّع — سعر ثابت أو حساب حسب كل مورد.</div>
      </div>
    </div>

    @if(session('success'))
      <div style="background:var(--forest-dim);color:var(--forest);border:1px solid var(--forest);border-radius:9px;padding:12px 16px;margin-bottom:18px;font-weight:600">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.shipping.update') }}">
      @csrf
      <div class="table-wrap" style="padding:22px 24px;margin-bottom:22px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="field full">
            <label>نمط الشحن</label>
            <select name="shipping_mode">
              <option value="flat" @selected(($settings['shipping_mode'] ?? 'flat') === 'flat')>سعر ثابت للمتجر</option>
              <option value="per_supplier" @selected(($settings['shipping_mode'] ?? '') === 'per_supplier')>حسب المورد (يجمع شحن كل مورد)</option>
            </select>
          </div>
          <div class="field">
            <label>سعر الشحن الثابت ($)</label>
            <input name="shipping_flat_rate" type="number" min="0" step="0.01" value="{{ old('shipping_flat_rate', $settings['shipping_flat_rate'] ?? '0') }}">
          </div>
          <div class="field">
            <label>حد الشحن المجاني ($) — 0 لتعطيله</label>
            <input name="shipping_free_threshold" type="number" min="0" step="0.01" value="{{ old('shipping_free_threshold', $settings['shipping_free_threshold'] ?? '0') }}">
          </div>
        </div>
      </div>
      <div class="btn-group"><button type="submit" class="btn btn-primary">💾 حفظ إعدادات الشحن</button></div>
    </form>

    <div class="table-wrap" style="margin-top:26px">
      <table>
        <thead>
          <tr><th>المورد</th><th>الدولة</th><th>تكلفة الشحن الافتراضية</th><th>مدة الشحن</th></tr>
        </thead>
        <tbody>
          @forelse($suppliers as $s)
            <tr>
              <td class="prod-name">{{ $s->name }}</td>
              <td>{{ $s->country ?? '—' }}</td>
              <td class="price-cell">${{ number_format((float) $s->default_shipping_cost, 2) }}</td>
              <td>{{ $s->shippingRange() ? $s->shippingRange().' يوم' : '—' }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="prod-cat" style="padding:20px">لا يوجد موردون. أضِفهم من صفحة الموردين لتفعيل الشحن حسب المورد.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </main>
</div>

<script>
function toggleSidebar(){ document.getElementById('sidebar').classList.toggle('open'); }
</script>
</body>
</html>
