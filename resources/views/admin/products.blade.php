@extends('admin.layout')

@section('title', 'إدارة المنتجات')
@section('active', 'products')
@section('page-title', 'إدارة المنتجات')
@section('page-sub')<span id="prodCount">— منتج في الكتالوج</span>@endsection

@section('actions')
  <button class="btn btn-ghost" onclick="exportJSON()">📥 تصدير JSON</button>
  <button class="btn btn-ghost" onclick="syncCjInventory()">🔄 مزامنة المخزون</button>
  <button class="btn btn-ghost" onclick="openCjModal()">🔗 استيراد من CJ</button>
  <button class="btn btn-primary" onclick="openModal()">+ إضافة منتج</button>
@endsection

@section('content')

  <div class="stats" id="statsRow"></div>

  <div class="toolbar">
    <div class="search">
      <input id="searchInput" placeholder="ابحث باسم المنتج، الفئة أو المورد…" oninput="render()">
    </div>
    <select class="filter" id="catFilter" onchange="render()">
      <option value="">كل الفئات</option>
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
          <th>المخزون</th>
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

  {{-- Modal: إضافة/تعديل منتج --}}
  <div class="overlay" id="overlay">
    <div class="modal">
      <div class="modal-head">
        <h2 id="modalTitle">إضافة منتج</h2>
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
          <select id="f_cat"></select>
        </div>
        <div class="field">
          <label>رابط صورة المنتج (URL)</label>
          <input id="f_img" placeholder="https://example.com/image.jpg">
        </div>
        <div class="field">
          <label>المورد المرتبط</label>
          <select id="f_supplier_id"><option value="">— بدون —</option></select>
        </div>
        <div class="field">
          <label>منصة المورد (نص حر)</label>
          <input id="f_platform" placeholder="CJ Dropshipping / مورد محلي…">
        </div>
        <div class="field">
          <label>اسم المورد (نص حر)</label>
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
      </div>
      <div class="modal-foot">
        <button class="btn btn-ghost" onclick="closeModal()">إلغاء</button>
        <button class="btn btn-primary" onclick="saveProduct()">حفظ البيانات</button>
      </div>
    </div>
  </div>

  {{-- Modal: استيراد من CJ Dropshipping --}}
  <div class="overlay" id="cjOverlay">
    <div class="modal" style="max-width:840px">
      <div class="modal-head">
        <h2>استيراد منتجات من CJ Dropshipping</h2>
        <button class="modal-close" onclick="closeCjModal()">×</button>
      </div>
      <div class="modal-body" style="grid-template-columns:1fr;gap:14px">
        <div class="section-note">
          اختر الفئة وهامش الربح قبل الاستيراد. يمكنك تعديل أي قيمة (السعر، الصورة، الفئة…) لاحقاً من زر "تعديل" في الجدول الرئيسي.
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
          <div class="field" style="flex:1;min-width:160px">
            <label>الفئة عند الاستيراد</label>
            <select id="cjCategory"></select>
          </div>
          <div class="field" style="flex:1;min-width:120px">
            <label>هامش الربح %</label>
            <input id="cjMargin" type="number" min="0" step="1" value="30">
          </div>
          <button class="btn btn-primary" onclick="loadCjProducts(1)">تحميل منتجات CJ</button>
        </div>
        <div id="cjList" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;max-height:440px;overflow-y:auto"></div>
      </div>
      <div class="modal-foot">
        <button class="btn btn-ghost" onclick="closeCjModal()">إغلاق</button>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
/* لوحة إدارة المنتجات — متصلة فعلياً بقاعدة البيانات عبر /admin/products.
   مساعدات csrfToken/apiRequest/escapeHtml/escapeAttr/toggleSidebar موجودة في الـ layout. */
let products = [];
let CATEGORY_LABELS = {};   // slug → name (تُبنى ديناميكياً من الفئات الحقيقية)
let CATEGORIES = [];
let SUPPLIERS = [];

const PRODUCTS_URL = '{{ route("admin.products.index") }}';

