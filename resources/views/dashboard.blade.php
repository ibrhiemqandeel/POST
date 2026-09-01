@extends('admin.layout')

@section('title', 'لوحة القيادة')
@section('active', 'dashboard')
@section('page-title', 'لوحة القيادة')
@section('page-sub', 'نظرة عامة حيّة على أداء المتجر — كل الأرقام محسوبة مباشرة من قاعدة البيانات.')

@section('actions')
  <a class="btn btn-ghost" href="{{ route('admin.orders.index') }}">الطلبات</a>
  <a class="btn btn-primary" href="{{ route('admin.products.index') }}">إدارة المنتجات</a>
@endsection

@section('content')

  {{-- ============ مؤشرات الأداء (KPIs) ============ --}}
  <div class="stats">
    <div class="stat kpi">
      <div class="kpi-top">
        <div>
          <div class="stat-label">إجمالي المبيعات</div>
          <div class="stat-value">${{ number_format($stats['total_sales'], 2) }}</div>
        </div>
        <div class="kpi-icon i-forest">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
      </div>
      <div class="stat-tag">متوسط قيمة الطلب ${{ number_format($stats['avg_order_value'], 2) }}</div>
    </div>

    <div class="stat kpi">
      <div class="kpi-top">
        <div>
          <div class="stat-label">الطلبات</div>
          <div class="stat-value">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="kpi-icon i-gold">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
        </div>
      </div>
      <div class="stat-tag">{{ $stats['pending_orders'] }} قيد الانتظار</div>
    </div>

    <div class="stat kpi">
      <div class="kpi-top">
        <div>
          <div class="stat-label">المنتجات</div>
          <div class="stat-value">{{ $stats['total_products'] }}</div>
        </div>
        <div class="kpi-icon i-sky">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4v10l8 4 8-4V7z"/><path d="M4 7l8 4 8-4M12 21V11"/></svg>
        </div>
      </div>
      <div class="stat-tag" style="{{ $stats['out_of_stock'] > 0 ? 'color:var(--brick)' : '' }}">{{ $stats['out_of_stock'] }} نفد مخزونها</div>
    </div>

    <div class="stat kpi">
      <div class="kpi-top">
        <div>
          <div class="stat-label">العملاء</div>
          <div class="stat-value">{{ $stats['total_customers'] }}</div>
        </div>
        <div class="kpi-icon i-brick">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
      </div>
      <div class="stat-tag">{{ $stats['total_categories'] }} تصنيف</div>
    </div>
  </div>

  {{-- ============ رسم المبيعات (14 يوماً) — SVG خالص بلا مكتبات خارجية ============ --}}
  @php
    $vals = array_map(fn ($p) => $p['total'], $salesSeries);
    $maxVal = max($vals) ?: 1;
    $sumVal = array_sum($vals);
    $n = count($salesSeries);
    $w = 720; $h = 200; $padY = 16; $plotH = $h - $padY * 2;
    $stepX = $n > 1 ? $w / ($n - 1) : $w;
    $pts = [];
    foreach ($vals as $i => $v) {
        $x = round($i * $stepX, 2);
        $y = round($h - $padY - ($v / $maxVal) * $plotH, 2);
        $pts[] = [$x, $y];
    }
    $linePath = collect($pts)->map(fn ($p, $i) => ($i === 0 ? 'M' : 'L') . $p[0] . ',' . $p[1])->implode(' ');
    $areaPath = $linePath . ' L' . end($pts)[0] . ',' . ($h - $padY) . ' L' . $pts[0][0] . ',' . ($h - $padY) . ' Z';
  @endphp

  <div class="grid dash-2col" style="grid-template-columns:1.9fr 1fr;margin-bottom:16px">
    <div class="card chart-card">
      <div class="chart-head">
        <div>
          <h2 class="section-title">المبيعات — آخر 14 يوماً</h2>
          <div class="page-sub" style="margin-top:2px">إجمالي الفترة ${{ number_format($sumVal, 2) }}</div>
        </div>
      </div>
      <svg class="chart-svg" viewBox="0 0 {{ $w }} {{ $h }}" preserveAspectRatio="none" role="img" aria-label="رسم المبيعات">
        <defs>
          <linearGradient id="salesFill" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#3A4A3B" stop-opacity="0.22"/>
            <stop offset="100%" stop-color="#3A4A3B" stop-opacity="0"/>
          </linearGradient>
        </defs>
        <path d="{{ $areaPath }}" fill="url(#salesFill)"/>
        <path d="{{ $linePath }}" fill="none" stroke="#3A4A3B" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>
        @foreach($pts as $i => $p)
          <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="{{ $vals[$i] > 0 ? 3 : 0 }}" fill="#3A4A3B"/>
        @endforeach
      </svg>
      <div class="chart-xlabels">
        <span>{{ $salesSeries[0]['label'] }}</span>
        <span>{{ $salesSeries[intdiv($n, 2)]['label'] }}</span>
        <span>{{ $salesSeries[$n - 1]['label'] }}</span>
      </div>
    </div>

    {{-- توزيع حالات الطلبات --}}
    <div class="card card-pad">
      <h2 class="section-title" style="margin-bottom:16px">حالات الطلبات</h2>
      @php($maxStatus = collect($statusBreakdown)->max('value') ?: 1)
      <div style="display:flex;flex-direction:column;gap:14px">
        @foreach($statusBreakdown as $s)
          <div>
            <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:5px">
              <span>{{ $s['label'] }}</span>
              <strong>{{ $s['value'] }}</strong>
            </div>
            <div style="height:8px;background:var(--paper);border-radius:6px;overflow:hidden">
              <span class="badge {{ $s['class'] }}" style="display:block;height:100%;width:{{ round(($s['value'] / $maxStatus) * 100) }}%;border-radius:6px;padding:0"></span>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- ============ أحدث الطلبات + تنبيهات المخزون ============ --}}
  <div class="grid dash-2col" style="grid-template-columns:1.9fr 1fr;margin-bottom:16px">
    <div class="card">
      <div class="card-pad" style="padding-bottom:6px;display:flex;justify-content:space-between;align-items:baseline">
        <h2 class="section-title">أحدث الطلبات</h2>
        <a href="{{ route('admin.orders.index') }}" style="font-size:12.5px;color:var(--forest);font-weight:600;text-decoration:none">عرض الكل ←</a>
      </div>
      <div class="table-wrap" style="border:none;box-shadow:none;border-radius:0">
        <table>
          <thead><tr><th>#</th><th>العميل</th><th>الإجمالي</th><th>الحالة</th></tr></thead>
          <tbody>
            @forelse($recentOrders as $order)
              <tr onclick="window.location='{{ route('admin.orders.index') }}'" style="cursor:pointer">
                <td>#{{ $order->id }}</td>
                <td>
                  <div class="prod-name">{{ $order->shipping_name ?? $order->user?->name ?? 'زائر' }}</div>
                  <div class="prod-cat">{{ $order->created_at?->format('Y/m/d') }}</div>
                </td>
                <td class="price-cell">${{ number_format($order->total, 2) }}</td>
                <td>
                  @php($cls = $order->status === 'cancelled' ? 'badge-out' : ($order->status === 'pending' ? 'badge-pending' : ($order->status === 'processing' ? 'badge-info' : 'badge-synced')))
                  <span class="badge {{ $cls }}"><span class="dot"></span>{{ $order->status }}</span>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="prod-cat" style="padding:26px;text-align:center">لا توجد طلبات بعد.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card card-pad">
      <h2 class="section-title" style="margin-bottom:6px">تنبيهات المخزون</h2>
      <div class="page-sub" style="margin-bottom:14px">منتجات على وشك النفاد (≤ 5 قطع).</div>
      @forelse($lowStock as $p)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid var(--line)">
          <div style="min-width:0">
            <div class="prod-name" style="font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px">{{ $p->name }}</div>
            <div class="prod-cat">${{ number_format($p->price, 2) }}</div>
          </div>
          <span class="badge {{ $p->stock <= 2 ? 'badge-out' : 'badge-pending' }}"><span class="dot"></span>{{ $p->stock }} متبقٍ</span>
        </div>
      @empty
        <div class="prod-cat" style="padding:16px 0">كل المنتجات بمخزون صحّي ✓</div>
      @endforelse
      @if($stats['out_of_stock'] > 0)
        <a href="{{ route('admin.products.index') }}" style="display:inline-block;margin-top:12px;font-size:12.5px;color:var(--brick);font-weight:600;text-decoration:none">{{ $stats['out_of_stock'] }} منتج نفد تماماً — إدارة ←</a>
      @endif
    </div>
  </div>

  {{-- ============ الأكثر مبيعاً ============ --}}
  <div class="card">
    <div class="card-pad" style="padding-bottom:6px">
      <h2 class="section-title">المنتجات الأكثر مبيعاً</h2>
    </div>
    <div class="table-wrap" style="border:none;box-shadow:none;border-radius:0">
      <table>
        <thead><tr><th>المنتج</th><th>الكمية المباعة</th><th>الإيراد</th></tr></thead>
        <tbody>
          @forelse($bestSellers as $b)
            <tr>
              <td class="prod-name">{{ $b->product_name }}</td>
              <td>{{ (int) $b->total_sold }}</td>
              <td class="price-cell">${{ number_format((float) $b->revenue, 2) }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="prod-cat" style="padding:26px;text-align:center">لا توجد مبيعات بعد.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

@endsection
