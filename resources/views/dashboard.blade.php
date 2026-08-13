<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>POST — لوحة التحكم الإحترافية</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{
    --paper:#F4F1E9;
    --panel:#FDFCF9;
    --ink:#211F1A;
    --ink-soft:#5C594E;
    --line:#DEDACC;
    --forest:#3A4A3B;
    --forest-dim:#EDF0E7;
    --brick:#8A4A34;
    --brick-dim:#F4E9E3;
    --danger:#A23B2E;
    --gold:#B08A3E;
  }
  *{box-sizing:border-box;}
  body{margin:0;background:var(--paper);color:var(--ink);font-family:'Inter',sans-serif;}
  .app{display:grid;grid-template-columns:240px 1fr;min-height:100vh;}

  /* Mobile Header */
  .mobile-nav{display:none;background:var(--ink);color:#fff;padding:12px 20px;justify-content:space-between;align-items:center;}
  .mobile-nav button{background:none;border:none;color:#fff;font-size:20px;cursor:pointer;}

  /* Sidebar */
  .sidebar{background:var(--ink);color:#EFECE2;padding:28px 20px;display:flex;flex-direction:column;gap:28px;transition:0.3s;}
  .brand{font-family:'Fraunces',serif;font-size:26px;letter-spacing:0.04em;font-weight:500;}
  .brand span{display:block;font-family:'Inter',sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#9C9787;margin-top:4px;font-weight:500;}
  .nav{display:flex;flex-direction:column;gap:2px;margin-top:8px;}
  .nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:6px;font-size:14px;color:#C9C5B6;cursor:pointer;transition:.15s;text-decoration:none;}
  .nav-item:hover{background:#2E2C25;color:#fff;}
  .nav-item.active{background:#EFECE2;color:var(--ink);font-weight:600;}
  .nav-eyebrow{font-size:10px;letter-spacing:0.14em;text-transform:uppercase;color:#8A8672;margin:14px 0 2px 4px;}
  .sidebar-foot{margin-top:auto;font-size:11px;color:#847F6C;line-height:1.6;border-top:1px solid #35322A;padding-top:16px;}

  /* Main */
  .main{padding:32px 40px;max-width:1200px;width:100%;}
  .topbar{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:28px;gap:20px;flex-wrap:wrap;}
  .page-title{font-family:'Fraunces',serif;font-size:32px;font-weight:500;margin:0;}
  .page-sub{color:var(--ink-soft);font-size:14px;margin-top:4px;}
  .btn-group{display:flex;gap:10px;}
  .btn{font-family:'Inter';font-size:13.5px;font-weight:600;border:none;border-radius:7px;padding:11px 18px;cursor:pointer;transition:.15s;display:inline-flex;align-items:center;gap:8px;}
  .btn-primary{background:var(--forest);color:#fff;}
  .btn-primary:hover{background:#2D3A2E;}
  .btn-ghost{background:transparent;border:1px solid var(--line);color:var(--ink);}
  .btn-ghost:hover{border-color:var(--ink);background:#EAE6DA;}

  /* Stat cards */
  .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:30px;}
  .stat{background:var(--panel);border:1px solid var(--line);border-radius:10px;padding:18px 20px;}
  .stat-label{font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:var(--ink-soft);margin-bottom:8px;}
  .stat-value{font-family:'Fraunces',serif;font-size:26px;font-weight:500;}
  .stat-tag{font-size:11px;color:var(--forest);margin-top:6px;font-weight:600;}

  /* Toolbar */
  .toolbar{display:flex;gap:10px;align-items:center;margin-bottom:16px;flex-wrap:wrap;}
  .search{flex:1;min-width:200px;}
  .search input{width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:7px;background:var(--panel);font-family:'Inter';font-size:13.5px;}
  select.filter{padding:10px 12px;border:1px solid var(--line);border-radius:7px;background:var(--panel);font-family:'Inter';font-size:13.5px;color:var(--ink);}

  /* Table */
  .table-wrap{background:var(--panel);border:1px solid var(--line);border-radius:12px;overflow-x:auto;}
  table{width:100%;border-collapse:collapse;min-width:700px;}
  thead th{text-align:right;font-size:10.5px;letter-spacing:0.09em;text-transform:uppercase;color:var(--ink-soft);padding:14px 16px;border-bottom:1px solid var(--line);font-weight:600;}
  tbody td{padding:14px 16px;border-bottom:1px solid var(--line);font-size:13.5px;vertical-align:middle;}
  tbody tr:last-child td{border-bottom:none;}
  tbody tr:hover{background:#FAF8F2;}
  .prod-cell{display:flex;align-items:center;gap:12px;}
  .thumb{width:44px;height:44px;border-radius:6px;object-fit:cover;background:var(--line);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:11px;color:#8A8672;font-weight:600;overflow:hidden;}
  .thumb img{width:100%;height:100%;object-fit:cover;}
  .prod-name{font-weight:600;}
  .prod-cat{font-size:11.5px;color:var(--ink-soft);}
  .badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;}
  .badge-synced{background:var(--forest-dim);color:var(--forest);}
  .badge-out{background:#F4E3E0;color:var(--danger);}
  .badge-pending{background:#F7EFDD;color:var(--gold);}
  .dot{width:6px;height:6px;border-radius:50%;background:currentColor;}
  .price-cell{font-family:'IBM Plex Mono',monospace;font-size:13px;}
  .margin{font-family:'IBM Plex Mono',monospace;font-size:12.5px;padding:3px 8px;border-radius:5px;background:var(--forest-dim);color:var(--forest);font-weight:500;}
  .margin.low{background:var(--brick-dim);color:var(--brick);}
  .row-actions{display:flex;gap:6px;}
  .icon-btn{width:32px;height:32px;border-radius:6px;border:1px solid var(--line);background:var(--panel);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.15s;font-size:13px;}
  .icon-btn:hover{border-color:var(--ink);}
  .icon-btn.danger:hover{border-color:var(--danger);background:var(--brick-dim);color:var(--danger);}
  .supplier-src{font-size:11.5px;color:var(--ink-soft);display:flex;align-items:center;gap:5px;}
  .empty-state{padding:60px 20px;text-align:center;color:var(--ink-soft);}
  .empty-state h3{font-family:'Fraunces',serif;font-size:20px;color:var(--ink);margin-bottom:6px;font-weight:500;}

  /* Modal */
  .overlay{position:fixed;inset:0;background:rgba(33,31,26,0.5);display:none;align-items:center;justify-content:center;z-index:50;padding:20px;}
  .overlay.open{display:flex;}
  .modal{background:var(--panel);border-radius:14px;width:100%;max-width:640px;max-height:90vh;overflow-y:auto;padding:0;box-shadow:0 10px 30px rgba(0,0,0,0.15);}
  .modal-head{padding:24px 28px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;}
  .modal-head h2{font-family:'Fraunces',serif;font-weight:500;font-size:22px;margin:0;}
  .modal-close{background:none;border:none;font-size:22px;cursor:pointer;color:var(--ink-soft);line-height:1;}
  .modal-body{padding:24px 28px;display:grid;grid-template-columns:1fr 1fr;gap:16px;}
  .field{display:flex;flex-direction:column;gap:6px;}
  .field.full{grid-column:1/-1;}
  .field label{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--ink-soft);}
  .field input,.field select{padding:10px 12px;border:1px solid var(--line);border-radius:7px;font-family:'Inter';font-size:13.5px;background:#fff;}
  .field input:focus,.field select:focus{outline:2px solid var(--forest);outline-offset:1px;border-color:var(--forest);}
  .margin-preview{grid-column:1/-1;background:var(--forest-dim);border-radius:9px;padding:14px 16px;display:flex;justify-content:space-between;align-items:center;font-size:13px;}
  .margin-preview.low{background:var(--brick-dim);}
  .margin-preview .amt{font-family:'Fraunces',serif;font-size:22px;font-weight:500;}
  .modal-foot{padding:20px 28px;border-top:1px solid var(--line);display:flex;justify-content:flex-end;gap:10px;}
  .section-note{grid-column:1/-1;font-size:11.5px;color:var(--ink-soft);background:var(--paper);border:1px dashed var(--line);border-radius:8px;padding:10px 12px;margin-top:-4px;}

  ::-webkit-scrollbar{width:8px;height:8px;}
  ::-webkit-scrollbar-thumb{background:var(--line);border-radius:8px;}

  @media (max-width:860px){
    .app{grid-template-columns:1fr;}
    .mobile-nav{display:flex;}
    .sidebar{display:none;position:fixed;inset:0;z-index:99;width:260px;}
    .sidebar.open{display:flex;}
    .stats{grid-template-columns:1fr 1fr;}
    .modal-body{grid-template-columns:1fr;}
    .main{padding:20px;}
  }
</style>
</head>
<body>

<div class="app">
  @include('admin.partials.sidebar', ['active' => 'products'])

  <main class="main">
    {{-- ============================================================
         نظرة عامة على المتجر — أرقام حقيقية من قاعدة البيانات
         (وليست بيانات وهمية)، تُحسب في DashboardController::index().
         ============================================================ --}}
    <div class="topbar">
      <div>
        <h1 class="page-title">نظرة عامة</h1>
        <div class="page-sub">أداء المتجر الحقيقي من قاعدة البيانات، محدَّث لحظياً.</div>
      </div>
    </div>

    <div class="stats">
      <div class="stat">
        <div class="stat-label">المنتجات</div>
        <div class="stat-value">{{ $stats['total_products'] }}</div>
        <div class="stat-tag">{{ $stats['out_of_stock'] }} نفد مخزونها</div>
      </div>
      <div class="stat">
        <div class="stat-label">الطلبات</div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
        <div class="stat-tag">{{ $stats['pending_orders'] }} قيد الانتظار</div>
      </div>
      <div class="stat">
        <div class="stat-label">العملاء</div>
        <div class="stat-value">{{ $stats['total_customers'] }}</div>
        <div class="stat-tag">{{ $stats['total_categories'] }} تصنيف</div>
      </div>
      <div class="stat">
        <div class="stat-label">إجمالي المبيعات</div>
        <div class="stat-value">${{ number_format($stats['total_sales'], 2) }}</div>
        <div class="stat-tag">{{ $stats['completed_orders'] }} طلب مكتمل</div>
      </div>
    </div>

    @if($recentOrders->isNotEmpty() || $bestSellers->isNotEmpty())
      <div class="toolbar" style="margin-top:4px">
        <div class="table-wrap" style="flex:1">
          <table>
            <thead>
              <tr><th colspan="4" style="font-family:'Fraunces',serif;font-size:15px;text-transform:none;letter-spacing:0;color:var(--ink);font-weight:500;padding-bottom:10px">أحدث الطلبات</th></tr>
            </thead>
            <tbody>
              @forelse($recentOrders as $order)
                <tr>
                  <td>#{{ $order->id }}</td>
                  <td>{{ $order->shipping_name ?? $order->user?->name ?? 'زائر' }}</td>
                  <td class="price-cell">${{ number_format($order->total, 2) }}</td>
                  <td><span class="badge {{ $order->status === 'cancelled' ? 'badge-out' : ($order->status === 'pending' ? 'badge-pending' : 'badge-synced') }}">{{ $order->status }}</span></td>
                </tr>
              @empty
                <tr><td colspan="4" class="prod-cat">لا توجد طلبات بعد.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    @endif

    <div class="topbar" style="margin-top:30px">
      <div>
        <h1 class="page-title">إدارة المنتجات</h1>
        <div class="page-sub" id="prodCount">— منتج في الكتالوج</div>
      </div>
      <div class="btn-group">
        <button class="btn btn-ghost" onclick="exportJSON()">📥 تصدير JSON</button>
        <button class="btn btn-ghost" onclick="openCjModal()">🔗 استيراد من CJ Dropshipping</button>
        <button class="btn btn-primary" onclick="openModal()">+ إضافة منتج جديد</button>
      </div>
    </div>

    <div class="stats" id="statsRow"></div>

    <div class="toolbar">
      <div class="search">
        <input id="searchInput" placeholder="ابحث باسم المنتج، الفئة أو المورد…" oninput="render()">
      </div>
      <select class="filter" id="catFilter" onchange="render()">
        <option value="">كل الفئات</option>
        <option value="women">نساء</option>
        <option value="kids">أطفال</option>
        <option value="beauty">تجميل</option>
        <option value="accessories">إكسسوارات</option>
      </select>
      <select class="filter" id="statusFilter" onchange="render()">
        <option value="">كل الحالات</option>
        <option value="synced">متزامن</option>
        <option value="pending">قيد المزامنة</option>
        <option value="out">نفد المخزون</option>
      </select>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>المنتج</th>
            <th>المورد / المنصة</th>
            <th>سعر التكلفة</th>
            <th>سعر البيع</th>
            <th>هامش الربح</th>
            <th>الحالة</th>
            <th>إجراءات</th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
      <div id="emptyState" class="empty-state" style="display:none">
        <h3>لا توجد منتجات مطابقة</h3>
        <div>جرّب تغيير عبارة البحث أو الفلاتر، أو أضف منتجاً جديداً.</div>
      </div>
    </div>
  </main>
</div>

<!-- Modal Edit/Create -->
<div class="overlay" id="overlay">
  <div class="modal">
    <div class="modal-head">
      <h2 id="modalTitle">إضافة منتج (دروب شوبينغ)</h2>
      <button class="modal-close" onclick="closeModal()">×</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="f_id">
      <div class="field full">
        <label>اسم المنتج</label>
        <input id="f_name" placeholder="مثال: فستان كتان — طبعة كومو">
      </div>
      <div class="field">
        <label>الفئة</label>
        <select id="f_cat">
          <option value="women">نساء</option>
          <option value="kids">أطفال</option>
          <option value="beauty">تجميل</option>
          <option value="accessories">إكسسوارات</option>
        </select>
      </div>
      <div class="field">
        <label>رابط صورة المنتج (URL)</label>
        <input id="f_img" placeholder="https://example.com/image.jpg">
      </div>
      <div class="field">
        <label>منصة المورد</label>
        <select id="f_platform">
          <option>Syncee</option>
          <option>CJ Dropshipping</option>
          <option>Spocket</option>
          <option>AliExpress</option>
          <option>مورد محلي</option>
        </select>
      </div>
      <div class="field">
        <label>اسم المورد</label>
        <input id="f_supplier" placeholder="مثال: Como Textiles Co.">
      </div>
      <div class="field">
        <label>سعر التكلفة (المورد $)</label>
        <input id="f_cost" type="number" min="0" step="0.01" placeholder="0.00" oninput="updateMarginPreview()">
      </div>
      <div class="field">
        <label>سعر البيع (للزبون $)</label>
        <input id="f_price" type="number" min="0" step="0.01" placeholder="0.00" oninput="updateMarginPreview()">
      </div>
      <div class="field">
        <label>المخزون المتاح</label>
        <input id="f_stock" type="number" min="0" placeholder="0" oninput="autoAdjustStatus()">
      </div>
      <div class="field">
        <label>حالة المزامنة</label>
        <select id="f_status">
          <option value="synced">متزامن</option>
          <option value="pending">قيد المزامنة</option>
          <option value="out">نفد المخزون</option>
        </select>
      </div>

      <div class="margin-preview" id="marginPreview">
        <span>هامش الربح المتوقع</span>
        <span class="amt" id="marginAmt">—</span>
      </div>
      <div class="section-note">تنبيه: يتزامن المخزون والتكلفة آلياً عند الربط المباشر عبر API من المنصات المعتمدة.</div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal()">إلغاء</button>
      <button class="btn btn-primary" onclick="saveProduct()">حفظ البيانات</button>
    </div>
  </div>
</div>

<!-- Modal: استيراد من CJ Dropshipping -->
<div class="overlay" id="cjOverlay">
  <div class="modal" style="max-width:820px">
    <div class="modal-head">
      <h2>استيراد منتجات من CJ Dropshipping</h2>
      <button class="modal-close" onclick="closeCjModal()">×</button>
    </div>
    <div class="modal-body" style="grid-template-columns:1fr;gap:14px">
      <div class="section-note" id="cjNote">
        يتطلب هذا القسم ضبط بيانات CJ_EMAIL وCJ_API_KEY في إعدادات الخادم. اختر الفئة وهامش الربح قبل الاستيراد، ويمكنك تعديل أي قيمة (السعر، الصورة، الفئة...) لاحقاً بحرية من زر "تعديل" في الجدول الرئيسي.
      </div>
      <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
        <div class="field" style="flex:1;min-width:140px">
          <label>الفئة عند الاستيراد</label>
          <select id="cjCategory">
            <option value="women">نساء</option>
            <option value="kids">أطفال</option>
            <option value="beauty">تجميل</option>
            <option value="accessories">إكسسوارات</option>
          </select>
        </div>
        <div class="field" style="flex:1;min-width:120px">
          <label>هامش الربح %</label>
          <input id="cjMargin" type="number" min="0" step="1" value="30">
        </div>
        <button class="btn btn-primary" onclick="loadCjProducts(1)">تحميل منتجات CJ</button>
      </div>
      <div id="cjList" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;max-height:420px;overflow-y:auto"></div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeCjModal()">إغلاق</button>
    </div>
  </div>
</div>

<script>
/* =================================================================
   لوحة التحكم أصبحت متصلة فعلياً بقاعدة البيانات عبر /admin/products
   (App\Http\Controllers\Admin\ProductController) بدل العمل على مصفوفة
   JS محلية غير محفوظة كما كانت سابقاً. أي إضافة/تعديل/حذف هنا يُطبَّق
   فوراً على نفس قاعدة البيانات التي تعرضها صفحات Women/Kids/Beauty/
   Accessories في الموقع.
   ================================================================= */
let products = [];

const CATEGORY_LABELS = { women: 'نساء', kids: 'أطفال', beauty: 'تجميل', accessories: 'إكسسوارات' };

function csrfToken(){
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function apiRequest(url, options = {}){
  return fetch(url, {
    ...options,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrfToken(),
      ...(options.headers || {})
    }
  }).then(async r => {
    const data = await r.json().catch(() => ({}));
    if(!r.ok || data.success === false){
      throw new Error(data.message || 'حدث خطأ غير متوقع.');
    }
    return data;
  });
}

// يمنع XSS عند عرض بيانات قد تكون غير موثوقة (مثلاً أسماء منتجات آتية من
// CJ Dropshipping، وهي API خارجي لا نتحكم بمحتواه) داخل innerHTML.
function escapeHtml(str){
  const div = document.createElement('div');
  div.textContent = str ?? '';
  return div.innerHTML;
}

function escapeAttr(str){
  return escapeHtml(str).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

function loadProducts(){
  return apiRequest('{{ route("admin.products.index") }}')
    .then(data => {
      products = data.products || [];
      render();
    })
    .catch(err => {
      document.getElementById('tbody').innerHTML = '';
      document.getElementById('emptyState').style.display = 'block';
      document.getElementById('emptyState').querySelector('h3').textContent = 'تعذّر تحميل المنتجات';
      document.getElementById('emptyState').querySelector('div').textContent = err.message;
    });
}

function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('open');
}

function marginPct(cost, price){
  if(!price || price <= 0 || price < cost) return 0;
  return Math.round(((price - cost) / price) * 100);
}

function statusBadge(status){
  const map = {
    synced: {cls:"badge-synced", label:"متزامن"},
    pending: {cls:"badge-pending", label:"قيد المزامنة"},
    out: {cls:"badge-out", label:"نفد المخزون"},
  };
  const s = map[status] || map.synced;
  return `<span class="badge ${s.cls}"><span class="dot"></span>${s.label}</span>`;
}

function render(){
  const q = document.getElementById('searchInput').value.trim().toLowerCase();
  const catF = document.getElementById('catFilter').value;
  const statusF = document.getElementById('statusFilter').value;

  const filtered = products.filter(p=>{
    const matchQ = !q || p.name.toLowerCase().includes(q) || p.supplier.toLowerCase().includes(q) || p.platform.toLowerCase().includes(q);
    const matchCat = !catF || p.cat === catF;
    const matchStatus = !statusF || p.status === statusF;
    return matchQ && matchCat && matchStatus;
  });

  const tbody = document.getElementById('tbody');
  const empty = document.getElementById('emptyState');
  document.getElementById('prodCount').textContent = `${products.length} منتج في الكتالوج`;

  if(filtered.length === 0){
    tbody.innerHTML = '';
    empty.style.display = 'block';
  } else {
    empty.style.display = 'none';
    tbody.innerHTML = filtered.map(p=>{
      const m = marginPct(p.cost, p.price);
      const safeName = escapeHtml(p.name);
      const initials = escapeHtml(p.name.split(' ')[0].slice(0,2));
      const imgContent = p.img
        ? `<img src="${escapeAttr(p.img)}" alt="${escapeAttr(p.name)}" onerror="this.onerror=null; this.parentNode.innerText=${JSON.stringify(p.name.split(' ')[0].slice(0,2))};">`
        : initials;

      return `
      <tr>
        <td>
          <div class="prod-cell">
            <div class="thumb">${imgContent}</div>
            <div>
              <div class="prod-name">${safeName}</div>
              <div class="prod-cat">${escapeHtml(CATEGORY_LABELS[p.cat] || p.cat)}</div>
            </div>
          </div>
        </td>
        <td>
          <div class="supplier-src">${escapeHtml(p.platform)} · ${escapeHtml(p.supplier)}</div>
        </td>
        <td class="price-cell">$${p.cost.toFixed(2)}</td>
        <td class="price-cell">$${p.price.toFixed(2)}</td>
        <td><span class="margin ${m < 30 ? 'low' : ''}">${m}%</span></td>
        <td>${statusBadge(p.status)}</td>
        <td>
          <div class="row-actions">
            <button class="icon-btn" title="تعديل" onclick="editProduct(${p.id})">✏️</button>
            <button class="icon-btn danger" title="حذف" onclick="deleteProduct(${p.id})">✕</button>
          </div>
        </td>
      </tr>`;
    }).join('');
  }

  renderStats();
}

function renderStats(){
  const total = products.length;
  const synced = products.filter(p=>p.status === 'synced').length;
  const out = products.filter(p=>p.status === 'out').length;
  const avgMargin = total ? Math.round(products.reduce((s,p)=>s + marginPct(p.cost, p.price), 0) / total) : 0;

  document.getElementById('statsRow').innerHTML = `
    <div class="stat">
      <div class="stat-label">إجمالي المنتجات</div>
      <div class="stat-value">${total}</div>
      <div class="stat-tag">عبر ${new Set(products.map(p=>p.platform)).size} موردين</div>
    </div>
    <div class="stat">
      <div class="stat-label">متزامن مع المورد</div>
      <div class="stat-value">${synced}</div>
      <div class="stat-tag">جاهز للبيع</div>
    </div>
    <div class="stat">
      <div class="stat-label">نفد المخزون</div>
      <div class="stat-value">${out}</div>
      <div class="stat-tag" style="color:var(--brick)">يحتاج متابعة</div>
    </div>
    <div class="stat">
      <div class="stat-label">متوسط هامش الربح</div>
      <div class="stat-value">${avgMargin}%</div>
      <div class="stat-tag">عبر الكتالوج</div>
    </div>`;
}

function openModal(isEdit = false){
  document.getElementById('overlay').classList.add('open');
  if(!isEdit){
    document.getElementById('modalTitle').textContent = 'إضافة منتج (دروب شوبينغ)';
    document.getElementById('f_id').value = '';
    ['f_name','f_img','f_supplier','f_cost','f_price','f_stock'].forEach(id=>document.getElementById(id).value='');
    document.getElementById('f_cat').value = 'women';
    document.getElementById('f_platform').value = 'Syncee';
    document.getElementById('f_status').value = 'pending';
  } else {
    document.getElementById('modalTitle').textContent = 'تعديل بيانات المنتج';
  }
  updateMarginPreview();
}

function closeModal(){
  document.getElementById('overlay').classList.remove('open');
}

/* =================================================================
   استيراد من CJ Dropshipping — يستخدم الـ endpoints الموجودة فعلياً في
   App\Http\Controllers\CjProductController (كانت بلا أي واجهة استخدام).
   ================================================================= */
function openCjModal(){
  document.getElementById('cjOverlay').classList.add('open');
  document.getElementById('cjList').innerHTML = '';
}

function closeCjModal(){
  document.getElementById('cjOverlay').classList.remove('open');
}

function loadCjProducts(page){
  const list = document.getElementById('cjList');
  const note = document.getElementById('cjNote');
  list.innerHTML = '<p style="opacity:.6">جاري التحميل…</p>';

  apiRequest(`{{ route('admin.cj.products') }}?page=${page}&page_size=20`)
    .then(data => {
      const items = data?.data?.data?.list || data?.data?.list || [];
      if(!items.length){
        list.innerHTML = '<p style="opacity:.6">لا توجد منتجات لعرضها.</p>';
        return;
      }
      list.innerHTML = items.map(item => {
        // ملاحظة أمنية: هذه البيانات آتية من CJ Dropshipping، وهو API خارجي
        // لا نتحكم بمحتواه — يجب معاملة كل حقل هنا كـ "غير موثوق" وتفاديه
        // (escape) قبل إدخاله في innerHTML، تماماً كما نفعل مع مدخلات المستخدم.
        const pidRaw = item.pid || item.productId || '';
        const nameRaw = item.productNameEn || item.productName || 'منتج بدون اسم';
        const img = item.productImage || item.image || '';
        const wholesale = parseFloat(item.wholesale_price ?? item.productPrice ?? 0);
        const sell = parseFloat(item.sellPrice ?? 0);
        const name = escapeHtml(nameRaw);
        return `
          <div style="border:1px solid var(--line);border-radius:9px;padding:12px;display:flex;flex-direction:column;gap:8px">
            <div class="thumb" style="width:100%;height:110px;border-radius:6px">
              ${img ? `<img src="${escapeAttr(img)}" alt="${escapeAttr(nameRaw)}" style="width:100%;height:100%;object-fit:cover">` : escapeHtml(nameRaw.slice(0,2))}
            </div>
            <div style="font-size:13px;font-weight:600;line-height:1.4">${name}</div>
            <div style="font-size:12px;color:var(--ink-soft)">جملة: $${wholesale.toFixed(2)} → بيع مقترح: $${sell.toFixed(2)}</div>
            <button class="btn btn-primary" style="width:100%" data-cj-pid="${escapeAttr(pidRaw)}" onclick="importCjProduct(this.dataset.cjPid, this)">استيراد للكتالوج</button>
          </div>`;
      }).join('');
    })
    .catch(err => {
      list.innerHTML = `<p style="color:var(--danger)">${err.message}</p>`;
    });
}

function importCjProduct(pid, btn){
  btn.disabled = true;
  btn.textContent = 'جاري الاستيراد…';

  const category = document.getElementById('cjCategory').value;
  const margin = parseFloat(document.getElementById('cjMargin').value) || 0;

  apiRequest('{{ route('admin.cj.import') }}', {
    method: 'POST',
    body: JSON.stringify({ pid, category, margin })
  })
    .then(() => {
      btn.textContent = 'تم الاستيراد ✓';
      loadProducts();
    })
    .catch(err => {
      alert(err.message);
      btn.disabled = false;
      btn.textContent = 'استيراد للكتالوج';
    });
}

document.getElementById('overlay').addEventListener('click', e=>{
  if(e.target.id === 'overlay') closeModal();
});

document.getElementById('cjOverlay').addEventListener('click', e=>{
  if(e.target.id === 'cjOverlay') closeCjModal();
});

function autoAdjustStatus(){
  const stock = parseInt(document.getElementById('f_stock').value) || 0;
  const statusSelect = document.getElementById('f_status');
  if(stock === 0){
    statusSelect.value = 'out';
  } else if(statusSelect.value === 'out') {
    statusSelect.value = 'synced';
  }
}

function updateMarginPreview(){
  const cost = parseFloat(document.getElementById('f_cost').value) || 0;
  const price = parseFloat(document.getElementById('f_price').value) || 0;
  const m = marginPct(cost, price);
  const box = document.getElementById('marginPreview');
  const amt = document.getElementById('marginAmt');
  if(price > 0){
    const profit = (price - cost).toFixed(2);
    amt.textContent = `${m}% ($${profit}+)`;
    box.classList.toggle('low', m < 30);
  } else {
    amt.textContent = '—';
    box.classList.remove('low');
  }
}

function editProduct(id){
  const p = products.find(prod => prod.id === id);
  if(!p) return;
  document.getElementById('f_id').value = p.id;
  document.getElementById('f_name').value = p.name;
  document.getElementById('f_cat').value = p.cat;
  document.getElementById('f_img').value = p.img || '';
  document.getElementById('f_platform').value = p.platform;
  document.getElementById('f_supplier').value = p.supplier;
  document.getElementById('f_cost').value = p.cost;
  document.getElementById('f_price').value = p.price;
  document.getElementById('f_stock').value = p.stock;
  document.getElementById('f_status').value = p.status;
  openModal(true);
}

function saveProduct(){
  const name = document.getElementById('f_name').value.trim();
  const cost = parseFloat(document.getElementById('f_cost').value) || 0;
  const price = parseFloat(document.getElementById('f_price').value) || 0;
  const id = document.getElementById('f_id').value;

  if(!name || price <= 0){
    alert('يرجى إدخال اسم المنتج وسعر بيع صحيح أكبر من 0.');
    return;
  }

  const prodData = {
    name,
    category: document.getElementById('f_cat').value,
    image: document.getElementById('f_img').value.trim(),
    supplier_platform: document.getElementById('f_platform').value,
    supplier_name: document.getElementById('f_supplier').value.trim() || 'مورد غير محدد',
    cost_price: cost,
    price,
    stock: parseInt(document.getElementById('f_stock').value) || 0,
    sync_status: document.getElementById('f_status').value,
  };

  const request = id
    ? apiRequest(`{{ url('/admin/products') }}/${id}`, { method: 'PUT', body: JSON.stringify(prodData) })
    : apiRequest('{{ route("admin.products.store") }}', { method: 'POST', body: JSON.stringify(prodData) });

  request
    .then(() => {
      closeModal();
      return loadProducts();
    })
    .catch(err => alert(err.message));
}

function deleteProduct(id){
  if(!confirm('هل انت تأكد من رغبتك في حذف هذا المنتج من الكتالوج؟')) return;

  apiRequest(`{{ url('/admin/products') }}/${id}`, { method: 'DELETE' })
    .then(() => loadProducts())
    .catch(err => alert(err.message));
}

/**
 * مزامنة مخزون بالجملة عبر أمر app:sync-cj-products (كان موجوداً بدون أي
 * واجهة استخدام). يجلب أحدث صفحة منتجات من CJ ويحدّث الأسعار/المخزون.
 */
function syncCjInventory(){
  if(!confirm('سيتم جلب أحدث المنتجات من CJ Dropshipping وتحديث الكتالوج. متابعة؟')) return;

  apiRequest('{{ route("admin.cj.sync") }}', { method: 'POST', body: JSON.stringify({}) })
    .then(data => {
      alert(data.success ? 'تمت المزامنة بنجاح.' : 'فشلت المزامنة.');
      loadProducts();
    })
    .catch(err => alert(err.message));
}

function exportJSON(){
  const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(products, null, 2));
  const downloadAnchor = document.createElement('a');
  downloadAnchor.setAttribute("href", dataStr);
  downloadAnchor.setAttribute("download", "post_catalog_products.json");
  document.body.appendChild(downloadAnchor);
  downloadAnchor.click();
  downloadAnchor.remove();
}

loadProducts();
</script>
</body>
</html>
