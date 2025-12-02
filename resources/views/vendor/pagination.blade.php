@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-4">
   <span class="text-sm text-gray-500">
      Showing
      <span class="font-medium">{{ $paginator->firstItem() }}</span>
      to
      <span class="font-medium">{{ $paginator->lastItem() }}</span>
      of
      <span class="font-medium">{{ $paginator->total() }}</span>
      results
   </span>

   <div class="join">
      {{-- Previous Button --}}
      @if ($paginator->onFirstPage())
      <button class="join-item btn btn-sm btn-disabled" disabled>«</button>
      @else
      <a href="{{ $paginator->previousPageUrl() }}" class="join-item btn btn-sm">«</a>
      @endif

      {{-- Pagination Elements --}}
      @foreach ($elements as $element)
      {{-- "Three Dots" Separator --}}
      @if (is_string($element))
      <button class="join-item btn btn-sm btn-disabled" disabled>{{ $element }}</button>
      @endif

      {{-- Array Of Links --}}
      @if (is_array($element))
      @foreach ($element as $page => $url)
      @if ($page == $paginator->currentPage())
      <button class="join-item btn btn-sm bg-primary text-white hover:bg-primary-focus">{{ $page }}</button>
      @else
      <a href="{{ $url }}" class="join-item btn btn-sm hover:bg-primary hover:text-white">{{ $page }}</a>
      @endif
      @endforeach
      @endif
      @endforeach

      {{-- Next Button --}}
      @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" class="join-item btn btn-sm">»</a>
      @else
      <button class="join-item btn btn-sm btn-disabled" disabled>»</button>
      @endif
   </div>
</nav>
@endif