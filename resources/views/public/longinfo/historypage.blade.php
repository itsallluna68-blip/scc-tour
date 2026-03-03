<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>The History of San Carlos City</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
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

<body class="bg-gray-50 text-gray-800">

  {{-- Header --}}
  @include('components.fnavbar')
  <div class="mb-12"></div>

  {{-- HISTORY SECTION --}}
  <section class="max-w-7xl mx-auto px-6 py-20 text-center">

    <!-- Title -->
    <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-gray-900 mb-4 uppercase">
      The History of San Carlos City
    </h1>

    <!-- Small Tagline -->
    <p class="text-gray-600 text-sm md:text-base mb-10">
      {!! nl2br(e($settings['history_subtitle'] ?? 'Learn about the rich history and cultural heritage of San Carlos City.')) !!}
    </p>

    <!-- Image -->
    <div class="flex justify-center mb-12">
      @php
          $historyImg = $settings['historyImg'][0] ?? null;
      @endphp
      <img src="{{ $historyImg ? asset('uploads/settings/' . $historyImg) : asset('image/scc_ovw.jpg') }}"
           class="w-full md:w-[600px] h-[400px] object-cover shadow-xl">
    </div>

    <!-- Text Content -->
    <div class="max-w-4xl mx-auto text-gray-700 text-lg md:text-base leading-relaxed space-y-6 text-justify">
      {!! nl2br(e($settings['historyTxt'] ?? 'No content added yet.')) !!}
    </div>

  </section>

  {{-- Footer --}}
  @include('components.ffooter')

</body>

</html>