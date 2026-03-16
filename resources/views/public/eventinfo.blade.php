<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upcoming Events</title>
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  @vite(['resources/css/app.css', 'resources/js/public.js'])
  <style>
    .arrow-btn {
      @apply absolute top-1/2 -translate-y-1/2 bg-black bg-opacity-30 text-white p-2 rounded-full cursor-pointer hover:bg-opacity-50;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  @include('components.fnavbar')
  <div class="mb-12"></div>
  <section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 space-y-12">
      @forelse ($events as $date => $dayEvents)
      <h2 class="text-2xl font-bold text-indigo-600">{{ $date }}</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($dayEvents as $evt)
        <div class="bg-white rounded-lg shadow p-5 space-y-3">
          <img src="{{ !empty($evt->pics) && is_array($evt->pics) && count($evt->pics) > 0 ? asset('storage/' . $evt->pics[0]) : asset('image/sample-event.jpg') }}" class="w-full h-48 object-cover rounded-md">
          <p>{{ \Carbon\Carbon::parse($evt->e_datetime)->format('F j, Y g:i A') }}</p>
          <h1 class="text-xl font-semibold">{{ $evt->events }}</h1>
          <p class="text-gray-600 line-clamp-2">{{ $evt->e_info }}</p>
          <a href="{{ route('events.show', $evt->id) }}" class="inline-block text-blue-600 font-medium hover:underline">View Details →</a>
        </div>
        @endforeach
      </div>
      @empty
      <p class="text-gray-500">No events scheduled for this month.</p>
      @endforelse
    </div>
  </section>
  @include('components.ffooter')
</body>

</html>