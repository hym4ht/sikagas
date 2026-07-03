<x-layout title="Dashboard">

  <x-slot name="head">
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <style>
      /* STATUS BOX */
      .status-banner {
        border-radius: var(--radius);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid;
        transition: all 0.4s ease;
      }
      .status-banner.aman   { background: rgba(16,185,129,.1);  border-color: rgba(16,185,129,.25);  }
      .status-banner.waspada{ background: rgba(245,158,11,.1);  border-color: rgba(245,158,11,.25);  }
      .status-banner.bahaya { background: rgba(239,68,68,.12);  border-color: rgba(239,68,68,.3);    }

      .status-banner .s-icon { font-size: 2rem; flex-shrink: 0; }
      .status-banner .s-label { font-size: 0.75rem; color: var(--muted); font-weight: 500; }
      .status-banner .s-title { font-size: 1.1rem; font-weight: 700; color: #fff; }

      /* METRIC CARD */
      .metric-card {
        padding: 1.25rem 1.5rem;
        position: relative;
        overflow: hidden;
      }
      .metric-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(249,115,22,.04) 0%, transparent 60%);
        pointer-events: none;
      }
      .metric-card .m-label { font-size: 0.72rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
      .metric-card .m-value { font-size: 2rem; font-weight: 800; color: #fff; line-height: 1; }
      .metric-card .m-unit  { font-size: 0.85rem; font-weight: 500; color: var(--muted); margin-left: 4px; }
      .metric-card .m-icon  { position: absolute; top: 1rem; right: 1rem; font-size: 1.5rem; opacity: 0.6; }

      /* STATUS DETAIL */
      .status-pill {
        padding: 1rem;
        text-align: center;
      }
      .status-pill .sp-label { font-size: 0.68rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
      .status-pill .sp-val   { font-size: 0.9rem; font-weight: 700; color: var(--green); }
      .status-pill .sp-val.danger { color: #f87171; }
      .status-pill .sp-val.warn   { color: #fbbf24; }

      /* LOKASI */
      .lokasi-bar {
        padding: 0.9rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        font-size: 0.82rem;
      }
      .lokasi-bar .lok-icon { font-size: 1rem; }
      .lokasi-bar .lok-label { color: var(--muted); }
      .lokasi-bar a { color: var(--accent); text-decoration: none; }
      .lokasi-bar a:hover { text-decoration: underline; }

      /* ALERT */
      .alert-box {
        display: none;
        padding: 0.9rem 1.25rem;
        border-radius: var(--radius);
        margin-bottom: 1rem;
        font-size: 0.82rem;
        font-weight: 600;
        background: rgba(239,68,68,.12);
        border: 1px solid rgba(239,68,68,.3);
        color: #f87171;
        animation: blink-alert 1.5s ease-in-out infinite;
      }
      @keyframes blink-alert {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
      }

      /* ACTION BUTTONS */
      .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

      @media (max-width: 600px) {
        .metric-card .m-value { font-size: 1.6rem; }
      }
    </style>
  </x-slot>

  <!-- PAGE HEADER -->
  <div class="page-header">
    <h1>Dashboard</h1>
    <p>Monitoring realtime sistem keamanan gas LPG</p>
  </div>

  <!-- STATUS BANNER -->
  <div id="statusBanner" class="status-banner aman">
    <div class="s-icon">✅</div>
    <div>
      <div class="s-label">Status Sistem</div>
      <div class="s-title" id="statusTitle">Sistem Aman</div>
    </div>
  </div>

  <!-- ALERT -->
  <div id="alertBox" class="alert-box">⚠️ Peringatan! Terjadi kebocoran gas / kebakaran!</div>

  <!-- METRIC CARDS -->
  <div style="margin-bottom: 1rem;">
    <div class="glass-card metric-card">
      <div class="m-icon">💨</div>
      <div class="m-label">Gas Level</div>
      <div class="m-value" id="gas">120<span class="m-unit">PPM</span></div>
    </div>
  </div>

  <!-- STATUS DETAIL PILLS -->
  <div class="grid-3" style="margin-bottom: 1rem;">
    <div class="glass-card status-pill">
      <div class="sp-label">Gas</div>
      <div class="sp-val" id="statusGas">AMAN</div>
    </div>
    <div class="glass-card status-pill">
      <div class="sp-label">Api</div>
      <div class="sp-val" id="statusApi">TIDAK ADA</div>
    </div>
    <div class="glass-card status-pill">
      <div class="sp-label">APAR</div>
      <div class="sp-val" id="statusApar">SIAP</div>
    </div>
  </div>

  <!-- LOKASI -->
  <div class="glass-card lokasi-bar" style="margin-bottom: 1rem;">
    <span class="lok-icon">📍</span>
    <span class="lok-label">Lokasi:</span>
    <span id="lokasi">Mengambil lokasi...</span>
  </div>

  <!-- ACTION BUTTONS -->
  <div class="action-grid">
    <button onclick="kirimWA()" class="btn btn-success">
      💬 Kirim WhatsApp
    </button>
    <a href="tel:113" class="btn btn-danger">
      📞 Hubungi 113
    </a>
  </div>

  <x-slot name="scripts">
    <script>
    let gas = {{ $latestData->gas_value ?? 120 }};
    let statusGas = "{{ $latestData->status ?? 'AMAN' }}";
    let aparAktif = {{ ($latestData->apar_aktif ?? false) ? 'true' : 'false' }};
    let buzzerAktif = {{ ($latestData->buzzer_aktif ?? false) ? 'true' : 'false' }};
    let lokasiText = "Tidak tersedia";

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(pos) {
        let lat = pos.coords.latitude;
        let lon = pos.coords.longitude;
        lokasiText = `https://maps.google.com/?q=${lat},${lon}`;
        document.getElementById("lokasi").innerHTML =
          `<a href="${lokasiText}" target="_blank">📍 Lihat di Google Maps</a>`;
      });
    }

    function updateUI(gasVal, statusVal, aparVal, buzzerVal) {
      gas = gasVal; statusGas = statusVal; aparAktif = aparVal; buzzerAktif = buzzerVal;

      document.getElementById("gas").innerHTML = `${gasVal}<span class="m-unit">PPM</span>`;

      const banner   = document.getElementById("statusBanner");
      const title    = document.getElementById("statusTitle");
      const alertBox = document.getElementById("alertBox");
      const sGas     = document.getElementById("statusGas");
      const sApi     = document.getElementById("statusApi");
      const sApar    = document.getElementById("statusApar");

      // Gas badge
      sGas.textContent = statusVal;
      sGas.className = "sp-val";
      if (statusVal === "BAHAYA") sGas.classList.add("danger");
      else if (statusVal === "WASPADA") sGas.classList.add("warn");

      // Api badge
      if (statusVal === "BAHAYA" || statusVal === "WASPADA") {
        sApi.textContent = "TERDETEKSI 🔥"; sApi.className = "sp-val danger";
      } else {
        sApi.textContent = "TIDAK ADA"; sApi.className = "sp-val";
      }

      // APAR badge
      if (aparVal) {
        sApar.textContent = "AKTIF 🧯"; sApar.className = "sp-val danger";
      } else {
        sApar.textContent = "SIAP"; sApar.className = "sp-val";
      }

      // Banner
      banner.className = "status-banner";
      if (statusVal === "BAHAYA") {
        banner.classList.add("bahaya");
        document.querySelector("#statusBanner .s-icon").textContent = "🚨";
        title.textContent = "BAHAYA TERDETEKSI!";
        alertBox.style.display = "block";
        alertBox.textContent = "⚠️ BAHAYA! Kebocoran gas terdeteksi!";
      } else if (statusVal === "WASPADA") {
        banner.classList.add("waspada");
        document.querySelector("#statusBanner .s-icon").textContent = "⚠️";
        title.textContent = "Kadar Gas Meningkat";
        alertBox.style.display = "block";
        alertBox.textContent = "⚠️ WASPADA! Kadar gas melebihi batas normal.";
      } else {
        banner.classList.add("aman");
        document.querySelector("#statusBanner .s-icon").textContent = "✅";
        title.textContent = "Sistem Aman";
        alertBox.style.display = "none";
      }
    }

    updateUI(gas, statusGas, aparAktif, buzzerAktif);

    const pusherKey = "{{ env('PUSHER_APP_KEY') }}";
    const pusherCluster = "{{ env('PUSHER_APP_CLUSTER', 'ap1') }}";
    if (pusherKey) {
      const pusher = new Pusher(pusherKey, { cluster: pusherCluster, forceTLS: true });
      const channel = pusher.subscribe('sensor-channel');
      channel.bind('SensorDataUpdated', function(data) {
        updateUI(data.gas_value, data.status, data.apar_aktif == 1, data.buzzer_aktif == 1);
      });
    } else {
      setInterval(() => {
        fetch('/api/sensor/latest?t=' + Date.now(), { cache: 'no-store' })
          .then(r => r.json())
          .then(data => { if (data) updateUI(data.gas_value, data.status, data.apar_aktif == 1, data.buzzer_aktif == 1); })
          .catch(e => console.error(e));
      }, 3000);
    }

    function kirimWA() {
      let pesan = `🚨 LAPORAN DARURAT 🚨\nGas: ${gas} PPM\nStatus: ${statusGas}\nAPAR: ${aparAktif ? "AKTIF" : "SIAP"}\nBuzzer: ${buzzerAktif ? "AKTIF" : "MATI"}\nLokasi: ${lokasiText}`;
      window.open("https://wa.me/6285290671398?text=" + encodeURIComponent(pesan), '_blank');
    }
    </script>
  </x-slot>

</x-layout>