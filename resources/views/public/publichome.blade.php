<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>San Carlos Tourism</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Hide scrollbar */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* Fade/slide animation */
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

    {{-- fnavbar --}}
    @include('components.fnavbar')


  <!-- 🌄 HERO SECTION -->
  @php
      $heroTagline = $settings['tagline'] ?? '"Soaring Green City of Opportunities. ¡Vamos, San Carlos!"';
      $heroBgImage = $settings['bgImg'][0] ?? 'can-carlos-city-hall.png';
  @endphp
<section id="hero" class="relative h-[95vh] flex items-start justify-center text-white overflow-hidden pt-40 md:pt-52">

<img src="{{ asset('image/can-carlos-city-hall.png') }}" 
       alt="Tourism Image"
       class="absolute inset-0 w-full h-full object-cover scale-105" />

  <div class="absolute inset-0 
              bg-gradient-to-b 
              from-black/40 
              via-black/50 
              to-black/80">
  </div>
  <div class="relative z-10 max-w-6xl mx-auto px-6 text-center">

    <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight uppercase">
      SAN CARLOS <span class="text-blue-400">TOURISM</span>
    </h1>

    <p class="text-sm md:text-base tracking-widest uppercase 
              text-gray-200 mt-2 font-light">
      Negros Occidental
    </p>

    <div class="w-16 h-[2px] bg-white/70 mx-auto my-6"></div>

    <p class="text-lg md:text-xl italic text-gray-100 
              max-w-2xl mx-auto font-light">
      {!! nl2br(e($heroTagline)) !!}
    </p>

    <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">

      <a href="{{ route('activities.index') }}"
         class="group bg-white/10 backdrop-blur-lg 
                border border-white/10
                rounded-2xl p-8
                flex flex-col items-center justify-center text-center
                shadow-xl
                transition duration-300
                hover:bg-white/15
                hover:border-red-400/40
                hover:scale-105">

        <svg class="hidden md:block h-10 w-10 mb-4 text-white transition group-hover:text-red-400"
     xmlns="http://www.w3.org/2000/svg"
     fill="none"
     viewBox="0 0 24 24"
     stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.868v4.264a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
        </svg>

        <span class="font-semibold text-lg tracking-wide">
          Activities
        </span>
      </a>

      <a href="{{ route('exploreplaces') }}"
         class="group bg-white/10 backdrop-blur-lg 
                border border-white/10
                rounded-2xl p-8
                flex flex-col items-center justify-center text-center
                shadow-xl
                transition duration-300
                hover:bg-white/15
                hover:border-blue-400/40
                hover:scale-105">

<svg class="hidden md:block h-10 w-10 mb-4 text-white transition group-hover:text-blue-400"
     xmlns="http://www.w3.org/2000/svg"
     fill="none"
     viewBox="0 0 24 24"
     stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M9 20l-5.447-2.724A2 2 0 013 15.382V6.618a2 2 011.553-1.894L9 2m0 18l6-2m-6 2V2m6 16l5.447-2.724A2 2 0 0021 15.382V6.618a2 2 0 00-1.553-1.894L15 2m0 16V2" />
        </svg>

        <span class="font-semibold text-lg tracking-wide">
          Explore
        </span>
      </a>

      <a href="#a-events"
         class="group bg-white/10 backdrop-blur-lg 
                border border-white/10
                rounded-2xl p-8
                flex flex-col items-center justify-center text-center
                shadow-xl
                transition duration-300
                hover:bg-white/15
                hover:border-yellow-400/50
                hover:scale-105">

<svg class="hidden md:block h-10 w-10 mb-4 text-white transition group-hover:text-yellow-400"
     xmlns="http://www.w3.org/2000/svg"
     fill="none"
     viewBox="0 0 24 24"
     stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M8 7V3m8 4V3m-9 8h10m-11 8h12a2 2 002-2V7a2 2 00-2-2H5a2 2 00-2 2v10a2 2 002 2z" />
        </svg>

        <span class="font-semibold text-lg tracking-wide">
          Events
        </span>
      </a>

    </div>
  </div>
</section>

  <!-- 🏛️ HISTORY SECTION -->
