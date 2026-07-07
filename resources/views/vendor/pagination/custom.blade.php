@if ($paginator->hasPages())
<style>
  .pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 1.25rem;
  }
  .pg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    border: 1px solid var(--border);
    background: rgba(17,24,39,.6);
    color: var(--muted);
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
  }
  .pg-btn:hover {
    background: rgba(255,255,255,.07);
    color: var(--text);
    border-color: rgba(255,255,255,.15);
  }
  .pg-btn.active {
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff;
    border-color: transparent;
    box-shadow: 0 2px 10px rgba(249,115,22,.35);
  }
  .pg-btn.disabled {
    opacity: 0.35;
    pointer-events: none;
    cursor: default;
  }
  .pg-dots {
    color: var(--muted);
    font-size: 0.8rem;
    padding: 0 4px;
    line-height: 34px;
  }
  @media (max-width: 480px) {
    .pg-btn { min-width: 30px; height: 30px; font-size: 0.73rem; padding: 0 8px; }
  }
</style>

<nav class="pagination-wrap" aria-label="Navigasi halaman">

  {{-- Previous --}}
  @if ($paginator->onFirstPage())
    <span class="pg-btn disabled">‹ Prev</span>
  @else
    <a class="pg-btn" href="{{ $paginator->previousPageUrl() }}">‹ Prev</a>
  @endif

  {{-- Pages --}}
  @foreach ($elements as $element)
    @if (is_string($element))
      <span class="pg-dots">{{ $element }}</span>
    @endif

    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
          <span class="pg-btn active">{{ $page }}</span>
        @else
          <a class="pg-btn" href="{{ $url }}">{{ $page }}</a>
        @endif
      @endforeach
    @endif
  @endforeach

  {{-- Next --}}
  @if ($paginator->hasMorePages())
    <a class="pg-btn" href="{{ $paginator->nextPageUrl() }}">Next ›</a>
  @else
    <span class="pg-btn disabled">Next ›</span>
  @endif

</nav>
@endif
