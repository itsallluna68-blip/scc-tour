<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Monthly Visits Overview</title>
    @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    @include('components.sidebar')

    <div class="flex-1 ml-48">
        @include('components.header')

        <main class="p-6 mt-16">
            @yield('content')
        </main>

    <main class="p-6 flex-1">

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

            <div class="flex flex-wrap items-center justify-between gap-4 mb-5 w-full">

                <form method="GET" action="{{ route('monthlyvisits.overview') }}" class="flex items-center gap-2 flex-wrap">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by month or year"
                        class="w-64 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <button type="submit"
                        class="bg-indigo-900 text-white px-4 py-2 rounded-md hover:bg-indigo-800 transition text-sm">
                        Search
                    </button>
                </form>

                <form method="GET" action="{{ route('monthlyvisits.overview') }}" class="flex items-center gap-2 flex-wrap">

                    <select name="month" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-md px-2 py-1 text-sm">
                        <option value="all">All Months</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>

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

                <button onclick="openModal()"
                    class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition text-sm flex items-center gap-1">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add
                </button>

            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full border border-gray-300 rounded-lg overflow-hidden text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="py-3 px-4 text-left w-40">Date Added</th>
                            <th class="py-3 px-4 text-left w-96">Visits for the Month, Year</th>
                            <th class="py-3 px-4 text-left w-40">Location</th>
                            <th class="py-3 px-4 text-left w-32">Total Visits</th>
                            <th class="py-3 px-4 text-center w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        @foreach ($mvisits as $mvisit)
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4">{{ $mvisit->date_add }}</td>
                                <td class="py-2 px-4">
                                    {{ \Carbon\Carbon::create($mvisit->vyear, $mvisit->vmonth, 1)->format('F Y') }}
                                </td>
                                <td class="py-2 px-4">{{ $mvisit->loc }}</td>
                                <td class="py-2 px-4">{{ $mvisit->total_visitors }}</td>
                                <td class="py-2 px-4 flex gap-3 justify-center">
                                    <button type="button"
                                        onclick="openViewModal(this)"
                                        data-mvisit="{{ json_encode($mvisit) }}"
                                        class="text-indigo-600 hover:text-indigo-800 transition flex items-center justify-center">
                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                    </button>

                                    <button type="button" 
                                        onclick="openEditModal(this)"
                                        data-mvisit="{{ json_encode($mvisit) }}"
                                        class="text-indigo-700 hover:text-indigo-900 transition flex items-center justify-center">
                                        <i data-lucide="edit" class="w-5 h-5"></i>
                                    </button>

                                    <button type="button" 
                                        onclick="confirmDelete(this)"
                                        data-id="{{ $mvisit->id }}"
                                        class="text-red-600 hover:text-red-800 transition flex items-center justify-center">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
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

            <div id="addVisitsModal"
                class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-lg">
                    <h3 class="text-xl font-semibold text-indigo-900 mb-4">Add Monthly Visit Entry</h3>

                    <form id="addVisitsForm" action="{{ route('monthlyvisits.store') }}" method="POST"
                        class="space-y-4">

                        @csrf

                        <div>
                            <label class="block text-sm font-medium">Select Month & Year</label>
                            <input type="month" name="vdate" value="{{ old('vdate') }}" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Location</label>
                            <select name="location" id="vlocation"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="pier">Pier</option>
                                <option value="port to sipaway">Port to Sipaway</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Total Visitors</label>
                            <input type="number" id="vcounts" name="vcounts" min="0" required
                                value="{{ old('total_visitors') }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <div class="flex justify-between pt-4">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm transition">
                                Save
                            </button>

                            <button type="button" onclick="closeModal()"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm transition">
                                Cancel
                            </button>
                        </div>

                    </form>
                </div>
            </div>

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
                            <input type="month" id="edit_date_visit" disabled
                                class="w-full bg-gray-100 border border-gray-300 rounded-md px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Location</label>
                            <select id="edit_location" name="location"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="pier">Pier</option>
                                <option value="port to sipaway">Port to Sipaway</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Visitor Count</label>
                            <input type="number" id="edit_amt_visit" name="total_visitors" min="0" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>

                        <div class="flex justify-between pt-4">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm transition">
                                Update
                            </button>

                            <button type="button" onclick="closeEditModal()"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm transition">
                                Cancel
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <div id="deleteVisitsModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-sm shadow-lg text-center transform transition-all">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <i data-lucide="triangle-alert" class="w-8 h-8 text-red-600"></i>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Record</h3>
                    <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete this record? This action cannot be undone.</p>
                    
                    <form id="deleteVisitsForm" method="POST" class="flex justify-center gap-3">
                        @csrf
                        @method('DELETE')
                        
                        <button type="button" onclick="closeDeleteModal()"
                            class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-md text-sm font-medium transition">
                            Cancel
                        </button>
                        
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();

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

        function openEditModal(element) {
            const data = JSON.parse(element.dataset.mvisit);

            document.getElementById('edit_id').value = data.id;

            let month = String(data.vmonth).padStart(2, '0');
            let formattedDate = `${data.vyear}-${month}`;
            document.getElementById('edit_date_visit').value = formattedDate;
            
            if (document.getElementById('edit_location')) {
                document.getElementById('edit_location').value = data.loc || data.location || '';
            }

            document.getElementById('edit_amt_visit').value = data.total_visitors;

            const form = document.getElementById('editVisitsForm');
            form.action = `/monthlyvisits/${data.id}`;

            document.getElementById('editVisitsModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editVisitsModal').classList.add('hidden');
        }

        function openViewModal(element) {
            const data = JSON.parse(element.dataset.mvisit);
            
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            const monthName = monthNames[data.vmonth - 1] || data.vmonth;

            const modalContent = `
                <div class="space-y-3">
                    <p class="text-gray-700"><strong class="text-indigo-900">Month & Year:</strong> ${monthName} ${data.vyear}</p>
                    <p class="text-gray-700"><strong class="text-indigo-900">Total Visitors:</strong> ${data.total_visitors}</p>
                    <p class="text-gray-700"><strong class="text-indigo-900">Location:</strong> ${data.loc || data.location || 'N/A'}</p>
                    <p class="text-gray-700"><strong class="text-indigo-900">Date Added:</strong> ${data.date_add}</p>
                </div>
            `;

            let modal = document.getElementById('viewVisitsModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'viewVisitsModal';
                modal.className = 'fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg p-6 w-full max-w-sm shadow-lg">
                        <h3 class="text-xl font-semibold text-indigo-900 mb-4 border-b pb-2">View Visit Details</h3>
                        <div id="viewContent">${modalContent}</div>
                        <div class="flex justify-end mt-6">
                            <button onclick="closeViewModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm transition">Close</button>
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

        function confirmDelete(element) {
            const id = element.dataset.id;
            const form = document.getElementById('deleteVisitsForm');
            form.action = `/monthlyvisits/${id}`;
            document.getElementById('deleteVisitsModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteVisitsModal').classList.add('hidden');
            const form = document.getElementById('deleteVisitsForm');
            form.action = '';
        }
    </script>

</body>

</html>