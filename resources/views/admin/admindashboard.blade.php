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
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card-fade {
      animation: fadeIn 0.6s cubic-bezier(0.23, 1, 0.320, 1);
    }

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

    .stat-value.green {
      color: #10b981;
    }

    .stat-value.yellow {
      color: #f59e0b;
    }

    .stat-value.red {
      color: #ef4444;
    }

    .stat-value.purple {
      color: #a855f7;
    }

    .icon-badge {
      transition: all 0.3s ease;
    }

    .stat-card:hover .icon-badge {
      transform: scale(1.1) rotate(5deg);
    }

    .chart-container {
      position: relative;
    }

    .data-table-wrapper::-webkit-scrollbar {
      height: 6px;
      width: 6px;
    }

    .data-table-wrapper::-webkit-scrollbar-track {
      background: #f3f4f6;
      border-radius: 3px;
    }

    .data-table-wrapper::-webkit-scrollbar-thumb {
      background: #d1d5db;
      border-radius: 3px;
    }

    .data-table-wrapper::-webkit-scrollbar-thumb:hover {
      background: #9ca3af;
    }
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
            <span id="lastUpdatedTime" class="text-white font-semibold text-lg transition-all duration-300">
              {{ now()->timezone('Asia/Manila')->format('F d, Y \a\t h:i A') }}
            </span>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

          <div class="stat-card p-8 rounded-2xl shadow-xl transition-all duration-300 card-fade" style="animation-delay: 0s;">
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

          <div class="stat-card p-8 rounded-2xl shadow-xl transition-all duration-300 card-fade" style="animation-delay: 0.1s;">
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

          <div class="stat-card p-8 rounded-2xl shadow-xl transition-all duration-300 card-fade" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Total Visits</p>
                <h3 class="stat-value yellow text-5xl font-black mt-2">{{ number_format($totalVisits ?? 0) }}</h3>
              </div>
              <div class="icon-badge bg-gradient-to-br from-amber-500 to-amber-600 p-4 rounded-xl shadow-lg">
                <i data-lucide="bar-chart-2" class="w-8 h-8 text-white"></i>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">Based on filter</p>
          </div>

          <div id="realtimeCard" class="stat-card p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 card-fade" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">This Month</p>
                <h3 id="realtimeCount" class="stat-value red text-5xl font-black mt-2">{{ number_format($currentMonthVisits ?? 0) }}</h3>
              </div>
              <div class="icon-badge bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-xl shadow-lg">
                <i data-lucide="clock" class="w-8 h-8 text-white"></i>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">Live count</p>
          </div>

          <div class="stat-card p-8 rounded-2xl shadow-xl transition-all duration-300 card-fade" style="animation-delay: 0.4s;">
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

          <div class="stat-card p-8 rounded-2xl shadow-xl transition-all duration-300 card-fade" style="animation-delay: 0.5s;">
            <div class="flex items-center justify-between mb-5">
              <div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Demographics</p>
                <div class="mt-2 flex items-baseline gap-2">
                  <h3 class="text-3xl font-black text-blue-600">{{ number_format($totalTourists ?? 0) }}</h3>
                  <span class="text-xs font-bold text-slate-400 uppercase">Vis</span>
                </div>
                <div class="mt-1 flex items-baseline gap-2">
                  <h3 class="text-2xl font-black text-emerald-500">{{ number_format($totalResidents ?? 0) }}</h3>
                  <span class="text-xs font-bold text-slate-400 uppercase">Res</span>
                </div>
              </div>
              <div class="icon-badge bg-gradient-to-br from-cyan-500 to-blue-600 p-4 rounded-xl shadow-lg">
                <i data-lucide="users" class="w-8 h-8 text-white"></i>
              </div>
            </div>
            <p class="text-slate-400 text-sm font-medium">All time visitors vs residents</p>
          </div>

        </div>

        <div class="bg-white bg-opacity-95 backdrop-blur-sm p-6 rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-300 border border-gray-100 mb-10 w-full">
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <div>
              <h3 class="text-xl font-bold text-gray-900">Monthly Visits Trend</h3>
              <p class="text-xs text-gray-500 mt-1">Website traffic over 12 months</p>
            </div>
            <form method="GET" action="{{ route('admindashboard') }}" class="flex items-center gap-2">
              <label for="filterVisitorType" class="text-sm font-medium text-gray-700">Filter:</label>
              <select name="visitor_type" id="filterVisitorType" onchange="this.form.submit()" class="appearance-none bg-no-repeat bg-[right_0.5rem_center] bg-[length:1em_1em] border border-gray-300 rounded-md py-1.5 pl-3 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                <option value="all">All Types</option>
                <option value="visitor" {{ request('visitor_type') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                <option value="resident" {{ request('visitor_type') == 'resident' ? 'selected' : '' }}>Resident</option>
              </select>
            </form>
          </div>
          <div class="h-80 chart-container w-full">
            <canvas id="visitsChart"
              data-labels="{{ json_encode($labels ?? []) }}"
              data-values="{{ json_encode($data ?? []) }}"
              data-residents="{{ json_encode($residentData ?? []) }}"
              data-visitors="{{ json_encode($visitorData ?? []) }}"></canvas>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
          <div class="bg-white bg-opacity-95 backdrop-blur-sm rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="p-6 pb-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 via-transparent to-transparent">
              <h3 class="text-xl font-bold text-gray-900">Visits Report</h3>
              <p class="text-xs text-gray-500 mt-1">Detailed monthly breakdown</p>
            </div>
            <div class="overflow-x-auto max-h-[300px] data-table-wrapper">
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
              <p class="text-xs text-gray-500 mt-1">Latest {{ collect($recentPlaces)->count() }} entries</p>
            </div>
            <div class="p-6 space-y-2 max-h-[300px] overflow-y-auto data-table-wrapper">
              @if(!$recentPlaces->isEmpty())
              @foreach($recentPlaces as $index => $place)
              <a href="{{ route('admin.places.index') }}" class="flex items-center p-3 bg-gradient-to-r from-emerald-50 to-transparent rounded-lg hover:from-emerald-100 transition-all duration-200 group cursor-pointer border border-transparent hover:border-emerald-200">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 text-white text-xs font-bold mr-3 group-hover:scale-110 transition-transform duration-150 shadow-md">
                  {{ $index + 1 }}
                </span>
                <div class="flex-1 min-w-0">
                  <p class="text-gray-700 font-semibold group-hover:text-emerald-700 transition truncate text-sm">{{ $place->name }}</p>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 group-hover:text-emerald-600 transition ml-2"></i>
              </a>
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
    const visitsCtx = document.getElementById('visitsChart');
    const chartLabels = visitsCtx ? JSON.parse(visitsCtx.dataset.labels || '[]') : [];
    const chartData = visitsCtx ? JSON.parse(visitsCtx.dataset.values || '[]') : [];
    const residentDataArray = visitsCtx ? JSON.parse(visitsCtx.dataset.residents || '[]') : [];
    const visitorDataArray = visitsCtx ? JSON.parse(visitsCtx.dataset.visitors || '[]') : [];

    if (visitsCtx) {
      visitsChartInstance = new Chart(visitsCtx, {
        type: 'line',
        data: {
          labels: chartLabels,
          datasets: [{
            label: "{{ request('visitor_type') == 'resident' ? 'Resident Visits' : (request('visitor_type') == 'visitor' ? 'Visitor Visits' : 'All Visits') }}",
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
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'point',
            intersect: true
          },
          plugins: {
            filler: {
              propagate: true
            },
            tooltip: {
              animation: {
                duration: 0
              },
              displayColors: true,
              backgroundColor: 'rgba(17, 24, 39, 0.95)',
              titleColor: '#ffffff',
              bodyColor: '#f3f4f6',
              borderColor: 'rgba(99, 102, 241, 0.3)',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 8,
              titleFont: {
                size: 13,
                weight: 'bold'
              },
              bodyFont: {
                size: 12
              },
              callbacks: {
                label: function(context) {
                  const index = context.dataIndex;
                  const total = context.raw.toLocaleString();
                  const res = Number(residentDataArray[index] || 0).toLocaleString();
                  const vis = Number(visitorDataArray[index] || 0).toLocaleString();

                  return [
                    `  Residents: ${res}`,
                    `  Visitors: ${vis}`,
                    `  Total: ${total}`
                  ];
                }
              }
            },
            legend: {
              position: 'top',
              labels: {
                color: '#374151',
                font: {
                  size: 12,
                  weight: '600'
                },
                usePointStyle: true,
                padding: 20
              }
            },
          },
          scales: {
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: '#6b7280',
                font: {
                  size: 11,
                  weight: '500'
                }
              }
            },
            y: {
              beginAtZero: true,
              grid: {
                color: '#f0f0f0',
                drawBorder: false,
                lineWidth: 1
              },
              ticks: {
                color: '#6b7280',
                font: {
                  size: 11,
                  weight: '500'
                }
              }
            }
          }
        }
      });
    }

    function updateRealtime() {
      const vtype = "{{ request('visitor_type', 'all') }}";
      fetch(`{{ route('realtime.visits') }}?visitor_type=${vtype}`)
        .then(res => res.json())
        .then(json => {
          const count = json.currentMonthVisits;
          const resCount = json.currentMonthResidents;
          const visCount = json.currentMonthVisitors;
          const lastUpdatedStr = json.lastUpdated;

          const elementCount = document.getElementById('realtimeCount');
          if (elementCount && elementCount.innerText !== count.toLocaleString()) {
            elementCount.innerText = count.toLocaleString();
          }

          const elementTime = document.getElementById('lastUpdatedTime');
          if (elementTime && lastUpdatedStr) {
            elementTime.innerText = lastUpdatedStr;
            elementTime.classList.add('text-indigo-300');
            setTimeout(() => elementTime.classList.remove('text-indigo-300'), 500);
          }

          try {
            if (visitsChartInstance && visitsChartInstance.data && visitsChartInstance.data.datasets[0]) {
              const ds = visitsChartInstance.data.datasets[0];
              const labels = visitsChartInstance.data.labels || [];
              if (labels.length > 0) {
                const lastIndex = labels.length - 1;
                ds.data[lastIndex] = count;
                residentDataArray[lastIndex] = resCount;
                visitorDataArray[lastIndex] = visCount;
              } else {
                const monthLabel = new Date().toLocaleString('default', {
                  month: 'short',
                  year: '2-digit'
                });
                visitsChartInstance.data.labels.push(monthLabel);
                ds.data.push(count);
                residentDataArray.push(resCount);
                visitorDataArray.push(visCount);
              }
              visitsChartInstance.update('none');
            }
          } catch (e) {
            console.error(e);
          }
        })
        .catch(e => console.error(e));
    }

    updateRealtime();
    setInterval(updateRealtime, 15000);
  </script>

</body>

</html>