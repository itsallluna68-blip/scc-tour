<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/public.js'])

    <style>
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
      }
      .card-fade { animation: fadeIn 0.6s ease; }

      .stat-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
      }
      .stat-card:hover {
        border-color: #6366f1;
        box-shadow: 0 10px 15px rgba(0,0,0,0.05);
        transform: translateY(-2px);
      }
    </style>
</head>

<body class="bg-gray-100 font-sans text-gray-800 min-h-screen">

<div class="flex">
  @include('components.sidebar')

  <div class="flex-1 ml-48 pt-16 min-h-screen">
    @include('components.header')

    <main class="p-8 bg-gray-100 min-h-screen">

      <!-- HEADER -->
      <div class="flex justify-between items-center mb-10">
        <div>
          <h1 class="text-4xl font-bold text-gray-900 mb-2">Dashboard</h1>
          <p class="text-gray-500 text-sm">Real-time analytics</p>
        </div>
        <div class="text-right">
          <span class="text-gray-500 text-sm">Last updated</span><br>
          <span class="text-gray-800 font-semibold text-sm">
            {{ now()->format('F d, Y \a\t H:i') }}
          </span>
        </div>
      </div>

      <!-- STATS -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <div class="stat-card p-6 rounded-xl card-fade">
          <p class="text-gray-500 text-xs uppercase">Tourist Spots</p>
          <h3 class="text-3xl font-bold text-indigo-600 mt-2">{{ $touristCount ?? 0 }}</h3>
        </div>

        <div class="stat-card p-6 rounded-xl card-fade">
          <p class="text-gray-500 text-xs uppercase">Categories</p>
          <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $categoryCount ?? 0 }}</h3>
        </div>

        <div class="stat-card p-6 rounded-xl card-fade">
          <p class="text-gray-500 text-xs uppercase">Total Visits</p>
          <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ number_format($totalVisits ?? 0) }}</h3>
        </div>

        <div class="stat-card p-6 rounded-xl card-fade">
          <p class="text-gray-500 text-xs uppercase">This Month</p>
          <h3 id="realtimeCount" class="text-3xl font-bold text-red-600 mt-2">
            {{ $currentMonthVisits ?? 0 }}
          </h3>
        </div>

      </div>

      <!-- CHARTS -->
      <div class="grid md:grid-cols-2 gap-6 mb-10">

        <div class="bg-white p-6 rounded-xl border">
          <h3 class="font-semibold mb-4">Monthly Visits</h3>
          <div class="h-64">
            <canvas id="visitsChart"
              data-labels='@json($labels ?? [])'
              data-values='@json($data ?? [])'>
            </canvas>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl border">
          <h3 class="font-semibold mb-4">Visitor Analytics</h3>
          <div class="h-64">
            <canvas id="visitorsChart"
              data-labels='@json($labels ?? [])'
              data-values='@json($data ?? [])'>
            </canvas>
          </div>
        </div>

      </div>

      <!-- TABLE + RECENT -->
      <div class="grid md:grid-cols-2 gap-6">

        <!-- TABLE -->
        <div class="bg-white rounded-xl border overflow-hidden">
          <div class="p-4 border-b">
            <h3 class="font-semibold">Visits Report</h3>
          </div>

          <div class="overflow-x-auto max-h-60">
            <table class="w-full text-sm">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="p-3 text-left">Month</th>
                  <th class="p-3 text-right">Visitors</th>
                  <th class="p-3 text-right">Categories</th>
                  <th class="p-3 text-right">Total Visits</th>
                </tr>
              </thead>

              <tbody>
                @foreach($labels ?? [] as $i => $label)
                <tr class="border-t hover:bg-gray-50">
                  <td class="p-3">{{ $label }}</td>

                  <td class="p-3 text-right font-semibold text-indigo-600">
                    {{ number_format($data[$i] ?? 0) }}
                  </td>

                  <td class="p-3 text-right text-green-600 font-semibold">
                    {{ $categoryCount ?? 0 }}
                  </td>

                  <td class="p-3 text-right text-yellow-600 font-semibold">
                    {{ number_format($totalVisits ?? 0) }}
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <!-- RECENT -->
        <div class="bg-white rounded-xl border p-4 max-h-60 overflow-y-auto">
          <h3 class="font-semibold mb-3">Recently Added Places</h3>

          @forelse($recentPlaces as $place)
            <div class="p-2 border-b text-sm text-gray-700 hover:bg-gray-50">
              {{ $place->name }}
            </div>
          @empty
            <p class="text-gray-400 text-sm">No places yet</p>
          @endforelse
        </div>

      </div>

    </main>
  </div>
</div>

<!-- SCRIPT -->
<script>
const visitsCtx = document.getElementById('visitsChart');
const visitorsCtx = document.getElementById('visitorsChart');

const labels = JSON.parse(visitsCtx.dataset.labels || '[]');
const data = JSON.parse(visitsCtx.dataset.values || '[]');

const visitsChart = new Chart(visitsCtx, {
  type: 'line',
  data: {
    labels,
    datasets: [{
      data,
      borderColor: '#6366f1',
      backgroundColor: 'rgba(99,102,241,0.1)',
      fill: true
    }]
  }
});

const visitorsChart = new Chart(visitorsCtx, {
  type: 'bar',
  data: {
    labels,
    datasets: [{
      data,
      backgroundColor: '#6366f1'
    }]
  }
});

function updateRealtime() {
  fetch("{{ route('realtime.visits') }}")
    .then(res => res.json())
    .then(json => {
      const count = json.currentMonthVisits;

      document.getElementById('realtimeCount').innerText = count;

      // ✅ FIX: update last value only (NO PUSH)
      if (labels.length > 0) {
        let last = labels.length - 1;

        visitsChart.data.datasets[0].data[last] = count;
        visitorsChart.data.datasets[0].data[last] = count;

        visitsChart.update();
        visitorsChart.update();
      }
    });
}

setInterval(updateRealtime, 15000);
</script>

</body>
</html>