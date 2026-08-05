<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>POST — Create Account</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#1c1a17;
    --cream:#f6f2ea;
    --label-bg:#efe6d8;
    --rust:#a24a34;
    --taupe:#8c7d68;
    --line:#d8cfc0;
    --line-on-ink: rgba(246,242,234,0.18);
  }
  *{box-sizing:border-box;margin:0;padding:0;}
  html,body{height:100%;}
  body{
    font-family:'Inter',sans-serif;
    background:var(--cream);
    color:var(--ink);
    -webkit-font-smoothing:antialiased;
  }
  .wrap{
    display:grid;
    grid-template-columns: 1fr 1.15fr;
    min-height:100vh;
  }

  /* ===== LEFT — editorial panel with hanging tag ===== */
  .stage{
    background:var(--ink);
    color:var(--cream);
    position:relative;
    overflow:hidden;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    padding:48px 56px;
  }
  .stage::before{
    content:"";
    position:absolute;
    inset:0;
    background-image:
      radial-gradient(circle at 18% 12%, rgba(246,242,234,0.05), transparent 40%),
      radial-gradient(circle at 85% 90%, rgba(162,74,52,0.12), transparent 45%);
    pointer-events:none;
  }
  .stage-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    position:relative;
    z-index:2;
  }
  .wordmark{
    font-family:'Fraunces',serif;
    font-size:22px;
    letter-spacing:0.14em;
    font-weight:500;
  }
  .stage-eyebrow{
    font-size:11px;
    letter-spacing:0.18em;
    text-transform:uppercase;
    color:var(--taupe);
    border:1px solid var(--line-on-ink);
    padding:6px 12px;
    border-radius:100px;
  }

  .stage-mid{
    position:relative;
    z-index:2;
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px 0;
  }

  /* the hanging garment tag — signature element */
  .tag-string{
    position:absolute;
    top:-40px;
    left:50%;
    width:1px;
    height:120px;
    background:linear-gradient(var(--line-on-ink), rgba(246,242,234,0.5));
    transform-origin:top center;
  }
  .tag{
    width:260px;
    background:var(--label-bg);
    color:var(--ink);
    border-radius:4px;
    padding:28px 24px 24px;
    position:relative;
    transform:rotate(-4deg);
    box-shadow:0 30px 60px -20px rgba(0,0,0,0.55), 0 10px 20px -10px rgba(0,0,0,0.4);
  }
  .tag::before{
    content:"";
    position:absolute;
    inset:8px;
    border:1px dashed rgba(28,26,23,0.35);
    border-radius:2px;
    pointer-events:none;
  }
  .tag-hole{
    width:12px;height:12px;
    border-radius:50%;
    background:var(--ink);
    box-shadow: inset 0 0 0 2px var(--label-bg);
    margin:0 auto 18px;
    position:relative;
  }
  .tag-hole::after{
    content:"";
    position:absolute;
    top:-30px; left:50%;
    width:1px; height:30px;
    background:rgba(28,26,23,0.4);
    transform:translateX(-50%);
  }
  .tag-brand{
    font-family:'Fraunces',serif;
    font-size:26px;
    text-align:center;
    letter-spacing:0.08em;
    margin-bottom:4px;
  }
  .tag-sub{
    text-align:center;
    font-size:10px;
    letter-spacing:0.2em;
    text-transform:uppercase;
    color:var(--taupe);
    margin-bottom:18px;
  }
  .tag-divider{
    height:1px;
    background:rgba(28,26,23,0.15);
    margin:14px 0;
  }
  .tag-row{
    display:flex;
    justify-content:space-between;
    font-size:10.5px;
    letter-spacing:0.04em;
    color:#57503f;
    margin-bottom:6px;
  }
  .tag-row b{ color:var(--ink); font-weight:600; }
  .tag-no{
    text-align:center;
    margin-top:16px;
    font-size:10px;
    letter-spacing:0.18em;
    color:var(--taupe);
  }

  .stage-bottom{
    position:relative;
    z-index:2;
  }
  .stage-headline{
    font-family:'Fraunces',serif;
    font-weight:500;
    font-size:clamp(26px, 3vw, 34px);
    line-height:1.25;
    max-width:420px;
    margin-bottom:14px;
  }
  .stage-headline em{
    font-style:italic;
    color:#d9b6a3;
  }
  .stage-text{
    font-size:13.5px;
    line-height:1.7;
    color:rgba(246,242,234,0.65);
    max-width:380px;
  }
  .stage-meta{
    display:flex;
    gap:24px;
    margin-top:26px;
    font-size:10.5px;
    letter-spacing:0.14em;
    text-transform:uppercase;
    color:var(--taupe);
  }

  /* ===== RIGHT — form panel ===== */
  .panel{
    display:flex;
    align-items:center;
    justify-content:center;
    padding:48px 32px;
  }
  .form-col{
    width:100%;
    max-width:400px;
  }
  .panel-eyebrow{
    font-size:11px;
    letter-spacing:0.18em;
    text-transform:uppercase;
    color:var(--rust);
    margin-bottom:14px;
    font-weight:600;
  }
  h1.title{
    font-family:'Fraunces',serif;
    font-weight:500;
    font-size:32px;
    margin-bottom:10px;
  }
  .subtitle{
    font-size:14px;
    color:#5c5648;
    line-height:1.6;
    margin-bottom:32px;
  }
  .subtitle a{ color:var(--ink); text-decoration:underline; text-underline-offset:3px; }

  form{ display:flex; flex-direction:column; gap:16px; }
  .field{
    display:flex;
    flex-direction:column;
    gap:6px;
  }
  .field label{
    font-size:11px;
    letter-spacing:0.08em;
    text-transform:uppercase;
    color:#6b6455;
    font-weight:600;
  }
  .row-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px;
  }
  .field input{
    font-family:'Inter',sans-serif;
    font-size:14.5px;
    padding:13px 14px;
    border:1px solid var(--line);
    background:#fff;
    border-radius:3px;
    color:var(--ink);
    outline:none;
    transition:border-color .15s ease, box-shadow .15s ease;
  }
  .field input:focus{
    border-color:var(--rust);
    box-shadow:0 0 0 3px rgba(162,74,52,0.12);
  }
  .field small.hint{
    font-size:11.5px;
    color:#8a8271;
  }
  .field small.err{
    font-size:11.5px;
    color:var(--rust);
    display:none;
  }
  .field.invalid input{ border-color:var(--rust); }
  .field.invalid small.err{ display:block; }

  .strength{
    display:flex;
    gap:5px;
    margin-top:2px;
  }
  .strength span{
    height:3px;
    flex:1;
    background:var(--line);
    border-radius:2px;
    transition:background .2s ease;
  }

  .terms{
    display:flex;
    align-items:flex-start;
    gap:10px;
    font-size:12.5px;
    color:#5c5648;
    line-height:1.5;
    margin-top:4px;
  }
  .terms input{ margin-top:3px; accent-color:var(--ink); }
  .terms a{ color:var(--ink); text-decoration:underline; text-underline-offset:2px; }

  .btn-primary{
    margin-top:8px;
    background:var(--ink);
    color:var(--cream);
    border:none;
    padding:15px;
    font-size:13.5px;
    letter-spacing:0.06em;
    text-transform:uppercase;
    font-weight:600;
    border-radius:3px;
    cursor:pointer;
    transition:background .15s ease, transform .1s ease;
  }
  .btn-primary:hover{ background:#33302a; }
  .btn-primary:active{ transform:translateY(1px); }

  .divider{
    display:flex;
    align-items:center;
    gap:14px;
    margin:26px 0 20px;
    color:#a39c8b;
    font-size:11px;
    letter-spacing:0.1em;
    text-transform:uppercase;
  }
  .divider::before,.divider::after{
    content:"";
    flex:1;
    height:1px;
    background:var(--line);
  }

  .socials{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:12px;
  }
  .btn-social{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:12px;
    border:1px solid var(--line);
    background:#fff;
    border-radius:3px;
    font-size:13px;
    color:var(--ink);
    cursor:pointer;
    text-decoration:none;
    transition:border-color .15s ease, background .15s ease;
  }
  .btn-social:hover{ border-color:#b8ab90; background:#fbf9f5; }
  .btn-social svg{ width:16px; height:16px; }

  .footer-note{
    margin-top:28px;
    font-size:12px;
    color:#9a927e;
    text-align:center;
    line-height:1.6;
  }
  .footer-note a{ color:#6b6455; text-decoration:underline; text-underline-offset:2px; }

  @media (max-width: 920px){
    .wrap{ grid-template-columns:1fr; }
    .stage{ display:none; }
    .panel{ padding:40px 22px; }
  }
</style>
</head>
<body>

<div class="wrap">

  <!-- LEFT: editorial stage with hanging garment tag -->
  <section class="stage">
    <div class="stage-top">
      <div class="wordmark">POST</div>
      <div class="stage-eyebrow">House of Origin</div>
    </div>

    <div class="stage-mid">
      <div style="position:relative;">
        <div class="tag-string"></div>
        <div class="tag">
          <div class="tag-hole"></div>
          <div class="tag-brand">POST</div>
          <div class="tag-sub">Origin Stories</div>
          <div class="tag-divider"></div>
          <div class="tag-row"><span>Origin</span><b>Como, Italy</b></div>
          <div class="tag-row"><span>Design</span><b>New York</b></div>
          <div class="tag-row"><span>Status</span><b>New Member</b></div>
          <div class="tag-no">No. 002 — Joining</div>
        </div>
      </div>
    </div>

    <div class="stage-bottom">
      <div class="stage-headline">Every story <em>starts</em><br>with your name.</div>
      <p class="stage-text">Join the house of POST to follow your favourite stories, save your private collections, and get early access to what's next.</p>
      <div class="stage-meta">
        <span>Traceable materials</span>
        <span>Carbon-neutral shipping</span>
      </div>
    </div>
  </section>

  <!-- RIGHT: sign-up form -->
  <section class="panel">
    <div class="form-col">
      <div class="panel-eyebrow">Join the House</div>
      <h1 class="title">Create Account</h1>
      <p class="subtitle">Already have an account? <a href="/login">Sign in</a></p>

      <form id="signupForm" novalidate>
        <div class="row-2">
          <div class="field">
            <label for="firstName">First name</label>
            <input type="text" id="firstName" name="firstName" placeholder="e.g. Leyla" required>
          </div>
          <div class="field">
            <label for="lastName">Last name</label>
            <input type="text" id="lastName" name="lastName" placeholder="e.g. Hassan" required>
          </div>
        </div>

        <div class="field" id="emailField">
          <label for="email">Email address</label>
          <input type="email" id="email" name="email" placeholder="name@example.com" required>
          <small class="err">Please enter a valid email address</small>
        </div>

        <div class="field" id="passField">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="At least 8 characters" required minlength="8">
          <div class="strength"><span></span><span></span><span></span><span></span></div>
          <small class="hint">Use a mix of letters and numbers for a stronger password</small>
        </div>

        <div class="field" id="confirmField">
          <label for="confirm">Confirm password</label>
          <input type="password" id="confirm" name="confirm" placeholder="Re-enter your password" required>
          <small class="err">Passwords do not match</small>
        </div>

        <label class="terms">
          <input type="checkbox" id="agree" required>
          <span>I agree to POST's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
        </label>

        <button type="submit" class="btn-primary">Create Account</button>
      </form>

      <div class="divider">Or</div>

      <div class="socials">
        <a class="btn-social" href="https://post-z44n.onrender.com/auth/google">
          <svg viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.6 32.9 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 6.1 29.5 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.7-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.5 16 18.9 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 6.1 29.5 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.4 26.7 36 24 36c-5.2 0-9.6-3.1-11.3-7.6l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.3 4.3-4.2 5.6l6.2 5.2C40.9 36 44 30.9 44 24c0-1.3-.1-2.7-.4-3.5z"/></svg>
          Google
        </a>
        <a class="btn-social" href="https://post-z44n.onrender.com/auth/facebook">
          <svg viewBox="0 0 24 24" fill="#1877F2"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.16 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22c4.78-.78 8.44-4.94 8.44-9.94z"/></svg>
          Facebook
        </a>
      </div>

      <p class="footer-note">By creating an account, you agree to POST's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
    </div>
  </section>

</div>

<script>
  const form = document.getElementById('signupForm');
  const password = document.getElementById('password');
  const confirm = document.getElementById('confirm');
  const email = document.getElementById('email');
  const strengthBars = document.querySelectorAll('.strength span');

  password.addEventListener('input', () => {
    const val = password.value;
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['#d8cfc0', '#a24a34', '#c98a5e', '#7a9a6a'];
    strengthBars.forEach((bar, i) => {
      bar.style.background = i < score ? colors[score - 1] : '#d8cfc0';
    });
  });

  function isValidEmail(v){
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    let valid = true;

    const emailField = document.getElementById('emailField');
    if (!isValidEmail(email.value)) {
      emailField.classList.add('invalid');
      valid = false;
    } else {
      emailField.classList.remove('invalid');
    }

    const confirmField = document.getElementById('confirmField');
    if (password.value !== confirm.value || confirm.value === '') {
      confirmField.classList.add('invalid');
      valid = false;
    } else {
      confirmField.classList.remove('invalid');
    }

    if (password.value.length < 8) {
      valid = false;
    }

    if (!valid) return;

    // Replace with real submission logic / API call
    alert('Account created successfully! (Demo form)');
    form.reset();
    strengthBars.forEach(bar => bar.style.background = '#d8cfc0');
  });
</script>

</body>
</html>
