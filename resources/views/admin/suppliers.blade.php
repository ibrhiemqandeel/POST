<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>POST — إدارة الموردين</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
@include('admin.partials.style')
</head>
<body>

<div class="app">
  @include('admin.partials.sidebar', ['active' => 'suppliers'])

  <main class="main">
    <div class="topbar">
      <div>
        <h1 class="page-title">إدارة الموردين</h1>
        <div class="page-sub" id="supCount">— مورد — دعم موردين من دول مختلفة (Multi-Supplier)</div>
      </div>
      <div class="btn-group">
        <button class="btn btn-primary" onclick="openModal()">+ إضافة مورد جديد</button>
      </div>
    </div>

    <div class="toolbar">
      <div class="search">
        <input id="searchInput" placeholder="ابحث باسم المورد، الدولة أو المنصة…" oninput="render()">
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>المورد</th>
            <th>الدولة</th>
            <th>المنصة</th>
            <th>العملة</th>
            <th>شحن افتراضي</th>
            <th>مدة الشحن</th>
            <th>المنتجات</th>
            <th>الحالة</th>
            <th>إجراءات</th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
      <div id="emptyState" class="empty-state" style="display:none">
        <h3>لا يوجد موردون</h3>
        <div>أضف مورداً جديداً لبدء ربط المنتجات بمصادرها.</div>
      </div>
    </div>
  </main>
</div>

<!-- Modal إضافة/تعديل مورد -->
<div class="overlay" id="overlay">
  <div class="modal">
    <div class="modal-head">
      <h2 id="modalTitle">إضافة مورد</h2>
      <button class="modal-close" onclick="closeModal()">×</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="f_id">
      <div class="field full">
        <label>اسم المورد</label>
        <input id="f_name" placeholder="مثال: Como Textiles Co.">
      </div>
      <div class="field">
        <label>الدولة</label>
        <input id="f_country" placeholder="مثال: الصين / تركيا">
      </div>
      <div class="field">
        <label>المنصة</label>
        <select id="f_platform">
          <option value="">—</option>
          <option>CJ Dropshipping</option>
          <option>AliExpress</option>
          <option>Spocket</option>
          <option>Syncee</option>
          <option>مورد محلي</option>
        </select>
      </div>
      <div class="field">
        <label>البريد الإلكتروني</label>
        <input id="f_email" type="email" placeholder="supplier@example.com">
      </div>
      <div class="field">
        <label>الهاتف</label>
        <input id="f_phone" placeholder="+90...">
      </div>
      <div class="field">
        <label>العملة</label>
        <input id="f_currency" placeholder="USD" value="USD">
      </div>
      <div class="field">
        <label>تكلفة الشحن الافتراضية ($)</label>
        <input id="f_ship_cost" type="number" min="0" step="0.01" placeholder="0.00">
      </div>
      <div class="field">
        <label>أقل مدة شحن (أيام)</label>
        <input id="f_days_min" type="number" min="0" placeholder="7">
      </div>
      <div class="field">
        <label>أقصى مدة شحن (أيام)</label>
        <input id="f_days_max" type="number" min="0" placeholder="15">
      </div>
      <div class="field">
        <label>الحالة</label>
        <select id="f_active">
          <option value="1">مفعّل</option>
          <option value="0">موقوف</option>
        </select>
      </div>
      <div class="field full">
        <label>ملاحظات</label>
        <input id="f_notes" placeholder="ملاحظات داخلية عن المورد…">
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal()">إلغاء</button>
      <button class="btn btn-primary" onclick="saveSupplier()">حفظ</button>
    </div>
  </div>
</div>

<script>
let SUPPLIERS = [];

function csrfToken(){ return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''; }
function toggleSidebar(){ document.getElementById('sidebar').classList.toggle('open'); }
function escapeHtml(str){ const d = document.createElement('div'); d.textContent = str ?? ''; return d.innerHTML; }

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
    if(!r.ok || data.success === false){ throw new Error(data.message || 'حدث خطأ غير متوقع.'); }
    return data;
  });
}

function loadSuppliers(){
  return apiRequest('{{ route("admin.suppliers.index") }}')
    .then(data => { SUPPLIERS = data.suppliers || []; render(); })
    .catch(err => {
      document.getElementById('emptyState').style.display = 'block';
      document.querySelector('#emptyState h3').textContent = 'تعذّر تحميل الموردين';
      document.querySelector('#emptyState div').textContent = err.message;
    });
}

