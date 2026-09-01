{{--
  ============================================================================
  Layout موحّد لكل صفحات لوحة تحكم POST.
  المصدر الوحيد للتصميم (head + الخطوط + كل الـ CSS + مساعدات JS المشتركة)،
  بدل تكرار نفس المستند الكامل في كل صفحة إدارة كما كان سابقاً.

  كيفية الاستخدام في أي صفحة:
    @extends('admin.layout')
    @section('title', 'إدارة الطلبات')
    @section('active', 'orders')     ← لتحديد العنصر النشط في الشريط الجانبي
    @section('page-title', 'إدارة الطلبات')
    @section('page-sub', 'وصف قصير')
    @section('actions') ...أزرار أعلى الصفحة... @endsection   (اختياري)
    @section('content') ...محتوى الصفحة... @endsection
    @push('scripts') <script>...</script> @endpush            (اختياري)
  ============================================================================
--}}
@php($active = trim($__env->yieldContent('active', 'dashboard')))
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>POST — @yield('title', 'لوحة التحكم')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
      --gold-dim:#F7EFDD;
      --sky:#3C5A6E;
      --sky-dim:#E5EDF1;
      --shadow-sm:0 1px 2px rgba(33,31,26,.05);
      --shadow-md:0 4px 16px rgba(33,31,26,.08);
      --shadow-lg:0 12px 34px rgba(33,31,26,.14);
      --radius:12px;
    }
    *{box-sizing:border-box;}
    body{margin:0;background:var(--paper);color:var(--ink);font-family:'Inter',sans-serif;-webkit-font-smoothing:antialiased;}
    a{color:inherit;}
    .app{display:grid;grid-template-columns:256px 1fr;min-height:100vh;}

    /* ============ Mobile top bar ============ */
    .mobile-nav{display:none;background:var(--ink);color:#fff;padding:12px 18px;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:40;}
    .mobile-nav .brand{font-size:20px;}
    .mobile-nav button{background:none;border:none;color:#fff;font-size:22px;cursor:pointer;line-height:1;}

    /* ============ Sidebar ============ */
    .sidebar{background:var(--ink);color:#EFECE2;padding:26px 18px;display:flex;flex-direction:column;gap:22px;position:sticky;top:0;height:100vh;overflow-y:auto;transition:.3s;}
    .brand{font-family:'Fraunces',serif;font-size:25px;letter-spacing:.04em;font-weight:500;line-height:1;}
    .brand span{display:block;font-family:'Inter',sans-serif;font-size:9.5px;letter-spacing:.2em;text-transform:uppercase;color:#9C9787;margin-top:5px;font-weight:600;}
    .nav{display:flex;flex-direction:column;gap:1px;}
    .nav-item{display:flex;align-items:center;gap:11px;padding:10px 12px;border-radius:8px;font-size:13.5px;color:#C4C0B1;cursor:pointer;transition:.15s;text-decoration:none;font-weight:500;}
    .nav-item svg{width:17px;height:17px;flex-shrink:0;opacity:.85;}
    .nav-item:hover{background:#2E2C25;color:#fff;}
    .nav-item.active{background:var(--forest);color:#fff;font-weight:600;box-shadow:inset 3px 0 0 var(--gold);}
    .nav-item.active svg{opacity:1;}
    .nav-eyebrow{font-size:9.5px;letter-spacing:.16em;text-transform:uppercase;color:#7E7A68;margin:15px 0 4px 6px;font-weight:600;}
    .sidebar-user{margin-top:auto;border-top:1px solid #35322A;padding-top:16px;display:flex;align-items:center;gap:10px;font-size:12.5px;}
    .sidebar-user .avatar{width:34px;height:34px;border-radius:50%;background:var(--forest);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;}
    .sidebar-user .u-name{font-weight:600;color:#EFECE2;}
    .sidebar-user .u-role{color:#847F6C;font-size:11px;}

    /* ============ Main ============ */
    .main{padding:30px 38px;max-width:1240px;width:100%;}
    .topbar{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:26px;gap:20px;flex-wrap:wrap;}
    .page-title{font-family:'Fraunces',serif;font-size:31px;font-weight:500;margin:0;line-height:1.1;}
    .page-sub{color:var(--ink-soft);font-size:13.5px;margin-top:6px;}
    .btn-group{display:flex;gap:10px;flex-wrap:wrap;}

    /* ============ Buttons ============ */
    .btn{font-family:'Inter';font-size:13px;font-weight:600;border:none;border-radius:8px;padding:10px 16px;cursor:pointer;transition:.15s;display:inline-flex;align-items:center;gap:7px;line-height:1;}
    .btn-primary{background:var(--forest);color:#fff;box-shadow:var(--shadow-sm);}
    .btn-primary:hover{background:#2D3A2E;transform:translateY(-1px);box-shadow:var(--shadow-md);}
    .btn-ghost{background:var(--panel);border:1px solid var(--line);color:var(--ink);}
    .btn-ghost:hover{border-color:var(--ink);background:#EAE6DA;}
    .btn-danger{background:var(--brick-dim);color:var(--danger);border:1px solid transparent;}
    .btn-danger:hover{border-color:var(--danger);}

    /* ============ Cards / panels ============ */
    .card{background:var(--panel);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow-sm);}
    .card-pad{padding:22px 24px;}
    .section-title{font-family:'Fraunces',serif;font-weight:500;font-size:19px;margin:0 0 4px;}
    .grid{display:grid;gap:16px;}

    /* ============ KPI / stat cards ============ */
    .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:26px;}
    .stat{background:var(--panel);border:1px solid var(--line);border-radius:var(--radius);padding:18px 20px;box-shadow:var(--shadow-sm);}
    .stat-label{font-size:11px;letter-spacing:.07em;text-transform:uppercase;color:var(--ink-soft);margin-bottom:8px;}
    .stat-value{font-family:'Fraunces',serif;font-size:27px;font-weight:500;line-height:1;}
    .stat-tag{font-size:11px;color:var(--forest);margin-top:7px;font-weight:600;}
    .kpi{position:relative;overflow:hidden;}
    .kpi .kpi-top{display:flex;justify-content:space-between;align-items:flex-start;gap:10px;}
    .kpi .kpi-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .kpi .kpi-icon svg{width:19px;height:19px;}
    .kpi-icon.i-forest{background:var(--forest-dim);color:var(--forest);}
    .kpi-icon.i-gold{background:var(--gold-dim);color:var(--gold);}
    .kpi-icon.i-sky{background:var(--sky-dim);color:var(--sky);}
    .kpi-icon.i-brick{background:var(--brick-dim);color:var(--brick);}

    /* ============ Toolbar / filters ============ */
    .toolbar{display:flex;gap:10px;align-items:center;margin-bottom:16px;flex-wrap:wrap;}
    .search{flex:1;min-width:220px;position:relative;}
    .search input{width:100%;padding:11px 14px;border:1px solid var(--line);border-radius:8px;background:var(--panel);font-family:'Inter';font-size:13.5px;}
    .search input:focus{outline:2px solid var(--forest);outline-offset:1px;border-color:var(--forest);}
    select.filter{padding:11px 12px;border:1px solid var(--line);border-radius:8px;background:var(--panel);font-family:'Inter';font-size:13.5px;color:var(--ink);cursor:pointer;}

    /* ============ Table ============ */
    .table-wrap{background:var(--panel);border:1px solid var(--line);border-radius:var(--radius);overflow-x:auto;box-shadow:var(--shadow-sm);}
    table{width:100%;border-collapse:collapse;min-width:640px;}
    thead th{text-align:right;font-size:10.5px;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-soft);padding:14px 16px;border-bottom:1px solid var(--line);font-weight:600;background:#FAF8F2;}
    tbody td{padding:13px 16px;border-bottom:1px solid var(--line);font-size:13.5px;vertical-align:middle;}
    tbody tr:last-child td{border-bottom:none;}
    tbody tr:hover{background:#FAF8F2;}
    .prod-cell{display:flex;align-items:center;gap:12px;}
    .thumb{width:44px;height:44px;border-radius:8px;object-fit:cover;background:var(--line);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:11px;color:#8A8672;font-weight:600;overflow:hidden;}
    .thumb img{width:100%;height:100%;object-fit:cover;}
    .prod-name{font-weight:600;}
    .prod-cat{font-size:11.5px;color:var(--ink-soft);}
    .badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap;}
    .badge-synced{background:var(--forest-dim);color:var(--forest);}
    .badge-out{background:var(--brick-dim);color:var(--danger);}
    .badge-pending{background:var(--gold-dim);color:var(--gold);}
    .badge-info{background:var(--sky-dim);color:var(--sky);}
    .dot{width:6px;height:6px;border-radius:50%;background:currentColor;}
    .price-cell{font-family:'IBM Plex Mono',monospace;font-size:13px;}
    .margin{font-family:'IBM Plex Mono',monospace;font-size:12.5px;padding:3px 8px;border-radius:5px;background:var(--forest-dim);color:var(--forest);font-weight:500;}
    .margin.low{background:var(--brick-dim);color:var(--brick);}
    .row-actions{display:flex;gap:6px;}
    .icon-btn{width:32px;height:32px;border-radius:7px;border:1px solid var(--line);background:var(--panel);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.15s;font-size:13px;}
    .icon-btn:hover{border-color:var(--ink);}
    .icon-btn.danger:hover{border-color:var(--danger);background:var(--brick-dim);color:var(--danger);}
    .supplier-src{font-size:11.5px;color:var(--ink-soft);display:flex;align-items:center;gap:5px;}
    .empty-state{padding:56px 20px;text-align:center;color:var(--ink-soft);}
    .empty-state h3{font-family:'Fraunces',serif;font-size:20px;color:var(--ink);margin-bottom:6px;font-weight:500;}

    /* ============ Flash / alerts ============ */
    .alert{border-radius:10px;padding:12px 16px;margin-bottom:18px;font-weight:600;font-size:13.5px;display:flex;gap:9px;align-items:flex-start;}
    .alert-success{background:var(--forest-dim);color:var(--forest);border:1px solid var(--forest);}
    .alert-error{background:var(--brick-dim);color:var(--danger);border:1px solid var(--danger);}
    .alert ul{margin:0;padding-right:18px;font-weight:500;}

    /* ============ Forms ============ */
    .field{display:flex;flex-direction:column;gap:6px;}
    .field.full{grid-column:1/-1;}
    .field label{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--ink-soft);}
    .field input,.field select,.field textarea{padding:10px 12px;border:1px solid var(--line);border-radius:8px;font-family:'Inter';font-size:13.5px;background:#fff;color:var(--ink);}
    .field input:focus,.field select:focus,.field textarea:focus{outline:2px solid var(--forest);outline-offset:1px;border-color:var(--forest);}
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}

    /* ============ Modal ============ */
    .overlay{position:fixed;inset:0;background:rgba(33,31,26,.5);display:none;align-items:center;justify-content:center;z-index:60;padding:20px;backdrop-filter:blur(2px);}
    .overlay.open{display:flex;}
    .modal{background:var(--panel);border-radius:16px;width:100%;max-width:640px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow-lg);}
    .modal-head{padding:22px 26px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;background:var(--panel);z-index:1;}
    .modal-head h2{font-family:'Fraunces',serif;font-weight:500;font-size:22px;margin:0;}
    .modal-close{background:none;border:none;font-size:24px;cursor:pointer;color:var(--ink-soft);line-height:1;}
    .modal-body{padding:22px 26px;display:grid;grid-template-columns:1fr 1fr;gap:16px;}
    .modal-foot{padding:18px 26px;border-top:1px solid var(--line);display:flex;justify-content:flex-end;gap:10px;position:sticky;bottom:0;background:var(--panel);}
    .margin-preview{grid-column:1/-1;background:var(--forest-dim);border-radius:10px;padding:14px 16px;display:flex;justify-content:space-between;align-items:center;font-size:13px;}
    .margin-preview.low{background:var(--brick-dim);}
    .margin-preview .amt{font-family:'Fraunces',serif;font-size:22px;font-weight:500;}
    .section-note{grid-column:1/-1;font-size:11.5px;color:var(--ink-soft);background:var(--paper);border:1px dashed var(--line);border-radius:8px;padding:10px 12px;}

    /* ============ Chart ============ */
    .chart-card{padding:22px 24px 14px;}
    .chart-head{display:flex;justify-content:space-between;align-items:baseline;margin-bottom:6px;flex-wrap:wrap;gap:8px;}
    .chart-svg{width:100%;height:auto;display:block;overflow:visible;}
    .chart-xlabels{display:flex;justify-content:space-between;font-size:10px;color:var(--ink-soft);margin-top:6px;font-family:'IBM Plex Mono',monospace;}

    ::-webkit-scrollbar{width:9px;height:9px;}
    ::-webkit-scrollbar-thumb{background:var(--line);border-radius:8px;}
    ::-webkit-scrollbar-thumb:hover{background:#C9C4B4;}

    @media (max-width:980px){
      .stats{grid-template-columns:1fr 1fr;}
      .dash-2col{grid-template-columns:1fr !important;}
    }
    @media (max-width:860px){
      .app{grid-template-columns:1fr;}
      .mobile-nav{display:flex;}
      .sidebar{display:none;position:fixed;inset:0;z-index:99;width:270px;height:100vh;}
      .sidebar.open{display:flex;}
      .modal-body,.form-grid{grid-template-columns:1fr;}
      .main{padding:20px 18px;}
      .page-title{font-size:26px;}
    }
  </style>
  @stack('head')
</head>
<body>
<div class="app">
  @include('admin.partials.sidebar', ['active' => $active])

  <main class="main">
    @if(session('success'))
      <div class="alert alert-success"><span>✓</span><span>{{ session('success') }}</span></div>
    @endif
    @if(session('error'))
      <div class="alert alert-error"><span>!</span><span>{{ session('error') }}</span></div>
    @endif
    @if($errors->any())
      <div class="alert alert-error">
        <span>!</span>
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
      </div>
    @endif

    @hasSection('page-title')
      <div class="topbar">
        <div>
          <h1 class="page-title">@yield('page-title')</h1>
          @hasSection('page-sub')<div class="page-sub">@yield('page-sub')</div>@endif
        </div>
        @hasSection('actions')<div class="btn-group">@yield('actions')</div>@endif
      </div>
    @endif

    @yield('content')
  </main>
</div>

<script>
  /* مساعدات مشتركة لكل صفحات لوحة التحكم — كانت مكرّرة حرفياً في كل صفحة. */
  function csrfToken(){
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  }
  function toggleSidebar(){
    document.getElementById('sidebar')?.classList.toggle('open');
  }
  function escapeHtml(str){
    const d = document.createElement('div');
    d.textContent = str ?? '';
    return d.innerHTML;
  }
  function escapeAttr(str){
    return escapeHtml(str).replace(/"/g,'&quot;').replace(/'/g,'&#39;');
  }
  function apiRequest(url, options = {}){
    return fetch(url, {
      ...options,
      headers: {
        'Content-Type':'application/json',
        'Accept':'application/json',
        'X-CSRF-TOKEN':csrfToken(),
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
  /* إغلاق أي مودال عند الضغط على الخلفية أو زر Escape */
  document.addEventListener('click', e => {
    if(e.target.classList && e.target.classList.contains('overlay')) e.target.classList.remove('open');
  });
  document.addEventListener('keydown', e => {
    if(e.key === 'Escape') document.querySelectorAll('.overlay.open').forEach(o => o.classList.remove('open'));
  });
</script>
@stack('scripts')
</body>
</html>
