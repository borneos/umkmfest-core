@php
  if ($columnName == $sortColumn) {
      if ($sortDirection == 'asc') {
          $currentSortDirection = 'asc';
          $sortDirection = 'desc';
      } elseif ($sortDirection == 'desc') {
          $currentSortDirection = 'desc';
          $sortDirection = '';
      } else {
          $currentSortDirection = '';
          $sortDirection = 'asc';
      }
  } else {
      $currentSortDirection = '';
      $sortDirection = 'asc';
  }
@endphp

<div class="flex items-center gap-1">
  <a href="{{ route($dataRoute, ['sortDirection' => $sortDirection, 'sortColumn' => $columnName]) }}">
    <span class="text-primary text-base">{{ $slot }}</span>
  </a>
  @if ($columnName == $sortColumn)
    @if ($currentSortDirection == 'asc')
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" />
      </svg>
    @elseif($currentSortDirection == 'desc')
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" />
      </svg>
    @endif
  @endif
</div>
