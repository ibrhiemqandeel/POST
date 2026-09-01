@extends('admin.layout')

@section('title', 'المدفوعات')
@section('active', 'payments')
@section('page-title', 'المدفوعات')
@section('page-sub', 'طرق الدفع المفعّلة ونظرة عامة على مدفوعات الطلبات.')

@section('content')

  <div class="stats" style="grid-template-columns:repeat(2,1fr);margin-bottom:26px">
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
    <div class="card card-pad" style="margin-bottom:22px">
      <h2 class="section-title" style="margin-bottom:16px">طرق الدفع</h2>
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
        <textarea name="payment_bank_details" rows="3">{{ old('payment_bank_details', $settings['payment_bank_details'] ?? '') }}</textarea>
      </div>
    </div>
    <div class="btn-group" style="margin-bottom:30px"><button type="submit" class="btn btn-primary">💾 حفظ إعدادات الدفع</button></div>
  </form>

  <h2 class="section-title" style="margin:0 0 14px">أحدث المدفوعات</h2>
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

@endsection
