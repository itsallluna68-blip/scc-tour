<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upcoming Events</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Simple slideshow arrows */
    .arrow-btn {
      @apply absolute top-1/2 -translate-y-1/2 bg-black bg-opacity-30 text-white p-2 rounded-full cursor-pointer hover:bg-opacity-50;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">

  {{-- fnavbar --}}
  @include('components.fnavbar')
  <div class="mb-12"></div>

 <section class="py-16 bg-gray-50">
  <div class="max-w-6xl mx-auto px-4 space-y-12">

    @forelse ($event as $date => $dayEvents)

      {{-- DATE HEADER --}}
      <h2 class="text-2xl font-bold text-indigo-600">
        {{ $date }}
      </h2>

      <div class="grid md:grid-cols-2 gap-6">
        @foreach ($dayEvents as $event)
          <div class="bg-white rounded-lg shadow p-5 space-y-3">

            <img
              src="{{ $event->pic0 && is_array($event->pic0) && count($event->pic0) > 0 ? asset('image/' . $event->pic0[0]) : asset('image/sample-event.jpg') }}"
              class="w-full h-48 object-cover rounded-md"
            >

            <p>
              {{ \Carbon\Carbon::parse($event->e_datetime)->format('F j, Y g:i A') }}
            </p>

            <h1>{{ $event->events }}</h1>

            <p>
              {{ $event->e_info }}
            </p>

            <a href="{{ route('events.show', $event->id) }}"
               class="text-blue-600 font-medium hover:underline">
              View Details →
            </a>

          </div>
        @endforeach
      </div>

    @empty
      <p class="text-gray-500">No events scheduled for this month.</p>
    @endforelse

  </div>
</section>


  {{-- Footer --}}
  @include('components.ffooter')

</body>
</html>
