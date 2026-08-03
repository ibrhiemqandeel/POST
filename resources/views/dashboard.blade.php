<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>POST — لوحة التحكم</title>
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
  /* Sidebar */
  .sidebar{background:var(--ink);color:#EFECE2;padding:28px 20px;display:flex;flex-direction:column;gap:28px;}
  .brand{font-family:'Fraunces',serif;font-size:26px;letter-spacing:0.04em;font-weight:500;}
  .brand span{display:block;font-family:'Inter',sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#9C9787;margin-top:4px;font-weight:500;}
  .nav{display:flex;flex-direction:column;gap:2px;margin-top:8px;}
  .nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:6px;font-size:14px;color:#C9C5B6;cursor:pointer;transition:.15s;}
  .nav-item:hover{background:#2E2C25;color:#fff;}
  .nav-item.active{background:#EFECE2;color:var(--ink);font-weight:600;}
  .nav-eyebrow{font-size:10px;letter-spacing:0.14em;text-transform:uppercase;color:#8A8672;margin:14px 0 2px 4px;}
  .sidebar-foot{margin-top:auto;font-size:11px;color:#847F6C;line-height:1.6;border-top:1px solid #35322A;padding-top:16px;}

  /* Main */
  .main{padding:32px 40px;max-width:1200px;}
  .topbar{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:28px;gap:20px;flex-wrap:wrap;}
  .page-title{font-family:'Fraunces',serif;font-size:32px;font-weight:500;margin:0;}
  .page-sub{color:var(--ink-soft);font-size:14px;margin-top:4px;}
  .btn{font-family:'Inter';font-size:13.5px;font-weight:600;border:none;border-radius:7px;padding:11px 18px;cursor:pointer;transition:.15s;display:inline-flex;align-items:center;gap:8px;}
  .btn-primary{background:var(--forest);color:#fff;}
  .btn-primary:hover{background:#2D3A2E;}
  .btn-ghost{background:transparent;border:1px solid var(--line);color:var(--ink);}
  .btn-ghost:hover{border-color:var(--ink);}

  /* Stat cards */
  .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:30px;}
  .stat{background:var(--panel);border:1px solid var(--line);border-radius:10px;padding:18px 20px;}
  .stat-label{font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:var(--ink-soft);margin-bottom:8px;}
  .stat-value{font-family:'Fraunces',serif;font-size:26px;font-weight:500;}
  .stat-tag{font-size:11px;color:var(--forest);margin-top:6px;font-weight:600;}

  /* Filters */
  .toolbar{display:flex;gap:10px;align-items:center;margin-bottom:16px;flex-wrap:wrap;}
  .search{flex:1;min-width:200px;position:relative;}
  .search input{width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:7px;background:var(--panel);font-family:'Inter';font-size:13.5px;}
  select.filter{padding:10px 12px;border:1px solid var(--line);border-radius:7px;background:var(--panel);font-family:'Inter';font-size:13.5px;color:var(--ink);}

  /* Table */
  .table-wrap{background:var(--panel);border:1px solid var(--line);border-radius:12px;overflow:hidden;}
  table{width:100%;border-collapse:collapse;}
  thead th{text-align:right;font-size:10.5px;letter-spacing:0.09em;text-transform:uppercase;color:var(--ink-soft);padding:14px 16px;border-bottom:1px solid var(--line);font-weight:600;}
  tbody td{padding:14px 16px;border-bottom:1px solid var(--line);font-size:13.5px;vertical-align:middle;}
  tbody tr:last-child td{border-bottom:none;}
  tbody tr:hover{background:#FAF8F2;}
  .prod-cell{display:flex;align-items:center;gap:12px;}
  .thumb{width:44px;height:44px;border-radius:6px;object-fit:cover;background:var(--line);flex-shrink:0;}
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
  .icon-btn{width:30px;height:30px;border-radius:6px;border:1px solid var(--line);background:var(--panel);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:.15s;}
  .icon-btn:hover{border-color:var(--ink);}
  .icon-btn.danger:hover{border-color:var(--danger);background:var(--brick-dim);}
  .supplier-src{font-size:11.5px;color:var(--ink-soft);display:flex;align-items:center;gap:5px;}
  .empty-state{padding:60px 20px;text-align:center;color:var(--ink-soft);}
  .empty-state h3{font-family:'Fraunces',serif;font-size:20px;color:var(--ink);margin-bottom:6px;font-weight:500;}

  /* Modal */
  .overlay{position:fixed;inset:0;background:rgba(33,31,26,0.5);display:none;align-items:center;justify-content:center;z-index:50;padding:20px;}
  .overlay.open{display:flex;}
  .modal{background:var(--panel);border-radius:14px;width:100%;max-width:640px;max-height:90vh;overflow-y:auto;padding:0;}
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

  ::-webkit-scrollbar{width:8px;}
  ::-webkit-scrollbar-thumb{background:var(--line);border-radius:8px;}

  @media (max-width:860px){
    .app{grid-template-columns:1fr;}
    .sidebar{display:none;}
    .stats{grid-template-columns:1fr 1fr;}
    .modal-body{grid-template-columns:1fr;}
  }
</style>
</head>
<body>

<div class="app">
  <aside class="sidebar">
    <div class="brand">POST<span>لوحة التحكم — الإدارة</span></div>
    <nav class="nav">
      <div class="nav-item active">↳ المنتجات</div>
      <div class="nav-item">↳ الطلبات</div>
      <div class="nav-item">↳ الموردون</div>
      <div class="nav-item">↳ التقارير</div>
      <div class="nav-eyebrow">دروب شوبينغ</div>
      <div class="nav-item">↳ استيراد من Syncee</div>
      <div class="nav-item">↳ مزامنة المخزون</div>
      <div class="nav-eyebrow">الإعدادات</div>
      <div class="nav-item">↳ الفئات</div>
      <div class="nav-item">↳ الحساب</div>
    </nav>
    <div class="sidebar-foot">نموذج أولي للوحة التحكم — غير متصل بعد بمتجر POST المباشر أو بمزود دروب شوبينغ حقيقي.</div>
  </aside>

  <main class="main">
    <div class="topbar">
      <div>
        <h1 class="page-title">المنتجات</h1>
        <div class="page-sub" id="prodCount">— منتج في الكتالوج</div>
      </div>
      <button class="btn btn-primary" onclick="openModal()">+ إضافة منتج جديد</button>
    </div>

    <div class="stats" id="statsRow"></div>

    <div class="toolbar">
      <div class="search">
        <input id="searchInput" placeholder="ابحث باسم المنتج أو المورد…" oninput="render()">
      </div>
      <select class="filter" id="catFilter" onchange="render()">
        <option value="">كل الفئات</option>
        <option>نساء</option>
        <option>أطفال</option>
        <option>تجميل</option>
        <option>إكسسوارات</option>
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
            <th>المورد / المصدر</th>
            <th>سعر التكلفة</th>
            <th>سعر البيع</th>
            <th>هامش الربح</th>
            <th>الحالة</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
      <div id="emptyState" class="empty-state" style="display:none">
        <h3>لا توجد منتجات مطابقة</h3>
        <div>جرّب تغيير البحث أو الفلاتر، أو أضف منتجًا جديدًا.</div>
      </div>
    </div>
  </main>
</div>

<!-- Modal -->
<div class="overlay" id="overlay">
  <div class="modal">
    <div class="modal-head">
      <h2>إضافة منتج (دروب شوبينغ)</h2>
      <button class="modal-close" onclick="closeModal()">×</button>
    </div>
    <div class="modal-body">
      <div class="field full">
        <label>اسم المنتج</label>
        <input id="f_name" placeholder="مثال: فستان كتان — طبعة كومو">
      </div>
      <div class="field">
        <label>الفئة</label>
        <select id="f_cat">
          <option>نساء</option>
          <option>أطفال</option>
          <option>تجميل</option>
          <option>إكسسوارات</option>
        </select>
      </div>
      <div class="field">
        <label>رابط صورة المنتج</label>
        <input id="f_img" placeholder="https://…">
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
        <label>سعر التكلفة (المورد)</label>
        <input id="f_cost" type="number" min="0" step="0.01" placeholder="0.00" oninput="updateMarginPreview()">
      </div>
      <div class="field">
        <label>سعر البيع (للزبون)</label>
        <input id="f_price" type="number" min="0" step="0.01" placeholder="0.00" oninput="updateMarginPreview()">
      </div>
      <div class="field">
        <label>المخزون لدى المورد</label>
        <input id="f_stock" type="number" min="0" placeholder="0">
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
      <div class="section-note">في نظام دروب شوبينغ حقيقي (كـ Syncee)، سعر التكلفة والمخزون يُسحبان تلقائيًا من المورد عبر API، ولا يُعدَّلان يدويًا إلا نادرًا.</div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal()">إلغاء</button>
      <button class="btn btn-primary" onclick="saveProduct()">حفظ المنتج</button>
    </div>
  </div>
</div>

<script>
let products = [
  {id:1,name:"فستان كتان — طبعة كومو",cat:"نساء",img:"",platform:"Syncee",supplier:"Como Textiles Co.",cost:38,price:89,stock:24,status:"synced"},
  {id:2,name:"سترة صوف للأطفال",cat:"أطفال",img:"",platform:"CJ Dropshipping",supplier:"NordKids Supply",cost:14,price:35,stock:0,status:"out"},
  {id:3,name:"مصل فيتامين سي",cat:"تجميل",img:"",platform:"Spocket",supplier:"Pure Lab EU",cost:6,price:24,stock:120,status:"synced"},
  {id:4,name:"حزام جلد طبيعي",cat:"إكسسوارات",img:"",platform:"Syncee",supplier:"Milano Leather House",cost:12,price:42,stock:8,status:"pending"},
];
let nextId = 5;

function marginPct(cost, price){
  if(!price || price<=0) return 0;
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
    const matchQ = !q || p.name.toLowerCase().includes(q) || p.supplier.toLowerCase().includes(q);
    const matchCat = !catF || p.cat===catF;
    const matchStatus = !statusF || p.status===statusF;
    return matchQ && matchCat && matchStatus;
  });

  const tbody = document.getElementById('tbody');
  const empty = document.getElementById('emptyState');
  document.getElementById('prodCount').textContent = `${products.length} منتج في الكتالوج`;

  if(filtered.length===0){
    tbody.innerHTML = '';
    empty.style.display = 'block';
  } else {
    empty.style.display = 'none';
    tbody.innerHTML = filtered.map(p=>{
      const m = marginPct(p.cost, p.price);
      const initials = p.name.split(' ')[0].slice(0,2);
      return `
      <tr>
        <td>
          <div class="prod-cell">
            <div class="thumb" style="display:flex;align-items:center;justify-content:center;font-size:11px;color:#8A8672;font-weight:600;">${initials}</div>
            <div>
              <div class="prod-name">${p.name}</div>
              <div class="prod-cat">${p.cat}</div>
            </div>
          </div>
        </td>
        <td>
          <div class="supplier-src">${p.platform} · ${p.supplier}</div>
        </td>
        <td class="price-cell">$${p.cost.toFixed(2)}</td>
        <td class="price-cell">$${p.price.toFixed(2)}</td>
        <td><span class="margin ${m<30?'low':''}">${m}%</span></td>
        <td>${statusBadge(p.status)}</td>
        <td>
          <div class="row-actions">
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
  const synced = products.filter(p=>p.status==='synced').length;
  const out = products.filter(p=>p.status==='out').length;
  const avgMargin = total ? Math.round(products.reduce((s,p)=>s+marginPct(p.cost,p.price),0)/total) : 0;

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

function openModal(){
  document.getElementById('overlay').classList.add('open');
  ['f_name','f_img','f_supplier','f_cost','f_price','f_stock'].forEach(id=>document.getElementById(id).value='');
  document.getElementById('f_cat').value='نساء';
  document.getElementById('f_platform').value='Syncee';
  document.getElementById('f_status').value='pending';
  updateMarginPreview();
}
function closeModal(){
  document.getElementById('overlay').classList.remove('open');
}
document.getElementById('overlay').addEventListener('click', e=>{
  if(e.target.id==='overlay') closeModal();
});

function updateMarginPreview(){
  const cost = parseFloat(document.getElementById('f_cost').value)||0;
  const price = parseFloat(document.getElementById('f_price').value)||0;
  const m = marginPct(cost, price);
  const box = document.getElementById('marginPreview');
  const amt = document.getElementById('marginAmt');
  if(price>0){
    amt.textContent = `${m}% ($${(price-cost).toFixed(2)})`;
    box.classList.toggle('low', m<30);
  } else {
    amt.textContent = '—';
    box.classList.remove('low');
  }
}

function saveProduct(){
  const name = document.getElementById('f_name').value.trim();
  const cost = parseFloat(document.getElementById('f_cost').value)||0;
  const price = parseFloat(document.getElementById('f_price').value)||0;
  if(!name || price<=0){
    alert('يرجى إدخال اسم المنتج وسعر بيع صحيح.');
    return;
  }
  products.push({
    id: nextId++,
    name,
    cat: document.getElementById('f_cat').value,
    img: document.getElementById('f_img').value.trim(),
    platform: document.getElementById('f_platform').value,
    supplier: document.getElementById('f_supplier').value.trim() || 'مورد غير محدد',
    cost,
    price,
    stock: parseInt(document.getElementById('f_stock').value)||0,
    status: document.getElementById('f_status').value,
  });
  closeModal();
  render();
}

function deleteProduct(id){
  if(!confirm('هل تريد حذف هذا المنتج من الكتالوج؟')) return;
  products = products.filter(p=>p.id!==id);
  render();
}

render();
</script>
</body>
</html>
