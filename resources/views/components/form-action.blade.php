@php
  $route = $route ?? '/dashboard';
  $type = $type;
  $closeUrl = $closeUrl ?? $route;
@endphp

<div class="flex items-center justify-end gap-2 mt-4">
  <a href="{{ $closeUrl }}" class="btn btn-light">Close</a>
  @if ($type == 'save')
    <button id="submitAdd" type="submit" class="btn btn-primary">Save<span id="loadingAdd" class="loading loading-spinner loading-sm hidden"></span></button>
  @elseif ($type == 'photo')
    <button id="submitAddPhoto" type="submit" class="btn btn-primary">Save<span id="loadingAddPhoto" class="loading loading-spinner loading-sm hidden"></span></button>
  @else
    <button id="submitEdit" type="submit" class="btn btn-primary">Update<span id="loadingEdit" class="loading loading-spinner loading-sm hidden"></span></button>
  @endif
</div>
