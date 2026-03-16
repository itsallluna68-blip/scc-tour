<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Monthly Visits</title>
    @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    @include('components.sidebar')

    <div class="flex-1 ml-60">
        @include('components.header')

        <main class="p-6">
            @yield('content')
        </main>
    </div>

    <main class="ml-56 mt-2 flex-1 p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-indigo-900">Monthly Visits Display</h2>
        </div>

        <form method="GET" action="{{ route('monthlyvisits.index') }}" class="flex items-center gap-4 mb-4">

            <div class="flex items-center gap-2">
                <label class="font-medium text-gray-700">Display:</label>
                <select name="location" class="border rounded px-2 py-1" onchange="this.form.submit()">
                    <option value="all">All</option>
                    <option value="pier" {{ request('location') == 'pier' ? 'selected' : '' }}>Pier</option>
                    <option value="port to sipaway" {{ request('location') == 'port to sipaway' ? 'selected' : '' }}>Port to Sipaway</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="font-medium text-gray-700">Month:</label>
                <select name="month" class="border rounded px-2 py-1" onchange="this.form.submit()">
                    <option value="all">All</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="font-medium text-gray-700">Year:</label>
                <select name="year" class="border rounded px-2 py-1" onchange="this.form.submit()">
                    <option value="all">All</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('monthlyvisits.overview') }}"
                class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-1.5 rounded-md shadow-md transition">
                Overview
            </a>

        </form>

        <div class="overflow-x-auto">
            <div class="w-full h-64 md:h-80 lg:h-96">
                <canvas id="monthlyVisitsChart"
                    data-label="{{ (request('location') && request('location') !== 'all') ? 'Monthly Visitors - ' . ucwords(request('location')) : 'Monthly Visitors' }}"
                    data-labels="{{ json_encode($labels) }}"
                    data-values="{{ json_encode($data) }}"
                ></canvas>
            </div>
        </div>

    </main>

    <script type="module">
        const ctx = document.getElementById('monthlyVisitsChart');

        const datasetLabel = ctx.dataset.label;
        const labels = JSON.parse(ctx.dataset.labels);
        const dataValues = JSON.parse(ctx.dataset.values);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: datasetLabel,
                    data: dataValues,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#6366f1',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>