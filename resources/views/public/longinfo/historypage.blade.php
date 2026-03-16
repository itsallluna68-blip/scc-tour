<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>History | San Carlos City</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
  <style>
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    .fade-up {
      opacity: 0;
      transform: translateY(30px);
      transition: all 1s ease-out;
    }

    .fade-up.show {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800">

  {{-- header --}}
  @include('components.fnavbar')
  <div class="mb-12"></div>

  {{-- HISTORY CONTENT --}}
  <section class="max-w-5xl mx-auto px-6 py-16">

    <!-- Title -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            {{ $settings['history_title'] ?? 'History San Carlos City' }}
        </h1>

        <p class="text-gray-600 text-lg">
            {{ $settings['history_subtitle'] ?? '' }}
        </p>
    </div>

    <!-- Image -->
    <div class="flex justify-center mb-10">
        @php
            $historyImg = $settings['historyImg'][0] ?? null;
        @endphp
        <img src="{{ $historyImg ? asset('uploads/settings/' . $historyImg) : asset('image/scc_ovw.jpg') }}"
             class="w-full md:w-4/5 lg:w-3/4 h-72 object-cover rounded-2xl shadow-lg">
    </div>

    <!-- Content -->
    <div class="text-gray-700 leading-relaxed text-justify">
        {!! nl2br(e($settings['historyTxt'] ?? 'No content added yet.')) !!}
    </div>

</section>

  {{-- footer --}}
  @include('components.ffooter')

</body>

</html>