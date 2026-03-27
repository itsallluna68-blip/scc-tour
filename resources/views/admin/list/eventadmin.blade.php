<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Event Management</title>
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
            <h2 class="text-2xl font-semibold text-indigo-900 mb-6">Events List</h2>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <form method="GET" action="{{ route('admin.events.index') }}" class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                    <div class="relative w-full sm:w-64">
                        <label for="searchInput" class="sr-only">Search events</label>
                        <input type="text" name="search" id="searchInput" placeholder="Search events" value="{{ request('search') }}" autocomplete="off"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">

                        @if(request('search'))
                        <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 flex items-center justify-center">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                        @endif
                    </div>

                    <label for="statusFilter" class="sr-only">Filter by Status</label>
                    <select name="status" id="statusFilter" onchange="this.form.submit()" autocomplete="off"
                        class="border border-gray-300 rounded-md py-2 pl-3 pr-10 appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:1em_1em] bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </form>

                <button onclick="openEventModal()"
                    class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition flex items-center justify-center gap-2 whitespace-nowrap w-full sm:w-auto">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Event
                </button>
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
                            <th class="p-3">Event Name</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Location</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300" id="eventsTableBody">
                        @forelse($events as $event)
                        <tr class="event-row hover:bg-gray-100 transition">
                            <td class="p-3 font-medium">{{ $event->events }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($event->e_datetime)->format('M d, Y') }}</td>
                            <td class="p-3">{{ \Illuminate\Support\Str::limit($event->e_location, 30, '...') }}</td>
                            <td class="p-3">
                                @if($event->status == 1)
                                <span class="text-green-600 font-semibold">Active</span>
                                @else
                                <span class="text-red-600 font-semibold">Inactive</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                        data-event="{{ json_encode($event) }}"
                                        onclick="openViewEvent(this)"
                                        class="text-blue-600 p-2 rounded-md transition flex items-center justify-center" title="View">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>

                                    <button type="button"
                                        data-event="{{ json_encode($event) }}"
                                        onclick="openEditEvent(this)"
                                        class="text-indigo-600 p-2 rounded-md transition flex items-center justify-center" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>

                                    <button type="button"
                                        onclick="confirmAction('delete', '/admin/events/{{ $event->id }}', '{{ csrf_token() }}')"
                                        class="text-red-600 p-2 rounded-md transition flex items-center justify-center" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">No events found.</td>
                        </tr>
                        @endforelse
                        <tr id="jsNoResultsRow" style="display: none;">
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                No events found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $events->links() }}
            </div>

            <div id="addEventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-3xl rounded-xl shadow-xl p-6 overflow-y-auto max-h-[90vh] relative">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-semibold text-indigo-900">Add New Event</h2>
                        <button type="button" onclick="closeEventModal()" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="status" value="1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="addEventName" class="block text-sm font-medium mb-1">Event Name</label>
                                <input type="text" id="addEventName" name="events" required autocomplete="off" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div>
                                <label for="addEDatetime" class="block text-sm font-medium mb-1">Date</label>
                                <input type="date" id="addEDatetime" name="e_datetime" required autocomplete="off" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="addELocation" class="block text-sm font-medium mb-1">Location</label>
                                <input type="text" id="addELocation" name="e_location" required autocomplete="street-address" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="addEInfo" class="block text-sm font-medium mb-1">Event Info</label>
                                <textarea id="addEInfo" name="e_info" rows="3" autocomplete="off" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label for="addMapLink" class="block text-sm font-medium mb-1">Google Map Link</label>
                                <input type="url" id="addMapLink" name="e_maplink" placeholder="https://maps.google.com/..." autocomplete="url" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="addEventLink" class="block text-sm font-medium mb-1">External Event Link</label>
                                <input type="url" id="addEventLink" name="e_link" placeholder="https://example.com" autocomplete="url" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <span class="block text-sm font-medium mb-1">Images</span>
                                <div id="galleryPreview" class="flex flex-wrap gap-2 mb-2"></div>
                                <label for="galleryInput" class="sr-only">Upload Images</label>
                                <input type="file" name="pics[]" accept="image/*" multiple id="galleryInput" class="hidden">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                            <button type="button" onclick="closeEventModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition text-sm">Cancel</button>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md transition text-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="viewEventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-3xl rounded-xl shadow-xl p-6 overflow-y-auto max-h-[90vh] flex flex-col">
                    <div class="flex justify-between items-center mb-4 border-b pb-2 shrink-0">
                        <h2 class="text-xl font-semibold text-indigo-900">Event Details</h2>
                        <button type="button" onclick="closeViewEventModal()" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div class="space-y-4 text-sm overflow-y-auto pr-2 flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><strong class="text-gray-700">Event Name:</strong>
                                <p id="viewEventName" class="mt-1 bg-gray-50 p-2 rounded border"></p>
                            </div>
                            <div><strong class="text-gray-700">Date:</strong>
                                <p id="viewDatetime" class="mt-1 bg-gray-50 p-2 rounded border"></p>
                            </div>
                        </div>
                        <div><strong class="text-gray-700">Location:</strong>
                            <p id="viewLocation" class="mt-1 bg-gray-50 p-2 rounded border"></p>
                        </div>
                        <div><strong class="text-gray-700">Event Info:</strong>
                            <p id="viewInfo" class="mt-1 bg-gray-50 p-3 rounded border min-h-[60px]"></p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <strong class="text-gray-700">Map Link:</strong>
                                <a id="viewMapLink" href="#" target="_blank" class="block mt-1 text-blue-600 hover:underline truncate bg-gray-50 p-2 rounded border"></a>
                            </div>
                            <div>
                                <strong class="text-gray-700">External Link:</strong>
                                <a id="viewExtLink" href="#" target="_blank" class="block mt-1 text-blue-600 hover:underline truncate bg-gray-50 p-2 rounded border"></a>
                            </div>
                        </div>
                        <div><strong class="text-gray-700">Status:</strong>
                            <p id="viewStatus" class="mt-1"></p>
                        </div>
                        <div>
                            <strong class="text-gray-700">Images:</strong>
                            <div id="viewImages" class="flex flex-wrap gap-2 mt-2"></div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t shrink-0">
                        <button type="button" onclick="closeViewEventModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <div id="editEventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-3xl rounded-xl shadow-xl p-6 overflow-y-auto max-h-[90vh] relative">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-semibold text-indigo-900">Edit Event</h2>
                        <button type="button" onclick="closeEditEventModal()" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form id="editEventForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editEventName" class="block text-sm font-medium mb-1">Event Name</label>
                                <input id="editEventName" type="text" name="events" required autocomplete="off" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div>
                                <label for="editEDatetime" class="block text-sm font-medium mb-1">Date</label>
                                <input id="editEDatetime" type="date" name="e_datetime" required autocomplete="off" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="editELocation" class="block text-sm font-medium mb-1">Location</label>
                                <input id="editELocation" type="text" name="e_location" required autocomplete="street-address" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="editEInfo" class="block text-sm font-medium mb-1">Event Info</label>
                                <textarea id="editEInfo" name="e_info" rows="3" autocomplete="off" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label for="editMapLink" class="block text-sm font-medium mb-1">Google Map Link</label>
                                <input id="editMapLink" type="url" name="e_maplink" autocomplete="url" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="editEventLink" class="block text-sm font-medium mb-1">External Event Link</label>
                                <input id="editEventLink" type="url" name="e_link" autocomplete="url" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <span class="block text-sm font-medium mb-2">Status</span>
                                <label for="editStatusToggle" class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="editStatusToggle" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                    <span id="editStatusLabel" class="ml-3 text-sm font-medium text-gray-700">Inactive</span>
                                </label>
                                <input type="hidden" name="status" id="editStatus">
                            </div>
                            <div class="md:col-span-2 border-t pt-3 mt-2">
                                <span class="block text-sm font-medium mb-2 text-indigo-900">Current Photos</span>
                                <div id="editImagePreview" class="flex flex-wrap gap-2 mb-3"></div>
                                <span class="block text-sm font-medium mb-1">Add More Images</span>
                                <div id="editGalleryPreview" class="flex flex-wrap gap-2 mb-2"></div>
                                <label for="editGalleryInput" class="sr-only">Upload Images</label>
                                <input type="file" name="pics[]" accept="image/*" multiple id="editGalleryInput" class="hidden">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                            <button type="button" onclick="closeEditEventModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition text-sm">Cancel</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md transition text-sm">Update</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            reRenderIcons();

            if (document.querySelector('.text-red-600 ul li')) {
                openEventModal();
            }
        });

        function reRenderIcons() {
            setTimeout(() => {
                if (window.lucide) {
                    window.lucide.createIcons({
                        icons: window.lucide.icons
                    });
                }
            }, 50);
        }

        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.event-row');
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
            if (rows.length > 0) {
                if (!hasMatch) {
                    noResultsRow.style.display = '';
                } else {
                    noResultsRow.style.display = 'none';
                }
            }
        });

        function clearSearch() {
            window.location.href = "{{ route('admin.events.index') }}";
        }

        function openEventModal() {
            document.getElementById('addEventModal').classList.remove('hidden');
        }

        function closeEventModal() {
            document.getElementById('addEventModal').classList.add('hidden');
        }

        function openViewEvent(btn) {
            const data = JSON.parse(btn.dataset.event);

            document.getElementById('viewEventName').textContent = data.events;

            const dateObj = new Date(data.e_datetime);
            const formattedDate = dateObj.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
            document.getElementById('viewDatetime').textContent = formattedDate;

            document.getElementById('viewLocation').textContent = data.e_location;
            document.getElementById('viewInfo').textContent = data.e_info || 'No info provided.';

            const mapLink = document.getElementById('viewMapLink');
            if (data.e_maplink) {
                mapLink.href = data.e_maplink;
                mapLink.textContent = data.e_maplink;
            } else {
                mapLink.textContent = 'N/A';
                mapLink.removeAttribute('href');
            }

            const extLink = document.getElementById('viewExtLink');
            if (data.e_link) {
                extLink.href = data.e_link;
                extLink.textContent = data.e_link;
            } else {
                extLink.textContent = 'N/A';
                extLink.removeAttribute('href');
            }

            document.getElementById('viewStatus').innerHTML = data.status == 1 ?
                '<span class="text-green-600 font-semibold bg-green-50 px-2 py-1 rounded">Active</span>' :
                '<span class="text-red-600 font-semibold bg-red-50 px-2 py-1 rounded">Inactive</span>';

            const imgContainer = document.getElementById('viewImages');
            imgContainer.innerHTML = '';
            if (data.pics && data.pics.length > 0) {
                data.pics.forEach(src => {
                    imgContainer.innerHTML += `<img src="{{ Storage::disk('s3')->url('') }}${src}" class="w-20 h-20 object-cover rounded-md border shadow-sm">`;
                });
            } else {
                imgContainer.innerHTML = '<span class="text-gray-500 text-xs">No images uploaded.</span>';
            }

            document.getElementById('viewEventModal').classList.remove('hidden');
        }

        function closeViewEventModal() {
            document.getElementById('viewEventModal').classList.add('hidden');
        }

        function openEditEvent(btn) {
            const data = JSON.parse(btn.dataset.event);
            const form = document.getElementById('editEventForm');
            form.action = `/admin/events/${data.id}`;

            document.getElementById('editEventName').value = data.events;
            document.getElementById('editEInfo').value = data.e_info;

            const dateOnly = data.e_datetime ? data.e_datetime.split(' ')[0] : '';
            document.getElementById('editEDatetime').value = dateOnly;

            document.getElementById('editELocation').value = data.e_location;
            document.getElementById('editMapLink').value = data.e_maplink;
            document.getElementById('editEventLink').value = data.e_link;

            const toggle = document.getElementById('editStatusToggle');
            const hiddenStatus = document.getElementById('editStatus');
            const label = document.getElementById('editStatusLabel');

            if (data.status == 1) {
                toggle.checked = true;
                hiddenStatus.value = 1;
                label.innerText = "Active";
            } else {
                toggle.checked = false;
                hiddenStatus.value = 0;
                label.innerText = "Inactive";
            }

            toggle.onchange = function() {
                hiddenStatus.value = this.checked ? 1 : 0;
                label.innerText = this.checked ? "Active" : "Inactive";
            };

            const preview = document.getElementById('editImagePreview');
            preview.innerHTML = '';
            if (data.pics && data.pics.length > 0) {
                data.pics.forEach(src => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative w-20 h-20 group';

                    wrapper.innerHTML = `
                        <img src="{{ Storage::disk('s3')->url('') }}${src}" class="w-20 h-20 object-cover rounded-md border shadow-sm">
                        <button type="button" onclick="removeEventImage(${data.id}, '${src}', this.parentElement)"
                            class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white w-5 h-5 rounded-full flex items-center justify-center shadow-md transition">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </button>
                    `;
                    preview.appendChild(wrapper);
                });
                reRenderIcons();
            } else {
                preview.innerHTML = '<span class="text-gray-500 text-xs">No images currently uploaded.</span>';
            }

            editGalleryFiles = [];
            renderEditGallery();

            document.getElementById('editEventModal').classList.remove('hidden');
        }

        function closeEditEventModal() {
            document.getElementById('editEventModal').classList.add('hidden');
        }

        function removeEventImage(eventId, imagePath, wrapperEl) {
            wrapperEl.remove();
            fetch(`/admin/events/${eventId}/remove-image`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        image: imagePath
                    })
                })
                .catch(err => console.error(err));
        }

        const galleryInput = document.getElementById('galleryInput');
        const galleryPreview = document.getElementById('galleryPreview');
        let galleryFiles = [];

        function renderGallery() {
            galleryPreview.innerHTML = '';
            galleryFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative w-20 h-20';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 object-cover rounded-md shadow border border-gray-300';
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.innerHTML = '<i data-lucide="x" class="w-3 h-3"></i>';
                    removeBtn.className = 'absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center shadow-md';
                    removeBtn.onclick = (event) => {
                        event.stopPropagation();
                        galleryFiles.splice(index, 1);
                        const dt = new DataTransfer();
                        galleryFiles.forEach(f => dt.items.add(f));
                        galleryInput.files = dt.files;
                        renderGallery();
                    };
                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    galleryPreview.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
            const addBtn = document.createElement('div');
            addBtn.className = 'w-20 h-20 flex items-center justify-center border-2 border-dashed border-gray-400 hover:border-indigo-500 bg-gray-50 hover:bg-gray-100 rounded-md cursor-pointer text-gray-400 hover:text-indigo-500 transition';
            addBtn.innerHTML = '<i data-lucide="plus" class="w-6 h-6"></i>';
            addBtn.onclick = () => galleryInput.click();
            galleryPreview.appendChild(addBtn);
            reRenderIcons();
        }

        if (galleryInput) {
            galleryInput.addEventListener('change', (e) => {
                Array.from(e.target.files).forEach(file => galleryFiles.push(file));
                const dt = new DataTransfer();
                galleryFiles.forEach(file => dt.items.add(file));
                galleryInput.files = dt.files;
                renderGallery();
            });
            renderGallery();
        }

        const editGalleryInput = document.getElementById('editGalleryInput');
        const editGalleryPreview = document.getElementById('editGalleryPreview');
        let editGalleryFiles = [];

        function renderEditGallery() {
            if (!editGalleryPreview) return;
            editGalleryPreview.innerHTML = '';
            editGalleryFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative w-20 h-20';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 object-cover rounded-md shadow border border-gray-300';
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.innerHTML = '<i data-lucide="x" class="w-3 h-3"></i>';
                    removeBtn.className = 'absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center shadow-md';
                    removeBtn.onclick = (event) => {
                        event.stopPropagation();
                        editGalleryFiles.splice(index, 1);
                        const dt = new DataTransfer();
                        editGalleryFiles.forEach(f => dt.items.add(f));
                        editGalleryInput.files = dt.files;
                        renderEditGallery();
                    };
                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    editGalleryPreview.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
            const addBtn = document.createElement('div');
            addBtn.className = 'w-20 h-20 flex items-center justify-center border-2 border-dashed border-gray-400 hover:border-indigo-500 bg-gray-50 hover:bg-gray-100 rounded-md cursor-pointer text-gray-400 hover:text-indigo-500 transition';
            addBtn.innerHTML = '<i data-lucide="plus" class="w-6 h-6"></i>';
            addBtn.onclick = () => editGalleryInput.click();
            editGalleryPreview.appendChild(addBtn);
            reRenderIcons();
        }

        if (editGalleryInput) {
            editGalleryInput.addEventListener('change', (e) => {
                Array.from(e.target.files).forEach(file => editGalleryFiles.push(file));
                const dt = new DataTransfer();
                editGalleryFiles.forEach(file => dt.items.add(file));
                editGalleryInput.files = dt.files;
                renderEditGallery();
            });
            renderEditGallery();
        }
    </script>

</body>

</html>