{{-- نمط بصري موحّد لكل صفحات لوحة التحكم (نفس الألوان والخطوط والمكوّنات
     المستخدمة أصلاً في resources/views/dashboard.blade.php) — تم استخراجه
     هنا كملف مشترك بدل تكراره في كل صفحة إدارة جديدة. --}}
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
