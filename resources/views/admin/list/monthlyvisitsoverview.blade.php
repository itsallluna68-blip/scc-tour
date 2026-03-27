<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Monthly Visits Overview</title>
    @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    @if(session('success'))
    <div id="flash-success" data-message="{{ session('success') }}" class="hidden"></div>
    @endif
    @if(session('error'))
    <div id="flash-error" data-message="{{ session('error') }}" class="hidden"></div>
    @endif

    @include('components.sidebar')

    <div class="flex-1 ml-48">
        @include('components.header')
        <div class="flex-1 ml-60"></div>
        <div class="flex-1"></div>

        <main class="p-6">
            @yield('content')
        </main>

        <div class="p-6">
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('monthlyvisits.index') }}"
                    class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 hover:text-indigo-900 shadow-sm px-3 py-1.5 rounded-md transition flex items-center justify-center" title="Back to Charts">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h2 class="text-2xl font-semibold text-indigo-900">
                    Monthly Visits Display Overview
                </h2>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full sm:w-64">
                        <label for="searchInput" class="sr-only">Search</label>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            placeholder="Search records here" autocomplete="off"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        @if(request('search'))
                        <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 flex items-center justify-center">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('monthlyvisits.overview') }}" class="flex items-center gap-2 flex-wrap">

                        <label for="filterMonth" class="sr-only">Filter by Month</label>
                        <select name="month" id="filterMonth" onchange="this.form.submit()" class="appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:1em_1em] border border-gray-300 rounded-md py-2 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                            <option value="all">All Months</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                                @endfor
                        </select>

                        <label for="filterYear" class="sr-only">Filter by Year</label>
                        <select name="year" id="filterYear" onchange="this.form.submit()" class="appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:1em_1em] border border-gray-300 rounded-md py-2 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                            <option value="all">All Years</option>
                            @foreach ($years as $yr)
                            <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>

                        <label for="filterVisitorType" class="sr-only">Filter by Visitor Type</label>
                        <select name="visitor_type" id="filterVisitorType" onchange="this.form.submit()" class="appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:1em_1em] border border-gray-300 rounded-md py-2 pl-3 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                            <option value="all">All Types</option>
                            <option value="visitor" {{ request('visitor_type') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                            <option value="resident" {{ request('visitor_type') == 'resident' ? 'selected' : '' }}>Resident</option>
                        </select>
                    </form>
                </div>

                <button onclick="openModal()" class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition text-sm flex items-center gap-2 w-full sm:w-auto justify-center">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Visit
                </button>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden mb-4">
                <table class="w-full border-collapse text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 border-b border-gray-300">
                        <tr>
                            <th class="p-3">Date Added</th>
                            <th class="p-3">Visits for Month & Year</th>
                            <th class="p-3">Location</th>
                            <th class="p-3">Visitor Type</th>
                            <th class="p-3">Total Visits</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300" id="visitsTableBody">
                        @forelse ($mvisits as $mvisit)
                        <tr class="visit-row hover:bg-gray-100 transition">
                            <td class="p-3">{{ \Carbon\Carbon::parse($mvisit->date_add)->format('Y-m-d') }}</td>
                            <td class="p-3 font-medium">{{ \Carbon\Carbon::create($mvisit->vyear, $mvisit->vmonth, 1)->format('F Y') }}</td>
                            <td class="p-3">{{ $mvisit->loc }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $mvisit->visitor_type === 'resident' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst($mvisit->visitor_type) }}
                                </span>
                            </td>
                            <td class="p-3 font-bold text-gray-800">{{ number_format($mvisit->total_visitors) }}</td>
                            <td class="p-3">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="openViewModal(this)" data-mvisit="{{ json_encode($mvisit) }}" class="text-blue-600 p-2 rounded-md transition flex items-center justify-center" title="View"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                    <button type="button" onclick="openEditModal(this)" data-mvisit="{{ json_encode($mvisit) }}" class="text-indigo-600 p-2 rounded-md transition flex items-center justify-center" title="Edit"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                    <button type="button" onclick="confirmAction('delete', '/monthlyvisits/{{ $mvisit->id }}', '{{ csrf_token() }}')" class="text-red-600 p-2 rounded-md transition flex items-center justify-center" title="Delete"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">No records found.</td>
                        </tr>
                        @endforelse
                        <tr id="jsNoResultsRow" style="display: none;">
                            <td colspan="6" class="p-4 text-center text-gray-500">No records found.</td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-4 bg-white border-t border-gray-200">
                    {{ $mvisits->links() }}
                </div>
            </div>

            <div id="addVisitsModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg w-full max-w-lg shadow-lg flex flex-col max-h-[90vh]">
                    <div class="flex justify-between items-center px-6 py-4 border-b shrink-0">
                        <h3 class="text-xl font-semibold text-indigo-900">Add Monthly Visit</h3>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition"><i data-lucide="x" class="w-5 h-5"></i></button>
                    </div>
                    <form id="addVisitsForm" action="{{ route('monthlyvisits.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                        @csrf
                        <div class="overflow-y-auto px-6 py-4 flex-1 space-y-4">
                            <div>
                                <label for="add_vdate" class="block text-sm font-medium mb-1">Select Month & Year</label>
                                <input type="month" id="add_vdate" name="vdate" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div>
                                <label for="add_location" class="block text-sm font-medium mb-1">Location</label>
                                <input type="text" id="add_location" name="location" required placeholder="Type location here..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div>
                                <label for="add_vtype" class="block text-sm font-medium mb-1">Visitor Type</label>
                                <select id="add_vtype" name="visitor_type" required class="appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:1em_1em] w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                                    <option value="visitor">Visitor</option>
                                    <option value="resident">Resident</option>
                                </select>
                            </div>
                            <div>
                                <label for="add_vcounts" class="block text-sm font-medium mb-1">Total Visits Count</label>
                                <input type="number" id="add_vcounts" name="vcounts" min="0" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                        </div>
                        <div class="sticky bottom-0 bg-white pt-4 pb-4 border-t flex justify-end gap-2 px-6">
                            <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">Cancel</button>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="editVisitsModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg w-full max-w-lg shadow-lg flex flex-col max-h-[90vh]">
                    <div class="flex justify-between items-center px-6 py-4 border-b shrink-0">
                        <h3 class="text-xl font-semibold text-indigo-900">Edit Monthly Visit</h3>
                        <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition"><i data-lucide="x" class="w-5 h-5"></i></button>
                    </div>
                    <form id="editVisitsForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
                        @csrf @method('PUT')
                        <input type="hidden" id="edit_id" name="id">
                        <div class="overflow-y-auto px-6 py-4 flex-1 space-y-4">
                            <div>
                                <label for="edit_date_visit" class="block text-sm font-medium mb-1">Visits for Month & Year</label>
                                <input type="month" id="edit_date_visit" disabled class="w-full bg-gray-100 border border-gray-300 text-gray-500 rounded-md px-3 py-2 text-sm cursor-not-allowed">
                            </div>
                            <div>
                                <label for="edit_location" class="block text-sm font-medium mb-1">Location</label>
                                <input type="text" id="edit_location" name="location" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div>
                                <label for="edit_vtype" class="block text-sm font-medium mb-1">Visitor Type</label>
                                <select id="edit_vtype" name="visitor_type" required class="appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:1em_1em] w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                                    <option value="visitor">Visitor</option>
                                    <option value="resident">Resident</option>
                                </select>
                            </div>
                            <div>
                                <label for="edit_amt_visit" class="block text-sm font-medium mb-1">Visitor Count</label>
                                <input type="number" id="edit_amt_visit" name="total_visitors" min="0" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                        </div>
                        <div class="sticky bottom-0 bg-white pt-4 pb-4 border-t flex justify-end gap-2 px-6">
                            <button type="button" onclick="closeEditModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">Cancel</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="viewVisitsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-sm rounded-xl shadow-xl p-6 relative flex flex-col">
                    <div class="flex justify-between items-center mb-4 border-b pb-2 shrink-0">
                        <h2 class="text-xl font-semibold text-indigo-900">Visit Details</h2><button type="button" onclick="closeViewModal()" class="text-gray-400 hover:text-red-500 transition"><i data-lucide="x" class="w-5 h-5"></i></button>
                    </div>
                    <div class="space-y-3 text-sm mt-2 mb-6">
                        <p><strong class="text-gray-700">Month & Year:</strong> <span id="viewMonthYear"></span></p>
                        <p><strong class="text-gray-700">Location:</strong> <span id="viewLocationText"></span></p>
                        <p><strong class="text-gray-700">Visitor Type:</strong> <span id="viewType" class="capitalize font-semibold"></span></p>
                        <p><strong class="text-gray-700">Total Visits:</strong> <span id="viewCount" class="font-bold text-indigo-700"></span></p>
                        <p><strong class="text-gray-700">Date Added:</strong> <span id="viewDateAdded" class="text-gray-500"></span></p>
                    </div>
                    <div class="flex justify-end pt-4 border-t shrink-0"><button type="button" onclick="closeViewModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition text-sm">Close</button></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            reRenderIcons();
        });

        function reRenderIcons() {
            setTimeout(() => {
                if (window.lucide) window.lucide.createIcons({
                    icons: window.lucide.icons
                });
            }, 50);
        }

        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.visit-row');
            let hasMatch = false;
            rows.forEach(row => {
                if (row.textContent.toLowerCase().includes(filter)) {
                    row.style.display = '';
                    hasMatch = true;
                } else row.style.display = 'none';
            });
            document.getElementById('jsNoResultsRow').style.display = (rows.length > 0 && !hasMatch) ? '' : 'none';
        });

        function clearSearch() {
            window.location.href = "{{ route('monthlyvisits.overview') }}";
        }

        function openModal() {
            document.getElementById('addVisitsModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('addVisitsForm').reset();
            document.getElementById('addVisitsModal').classList.add('hidden');
        }

        function openEditModal(element) {
            const data = JSON.parse(element.dataset.mvisit);
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_date_visit').value = `${data.vyear}-${String(data.vmonth).padStart(2, '0')}`;
            document.getElementById('edit_location').value = data.loc || '';
            document.getElementById('edit_vtype').value = data.visitor_type || 'visitor';
            document.getElementById('edit_amt_visit').value = data.total_visitors;
            document.getElementById('editVisitsForm').action = `/monthlyvisits/${data.id}`;
            document.getElementById('editVisitsModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editVisitsModal').classList.add('hidden');
        }

        function openViewModal(element) {
            const data = JSON.parse(element.dataset.mvisit);
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            document.getElementById('viewMonthYear').textContent = `${monthNames[data.vmonth - 1]} ${data.vyear}`;
            document.getElementById('viewLocationText').textContent = data.loc || 'N/A';
            const vTypeEl = document.getElementById('viewType');
            vTypeEl.textContent = data.visitor_type;
            vTypeEl.className = data.visitor_type === 'resident' ? 'capitalize font-semibold text-green-600' : 'capitalize font-semibold text-blue-600';
            document.getElementById('viewCount').textContent = new Intl.NumberFormat().format(data.total_visitors);
            document.getElementById('viewDateAdded').textContent = data.date_add;
            document.getElementById('viewVisitsModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewVisitsModal').classList.add('hidden');
        }
    </script>
</body>

</html>