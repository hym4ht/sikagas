<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About | Sistem Gas</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Poppins', 'sans-serif'],
          },
          colors: {
            primary: '#4b5563', // abu gelap (biar mirip desain kamu)
            soft: '#f3f4f6',
          },
        },
      },
    };
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>

<body class="bg-gray-100 text-gray-700">

<!-- Navbar -->
<header class="bg-white shadow-sm">
  <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
    <h1 class="font-semibold text-lg">Sistem Monitoring Gas</h1>
    <div class="space-x-6 text-sm">
      <a href="#" class="text-primary font-medium">Dashboard</a>
      <a href="#">Notifikasi</a>
      <a href="#">Kontrol APAR</a>
    </div>
  </div>
</header>

<!-- MAIN -->
<main class="max-w-6xl mx-auto p-6">

  <!-- TOP INFO -->
  <div class="grid md:grid-cols-2 gap-6 mb-6">

    <div class="bg-white p-5 rounded-xl shadow-sm flex justify-between">
      <span>Gas Level</span>
      <b>120 PPM</b>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm flex justify-between">
      <span>Suhu Ruangan</span>
      <b>27°C</b>
    </div>

  </div>

  <!-- MONITORING -->
  <div class="bg-white p-6 rounded-xl shadow-sm mb-6">
    <h2 class="font-semibold mb-4">Monitoring</h2>

    <div class="grid md:grid-cols-2 gap-6">

      <div class="bg-gray-50 p-6 rounded-xl text-center">
        <p class="text-sm text-gray-500">Gas Level</p>
        <h1 class="text-3xl font-semibold mt-2">120 <span class="text-sm">PPM</span></h1>
      </div>

      <div class="bg-gray-50 p-6 rounded-xl text-center">
        <p class="text-sm text-gray-500">Temperature</p>
        <h1 class="text-3xl font-semibold mt-2">27°C</h1>
      </div>

    </div>
  </div>

  <!-- STATUS APAR -->
  <div class="bg-white p-6 rounded-xl shadow-sm mb-6 flex items-center justify-between">

    <div>
      <p class="text-sm text-gray-500">Status APAR</p>
      <h2 class="text-xl font-semibold mt-1">APAR AKTIF</h2>
      <p class="text-xs text-gray-400 mt-1">Automatically Activated</p>
      <p class="text-xs text-gray-400 mt-2">Last Activation: 10:15 AM</p>
    </div>

    <div class="text-4xl">🧯</div>

  </div>

  <!-- BUTTON -->
  <a href="tel:113"
     class="block text-center bg-gray-700 text-white py-4 rounded-xl shadow hover:bg-gray-800 transition">
     📞 Hubungi Pemadam Kebakaran
  </a>

</main>

<x-footer />

</body>
</html>