function fillCategorySelects(){
  const opts = CATEGORIES.map(c => `<option value="${escapeAttr(c.slug)}">${escapeHtml(c.name)}</option>`).join('');
  document.getElementById('f_cat').innerHTML = opts || '<option value="">— لا فئات —</option>';
  document.getElementById('cjCategory').innerHTML = opts || '<option value="">— لا فئات —</option>';
  const catFilter = document.getElementById('catFilter');
  catFilter.innerHTML = '<option value="">كل الفئات</option>' + CATEGORIES.map(c => `<option value="${escapeAttr(c.slug)}">${escapeHtml(c.name)}</option>`).join('');
  document.getElementById('f_supplier_id').innerHTML = '<option value="">— بدون —</option>' +
    SUPPLIERS.map(s => `<option value="${s.id}">${escapeHtml(s.name)}</option>`).join('');
}

function loadProducts(){
  return apiRequest(PRODUCTS_URL)
    .then(data => {
      products   = data.products || [];
      CATEGORIES = data.categories || [];
      SUPPLIERS  = data.suppliers || [];
      CATEGORY_LABELS = Object.fromEntries(CATEGORIES.map(c => [c.slug, c.name]));
      fillCategorySelects();
      render();
    })
    .catch(err => {
      document.getElementById('tbody').innerHTML = '';
      document.getElementById('emptyState').style.display = 'block';
      document.querySelector('#emptyState h3').textContent = 'تعذّر تحميل المنتجات';
      document.querySelector('#emptyState div').textContent = err.message;
    });
}

function marginPct(cost, price){
  if(!price || price <= 0 || price < cost) return 0;
  return Math.round(((price - cost) / price) * 100);
}

function statusBadge(status){
  const map = {
    synced:  {cls:'badge-synced',  label:'متزامن'},
    pending: {cls:'badge-pending', label:'قيد المزامنة'},
    out:     {cls:'badge-out',     label:'نفد المخزون'},
  };
  const s = map[status] || map.synced;
  return `<span class="badge ${s.cls}"><span class="dot"></span>${s.label}</span>`;
}

