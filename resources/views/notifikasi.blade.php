<x-layout title="Histori Notifikasi">

  <x-slot name="head">
    <style>
      /* FILTER BAR */
      .filter-bar {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: flex-end;
        margin-bottom: 1.5rem;
        padding: 1.25rem;
      }
      .filter-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 120px; }

      /* TABLE */
      .table-wrap {
        border-radius: var(--radius);
        overflow: hidden;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border: 1px solid var(--border);
        background: rgba(17,24,39,.7);
        backdrop-filter: blur(12px);
      }
      table { width: 100%; border-collapse: collapse; min-width: 500px; }
      thead { background: rgba(255,255,255,.04); border-bottom: 1px solid var(--border); }
      thead th {
        text-align: left;
        padding: 12px 16px;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        white-space: nowrap;
      }
      tbody tr { border-bottom: 1px solid rgba(255,255,255,.04); transition: background 0.15s; }
      tbody tr:last-child { border-bottom: none; }
      tbody tr:hover { background: rgba(255,255,255,.04); }
      tbody td { padding: 13px 16px; font-size: 0.82rem; color: var(--text); }
      .td-time { color: var(--muted); font-size: 0.75rem; }
      .empty-row td {
        padding: 3rem;
        text-align: center;
        color: var(--muted);
        font-size: 0.875rem;
      }

      @media (max-width: 600px) {
        .filter-bar { gap: 0.5rem; }
        .filter-group { min-width: 100%; }
      }
    </style>
  </x-slot>

  <div class="page-header">
    <h1>🔔 Histori Sistem</h1>
    <p>Rekaman data sensor dan kejadian sistem gas</p>
  </div>

  <!-- FILTER -->
  <div class="glass-card filter-bar">
    <form method="GET" action="" style="display:contents;">
      <div class="filter-group">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-input" value="{{ request('tanggal') }}">
      </div>
      <div class="filter-group">
        <label class="form-label">Bulan</label>
        <select name="bulan" class="form-select">
          <option value="">Semua Bulan</option>
          @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $b)
            <option value="{{ $i+1 }}" {{ request('bulan') == $i+1 ? 'selected' : '' }}>{{ $b }}</option>
          @endforeach
        </select>
      </div>
      <div class="filter-group">
        <label class="form-label">Tahun</label>
        <select name="tahun" class="form-select">
          <option value="">Semua</option>
          @foreach([2024, 2025, 2026] as $y)
            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex; align-items:flex-end;">
        <button type="submit" class="btn btn-primary" style="height:40px;">🔍 Filter</button>
      </div>
    </form>
  </div>

  <!-- TABLE -->
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Waktu</th>
          <th>Detail</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
          <tr>
            <td class="td-time">{{ $log->created_at->format('d/m/Y') }}</td>
            <td class="td-time">{{ $log->created_at->format('H:i:s') }}</td>
            <td>
              Gas: <strong>{{ $log->gas_value }}</strong> PPM &nbsp;|&nbsp;
              APAR: {{ $log->apar_aktif ? '🟢 Aktif' : '⚫ Mati' }} &nbsp;|&nbsp;
              Buzzer: {{ $log->buzzer_aktif ? '🟢 Aktif' : '⚫ Mati' }}
            </td>
            <td>
              @if($log->status === 'BAHAYA')
                <span class="badge badge-red">🚨 BAHAYA</span>
              @elseif($log->status === 'WASPADA')
                <span class="badge badge-yellow">⚠️ WASPADA</span>
              @else
                <span class="badge badge-green">✅ AMAN</span>
              @endif
            </td>
          </tr>
        @empty
          <tr class="empty-row">
            <td colspan="4">📭 Belum ada data histori</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</x-layout>