<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>POST — الإعدادات</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
@include('admin.partials.style')
</head>
<body>

<div class="app">
  @include('admin.partials.sidebar', ['active' => 'settings'])

  <main class="main">
    <div class="topbar">
      <div>
        <h1 class="page-title">الإعدادات والسوشيال ميديا</h1>
        <div class="page-sub">معلومات المتجر وروابط التواصل الاجتماعي — تظهر في فوتر الموقع.</div>
      </div>
    </div>

    @if(session('success'))
      <div style="background:var(--forest-dim);color:var(--forest);border:1px solid var(--forest);border-radius:9px;padding:12px 16px;margin-bottom:18px;font-weight:600">
        {{ session('success') }}
      </div>
    @endif
    @if($errors->any())
      <div style="background:var(--brick-dim);color:var(--danger);border:1px solid var(--danger);border-radius:9px;padding:12px 16px;margin-bottom:18px">
        <ul style="margin:0;padding-right:18px">
          @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
      @csrf

      <div class="table-wrap" style="padding:22px 24px;margin-bottom:22px">
        <h2 style="font-family:'Fraunces',serif;font-weight:500;font-size:20px;margin:0 0 16px">معلومات المتجر</h2>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="field">
            <label>اسم المتجر</label>
            <input name="store_name" value="{{ old('store_name', $settings['store_name'] ?? config('app.name')) }}">
          </div>
          <div class="field">
            <label>البريد الإلكتروني</label>
            <input name="store_email" type="email" value="{{ old('store_email', $settings['store_email'] ?? '') }}">
          </div>
          <div class="field">
            <label>الهاتف</label>
            <input name="store_phone" value="{{ old('store_phone', $settings['store_phone'] ?? '') }}">
          </div>
          <div class="field">
            <label>العنوان</label>
            <input name="store_address" value="{{ old('store_address', $settings['store_address'] ?? '') }}">
          </div>
        </div>
      </div>

      <div class="table-wrap" style="padding:22px 24px;margin-bottom:22px">
        <h2 style="font-family:'Fraunces',serif;font-weight:500;font-size:20px;margin:0 0 6px">روابط السوشيال ميديا</h2>
        <p class="page-sub" style="margin:0 0 16px">اترك الحقل فارغاً لإخفاء الأيقونة من الموقع.</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="field">
            <label>📸 Instagram</label>
            <input name="social_instagram" type="url" placeholder="https://instagram.com/yourstore" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}">
          </div>
          <div class="field">
            <label>📘 Facebook</label>
            <input name="social_facebook" type="url" placeholder="https://facebook.com/yourstore" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}">
          </div>
          <div class="field">
            <label>🎵 TikTok</label>
            <input name="social_tiktok" type="url" placeholder="https://tiktok.com/@yourstore" value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}">
          </div>
          <div class="field">
            <label>▶️ YouTube</label>
            <input name="social_youtube" type="url" placeholder="https://youtube.com/@yourstore" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}">
          </div>
          <div class="field">
            <label>💬 WhatsApp (رقم دولي أو رابط wa.me)</label>
            <input name="social_whatsapp" placeholder="+201234567890 أو https://wa.me/201234567890" value="{{ old('social_whatsapp', $settings['social_whatsapp'] ?? '') }}">
          </div>
          <div class="field">
            <label>✖️ X / Twitter</label>
            <input name="social_twitter" type="url" placeholder="https://x.com/yourstore" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}">
          </div>
        </div>
      </div>

      <div class="btn-group">
        <button type="submit" class="btn btn-primary">💾 حفظ الإعدادات</button>
      </div>
    </form>
  </main>
</div>

<script>
function toggleSidebar(){ document.getElementById('sidebar').classList.toggle('open'); }
</script>
</body>
</html>
