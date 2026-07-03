<x-layout title="Kontak Darurat">
  <x-slot name="head">
    <style>
      /* WARNING BANNER */
      #warningBanner {
        display: none;
        border-radius: var(--radius);
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 1px solid rgba(239,68,68,.3);
        background: rgba(239,68,68,.1);
        animation: blink-alert 1.5s ease-in-out infinite;
      }
      @keyframes blink-alert {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.65; }
      }
      #warningBanner .w-icon { font-size: 2rem; }
      #warningBanner .w-text { font-size: 0.9rem; font-weight: 700; color: #f87171; }
      #warningBanner .w-sub  { font-size: 0.75rem; color: #fca5a5; margin-top: 2px; }
      #warningBanner.hidden  { display: none; }
      #warningBanner.visible { display: flex; }

      /* INFO CARDS */
      .info-pair {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
      }
      .info-item {
        padding: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .info-item .i-label { font-size: 0.75rem; color: var(--muted); font-weight: 500; }
      .info-item .i-value { font-size: 1.2rem; font-weight: 700; color: #fff; }

      /* APAR STATUS */
      .apar-status-card {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
      }
      .apar-status-card .as-label { font-size: 0.75rem; color: var(--muted); margin-bottom: 4px; }
      .apar-status-card #status   { font-size: 1.1rem; font-weight: 700; color: var(--green); }
      .apar-status-card #status.danger { color: #f87171; }
      .apar-status-card .as-icon  { font-size: 2.5rem; }

      /* EMERGENCY BTN */
      .btn-emergency {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        color: #fff;
        border: none;
        border-radius: var(--radius);
        font-size: 1rem;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: all 0.2s;
        box-shadow: 0 4px 20px rgba(239,68,68,.3);
        letter-spacing: 0.03em;
      }
      .btn-emergency:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(239,68,68,.45);
      }

      @media (max-width: 500px) {
        .info-pair { grid-template-columns: 1fr; }
      }
    </style>
  </x-slot>

  <div class="page-header">
    <h1>📞 Kontak Darurat</h1>
    <p>Informasi status dan saluran pelaporan keadaan darurat</p>
  </div>

  <!-- WARNING -->
  <div id="warningBanner" class="hidden">
    <div class="w-icon">🚨</div>
    <div>
      <div class="w-text">PERINGATAN! KEBOCORAN GAS TERDETEKSI</div>
      <div class="w-sub">Segera laporkan ke pemadam kebakaran atau hubungi nomor darurat</div>
    </div>
  </div>

  <!-- DATA SENSOR -->
  <div style="margin-bottom:1rem;">
    <div class="glass-card info-item">
      <div>
        <div class="i-label">Gas Level</div>
        <div class="i-value" id="gas">120 PPM</div>
      </div>
      <span style="font-size:1.5rem;">💨</span>
    </div>
  </div>

  <!-- STATUS APAR -->
  <div class="glass-card apar-status-card">
    <div>
      <div class="as-label">Status APAR</div>
      <div id="status">AMAN</div>
    </div>
    <div class="as-icon">🧯</div>
  </div>

  <!-- EMERGENCY BUTTON -->
  <button onclick="kirimWA()" class="btn-emergency">
    🚒 LAPORKAN KE PEMADAM (DARURAT)
  </button>

  <x-slot name="scripts">
    <script>
    let gasValue = 120;
    if (gasValue > 100) {
      document.getElementById("warningBanner").className = "visible";
      const s = document.getElementById("status");
      s.textContent = "BAHAYA"; s.classList.add("danger");
    }

    function kirimWA() {
      const gas  = document.getElementById("gas").innerText;
      const pesan = `🚨 *LAPORAN DARURAT KEBAKARAN / KEBOCORAN GAS* 🚨\n\n📍 Lokasi: (isi lokasi)\n💨 Gas Level: ${gas}\n\n⚠️ Terjadi indikasi kebocoran gas LPG.\nMohon segera ditindaklanjuti.\n\nTerima kasih.`;
      window.open("https://wa.me/6285290671398?text=" + encodeURIComponent(pesan), '_blank');
    }
    </script>
  </x-slot>

</x-layout>