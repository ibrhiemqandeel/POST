@extends('admin.layout')

@section('title', 'إدارة التصنيفات')
@section('active', 'categories')
@section('page-title', 'إدارة التصنيفات')
@section('page-sub')<span id="catCount">— تصنيف</span>@endsection

@section('actions')
  <button class="btn btn-primary" onclick="openModal()">+ إضافة تصنيف جديد</button>
@endsection

@section('content')

  <div class="toolbar">
    <div class="search">
      <input id="searchInput" placeholder="ابحث باسم التصنيف…" oninput="render()">
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>اسم التصنيف</th>
          <th>الرابط (slug)</th>
          <th>عدد المنتجات</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody id="tbody"></tbody>
    </table>
    <div id="emptyState" class="empty-state" style="display:none">
      <h3>لا توجد تصنيفات</h3>
      <div>أضف تصنيفاً جديداً لتنظيم المنتجات.</div>
    </div>
  </div>

  <div class="overlay" id="overlay">
    <div class="modal" style="max-width:440px">
      <div class="modal-head">
        <h2 id="modalTitle">إضافة تصنيف</h2>
        <button class="modal-close" onclick="closeModal()">×</button>
      </div>
      <div class="modal-body" style="grid-template-columns:1fr">
        <input type="hidden" id="f_id">
        <div class="field full">
          <label>اسم التصنيف</label>
          <input id="f_name" placeholder="مثال: نساء">
        </div>
      </div>
      <div class="modal-foot">
        <button class="btn btn-ghost" onclick="closeModal()">إلغاء</button>
        <button class="btn btn-primary" onclick="saveCategory()">حفظ</button>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
let CATEGORIES = [];

function loadCategories(){
  return apiRequest('{{ route("admin.categories.index") }}')
    .then(data => { CATEGORIES = data.categories || []; render(); })
    .catch(err => {
      document.getElementById('tbody').innerHTML = '';
      document.getElementById('emptyState').style.display = 'block';
      document.querySelector('#emptyState h3').textContent = 'تعذّر تحميل التصنيفات';
      document.querySelector('#emptyState div').textContent = err.message;
    });
}

function render(){
  const q = (document.getElementById('searchInput').value || '').trim().toLowerCase();
  const filtered = CATEGORIES.filter(c => !q || c.name.toLowerCase().includes(q));
  document.getElementById('catCount').textContent = `${CATEGORIES.length} تصنيف`;
  const tbody = document.getElementById('tbody');
  const emptyState = document.getElementById('emptyState');
  if(filtered.length === 0){
    tbody.innerHTML = '';
    emptyState.style.display = 'block';
    document.querySelector('#emptyState h3').textContent = 'لا توجد تصنيفات مطابقة';
    document.querySelector('#emptyState div').textContent = 'جرّب تغيير عبارة البحث.';
    return;
  }
  emptyState.style.display = 'none';
  tbody.innerHTML = filtered.map(c => `
    <tr>
      <td class="prod-name">${escapeHtml(c.name)}</td>
      <td class="prod-cat">/${escapeHtml(c.slug)}</td>
      <td>${c.products_count}</td>
      <td class="row-actions">
        <button class="icon-btn" title="تعديل" onclick="editCategory(${c.id})">✎</button>
        <button class="icon-btn danger" title="حذف" onclick="deleteCategory(${c.id})">🗑</button>
      </td>
    </tr>`).join('');
}

function openModal(){
  document.getElementById('modalTitle').textContent = 'إضافة تصنيف';
  document.getElementById('f_id').value = '';
  document.getElementById('f_name').value = '';
  document.getElementById('overlay').classList.add('open');
}

function editCategory(id){
  const c = CATEGORIES.find(x => x.id === id);
  if(!c) return;
  document.getElementById('modalTitle').textContent = 'تعديل تصنيف';
  document.getElementById('f_id').value = c.id;
  document.getElementById('f_name').value = c.name;
  document.getElementById('overlay').classList.add('open');
}

function closeModal(){ document.getElementById('overlay').classList.remove('open'); }

function saveCategory(){
  const id = document.getElementById('f_id').value;
  const name = document.getElementById('f_name').value.trim();
  if(!name){ alert('يرجى إدخال اسم التصنيف.'); return; }
  const req = id
    ? apiRequest(`{{ url('/admin/categories') }}/${id}`, { method:'PUT', body:JSON.stringify({ name }) })
    : apiRequest('{{ route("admin.categories.store") }}', { method:'POST', body:JSON.stringify({ name }) });
  req.then(() => { closeModal(); loadCategories(); }).catch(err => alert(err.message));
}

function deleteCategory(id){
  if(!confirm('هل أنت متأكد من حذف هذا التصنيف؟')) return;
  apiRequest(`{{ url('/admin/categories') }}/${id}`, { method:'DELETE' })
    .then(() => loadCategories()).catch(err => alert(err.message));
}

loadCategories();
</script>
@endpush