<section class="bg-gray-50 py-20">

  <!-- Small Divider -->
  <div class="max-w-7xl mx-auto px-6">
    <div class="w-full h-px bg-gray-300 mb-16"></div>
  </div>

  <div class="max-w-7xl mx-auto px-6 md:px-10 
              flex flex-col md:flex-row items-center gap-16">

    <!-- Image (Left) -->
    <div class="md:w-1/2">
      @php
          $historyImg = $settings['historyImg'][0] ?? null;
      @endphp
      <img src="{{ $historyImg ? asset('uploads/settings/' . $historyImg) : asset('image/scc_ovw.jpg') }}" 
           alt="San Carlos History" 
           class="w-full h-[400px] object-cover shadow-xl">
    </div>

    <!-- Text (Right) -->
    <div class="md:w-1/2 space-y-6">

      <h2 class="text-4xl md:text-5xl font-bold text-gray-900 uppercase tracking-tight">
        The History of San Carlos
      </h2>

      <div class="w-16 h-[2px] bg-blue-500"></div>

      <p class="text-gray-700 leading-relaxed text-justify text-lg">
        {!! nl2br(e(\Illuminate\Support\Str::limit($settings['historyTxt'] ?? 'No content added yet.', 500))) !!}
      </p>

      <a href="{{ route('historypage') }}" 
         class="inline-block font-bold text-black hover:opacity-70 transition">
        READ MORE
      </a>

    </div>

  </div>
</section>

<!-- 🌄 POPULAR PLACES (Bento Editorial Style, Refined) -->
<section class="bg-gray-50 py-20 relative overflow-hidden">

<!-- Divider -->
<div class="max-w-7xl mx-auto px-4">
  <div class="w-full h-px bg-gray-300 mb-16"></div>
</div>

<div class="max-w-7xl mx-auto px-4 relative">

    <!-- Header Row -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10">

      <!-- Title + Description (Left Aligned) -->
      <div>
        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 uppercase tracking-tight">
          Popular Places
        </h2>
        <p class="text-gray-600 mt-3 text-lg">
          Here are some recommended places to visit.
        </p>
      </div>

      <!-- Scroll Buttons (Top Left Style, Side by Side) -->
      <div class="flex gap-4 mt-6 md:mt-0">

        <button
          id="scrollLeft"
          class="bg-blue-600 hover:bg-blue-700 text-white 
                 w-12 h-12 rounded-full flex items-center justify-center 
                 shadow-lg transition">
          <
        </button>

        <button
          id="scrollRight"
          class="bg-blue-600 hover:bg-blue-700 text-white 
                 w-12 h-12 rounded-full flex items-center justify-center 
                 shadow-lg transition">
          >
        </button>

      </div>
    </div>

    <!-- Horizontal Scroll Container -->
    <div
      id="placesContainer"
      class="flex gap-6 overflow-x-auto pb-6 scrollbar-hide scroll-smooth"
    >

      @foreach ($popularPlaces as $place)

      <a href="{{ route('exploreplaces.show', $place->id) }}"
         class="relative min-w-[300px] md:min-w-[450px] h-[450px] 
                overflow-hidden group shadow-xl flex-shrink-0">

          @if(!empty($place->images) && count($place->images) > 0)
              <img 
                  src="{{ asset('storage/' . $place->images[0]) }}" 
                  class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:blur-[2px]"
                  alt="{{ $place->name }}">
          @endif

          <!-- Hover Dim -->
          <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition duration-500"></div>

          <!-- Centered Name on Hover -->
          <div class="absolute inset-0 flex items-center justify-center">
              <h3 class="text-white text-2xl md:text-3xl font-bold 
                         opacity-0 group-hover:opacity-100 
                         transition duration-500 
                         transform group-hover:scale-110 text-center px-4">
                  {{ $place->name }}
              </h3>
          </div>

          <!-- Bottom Full Width Details Bar (Hover Only) -->
          <div class="absolute bottom-0 left-0 right-0 
                      bg-black/75  text-white text-center 
                      py-4 font-semibold tracking-wide 
                      uppercase text-sm
                      opacity-0 group-hover:opacity-100
                      transition duration-500">
              Details
          </div>

      </a>

      @endforeach

    </div>

  </div>
