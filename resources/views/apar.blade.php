<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kontrol APAR | Sistem Monitoring Gas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
      background: none; border: none; font-size: 0.78rem; font-weight: 500;
      color: #6b7280; padding: 6px 10px; border-radius: 8px; cursor: pointer;
      transition: all 0.15s; font-family: 'Poppins', sans-serif; white-space: nowrap;
    }
    .btn-logout:hover { background: #f3f4f6; color: #111827; }

    main { max-width: 580px; margin: 0 auto; padding: 1.25rem 1rem; }
    h2 { font-size: 1.1rem; font-weight: 600; color: #111827; margin-bottom: 1.25rem; }

    /* === APAR CARD === */
    .apar-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 2rem 1.5rem;
      text-align: center;
      margin-bottom: 1rem;
    }

    .apar-card .label {
      font-size: 0.82rem;
      color: #9ca3af;
      font-weight: 500;
      margin-bottom: 0.4rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    #aparStatus {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.25rem;
      transition: color 0.3s;
    }
    #aparStatus.on  { color: #059669; }
    #aparStatus.off { color: #6b7280; }

    #sensorBadge {
      display: inline-block;
      font-size: 0.72rem;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 20px;
      margin-bottom: 1.5rem;
      letter-spacing: 0.04em;
    }
    #sensorBadge.aman    { background: #d1fae5; color: #065f46; }
    #sensorBadge.waspada { background: #fef3c7; color: #92400e; }
    #sensorBadge.bahaya  { background: #fee2e2; color: #991b1b; }

    .btn-toggle {
      width: 100%;
      padding: 13px;
      border-radius: 10px;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      font-family: 'Poppins', sans-serif;
      transition: opacity 0.15s, transform 0.1s;
      letter-spacing: 0.02em;
    }
    .btn-toggle:hover:not(:disabled)  { opacity: 0.87; }
    .btn-toggle:active:not(:disabled) { transform: scale(0.98); }
    .btn-toggle:disabled { opacity: 0.55; cursor: not-allowed; }
    .btn-toggle.btn-off { background: #dc2626; color: #fff; }
    .btn-toggle.btn-on  { background: #059669; color: #fff; }

    /* === INFO CARD === */
    .info-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 1rem 1.25rem;
      margin-bottom: 1rem;
    }
    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 0;
      font-size: 0.82rem;
      border-bottom: 1px solid #f3f4f6;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .key   { color: #6b7280; }
    .info-row .value { font-weight: 600; color: #111827; }

    /* === ALERT BAHAYA === */
    .alert-bahaya {
      display: none;
      background: #fee2e2;
      border: 1px solid #fca5a5;
      border-radius: 10px;
      padding: 0.85rem 1rem;
      margin-bottom: 1rem;
      font-size: 0.82rem;
      color: #7f1d1d;
      line-height: 1.5;
    }
    .alert-bahaya.show { display: block; }

    /* === TOAST === */
    #toast {
      position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%) translateY(80px);
      background: #1f2937; color: #fff; font-size: 0.82rem; font-weight: 500;
      padding: 10px 20px; border-radius: 30px; white-space: nowrap;
      transition: transform 0.3s ease; z-index: 999; pointer-events: none;
    }
    #toast.show { transform: translateX(-50%) translateY(0); }

    /* === STATUS INDICATOR === */
    .status-dot {
      display: inline-block;
      width: 8px; height: 8px;
      border-radius: 50%;
      margin-right: 5px;
      vertical-align: middle;
    }
    .status-dot.online  { background: #10b981; }
    .status-dot.offline { background: #9ca3af; }

    #lastUpdate { font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem; display: block; }

    @media (max-width: 580px) {
      .apar-card { padding: 1.5rem 1rem; }
    }
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

  <!-- Alert Bahaya -->
  <div class="alert-bahaya" id="alertBahaya">
    ⚠️ <strong>Sensor mendeteksi kadar gas BAHAYA!</strong><br>
    APAR diaktifkan secara otomatis oleh sensor. Tombol manual tetap bisa digunakan.
  </div>

  <!-- Kartu Utama -->
  <div class="apar-card">
    <p class="label">Status Perintah APAR</p>
    <div id="aparStatus" class="off">NONAKTIF</div>
    <span id="sensorBadge" class="aman">Sensor: AMAN</span>
    <br>
    <button id="toggleBtn" onclick="toggleAPAR()" class="btn-toggle btn-on">
      Aktifkan APAR
    </button>
  </div>

  <!-- Info Detail -->
  <div class="info-card">
    <div class="info-row">
      <span class="key">Perintah Manual</span>
      <span class="value" id="infoCommand">—</span>
    </div>
    <div class="info-row">
      <span class="key">Status Sensor IoT</span>
      <span class="value" id="infoSensor">—</span>
    </div>
    <div class="info-row">
      <span class="key">Nilai Gas (ADC)</span>
      <span class="value" id="infoGas">—</span>
    </div>
    <div class="info-row">
      <span class="key">APAR Fisik (IoT)</span>
      <span class="value" id="infoApar">—</span>
    </div>
    <div class="info-row">
      <span class="key">Koneksi IoT</span>
      <span class="value" id="infoKoneksi">
        <span class="status-dot offline" id="dotKoneksi"></span>
        <span id="txtKoneksi">Memeriksa...</span>
      </span>
    </div>
  </div>

  <span id="lastUpdate"></span>

</main>

<div id="toast">✓ Perintah terkirim</div>

<script>
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  let aparOn = false;
  let isSending = false;
  let lastSensorTime = null;

  async function fetchStatus() {
    try {
      const res = await fetch('/api/apar/status');
      if (!res.ok) return;
      const data = await res.json();

      aparOn = data.command === 'ON';
      renderToggle(aparOn);

      document.getElementById('infoCommand').textContent = data.command;
      document.getElementById('infoSensor').textContent  = data.sensor_status ?? '—';
      document.getElementById('infoGas').textContent     = data.gas_value !== null ? data.gas_value : '—';
      document.getElementById('infoApar').textContent    = data.apar_aktif ? 'AKTIF' : 'NONAKTIF';

      const badge       = document.getElementById('sensorBadge');
      const alertBahaya = document.getElementById('alertBahaya');
      const sensorSt    = (data.sensor_status ?? 'AMAN').toUpperCase();

      badge.textContent = 'Sensor: ' + sensorSt;
      badge.className   = '';
      if      (sensorSt === 'BAHAYA')  { badge.classList.add('bahaya');  alertBahaya.classList.add('show'); }
      else if (sensorSt === 'WASPADA') { badge.classList.add('waspada'); alertBahaya.classList.remove('show'); }
      else                             { badge.classList.add('aman');    alertBahaya.classList.remove('show'); }

      if (data.last_updated) {
        const diff   = (Date.now() - new Date(data.last_updated).getTime()) / 1000;
        const online = diff < 30;
        document.getElementById('dotKoneksi').className    = 'status-dot ' + (online ? 'online' : 'offline');
        document.getElementById('txtKoneksi').textContent  = online ? 'Online' : 'Offline (>' + Math.round(diff) + 'd)';
      } else {
        document.getElementById('dotKoneksi').className   = 'status-dot offline';
        document.getElementById('txtKoneksi').textContent = 'Belum ada data';
      }

      document.getElementById('lastUpdate').textContent =
        'Diperbarui: ' + new Date().toLocaleTimeString('id-ID');

    } catch (e) {
      console.error('Fetch status error:', e);
    }
  }

  function renderToggle(isOn) {
    const status = document.getElementById('aparStatus');
    const btn    = document.getElementById('toggleBtn');

    if (isOn) {
      status.textContent = 'AKTIF';
      status.className   = 'on';
      btn.textContent    = 'Matikan APAR';
      btn.className      = 'btn-toggle btn-off';
    } else {
      status.textContent = 'NONAKTIF';
      status.className   = 'off';
      btn.textContent    = 'Aktifkan APAR';
      btn.className      = 'btn-toggle btn-on';
    }
  }

  async function toggleAPAR() {
    if (isSending) return;
    isSending = true;

    const btn    = document.getElementById('toggleBtn');
    const newCmd = aparOn ? 'OFF' : 'ON';

    btn.disabled    = true;
    btn.textContent = 'Mengirim...';

    try {
      const res = await fetch('/api/apar/control', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: 'command=' + newCmd,
      });

      if (res.ok) {
        aparOn = newCmd === 'ON';
        renderToggle(aparOn);
        showToast(aparOn ? '✓ APAR diaktifkan' : '✓ APAR dimatikan');
        await fetchStatus();
      } else {
        showToast('✗ Gagal mengirim perintah');
        renderToggle(aparOn);
      }
    } catch (e) {
      showToast('✗ Tidak dapat terhubung');
      renderToggle(aparOn);
    } finally {
      btn.disabled = false;
      isSending    = false;
    }
  }

  function showToast(msg) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2500);
  }

  fetchStatus();
  setInterval(fetchStatus, 5000);
</script>
</body>
</html>