<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Spots</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- @php use Illuminate\Support\Str; @endphp --}}
</head>
<body class="bg-gray-50 font-sans text-gray-800">

  @php
      $selectedCategories = request('categories', []);
  @endphp

  <style type="text/tailwindcss">
    @layer utilities {
      @keyframes fadeUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
      }
      .animate-fade-up {
        animation: fadeUp 0.8s ease-out both;
      }
    }

    #menu-btn.open span:nth-child(1) {
      transform: rotate(45deg) translate(5px, 5px);
    }
    #menu-btn.open span:nth-child(2) {
      opacity: 0;
    }
    #menu-btn.open span:nth-child(3) {
      transform: rotate(-45deg) translate(5px, -5px);
    }
    #menu-btn span {
      transition: all 0.3s ease;
    }
  </style>

    {{-- fnavbar --}}
    @include('components.fnavbar')
    <div class="mb-12"></div>
    
<section class="bg-white py-16">
  <div class="max-w-7xl mx-auto px-4 flex gap-8">

<!--  LEFT SIDEBAR (Sticky Categories) -->
<div class="w-1/5 min-w-[180px] max-w-[220px] hidden md:block">
  <div class="sticky top-24 h-[calc(100vh-120px)] overflow-y-auto pr-2 border-r">

    {{-- NO FILTERS --}}
    <a href="{{ route('exploreplaces') }}"
       class="category-item block px-4 py-1 mb-2 rounded-lg text-sm bg-gray-100 hover:bg-gray-200 text-gray-700">
      Remove Filters
    </a>

    {{-- CATEGORY LIST --}}
    <h3 class="text-lg font-semibold mb-2 text-gray-800">Categories</h3>
    @foreach ($categories as $category)
      @php
        $isActive = in_array($category->cid, $selectedCategories);
        $newCategories = $isActive
            ? array_diff($selectedCategories, [$category->cid])
            : array_merge($selectedCategories, [$category->cid]);
      @endphp

      <a href="{{ route('exploreplaces', [
              'categories' => $newCategories,
              'search' => request('search')
          ]) }}"
         class="category-item block px-3 py-1 mb-1 rounded-lg text-sm
         {{ $isActive
              ? 'bg-blue-600 text-white'
              : 'hover:bg-blue-100 text-gray-700'
         }}">
        {{ $category->category }}
      </a>
    @endforeach

    {{-- ACTIVITIES --}}
    <h3 class="text-lg font-semibold mt-6 mb-2 text-gray-800">Activities</h3>
    @foreach ($activities as $activity)
      <a href="{{ route('exploreplaces', [
              'categories' => $selectedCategories,
              'activities' => [$activity->aid], // always one
              'search' => request('search')
          ]) }}"
         class="block px-3 py-1 mb-1 rounded-lg text-sm
         {{ (isset($selectedActivities[0]) && $selectedActivities[0] == $activity->aid)
              ? 'bg-green-600 text-white'
              : 'hover:bg-green-100 text-gray-700'
         }}">
        {{ $activity->a_name }}
      </a>
    @endforeach

  </div>
</div>

<!-- MOBILE FILTER CATEGORY -->
<div id="mobileFilter" class="fixed inset-0 z-50 hidden">
  <div onclick="closeFilter()" class="absolute inset-0 bg-black/50"></div>

  <div class="absolute left-0 top-0 h-full w-72 bg-white shadow-xl p-6 overflow-y-auto transform -translate-x-full transition-transform duration-300"
      id="mobileFilterPanel">

    <div class="flex justify-between items-center mb-6">
      <h3 class="text-lg font-semibold">Categories</h3>
      <button onclick="closeFilter()" class="text-gray-500 text-xl">&times;</button>
    </div>

    {{-- NO FILTERS --}}
    <a href="{{ route('exploreplaces') }}"
      class="category-item block px-4 py-2 mb-2 rounded-lg text-sm bg-gray-100 hover:bg-gray-200 text-gray-700">
      Remove Filters
    </a>

    {{-- CATEGORY LIST --}}
    @foreach ($categories as $category)
    @php
        $isActive = in_array($category->cid, $selectedCategories);

        $newCategories = $isActive
            ? array_diff($selectedCategories, [$category->cid])
            : array_merge($selectedCategories, [$category->cid]);
    @endphp

    <a href="#"
      data-id="{{ $category->cid }}"
      data-url="{{ route('exploreplaces', [
            'categories' => $newCategories,
            'search' => request('search')
      ]) }}"
      onclick="filterByCategory(event, this)"
      class="category-item block px-4 py-2 mb-2 rounded-lg text-sm
      {{ $isActive
            ? 'bg-blue-600 text-white'
            : 'hover:bg-blue-100 text-gray-700'
      }}">
      {{ $category->category }}
    </a>
    @endforeach

    <h3 class="text-lg font-semibold mt-6 mb-2 text-gray-800">Activities</h3>

    @foreach ($activities as $activity)
        <a href="{{ route('exploreplaces', [
                'categories' => $selectedCategories,
                'activities' => [$activity->aid],  
                'search' => request('search')
            ]) }}"
          class="block px-3 py-1 mb-1 rounded-lg text-sm
          {{ (isset($selectedActivities[0]) && $selectedActivities[0] == $activity->aid)
                ? 'bg-green-600 text-white'
                : 'hover:bg-green-100 text-gray-700'
          }}">
            {{ $activity->a_name }}
        </a>
    @endforeach

  </div>
