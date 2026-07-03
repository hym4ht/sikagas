<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | SIKAGAS</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --accent:  #f97316;
      --accent2: #ef4444;
      --border:  rgba(255,255,255,0.07);
      --muted:   #6b7280;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #0a0d14;
      color: #e5e7eb;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1.5rem;
      overflow: hidden;
      position: relative;
    }

    /* Ambient Glows */
    .ambient {
      position: fixed;
      inset: 0;
      pointer-events: none;
      z-index: 0;
      overflow: hidden;
    }
    .ambient::before {
      content: '';
      position: absolute;
      width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(249,115,22,.15) 0%, transparent 70%);
      top: -200px; left: -200px;
      animation: drift 18s ease-in-out infinite alternate;
    }
    .ambient::after {
      content: '';
      position: absolute;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(239,68,68,.12) 0%, transparent 70%);
      bottom: -150px; right: -100px;
      animation: drift 22s ease-in-out infinite alternate-reverse;
    }
    @keyframes drift {
      from { transform: translate(0,0); }
      to   { transform: translate(60px, 40px); }
    }

    /* CARD */
    .login-card {
      position: relative;
      z-index: 10;
      background: rgba(17, 24, 39, 0.65);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid var(--border);
      border-radius: 20px;
      width: 100%;
      max-width: 420px;
      padding: 2.5rem 2rem;
      box-shadow: 0 25px 50px rgba(0,0,0,.5);
      animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* BRAND */
    .brand-header { text-align: center; margin-bottom: 2rem; }

    .brand-logo {
      width: 60px; height: 60px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 16px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.75rem;
      margin: 0 auto 1rem;
      box-shadow: 0 0 30px rgba(249,115,22,.35);
      animation: pulse-logo 3s ease-in-out infinite;
    }
    @keyframes pulse-logo {
      0%, 100% { box-shadow: 0 0 30px rgba(249,115,22,.35); }
      50%       { box-shadow: 0 0 50px rgba(249,115,22,.55); }
    }

    .brand-title {
      font-size: 1.4rem;
      font-weight: 800;
      color: #fff;
      letter-spacing: 0.06em;
    }
    .brand-subtitle {
      font-size: 0.78rem;
      color: var(--muted);
      margin-top: 4px;
      line-height: 1.5;
    }

    /* DIVIDER */
    .divider {
      width: 40px; height: 2px;
      background: linear-gradient(90deg, var(--accent), var(--accent2));
      border-radius: 2px;
      margin: 0.75rem auto;
    }

    /* FORM */
    .form-group { margin-bottom: 1.25rem; }

    .form-label {
      display: block;
      font-size: 0.72rem;
      font-weight: 700;
      color: #9ca3af;
      margin-bottom: 0.45rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
    }

    .form-input {
      width: 100%;
      background: rgba(10, 13, 20, 0.5);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 12px 16px;
      color: #fff;
      font-family: 'Inter', sans-serif;
      font-size: 0.875rem;
      outline: none;
      transition: all 0.2s;
    }
    .form-input::placeholder { color: #4b5563; }
    .form-input:focus {
      border-color: rgba(249,115,22,.5);
      box-shadow: 0 0 0 3px rgba(249,115,22,.1);
      background: rgba(10, 13, 20, 0.8);
    }

    /* REMEMBER */
    .remember-group {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.75rem;
      font-size: 0.8rem;
      color: var(--muted);
    }
    .remember-me {
      display: flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
    }
    .remember-me input { accent-color: var(--accent); cursor: pointer; }

    /* LOGIN BUTTON */
    .btn-login {
      display: block;
      width: 100%;
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 13px;
      font-size: 0.9rem;
      font-weight: 700;
      cursor: pointer;
      font-family: 'Inter', sans-serif;
      letter-spacing: 0.04em;
      transition: all 0.2s;
      box-shadow: 0 4px 16px rgba(249,115,22,.3);
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(249,115,22,.45);
    }
    .btn-login:active { transform: translateY(0); }

    /* ERRORS */
    .error-box {
      background: rgba(239,68,68,.12);
      border: 1px solid rgba(239,68,68,.3);
      color: #fca5a5;
      padding: 0.75rem 1rem;
      border-radius: 10px;
      font-size: 0.8rem;
      margin-bottom: 1.5rem;
      list-style: none;
    }
    .error-box li { margin-bottom: 2px; }
    .error-box li:last-child { margin-bottom: 0; }

    /* FOOTER NOTE */
    .footer-note {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.7rem;
      color: #374151;
    }
  </style>
</head>
<body>

<div class="ambient"></div>

<div class="login-card">
  <div class="brand-header">
    <div class="brand-logo">🔥</div>
    <h1 class="brand-title">SIKAGAS</h1>
    <div class="divider"></div>
    <p class="brand-subtitle">Sistem Informasi &amp; Keamanan Gas LPG</p>
  </div>

  @if ($errors->any())
    <ul class="error-box">
      @foreach ($errors->all() as $error)
        <li>⚠️ {{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
      <label for="email" class="form-label">Email</label>
      <input
        type="email"
        name="email"
        id="email"
        class="form-input"
        placeholder="admin@sikagas.id"
        value="{{ old('email') }}"
        required
        autofocus
      >
    </div>

    <div class="form-group">
      <label for="password" class="form-label">Password</label>
      <input
        type="password"
        name="password"
        id="password"
        class="form-input"
        placeholder="••••••••"
        required
      >
    </div>

    <div class="remember-group">
      <label class="remember-me">
        <input type="checkbox" name="remember">
        Ingat Saya
      </label>
    </div>

    <button type="submit" class="btn-login">🔐 Masuk</button>
  </form>

  <div class="footer-note">SIKAGAS &times; Universitas Harkat Negeri Tegal</div>
</div>

</body>
</html>
