<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin CMS PKTBEEDUFEST</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }} " />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>
  <div id="app">
    <div class="drawer lg:drawer-open drawer-mobile">
      <input id="left-sidebar-drawer" type="checkbox" class="drawer-toggle" />
      <div class="drawer-content flex flex-col">
        <div class="navbar flex justify-between bg-base-100 z-10 shadow-md">
          <div class="">
            <label for="left-sidebar-drawer" class="btn btn-primary drawer-button lg:hidden">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-5 inline-block w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
              </svg>
            </label>
            <h1 class="text-2xl font-semibold ml-2">
              @hasSection('title')
                @yield('title')
              @else
                Dashboard
              @endif
            </h1>
          </div>

          <div class="order-last">
            <div class="dropdown dropdown-end ml-4">
              <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full items-center">
                  <x-bi-person class="h-6 w-6" />
                  {{-- <img src="https://res.cloudinary.com/domqavi1p/image/upload/v1690634584/favicon_ablvyf.webp" alt="profile" /> --}}
                </div>
              </label>
              <ul tabindex="0" class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-52">
                <li class="justify-between">
                  <a href="#">
                    Profile
                  </a>
                </li>
                <li>
                  <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <main class="flex-1 overflow-y-auto pt-8 px-6 bg-base-200">@yield('content')</main>
      </div>
      <div class="drawer-side min" x-data="{ menus: datasidebar, pathname: window.location.pathname.split('/') }">
        <label html-for="left-sidebar-drawer" class="drawer-overlay"></label>
        <ul class="menu pt-2 w-80 min-h-screen bg-base-100 text-base">
          <button class="btn btn-ghost bg-base-300  btn-circle z-50 top-0 right-0 mt-4 mr-2 absolute lg:hidden" onClick={(e)=>close(e))}>
            <span class="h-5 inline-block w-5">XXX</span>
          </button>
          <li class="mb-2 font-semibold text-xl">
            <span>
              {{-- <img class="w-28" src="https://res.cloudinary.com/domqavi1p/image/upload/v1690634689/logo-long_yewhbj.webp" alt="Keubitbi Logo" /> --}}<h1>PKTBEEDUFEST </h1>
            </span>
          </li>
          <template x-for="item in menus">
            <div>
              <template x-if="!item.isLabel">
                <li class="">
                  <a class="font-bold my-[3px]" x-bind:class="pathname[1] === '' && item.name === 'dashboard' && 'active' || pathname[2] === item.name ? 'active' : ''" x-bind:href="item.link">
                    <span x-text="item.label">-</span>
                  </a>
                </li>
              </template>
              <template x-if="!!item.isLabel">
                <div class="mt-3" x-show="item.isLabel">
                  <span class="text-yellow-800 text-sm" x-text="item.label">
                  </span>
                </div>
              </template>
            </div>
          </template>
        </ul>
      </div>
    </div>
  </div>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.3/dist/cdn.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="//cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
  @yield('js')
</body>
<script>
  const datasidebar = [{
      id: 0,
      name: "dashboard",
      label: "Dashboard",
      link: "/",
      isLabel: false,
    },
    {
      id: 1,
      name: "master",
      label: "Master",
      link: "",
      isLabel: true,
    },
    {
      id: 2,
      name: "banners",
      label: "Master Banner",
      link: "/admin/banners?sortDirection=desc&sortColumn=id",
      isLabel: false,
    },
    {
      id: 3,
      name: "events",
      label: "Master Event",
      link: "/admin/events?sortDirection=desc&sortColumn=id",
      isLabel: false,
    },
    {
      id: 4,
      name: "games",
      label: "Master Games",
      link: "",
      isLabel: false,
    },
    {
      id: 5,
      name: "merchants",
      label: "Master Merchant",
      link: "/admin/merchants?sortDirection=desc&sortColumn=id",
      isLabel: false,
    },
    {
      id: 6,
      name: "main",
      label: "Main",
      link: "",
      isLabel: true,
    },
    {
      id: 7,
      name: "visitor events",
      label: "Visitor Event",
      link: "",
      isLabel: false,
    },
    {
      id: 8,
      name: "visitor games",
      label: "Visitor Games",
      link: "",
      isLabel: false,
    },
    {
      id: 9,
      name: "visitor game wins",
      label: "Visitor Game Wins",
      link: "",
      isLabel: false,
    }
  ];
</script>

@if (Session::has('success'))
  <script>
    toastr.options = {
      "closeButton": true,
      "progressBar": true
    }
    toastr.success("{{ session('success') }}");
  </script>
@endif

@if (Session::has('error'))
  <script>
    toastr.options = {
      "closeButton": true,
      "progressBar": true
    }
    toastr.error("{{ session('error') }}");
  </script>
@endif

@if (Session::has('info'))
  <script>
    toastr.options = {
      "closeButton": true,
      "progressBar": true
    }
    toastr.info("{{ session('info') }}");
  </script>
@endif

@if (Session::has('warning'))
  <script>
    toastr.options = {
      "closeButton": true,
      "progressBar": true
    }
    toastr.warning("{{ session('warning') }}");
  </script>
@endif

</html>
