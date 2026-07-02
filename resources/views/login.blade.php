<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Sistem Monitoring Gas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at 10% 20%, rgb(90, 8, 8) 0%, rgb(16, 17, 22) 90%);
      color: #f3f4f6;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1.5rem;
      overflow: hidden;
      position: relative;
    }

    /* Ambient Background Glows */
    .glow-1 {
      position: absolute;
      width: 300px;
      height: 300px;
      background: rgba(220, 38, 38, 0.15);
      border-radius: 50%;
      top: 10%;
      left: 15%;
      filter: blur(80px);
      z-index: 1;
    }
    .glow-2 {
      position: absolute;
      width: 250px;
      height: 250px;
      background: rgba(249, 115, 22, 0.15);
      border-radius: 50%;
      bottom: 15%;
      right: 15%;
      filter: blur(70px);
      z-index: 1;
    }

    .login-container {
      background: rgba(31, 41, 55, 0.45);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 20px;
      width: 100%;
      max-width: 420px;
      padding: 2.5rem 2rem;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      z-index: 10;
      animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .brand-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .brand-logo {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
      display: inline-block;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.08); }
    }

    .brand-title {
      font-size: 1.35rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: 0.5px;
    }

    .brand-subtitle {
      font-size: 0.8rem;
      color: #9ca3af;
      margin-top: 4px;
    }

    /* FORM ELEMENTS */
    .form-group {
      margin-bottom: 1.25rem;
    }

    .form-label {
      display: block;
      font-size: 0.8rem;
      font-weight: 500;
      color: #d1d5db;
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-input {
      width: 100%;
      background: rgba(17, 24, 39, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      padding: 12px 16px;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      font-size: 0.875rem;
      outline: none;
      transition: all 0.2s;
    }

    .form-input:focus {
      border-color: #ef4444;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
      background: rgba(17, 24, 39, 0.8);
    }

    .remember-group {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.75rem;
      font-size: 0.8rem;
      color: #9ca3af;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
    }

    .remember-me input {
      accent-color: #ef4444;
      cursor: pointer;
    }

    /* BUTTONS */
    .btn-login {
      display: block;
      width: 100%;
      background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      transition: all 0.2s;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    /* ERRORS */
    .error-box {
      background: rgba(220, 38, 38, 0.15);
      border: 1px solid rgba(220, 38, 38, 0.3);
      color: #fca5a5;
      padding: 0.75rem 1rem;
      border-radius: 10px;
      font-size: 0.8rem;
      margin-bottom: 1.5rem;
      list-style-type: none;
    }

    .error-box li {
      margin-bottom: 2px;
    }
    .error-box li:last-child {
      margin-bottom: 0;
    }
  </style>
</head>
<body>

  <div class="glow-1"></div>
  <div class="glow-2"></div>

  <div class="login-container">
    <div class="brand-header">
      <span class="brand-logo">🔥</span>
      <h1 class="brand-title">SIKAGAS</h1>
      <p class="brand-subtitle">Sistem Informasi & Keamanan Gas LPG</p>
    </div>

    <!-- ERROR LIST -->
    @if ($errors->any())
      <ul class="error-box">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input 
          type="email" 
          name="email" 
          id="email" 
          class="form-input" 
          placeholder="admin@example.com" 
          value="{{ old('email') }}" 
          required 
          autofocus
        >
      </div>

      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input 
          type="password" 
          name="password" 
          id="password" 
          class="form-input" 
          placeholder="••••••••" 
          required
        >
      </div>

      <div class="remember-group">
        <label class="remember-me">
          <input type="checkbox" name="remember">
          Ingat Saya
        </label>
      </div>

      <button type="submit" class="btn-login">Masuk</button>
    </form>
  </div>

</body>
</html>
