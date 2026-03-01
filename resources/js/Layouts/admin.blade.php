<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard')</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans text-gray-800">

  {{-- Sidebar --}}
  @include('components.sidebar')

  <div class="flex-1 ml-60">
    {{-- Header --}}
    @include('components.header')

    {{-- Page Content --}}
    <main class="p-8 mt-4">
      @yield('content')
    </main>
  </div>

</body>
</html>
