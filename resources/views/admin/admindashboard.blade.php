<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      .stat-value.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); -webkit-text-fill-color: transparent; }
      .stat-value.yellow { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); -webkit-text-fill-color: transparent; }
      .stat-value.red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); -webkit-text-fill-color: transparent; }
      .stat-value.purple { background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); -webkit-text-fill-color: transparent; }
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
    {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="flex-1 ml-48 pt-16 min-h-screen">
      {{-- Header --}}
      @include('components.header')

      {{-- MAIN DASHBOARD CONTENT --}}
      <main class="p-8 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">

        <!-- DASHBOARD HEADER -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-12">
          <div>
            <h1 class="text-5xl font-black text-white mb-3 tracking-tight">Dashboard</h1>
            <p class="text-slate-400 text-lg">Real-time analytics and performance metrics</p>
          </div>
          <div class="mt-6 md:mt-0 flex flex-col items-end">
            <span class="text-slate-400 text-sm">Last updated</span>
            <span class="text-white font-semibold text-lg">{{ now()->format('F d, Y \a\t H:i') }}</span>
          </div>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

          <!-- Card: Tourist Spots -->
          <div class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade" style="animation-delay: 0s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Tourist Spots</p>
                <h3 class="stat-value text-5xl font-black mt-2">{{ $touristCount ?? 0 }}</h3>
              </div>
              <div class="icon-badge bg-gradient-to-br from-indigo-500 to-indigo-600 p-4 rounded-xl shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">Active locations</p>
          </div>

          <!-- Card: Categories -->
          <div class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Categories</p>
                <h3 class="stat-value green text-5xl font-black mt-2">{{ $categoryCount ?? 0 }}</h3>
              </div>
              <div class="icon-badge bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 rounded-xl shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">Type categories</p>
          </div>

          <!-- Card: Total Visits -->
          <div class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Total Visits</p>
                <h3 class="stat-value yellow text-5xl font-black mt-2">{{ number_format($totalVisits ?? 0) }}</h3>
              </div>
              <div class="icon-badge bg-gradient-to-br from-amber-500 to-amber-600 p-4 rounded-xl shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-2.16-2.66c-.44-.53-1.25-.58-1.78-.15-.53.44-.58 1.25-.15 1.78l3 3.68c.41.53 1.21.58 1.77.14l3.74-4.77c.45-.59.35-1.45-.23-1.89-.59-.45-1.45-.35-1.89.23z"/>
                </svg>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">All time visits</p>
          </div>

          <!-- Card: Real-time Visits (This Month) -->
          <div id="realtimeCard" class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade relative overflow-hidden" style="animation-delay: 0.3s;">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-red-500 to-red-600 opacity-10 rounded-full -mr-20 -mt-20"></div>
            <div class="relative z-10">
              <div class="flex items-center justify-between mb-5">
                <div>
                  <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">This Month</p>
                  <h3 id="realtimeCount" class="stat-value red text-5xl font-black mt-2">{{ $currentMonthVisits ?? 0 }}</h3>
                </div>
                <div class="icon-badge bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-xl shadow-lg animate-pulse">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                  </svg>
                </div>
              </div>
              <p class="text-slate-400 text-sm font-medium">Live visitor count</p>
            </div>
          </div>

          <!-- Card: Upcoming Events -->
          <div class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Events</p>
                <h3 class="stat-value purple text-5xl font-black mt-2">{{ $upcomingEvents ?? 0 }}<span class="text-2xl text-slate-400">/{{ $totalEvents ?? 0 }}</span></h3>
              </div>
              <div class="icon-badge bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-xl shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zm-5-5h5v5h-5z"/>
                </svg>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">Upcoming events</p>
          </div>

        </div>
        {{-- END STATS CARDS --}}


      <!-- CHARTS & DATA SECTION -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-10">
        <!-- Line Chart: Monthly Website Visits -->
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
            <canvas id="visitsChart"></canvas>
          </div>
        </div>

        <!-- Bar Chart: Website Visits (Realtime) -->
        <div class="bg-white bg-opacity-95 backdrop-blur-sm p-6 rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-300 border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-xl font-bold text-gray-900">Visitor Analytics</h3>
              <p class="text-xs text-gray-500 mt-1">Monthly breakdown with real-time updates</p>
            </div>
            <div class="text-3xl">📊</div>
          </div>
          <div class="h-64 chart-container">
            <canvas id="visitorsChart"></canvas>
          </div>
        </div>
      </div>

      <!-- DATA TABLES AND LISTS SECTION -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <!-- VISITS REPORT TABLE -->
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

        <!-- RECENT PLACES LIST -->
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
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 group-hover:text-emerald-600 transition ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </div>
              @endforeach
            @else
              <div class="text-center py-8 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
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

  <script>
    // create chart instances and expose to update function
    let visitsChartInstance = null;
    let visitorsChartInstance = null;

    const visitsCtx = document.getElementById('visitsChart');
    if (visitsCtx) {
      visitsChartInstance = new Chart(visitsCtx, {
        type: 'line',
        data: {
          labels: @json($labels ?? []),
          datasets: [
            {
              label: 'Website Visits',
              data: @json($data ?? []),
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
              pointShadowBlur: 8,
              shadowColor: 'rgba(99, 102, 241, 0.2)',
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

    const visitorsCtx = document.getElementById('visitorsChart');
    if (visitorsCtx) {
      visitorsChartInstance = new Chart(visitorsCtx, {
        type: 'bar',
        data: {
          labels: @json($labels ?? []),
          datasets: [{
            label: 'Website Visits',
            data: @json($data ?? []),
            backgroundColor: [
              '#6366f1', '#8b5cf6', '#ec4899', '#f43f5e', '#f97316', '#eab308',
              '#84cc16', '#22c55e', '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9'
            ],
            borderRadius: 10,
            borderSkipped: false,
            hoverBackgroundColor: '#4f46e5',
            borderWidth: 2,
            borderColor: 'transparent',
            shadowColor: 'rgba(0, 0, 0, 0.1)',
            shadowBlur: 8,
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
  </script>

  <script>
    function updateRealtime() {
      fetch("{{ route('realtime.visits') }}")
        .then(res => res.json())
        .then(json => {
          const count = json.currentMonthVisits;
          const element = document.getElementById('realtimeCount');
          if (element && element.innerText !== count.toString()) {
            // Animate number change
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
          // update charts with new monthly total if instances exist
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