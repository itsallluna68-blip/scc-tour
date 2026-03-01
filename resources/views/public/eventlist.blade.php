<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Events</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    {{-- fnavbar --}}
    @include('components.fnavbar')
    <div class="mb-12"></div>

  <section class="py-16">
    <div class="max-w-6xl mx-auto px-4">

      <div class="mb-12 text-center">
        <h1 class="text-4xl font-bold text-indigo-900 mb-2">
          EVENTS
        </h1>
        <p class="text-gray-600">
          Stay updated with the latest happenings in San Carlos City
        </p>
      </div>

      @forelse ($events as $event)
  <div class="bg-white rounded-xl shadow-md p-8 mb-8">
    <div class="flex flex-col md:flex-row items-center gap-8">

      <img
        src="{{ $event->pic0 && is_array($event->pic0) && count($event->pic0) > 0
                ? asset('image/' . $event->pic0[0])
                : asset('image/sample-event.jpg') }}"
        class="w-full md:w-1/2 h-72 object-cover rounded-lg"
        alt="{{ $event->title }}"
        >

      <div class="flex-1 text-left space-y-3">
        <p class="text-sm text-indigo-600 font-medium">
          {{ \Carbon\Carbon::parse($event->e_datetime)->format('F d, Y • g:i A') }}
        </p>

        <h2 class="text-2xl font-semibold">
          {{ $event->title }}
        </h2>

        <p class="text-gray-600">
          {{ $event->e_location }}
        </p>

        <a href="{{ route('events.show', $event->id) }}"
        class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
        Read More
        </a>
      </div>

    </div>
  </div>
@empty
  <p class="text-center text-gray-500">
    No events available at the moment.
  </p>
@endforelse


    </div>
  </section>

  {{-- Footer --}}
  @include('components.ffooter')

</body>
</html>
