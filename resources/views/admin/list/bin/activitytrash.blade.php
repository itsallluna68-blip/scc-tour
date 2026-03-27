<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>Activity Trash Bin</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>

<body class="bg-gray-100 font-sans text-gray-800">

  @if(session('success'))
  <div id="flash-success" data-message="{{ session('success') }}" class="hidden"></div>
  @endif
  @if(session('error'))
  <div id="flash-error" data-message="{{ session('error') }}" class="hidden"></div>
  @endif

  @include('components.sidebar')

  <div class="flex-1 ml-48">
    @include('components.header')
    <div class="flex-1 ml-60"></div>
    <div class="flex-1"></div>

    <main class="p-6">
      @yield('content')
    </main>

    <div class="p-6">

      <h2 class="text-2xl font-semibold text-red-900 flex items-center gap-2 mb-6">
        <i data-lucide="trash-2" class="w-6 h-6"></i> Trashed Activities
      </h2>

      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div class="relative w-full md:w-80">
          <label for="searchInput" class="sr-only">Search</label>
          <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <input type="text" id="searchInput" placeholder="Search activity name or info" autocomplete="off" class="w-full border border-gray-300 rounded-md pl-9 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition shadow-sm">
        </div>
      </div>

      <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200 mb-4">
        <table class="w-full text-sm text-left">
          <thead class="bg-red-900 text-white">
            <tr>
              <th class="py-3 px-4">Name</th>
              <th class="py-3 px-4">Info</th>
              <th class="py-3 px-4 text-center w-36">Actions</th>
            </tr>
          </thead>
          <tbody id="activitiesTableBody" class="divide-y divide-gray-200">
            @forelse($activities as $activity)
            <tr class="activity-row hover:bg-gray-50 transition">
              <td class="py-3 px-4 font-medium text-gray-900">{{ $activity->a_name }}</td>
              <td class="py-3 px-4 text-gray-600">{{ \Illuminate\Support\Str::limit($activity->a_info, 80, '...') }}</td>
              <td class="py-3 px-4 flex justify-center gap-4">
                <button type="button" onclick="confirmAction('restore', '/admin/bin/activities/{{ $activity->aid }}/restore', '{{ csrf_token() }}')" class="text-green-600 hover:text-green-800 transition bg-transparent focus:outline-none" title="Restore">
                  <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                </button>
                <button type="button" onclick="confirmAction('forceDelete', '/admin/bin/activities/{{ $activity->aid }}/force-delete', '{{ csrf_token() }}')" class="text-red-600 hover:text-red-800 transition bg-transparent focus:outline-none" title="Permanently Delete">
                  <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="3" class="py-8 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                    <i data-lucide="file-x" class="w-10 h-10 text-gray-300 mb-2"></i>
                    <p class="font-medium">No trashed activities found.</p>
                </div>
              </td>
            </tr>
            @endforelse
            <tr id="noSearchMatchRow" style="display: none;">
              <td colspan="3" class="py-8 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                  <i data-lucide="search-x" class="w-10 h-10 text-gray-300 mb-2"></i>
                  <p class="font-medium">No activities match your search.</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $activities->links() }}
      </div>

    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      reRenderIcons();
    });

    function reRenderIcons() {
      setTimeout(() => {
        if (typeof window.lucide !== 'undefined') {
          window.lucide.createIcons({
            icons: window.lucide.icons
          });
        }
      }, 50);
    }

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.activity-row');
        let visibleCount = 0;

        rows.forEach(row => {
          let text = row.textContent.toLowerCase();
          if (text.includes(filter)) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });

        const noSearchMatchRow = document.getElementById('noSearchMatchRow');
        if (noSearchMatchRow) {
          if (visibleCount === 0 && rows.length > 0) {
            noSearchMatchRow.style.display = '';
          } else {
            noSearchMatchRow.style.display = 'none';
          }
        }
      });
    }
  </script>

</body>

</html>