@extends('layouts.app')
@section('title')
  {{-- <div id="titleEvent"> --}}
  Visitor Event {{ $eventTitle->name ?? 'All Events' }}
  {{-- </div> --}}
@endsection
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
  <section id="list">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="flex justify-between items-center pb-6">
          <form id="search" action="{{ route('admin.events.visitor', request()->query()) }}">
            <div class="flex my-2">
              <input type="hidden" name="sortColumn" value="{{ $sortColumn }}" />
              <input type="hidden" name="sortDirection" value="{{ $sortDirection }}" />
              <select name="event" id="event" class="select border border-gray-200 :outline-none">
                <option disabled selected>Pilih Nama Event</option>
                @foreach ($events as $event)
                  <option value="{{ $event->id }}" data-doj="{{ $event->name }}" {{ $event->id == request()->get('event') ? 'selected' : '' }}>{{ $event->name }}</option>
                @endforeach
              </select>
            </div>
          </form>
        </div>
        <div class="card bg-white rounded-lg">
          <div class="card-body p-0">
            <div class="overflow-x-auto">
              <table class="table">
                <thead>
                  <tr>
                    <th width="3%">
                      <x-column-header dataRoute="admin.events" column-name="id" :sort-column="$sortColumn" :sortDirection="$sortDirection">#</x-column-header>
                    </th>
                    <th>
                      <x-column-header dataRoute="admin.events" column-name="name" :sort-column="$sortColumn" :sortDirection="$sortDirection">Name</x-column-header>
                    </th>
                    <th>
                      <x-column-header dataRoute="admin.events" column-name="telp" :sort-column="$sortColumn" :sortDirection="$sortDirection">Telepon</x-column-header>
                    </th>
                    <th>
                      <x-column-header dataRoute="admin.events" column-name="email" :sort-column="$sortColumn" :sortDirection="$sortDirection">Email</x-column-header>
                    </th>
                    <th>
                      <x-column-header dataRoute="admin.events" column-name="checkin_at" :sort-column="$sortColumn" :sortDirection="$sortDirection">Check In</x-column-header>
                    </th>
                    <th>
                      Attendance
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($visitors as $visitor)
                    <tr>
                      <td>{{ $visitor->id }}</td>
                      <td>
                        <div class="flex items-center space-x-3">
                          <div class="avatar">
                            <div class="mask mask-squircle w-9 h-9">
                              @if ($visitor->image != '')
                                <img src="{{ $visitor->image }}" alt="{{ $visitor->name }}">
                              @else
                                <img src="https://placehold.co/100x100" alt="blank" />
                              @endif
                            </div>
                          </div>
                          <div>
                            <div class="font-bold">{{ $visitor->name }}</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        {{ $visitor->telp }}
                      </td>
                      <td>
                        {{ $visitor->email }}
                      </td>
                      <td>
                        {{ $visitor->checkin_at }}
                      </td>
                      <td>
                        <form action="{{ route('admin.events.visitor.attendance', $visitor->id) }}">
                          <input type="checkbox" class="toggle" data-toggle="toggle" data-size="small" onChange="this.form.submit()" {{ $visitor->attendance ? 'checked' : '' }}>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        {{ $visitors->appends(['sortDirection' => request()->sortDirection, 'sortColumn' => request()->sortColumn, 'q' => request()->q])->onEachSide(5)->links() }}
      </div>
    </div>
  </section>
@endsection
@section('js')
  <script>
    $('#event').on('change', function() {
      $(this).closest('#search').submit();
    });
  </script>
@endsection
