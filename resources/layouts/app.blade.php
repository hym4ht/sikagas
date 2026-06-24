<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') | My Portfolio</title>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

  <!-- Navbar -->
  <header class="bg-white shadow-md fixed inset-x-0 top-0 z-50">
    <nav class="container mx-auto flex items-center justify-between p-4">
      <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">MyPortfolio</a>

      <button id="menu-toggle" class="md:hidden text-gray-700 focus:outline-none" aria-label="Toggle menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <ul id="menu" class="hidden md:flex space-x-6 font-medium">
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">Home</a></li>
        <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">About</a></li>
        <li><a href="{{ route('skill') }}" class="{{ request()->routeIs('skill') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">Skills</a></li>
        <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">Contact</a></li>
      </ul>
    </nav>
  </header>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg">
    <ul class="flex flex-col space-y-2 p-4">
      <li><a href="{{ route('home') }}" class="block py-2 px-4 rounded {{ request()->routeIs('home') ? 'bg-blue-100 text-blue-700' : 'hover:bg-blue-50' }}">Home</a></li>
      <li><a href="{{ route('about') }}" class="block py-2 px-4 rounded {{ request()->routeIs('about') ? 'bg-blue-100 text-blue-700' : 'hover:bg-blue-50' }}">About</a></li>
      <li><a href="{{ route('skill') }}" class="block py-2 px-4 rounded {{ request()->routeIs('skill') ? 'bg-blue-100 text-blue-700' : 'hover:bg-blue-50' }}">Skills</a></li>
      <li><a href="{{ route('contact') }}" class="block py-2 px-4 rounded {{ request()->routeIs('contact') ? 'bg-blue-100 text-blue-700' : 'hover:bg-blue-50' }}">Contact</a></li>
    </ul>
  </div>

  <!-- Content -->
  <main class="flex-grow mt-20 container mx-auto px-4">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="bg-gray-800 text-gray-200 mt-10">
    <div class="container mx-auto p-6 text-center">
      <p>&copy; {{ date('Y') }} MyPortfolio. All rights reserved.</p>
    </div>
  </footer>

  <!-- JS for Mobile Menu -->
  <script>
    (function(){
      const menuToggle = document.getElementById('menu-toggle');
      const mobileMenu = document.getElementById('mobile-menu');
      if(menuToggle){
        menuToggle.addEventListener('click', () => {
          mobileMenu.classList.toggle('hidden');
        });
      }
    })();
  </script>
</body>
</html>
