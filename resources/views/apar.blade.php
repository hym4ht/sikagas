<x-layout title="Kontrol APAR">
  <x-slot name="head">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
      /* APAR MAIN CARD */
      .apar-main {
        padding: 2.5rem 1.5rem;
        text-align: center;
        margin-bottom: 1rem;
        position: relative;
        overflow: hidden;
      }
      .apar-main::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at center, rgba(249,115,22,.06) 0%, transparent 70%);
        pointer-events: none;
      }

      .apar-icon {
        font-size: 3.5rem;
        margin-bottom: 0.75rem;
        display: block;
        animation: float 3s ease-in-out infinite;
      }
      @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
      }

      .apar-status-label { font-size: 0.7rem; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.4rem; }
      #aparStatus { font-size: 2.2rem; font-weight: 800; margin-bottom: 0.5rem; transition: color 0.4s; }
      #aparStatus.on  { color: #34d399; text-shadow: 0 0 20px rgba(52,211,153,.4); }
      #aparStatus.off { color: var(--muted); }

      #sensorBadge { margin-bottom: 1.5rem; }

      /* TOGGLE BUTTON */
      .btn-toggle {
        width: 100%;
        max-width: 280px;
        padding: 14px;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        border: none;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
        letter-spacing: 0.03em;
      }
      .btn-toggle:hover:not(:disabled)  { transform: translateY(-2px); }
      .btn-toggle:active:not(:disabled) { transform: translateY(0); }
      .btn-toggle:disabled { opacity: 0.5; cursor: not-allowed; }
      .btn-toggle.btn-activate {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        box-shadow: 0 4px 18px rgba(16,185,129,.3);
      }
      .btn-toggle.btn-deactivate {
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        color: #fff;
        box-shadow: 0 4px 18px rgba(239,68,68,.3);
      }

      /* INFO ROWS */
      .info-card { padding: 0.25rem 1.25rem; }
      .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 0;
        font-size: 0.82rem;
        border-bottom: 1px solid rgba(255,255,255,.05);
      }
      .info-row:last-child { border-bottom: none; }
      .info-row .key   { color: var(--muted); font-weight: 500; }
      .info-row .value { font-weight: 600; color: var(--text); }

      /* ALERT */
      .alert-bahaya {
        display: none;
        padding: 0.9rem 1.25rem;
        border-radius: var(--radius);
        margin-bottom: 1rem;
        font-size: 0.82rem;
        color: #fca5a5;
        background: rgba(239,68,68,.1);
        border: 1px solid rgba(239,68,68,.25);
        line-height: 1.6;
      }
      .alert-bahaya.show { display: block; }

      /* DOT INDICATOR */
      .status-dot {
        display: inline-block;
        width: 8px; height: 8px;
        border-radius: 50%;
        margin-right: 6px;
        vertical-align: middle;
      }
      .status-dot.online  { background: #10b981; box-shadow: 0 0 6px rgba(16,185,129,.6); }
      .status-dot.offline { background: var(--muted); }

      #lastUpdate { display: block; font-size: 0.72rem; color: var(--muted); margin-top: 0.5rem; text-align: right; }
    </style>
  </x-slot>

  <div class="page-header">
    <h1>🧯 Kontrol APAR</h1>
    <p>Kendalikan sistem pemadam otomatis secara manual</p>
  </div>

  <!-- ALERT BAHAYA -->
  <div class="alert-bahaya" id="alertBahaya">
    ⚠️ <strong>Sensor mendeteksi kadar gas BAHAYA!</strong><br>
    APAR diaktifkan secara otomatis oleh sensor. Tombol manual tetap bisa digunakan.
  </div>

  <!-- APAR MAIN CARD -->
  <div class="glass-card apar-main" style="margin-bottom:1rem;">
    <span class="apar-icon">🧯</span>
    <div class="apar-status-label">Status Perintah APAR</div>
    <div id="aparStatus" class="off">NONAKTIF</div>
    <span id="sensorBadge" class="badge badge-green">Sensor: AMAN</span><br>
    <button id="toggleBtn" onclick="toggleAPAR()" class="btn-toggle btn-activate">
      ⚡ Aktifkan APAR
    </button>
  </div>

  <!-- INFO DETAIL -->
  <div class="glass-card info-card" style="margin-bottom:0.5rem;">
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

  <div id="toast"></div>

  <x-slot name="scripts">
    <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let aparOn = false, isSending = false;

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

        const badge = document.getElementById('sensorBadge');
        const alertBahaya = document.getElementById('alertBahaya');
        const st = (data.sensor_status ?? 'AMAN').toUpperCase();

        badge.textContent = 'Sensor: ' + st;
        badge.className = 'badge';
        if      (st === 'BAHAYA')  { badge.classList.add('badge-red');    alertBahaya.classList.add('show'); }
        else if (st === 'WASPADA') { badge.classList.add('badge-yellow'); alertBahaya.classList.remove('show'); }
        else                       { badge.classList.add('badge-green');  alertBahaya.classList.remove('show'); }

        if (data.last_updated) {
          const diff = (Date.now() - new Date(data.last_updated).getTime()) / 1000;
          const online = diff < 30;
          document.getElementById('dotKoneksi').className    = 'status-dot ' + (online ? 'online' : 'offline');
          document.getElementById('txtKoneksi').textContent  = online ? 'Online' : 'Offline (>' + Math.round(diff) + 'd)';
        } else {
          document.getElementById('dotKoneksi').className   = 'status-dot offline';
          document.getElementById('txtKoneksi').textContent = 'Belum ada data';
        }

        document.getElementById('lastUpdate').textContent = 'Diperbarui: ' + new Date().toLocaleTimeString('id-ID');
      } catch(e) { console.error(e); }
    }

    function renderToggle(isOn) {
      const status = document.getElementById('aparStatus');
      const btn    = document.getElementById('toggleBtn');
      if (isOn) {
        status.textContent = 'AKTIF'; status.className = 'on';
        btn.textContent = '🛑 Matikan APAR'; btn.className = 'btn-toggle btn-deactivate';
      } else {
        status.textContent = 'NONAKTIF'; status.className = 'off';
        btn.textContent = '⚡ Aktifkan APAR'; btn.className = 'btn-toggle btn-activate';
      }
    }

    async function toggleAPAR() {
      if (isSending) return;
      isSending = true;
      const btn = document.getElementById('toggleBtn');
      const newCmd = aparOn ? 'OFF' : 'ON';
      btn.disabled = true; btn.textContent = 'Mengirim...';
      try {
        const res = await fetch('/api/apar/control', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
          body: 'command=' + newCmd,
        });
        if (res.ok) {
          aparOn = newCmd === 'ON';
          renderToggle(aparOn);
          showToast(aparOn ? '✓ APAR berhasil diaktifkan' : '✓ APAR berhasil dimatikan');
          await fetchStatus();
        } else {
          showToast('✗ Gagal mengirim perintah'); renderToggle(aparOn);
        }
      } catch(e) {
        showToast('✗ Tidak dapat terhubung'); renderToggle(aparOn);
      } finally {
        btn.disabled = false; isSending = false;
      }
    }

    function showToast(msg) {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 2500);
    }

    fetchStatus();
    setInterval(fetchStatus, 5000);
    </script>
  </x-slot>

</x-layout>