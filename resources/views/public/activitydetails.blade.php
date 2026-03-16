<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>{{ $activity->a_name }}</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>

<body class="bg-gray-50 text-gray-800">

  @include('components.fnavbar')
  <div class="mb-12"></div>

  <section class="bg-white py-12 md:py-20">
    <div class="max-w-6xl mx-auto px-4">

      <div class="mb-8 md:mb-12 text-center">
        <h1 class="text-3xl md:text-5xl font-bold text-indigo-900">{{ $activity->a_name }}</h1>
      </div>

      <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-5 md:p-10">

        <img src="{{ $activity->img0 ? asset($activity->img0) : asset('image/sample-activity.jpg') }}" alt="{{ $activity->a_name }}" class="w-full h-56 md:h-80 object-cover rounded-xl mb-6">

        <p class="text-gray-700 leading-relaxed mb-6 text-justify md:text-left">{{ $activity->a_info }}</p>
        <p class="text-xl font-semibold text-blue-800 mb-4">Places to Visit</p>

        @foreach ($activity->categories as $category)

        <h3 class="text-lg font-bold text-indigo-700 mt-6 mb-3">
          {{ $category->category }}
        </h3>

        @forelse ($category->places as $place)

        <div class="border-t border-gray-200 py-6">

          <h4 class="text-lg font-semibold text-gray-900 mb-3">
            {{ $place->name }}
          </h4>

          <div class="flex flex-col md:flex-row gap-4 md:gap-6">

            <div class="w-full md:w-1/3 shrink-0">
              @if(isset($place->images[0]))
              <img src="{{ asset('storage/' . $place->images[0]) }}"
                alt="{{ $place->name }}"
                class="w-full h-48 object-cover rounded-lg shadow">
              @else
              <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                No Image
              </div>
              @endif
            </div>

            <div class="w-full md:w-2/3 text-gray-700 text-sm leading-relaxed flex flex-col justify-between">
              <p class="text-justify md:text-left">
                {{ \Illuminate\Support\Str::limit($place->description, 500) }}
              </p>

              <a href="{{ route('exploreplaces.show', $place->id) }}"
                class="inline-block mt-3 text-blue-600 font-medium hover:underline self-start">
                Read more →
              </a>
            </div>
          </div>

        </div>

        @empty
        <p class="text-gray-400 text-sm italic mb-4">No places under this category.</p>
        @endforelse

        @endforeach

        <div class="mt-8 border-t border-gray-200 pt-6">
          <a href="{{ route('activities.index') }}" class="inline-flex items-center justify-center bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition w-full md:w-auto font-medium">
            ← Back to Activities
          </a>
        </div>
      </div>

    </div>
  </section>

  @include('components.ffooter')

</body>

</html>