</div>



    <!-- GRID-->
    <div class="flex-1">

      <!-- Header -->
      <div class="mb-8 pb-4 border-b border-gray-300">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Explore Places</h2>

  <!-- SEARCH INPUT -->
  <form action="{{ route('exploreplaces') }}" method="GET" class="flex gap-3">
      <input 
        type="text" 
        name="search"
        value="{{ request('search') }}"
        placeholder="Search destinations..." 
        class="border border-gray-300 rounded-lg px-4 py-2 w-48 sm:w-64
               focus:ring-2 focus:ring-blue-400 focus:outline-none transition"
      >

      <button
        type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
        Search
      </button>

      <button type="button"
        onclick="openFilter()"
        class="md:hidden bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" 
         viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" 
              d="M4 6h16M4 12h16M4 18h16" />
    </svg>
    </button>
  </form>

</div>

      <!-- 🌄 GRID -->
<div id="placesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
@foreach ($exploreplaces as $place)
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition flex flex-col overflow-hidden">

        @if(isset($place->main_image))
            <img src="{{ asset('storage/' . $place->main_image) }}"
                alt="{{ $place->name }}"
                class="w-full h-56 object-cover">
        @elseif(isset($place->images[0]))
            {{-- Fallback to first gallery image if no main image --}}
            <img src="{{ asset('storage/' . $place->images[0]) }}"
                alt="{{ $place->name }}"
                class="w-full h-56 object-cover">
        @else
            <img src="{{ asset('image/no-image.png') }}"
                alt="No Image Available"
                class="w-full h-56 object-cover">
        @endif

        <div class="p-5 flex flex-col flex-1">

             <div class="flex items-center gap-2 mb-2">
              <span class="text-yellow-400 text-xl">★</span>
              <span class="text-gray-700 font-semibold">
                  {{ number_format($place->reviews_avg_ratings ?? 0, 1) }}
              </span>
          </div> 

            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                {{ $place->name }}
            </h3>
            
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach ($place->categories as $category)
                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">
                        {{ $category->category }}
                    </span>
                @endforeach
            </div>

            {{-- BUTTON --}}
            <div class="mt-auto flex justify-end">
                <a href="{{ route('exploreplaces.show', $place->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition"> Details </a>
            </div>

        </div>
    </div>
@endforeach
</div>


    </div>

  </div>
</section>


  {{-- ffooter --}}
  @include('components.ffooter')
<script>
    // Redirect browser "Back" to homepage instead of previous exploreplaces state
    window.addEventListener('popstate', function(event) {
        if (window.location.pathname.includes('/exploreplaces')) {
            window.location.href = '/';
        }
    });

    // Open/Close mobile drawer
    function openFilter() {
        document.getElementById('mobileFilter').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('mobileFilterPanel').classList.remove('-translate-x-full');
        }, 10);
    }

    function closeFilter() {
        document.getElementById('mobileFilterPanel').classList.add('-translate-x-full');
        setTimeout(() => {
            document.getElementById('mobileFilter').classList.add('hidden');
        }, 300);
    }

    let selectedCategories = @json($selectedCategories ?? []);

    function filterByCategory(event, element) {
        event.preventDefault();

        const categoryId = element.dataset.id;

        if (categoryId === 'all') {
            selectedCategories = [];
        } else {
            const index = selectedCategories.indexOf(parseInt(categoryId));
            if (index > -1) {
                selectedCategories.splice(index, 1);
            } else {
                selectedCategories.push(parseInt(categoryId));
            }
        }

        const params = new URLSearchParams();
        if (selectedCategories.length) {
            selectedCategories.forEach(id => params.append('categories[]', id));
        }

        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput && searchInput.value.trim() !== '') {
            params.set('search', searchInput.value.trim());
        }

        const url = '{{ route("exploreplaces") }}' + '?' + params.toString();

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newGrid = doc.querySelector('#placesGrid');
                document.querySelector('#placesGrid').innerHTML = newGrid.innerHTML;

                document.querySelectorAll('.category-item').forEach(el => {
                    const id = el.dataset.id;
                    if (id === 'all') {
                        el.classList.toggle('bg-blue-600', selectedCategories.length === 0);
                        el.classList.toggle('text-white', selectedCategories.length === 0);
                    } else {
                        el.classList.toggle('bg-blue-600', selectedCategories.includes(parseInt(id)));
                        el.classList.toggle('text-white', selectedCategories.includes(parseInt(id)));
                    }
                });

                if (document.getElementById('mobileFilter').classList.contains('hidden') === false) {
                    openFilter();
                }

                window.history.pushState({}, '', url);
            })
            .catch(console.error);
    }

    // Category search filter
    function filterCategories(inputId, containerSelector = '.category-item') {
        const input = document.getElementById(inputId);
        const filter = input.value.toLowerCase();
        const items = document.querySelectorAll(containerSelector);

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(filter) ? "block" : "none";
        });
    }

</script>


</body>
</html>