<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $activity->a_name }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

  {{-- Navbar --}}
  @include('components.fnavbar')
  <div class="mb-12"></div>

  <section class="bg-white py-20">
    <div class="max-w-6xl mx-auto px-4">

      {{-- Activity Header --}}
      <div class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-indigo-900">{{ $activity->a_name }}</h1>
      </div>

      {{-- Activity Content --}}
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-6 md:p-10">
        
        {{-- Image --}}
        <img src="{{ $activity->img0 ? asset($activity->img0) : asset('image/sample-activity.jpg') }}" alt="{{ $activity->a_name }}" class="w-full h-80 object-cover rounded-xl mb-6">
          
        {{-- Info --}}
        <p class="text-gray-700 leading-relaxed mb-6">{{ $activity->a_info }}</p>
        <p class="text-xl font-semibold text-blue-800 mb-4">Places to Visit</p>

@foreach ($activity->categories as $category)

    <!-- Category Title -->
    <h3 class="text-lg font-bold text-indigo-700 mt-6 mb-3">
        {{ $category->category }}
    </h3>

    @forelse ($category->places as $place)

        <div class="border-t border-gray-300 py-6">

            <!-- Place Name -->
            <h4 class="text-lg font-semibold text-gray-900 mb-3">
                {{ $place->name }}
            </h4>

            <!-- Image + Description -->
            <div class="flex flex-col md:flex-row gap-6">

                <!-- Image -->
                <div class="md:w-1/3">
                     @if(isset($place->images[0]))
                      <img src="{{ asset('storage/' . $place->images[0]) }}"
                          alt="{{ $place->name }}"
                          class="w-full h-48 object-cover rounded-lg shadow"
                      >
                  @else
                      <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                          No Image
                      </div>
                  @endif
                </div>

                <!-- Description -->
                  <div class="md:w-2/3 text-gray-700 text-sm leading-relaxed">

                      <p>
                          {{ \Illuminate\Support\Str::limit($place->description, 700) }}
                      </p>

                      <a href="{{ route('exploreplaces.show', $place->id) }}"
                        class="block mt-3 text-black font-medium hover:underline">
                          Read more
                      </a>

                  </div>
            </div>



        </div>

    @empty
        <p class="text-gray-400 text-sm">No places under this category.</p>
    @endforelse

@endforeach

        {{-- Back Button --}}
        <a href="{{ route('activities.index') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
          ← Back to Activities
        </a>
      </div>

    </div>
  </section>

  {{-- Footer --}}
  @include('components.ffooter')

</body>
</html>
