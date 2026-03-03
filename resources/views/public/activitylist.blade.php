<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Activities in San Carlos City</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-900">

  {{-- Navbar --}}
  @include('components.fnavbar')
  <div class="mb-12"></div>

  {{-- Activities Section --}}
  <section class="bg-gray-50 py-16">
    <div class="max-w-6xl mx-auto px-4"> <!-- Smaller side margins -->

      {{-- Top Header --}}
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

        {{-- LEFT: Title + Description --}}
        <div>
          <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-black">
            Activities
          </h1>
          <p class="mt-2 text-gray-600 text-base md:text-lg max-w-md">
            Discover exciting activities you can experience in San Carlos City.
          </p>
        </div>

        {{-- RIGHT: Search with Magnifying Glass --}}
        <form method="GET" action="{{ route('activities.index') }}" class="flex w-full md:w-auto">
          <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search activities..."
            class="px-4 py-2 w-full md:w-64 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
          <button
            type="submit"
            class="bg-white text-black px-4 py-2 rounded-r-md hover:bg-gray-100 transition flex items-center justify-center"
          >
            <!-- Magnifying Glass Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
          </button>
        </form>

      </div>

      {{-- Divider --}}
      <div class="border-t border-gray-300 my-10"></div>

      {{-- Activities Grid --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        @forelse ($activities as $activity)

          <div class="overflow-hidden shadow-lg group rounded-md bg-gradient-to-b from-white to-indigo-50 transition duration-300 hover:shadow-2xl">

            {{-- Image --}}
            <div class="bg-white overflow-hidden">
              <img
                src="{{ $activity->img0 ? asset($activity->img0) : asset('image/sample-activity.jpg') }}"
                class="w-full h-64 object-cover"
              >
            </div>

            {{-- Content --}}
            <div class="p-4 flex items-center justify-between 
                        bg-gradient-to-b from-white to-indigo-50">
              <h3 class="text-lg font-semibold text-black">
                {{ $activity->a_name }}
              </h3>

              <a
                href="{{ route('activities.show', $activity->aid) }}"
                class="bg-white text-black text-sm px-4 py-2 rounded-md hover:bg-gray-100 transition shadow"
              >
                View
              </a>
            </div>

          </div>

        @empty
          <p class="text-gray-500 col-span-2 text-center">No activities found.</p>
        @endforelse

      </div>

    </div>
  </section>

  {{-- Footer --}}
  @include('components.ffooter')

</body>
</html>