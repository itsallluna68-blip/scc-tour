<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Visits Overview</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="flex-1 ml-60">
        {{-- Header --}}
        @include('components.header')

        {{-- Page Content --}}
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    {{-- main section --}}
    <main class="ml-56 mt-2 flex-1 p-6">

        <div class="flex items-center gap-3 mb-4">
            <h2 class="text-2xl font-semibold text-indigo-900">
                Monthly Visits Display Overview
            </h2>

            <a href="{{ route('monthlyvisits.index') }}"
                class="bg-indigo-900 text-white px-4 py-1 rounded-md hover:bg-indigo-800 transition">
                ←
            </a>
        </div>



        <div class="flex flex-wrap items-center gap-4 mb-5">


            <!-- Search -->
           <div class="flex flex-wrap items-center justify-between gap-4 mb-5">

    <!-- Left side: Search -->
    <form method="GET" action="{{ route('monthlyvisits.overview') }}" class="flex items-center gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search by month or year"
            class="w-64 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
        <button type="submit"
            class="bg-indigo-900 text-white px-4 py-2 rounded-md hover:bg-indigo-800 transition text-sm">
            Search
        </button>
    </form>

    <!-- Center: Filters -->
    <form method="GET" action="{{ route('monthlyvisits.overview') }}" class="flex items-center gap-2 flex-wrap">

        <!-- Month -->
        <select name="month" onchange="this.form.submit()"
            class="border border-gray-300 rounded-md px-2 py-1 text-sm">
            <option value="all">All Months</option>
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                </option>
            @endfor
        </select>

        <!-- Year -->
        <select name="year" onchange="this.form.submit()"
            class="border border-gray-300 rounded-md px-2 py-1 text-sm">
            <option value="all">All Years</option>
            @foreach ($years as $yr)
                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                    {{ $yr }}
                </option>
            @endforeach
        </select>

    </form>

    <!-- Right side: Add button -->
    <button onclick="openModal()"
        class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition text-sm">
        + Add
    </button>

