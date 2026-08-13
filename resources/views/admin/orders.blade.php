<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>POST — إدارة الطلبات</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
@include('admin.partials.style')
</head>
<body>

<div class="app">
  @include('admin.partials.sidebar', ['active' => 'orders'])

  <main class="main">
    <div class="topbar">
      <div>
        <h1 class="page-title">إدارة الطلبات</h1>
        <div class="page-sub" id="orderCount">— طلب</div>
      </div>
    </div>

    <div class="stats" id="statsRow"></div>

    <div class="toolbar">
      <div class="search">
        <input id="searchInput" placeholder="ابحث باسم العميل أو بريده الإلكتروني…" oninput="render()">
      </div>
      <select class="filter" id="statusFilter" onchange="render()">
        <option value="">كل الحالات</option>
        <option value="pending">قيد الانتظار</option>
        <option value="processing">قيد المعالجة</option>
        <option value="shipped">تم الشحن</option>
        <option value="delivered">تم التوصيل</option>
        <option value="cancelled">ملغي</option>
      </select>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>رقم الطلب</th>
            <th>العميل</th>
            <th>عدد القطع</th>
            <th>الإجمالي</th>
            <th>الحالة</th>
            <th>التاريخ</th>
            <th>إجراءات</th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
      <div id="emptyState" class="empty-state" style="display:none">
        <h3>لا توجد طلبات مطابقة</h3>
        <div>جرّب تغيير عبارة البحث أو الفلتر.</div>
      </div>
    </div>
  </main>
</div>

<!-- Modal تفاصيل الطلب -->
<div class="overlay" id="overlay">
  <div class="modal">
    <div class="modal-head">
      <h2 id="modalTitle">تفاصيل الطلب</h2>
      <button class="modal-close" onclick="closeModal()">×</button>
    </div>
    <div class="modal-body" id="modalBody" style="display:block">
      <!-- يُملأ ديناميكياً -->
    </div>
    <div class="modal-foot">
      <button class="btn btn-ghost" onclick="closeModal()">إغلاق</button>
    </div>
  </div>
</div>

<script>
let ORDERS = [];
let currentOrderId = null;

