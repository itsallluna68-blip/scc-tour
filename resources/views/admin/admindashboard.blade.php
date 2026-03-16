<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>Admin Dashboard</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-800 min-h-screen">

  <div class="flex">
    {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="flex-1 ml-48 pt-16 min-h-screen">
      @include('components.header')

      <main class="p-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
          <div>
            <h1 class="text-2xl font-semibold text-gray-800 mb-1">Dashboard</h1>
            <p class="text-sm text-gray-500">Real-time analytics and performance metrics</p>
          </div>
          <div class="mt-4 md:mt-0 text-sm text-gray-500">
            Last updated: <span class="font-medium text-gray-800">{{ now()->format('F d, Y \a\t H:i') }}</span>
          </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

          <div class="bg-white border rounded-xl p-4 flex justify-between items-center">
            <div>
              <p class="text-xs text-gray-500">Tourist Spots</p>
              <p class="text-xl font-semibold text-gray-800">{{ $touristCount ?? 0 }}</p>
            </div>
            <div class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
              <i data-lucide="map-pin" class="w-5 h-5"></i>
            </div>
          </div>

          <div class="bg-white border rounded-xl p-4 flex justify-between items-center">
            <div>
              <p class="text-xs text-gray-500">Categories</p>
              <p class="text-xl font-semibold text-gray-800">{{ $categoryCount ?? 0 }}</p>
            </div>
            <div class="bg-green-100 text-green-600 p-2 rounded-lg">
              <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
          </div>

          <div class="bg-white border rounded-xl p-4 flex justify-between items-center">
            <div>
              <p class="text-xs text-gray-500">Total Visits</p>
              <p class="text-xl font-semibold text-gray-800">{{ number_format($totalVisits ?? 0) }}</p>
            </div>
            <div class="bg-yellow-100 text-yellow-600 p-2 rounded-lg">
              <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
            </div>
          </div>

          <div class="bg-white border rounded-xl p-4 flex justify-between items-center">
            <div>
              <p class="text-xs text-gray-500">This Month</p>
              <p id="realtimeCount" class="text-xl font-semibold text-gray-800">{{ $currentMonthVisits ?? 0 }}</p>
            </div>
            <div class="bg-red-100 text-red-600 p-2 rounded-lg">
              <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
          </div>

        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
          <div class="bg-white border rounded-xl p-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Monthly Visits</h3>
            <canvas id="visitsChart" data-labels="{{ json_encode($labels ?? []) }}" data-values="{{ json_encode($data ?? []) }}" class="w-full h-64"></canvas>
          </div>
          <div class="bg-white border rounded-xl p-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Visitor Analytics</h3>
            <canvas id="visitorsChart" data-labels="{{ json_encode($labels ?? []) }}" data-values="{{ json_encode($data ?? []) }}" class="w-full h-64"></canvas>
          </div>
        </div>

        {{-- Recent Places & Reports --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

          <div class="bg-white border rounded-xl p-4 overflow-y-auto max-h-60">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Recently Added Places</h3>
            @if(!$recentPlaces->isEmpty())
              @foreach($recentPlaces as $index => $place)
                <div class="flex items-center justify-between p-2 border-b last:border-b-0">
                  <span class="text-gray-500 text-sm">{{ $index + 1 }}.</span>
                  <p class="text-gray-700 text-sm font-medium truncate">{{ $place->name }}</p>
                  <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </div>
              @endforeach
            @else
              <p class="text-gray-400 text-sm text-center py-4">No places added yet.</p>
            @endif
          </div>

          <div class="bg-white border rounded-xl p-4 overflow-x-auto max-h-60">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Visits Report</h3>
            <table class="min-w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="text-left px-2 py-1 text-gray-500 uppercase">Month</th>
                  <th class="text-right px-2 py-1 text-gray-500 uppercase">Visitors</th>
                </tr>
              </thead>
              <tbody>
                @foreach($labels ?? [] as $i => $label)
                  <tr class="border-t">
                    <td class="px-2 py-1 text-gray-700">{{ $label }}</td>
                    <td class="px-2 py-1 text-right font-semibold text-gray-800">{{ number_format($data[$i] ?? 0) }}</td>
                  </tr>
                @endforeach
                @if(empty($labels))
                  <tr>
                    <td colspan="2" class="text-center text-gray-400 py-4">No data available</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>

        </div>

      </main>
    </div>
  </div>

  {{-- Chart & Realtime Script --}}
  <script type="module">
    const visitsCtx = document.getElementById('visitsChart');
    const visitorsCtx = document.getElementById('visitorsChart');

    const chartLabels = visitsCtx ? JSON.parse(visitsCtx.dataset.labels || '[]') : [];
    const chartData = visitsCtx ? JSON.parse(visitsCtx.dataset.values || '[]') : [];

    function createChart(ctx, type='line') {
      return new Chart(ctx, {
        type,
        data: { labels: chartLabels, datasets: [{ label: 'Visits', data: chartData, borderColor:'#6366f1', backgroundColor:'rgba(99,102,241,0.1)', fill:true }] },
        options: { responsive:true, maintainAspectRatio:false }
      });
    }

    const visitsChartInstance = visitsCtx ? createChart(visitsCtx,'line') : null;
    const visitorsChartInstance = visitorsCtx ? createChart(visitorsCtx,'bar') : null;

    function updateRealtime() {
      fetch("{{ route('realtime.visits') }}")
        .then(res => res.json())
        .then(json => {
          const count = json.currentMonthVisits;
          const el = document.getElementById('realtimeCount');
          if(el) el.innerText = count.toLocaleString();
        });
    }
    updateRealtime();
    setInterval(updateRealtime, 15000);
  </script>

</body>
</html>