<style>
  .sikagas-footer {
    position: relative;
    z-index: 1;
    margin-top: auto;
    padding: 1.5rem 1.5rem;
    background: rgba(10, 13, 20, 0.9);
    backdrop-filter: blur(12px);
    border-top: 1px solid rgba(255,255,255,0.06);
    text-align: center;
    font-family: 'Inter', sans-serif;
  }

  .sikagas-footer .footer-inner {
    max-width: 900px;
    margin: 0 auto;
  }

  .sikagas-footer .footer-brand {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.6rem;
  }

  .sikagas-footer .footer-icon {
    width: 26px; height: 26px;
    background: linear-gradient(135deg, #f97316, #ef4444);
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem;
  }

  .sikagas-footer .brand-name {
    font-weight: 700;
    font-size: 0.85rem;
    color: #f9fafb;
    letter-spacing: 0.06em;
  }

  .sikagas-footer .brand-x {
    color: #f97316;
    font-weight: 700;
    font-size: 0.75rem;
  }

  .sikagas-footer .footer-divider {
    width: 36px;
    height: 2px;
    background: linear-gradient(90deg, #f97316, #fbbf24);
    border-radius: 2px;
    margin: 0.6rem auto;
    opacity: 0.5;
  }

  .sikagas-footer .footer-copy {
    color: #4b5563;
    font-size: 0.7rem;
    line-height: 1.7;
  }

  .sikagas-footer .footer-copy strong {
    color: #6b7280;
    font-weight: 600;
  }
</style>

<footer class="sikagas-footer">
  <div class="footer-inner">
    <div class="footer-brand">
      <div class="footer-icon">🔥</div>
      <span class="brand-name">SIKAGAS</span>
      <span class="brand-x">✕</span>
      <span class="brand-name">Universitas Harkat Negeri Tegal</span>
    </div>
    <div class="footer-divider"></div>
    <p class="footer-copy">
      &copy; {{ date('Y') }} <strong>SIKAGAS</strong> &mdash; Sistem Informasi Keamanan Gas LPG.<br>
      Dikembangkan bekerja sama dengan <strong>Universitas Harkat Negeri Tegal</strong>.
    </p>
  </div>
</footer>
