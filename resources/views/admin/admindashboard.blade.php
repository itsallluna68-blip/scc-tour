<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>

<body class="bg-gray-50 font-sans">

<div class="flex">
  @include('components.sidebar')

  <div class="flex-1 ml-48 pt-16">
    @include('components.header')

    <main class="p-6 space-y-6">

      <!-- HEADER -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
          <p class="text-sm text-gray-500">Overview</p>
        </div>
        <p class="text-sm text-gray-500">
          {{ now()->format('M d, Y H:i') }}
        </p>
      </div>

      <!-- STATS -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white border rounded-lg p-4 flex justify-between">
          <div>
            <p class="text-xs text-gray-500">Tourist Spots</p>
            <p class="text-lg font-semibold">{{ $touristCount ?? 0 }}</p>
          </div>
          <i data-lucide="map-pin" class="text-indigo-500"></i>
        </div>

        <div class="bg-white border rounded-lg p-4 flex justify-between">
          <div>
            <p class="text-xs text-gray-500">Categories</p>
            <p class="text-lg font-semibold">{{ $categoryCount ?? 0 }}</p>
          </div>
          <i data-lucide="layers" class="text-green-500"></i>
        </div>

        <div class="bg-white border rounded-lg p-4 flex justify-between">
          <div>
            <p class="text-xs text-gray-500">Total Visits</p>
            <p class="text-lg font-semibold">{{ number_format($totalVisits ?? 0) }}</p>
          </div>
          <i data-lucide="bar-chart-2" class="text-yellow-500"></i>
        </div>

        <div class="bg-white border rounded-lg p-4 flex justify-between">
          <div>
            <p class="text-xs text-gray-500">This Month</p>
            <p id="realtimeCount" class="text-lg font-semibold">
              {{ $currentMonthVisits ?? 0 }}
            </p>
          </div>
          <i data-lucide="clock" class="text-red-500"></i>
        </div>

      </div>

      <!-- CHARTS -->
      <div class="grid md:grid-cols-2 gap-4">

        <div class="bg-white border rounded-lg p-4">
          <h3 class="text-sm font-semibold mb-2">Monthly Visits</h3>
          <canvas id="visitsChart"
            data-labels='@json($labels ?? [])'
            data-values='@json($data ?? [])'>
          </canvas>
        </div>

        <div class="bg-white border rounded-lg p-4">
          <h3 class="text-sm font-semibold mb-2">Visitor Analytics</h3>
          <canvas id="visitorsChart"
            data-labels='@json($labels ?? [])'
            data-values='@json($data ?? [])'>
          </canvas>
        </div>

      </div>

      <!-- TABLE + RECENT -->
      <div class="grid md:grid-cols-2 gap-4">

        <!-- TABLE -->
        <div class="bg-white border rounded-lg p-4">
          <h3 class="text-sm font-semibold mb-2">Visits Report</h3>

          <table class="w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="text-left p-2">Month</th>
                <th class="text-right p-2">Visitors</th>
              </tr>
            </thead>

            <tbody>
              @foreach($labels ?? [] as $i => $label)
              <tr class="border-t">
                <td class="p-2">{{ $label }}</td>
                <td class="p-2 text-right font-medium">
                  {{ number_format($data[$i] ?? 0) }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- RECENT -->
        <div class="bg-white border rounded-lg p-4">
          <h3 class="text-sm font-semibold mb-2">Recent Places</h3>

          @forelse($recentPlaces as $place)
            <div class="border-b py-2 text-sm text-gray-700">
              {{ $place->name }}
            </div>
          @empty
            <p class="text-gray-400 text-sm">No data</p>
          @endforelse
        </div>

      </div>

    </main>
  </div>
</div>

<!-- SCRIPTS -->
<script>
const visitsCtx = document.getElementById('visitsChart');
const visitorsCtx = document.getElementById('visitorsChart');

const labels = JSON.parse(visitsCtx.dataset.labels || '[]');
const data = JSON.parse(visitsCtx.dataset.values || '[]');

const visitsChart = new Chart(visitsCtx, {
  type: 'line',
  data: {
    labels: labels,
    datasets: [{
      data: data,
      borderColor: '#6366f1',
      backgroundColor: 'rgba(99,102,241,0.1)',
      fill: true
    }]
  },
  options: { responsive: true }
});

const visitorsChart = new Chart(visitorsCtx, {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [{
      data: data,
      backgroundColor: '#6366f1'
    }]
  },
  options: { responsive: true }
});

function updateRealtime() {
  fetch("{{ route('realtime.visits') }}")
    .then(res => res.json())
    .then(json => {

      const count = json.currentMonthVisits;

      // update text
      document.getElementById('realtimeCount').innerText = count;

      // ✅ FIX: ONLY update last value (no push)
      if (visitsChart.data.datasets[0].data.length > 0) {
        let lastIndex = visitsChart.data.datasets[0].data.length - 1;

        visitsChart.data.datasets[0].data[lastIndex] = count;
        visitorsChart.data.datasets[0].data[lastIndex] = count;

        visitsChart.update();
        visitorsChart.update();
      }
    });
}

setInterval(updateRealtime, 15000);
</script>

</body>
</html>