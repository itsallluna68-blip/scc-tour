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
  <section id="hero" class="relative h-screen flex flex-col items-center justify-center text-center text-white overflow-hidden">
    <img src="{{ asset('image/can-carlos-city-hall.png') }}" 
         alt="Tourism Image"
         class="absolute inset-0 w-full h-full object-cover scale-105 animate-[float_12s_ease-in-out_infinite]" />
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/70"></div>

    <div class="relative z-10 px-6 fade-up mt-32">
      <p class="text-lg md:text-xl italic text-gray-200 mb-3 animate-fadeIn delay-100">
        "Soaring Green City of Opportunities. ¡Vamos, San Carlos!"
      </p>
      <div class="w-24 h-[2px] bg-white mx-auto mb-4"></div>
      <h1 class="text-5xl md:text-6xl font-extrabold mb-4 tracking-wide animate-fadeIn">
        San Carlos City
      </h1>
      <h1 class="text-5xl md:text-6xl font-extrabold mb-8 tracking-wide text-blue-400 animate-slideUp delay-100">
        Negros Occidental
      </h1>
      {{-- buttons --}}
      <a href="{{ route('activities.index') }}"  class="mr-4 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full text-lg shadow-lg transform hover:scale-105 transition-all duration-300">
        Activities
      </a>
      <a href="{{ route('exploreplaces') }}" class="mr-4 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full text-lg shadow-lg transform hover:scale-105 transition-all duration-300">
        Explore
      </a>
      <a href="#a-events" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full text-lg shadow-lg transform hover:scale-105 transition-all duration-300">
        Events
      </a>
    </div>
  </section>

  <!-- 🏛️ HISTORY SECTION -->
<section class="bg-gray-50 py-16">
  <div class="max-w-6xl mx-auto px-4 md:px-8 flex flex-col md:flex-row items-center gap-12">
    
    <!-- Image (left side) -->
    <div class="md:w-1/2">
      @php
          $historyImg = $settings['historyImg'][0] ?? null;
      @endphp
      <img src="{{ $historyImg ? asset('uploads/settings/' . $historyImg) : asset('image/scc_ovw.jpg') }}" 
           alt="San Carlos History" 
           class="w-full  rounded-2xl shadow-lg object-cover transition-transform duration-500">
    </div>

    <!-- Text (right side) -->
<div class="md:w-1/2 space-y-5">
  <h2 class="text-3xl md:text-4xl font-bold text-indigo-900">San Carlos City History</h2>
  
<p class="text-gray-700 leading-relaxed text-justify">
    {!! nl2br(e(\Illuminate\Support\Str::limit($settings['historyTxt'] ?? 'No content added yet.', 500))) !!}
</p>
  
  <a href="{{ route('historypage') }}" 
     class="inline-block text-blue-600 hover:text-blue-800 font-medium transition">
    Read →
  </a>
</div>

  </div>
</section>