</section>

  <!-- Scroll Script -->
  <script>
    const scrollContainer = document.getElementById("placesContainer");
    const scrollLeft = document.getElementById("scrollLeft");
    const scrollRight = document.getElementById("scrollRight");

    // Scroll left
    scrollLeft.addEventListener("click", () => {
      scrollContainer.scrollBy({ left: -400, behavior: "smooth" });
    });

    // Scroll right
    scrollRight.addEventListener("click", () => {
      scrollContainer.scrollBy({ left: 400, behavior: "smooth" });
    });
  </script>
</section>


{{-- activitions --}}
<section id="a-activity" class="bg-white pt-24 pb-12">

<!-- Divider -->
<div class="max-w-7xl mx-auto px-4">
  <div class="w-full h-px bg-gray-300 mb-16"></div>
</div>

  <div class="max-w-7xl mx-auto px-4 md:px-6 
              flex flex-col md:flex-row items-center gap-20">

    <!-- Left Side Text -->
    <div class="md:w-1/2 space-y-6">

      <h2 class="text-4xl md:text-5xl font-bold text-gray-900 uppercase tracking-tight">
        Activities in the City
      </h2>

      <div class="w-16 h-[2px] bg-blue-500"></div>

      <p class="text-gray-600 leading-relaxed text-justify text-lg">
        Experience the vibrant culture and lively activities that make our city truly special. 
        From breathtaking nature trails to colorful festivals, every corner offers something 
        exciting to explore.
      </p>

      <a href="{{ route('activities.index') }}" 
        class="inline-block bg-black hover:bg-gray-900 text-white 
                px-8 py-3 
                rounded-md
                shadow-md 
                transition duration-300">
        Discover More
      </a>

    </div>

    <!-- Right Side Images (Bigger) -->
    <div class="hidden md:flex md:w-1/2 relative justify-center items-center">

      <!-- Top Image -->
      <div class="absolute top-10 left-10 
                  w-[85%] md:w-[80%] 
                  h-[420px] 
                  overflow-hidden shadow-2xl 
                  transform hover:rotate-1 transition duration-500">

        <img src="{{ asset('image/mayana-peak-is-nearby.jpg') }}" 
             alt="Activity 1" 
             class="w-full h-full object-cover">
      </div>

      <!-- Bottom Image -->
      <div class="absolute bottom-10 right-10 
                  w-[85%] md:w-[80%] 
                  h-[420px] 
                  overflow-hidden shadow-xl 
                  transform hover:-rotate-1 transition duration-500">

        <img src="{{ asset('image/peoples-park1.jpg') }}" 
             alt="Activity 2" 
             class="w-full h-full object-cover">
      </div>

      <!-- Spacer -->
      <div class="invisible w-full h-[500px]"></div>

    </div>

  </div>
</section>

 <!-- 🎭 EVENTS (Editorial Bento Style) -->
<section id="a-events" class="bg-white pt-12 pb-24">

<!-- Divider -->
<div class="max-w-7xl mx-auto px-4">
  <div class="w-full h-px bg-gray-300 mb-16"></div>
</div>

  <div class="max-w-7xl mx-auto px-4 md:px-6">

    <!-- Header -->
    <div class="flex justify-between items-start mb-12">

  <!-- Left: Title -->
  <div>
    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 uppercase tracking-tight">
      Events
    </h2>
    <p class="text-gray-600 mt-3 text-lg">
      Upcoming celebrations and activities in the city.
    </p>
  </div>

  <!-- Right: Scroll Buttons -->
  <div class="flex gap-4">
    <button
      id="eventScrollLeft"
      class="bg-blue-600 hover:bg-blue-700 text-white 
                 w-12 h-12 rounded-full flex items-center justify-center 
                 shadow-lg transition">
          <
        </button>

    <button
      id="eventScrollRight"
      class="bg-blue-600 hover:bg-blue-700 text-white 
                 w-12 h-12 rounded-full flex items-center justify-center 
                 shadow-lg transition">
          >
        </button>
  </div>