function render(){
  const q = (document.getElementById('searchInput').value || '').trim().toLowerCase();
  const filtered = SUPPLIERS.filter(s => !q
    || (s.name||'').toLowerCase().includes(q)
    || (s.country||'').toLowerCase().includes(q)
    || (s.platform||'').toLowerCase().includes(q));

  document.getElementById('supCount').textContent = `${SUPPLIERS.length} مورد — دعم موردين من دول مختلفة (Multi-Supplier)`;

  const tbody = document.getElementById('tbody');
  const emptyState = document.getElementById('emptyState');

  if(filtered.length === 0){
    tbody.innerHTML = '';
    emptyState.style.display = 'block';
    document.querySelector('#emptyState h3').textContent = 'لا يوجد موردون مطابقون';
    document.querySelector('#emptyState div').textContent = 'جرّب تغيير عبارة البحث أو أضف مورداً.';
    return;
  }

  emptyState.style.display = 'none';
  tbody.innerHTML = filtered.map(s => {
    const days = (s.shipping_days_min && s.shipping_days_max)
      ? `${s.shipping_days_min}–${s.shipping_days_max} يوم`
      : (s.shipping_days_min ? `${s.shipping_days_min} يوم` : '—');
    const badge = s.is_active
      ? '<span class="badge badge-synced"><span class="dot"></span>مفعّل</span>'
      : '<span class="badge badge-out"><span class="dot"></span>موقوف</span>';
    return `
      <tr>
        <td class="prod-name">${escapeHtml(s.name)}</td>
        <td>${escapeHtml(s.country || '—')}</td>
        <td class="prod-cat">${escapeHtml(s.platform || '—')}</td>
        <td>${escapeHtml(s.currency || 'USD')}</td>
        <td class="price-cell">$${Number(s.default_shipping_cost||0).toFixed(2)}</td>
        <td>${days}</td>
        <td>${s.products_count}</td>
        <td>${badge}</td>
        <td class="row-actions">
          <button class="icon-btn" title="تعديل" onclick="editSupplier(${s.id})">✎</button>
          <button class="icon-btn danger" title="حذف" onclick="deleteSupplier(${s.id})">🗑</button>
        </td>
      </tr>`;
  }).join('');
}

function openModal(){
  document.getElementById('modalTitle').textContent = 'إضافة مورد';
  ['f_id','f_name','f_country','f_email','f_phone','f_ship_cost','f_days_min','f_days_max','f_notes'].forEach(id=>document.getElementById(id).value='');
  document.getElementById('f_platform').value = '';
  document.getElementById('f_currency').value = 'USD';
  document.getElementById('f_active').value = '1';
  document.getElementById('overlay').classList.add('open');
}

function editSupplier(id){
  const s = SUPPLIERS.find(x => x.id === id);
  if(!s) return;
  document.getElementById('modalTitle').textContent = 'تعديل مورد';
  document.getElementById('f_id').value = s.id;
  document.getElementById('f_name').value = s.name || '';
  document.getElementById('f_country').value = s.country || '';
  document.getElementById('f_platform').value = s.platform || '';
  document.getElementById('f_email').value = s.contact_email || '';
  document.getElementById('f_phone').value = s.contact_phone || '';
  document.getElementById('f_currency').value = s.currency || 'USD';
  document.getElementById('f_ship_cost').value = s.default_shipping_cost ?? '';
  document.getElementById('f_days_min').value = s.shipping_days_min ?? '';
  document.getElementById('f_days_max').value = s.shipping_days_max ?? '';
  document.getElementById('f_active').value = s.is_active ? '1' : '0';
  document.getElementById('f_notes').value = s.notes || '';
  document.getElementById('overlay').classList.add('open');
}

function closeModal(){ document.getElementById('overlay').classList.remove('open'); }

function saveSupplier(){
  const id = document.getElementById('f_id').value;
  const name = document.getElementById('f_name').value.trim();
  if(!name){ alert('يرجى إدخال اسم المورد.'); return; }

  const payload = {
    name,
    country: document.getElementById('f_country').value.trim(),
    platform: document.getElementById('f_platform').value,
    contact_email: document.getElementById('f_email').value.trim(),
    contact_phone: document.getElementById('f_phone').value.trim(),
    currency: document.getElementById('f_currency').value.trim() || 'USD',
    default_shipping_cost: parseFloat(document.getElementById('f_ship_cost').value) || 0,
    shipping_days_min: document.getElementById('f_days_min').value ? parseInt(document.getElementById('f_days_min').value) : null,
    shipping_days_max: document.getElementById('f_days_max').value ? parseInt(document.getElementById('f_days_max').value) : null,
    is_active: document.getElementById('f_active').value === '1',
    notes: document.getElementById('f_notes').value.trim(),
  };

  const req = id
    ? apiRequest(`{{ url('/admin/suppliers') }}/${id}`, { method: 'PUT', body: JSON.stringify(payload) })
    : apiRequest('{{ route("admin.suppliers.store") }}', { method: 'POST', body: JSON.stringify(payload) });

  req.then(() => { closeModal(); loadSuppliers(); }).catch(err => alert(err.message));
}

function deleteSupplier(id){
  if(!confirm('هل أنت متأكد من حذف هذا المورد؟')) return;
  apiRequest(`{{ url('/admin/suppliers') }}/${id}`, { method: 'DELETE' })
    .then(() => loadSuppliers())
    .catch(err => alert(err.message));
}

document.getElementById('overlay').addEventListener('click', e => { if(e.target.id === 'overlay') closeModal(); });

loadSuppliers();
</script>
</body>
</html>