<!-- 🌄 POPULAR PLACES (Horizontal Scroll Section) -->
<section class="bg-gray-50 py-16 relative overflow-hidden">
  <div class="max-w-[100vw] mx-auto px-0 relative">

    <!-- Section Title -->
    <h2 class="text-3xl md:text-4xl font-bold text-indigo-900 text-center mb-10">
      Popular Places
    </h2>

    <!-- Scroll Buttons -->
    <button
      id="scrollLeft"
      class="hidden md:flex items-center justify-center absolute left-6 top-1/2 -translate-y-1/2
             bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-full shadow-lg transition z-50"
    >
      <svg xmlns="http://www.w3.org/2000/svg"
           class="h-6 w-6"
           fill="none"
           viewBox="0 0 24 24"
           stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>

    <button
      id="scrollRight"
      class="hidden md:flex items-center justify-center absolute right-6 top-1/2 -translate-y-1/2
             bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-full shadow-lg transition z-50"
    >
      <svg xmlns="http://www.w3.org/2000/svg"
           class="h-6 w-6"
           fill="none"
           viewBox="0 0 24 24"
           stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </button>

    <!-- Horizontal Scroll Container -->
    <div
      id="placesContainer"
      class="flex gap-8 overflow-x-auto pb-6 scrollbar-hide snap-x snap-mandatory scroll-smooth px-6 relative z-10"
    >
      <!-- Card Template -->
      @foreach ($popularPlaces as $place)

      <div class="relative min-w-[300px] md:min-w-[400px] h-80 rounded-2xl overflow-hidden shadow-lg snap-center group">
          
          @if(!empty($place->images) && count($place->images) > 0)
              <img 
                  src="{{ asset('storage/' . $place->images[0]) }}" 
                  class="absolute inset-0 w-full h-full object-cover"
                  alt="{{ $place->name }}"
              >
          @endif

          <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition duration-500"></div>

          <div class="absolute bottom-4 left-0 right-0 text-center z-10">
              <h3 class="text-white text-lg font-semibold mb-2">
                  {{ $place->name }}
              </h3>
              
              <a href="{{ route('exploreplaces.show', $place->id) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md text-sm opacity-0
                      group-hover:opacity-100 transition duration-300">
                Visit →
              </a>
          </div>

      </div>

      @endforeach

    </div>
  </div>

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



  <!-- 🎭 ACTIVITIES -->
  <section id="a-activity" class="bg-white py-20 ">
    <div class="max-w-6xl mx-auto px-4 md:px-8 flex flex-col md:flex-row items-center gap-12">
      <div class="md:w-1/2 space-y-5">
        <h2 class="text-4xl font-bold text-indigo-900">Activities in the City</h2>
        <p class="text-gray-600 leading-relaxed text-justify">
          Experience the vibrant culture and lively activities that make our city truly special. 
          From breathtaking nature trails to colorful festivals, every corner offers something 
          exciting to explore.
        </p>
        <a href="{{ route('activities.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition">
          Discover More →
        </a>
      </div>

      <!-- Floating Image Stack -->
      <div class="md:w-1/2 relative flex justify-center items-center">
        <div class="absolute top-8 left-8 w-3/4 md:w-2/3 rounded-2xl overflow-hidden shadow-2xl z-20 transform hover:rotate-2 transition">
          <img src="{{ asset('image/mayana-peak-is-nearby.jpg') }}" alt="Activity 1" class="w-full h-full object-cover">
        </div>
        <div class="absolute bottom-8 right-8 w-3/4 md:w-2/3 rounded-2xl overflow-hidden shadow-lg z-10 transform hover:-rotate-2 transition">
          <img src="{{ asset('image/peoples-park1.jpg') }}" alt="Activity 2" class="w-full h-full object-cover">
        </div>
        <div class="invisible w-full aspect-[4/3]"></div>
      </div>
    </div>
  </section>

 <section id="a-events" class="bg-white text-gray-800 py-16">
  <div class="max-w-6xl mx-auto px-4">

    <!-- Section Header -->
    <div class="mb-10">
      <h2 class="text-4xl font-bold text-indigo-900 mb-3">
        Events
      </h2>
      <p class="text-gray-600 leading-relaxed max-w-3xl">
        Don't miss out the great events in San Carlos City.
      </p>
    </div>

    <!-- Events Grid -->
    <!-- Horizontal Scroll Wrapper -->
<div class="relative">

    <!-- Scroll Left Button -->
    <button
        id="eventScrollLeft"
        class="hidden md:flex items-center justify-center absolute left-0 top-1/2 -translate-y-1/2
               bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-full shadow-lg z-20">
        ←
    </button>

    <!-- Scroll Right Button -->
    <button
        id="eventScrollRight"
        class="hidden md:flex items-center justify-center absolute right-0 top-1/2 -translate-y-1/2
               bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-full shadow-lg z-20">
        →
    </button>

    <!-- Scroll Container -->
    <div id="eventsContainer"
         class="flex gap-6 overflow-x-auto scrollbar-hide scroll-smooth pb-4 px-2">

        @forelse ($events as $event)
            <div class="min-w-[300px] md:min-w-[350px] bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition flex-shrink-0">
            <img
                src="{{ ($event->pics && is_array($event->pics) && count($event->pics) > 0) ? asset('storage/'.$event->pics[0]) : asset('image/sample-event.jpg') }}"
                class="w-full h-48 object-cover"
                alt="{{ $event->events }}">
            <div class="p-5">
                <h3 class="text-lg font-semibold text-gray-800">{{ $event->events }}</h3>
                <span class="text-sm text-indigo-600 block mt-1">
                    {{ \Carbon\Carbon::parse($event->e_datetime)->format('F d, Y') }}
                </span>
                <div class="mt-4">
                    <a href="{{ route('events.show', $event->id) }}" class="inline-block text-sm bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                        More Details →
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
<section class="bg-white py-16">
  <div class="max-w-3xl mx-auto px-4 flex flex-col items-start gap-6">

    <!-- Heading -->
    <h2 class="text-3xl md:text-4xl font-bold text-indigo-900">
      About Us
    </h2>
    
    <!-- Description -->
    <p class="text-gray-700 leading-relaxed text-justify">
      {!! nl2br(e($settings['aboutUs'] ?? 'At SCC Tourism, we are dedicated to promoting the hidden gems and cultural beauty of our community. Our mission is to inspire travelers and locals alike to explore, experience, and embrace the spirit of adventure that our destinations have to offer.')) !!}
    </p>
    
    <!-- Read More Button -->
    <a href="{{ route('aboutuspage') }}" class="text-blue-600 hover:text-blue-800 font-medium transition">
      Read More →
    </a>

  </div>
</section>




  <!-- Back to Top Button -->
<a href="#hero"
   id="backToTop"
   class="fixed bottom-6 right-6 z-50 hidden
          bg-blue-600 hover:bg-blue-700 text-white
          w-16 h-16 text-xl rounded-full shadow-xl
          flex items-center justify-center
          transition-all duration-300
          hover:scale-110">
  ↑
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
