@extends('admin.layout')

@section('title', 'إعدادات الشحن')
@section('active', 'shipping')
@section('page-title', 'إعدادات الشحن')
@section('page-sub', 'نظام شحن قابل للتوسّع — سعر ثابت أو حساب حسب كل مورد.')

@section('content')

  <form method="POST" action="{{ route('admin.shipping.update') }}">
    @csrf
    <div class="card card-pad" style="margin-bottom:22px">
      <div class="form-grid">
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

@endsection
