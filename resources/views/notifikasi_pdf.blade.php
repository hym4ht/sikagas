<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Histori Gas – {{ $judulPeriode }}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #fff;
      color: #1a1a2e;
      font-size: 11pt;
      padding: 30px 40px;
    }

    /* ── Header ── */
    .pdf-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      border-bottom: 3px solid #f97316;
      padding-bottom: 16px;
      margin-bottom: 20px;
    }
    .pdf-logo-area h1 {
      font-size: 20pt;
      font-weight: 800;
      color: #f97316;
      letter-spacing: 1px;
    }
    .pdf-logo-area p {
      font-size: 9pt;
      color: #666;
      margin-top: 3px;
    }
    .pdf-meta {
      text-align: right;
      font-size: 9pt;
      color: #555;
      line-height: 1.6;
    }
    .pdf-meta strong { color: #1a1a2e; }

    /* ── Period Title ── */
    .pdf-period {
      background: linear-gradient(135deg, #f97316, #ef4444);
      color: #fff;
      padding: 10px 18px;
      border-radius: 8px;
      font-size: 11pt;
      font-weight: 700;
      margin-bottom: 18px;
      display: inline-block;
    }

    /* ── Summary Cards ── */
    .summary-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 22px;
    }
    .sum-card {
      border: 1.5px solid #e5e7eb;
      border-radius: 8px;
      padding: 12px 14px;
      text-align: center;
    }
    .sum-card .val {
      font-size: 18pt;
      font-weight: 800;
      color: #1a1a2e;
    }
    .sum-card .lbl {
      font-size: 8pt;
      color: #6b7280;
      margin-top: 3px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .sum-card.danger { border-color: #ef4444; }
    .sum-card.danger .val { color: #ef4444; }
    .sum-card.safe   { border-color: #10b981; }
    .sum-card.safe   .val { color: #10b981; }
    .sum-card.apar   { border-color: #f97316; }
    .sum-card.apar   .val { color: #f97316; }

    /* ── Table ── */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 9.5pt;
    }
    thead tr {
      background: #1a1a2e;
      color: #fff;
    }
    thead th {
      padding: 9px 12px;
      text-align: left;
      font-weight: 700;
      font-size: 8.5pt;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    tbody tr:nth-child(even) { background: #f9fafb; }
    tbody tr:nth-child(odd)  { background: #fff; }
    tbody td {
      padding: 8px 12px;
      border-bottom: 1px solid #e5e7eb;
      vertical-align: middle;
    }
    .td-date { color: #374151; font-weight: 600; }
    .td-time { color: #6b7280; }
    .td-gas  { font-weight: 700; color: #1a1a2e; }

    .badge-pdf {
      display: inline-block;
      padding: 2px 10px;
      border-radius: 20px;
      font-size: 8pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }
    .badge-bahaya { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
    .badge-aman   { background: #d1fae5; color: #059669; border: 1px solid #6ee7b7; }

    .apar-on  { color: #16a34a; font-weight: 600; }
    .apar-off { color: #9ca3af; }

    /* ── Footer ── */
    .pdf-footer {
      margin-top: 24px;
      padding-top: 12px;
      border-top: 1px solid #e5e7eb;
      font-size: 8.5pt;
      color: #9ca3af;
      display: flex;
      justify-content: space-between;
    }

    /* ── Print ── */
    @media print {
      body { padding: 20px 28px; }
      @page { margin: 1cm; size: A4 portrait; }
      .no-print { display: none !important; }
      table { page-break-inside: auto; }
      tr    { page-break-inside: avoid; }
      thead { display: table-header-group; }
    }
  </style>
</head>
<body>

  <!-- No-print button -->
  <div class="no-print" style="margin-bottom:18px; display:flex; gap:10px; align-items:center;">
    <button onclick="window.print()"
      style="padding:10px 22px; background:linear-gradient(135deg,#f97316,#ef4444);
             color:#fff; border:none; border-radius:8px; font-size:10pt;
             font-weight:700; cursor:pointer; font-family:inherit;">
      🖨️ Cetak / Simpan PDF
    </button>
    <button onclick="window.close()"
      style="padding:10px 18px; background:#f3f4f6; color:#374151;
             border:1px solid #d1d5db; border-radius:8px; font-size:10pt;
             cursor:pointer; font-family:inherit;">
      ✕ Tutup
    </button>
  </div>

  <!-- Header -->
  <div class="pdf-header">
    <div class="pdf-logo-area">
      <h1>🔥 SIKAGAS</h1>
      <p>Sistem Monitoring Kebocoran Gas LPG</p>
    </div>
    <div class="pdf-meta">
      <strong>Laporan Histori Sensor</strong><br>
      Dicetak: {{ now()->format('d/m/Y H:i') }}<br>
      Total data: {{ $logs->count() }} record
    </div>
  </div>

  <!-- Period -->
  <div class="pdf-period">📅 Periode: {{ $judulPeriode }}</div>

  <!-- Summary -->
  @php
    $totalBahaya = $logs->where('status', 'BAHAYA')->count();
    $totalAman   = $logs->where('status', 'AMAN')->count();
    $totalApar   = $logs->where('apar_aktif', true)->count();
    $maxGas      = $logs->max('gas_value') ?? 0;
  @endphp
  <div class="summary-grid">
    <div class="sum-card">
      <div class="val">{{ $logs->count() }}</div>
      <div class="lbl">Total Record</div>
    </div>
    <div class="sum-card danger">
      <div class="val">{{ $totalBahaya }}</div>
      <div class="lbl">Kejadian Bahaya</div>
    </div>
    <div class="sum-card safe">
      <div class="val">{{ $totalAman }}</div>
      <div class="lbl">Kondisi Aman</div>
    </div>
    <div class="sum-card apar">
      <div class="val">{{ $maxGas }}</div>
      <div class="lbl">Gas Tertinggi</div>
    </div>
  </div>

  <!-- Table -->
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Tanggal</th>
        <th>Waktu</th>
        <th>Nilai Gas</th>
        <th>APAR</th>
        <th>Buzzer</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($logs as $i => $log)
      <tr>
        <td class="td-time">{{ $i + 1 }}</td>
        <td class="td-date">{{ $log->created_at->format('d/m/Y') }}</td>
        <td class="td-time">{{ $log->created_at->format('H:i:s') }}</td>
        <td class="td-gas">{{ $log->gas_value }}</td>
        <td class="{{ $log->apar_aktif ? 'apar-on' : 'apar-off' }}">
          {{ $log->apar_aktif ? 'Aktif' : 'Mati' }}
        </td>
        <td class="{{ $log->buzzer_aktif ? 'apar-on' : 'apar-off' }}">
          {{ $log->buzzer_aktif ? 'Aktif' : 'Mati' }}
        </td>
        <td>
          @if($log->status === 'BAHAYA')
            <span class="badge-pdf badge-bahaya">BAHAYA</span>
          @else
            <span class="badge-pdf badge-aman">AMAN</span>
          @endif
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" style="text-align:center; padding:2rem; color:#9ca3af;">
          Tidak ada data untuk periode ini
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <!-- Footer -->
  <div class="pdf-footer">
    <span>SIKAGAS – Sistem Monitoring Gas LPG | sikagas.web.id</span>
    <span>Laporan otomatis dibuat oleh sistem</span>
  </div>

  <script>
    // Auto trigger print dialog setelah halaman siap
    window.addEventListener('load', function() {
      // Tunda 800ms agar render sempurna
      setTimeout(function() {
        window.print();
      }, 800);
    });
  </script>
</body>
</html>
