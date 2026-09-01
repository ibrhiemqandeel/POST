@extends('admin.layout')

@section('title', 'إدارة العملاء')
@section('active', 'customers')
@section('page-title', 'إدارة العملاء')
@section('page-sub')<span id="custCount">— عميل</span>@endsection

@section('content')

  <div class="stats" id="statsRow"></div>

  <div class="toolbar">
    <div class="search">
      <input id="searchInput" placeholder="ابحث بالاسم أو البريد الإلكتروني…" oninput="onSearch()">
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>الاسم</th>
          <th>البريد الإلكتروني</th>
          <th>عدد الطلبات</th>
          <th>تاريخ الانضمام</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody id="tbody"></tbody>
    </table>
    <div id="emptyState" class="empty-state" style="display:none">
      <h3>لا يوجد عملاء</h3>
      <div>جرّب تغيير عبارة البحث.</div>
    </div>
  </div>

  <div class="overlay" id="overlay">
    <div class="modal">
      <div class="modal-head">
        <h2 id="modalTitle">تفاصيل العميل</h2>
        <button class="modal-close" onclick="closeModal()">×</button>
      </div>
      <div class="modal-body" id="modalBody" style="display:block"></div>
      <div class="modal-foot">
        <button class="btn btn-ghost" onclick="closeModal()">إغلاق</button>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
let CUSTOMERS = [];
let searchTimer = null;

function onSearch(){ clearTimeout(searchTimer); searchTimer = setTimeout(loadCustomers, 300); }

function loadCustomers(){
  const q = document.getElementById('searchInput').value.trim();
  const url = '{{ route("admin.customers.index") }}' + (q ? `?q=${encodeURIComponent(q)}` : '');
  return apiRequest(url)
    .then(data => { CUSTOMERS = data.customers || []; render(); })
    .catch(err => {
      document.getElementById('tbody').innerHTML = '';
      document.getElementById('emptyState').style.display = 'block';
      document.querySelector('#emptyState h3').textContent = 'تعذّر تحميل العملاء';
      document.querySelector('#emptyState div').textContent = err.message;
    });
}

function render(){
  const total = CUSTOMERS.length;
  const withOrders = CUSTOMERS.filter(c => c.orders_count > 0).length;
  const admins = CUSTOMERS.filter(c => c.is_admin).length;
  document.getElementById('statsRow').innerHTML = `
    <div class="stat"><div class="stat-label">إجمالي العملاء</div><div class="stat-value">${total}</div></div>
    <div class="stat"><div class="stat-label">لديهم طلبات</div><div class="stat-value">${withOrders}</div></div>
    <div class="stat"><div class="stat-label">حسابات إدارية</div><div class="stat-value">${admins}</div></div>`;
  document.getElementById('custCount').textContent = `${total} عميل`;

  const tbody = document.getElementById('tbody');
  const emptyState = document.getElementById('emptyState');
  if(CUSTOMERS.length === 0){
    tbody.innerHTML = '';
    emptyState.style.display = 'block';
    document.querySelector('#emptyState h3').textContent = 'لا يوجد عملاء مطابقون';
    document.querySelector('#emptyState div').textContent = 'جرّب تغيير عبارة البحث.';
    return;
  }
  emptyState.style.display = 'none';
  tbody.innerHTML = CUSTOMERS.map(c => `
    <tr>
      <td class="prod-name">${escapeHtml(c.name)} ${c.is_admin ? '<span class="badge badge-synced">أدمن</span>' : ''}</td>
      <td class="prod-cat">${escapeHtml(c.email)}</td>
      <td>${c.orders_count}</td>
      <td>${c.joined}</td>
      <td class="row-actions"><button class="icon-btn" title="عرض التفاصيل" onclick="viewCustomer(${c.id})">👁</button></td>
    </tr>`).join('');
}

function viewCustomer(id){
  apiRequest(`{{ url('/admin/customers') }}/${id}`)
    .then(data => {
      const c = data.customer;
      const orders = data.orders || [];
      document.getElementById('modalTitle').textContent = `${c.name}`;
      document.getElementById('modalBody').innerHTML = `
        <div style="display:flex;flex-direction:column;gap:12px">
          <div><strong>البريد الإلكتروني:</strong> ${escapeHtml(c.email)}</div>
          <div><strong>تاريخ الانضمام:</strong> ${c.joined}</div>
          ${c.is_admin ? '<div><span class="badge badge-synced">حساب إداري</span></div>' : ''}
          <h3 style="font-family:\'Fraunces\',serif;font-weight:500;margin:6px 0 0">سجل الطلبات</h3>
          ${orders.length === 0 ? '<div class="prod-cat">لا توجد طلبات لهذا العميل بعد.</div>' : `
            <div class="table-wrap">
              <table>
                <thead><tr><th>رقم الطلب</th><th>الإجمالي</th><th>الحالة</th><th>التاريخ</th></tr></thead>
                <tbody>
                  ${orders.map(o => `<tr><td>#${o.id}</td><td class="price-cell">$${Number(o.total).toFixed(2)}</td><td>${escapeHtml(o.status)}</td><td>${o.date}</td></tr>`).join('')}
                </tbody>
              </table>
            </div>`}
        </div>`;
      document.getElementById('overlay').classList.add('open');
    })
    .catch(err => alert(err.message));
}

function closeModal(){ document.getElementById('overlay').classList.remove('open'); }

loadCustomers();
</script>
@endpush
