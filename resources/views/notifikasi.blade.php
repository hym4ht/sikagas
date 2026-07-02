<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Histori | Sistem Monitoring Gas</title>
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

    h2 { font-size: 1.1rem; font-weight: 600; color: #111827; margin-bottom: 1.25rem; }

    /* FILTER */
    .filter-bar {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 1rem 1.25rem;
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
      align-items: center;
      margin-bottom: 1.25rem;
    }

    .filter-bar input,
    .filter-bar select {
      background: #f9fafb;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      color: #374151;
      font-family: 'Poppins', sans-serif;
      font-size: 0.82rem;
      padding: 7px 11px;
      outline: none;
      transition: border-color 0.15s;
    }
    .filter-bar input:focus,
    .filter-bar select:focus { border-color: #6b7280; }

    .btn-filter {
      background: #1f2937;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 7px 18px;
      font-size: 0.82rem;
      font-weight: 500;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      transition: opacity 0.15s;
    }
    .btn-filter:hover { opacity: 0.85; }

    /* TABLE */
    .table-wrap {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      overflow: hidden;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }

    thead { background: #f9fafb; }
    thead th {
      text-align: left;
      padding: 12px 16px;
      font-size: 0.75rem;
      font-weight: 600;
      color: #6b7280;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border-bottom: 1px solid #e5e7eb;
    }

    tbody tr { border-bottom: 1px solid #f3f4f6; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f9fafb; }
    tbody td { padding: 12px 16px; color: #374151; }

    .empty {
      padding: 3rem;
      text-align: center;
      color: #9ca3af;
      font-size: 0.875rem;
    }

    @media (max-width: 580px) {
      .filter-bar { flex-direction: column; }
      .filter-bar input, .filter-bar select, .btn-filter { width: 100%; }
      table { min-width: 480px; }
      h2 { font-size: 1rem; }
    }
  </style>
</head>
<body>

<nav>
  <span class="nav-brand">🔥 Monitoring Gas</span>
  <div class="nav-links">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('notifikasi') }}" class="active">Notifikasi</a>
    <a href="{{ route('apar') }}">Kontrol APAR</a>
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
      @csrf
      <button type="submit" class="btn-logout">Keluar</button>
    </form>
  </div>
</nav>

<main>

  <h2>Histori Sistem</h2>

  <!-- FILTER -->
  <form method="GET" action="" class="filter-bar">
    <input type="date" name="tanggal">
    <select name="bulan">
      <option value="">Bulan</option>
      <option value="1">Januari</option>
      <option value="2">Februari</option>
      <option value="3">Maret</option>
      <option value="4">April</option>
      <option value="5">Mei</option>
      <option value="6">Juni</option>
      <option value="7">Juli</option>
      <option value="8">Agustus</option>
      <option value="9">September</option>
      <option value="10">Oktober</option>
      <option value="11">November</option>
      <option value="12">Desember</option>
    </select>
    <select name="tahun">
      <option value="">Tahun</option>
      <option>2024</option>
      <option>2025</option>
      <option>2026</option>
    </select>
    <button type="submit" class="btn-filter">Cari</button>
  </form>

  <!-- TABLE -->
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Waktu</th>
          <th>Kejadian</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
          <tr>
            <td>{{ $log->created_at->format('d-m-Y') }}</td>
            <td>{{ $log->created_at->format('H:i:s') }}</td>
            <td>Gas: {{ $log->gas_value }} | APAR: {{ $log->apar_aktif ? 'Aktif' : 'Mati' }} | Buzzer: {{ $log->buzzer_aktif ? 'Aktif' : 'Mati' }}</td>
            <td>
              @if($log->status === 'BAHAYA')
                <span style="color: #dc2626; font-weight: bold;">BAHAYA 🚨</span>
              @elseif($log->status === 'WASPADA')
                <span style="color: #d97706; font-weight: bold;">WASPADA ⚠️</span>
              @else
                <span style="color: #059669; font-weight: bold;">AMAN ✅</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="empty">Belum ada data histori</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</main>
</body>
</html>