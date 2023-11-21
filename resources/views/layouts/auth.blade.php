<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>CMS Administrator for UMKMFEST</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }} " />
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="author" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta property="og:title" content="CMS Administrator for UMKMFEST" />

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>

<body>
  <main>@yield('content')</main>
</body>

</html>
