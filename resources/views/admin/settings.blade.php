@extends('admin.layout')

@section('title', 'الإعدادات')
@section('active', 'settings')
@section('page-title', 'الإعدادات والسوشيال ميديا')
@section('page-sub', 'معلومات المتجر وروابط التواصل الاجتماعي — تظهر في فوتر الموقع.')

@section('content')

  <form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    <div class="card card-pad" style="margin-bottom:22px">
      <h2 class="section-title" style="margin-bottom:16px">معلومات المتجر</h2>
      <div class="form-grid">
        <div class="field"><label>اسم المتجر</label><input name="store_name" value="{{ old('store_name', $settings['store_name'] ?? config('app.name')) }}"></div>
        <div class="field"><label>البريد الإلكتروني</label><input name="store_email" type="email" value="{{ old('store_email', $settings['store_email'] ?? '') }}"></div>
        <div class="field"><label>الهاتف</label><input name="store_phone" value="{{ old('store_phone', $settings['store_phone'] ?? '') }}"></div>
        <div class="field"><label>العنوان</label><input name="store_address" value="{{ old('store_address', $settings['store_address'] ?? '') }}"></div>
      </div>
    </div>

    <div class="card card-pad" style="margin-bottom:22px">
      <h2 class="section-title" style="margin-bottom:6px">روابط السوشيال ميديا</h2>
      <p class="page-sub" style="margin:0 0 16px">اترك الحقل فارغاً لإخفاء الأيقونة من الموقع.</p>
      <div class="form-grid">
        <div class="field"><label>📸 Instagram</label><input name="social_instagram" type="url" placeholder="https://instagram.com/yourstore" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}"></div>
        <div class="field"><label>📘 Facebook</label><input name="social_facebook" type="url" placeholder="https://facebook.com/yourstore" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}"></div>
        <div class="field"><label>🎵 TikTok</label><input name="social_tiktok" type="url" placeholder="https://tiktok.com/@yourstore" value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}"></div>
        <div class="field"><label>▶️ YouTube</label><input name="social_youtube" type="url" placeholder="https://youtube.com/@yourstore" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}"></div>
        <div class="field"><label>💬 WhatsApp (رقم دولي أو رابط wa.me)</label><input name="social_whatsapp" placeholder="+201234567890 أو https://wa.me/201234567890" value="{{ old('social_whatsapp', $settings['social_whatsapp'] ?? '') }}"></div>
        <div class="field"><label>✖️ X / Twitter</label><input name="social_twitter" type="url" placeholder="https://x.com/yourstore" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}"></div>
      </div>
    </div>

    <div class="btn-group"><button type="submit" class="btn btn-primary">💾 حفظ الإعدادات</button></div>
  </form>

@endsection
