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
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-indigo-900 flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-6 h-6"></i> Users Log
                </h2>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-2 w-full md:w-1/3 relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Search logs in real-time" class="w-full border border-gray-300 rounded-md pl-9 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm">
                </div>
            </div>

            <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
                <table class="w-full text-sm text-left">
                    <thead class="bg-indigo-900 text-white">
                        <tr>
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
                        <tr class="hover:bg-gray-50 transition log-row">
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 bg-gray-100 border border-gray-200 text-gray-700 rounded text-xs font-semibold">
                                    {{ $log->user_type }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $log->username }}</td>
                            <td class="py-3 px-4">{{ $log->full_name }}</td>
                            <td class="py-3 px-4 text-gray-600 flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($log->date_time)->format('M d, Y h:i A') }}
                            </td>
                            <td class="py-3 px-4 text-gray-700">{{ $log->action_taken }}</td>
                            <td class="py-3 px-4 text-center">
                                <button type="button" onclick="confirmSoftDelete('/user-log/delete/{{ $log->id }}', 'log entry')" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-md transition flex items-center justify-center mx-auto">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="noLogsRow">
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="inbox" class="w-10 h-10 text-gray-300 mb-2"></i>
                                    <p class="font-medium">No user logs found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        <tr id="noSearchMatchRow" style="display: none;">
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="search-x" class="w-10 h-10 text-gray-300 mb-2"></i>
                                    <p class="font-medium">No logs match your search.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $userLogs->links() }}
            </div>
        </div>
    </div>

    <script>
        function reRenderIcons() {
            setTimeout(() => {
                if (typeof window.lucide !== 'undefined') {
                    window.lucide.createIcons({
                        icons: window.lucide.icons
                    });
                }
            }, 50);
        }

        document.addEventListener("DOMContentLoaded", function() {
            reRenderIcons();
            startRealtimeUpdates();
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.log-row');
            let visibleCount = 0;

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noSearchMatchRow = document.getElementById('noSearchMatchRow');
            if (noSearchMatchRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    noSearchMatchRow.style.display = '';
                } else {
                    noSearchMatchRow.style.display = 'none';
                }
            }
        });

        function startRealtimeUpdates() {
            setInterval(function() {
                let url = "{{ route('userlog.index') }}" + window.location.search;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(html, 'text/html');
                        let newTableBody = doc.getElementById('logTableBody').innerHTML;
                        document.getElementById('logTableBody').innerHTML = newTableBody;

                        reRenderIcons();

                        document.getElementById('searchInput').dispatchEvent(new Event('input'));
                    })
                    .catch(error => console.error(error));
            }, 5000);
        }
    </script>
</body>

</html>