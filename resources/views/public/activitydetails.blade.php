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

  <section class="bg-white">

    {{-- Activity Image --}}
    <div class="w-full h-96 md:h-[500px] overflow-hidden">
      <img src="{{ $activity->img0 ? asset($activity->img0) : asset('image/sample-activity.jpg') }}"
           alt="{{ $activity->a_name }}"
           class="w-full h-full object-cover">
    </div>

    {{-- Activity Title --}}
    <div class="max-w-6xl mx-auto px-4 mt-8 text-center md:text-left">
      <h1 class="text-4xl md:text-5xl font-extrabold text-black uppercase tracking-tight">
        {{ strtoupper($activity->a_name) }}
      </h1>
    </div>

    {{-- Activity Description --}}
    <div class="max-w-6xl mx-auto px-4 mt-4 text-gray-700 text-lg leading-relaxed text-justify">
      <p>{{ $activity->a_info }}</p>
    </div>
    {{-- Places to Visit Section --}}
<div class="max-w-6xl mx-auto px-4 mt-12">

  {{-- Section Title --}}
  <div class="text-center mb-10">
    <h2 class="text-2xl md:text-3xl font-bold text-black uppercase tracking-wide">
      PLACES TO VISIT
    </h2>
  </div>

  {{-- Categories and Places --}}
  <div class="space-y-12">

    @foreach ($activity->categories as $category)
      {{-- Category Name --}}
      <div class="mb-6">
        <h3 class="text-xl md:text-2xl font-bold text-indigo-900">
          {{ $category->category }}
        </h3>
      </div>

      @forelse ($category->places as $place)
        <div class="border-b border-gray-200 pb-6">

          {{-- Place Name --}}
          <h4 class="text-lg md:text-xl font-bold text-black mb-3">
            {{ $place->name }}
          </h4>

          <div class="flex flex-col md:flex-row gap-6 items-start">

            {{-- Image on Left --}}
            <div class="md:w-1/3 w-full overflow-hidden shadow-sm">
              @if(isset($place->images[0]))
                <img src="{{ asset('storage/' . $place->images[0]) }}"
                     alt="{{ $place->name }}"
                     class="w-full h-48 object-cover transition-transform duration-300 hover:scale-105">
              @else
                <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                  No Image
                </div>
              @endif
            </div>

            {{-- Description + Button on Right --}}
            <div class="md:w-2/3 w-full flex flex-col justify-between">
              <p class="text-gray-700 text-sm leading-relaxed mb-4">
                {{ \Illuminate\Support\Str::limit($place->description, 700) }}
              </p>
              <a href="{{ route('exploreplaces.show', $place->id) }}"
                 class="inline-block bg-black text-white text-sm px-5 py-2 rounded-md hover:bg-gray-900 transition w-max">
                READ MORE
              </a>
            </div>

          </div>
        </div>
      @empty
        <p class="text-gray-400 text-sm">No places under this category.</p>
      @endforelse

    @endforeach

  </div>
</div>

    {{-- Back Button --}}
    <div class="max-w-6xl mx-auto px-4 mt-12">
      <a href="{{ route('activities.index') }}"
         class="inline-block bg-black text-white px-6 py-2 rounded-md hover:bg-gray-900 transition shadow">
        ← Back to Activities
      </a>
    </div>

  </section>

  {{-- Footer --}}
  @include('components.ffooter')

</body>
</html>