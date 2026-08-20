<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>POST — المدفوعات</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
@include('admin.partials.style')
</head>
<body>

<div class="app">
  @include('admin.partials.sidebar', ['active' => 'payments'])

  <main class="main">
    <div class="topbar">
      <div>
        <h1 class="page-title">المدفوعات</h1>
        <div class="page-sub">طرق الدفع المفعّلة ونظرة عامة على مدفوعات الطلبات.</div>
      </div>
    </div>

    @if(session('success'))
      <div style="background:var(--forest-dim);color:var(--forest);border:1px solid var(--forest);border-radius:9px;padding:12px 16px;margin-bottom:18px;font-weight:600">{{ session('success') }}</div>
    @endif

    <div class="stats" style="margin-bottom:26px">
      <div class="stat">
        <div class="stat-label">مدفوعات محصّلة</div>
        <div class="stat-value">${{ number_format($paidTotal, 2) }}</div>
      </div>
      <div class="stat">
        <div class="stat-label">مستحقات غير مدفوعة</div>
        <div class="stat-value">${{ number_format($unpaidTotal, 2) }}</div>
      </div>
    </div>

    <form method="POST" action="{{ route('admin.payments.update') }}">
      @csrf
      <div class="table-wrap" style="padding:22px 24px;margin-bottom:26px">
        <h2 style="font-family:'Fraunces',serif;font-weight:500;font-size:20px;margin:0 0 16px">طرق الدفع</h2>
        <label style="display:flex;align-items:center;gap:10px;margin-bottom:12px;cursor:pointer">
          <input type="checkbox" name="payment_cod_enabled" value="1" @checked(($settings['payment_cod_enabled'] ?? '1') !== '0')>
          <span>الدفع عند الاستلام (Cash on Delivery)</span>
        </label>
        <label style="display:flex;align-items:center;gap:10px;margin-bottom:12px;cursor:pointer">
          <input type="checkbox" name="payment_bank_transfer_enabled" value="1" @checked(($settings['payment_bank_transfer_enabled'] ?? '0') === '1')>
          <span>تحويل بنكي</span>
        </label>
        <div class="field full" style="margin-top:8px">
          <label>تفاصيل الحساب البنكي (تظهر للعميل عند اختيار التحويل)</label>
          <textarea name="payment_bank_details" rows="3" style="padding:10px 12px;border:1px solid var(--line);border-radius:7px;font-family:'Inter';font-size:13.5px;background:#fff">{{ old('payment_bank_details', $settings['payment_bank_details'] ?? '') }}</textarea>
        </div>
      </div>
      <div class="btn-group" style="margin-bottom:30px"><button type="submit" class="btn btn-primary">💾 حفظ إعدادات الدفع</button></div>
    </form>

    <h2 style="font-family:'Fraunces',serif;font-weight:500;font-size:20px;margin:0 0 14px">أحدث المدفوعات</h2>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>#</th><th>العميل</th><th>الإجمالي</th><th>طريقة الدفع</th><th>حالة الدفع</th><th>حالة الطلب</th><th>التاريخ</th></tr>
        </thead>
        <tbody>
          @forelse($recentPayments as $p)
            <tr>
              <td>#{{ $p['id'] }}</td>
              <td class="prod-name">{{ $p['customer'] }}</td>
              <td class="price-cell">${{ number_format($p['total'], 2) }}</td>
              <td>{{ $methods[$p['payment_method']] ?? $p['payment_method'] }}</td>
              <td>
                <span class="badge {{ $p['payment_status'] === 'paid' ? 'badge-synced' : 'badge-pending' }}">
                  <span class="dot"></span>{{ $p['payment_status'] === 'paid' ? 'مدفوع' : 'غير مدفوع' }}
                </span>
              </td>
              <td class="prod-cat">{{ $p['status'] }}</td>
              <td class="prod-cat">{{ $p['date'] }}</td>
            </tr>
          @empty
            <tr><td colspan="7" class="prod-cat" style="padding:20px">لا توجد مدفوعات بعد.</td></tr>
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