function render(){
  const q = document.getElementById('searchInput').value.trim().toLowerCase();
  const catF = document.getElementById('catFilter').value;
  const statusF = document.getElementById('statusFilter').value;

  const filtered = products.filter(p => {
    const matchQ = !q || p.name.toLowerCase().includes(q) || (p.supplier||'').toLowerCase().includes(q) || (p.platform||'').toLowerCase().includes(q);
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
    tbody.innerHTML = filtered.map(p => {
      const m = marginPct(p.cost, p.price);
      const safeName = escapeHtml(p.name);
      const initials = escapeHtml(p.name.split(' ')[0].slice(0,2));
      const imgContent = p.img
        ? `<img src="${escapeAttr(p.img)}" alt="${escapeAttr(p.name)}" onerror="this.onerror=null; this.parentNode.innerText=${JSON.stringify(p.name.split(' ')[0].slice(0,2))};">`
        : initials;
      const stockBadge = p.stock <= 0
        ? '<span class="badge badge-out">0</span>'
        : (p.stock <= 5 ? `<span class="badge badge-pending">${p.stock}</span>` : p.stock);
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
        <td><div class="supplier-src">${escapeHtml(p.platform)} · ${escapeHtml(p.supplier)}</div></td>
        <td class="price-cell">$${p.cost.toFixed(2)}</td>
        <td class="price-cell">$${p.price.toFixed(2)}</td>
        <td><span class="margin ${m < 30 ? 'low' : ''}">${m}%</span></td>
        <td>${stockBadge}</td>
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
  const synced = products.filter(p => p.status === 'synced').length;
  const out = products.filter(p => p.status === 'out' || p.stock <= 0).length;
  const avgMargin = total ? Math.round(products.reduce((s,p) => s + marginPct(p.cost, p.price), 0) / total) : 0;
  document.getElementById('statsRow').innerHTML = `
    <div class="stat"><div class="stat-label">إجمالي المنتجات</div><div class="stat-value">${total}</div><div class="stat-tag">عبر ${new Set(products.map(p=>p.platform)).size} موردين</div></div>
    <div class="stat"><div class="stat-label">متزامن مع المورد</div><div class="stat-value">${synced}</div><div class="stat-tag">جاهز للبيع</div></div>
    <div class="stat"><div class="stat-label">نفد المخزون</div><div class="stat-value">${out}</div><div class="stat-tag" style="color:var(--brick)">يحتاج متابعة</div></div>
    <div class="stat"><div class="stat-label">متوسط هامش الربح</div><div class="stat-value">${avgMargin}%</div><div class="stat-tag">عبر الكتالوج</div></div>`;
}

function openModal(isEdit = false){
  document.getElementById('overlay').classList.add('open');
  if(!isEdit){
    document.getElementById('modalTitle').textContent = 'إضافة منتج';
    document.getElementById('f_id').value = '';
    ['f_name','f_img','f_supplier','f_cost','f_price','f_stock','f_platform'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('f_supplier_id').value = '';
    if(CATEGORIES[0]) document.getElementById('f_cat').value = CATEGORIES[0].slug;
    document.getElementById('f_status').value = 'pending';
  } else {
    document.getElementById('modalTitle').textContent = 'تعديل بيانات المنتج';
  }
  updateMarginPreview();
}
function closeModal(){ document.getElementById('overlay').classList.remove('open'); }

function autoAdjustStatus(){
  const stock = parseInt(document.getElementById('f_stock').value) || 0;
  const sel = document.getElementById('f_status');
  if(stock === 0) sel.value = 'out';
  else if(sel.value === 'out') sel.value = 'synced';
}

function updateMarginPreview(){
  const cost = parseFloat(document.getElementById('f_cost').value) || 0;
  const price = parseFloat(document.getElementById('f_price').value) || 0;
  const m = marginPct(cost, price);
  const box = document.getElementById('marginPreview');
  const amt = document.getElementById('marginAmt');
  if(price > 0){
    amt.textContent = `${m}% ($${(price - cost).toFixed(2)}+)`;
    box.classList.toggle('low', m < 30);
  } else { amt.textContent = '—'; box.classList.remove('low'); }
}

function editProduct(id){
  const p = products.find(prod => prod.id === id);
  if(!p) return;
  document.getElementById('f_id').value = p.id;
  document.getElementById('f_name').value = p.name;
  document.getElementById('f_cat').value = p.cat;
  document.getElementById('f_img').value = p.img || '';
  document.getElementById('f_supplier_id').value = p.supplier_id || '';
  document.getElementById('f_platform').value = p.platform === '—' ? '' : p.platform;
  document.getElementById('f_supplier').value = p.supplier === '—' ? '' : p.supplier;
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
  if(!name || price <= 0){ alert('يرجى إدخال اسم المنتج وسعر بيع صحيح أكبر من 0.'); return; }

  const prodData = {
    name,
    category: document.getElementById('f_cat').value,
    image: document.getElementById('f_img').value.trim(),
    supplier_id: document.getElementById('f_supplier_id').value || null,
    supplier_platform: document.getElementById('f_platform').value.trim(),
    supplier_name: document.getElementById('f_supplier').value.trim() || 'مورد غير محدد',
    cost_price: cost,
    price,
    stock: parseInt(document.getElementById('f_stock').value) || 0,
    sync_status: document.getElementById('f_status').value,
  };

  const request = id
    ? apiRequest(`{{ url('/admin/products') }}/${id}`, { method:'PUT', body:JSON.stringify(prodData) })
    : apiRequest(PRODUCTS_URL, { method:'POST', body:JSON.stringify(prodData) });

  request.then(() => { closeModal(); return loadProducts(); }).catch(err => alert(err.message));
}

function deleteProduct(id){
  if(!confirm('هل أنت متأكد من رغبتك في حذف هذا المنتج من الكتالوج؟')) return;
  apiRequest(`{{ url('/admin/products') }}/${id}`, { method:'DELETE' })
    .then(() => loadProducts()).catch(err => alert(err.message));
}

/* ===== CJ Dropshipping ===== */
function openCjModal(){ document.getElementById('cjOverlay').classList.add('open'); document.getElementById('cjList').innerHTML=''; }
function closeCjModal(){ document.getElementById('cjOverlay').classList.remove('open'); }

function loadCjProducts(page){
  const list = document.getElementById('cjList');
  list.innerHTML = '<p style="opacity:.6">جاري التحميل…</p>';
  apiRequest(`{{ route('admin.cj.products') }}?page=${page}&page_size=20`)
    .then(data => {
      const items = data?.data?.data?.list || data?.data?.list || [];
      if(!items.length){ list.innerHTML = '<p style="opacity:.6">لا توجد منتجات لعرضها.</p>'; return; }
      list.innerHTML = items.map(item => {
        const pidRaw = item.pid || item.productId || '';
        const nameRaw = item.productNameEn || item.productName || 'منتج بدون اسم';
        const img = item.productImage || item.image || '';
        const wholesale = parseFloat(item.wholesale_price ?? item.productPrice ?? 0);
        const sell = parseFloat(item.sellPrice ?? 0);
        return `
          <div style="border:1px solid var(--line);border-radius:10px;padding:12px;display:flex;flex-direction:column;gap:8px">
            <div class="thumb" style="width:100%;height:110px;border-radius:8px">
              ${img ? `<img src="${escapeAttr(img)}" alt="${escapeAttr(nameRaw)}" style="width:100%;height:100%;object-fit:cover">` : escapeHtml(nameRaw.slice(0,2))}
            </div>
            <div style="font-size:13px;font-weight:600;line-height:1.4">${escapeHtml(nameRaw)}</div>
            <div style="font-size:12px;color:var(--ink-soft)">جملة: $${wholesale.toFixed(2)} → بيع مقترح: $${sell.toFixed(2)}</div>
            <button class="btn btn-primary" style="width:100%" data-cj-pid="${escapeAttr(pidRaw)}" onclick="importCjProduct(this.dataset.cjPid, this)">استيراد للكتالوج</button>
          </div>`;
      }).join('');
    })
    .catch(err => { list.innerHTML = `<p style="color:var(--danger)">${escapeHtml(err.message)}</p>`; });
}

function importCjProduct(pid, btn){
  btn.disabled = true; btn.textContent = 'جاري الاستيراد…';
  const category = document.getElementById('cjCategory').value;
  const margin = parseFloat(document.getElementById('cjMargin').value) || 0;
  apiRequest('{{ route('admin.cj.import') }}', { method:'POST', body:JSON.stringify({ pid, category, margin }) })
    .then(() => { btn.textContent = 'تم الاستيراد ✓'; loadProducts(); })
    .catch(err => { alert(err.message); btn.disabled = false; btn.textContent = 'استيراد للكتالوج'; });
}

function syncCjInventory(){
  if(!confirm('سيتم جلب أحدث المنتجات من CJ Dropshipping وتحديث الكتالوج. متابعة؟')) return;
  apiRequest('{{ route("admin.cj.sync") }}', { method:'POST', body:JSON.stringify({}) })
    .then(data => { alert(data.success ? 'تمت المزامنة بنجاح.' : 'فشلت المزامنة.'); loadProducts(); })
    .catch(err => alert(err.message));
}

function exportJSON(){
  const dataStr = 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(products, null, 2));
  const a = document.createElement('a');
  a.setAttribute('href', dataStr);
  a.setAttribute('download', 'post_catalog_products.json');
  document.body.appendChild(a); a.click(); a.remove();
}

loadProducts().then(() => {
  // فتح مودال الاستيراد تلقائياً عند القدوم من رابط "استيراد من CJ" في الشريط الجانبي
  if(new URLSearchParams(location.search).get('import') === '1') openCjModal();
});
</script>
@endpush
