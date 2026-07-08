<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>POST — Sign In</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ivory:#F6F2EA;
    --ivory-deep:#EFE9DC;
    --ink:#1B1812;
    --ink-soft:#4A463D;
    --green:#3F4A3D;
    --green-deep:#2C3429;
    --line:#C9C2B4;
    --line-soft:#DED8C9;
    --brick:#8B3A3A;
    --paper-shadow: 0 30px 80px -30px rgba(27,24,18,0.35);
  }

  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}

  body{
    min-height:100vh;
    background:var(--ivory);
    font-family:'Inter',sans-serif;
    color:var(--ink);
    display:flex;
    align-items:stretch;
    justify-content:center;
    -webkit-font-smoothing:antialiased;
  }

  .stage{
    display:grid;
    grid-template-columns: 1.05fr 1fr;
    width:100%;
    min-height:100vh;
  }

  /* ---------- LEFT: editorial panel ---------- */
  .editorial{
    position:relative;
    background:
      linear-gradient(180deg, rgba(27,24,18,0.08), rgba(27,24,18,0.55)),
      radial-gradient(ellipse at 30% 20%, #4d5a48 0%, #2c3429 55%, #1e241b 100%);
    color:var(--ivory);
    padding:56px 64px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    overflow:hidden;
  }

  .editorial::before{
    content:"";
    position:absolute;
    inset:0;
    background-image:
      repeating-linear-gradient(115deg, rgba(246,242,234,0.035) 0px, rgba(246,242,234,0.035) 1px, transparent 1px, transparent 120px);
    pointer-events:none;
  }

  .brandmark{
    font-family:'Cormorant Garamond', serif;
    font-size:26px;
    letter-spacing:0.14em;
    font-weight:600;
    z-index:1;
  }
  .brandmark span{
    display:block;
    font-family:'Inter',sans-serif;
    font-size:10.5px;
    letter-spacing:0.28em;
    font-weight:400;
    color:#C9C2B4;
    margin-top:6px;
  }

  .editorial-quote{
    z-index:1;
    max-width:420px;
  }
  .editorial-quote .eyebrow{
    font-size:11px;
    letter-spacing:0.24em;
    text-transform:uppercase;
    color:#B9C2AE;
    margin:0 0 18px;
    display:flex;
    align-items:center;
    gap:10px;
  }
  .editorial-quote .eyebrow::before{
    content:"";
    width:28px;height:1px;
    background:#B9C2AE;
    display:inline-block;
  }
  .editorial-quote h1{
    font-family:'Cormorant Garamond', serif;
    font-weight:500;
    font-size:44px;
    line-height:1.28;
    margin:0 0 22px;
  }
  .editorial-quote h1 em{
    font-style:italic;
    font-weight:500;
    color:#E7D9C4;
  }
  .editorial-quote p{
    font-size:14.5px;
    line-height:1.9;
    color:#DAD5C6;
    margin:0;
    font-weight:400;
  }

  .origin-tag{
    z-index:1;
    display:flex;
    align-items:center;
    gap:14px;
    padding-top:26px;
    border-top:1px solid rgba(246,242,234,0.18);
  }
  .origin-tag .dot{
    width:7px;height:7px;border-radius:50%;
    background:#C77B55;
    flex:none;
  }
  .origin-tag .txt{
    font-size:11.5px;
    letter-spacing:0.06em;
    color:#CFC9B9;
    line-height:1.6;
  }
  .origin-tag .txt b{
    color:var(--ivory);
    font-weight:600;
  }

  /* ---------- RIGHT: form panel ---------- */
  .formside{
    background:var(--ivory);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:48px 40px;
  }

  .card{
    width:100%;
    max-width:400px;
  }

  .card-eyebrow{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:11px;
    letter-spacing:0.24em;
    text-transform:uppercase;
    color:var(--ink-soft);
    margin-bottom:18px;
  }
  .card-eyebrow::after{
    content:"";
    height:1px;flex:1;
    background:var(--line);
  }

  .card h2{
    font-family:'Cormorant Garamond', serif;
    font-weight:600;
    font-size:38px;
    margin:0 0 10px;
    color:var(--ink);
  }
  .card .sub{
    font-size:13.5px;
    color:var(--ink-soft);
    line-height:1.75;
    margin:0 0 34px;
  }
  .card .sub a{
    color:var(--green);
    text-decoration:underline;
    text-underline-offset:3px;
  }

  form{display:flex;flex-direction:column;gap:20px;}

  .field{display:flex;flex-direction:column;gap:9px;}
  .field label{
    font-size:12px;
    letter-spacing:0.03em;
    color:var(--ink);
    font-weight:500;
  }
  .field .input-wrap{
    position:relative;
    border-bottom:1px solid var(--line);
    transition:border-color .25s ease;
  }
  .field .input-wrap:focus-within{
    border-color:var(--green);
  }
  .field input{
    width:100%;
    border:none;
    background:transparent;
    outline:none;
    font-family:'Inter',sans-serif;
    font-size:15px;
    color:var(--ink);
    padding:10px 2px;
  }
  .field input::placeholder{color:#A79F8E;}

  .toggle-pass{
    position:absolute;
    right:2px;
    top:50%;
    transform:translateY(-50%);
    background:none;
    border:none;
    cursor:pointer;
    color:var(--ink-soft);
    padding:6px 4px;
    display:flex;
    align-items:center;
    justify-content:center;
  }
  .toggle-pass svg{display:block;}
  .toggle-pass:hover{color:var(--green);}

  .row-between{
    display:flex;
    align-items:center;
    justify-content:space-between;
    font-size:12.5px;
    margin-top:-4px;
  }
  .remember{
    display:flex;
    align-items:center;
    gap:8px;
    color:var(--ink-soft);
    cursor:pointer;
    user-select:none;
  }
  .remember input{
    width:14px;height:14px;
    accent-color:var(--green);
    cursor:pointer;
  }
  .row-between a{
    color:var(--ink-soft);
    text-decoration:none;
    border-bottom:1px solid var(--line);
    padding-bottom:1px;
    transition:color .2s ease, border-color .2s ease;
  }
  .row-between a:hover{color:var(--green);border-color:var(--green);}

  .error-msg{
    display:none;
    font-size:12px;
    color:var(--brick);
    background:#F4E6E1;
    border:1px solid #E3C3B9;
    padding:10px 12px;
    line-height:1.6;
  }
  .error-msg.show{display:block;}

  .btn-primary{
    margin-top:6px;
    background:var(--ink);
    color:var(--ivory);
    border:none;
    padding:16px 18px;
    font-family:'Inter',sans-serif;
    font-size:13.5px;
    letter-spacing:0.08em;
    cursor:pointer;
    transition:background .25s ease, transform .15s ease;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
  }
  .btn-primary:hover{background:var(--green-deep);}
  .btn-primary:active{transform:scale(0.99);}
  .btn-primary:focus-visible{outline:2px solid var(--green);outline-offset:3px;}

  .divider{
    display:flex;
    align-items:center;
    gap:14px;
    margin:28px 0 22px;
    color:#A79F8E;
    font-size:11px;
    letter-spacing:0.14em;
    text-transform:uppercase;
  }
  .divider::before,.divider::after{
    content:"";flex:1;height:1px;background:var(--line-soft);
  }

  .alt-actions{
    display:flex;
    flex-direction:column;
    gap:12px;
  }
  .btn-ghost{
    background:transparent;
    border:1px solid var(--line);
    color:var(--ink);
    padding:14px 16px;
    font-family:'Inter',sans-serif;
    font-size:13px;
    letter-spacing:0.02em;
    cursor:pointer;
    transition:border-color .2s ease, background .2s ease;
    display:flex;align-items:center;justify-content:center;gap:10px;
  }
  .btn-ghost:hover{border-color:var(--ink);background:var(--ivory-deep);}
  .btn-ghost:focus-visible{outline:2px solid var(--green);outline-offset:2px;}

  .footer-note{
    margin-top:34px;
    text-align:center;
    font-size:12px;
    color:var(--ink-soft);
    line-height:1.8;
  }
  .footer-note a{color:var(--green);text-decoration:underline;text-underline-offset:3px;}

  /* focus-visible baseline */
  a:focus-visible, button:focus-visible, input:focus-visible{
    outline:2px solid var(--green);
    outline-offset:2px;
  }

  @media (prefers-reduced-motion: reduce){
    *{transition:none !important; animation:none !important;}
  }

  /* ---------- Responsive ---------- */
  @media (max-width: 980px){
    .stage{grid-template-columns:1fr;}
    .editorial{
      padding:40px 32px;
      min-height:280px;
    }
    .editorial-quote h1{font-size:32px;}
    .editorial-quote p{display:none;}
    .origin-tag{padding-top:18px;}
    .formside{padding:44px 28px 60px;}
  }

  @media (max-width: 480px){
    .editorial{padding:32px 24px;min-height:220px;}
    .brandmark{font-size:22px;}
    .editorial-quote h1{font-size:26px;}
    .editorial-quote .eyebrow{margin-bottom:12px;}
    .card h2{font-size:30px;}
    .formside{padding:36px 20px 50px;}
  }
</style>
</head>
<body>

<div class="stage">

  <!-- Editorial / brand panel -->
  <aside class="editorial">
    <div class="brandmark">
      POST
      <span>PREMIUM ORIGIN STORIES &amp; THOUGHTS</span>
    </div>

    <div class="editorial-quote">
      <p class="eyebrow">Find your way in</p>
      <h1>Stories<br><em>worth wearing.</em></h1>
      <p>Considered clothing, beauty and accessories for women and children — each piece chosen for the story it begins, not just the season it fills.</p>
    </div>

    <div class="origin-tag">
      <span class="dot"></span>
      <span class="txt">Origin — <b>Como, Italy</b> · Designed in New York</span>
    </div>
  </aside>

  <!-- Form panel -->
  <main class="formside">
    <div class="card">
      <p class="card-eyebrow">Access — Members</p>
      <h2>Sign In</h2>
      <p class="sub">Join the house of POST to follow your favourite stories and private collections. Don't have an account? <a href="#">Create one</a></p>

      <div id="errorBox" class="error-msg" role="alert"></div>

      <form id="loginForm" novalidate>
        <div class="field">
          <label for="email">Email address</label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" placeholder="name@example.com" autocomplete="email" required>
          </div>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required style="padding-right:34px;">
            <button type="button" class="toggle-pass" id="togglePass" aria-label="Show password">
              <svg id="eyeOpen" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7.5 11-7.5S23 12 23 12s-4 7.5-11 7.5S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg id="eyeClosed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19.5C5 19.5 1 12 1 12a20.3 20.3 0 0 1 5.06-5.94M9.9 4.24A10.94 10.94 0 0 1 12 4.5c7 0 11 7.5 11 7.5a20.3 20.3 0 0 1-2.16 3.19M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
        </div>

        <div class="row-between">
          <label class="remember">
            <input type="checkbox" id="remember">
            Remember me
          </label>
          <a href="#">Forgot password?</a>
        </div>

        <button type="submit" class="btn-primary">
          Sign in
        </button>
      </form>

      <div class="divider">Or</div>

      <div class="alt-actions">
        <button type="button" class="btn-ghost" onclick="fakeAuth('Google')">
          <svg width="17" height="17" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.9 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 6.1 29.5 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.7-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.6 16 18.9 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 7.1 29.5 5 24 5c-7.5 0-14 4.2-17.7 10.4z"/><path fill="#4CAF50" d="M24 44c5.3 0 10.2-2 13.9-5.4l-6.4-5.4C29.4 34.9 26.8 36 24 36c-5.2 0-9.6-3.5-11.2-8.3l-6.5 5C9.9 39.6 16.4 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.3 4.3-4.2 5.7l6.4 5.4C39.9 37.3 44 31.6 44 24c0-1.3-.1-2.7-.4-3.5z"/></svg>
          Continue with Google
        </button>
        <button type="button" class="btn-ghost" onclick="fakeAuth('Apple')">
          <svg width="16" height="16" viewBox="0 0 384 512" fill="var(--ink)"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141 4 184.8 4 273.5c0 26.2 4.8 53.3 14.4 81.2 12.8 37 59 127.6 107.2 126.1 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-83.1 102.6-120.2-65.2-30.7-61.7-90-61.7-91.9zM256.4 88.9c26.9-32 24.5-61.1 23.7-71.6-23.8 1.4-51.4 16.4-67.2 34.9-17.4 19.8-27.6 44.3-25.4 71.3 25.7 2 49.1-10.9 68.9-34.6z"/></svg>
          Continue with Apple
        </button>
      </div>

      <p class="footer-note">By continuing, you agree to POST's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
    </div>
  </main>

</div>

<script>
  const form = document.getElementById('loginForm');
  const errorBox = document.getElementById('errorBox');
  const togglePass = document.getElementById('togglePass');
  const passwordInput = document.getElementById('password');

  const eyeOpen = document.getElementById('eyeOpen');
  const eyeClosed = document.getElementById('eyeClosed');

  togglePass.addEventListener('click', () => {
    const isPass = passwordInput.type === 'password';
    passwordInput.type = isPass ? 'text' : 'password';
    eyeOpen.style.display = isPass ? 'none' : 'block';
    eyeClosed.style.display = isPass ? 'block' : 'none';
    togglePass.setAttribute('aria-label', isPass ? 'Hide password' : 'Show password');
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value.trim();
    const pass = passwordInput.value;

    if (!email || !pass) {
      showError('Please fill in your email and password.');
      return;
    }
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      showError('Please enter a valid email address.');
      return;
    }

    errorBox.classList.remove('show');
    const btn = form.querySelector('.btn-primary');
    const original = btn.textContent;
    btn.textContent = 'Signing in…';
    btn.disabled = true;
    setTimeout(() => {
      btn.textContent = original;
      btn.disabled = false;
      alert('Sign in (demo) — connect this form to your backend.');
    }, 900);
  });

  function showError(msg){
    errorBox.textContent = msg;
    errorBox.classList.add('show');
  }

  function fakeAuth(provider){
    alert('Continue with ' + provider + ' (demo) — add your auth provider here.');
  }
</script>

</body>
</html>
