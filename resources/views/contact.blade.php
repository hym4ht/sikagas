<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | Sistem Monitoring Gas</title>
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

    main { max-width: 860px; margin: 0 auto; padding: 1.25rem 1rem; }

    /* WARNING ALERT */
    #warningBox {
      display: none;
      background: #fee2e2;
      border: 1px solid #fca5a5;
      color: #b91c1c;
      border-radius: 10px;
      padding: 0.9rem 1.25rem;
      text-align: center;
      font-weight: 600;
      font-size: 0.875rem;
      margin-bottom: 1.25rem;
      animation: blink 1s infinite;
    }
    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }

    .card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 1.1rem 1.25rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .card span { font-size: 0.9rem; color: #6b7280; }
    .card b { font-size: 1rem; font-weight: 600; color: #111827; }

    /* STATUS APAR */
    .status-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 1.1rem 1.25rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.25rem;
    }
    .status-card p { font-size: 0.82rem; color: #6b7280; margin-bottom: 3px; }
    #status { font-size: 1.05rem; font-weight: 700; color: #059669; }
    #status.danger { color: #dc2626; }
    .status-icon { font-size: 2.25rem; }

    /* BUTTON */
    .btn-emergency {
      display: block;
      width: 100%;
      text-align: center;
      background: #dc2626;
      color: #fff;
      padding: 14px;
      border-radius: 10px;
      font-size: 0.95rem;
      font-weight: 600;
      text-decoration: none;
      border: none;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      transition: opacity 0.15s;
    }
    .btn-emergency:hover { opacity: 0.88; }

    @media (max-width: 580px) {
      .grid-2 { grid-template-columns: 1fr; }
      .btn-emergency { font-size: 0.875rem; padding: 13px; }
      #warningBox { font-size: 0.82rem; }
    }
  </style>
</head>
<body>

<nav>
  <span class="nav-brand">🔥 Monitoring Gas</span>
  <div class="nav-links">
    <a href="{{ route('dashboard') }}" class="active">Dashboard</a>
    <a href="{{ route('notifikasi') }}">Notifikasi</a>
    <a href="{{ route('apar') }}">Kontrol APAR</a>
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
      @csrf
      <button type="submit" class="btn-logout">Keluar</button>
    </form>
  </div>
</nav>

<main>

  <!-- WARNING -->
  <div id="warningBox">🚨 PERINGATAN! KEBOCORAN GAS TERDETEKSI 🚨</div>

  <!-- INFO -->
  <div class="grid-2">
    <div class="card">
      <span>Gas Level</span>
      <b id="gas">120 PPM</b>
    </div>
    <div class="card">
      <span>Suhu</span>
      <b id="suhu">27°C</b>
    </div>
  </div>

  <!-- STATUS APAR -->
  <div class="status-card">
    <div>
      <p>Status APAR</p>
      <div id="status">AMAN</div>
    </div>
    <div class="status-icon">🧯</div>
  </div>

  <!-- BUTTON DARURAT -->
  <button onclick="kirimWA()" class="btn-emergency">
    🚒 LAPORKAN KE PEMADAM (DARURAT)
  </button>

</main>

<script>
function kirimWA() {
  let gas = document.getElementById("gas").innerText;
  let suhu = document.getElementById("suhu").innerText;

  let pesan = `🚨 *LAPORAN DARURAT KEBAKARAN / KEBOCORAN GAS* 🚨\n\n📍 Lokasi: (isi lokasi)\n💨 Gas Level: ${gas}\n🌡️ Suhu: ${suhu}\n\n⚠️ Terjadi indikasi kebocoran gas LPG.\nMohon segera ditindaklanjuti oleh petugas pemadam kebakaran.\n\nTerima kasih.`;

  let nomor = "6285290671398";
  window.open("https://wa.me/" + nomor + "?text=" + encodeURIComponent(pesan), '_blank');
}

// SIMULASI DETEKSI BAHAYA
let gasValue = 120;
if (gasValue > 100) {
  document.getElementById("warningBox").style.display = "block";
  let s = document.getElementById("status");
  s.textContent = "BAHAYA";
  s.classList.add("danger");
}
</script>
</body>
</html>