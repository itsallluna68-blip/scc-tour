<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Reviews Overview</title>
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
            <h2 class="text-2xl font-semibold text-indigo-900 mb-6">
                Manage Reviews
            </h2>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                    <div class="relative w-full sm:w-64">
                        <label for="searchInput" class="sr-only">Search reviews</label>
                        <input type="text" name="search" id="searchInput" placeholder="Search reviews" autocomplete="off"
                            value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        @if(request('search'))
                        <button type="button" data-url="{{ route('admin.reviews.index', ['status' => request('status')]) }}" onclick="window.location.href=this.dataset.url" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 text-sm flex items-center justify-center">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                        @endif
                    </div>

                    <label for="statusFilter" class="sr-only">Filter by Status</label>
                    <select name="status" id="statusFilter" onchange="this.form.submit()" autocomplete="off"
                        class="border border-gray-300 rounded-md py-2 pl-3 pr-10 appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:1em_1em] bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Deactivated" {{ request('status') == 'Deactivated' ? 'selected' : '' }}>Deactivated</option>
                    </select>
                </form>
            </div>

            @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm bg-red-50 p-3 rounded-md border border-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden mb-4">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3">Place</th>
                            <th class="p-3">Reviewer</th>
                            <th class="p-3">Rating</th>
                            <th class="p-3">Feedback</th>
                            <th class="p-3">Photos</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300" id="reviewsTableBody">
                        @forelse ($reviews as $review)
                        <tr class="review-row hover:bg-gray-100 transition">
                            <td class="p-3 font-medium">{{ $review->place->name ?? 'N/A' }}</td>
                            <td class="p-3">{{ $review->name }}</td>
                            <td class="p-3">
                                <span class="text-yellow-500 font-bold">★ {{ $review->ratings }}</span>
                            </td>
                            <td class="p-3 max-w-xs truncate" title="{{ $review->feedback }}">{{ $review->feedback }}</td>
                            <td class="p-3">
                                <div class="flex gap-1">
                                    @for($i=0; $i<=2; $i++)
                                        @php $pic='rpic' .$i; @endphp
                                        @if($review->$pic)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('s3')->url($review->$pic) }}" class="w-8 h-8 object-cover rounded border border-gray-200 shadow-sm">
                                        @endif
                                        @endfor
                                </div>
                            </td>
                            <td class="p-3">
                                @if(strtolower($review->status) === 'approved')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">{{ ucfirst($review->status) }}</span>
                                @elseif(strtolower($review->status) === 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">{{ ucfirst($review->status) }}</span>
                                @elseif(strtolower($review->status) === 'deactivated')
                                <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs font-bold">{{ ucfirst($review->status) }}</span>
                                @else
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold">{{ ucfirst($review->status) }}</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex items-center justify-center gap-2">
                                    @if(strtolower($review->status) !== 'approved')
                                    <button type="button" data-url="{{ route('admin.reviews.approve', $review->rid) }}" data-token="{{ csrf_token() }}" onclick="confirmAction('approve', this.dataset.url, this.dataset.token)" class="text-green-600 p-2 rounded-md hover:bg-green-100 transition flex items-center justify-center" title="Approve / Activate">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                    @if(strtolower($review->status) === 'approved')
                                    <button type="button" data-url="{{ route('admin.reviews.deactivate', $review->rid) }}" data-token="{{ csrf_token() }}" onclick="confirmAction('deactivate', this.dataset.url, this.dataset.token)" class="text-yellow-600 p-2 rounded-md hover:bg-yellow-100 transition flex items-center justify-center" title="Deactivate / Hide">
                                        <i data-lucide="eye-off" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                    <button type="button" data-url="{{ route('admin.reviews.destroy', $review->rid) }}" data-token="{{ csrf_token() }}" onclick="confirmAction('forceDelete', this.dataset.url, this.dataset.token, 'review')" class="text-red-600 p-2 rounded-md hover:bg-red-100 transition flex items-center justify-center" title="Delete Permanently">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">No reviews found.</td>
                        </tr>
                        @endforelse
                        <tr id="jsNoResultsRow" style="display: none;">
                            <td colspan="7" class="p-4 text-center text-gray-500">
                                No reviews found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $reviews->links() }}
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

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    let filter = this.value.toLowerCase();
                    let rows = document.querySelectorAll('.review-row');
                    let hasMatch = false;

                    rows.forEach(row => {
                        let text = row.textContent.toLowerCase();
                        if (text.includes(filter)) {
                            row.style.display = '';
                            hasMatch = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const noResultsRow = document.getElementById('jsNoResultsRow');
                    if (noResultsRow) {
                        if (rows.length > 0 && !hasMatch) {
                            noResultsRow.style.display = '';
                        } else {
                            noResultsRow.style.display = 'none';
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>