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

    <div class="flex-1 ml-48">
        @include('components.header')
        <div class="flex-1 ml-60"></div>
        <div class="flex-1"></div>

        <main class="p-6">
            @yield('content')
        </main>

        <div class="p-6">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-indigo-900">Monthly Visits Display</h2>
            </div>

            <form method="GET" action="{{ route('monthlyvisits.index') }}" class="flex flex-wrap items-center gap-4 mb-6">

                <div class="flex items-center gap-2">
                    <label for="filterLocation" class="font-medium text-gray-700 text-sm">Display:</label>
                    <select name="location" id="filterLocation" onchange="this.form.submit()" class="appearance-none bg-no-repeat bg-[right_0.5rem_center] bg-[length:1em_1em] border border-gray-300 rounded-md py-1.5 pl-3 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                        <option value="all">All</option>
                        <option value="pier" {{ request('location') == 'pier' ? 'selected' : '' }}>Pier</option>
                        <option value="port to sipaway" {{ request('location') == 'port to sipaway' ? 'selected' : '' }}>Port to Sipaway</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label for="filterMonth" class="font-medium text-gray-700 text-sm">Month:</label>
                    <select name="month" id="filterMonth" onchange="this.form.submit()" class="appearance-none bg-no-repeat bg-[right_0.5rem_center] bg-[length:1em_1em] border border-gray-300 rounded-md py-1.5 pl-3 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                        <option value="all">All</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                            @endfor
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label for="filterYear" class="font-medium text-gray-700 text-sm">Year:</label>
                    <select name="year" id="filterYear" onchange="this.form.submit()" class="appearance-none bg-no-repeat bg-[right_0.5rem_center] bg-[length:1em_1em] border border-gray-300 rounded-md py-1.5 pl-3 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                        <option value="all">All</option>
                        @foreach ($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label for="filterVisitorType" class="font-medium text-gray-700 text-sm">Type:</label>
                    <select name="visitor_type" id="filterVisitorType" onchange="this.form.submit()" class="appearance-none bg-no-repeat bg-[right_0.5rem_center] bg-[length:1em_1em] border border-gray-300 rounded-md py-1.5 pl-3 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                        <option value="all">All Types</option>
                        <option value="visitor" {{ request('visitor_type') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                        <option value="resident" {{ request('visitor_type') == 'resident' ? 'selected' : '' }}>Resident</option>
                    </select>
                </div>

                <a href="{{ route('monthlyvisits.overview') }}"
                    class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-1.5 rounded-md shadow-md transition text-sm ml-auto sm:ml-0">
                    Overview
                </a>

            </form>

            <div class="overflow-x-auto bg-white p-4 rounded-lg shadow border border-gray-200">
                <div class="w-full h-64 md:h-80 lg:h-96">
                    <canvas id="monthlyVisitsChart"
                        data-label="{{ (request('location') && request('location') !== 'all') ? 'Monthly Visitors - ' . ucwords(request('location')) : 'Monthly Visitors' }}"
                        data-labels="{{ json_encode($labels) }}"
                        data-values="{{ json_encode($data) }}"
                        data-residents="{{ json_encode($residentData) }}"
                        data-visitors="{{ json_encode($visitorData) }}"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script type="module">
        const ctx = document.getElementById('monthlyVisitsChart');

        const datasetLabel = ctx.dataset.label;
        const labels = JSON.parse(ctx.dataset.labels);
        const dataValues = JSON.parse(ctx.dataset.values);
        const residentValues = JSON.parse(ctx.dataset.residents || '[]');
        const visitorValues = JSON.parse(ctx.dataset.visitors || '[]');

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
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const total = context.raw.toLocaleString();
                                const res = Number(residentValues[index] || 0).toLocaleString();
                                const vis = Number(visitorValues[index] || 0).toLocaleString();

                                return [
                                    `  Residents: ${res}`,
                                    `  Visitors: ${vis}`,
                                    `  Total: ${total}`
                                ];
                            }
                        }
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