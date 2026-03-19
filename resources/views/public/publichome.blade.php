<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>San Carlos Tourism</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
  <style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800 overflow-x-hidden">

  @include('components.fnavbar')

  @php
  $heroTagline = $settings['tagline'] ?? '"Soaring Green City of Opportunities. ¡Vamos, San Carlos!"';
  $heroBgImage = $settings['bgImg'][0] ?? 'can-carlos-city-hall.png';
  @endphp

  <section id="hero" class="relative h-screen flex flex-col items-center justify-center text-center text-white overflow-hidden">
    <img src="{{ asset('image/public/image/sestra1.png') }}"
      alt="Tourism Image"
      class="absolute inset-0 w-full h-full object-cover scale-105 animate-[float_12s_ease-in-out_infinite]" />
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>

    <div class="relative z-10 px-6 mt-32">
      <p class="text-lg md:text-xl italic text-gray-200 mb-3 fade-down">
        {!! nl2br(e($heroTagline)) !!}
      </p>
      <div class="w-24 h-[2px] bg-white mx-auto mb-4 fade-down"></div>
      <h1 class="text-5xl md:text-6xl font-extrabold mb-4 tracking-wide fade-left">
        San Carlos City
      </h1>
      <h1 class="text-5xl md:text-6xl font-extrabold mb-8 tracking-wide text-blue-400 fade-right">
        Negros Occidental
      </h1>
      <div class="fade-up">
        <a href="{{ route('activities.index') }}" class="mr-2 md:mr-4 mb-3 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 md:px-8 py-3 rounded-full text-base md:text-lg shadow-lg transform hover:scale-105 transition-all duration-300">
          Activities
        </a>
        <a href="{{ route('exploreplaces') }}" class="mr-2 md:mr-4 mb-3 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 md:px-8 py-3 rounded-full text-base md:text-lg shadow-lg transform hover:scale-105 transition-all duration-300">
          Explore
        </a>
        <a href="#a-events" class="inline-block mb-3 bg-blue-600 hover:bg-blue-700 text-white px-6 md:px-8 py-3 rounded-full text-base md:text-lg shadow-lg transform hover:scale-105 transition-all duration-300">
          Events
        </a>
      </div>
    </div>
  </section>

  <section class="bg-gray-50 py-16 overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 md:px-8 flex flex-col md:flex-row items-center gap-12">
      <div class="md:w-1/2 fade-right">
        @php
        $historyImg = $settings['historyImg'][0] ?? null;
        @endphp
        <img src="{{ $historyImg ? asset('uploads/settings/' . $historyImg) : asset('image/scc_ovw.jpg') }}"
          alt="San Carlos History"
          class="w-full rounded-2xl shadow-lg object-cover transition-transform duration-500 hover:scale-105">
      </div>

      <div class="md:w-1/2 space-y-5 fade-left">
        <h2 class="text-3xl md:text-4xl font-bold text-indigo-900">San Carlos City History</h2>
        <p class="text-gray-700 leading-relaxed text-justify">
          {!! nl2br(e(\Illuminate\Support\Str::limit($settings['historyTxt'] ?? 'No content added yet.', 500))) !!}
        </p>
        <a href="{{ route('historypage') }}" class="inline-block text-blue-600 hover:text-blue-800 font-medium transition">
          Read →
        </a>
      </div>
    </div>
  </section>

  <section class="bg-gray-50 py-16 relative overflow-hidden">
    <div class="max-w-[100vw] mx-auto px-0 relative">
      <h2 class="text-3xl md:text-4xl font-bold text-indigo-900 text-center mb-10 fade-down">
        Popular Places
      </h2>

      <button id="scrollLeft" class="hidden md:flex items-center justify-center absolute left-6 top-1/2 -translate-y-1/2 bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-full shadow-lg transition z-50">
        <i data-lucide="chevron-left" class="w-6 h-6"></i>
      </button>

      <button id="scrollRight" class="hidden md:flex items-center justify-center absolute right-6 top-1/2 -translate-y-1/2 bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-full shadow-lg transition z-50">
        <i data-lucide="chevron-right" class="w-6 h-6"></i>
      </button>

      <div id="placesContainer" class="flex gap-8 overflow-x-auto pb-6 scrollbar-hide snap-x snap-mandatory scroll-smooth px-6 relative z-10 fade-up">
        @foreach ($popularPlaces as $place)
        <div class="relative min-w-[300px] md:min-w-[400px] h-80 rounded-2xl overflow-hidden shadow-lg snap-center group">
          @if(!empty($place->images) && count($place->images) > 0)
          <img src="{{ asset('storage/' . $place->images[0]) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="{{ $place->name }}">
          @endif
          <div class="absolute inset-0 bg-black bg-opacity-10 group-hover:bg-opacity-50 transition duration-500"></div>
          <div class="absolute bottom-4 left-0 right-0 text-center z-10">
            <h3 class="text-white text-lg md:text-xl font-bold mb-2 shadow-sm">{{ $place->name }}</h3>
            <a href="{{ route('exploreplaces.show', $place->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md text-sm opacity-0 group-hover:opacity-100 transition duration-300 transform translate-y-2 group-hover:translate-y-0 inline-block">
              Visit →
            </a>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <section id="a-activity" class="bg-white py-20 overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 md:px-8 flex flex-col md:flex-row items-center gap-12">
      <div class="md:w-1/2 space-y-5 fade-right">
        <h2 class="text-4xl font-bold text-indigo-900">Activities in the City</h2>
        <p class="text-gray-600 leading-relaxed text-justify">
          Experience the vibrant culture and lively activities that make our city truly special.
        </p>
        <a href="{{ route('activities.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition">
          Discover More →
        </a>
      </div>

      <div class="md:w-1/2 relative flex justify-center items-center fade-left">
        <div class="absolute top-8 left-8 w-3/4 md:w-2/3 rounded-2xl overflow-hidden shadow-2xl z-20 transform hover:rotate-2 transition duration-500">
          <img src="{{ asset('image/mayana-peak-is-nearby.jpg') }}" alt="Activity 1" class="w-full h-full object-cover">
        </div>
        <div class="absolute bottom-8 right-8 w-3/4 md:w-2/3 rounded-2xl overflow-hidden shadow-lg z-10 transform hover:-rotate-2 transition duration-500">
          <img src="{{ asset('image/peoples-park1.jpg') }}" alt="Activity 2" class="w-full h-full object-cover">
        </div>
        <div class="invisible w-full aspect-[4/3]"></div>
      </div>
    </div>
  </section>

  <section id="a-events" class="bg-gray-50 text-gray-800 py-16 overflow-hidden">
    <div class="max-w-6xl mx-auto px-4">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 fade-down">
        <div>
          <h2 class="text-4xl font-bold text-indigo-900 mb-3">Events</h2>
          <p class="text-gray-600 leading-relaxed max-w-3xl">
            Don't miss out the great events in San Carlos City.
          </p>
        </div>

        <div class="hidden md:flex gap-3">
          <button id="eventScrollLeft" class="flex items-center justify-center bg-indigo-100 hover:bg-indigo-600 text-indigo-700 hover:text-white w-10 h-10 rounded-full transition duration-300">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
          </button>
          <button id="eventScrollRight" class="flex items-center justify-center bg-indigo-100 hover:bg-indigo-600 text-indigo-700 hover:text-white w-10 h-10 rounded-full transition duration-300">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
          </button>
        </div>
      </div>

      <div id="eventsContainer" class="flex gap-6 overflow-x-auto scrollbar-hide scroll-smooth pb-4 px-1 fade-up">
        @forelse ($events as $event)
        <div class="min-w-[300px] md:min-w-[350px] bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition flex-shrink-0 group">
          <div class="overflow-hidden">
            <img src="{{ ($event->pics && is_array($event->pics) && count($event->pics) > 0) ? asset('storage/'.$event->pics[0]) : asset('image/sample-event.jpg') }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500" alt="{{ $event->events }}">
          </div>
          <div class="p-5">
            <h3 class="text-lg font-semibold text-gray-800 line-clamp-1">{{ $event->events }}</h3>
            <span class="text-sm text-indigo-600 font-medium block mt-1">
              {{ \Carbon\Carbon::parse($event->e_datetime)->format('F d, Y') }}
            </span>
            <div class="mt-5">
              <a href="{{ route('events.show', $event->id) }}" class="inline-block text-sm font-medium bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition">
                More Details →
              </a>
            </div>
          </div>
        </div>
        @empty
        <p class="text-gray-500 w-full text-center py-8">No upcoming events available.</p>
        @endforelse
      </div>
    </div>
  </section>

  <section class="bg-white py-16 overflow-hidden">
    <div class="max-w-3xl mx-auto px-4 flex flex-col items-start gap-6 fade-up">
      <h2 class="text-3xl md:text-4xl font-bold text-indigo-900">About Us</h2>
      <p class="text-gray-700 leading-relaxed text-justify">
        {!! nl2br(e($settings['aboutUs'] ?? 'At SCC Tourism, we are dedicated to promoting adventure.')) !!}
      </p>
      <a href="{{ route('aboutuspage') }}" class="text-blue-600 hover:text-blue-800 font-medium transition">
        Read More →
      </a>
    </div>
  </section>

  <a href="#hero" id="backToTop" class="fixed bottom-6 right-6 z-50 hidden bg-blue-600 hover:bg-blue-700 text-white w-16 h-16 text-xl rounded-full shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110">
    <i data-lucide="arrow-up" class="w-6 h-6"></i>
  </a>

  @include('components.ffooter')

  <script>
    const scrollContainer = document.getElementById("placesContainer");
    const scrollLeft = document.getElementById("scrollLeft");
    const scrollRight = document.getElementById("scrollRight");

    if(scrollLeft && scrollContainer) {
      scrollLeft.addEventListener("click", () => { scrollContainer.scrollBy({ left: -400, behavior: "smooth" }); });
      scrollRight.addEventListener("click", () => { scrollContainer.scrollBy({ left: 400, behavior: "smooth" }); });
    }

    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
      if (window.scrollY > window.innerHeight * 0.6) { backToTop.classList.remove('hidden'); } 
      else { backToTop.classList.add('hidden'); }
    });

    const eventsContainer = document.getElementById("eventsContainer");
    const eventScrollLeft = document.getElementById("eventScrollLeft");
    const eventScrollRight = document.getElementById("eventScrollRight");

    if (eventScrollLeft && eventsContainer) {
      eventScrollLeft.addEventListener("click", () => { eventsContainer.scrollBy({ left: -350, behavior: "smooth" }); });
      eventScrollRight.addEventListener("click", () => { eventsContainer.scrollBy({ left: 350, behavior: "smooth" }); });
    }
  </script>

</body>
</html>
