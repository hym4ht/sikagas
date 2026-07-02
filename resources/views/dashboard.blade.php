<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | Sistem Monitoring Gas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f2f5;
      color: #374151;
      min-height: 100vh;
    }

    /* NAVBAR */
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

    .nav-brand {
      font-size: 0.9rem;
      font-weight: 600;
      color: #111827;
      white-space: nowrap;
      flex-shrink: 0;
    }

    .nav-links {
      display: flex;
      gap: 4px;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
      flex-shrink: 1;
    }
    .nav-links::-webkit-scrollbar { display: none; }

    .nav-links a {
      text-decoration: none;
      font-size: 0.78rem;
      font-weight: 500;
      color: #6b7280;
      padding: 6px 10px;
      border-radius: 8px;
      transition: all 0.15s;
      white-space: nowrap;
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

    /* CONTENT */
    main { max-width: 860px; margin: 0 auto; padding: 1.25rem 1rem; }

    /* STATUS BOX */
    #statusBox {
      background: #d1fae5;
      color: #065f46;
      border-radius: 12px;
      padding: 1rem 1.25rem;
      text-align: center;
      margin-bottom: 1.25rem;
      font-weight: 600;
      font-size: 0.95rem;
    }

    /* GRID 2 COL */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }
    .grid-btn { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    /* CARD */
    .card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 1.1rem 1.25rem;
    }

    .card .label { font-size: 0.78rem; color: #9ca3af; margin-bottom: 0.4rem; }
    .card .value { font-size: 1.5rem; font-weight: 700; color: #111827; }
    .card .value span { font-size: 0.9rem; font-weight: 500; color: #9ca3af; }

    /* STATUS CARDS */
    .status-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 1rem 1.1rem;
      text-align: center;
    }
    .status-card .label { font-size: 0.75rem; color: #9ca3af; margin-bottom: 0.5rem; }
    .status-card .val { font-size: 0.85rem; font-weight: 700; color: #059669; }
    .status-card .val.danger { color: #dc2626; }

    /* LOKASI */
    .lokasi-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.25rem;
      font-size: 0.85rem;
      color: #6b7280;
    }
    .lokasi-card #lokasi { color: #111827; font-weight: 500; }
    .lokasi-card #lokasi a { color: #2563eb; text-decoration: none; }

    /* NOTIF */
    #notif {
      display: none;
      background: #fee2e2;
      border: 1px solid #fca5a5;
      border-radius: 10px;
      padding: 0.85rem 1.25rem;
      margin-bottom: 1.25rem;
      color: #b91c1c;
      font-size: 0.875rem;
      font-weight: 500;
      text-align: center;
    }

    /* BUTTONS */
    .btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: opacity 0.15s;
    }
    .btn:hover { opacity: 0.85; }
    .btn-green { background: #059669; color: #fff; }
    .btn-red { background: #dc2626; color: #fff; }

    @media (max-width: 580px) {
      .grid-2, .grid-btn { grid-template-columns: 1fr; }
      .grid-3 { grid-template-columns: 1fr 1fr 1fr; }
      .card .value { font-size: 1.3rem; }
      #statusBox { font-size: 0.875rem; padding: 0.85rem 1rem; }
    }
    @media (max-width: 360px) {
      .grid-3 { grid-template-columns: 1fr; }
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

  <!-- STATUS -->
  <div id="statusBox">Sistem Aman ✅</div>

  <!-- DATA GAS & SUHU -->
  <div class="grid-2">
    <div class="card">
      <div class="label">Gas Level</div>
      <div class="value" id="gas">120 <span>PPM</span></div>
    </div>
    <div class="card">
      <div class="label">Suhu</div>
      <div class="value" id="suhu">27 <span>°C</span></div>
    </div>
  </div>

  <!-- STATUS DETAIL -->
  <div class="grid-3">
    <div class="status-card">
      <div class="label">Gas</div>
      <div class="val" id="statusGas">AMAN</div>
    </div>
    <div class="status-card">
      <div class="label">Api</div>
      <div class="val" id="statusApi">TIDAK ADA</div>
    </div>
    <div class="status-card">
      <div class="label">APAR</div>
      <div class="val" id="statusApar">SIAP</div>
    </div>
  </div>

  <!-- LOKASI -->
  <div class="lokasi-card">
    Lokasi Rumah: <span id="lokasi">Mengambil lokasi...</span>
  </div>

  <!-- NOTIF -->
  <div id="notif">⚠️ Peringatan! Terjadi kebocoran gas / kebakaran!</div>

  <!-- BUTTONS -->
  <div class="grid-btn">
    <button onclick="kirimWA()" class="btn btn-green">💬 WhatsApp</button>
    <a href="tel:113" class="btn btn-red">📞 Telepon 113</a>
  </div>

</main>

<script>
let gas = {{ $latestData->gas_value ?? 120 }};
let statusGas = "{{ $latestData->status ?? 'AMAN' }}";
let aparAktif = {{ ($latestData->apar_aktif ?? false) ? 'true' : 'false' }};
let buzzerAktif = {{ ($latestData->buzzer_aktif ?? false) ? 'true' : 'false' }};
let suhu = 27; // Database tidak mencatat suhu
let lokasiText = "Tidak tersedia";

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(pos) {
    let lat = pos.coords.latitude;
    let lon = pos.coords.longitude;
    lokasiText = `https://maps.google.com/?q=${lat},${lon}`;
    document.getElementById("lokasi").innerHTML =
      `<a href="${lokasiText}" target="_blank">Lihat Lokasi</a>`;
  });
}

function updateUI(gasVal, statusVal, aparVal, buzzerVal) {
  gas = gasVal;
  statusGas = statusVal;
  aparAktif = aparVal;
  buzzerAktif = buzzerVal;

  document.getElementById("gas").innerHTML = `${gasVal} <span>PPM</span>`;

  // Status Gas
  const statusGasEl = document.getElementById("statusGas");
  statusGasEl.innerText = statusVal;
  if (statusVal === "BAHAYA" || statusVal === "WASPADA") {
    statusGasEl.classList.add("danger");
  } else {
    statusGasEl.classList.remove("danger");
  }

  // Status Api (terdeteksi jika status WASPADA/BAHAYA)
  const statusApiEl = document.getElementById("statusApi");
  if (statusVal === "BAHAYA" || statusVal === "WASPADA") {
    statusApiEl.innerText = "TERDETEKSI 🔥";
    statusApiEl.classList.add("danger");
  } else {
    statusApiEl.innerText = "TIDAK ADA";
    statusApiEl.classList.remove("danger");
  }

  // Status APAR
  const statusAparEl = document.getElementById("statusApar");
  if (aparVal) {
    statusAparEl.innerText = "AKTIF 🧯";
    statusAparEl.classList.add("danger");
  } else {
    statusAparEl.innerText = "SIAP";
    statusAparEl.classList.remove("danger");
  }

  // Peringatan Notif & Status Box
  const notifEl = document.getElementById("notif");
  const statusBoxEl = document.getElementById("statusBox");

  if (statusVal === "BAHAYA") {
    notifEl.style.display = "block";
    notifEl.innerText = "⚠️ Peringatan! Terjadi kebocoran gas BAHAYA / kebakaran!";
    statusBoxEl.style.background = "#fee2e2";
    statusBoxEl.style.color = "#b91c1c";
    statusBoxEl.innerHTML = "<h2>BAHAYA TERDETEKSI 🚨</h2>";
  } else if (statusVal === "WASPADA") {
    notifEl.style.display = "block";
    notifEl.innerText = "⚠️ Peringatan! Kadar gas WASPADA!";
    statusBoxEl.style.background = "#fef3c7";
    statusBoxEl.style.color = "#92400e";
    statusBoxEl.innerHTML = "<h2>WASPADA KADAR GAS ⚠️</h2>";
  } else {
    notifEl.style.display = "none";
    statusBoxEl.style.background = "#d1fae5";
    statusBoxEl.style.color = "#065f46";
    statusBoxEl.innerHTML = "Sistem Aman ✅";
  }
}

// Panggil updateUI pertama kali
updateUI(gas, statusGas, aparAktif, buzzerAktif);

// Inisialisasi Pusher
const pusherKey = "{{ env('PUSHER_APP_KEY') }}";
const pusherCluster = "{{ env('PUSHER_APP_CLUSTER', 'ap1') }}";

if (pusherKey) {
  // Aktifkan logging Pusher untuk membantu debugging (opsional, bisa dinonaktifkan di prod)
  Pusher.logToConsole = true;

  const pusher = new Pusher(pusherKey, {
    cluster: pusherCluster,
    forceTLS: true
  });

  const channel = pusher.subscribe('sensor-channel');
  channel.bind('SensorDataUpdated', function(data) {
    console.log('Menerima update sensor via WebSocket:', data);
    updateUI(data.gas_value, data.status, data.apar_aktif == 1, data.buzzer_aktif == 1);
  });
} else {
  console.warn('Pusher Key belum dikonfigurasi. Menggunakan fallback polling 3 detik...');
  // Fallback Polling 3 detik jika Pusher belum disetup
  setInterval(() => {
    fetch('/api/sensor/latest?t=' + Date.now(), { cache: 'no-store' })
      .then(response => response.json())
      .then(data => {
        if (data) {
          updateUI(data.gas_value, data.status, data.apar_aktif == 1, data.buzzer_aktif == 1);
        }
      })
      .catch(err => console.error('Gagal mengambil data sensor:', err));
  }, 3000);
}

function kirimWA() {
  let pesan = `🚨 LAPORAN DARURAT 🚨\nGas: ${gas} PPM\nStatus: ${statusGas}\nAPAR: ${aparAktif ? "AKTIF" : "SIAP"}\nBuzzer: ${buzzerAktif ? "AKTIF" : "MATI"}\nLokasi: ${lokasiText}`;
  let nomor = "6285290671398";
  window.open("https://wa.me/" + nomor + "?text=" + encodeURIComponent(pesan), '_blank');
}
</script>
</body>
</html>