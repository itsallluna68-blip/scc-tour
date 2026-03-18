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
      @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
      }
      @keyframes glow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.3); }
        50% { box-shadow: 0 0 0 10px rgba(99, 102, 241, 0); }
      }
      .card-fade { animation: fadeIn 0.6s cubic-bezier(0.23, 1, 0.320, 1); }
      .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafb 100%);
        border: 1px solid rgba(229, 231, 235, 0.8);
      }
      .stat-card:hover {
        border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.1);
        transform: translateY(-2px);
      }
      .stat-value {
        color: #6366f1;
      }
      .stat-value.green { color: #10b981; }
      .stat-value.yellow { color: #f59e0b; }
      .stat-value.red { color: #ef4444; }
      .stat-value.purple { color: #a855f7; }

      .icon-badge {
        transition: all 0.3s ease;
      }
      .stat-card:hover .icon-badge {
        transform: scale(1.1) rotate(5deg);
      }
      .chart-container { position: relative; }
      .data-table-wrapper::-webkit-scrollbar { height: 6px; }
      .data-table-wrapper::-webkit-scrollbar-track { background: #f3f4f6; border-radius: 3px; }
      .data-table-wrapper::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
      .data-table-wrapper::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 font-sans text-gray-800 min-h-screen">

  <div class="flex">
    @include('components.sidebar')

    <div class="flex-1 ml-48 pt-16 min-h-screen">
      @include('components.header')

      <main class="p-8 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-12">
          <div>
            <h1 class="text-5xl font-black text-white mb-3 tracking-tight">Dashboard</h1>
            <p class="text-slate-400 text-lg">Real-time analytics and performance metrics</p>
          </div>
          <div class="mt-6 md:mt-0 flex flex-col items-end">
            <span class="text-slate-400 text-sm">Last updated</span>
            <span class="text-white font-semibold text-lg">
    {{ now()->timezone('Asia/Manila')->format('F d, Y \a\t h:i A') }}
</span>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

          <div class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade" style="animation-delay: 0s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Tourist Spots</p>
                <h3 class="stat-value text-5xl font-black mt-2">{{ $touristCount ?? 0 }}</h3>
              </div>
              <div class="icon-badge bg-gradient-to-br from-indigo-500 to-indigo-600 p-4 rounded-xl shadow-lg">
                <i data-lucide="map-pin" class="w-8 h-8 text-white"></i>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">Active locations</p>
          </div>

          <div class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Categories</p>
                <h3 class="stat-value green text-5xl font-black mt-2">{{ $categoryCount ?? 0 }}</h3>
              </div>
              <div class="icon-badge bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 rounded-xl shadow-lg">
                <i data-lucide="layers" class="w-8 h-8 text-white"></i>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">Type categories</p>
          </div>

          <div class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Total Visits</p>
                <h3 class="stat-value yellow text-5xl font-black mt-2">{{ number_format($totalVisits ?? 0) }}</h3>
              </div>
              <div class="icon-badge bg-gradient-to-br from-amber-500 to-amber-600 p-4 rounded-xl shadow-lg">
                <i data-lucide="bar-chart-2" class="w-8 h-8 text-white"></i>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">All time visits</p>
          </div>

          <div id="realtimeCard" class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade relative overflow-hidden" style="animation-delay: 0.3s;">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-red-500 to-red-600 opacity-10 rounded-full -mr-20 -mt-20 z-0 pointer-events-none"></div>
            <div class="relative z-20">
              <div class="flex items-center justify-between mb-5">
                <div>
                  <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">This Month</p>
                  <h3 id="realtimeCount" class="stat-value red text-5xl font-black mt-2">{{ $currentMonthVisits ?? 0 }}</h3>
                </div>
                <div class="icon-badge bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-xl shadow-lg animate-pulse">
                  <i data-lucide="clock" class="w-8 h-8 text-white"></i>
                </div>
              </div>
              <p class="text-slate-400 text-sm font-medium">Live visitor count</p>
            </div>
          </div>

          <div class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Events</p>
                <h3 class="stat-value purple text-5xl font-black mt-2">{{ $upcomingEvents ?? 0 }}<span class="text-2xl text-slate-400">/{{ $totalEvents ?? 0 }}</span></h3>
              </div>
              <div class="icon-badge bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-xl shadow-lg">
                <i data-lucide="calendar" class="w-8 h-8 text-white"></i>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">Upcoming events</p>
          </div>

        </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-10">
        <div class="bg-white bg-opacity-95 backdrop-blur-sm p-6 rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-300 border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-xl font-bold text-gray-900">Monthly Visits</h3>
              <p class="text-xs text-gray-500 mt-1">Trend over 12 months</p>
            </div>
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full animate-pulse"></div>
              <span class="text-xs text-gray-500">Live</span>
            </div>
          </div>
          <div class="h-64 chart-container">
            <canvas id="visitsChart" data-labels="{{ json_encode($labels ?? []) }}" data-values="{{ json_encode($data ?? []) }}"></canvas>
          </div>
        </div>

        <div class="bg-white bg-opacity-95 backdrop-blur-sm p-6 rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-300 border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-xl font-bold text-gray-900">Visitor Analytics</h3>
              <p class="text-xs text-gray-500 mt-1">Monthly breakdown with real-time updates</p>
            </div>
            <div class="text-gray-500">
                <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
            </div>
          </div>
          <div class="h-64 chart-container">
            <canvas id="visitorsChart" data-labels="{{ json_encode($labels ?? []) }}" data-values="{{ json_encode($data ?? []) }}"></canvas>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div class="bg-white bg-opacity-95 backdrop-blur-sm rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-300 overflow-hidden border border-gray-100">
          <div class="p-6 pb-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 via-transparent to-transparent">
            <h3 class="text-xl font-bold text-gray-900">Visits Report</h3>
            <p class="text-xs text-gray-500 mt-1">Detailed monthly breakdown</p>
          </div>
          <div class="overflow-x-auto max-h-60 data-table-wrapper">
            <table class="min-w-full">
              <thead class="bg-gradient-to-r from-gray-50 to-transparent sticky top-0 z-10">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Month</th>
                  <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Visitors</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @foreach($labels ?? [] as $i => $label)
                  <tr class="hover:bg-indigo-50 transition duration-150 group">
                    <td class="px-6 py-3 text-sm text-gray-700 font-semibold group-hover:text-indigo-700">{{ $label }}</td>
                    <td class="px-6 py-3 text-right">
                      <span class="text-sm font-bold bg-gradient-to-r from-indigo-600 to-indigo-700 bg-clip-text text-transparent">
                        {{ number_format($data[$i] ?? 0) }}
                      </span>
                    </td>
                  </tr>
                @endforeach
                @if(empty($labels))
                  <tr>
                    <td colspan="2" class="px-6 py-8 text-center text-gray-400 text-sm">No data available</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>

        <div class="bg-white bg-opacity-95 backdrop-blur-sm rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-300 overflow-hidden border border-gray-100">
          <div class="p-6 pb-4 border-b border-gray-100 bg-gradient-to-r from-green-50 via-transparent to-transparent">
            <h3 class="text-xl font-bold text-gray-900">Recently Added Places</h3>
            <p class="text-xs text-gray-500 mt-1">Latest {{ $recentPlaces->count() }} entries</p>
          </div>
          <div class="p-6 space-y-2 max-h-60 overflow-y-auto data-table-wrapper">
            @if(!$recentPlaces->isEmpty())
              @foreach($recentPlaces as $index => $place)
                <div class="flex items-center p-3 bg-gradient-to-r from-emerald-50 to-transparent rounded-lg hover:from-emerald-100 transition-all duration-200 group cursor-pointer border border-transparent hover:border-emerald-200">
                  <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 text-white text-xs font-bold mr-3 group-hover:scale-110 transition-transform duration-150 shadow-md">
                    {{ $index + 1 }}
                  </span>
                  <div class="flex-1 min-w-0">
                    <p class="text-gray-700 font-semibold group-hover:text-emerald-700 transition truncate text-sm">{{ $place->name }}</p>
                  </div>
                  <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 group-hover:text-emerald-600 transition ml-2"></i>
                </div>
              @endforeach
            @else
              <div class="text-center py-8 text-gray-400 flex flex-col items-center">
                <i data-lucide="map" class="w-12 h-12 mb-2 opacity-30"></i>
                <p class="text-sm font-semibold">No places added yet</p>
                <p class="text-xs mt-1">New venues will appear here</p>
              </div>
            @endif
          </div>
        </div>
      </div>

      </main>
    </div>
  </div>

  <script type="module">
    let visitsChartInstance = null;
    let visitorsChartInstance = null;

    const visitsCtx = document.getElementById('visitsChart');
    const visitorsCtx = document.getElementById('visitorsChart');

    const chartLabels = visitsCtx ? JSON.parse(visitsCtx.dataset.labels || '[]') : [];
    const chartData = visitsCtx ? JSON.parse(visitsCtx.dataset.values || '[]') : [];

    if (visitsCtx) {
      visitsChartInstance = new Chart(visitsCtx, {
        type: 'line',
        data: {
          labels: chartLabels,
          datasets: [
            {
              label: 'Website Visits',
              data: chartData,
              borderColor: '#6366f1',
              backgroundColor: 'rgba(99, 102, 241, 0.08)',
              borderWidth: 3,
              tension: 0.4,
              fill: true,
              pointRadius: 6,
              pointHoverRadius: 8,
              pointBackgroundColor: '#6366f1',
              pointBorderColor: '#fff',
              pointBorderWidth: 3,
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: { mode: 'nearest', axis: 'x', intersect: false },
          plugins: {
            filler: { propagate: true },
            tooltip: { 
              animation: { duration: 0 }, 
              displayColors: true,
              backgroundColor: 'rgba(17, 24, 39, 0.95)',
              titleColor: '#ffffff',
              bodyColor: '#f3f4f6',
              borderColor: 'rgba(99, 102, 241, 0.3)',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 8,
              titleFont: { size: 13, weight: 'bold' },
              bodyFont: { size: 12 },
              callbacks: {
                label: function(context) {
                  return ' Visits: ' + context.raw.toLocaleString();
                }
              }
            },
            legend: { 
              position: 'top',
              labels: { 
                color: '#374151', 
                font: { size: 12, weight: '600' },
                usePointStyle: true,
                padding: 20
              } 
            },
          },
          scales: {
            x: { 
              grid: { display: false },
              ticks: { color: '#6b7280', font: { size: 11, weight: '500' } } 
            },
            y: { 
              beginAtZero: true, 
              grid: { color: '#f0f0f0', drawBorder: false, lineWidth: 1 }, 
              ticks: { color: '#6b7280', font: { size: 11, weight: '500' } } 
            }
          }
        }
      });
    }

    if (visitorsCtx) {
      visitorsChartInstance = new Chart(visitorsCtx, {
        type: 'bar',
        data: {
          labels: chartLabels,
          datasets: [{
            label: 'Website Visits',
            data: chartData,
            backgroundColor: [
              '#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#f97316', '#eab308',
              '#84cc16', '#22c55e', '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9'
            ],
            borderRadius: 10,
            borderSkipped: false,
            hoverBackgroundColor: '#4f46e5',
            borderWidth: 2,
            borderColor: 'transparent',
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: { 
              grid: { display: false }, 
              ticks: { color: '#6b7280', font: { size: 11, weight: '500' } } 
            },
            y: { 
              beginAtZero: true, 
              grid: { color: '#f0f0f0', drawBorder: false, lineWidth: 1 }, 
              ticks: { color: '#6b7280', font: { size: 11, weight: '500' } } 
            }
          },
          plugins: { 
            legend: { display: false },
            tooltip: { 
              animation: { duration: 0 }, 
              backgroundColor: 'rgba(17, 24, 39, 0.95)', 
              titleColor: '#ffffff', 
              bodyColor: '#f3f4f6', 
              borderColor: 'rgba(99, 102, 241, 0.3)',
              borderWidth: 1,
              padding: 12, 
              cornerRadius: 8,
              titleFont: { size: 13, weight: 'bold' },
              bodyFont: { size: 12 },
              callbacks: {
                label: function(context) {
                  return ' Visits: ' + context.raw.toLocaleString();
                }
              }
            } 
          }
        }
      });
    }

    function updateRealtime() {
      fetch("{{ route('realtime.visits') }}")
        .then(res => res.json())
        .then(json => {
          const count = json.currentMonthVisits;
          const element = document.getElementById('realtimeCount');
          if (element && element.innerText !== count.toString()) {
            const oldValue = parseInt(element.innerText.replace(/,/g, ''));
            const newValue = count;
            
            if (oldValue !== newValue) {
              element.classList.add('animate-pulse');
              setTimeout(() => {
                element.innerText = newValue.toLocaleString();
                element.classList.remove('animate-pulse');
              }, 250);
            }
          }
          
          try {
            if (visitorsChartInstance && visitorsChartInstance.data && visitorsChartInstance.data.datasets[0]) {
              const ds = visitorsChartInstance.data.datasets[0];
              const labels = visitorsChartInstance.data.labels || [];
              if (labels.length > 0) {
                const lastIndex = labels.length - 1;
                ds.data[lastIndex] = count;
              } else {
                const monthLabel = new Date().toLocaleString('default', { month: 'short', year: '2-digit' });
                visitorsChartInstance.data.labels.push(monthLabel);
                ds.data.push(count);
              }
              visitorsChartInstance.update('none');
            }

            if (visitsChartInstance && visitsChartInstance.data && visitsChartInstance.data.datasets[0]) {
              const ds2 = visitsChartInstance.data.datasets[0];
              const labels2 = visitsChartInstance.data.labels || [];
              if (labels2.length > 0) {
                const lastIndex2 = labels2.length - 1;
                ds2.data[lastIndex2] = count;
              } else {
                const monthLabel2 = new Date().toLocaleString('default', { month: 'short', year: '2-digit' });
                visitsChartInstance.data.labels.push(monthLabel2);
                ds2.data.push(count);
              }
              visitsChartInstance.update('none');
            }
          } catch (e) {
            console.error('Chart update failed', e);
          }
        })
        .catch(e => console.error('Realtime update failed', e));
    }
    updateRealtime();
    setInterval(updateRealtime, 15000);
  </script>

</body>
</html>