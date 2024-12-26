@extends('layouts.app')
@section('title', 'Master Banner')
@section('content')
  @php
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = 'https://';
    } else {
        $url = 'http://';
    }
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI'];
  @endphp

  <section>
    <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
      @foreach ($results as $item)
        <div class="card bg-base-100 shadow-xl">
          <div class="card-body content-between">
            <h2 class="card-title font-normal text-xl">{{ $item->event->name ?? 'Unknown Category' }}</h2>
          </div>
          <div class="card-footer flex p-3 pr-5 justify-between items-center">
            <span class="font-bold text-xl">Peserta Hadir : {{ $item->totalPesertaHadir }}</span>
            <span class="font-bold text-[3em]">{{ $item->totalPeserta }}</span>
          </div>
        </div>
      @endforeach
    </div>
  </section>
@endsection
@section('js')
  <script>
    setTimeout(() => {
      window.location.reload();
    }, 5000)
  </script>
@endsection