function csrfToken(){
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function toggleSidebar(){
  document.getElementById('sidebar').classList.toggle('open');
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

const STATUS_LABELS = {
  pending: 'قيد الانتظار',
  processing: 'قيد المعالجة',
  shipped: 'تم الشحن',
  delivered: 'تم التوصيل',
  cancelled: 'ملغي',
};

const STATUS_BADGE_CLASS = {
  pending: 'badge-pending',
  processing: 'badge-pending',
  shipped: 'badge-synced',
  delivered: 'badge-synced',
  cancelled: 'badge-out',
};

function loadOrders(){
  return apiRequest('{{ route("admin.orders.index") }}')
    .then(data => {
      ORDERS = data.orders || [];
      renderStats();
      render();
    })
    .catch(err => {
      document.getElementById('tbody').innerHTML = '';
      document.getElementById('emptyState').style.display = 'block';
      document.querySelector('#emptyState h3').textContent = 'تعذّر تحميل الطلبات';
      document.querySelector('#emptyState div').textContent = err.message;
    });
}

function renderStats(){
  const total = ORDERS.length;
  const pending = ORDERS.filter(o => o.status === 'pending').length;
  const processing = ORDERS.filter(o => o.status === 'processing').length;
  const delivered = ORDERS.filter(o => o.status === 'delivered').length;
  const cancelled = ORDERS.filter(o => o.status === 'cancelled').length;
  const totalSales = ORDERS.filter(o => o.status !== 'cancelled').reduce((s, o) => s + Number(o.total || 0), 0);

  document.getElementById('statsRow').innerHTML = `
    <div class="stat"><div class="stat-label">إجمالي الطلبات</div><div class="stat-value">${total}</div></div>
    <div class="stat"><div class="stat-label">قيد الانتظار</div><div class="stat-value">${pending}</div></div>
    <div class="stat"><div class="stat-label">قيد المعالجة</div><div class="stat-value">${processing}</div></div>
    <div class="stat"><div class="stat-label">تم التوصيل</div><div class="stat-value">${delivered}</div><div class="stat-tag">${cancelled} ملغي</div></div>
  `;
  document.getElementById('orderCount').textContent = `${total} طلب — إجمالي مبيعات $${totalSales.toFixed(2)}`;
}

function render(){
  const q = (document.getElementById('searchInput').value || '').trim().toLowerCase();
  const statusFilter = document.getElementById('statusFilter').value;

  const filtered = ORDERS.filter(o => {
    const matchesQ = !q || o.customer_name.toLowerCase().includes(q) || o.customer_email.toLowerCase().includes(q) || String(o.id).includes(q);
    const matchesStatus = !statusFilter || o.status === statusFilter;
    return matchesQ && matchesStatus;
  });

  const tbody = document.getElementById('tbody');
  const emptyState = document.getElementById('emptyState');

  if(filtered.length === 0){
    tbody.innerHTML = '';
    emptyState.style.display = 'block';
    document.querySelector('#emptyState h3').textContent = 'لا توجد طلبات مطابقة';
    document.querySelector('#emptyState div').textContent = 'جرّب تغيير عبارة البحث أو الفلتر.';
    return;
  }

  emptyState.style.display = 'none';
  tbody.innerHTML = filtered.map(o => `
    <tr>
      <td>#${o.id}</td>
      <td>
        <div class="prod-name">${escapeHtml(o.customer_name)}</div>
        <div class="prod-cat">${escapeHtml(o.customer_email)}</div>
      </td>
      <td>${o.items_count}</td>
      <td class="price-cell">$${Number(o.total).toFixed(2)}</td>
      <td>
        <select class="filter" style="padding:6px 8px;font-size:12px" onchange="changeStatus(${o.id}, this.value)">
          ${Object.keys(STATUS_LABELS).map(s => `<option value="${s}" ${s === o.status ? 'selected' : ''}>${STATUS_LABELS[s]}</option>`).join('')}
        </select>
      </td>
      <td>${o.date}</td>
      <td class="row-actions">
        <button class="icon-btn" title="عرض التفاصيل" onclick="viewOrder(${o.id})">👁</button>
      </td>
    </tr>
  `).join('');
}

function changeStatus(orderId, status){
  apiRequest(`{{ url('admin/orders') }}/${orderId}/status`, {
    method: 'PATCH',
    body: JSON.stringify({ status })
  }).then(data => {
    const idx = ORDERS.findIndex(o => o.id === orderId);
    if(idx > -1) ORDERS[idx].status = status;
    renderStats();
  }).catch(err => {
    alert(err.message);
    loadOrders();
  });
}

function viewOrder(orderId){
  currentOrderId = orderId;
  apiRequest(`{{ url('admin/orders') }}/${orderId}`)
    .then(data => {
      const o = data.order;
      document.getElementById('modalTitle').textContent = `تفاصيل الطلب #${o.id}`;
      document.getElementById('modalBody').innerHTML = `
        <div style="display:flex;flex-direction:column;gap:14px">
          <div><strong>العميل:</strong> ${escapeHtml(o.customer_name)} (${escapeHtml(o.customer_email)})</div>
          <div><strong>الهاتف:</strong> ${escapeHtml(o.shipping_phone || '—')}</div>
          <div><strong>المدينة:</strong> ${escapeHtml(o.shipping_city || '—')}</div>
          <div><strong>العنوان:</strong> ${escapeHtml(o.shipping_address || '—')}</div>
          ${o.notes ? `<div><strong>ملاحظات:</strong> ${escapeHtml(o.notes)}</div>` : ''}
          <div class="table-wrap" style="margin-top:6px">
            <table>
              <thead><tr><th>المنتج</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr></thead>
              <tbody>
                ${(o.items || []).map(it => `
                  <tr>
                    <td>${escapeHtml(it.name)}</td>
                    <td>${it.quantity}</td>
                    <td class="price-cell">$${Number(it.price).toFixed(2)}</td>
                    <td class="price-cell">$${Number(it.total).toFixed(2)}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
          <div style="display:flex;justify-content:space-between;font-weight:700;font-size:16px;padding-top:8px">
            <span>الإجمالي</span><span>$${Number(o.total).toFixed(2)}</span>
          </div>
        </div>
      `;
      document.getElementById('overlay').classList.add('open');
    })
    .catch(err => alert(err.message));
}

function closeModal(){
  document.getElementById('overlay').classList.remove('open');
}

function escapeHtml(str){
  const div = document.createElement('div');
  div.textContent = str ?? '';
  return div.innerHTML;
}

loadOrders();
</script>
</body>
</html>