</div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 rounded-lg overflow-hidden text-sm">
                    <thead class="bg-indigo-900 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left w-40">Date Added</th>
                            <th class="py-3 px-4 text-left w-96">Visits for the 'Month, Year'</th>
                            <th class="py-3 px-4 text-left w-40">Location</th>
                            <th class="py-3 px-4 text-left w-32">Total Visits</th>
                            <th class="py-3 px-4 text-left w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        @foreach ($mvisits as $mvisit)
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4">{{ $mvisit->date_add }}</td>
                                <td>
                                    {{ \Carbon\Carbon::create($mvisit->vyear, $mvisit->vmonth, 1)->format('F Y') }}
                                </td>
                                <td class="py-2 px-4">{{ $mvisit->loc }}</td>
                                <td>{{ $mvisit->total_visitors }}</td>
                                <td class="py-2 px-4 flex justify-center gap-2">
                                    <button onclick='openViewModal(@json($mvisit))'
                                        class="text-blue-600 hover:text-blue-800 transition text-lg">👁️</button>

                                    <button onclick='openEditModal(@json($mvisit))'
                                        class="text-indigo-700 hover:text-indigo-900 transition text-lg">✏️</button>

                                    <button onclick="confirmDelete({{ $mvisit->id }})"
                                        class="text-yellow-600 hover:text-yellow-800 transition text-lg">🗑️</button>
                                </td>
                            </tr>
                        @endforeach
                        @if ($mvisits->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    No records found.
                                </td>
                            </tr>
                        @endif


                    </tbody>
                </table>
            </div>

            {{-- Add Modal --}}
            <div id="addVisitsModal"
                class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-indigo-900 mb-4">Add Monthly Visit Entry</h3>

                    <form id="addVisitsForm" action="{{ route('monthlyvisits.store') }}" method="POST"
                        class="space-y-4">

                        @csrf

                        {{-- Month & Year --}}
                        <div>
                            <label class="block text-sm font-medium">Select Month & Year</label>
                            <input type="month" name="vdate" value="{{ old('vdate') }}" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>

                        {{-- Location --}}
                        <div>
                            <label class="block text-sm font-medium">Location</label>
                            <select name="location" id="vlocation"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="pier">Pier</option>
                                <option value="port to sipaway">Port to Sipaway</option>
                            </select>
                        </div>

                        {{-- Total Visitors --}}
                        <div>
                            <label class="block text-sm font-medium">Total Visitors</label>
                            <input type="number" id="vcounts" name="vcounts" min="0" required
                                value="{{ old('total_visitors') }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>

                        {{-- Buttons --}}
                        <div class="flex justify-between pt-4">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                                Save
                            </button>

                            <button type="button" onclick="closeModal()"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                                Cancel
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Edit Modal --}}
            <div id="editVisitsModal"
                class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-indigo-900 mb-4">Edit Monthly Visit</h3>

                    <form id="editVisitsForm" method="POST" class="space-y-3">

                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id" name="id">

                        <div>
                            <label class="block text-sm font-medium">Visits for the Month, Year</label>
                            <<input type="month" id="edit_date_visit" disabled
                                class="w-full bg-gray-100 border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Location</label>
                            <select id="edit_location" name="location"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="pier">Pier</option>
                                <option value="port to sipaway">Port to Sipaway</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Visitor Count</label>
                            <input type="number" id="edit_amt_visit" name="total_visitors" min="0" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>

                        <div class="flex justify-between pt-4">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm">
                                Update
                            </button>

                            <button type="button" onclick="closeEditModal()"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>

                </div>
            </div>


        </div>

        <script>
            // add modal
            function openModal() {
                document.getElementById('addVisitsModal').classList.remove('hidden');
            }

            function closeModal() {
                const form = document.getElementById('addVisitsForm');
                if (form) form.reset();
                const vcountsEl = document.getElementById('vcounts');
                if (vcountsEl) vcountsEl.value = '';
                document.getElementById('addVisitsModal').classList.add('hidden');
            }

            // edit modal
            function openEditModal(data) {

                // Set hidden ID
                document.getElementById('edit_id').value = data.id;

                // Format month properly (01–12)
                let month = String(data.vmonth).padStart(2, '0');
                let formattedDate = `${data.vyear}-${month}`;

                // Set month input (if using type="month")
                document.getElementById('edit_date_visit').value = formattedDate;

                // Populate location selector if available
                if (document.getElementById('edit_location')) {
                    document.getElementById('edit_location').value = data.loc || data.location || '';
                }

                // Set total visitors
                document.getElementById('edit_amt_visit').value = data.total_visitors;

                // Set form action
                const form = document.getElementById('editVisitsForm');
                form.action = `/monthlyvisits/${data.id}`;

                // Show modal
                document.getElementById('editVisitsModal').classList.remove('hidden');
            }
            //     // Set form action dynamically
            //     const form = document.getElementById('editVisitsForm');
            //     form.action = `/monthlyvisits/${data.id}`;

            //     document.getElementById('editVisitsModal').classList.remove('hidden');
            //   }

            function closeEditModal() {
                document.getElementById('editVisitsModal').classList.add('hidden');
            }

            // VISITS CALCULATOR
            function calculateTotalVisits() {
                const localEl = document.getElementById('vlocal');
                const nationalEl = document.getElementById('vnational');
                const internationalEl = document.getElementById('vinternational');
                const vcountsEl = document.getElementById('vcounts');

                const local = localEl ? (parseInt(localEl.value) || 0) : 0;
                const national = nationalEl ? (parseInt(nationalEl.value) || 0) : 0;
                const international = internationalEl ? (parseInt(internationalEl.value) || 0) : 0;

                if (vcountsEl) vcountsEl.value = local + national + international;
            }

            if (document.getElementById('vlocal')) document.getElementById('vlocal').addEventListener('input', calculateTotalVisits);
            if (document.getElementById('vnational')) document.getElementById('vnational').addEventListener('input', calculateTotalVisits);
            if (document.getElementById('vinternational')) document.getElementById('vinternational').addEventListener('input', calculateTotalVisits);
        </script>
        <script>
            function openViewModal(data) {
                const modalContent = `
        <p><strong>Month & Year:</strong> ${data.vmonth}/${data.vyear}</p>
        <p><strong>Total Visitors:</strong> ${data.total_visitors}</p>
        <p><strong>Date Added:</strong> ${data.date_add}</p>
    `;

                // Create modal dynamically
                let modal = document.getElementById('viewVisitsModal');
                if (!modal) {
                    modal = document.createElement('div');
                    modal.id = 'viewVisitsModal';
                    modal.className = 'fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50';
                    modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-lg">
                <h3 class="text-xl font-semibold text-indigo-900 mb-4">View Monthly Visit</h3>
                <div id="viewContent">${modalContent}</div>
                <div class="flex justify-end mt-4">
                    <button onclick="closeViewModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">Close</button>
                </div>
            </div>
        `;
                    document.body.appendChild(modal);
                } else {
                    document.getElementById('viewContent').innerHTML = modalContent;
                    modal.classList.remove('hidden');
                }
            }

            function closeViewModal() {
                document.getElementById('viewVisitsModal').classList.add('hidden');
            }
        </script>

        {{-- detele script --}}
        <script>
            function confirmDelete(id) {
                if (confirm("Are you sure you want to delete this record?")) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/monthlyvisits/${id}`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>

</body>

</html>
