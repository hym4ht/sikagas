<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kontrol APAR | Sistem Monitoring Gas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f2f5;
      color: #374151;
      min-height: 100vh;
    }

    nav {
      background: #fff;
      border-bottom: 1px solid #e5e7eb;
      padding: 0 1.5rem;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .nav-brand { font-size: 1rem; font-weight: 600; color: #111827; }
    .nav-links { display: flex; gap: 6px; }
    .nav-links a {
      text-decoration: none; font-size: 0.82rem; font-weight: 500;
      color: #6b7280; padding: 7px 14px; border-radius: 8px; transition: all 0.15s;
    }
    .nav-links a:hover { background: #f3f4f6; color: #111827; }
    .nav-links a.active { background: #1f2937; color: #fff; }

    main { max-width: 580px; margin: 0 auto; padding: 1.75rem 1.25rem; }

    h2 { font-size: 1.1rem; font-weight: 600; color: #111827; margin-bottom: 1.25rem; }

    /* APAR CARD */
    .apar-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 2.5rem 1.5rem;
      text-align: center;
    }

    .apar-card p { font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem; }

    #aparStatus {
      font-size: 1.75rem;
      font-weight: 700;
      color: #059669;
      margin-bottom: 1.5rem;
    }
    #aparStatus.off { color: #dc2626; }

    .btn-toggle {
      padding: 11px 32px;
      border-radius: 10px;
      font-size: 0.875rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      font-family: 'Poppins', sans-serif;
      transition: opacity 0.15s;
    }
    .btn-toggle:hover { opacity: 0.85; }
    .btn-off { background: #dc2626; color: #fff; }
    .btn-on { background: #059669; color: #fff; }
  </style>
</head>
<body>

<nav>
  <span class="nav-brand">🔥 Monitoring Gas</span>
  <div class="nav-links">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('notifikasi') }}">Notifikasi</a>
    <a href="{{ route('apar') }}" class="active">Kontrol APAR</a>
  </div>
</nav>

<main>

  <h2>Kontrol APAR</h2>

  <div class="apar-card">
    <p>Status APAR</p>
    <h1 id="aparStatus">AKTIF</h1>
    <button id="toggleBtn" onclick="toggleAPAR()" class="btn-toggle btn-off">Matikan APAR</button>
  </div>

</main>

<script>
let aparOn = true;

function toggleAPAR() {
  aparOn = !aparOn;
  const status = document.getElementById("aparStatus");
  const btn = document.getElementById("toggleBtn");

  if (aparOn) {
    status.textContent = "AKTIF";
    status.classList.remove("off");
    btn.textContent = "Matikan APAR";
    btn.className = "btn-toggle btn-off";
  } else {
    status.textContent = "NONAKTIF";
    status.classList.add("off");
    btn.textContent = "Aktifkan APAR";
    btn.className = "btn-toggle btn-on";
  }
}
</script>
</body>
</html>