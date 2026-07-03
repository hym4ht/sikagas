<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'SIKAGAS' }} | Sistem Monitoring Gas</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  {{ $head ?? '' }}
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --bg:       #0a0d14;
      --surface:  #111827;
      --card:     rgba(17, 24, 39, 0.7);
      --border:   rgba(255,255,255,0.07);
      --text:     #e5e7eb;
      --muted:    #6b7280;
      --accent:   #f97316;
      --accent2:  #ef4444;
      --green:    #10b981;
      --yellow:   #f59e0b;
      --radius:   14px;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── Ambient glows ── */
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
      background: radial-gradient(circle, rgba(249,115,22,.12) 0%, transparent 70%);
      top: -200px; left: -200px;
      animation: drift 18s ease-in-out infinite alternate;
    }
    .ambient::after {
      content: '';
      position: absolute;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(239,68,68,.10) 0%, transparent 70%);
      bottom: -150px; right: -100px;
      animation: drift 22s ease-in-out infinite alternate-reverse;
    }
    @keyframes drift {
      from { transform: translate(0, 0); }
      to   { transform: translate(60px, 40px); }
    }

    /* ── NAVBAR ── */
    nav.sikagas-nav {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(10, 13, 20, 0.85);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border);
      padding: 0 1.5rem;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .nav-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      flex-shrink: 0;
    }
    .nav-brand .brand-icon {
      width: 34px; height: 34px;
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem;
      box-shadow: 0 0 16px rgba(249,115,22,.4);
    }
    .nav-brand .brand-text {
      font-size: 0.95rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: 0.5px;
    }

    .nav-links {
      display: flex;
      gap: 2px;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
      flex-shrink: 1;
    }
    .nav-links::-webkit-scrollbar { display: none; }

    .nav-links a,
    .nav-links button {
      display: flex; align-items: center; gap: 6px;
      text-decoration: none;
      font-size: 0.8rem;
      font-weight: 500;
      color: var(--muted);
      padding: 7px 12px;
      border-radius: 9px;
      transition: all 0.2s;
      white-space: nowrap;
      border: none;
      background: none;
      cursor: pointer;
      font-family: 'Inter', sans-serif;
    }
    .nav-links a:hover,
    .nav-links button:hover {
      background: rgba(255,255,255,0.07);
      color: var(--text);
    }
    .nav-links a.active {
      background: linear-gradient(135deg, rgba(249,115,22,.2), rgba(239,68,68,.15));
      color: var(--accent);
      border: 1px solid rgba(249,115,22,.25);
    }

    /* ── MAIN CONTENT ── */
    main.page-content {
      flex: 1;
      position: relative;
      z-index: 1;
      max-width: 900px;
      width: 100%;
      margin: 0 auto;
      padding: 2rem 1.25rem;
    }

    /* ── PAGE HEADER ── */
    .page-header {
      margin-bottom: 1.75rem;
    }
    .page-header h1 {
      font-size: 1.4rem;
      font-weight: 700;
      color: #fff;
    }
    .page-header p {
      font-size: 0.82rem;
      color: var(--muted);
      margin-top: 4px;
    }

    /* ── CARDS ── */
    .glass-card {
      background: var(--card);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      transition: border-color 0.2s, transform 0.2s;
    }
    .glass-card:hover { border-color: rgba(255,255,255,0.12); }

    /* ── GRID UTILITIES ── */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }

    /* ── BADGE ── */
    .badge {
      display: inline-flex; align-items: center; gap: 5px;
      font-size: 0.7rem;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 20px;
      letter-spacing: 0.04em;
      text-transform: uppercase;
    }
    .badge-green  { background: rgba(16,185,129,.15);  color: #34d399; border: 1px solid rgba(16,185,129,.25); }
    .badge-yellow { background: rgba(245,158,11,.15);  color: #fbbf24; border: 1px solid rgba(245,158,11,.25); }
    .badge-red    { background: rgba(239,68,68,.15);   color: #f87171; border: 1px solid rgba(239,68,68,.25); }
    .badge-gray   { background: rgba(107,114,128,.15); color: #9ca3af; border: 1px solid rgba(107,114,128,.25); }

    /* ── BUTTONS ── */
    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      padding: 12px 20px;
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: 600;
      font-family: 'Inter', sans-serif;
      border: none;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
      letter-spacing: 0.02em;
    }
    .btn:hover { transform: translateY(-2px); }
    .btn:active { transform: translateY(0); }
    .btn-primary {
      background: linear-gradient(135deg, var(--accent), var(--accent2));
      color: #fff;
      box-shadow: 0 4px 14px rgba(249,115,22,.3);
    }
    .btn-primary:hover { box-shadow: 0 6px 20px rgba(249,115,22,.45); }
    .btn-danger  { background: rgba(239,68,68,.15); color: #f87171; border: 1px solid rgba(239,68,68,.3); }
    .btn-danger:hover { background: rgba(239,68,68,.25); }
    .btn-success { background: rgba(16,185,129,.15); color: #34d399; border: 1px solid rgba(16,185,129,.3); }
    .btn-success:hover { background: rgba(16,185,129,.25); }
    .btn-ghost   { background: rgba(255,255,255,.06); color: var(--text); border: 1px solid var(--border); }
    .btn-ghost:hover { background: rgba(255,255,255,.1); }
    .btn-w100    { width: 100%; }

    /* ── FORM ── */
    .form-label {
      display: block;
      font-size: 0.75rem;
      font-weight: 600;
      color: #9ca3af;
      margin-bottom: 0.4rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .form-input, .form-select {
      width: 100%;
      background: rgba(17,24,39,.6);
      border: 1px solid var(--border);
      border-radius: 9px;
      padding: 10px 14px;
      color: var(--text);
      font-family: 'Inter', sans-serif;
      font-size: 0.85rem;
      outline: none;
      transition: all 0.2s;
    }
    .form-input:focus, .form-select:focus {
      border-color: rgba(249,115,22,.5);
      box-shadow: 0 0 0 3px rgba(249,115,22,.1);
    }
    .form-select option { background: #111827; }

    /* ── TOAST ── */
    #toast {
      position: fixed; bottom: 1.5rem; left: 50%;
      transform: translateX(-50%) translateY(80px);
      background: rgba(17,24,39,.95);
      color: #fff;
      border: 1px solid var(--border);
      font-size: 0.82rem;
      font-weight: 500;
      padding: 10px 20px;
      border-radius: 30px;
      white-space: nowrap;
      transition: transform 0.3s ease;
      z-index: 999;
      pointer-events: none;
      backdrop-filter: blur(12px);
    }
    #toast.show { transform: translateX(-50%) translateY(0); }

    /* ── RESPONSIVE ── */
    @media (max-width: 600px) {
      .grid-2, .grid-3 { grid-template-columns: 1fr 1fr; }
      .grid-2-mobile    { grid-template-columns: 1fr; }
      main.page-content { padding: 1.25rem 1rem; }
      nav.sikagas-nav   { padding: 0 1rem; }
    }
    @media (max-width: 380px) {
      .grid-3 { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<div class="ambient"></div>

<!-- NAVBAR -->
<nav class="sikagas-nav">
  <a href="{{ route('dashboard') }}" class="nav-brand">
    <div class="brand-icon">🔥</div>
    <span class="brand-text">SIKAGAS</span>
  </a>
  <div class="nav-links">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
      📊 Dashboard
    </a>
    <a href="{{ route('notifikasi') }}" class="{{ request()->routeIs('notifikasi') ? 'active' : '' }}">
      🔔 Histori
    </a>
    <a href="{{ route('apar') }}" class="{{ request()->routeIs('apar') ? 'active' : '' }}">
      🧯 APAR
    </a>
    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
      📞 Kontak
    </a>
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
      @csrf
      <button type="submit">⏻ Keluar</button>
    </form>
  </div>
</nav>

<!-- CONTENT -->
<main class="page-content">
  {{ $slot }}
</main>

<x-footer />

{{ $scripts ?? '' }}
</body>
</html>
