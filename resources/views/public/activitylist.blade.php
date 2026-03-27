<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>Activities in San Carlos City</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>

<body class="bg-gray-50 text-gray-800">

  @include('components.fnavbar')
  <div class="mb-12"></div>

  <section class="bg-gray-50 py-16">
    <div class="max-w-6xl mx-auto px-4">

      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

        <div>
          <h1 class="text-3xl font-bold text-gray-900">
            Activities
          </h1>
          <p class="mt-2 text-gray-600 text-sm max-w-md">
            Discover exciting activities you can experience in San Carlos City.
          </p>
        </div>

        <form id="searchForm" method="GET" action="{{ route('activities.index') }}" class="flex w-full md:w-auto">
          <input
            type="text"
            id="searchInput"
            name="search"
            autocomplete="off"
            value="{{ request('search') }}"
            placeholder="Search activities"
            class="px-4 py-2 w-full md:w-64 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
          <button
            type="submit"
            class="bg-indigo-600 text-white px-5 py-2 rounded-r-lg hover:bg-indigo-700 transition font-medium">
            Search
          </button>
        </form>

      </div>

      <div class="border-t border-gray-300 my-8"></div>

      <div id="activitiesGrid" class="grid grid-cols-1 md:grid-cols-2 gap-8">

        @forelse ($activities as $activity)

        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">

          <div class="overflow-hidden">
            <img
              src="{{ !empty($activity->img0) ? \Illuminate\Support\Facades\Storage::disk('s3')->url($activity->img0) : asset('image/sample-activity.jpg') }}"
              class="w-full h-64 object-cover">
          </div>

          <div class="p-6 flex items-center justify-between">

            <h3 class="text-lg font-semibold text-gray-900">
              {{ $activity->a_name }}
            </h3>

            <a
              href="{{ route('activities.show', $activity->aid) }}"
              class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
              View
            </a>

          </div>

        </div>

        @empty
        <div class="col-span-1 md:col-span-2 text-center py-12">
          <p class="text-gray-500 text-lg">No activities found matching your search.</p>
        </div>
        @endforelse

      </div>

    </div>
  </section>

  @include('components.ffooter')

  <script>
    let searchTimeout = null;
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
          fetchUpdatedActivities();
        }, 400);
      });
    }

    if (searchForm) {
      searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        clearTimeout(searchTimeout);
        fetchUpdatedActivities();
      });
    }

    function fetchUpdatedActivities() {
      const params = new URLSearchParams();

      if (searchInput && searchInput.value.trim() !== '') {
        params.set('search', searchInput.value.trim());
      }

      const url = '{{ route("activities.index") }}' + (params.toString() ? '?' + params.toString() : '');

      fetch(url, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(res => res.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const newGrid = doc.querySelector('#activitiesGrid');
          if (newGrid) {
            document.querySelector('#activitiesGrid').innerHTML = newGrid.innerHTML;
          }
          window.history.pushState({}, '', url);
        })
        .catch(console.error);
    }
  </script>
</body>

</html>