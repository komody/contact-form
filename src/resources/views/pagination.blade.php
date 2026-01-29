@if ($paginator->hasPages())
  <nav class="custom-pagination">
    <ul class="pagination">
      {{-- 前のページへのリンク --}}
      @if ($paginator->onFirstPage())
        <li class="page-item disabled">
          <span class="page-link page-arrow">&lt;</span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link page-arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lt;</a>
        </li>
      @endif

      {{-- ページ番号 --}}
      @foreach ($elements as $element)
        {{-- "Three Dots" セパレーター --}}
        @if (is_string($element))
          <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
        @endif

        {{-- ページ番号の配列 --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li class="page-item active">
                <span class="page-link">{{ $page }}</span>
              </li>
            @else
              <li class="page-item">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
              </li>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- 次のページへのリンク --}}
      @if ($paginator->hasMorePages())
        <li class="page-item">
          <a class="page-link page-arrow" href="{{ $paginator->nextPageUrl() }}" rel="next">&gt;</a>
        </li>
      @else
        <li class="page-item disabled">
          <span class="page-link page-arrow">&gt;</span>
        </li>
      @endif
    </ul>
  </nav>
@endif
