<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Tourist Spots</title>
    @vite(['resources/css/app.css', 'resources/js/public.js'])
    <style>
        #menu-btn.open span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
        #menu-btn.open span:nth-child(2) { opacity: 0; }
        #menu-btn.open span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }
        #menu-btn span { transition: all 0.3s ease; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">
  @php
      $selectedCategories = request('categories', []);
  @endphp

    @include('components.fnavbar')
    <div class="mb-12"></div>
    
<section class="bg-white py-12 md:py-16">
  <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row gap-8">
    
    <div class="w-1/4 min-w-[200px] hidden md:block">
      <div class="sticky top-24 h-[calc(100vh-120px)] overflow-y-auto pr-4 border-r border-gray-200">
        <a href="{{ route('exploreplaces') }}" class="category-item block px-4 py-2 mb-4 rounded-lg text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-center">Clear Filters</a>
        
        <h3 class="text-lg font-bold mb-3 text-gray-900">Categories</h3>
        @foreach ($categories as $category)
          @php
            $isActive = in_array($category->cid, $selectedCategories);
            $newCategories = $isActive ? array_diff($selectedCategories, [$category->cid]) : array_merge($selectedCategories, [$category->cid]);
          @endphp
          <a href="{{ route('exploreplaces', ['categories' => $newCategories, 'search' => request('search')]) }}" 
             class="category-item block px-3 py-1.5 mb-1 rounded-lg text-sm transition {{ $isActive ? 'bg-blue-600 text-white font-medium shadow-sm' : 'hover:bg-blue-50 text-gray-600' }}">
             {{ $category->category }}
          </a>
        @endforeach
      </div>
    </div>

    <div id="mobileFilter" class="fixed inset-0 z-50 hidden">
      <div onclick="closeFilter()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
      <div class="absolute left-0 top-0 h-full w-72 bg-white shadow-2xl p-6 overflow-y-auto transform -translate-x-full transition-transform duration-300" id="mobileFilterPanel">
        <div class="flex justify-between items-center mb-6 border-b pb-3">
          <h3 class="text-xl font-bold text-gray-900">Filters</h3>
          <button onclick="closeFilter()" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
        </div>
        <a href="{{ route('exploreplaces') }}" class="block px-4 py-2 mb-4 rounded-lg text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-center">Clear All Filters</a>
        
        <h3 class="text-lg font-bold mb-3 text-gray-900">Categories</h3>
        @foreach ($categories as $category)
        @php
            $isActive = in_array($category->cid, $selectedCategories);
            $newCategories = $isActive ? array_diff($selectedCategories, [$category->cid]) : array_merge($selectedCategories, [$category->cid]);
        @endphp
        <a href="#" data-id="{{ $category->cid }}" data-url="{{ route('exploreplaces', ['categories' => $newCategories, 'search' => request('search')]) }}" onclick="filterByCategory(event, this)" 
           class="category-item block px-4 py-2 mb-1.5 rounded-lg text-sm transition {{ $isActive ? 'bg-blue-600 text-white font-medium shadow-sm' : 'bg-gray-50 hover:bg-blue-50 text-gray-700' }}">
           {{ $category->category }}
        </a>
        @endforeach
      </div>
    </div>

    <div class="flex-1 w-full">
      <div class="mb-8 pb-6 border-b border-gray-200">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Explore Places</h2>
        <form action="{{ route('exploreplaces') }}" method="GET" class="flex flex-wrap gap-3 w-full">
            
            <label for="searchInput" class="sr-only">Search destinations</label>
            <input type="text" name="search" id="searchInput" autocomplete="off" value="{{ request('search') }}" placeholder="Search destinations..." 
                   class="border border-gray-300 rounded-lg px-4 py-2 flex-1 min-w-[200px] focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition font-medium">Search</button>
            
            <button type="button" onclick="openFilter()" class="md:hidden bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition flex items-center justify-center">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
        </form>
      </div>

      <div id="placesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        @foreach ($exploreplaces as $place)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
                <div class="w-full h-56 overflow-hidden relative">
                    @if(isset($place->main_image))
                        <img src="{{ asset('storage/' . $place->main_image) }}" alt="{{ $place->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @elseif(isset($place->images[0]))
                        <img src="{{ asset('storage/' . $place->images[0]) }}" alt="{{ $place->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <img src="{{ asset('image/no-image.png') }}" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md text-xs font-bold text-gray-800 flex items-center gap-1 shadow">
                        <span class="text-yellow-500 text-base">★</span>
                        {{ number_format($place->reviews_avg_ratings ?? 0, 1) }}
                    </div>
                </div>
                
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1">{{ $place->name }}</h3>
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        @foreach ($place->categories->take(3) as $category)
                            <span class="bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-md border border-blue-100">
                                {{ $category->category }}
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-auto pt-4 border-t border-gray-100">
                        <a href="{{ route('exploreplaces.show', $place->id) }}" class="block text-center bg-gray-50 hover:bg-blue-600 text-gray-700 hover:text-white font-medium px-4 py-2.5 rounded-xl transition duration-300 w-full">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
      </div>
      
      @if($exploreplaces->isEmpty())
        <div class="text-center py-16">
            <h3 class="text-xl text-gray-500 font-medium">No places found matching your filters.</h3>
        </div>
      @endif

    </div>
  </div>
</section>

  @include('components.ffooter')

<script>
    window.addEventListener('popstate', function(event) {
        if (window.location.pathname.includes('/exploreplaces')) {
            window.location.href = '/';
        }
    });

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

    let selectedCategories = JSON.parse('{!! json_encode($selectedCategories ?? []) !!}');

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
                    if(id !== undefined) {
                        const isSelected = selectedCategories.includes(parseInt(id));
                        el.classList.toggle('bg-blue-600', isSelected);
                        el.classList.toggle('text-white', isSelected);
                        el.classList.toggle('font-medium', isSelected);
                        el.classList.toggle('shadow-sm', isSelected);
                        el.classList.toggle('bg-gray-50', !isSelected);
                        el.classList.toggle('text-gray-700', !isSelected);
                    }
                });
                
                window.history.pushState({}, '', url);
            })
            .catch(console.error);
    }
</script>
</body>
</html>