</div>

      <!-- Events Container -->
      <div id="eventsContainer"
           class="flex gap-8 overflow-x-auto scrollbar-hide scroll-smooth pb-6">

        @forelse ($events as $event)

        <div class="min-w-[300px] md:min-w-[380px] 
                    bg-white border border-gray-200 
                    shadow-lg 
                    overflow-hidden flex-shrink-0">

          <!-- Image -->
          <img
            src="{{ ($event->pics && is_array($event->pics) && count($event->pics) > 0) ? asset('storage/'.$event->pics[0]) : asset('image/sample-event.jpg') }}"
            class="w-full h-56 object-cover"
            alt="{{ $event->events }}">

          <!-- Content -->
          <div class="p-6 space-y-3">

            <h3 class="text-xl font-semibold text-gray-900">
              {{ $event->events }}
            </h3>

            <!-- Date -->
            <span class="block text-blue-800 font-bold text-sm">
              {{ \Carbon\Carbon::parse($event->e_datetime)->format('F d, Y') }}
            </span>

            <!-- Button -->
            <div class="pt-4">
              <a href="{{ route('events.show', $event->id) }}"
                 class="inline-block bg-black hover:bg-gray-900 text-white 
                        px-6 py-2 
                        rounded-md
                        transition duration-300">
                More Details
              </a>
            </div>

          </div>

        </div>

        @empty
          <p class="text-gray-500">No upcoming events available.</p>
        @endforelse

      </div>

    </div>

  </div>
</section>

<!-- 🌍 ABOUT -->
<section class="bg-white py-20">
  <div class="max-w-6xl mx-auto px-4 md:px-6">

    <!-- Centered Title -->
    <div class="text-center mb-14">
      <h2 class="text-4xl md:text-5xl font-bold text-black-900 uppercase tracking-tight">
        About Us
      </h2>
    </div>

      <!-- Description -->
      <div>
        <p class="text-gray-700 text-lg leading-relaxed text-justify">
          {!! nl2br(e($settings['aboutUs'] ?? 'At SCC Tourism, we are dedicated to promoting the hidden gems and cultural beauty of our community. Our mission is to inspire travelers and locals alike to explore, experience, and embrace the spirit of adventure that our destinations have to offer.')) !!}
        </p>
      </div>

  </div>
</section>

{{-- back to top button --}}
 <a href="#hero"
   id="backToTop"
   class="fixed bottom-6 right-6 z-50 hidden
          backdrop-blur-md bg-blue-600/70 hover:bg-blue-700/80
          text-white
          w-14 h-14
          rounded-2xl shadow-xl
          flex items-center justify-center
          transition-all duration-300
          hover:scale-110 hover:shadow-2xl">
  <svg xmlns="http://www.w3.org/2000/svg" 
       fill="none" 
       viewBox="0 0 24 24" 
       stroke-width="2" 
       stroke="currentColor" 
       class="w-6 h-6">
    <path stroke-linecap="round" 
          stroke-linejoin="round" 
          d="M5 15l7-7 7 7" />
  </svg>

</a>

  {{-- ffooter --}}
  @include('components.ffooter')

  <!-- ✨ Scroll Animation Script -->
  <script>
    const fadeEls = document.querySelectorAll('.fade-up');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('show');
      });
    }, { threshold: 0.05 });
    fadeEls.forEach(el => observer.observe(el));

    // back to top
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
      if (window.scrollY > window.innerHeight * 0.6) {
        backToTop.classList.remove('hidden');
      } else {
        backToTop.classList.add('hidden');
      }
    });

    // EVENTS SCROLLER
    const eventsContainer = document.getElementById("eventsContainer");
    const eventScrollLeft = document.getElementById("eventScrollLeft");
    const eventScrollRight = document.getElementById("eventScrollRight");

    eventScrollLeft.addEventListener("click", () => {
        eventsContainer.scrollBy({ left: -350, behavior: "smooth" });
    });

    eventScrollRight.addEventListener("click", () => {
        eventsContainer.scrollBy({ left: 350, behavior: "smooth" });
    });

  </script>

  <!-- Track landing page visit (POST with CSRF and 24h localStorage dedupe) -->
  <script>
    (function(){
      try{
        const key = 'scctrack_visited_v1';
        const ttl = 24 * 60 * 60 * 1000; // 24 hours
        const last = parseInt(localStorage.getItem(key) || '0', 10);
        const now = Date.now();
        if (last && (now - last) < ttl) return; // recently tracked

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!token) return;

        fetch("{{ route('track.visit') }}", {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
          },
          body: JSON.stringify({ source: 'landing' })
        }).then(res => {
          if (res.ok) localStorage.setItem(key, now.toString());
        }).catch(()=>{});
      }catch(e){ }
    })();
  </script>

</body>
</html>
