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
      padding: 0 1rem;
      height: 56px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem;
    }
    .nav-brand { font-size: 0.9rem; font-weight: 600; color: #111827; white-space: nowrap; flex-shrink: 0; }
    .nav-links { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; flex-shrink: 1; }
    .nav-links::-webkit-scrollbar { display: none; }
    .nav-links a {
      text-decoration: none; font-size: 0.78rem; font-weight: 500;
      color: #6b7280; padding: 6px 10px; border-radius: 8px; transition: all 0.15s; white-space: nowrap;
    }
    .nav-links a:hover { background: #f3f4f6; color: #111827; }
    .nav-links a.active { background: #1f2937; color: #fff; }
    .btn-logout {
      background: none;
      border: none;
      font-size: 0.78rem;
      font-weight: 500;
      color: #6b7280;
      padding: 6px 10px;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.15s;
      font-family: 'Poppins', sans-serif;
      white-space: nowrap;
    }
    .btn-logout:hover { background: #f3f4f6; color: #111827; }

    main { max-width: 580px; margin: 0 auto; padding: 1.25rem 1rem; }

    h2 { font-size: 1.1rem; font-weight: 600; color: #111827; margin-bottom: 1.25rem; }

    @media (max-width: 580px) {
      .btn-toggle { width: 100%; padding: 12px; }
      #aparStatus { font-size: 1.4rem; }
    }

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
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
      @csrf
      <button type="submit" class="btn-logout">Keluar</button>
    </form>
  </div>
</nav>

<main>

  <h2>Kontrol APAR</h2>

  <div class="apar-card">
    <p>Status APAR</p>
    <h1 id="aparStatus" class="{{ $aparControl === 'off' ? 'off' : '' }}">
      {{ $aparControl === 'off' ? 'NONAKTIF' : 'AKTIF' }}
    </h1>
    <button id="toggleBtn" onclick="toggleAPAR()" class="btn-toggle {{ $aparControl === 'off' ? 'btn-on' : 'btn-off' }}">
      {{ $aparControl === 'off' ? 'Aktifkan APAR' : 'Matikan APAR' }}
    </button>
  </div>

</main>

<script>
let aparOn = {{ $aparControl === 'on' ? 'true' : 'false' }};

function toggleAPAR() {
  const targetStatus = aparOn ? 'off' : 'on';

  fetch('/api/apar/toggle', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ status: targetStatus })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      aparOn = (targetStatus === 'on');
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
  })
  .catch(err => {
    console.error('Gagal memperbarui status APAR:', err);
    alert('Gagal berkomunikasi dengan server.');
  });
}
</script>
</body>
</html>