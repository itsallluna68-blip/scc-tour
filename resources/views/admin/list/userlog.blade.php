<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Users Log</title>
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

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-indigo-900 flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-6 h-6"></i> Users Log
            </h2>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
            <div class="flex items-center gap-2 w-full md:w-1/3 relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 text-gray-400"></i>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search logs in real-time"
                    class="w-full border border-gray-300 rounded-md pl-9 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm">
            </div>

            <div class="flex items-center gap-2">
                <label for="sort" class="font-medium text-gray-700 flex items-center gap-1">
                    <i data-lucide="filter" class="w-4 h-4 text-gray-500"></i> Sort:
                </label>
                <select id="sort" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm bg-white text-sm">
                    <option value="default" selected>Default</option>
                    <option value="date">Date</option>
                    <option value="time">option>
                    <option value="action">Action</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
            <table class="w-full text-sm text-left">
                <thead class="bg-indigo-900 text-white">
                    <tr>
                        <th class="py-3 px-4 w-16">ID</th>
                        <th class="py-3 px-4">User Type</th>
                        <th class="py-3 px-4">Username</th>
                        <th class="py-3 px-4">Full Name</th>
                        <th class="py-3 px-4">Date & Time</th>
                        <th class="py-3 px-4">Action Taken</th>
                        <th class="py-3 px-4 text-center w-24">Action</th>
                    </tr>
                </thead>

                <tbody id="logTableBody" class="divide-y divide-gray-200">
                    @forelse ($userLogs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4">{{ $log->id }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-gray-100 border border-gray-200 text-gray-700 rounded text-xs font-semibold">
                                {{ $log->user_type }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $log->username }}</td>
                        <td class="py-3 px-4">{{ $log->full_name }}</td>
                        <td class="py-3 px-4 text-gray-600 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            {{ \Carbon\Carbon::parse($log->date_time)->format('M d, Y h:i A') }}
                        </td>
                        <td class="py-3 px-4 text-gray-700">{{ $log->action_taken }}</td>
                        <td class="py-3 px-4 text-center">
                            <button type="button"
                                data-id="{{ $log->id }}"
                                onclick="openDeleteModal(this)"
                                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-md transition flex items-center justify-center mx-auto"
                                title="Delete Log">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                                    <path d="M5.45 5.11 2 12v6c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                                </svg>
                                <p class="font-medium">No user logs found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="deleteLogModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white rounded-xl p-6 w-full max-w-sm shadow-2xl text-center transform transition-all">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4 mt-2">
                    <i data-lucide="triangle-alert" class="w-8 h-8 text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Delete Log Entry</h3>
                <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete this log entry? This action cannot be undone.</p>
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 px-5 py-2.5 rounded-lg text-sm font-medium transition">Cancel</button>
                    <a id="confirmDeleteLink" href="#" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm flex items-center">Delete</a>
                </div>
            </div>
        </div>

    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            startRealtimeUpdates();
        });

        document.getElementById('searchInput').addEventListener('input', applySearchFilter);

        function applySearchFilter() {
            let filter = document.getElementById('searchInput').value.toLowerCase();
            let rows = document.querySelectorAll('#logTableBody tr');

            rows.forEach(row => {
                if (row.cells.length === 1) return;

                let text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function startRealtimeUpdates() {
            setInterval(function() {
                fetch("{{ route('userlog.index') }}")
                    .then(response => response.text())
                    .then(html => {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(html, 'text/html');
                        let newTableBody = doc.getElementById('logTableBody').innerHTML;
                        document.getElementById('logTableBody').innerHTML = newTableBody;

                        applySearchFilter();
                    })
                    .catch(error => console.error(error));
            }, 5000);
        }

        const deleteModal = document.getElementById("deleteLogModal");
        const confirmDeleteLink = document.getElementById("confirmDeleteLink");

        function openDeleteModal(btnElement) {
            const id = btnElement.dataset.id;
            confirmDeleteLink.href = `/user-log/delete/${id}`;
            deleteModal.classList.remove("hidden");
        }

        function closeDeleteModal() {
            deleteModal.classList.add("hidden");
        }
    </script>
</body>

</html>