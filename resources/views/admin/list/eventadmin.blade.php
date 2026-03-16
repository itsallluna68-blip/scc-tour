<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Event Management</title>
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
                <h2 class="text-2xl font-semibold text-indigo-900">Events List</h2>

                <button onclick="openEventModal()"
                    class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Event
                </button>
            </div>

            <form method="GET" action="{{ route('admin.events.index') }}" class="mb-6 flex gap-3 items-center">
                <div class="relative w-full md:w-1/3">
                    <input type="text" name="search" id="searchInput" placeholder="Search events" value="{{ request('search') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    
                    @if(request('search'))
                        <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 flex items-center justify-center">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    @endif
                </div>

                <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </form>

            @if ($errors->any())
                <div class="mb-4 text-red-600 text-sm bg-red-50 p-3 rounded-md border border-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow rounded-lg overflow-hidden">
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
                        <tr class="hover:bg-gray-100 transition">
                            <td class="p-3 font-medium">{{ $event->events }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($event->e_datetime)->format('M d, Y h:i A') }}</td>
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
                                        class="bg-blue-50 text-blue-600 hover:bg-blue-100 p-2 rounded-md transition flex items-center justify-center" title="View">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    
                                    <button type="button"
                                        data-event="{{ json_encode($event) }}"
                                        onclick="openEditEvent(this)"
                                        class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 p-2 rounded-md transition flex items-center justify-center" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>

                                    <button type="button" 
                                        data-id="{{ $event->id }}"
                                        onclick="confirmDeleteEvent(this)"
                                        class="bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded-md transition flex items-center justify-center" title="Delete">
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
                    </tbody>
                </table>
            </div>

            <div id="addEventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-3xl rounded-xl shadow-xl p-6 overflow-y-auto max-h-[90vh]">
                    <h2 class="text-xl font-semibold mb-4 text-indigo-900 border-b pb-2">Add New Event</h2>
                    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="status" value="1"> 
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Event Name</label>
                                <input type="text" name="events" required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Date & Time</label>
                                <input type="datetime-local" name="e_datetime" required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Location</label>
                                <input type="text" name="e_location" required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Event Info</label>
                                <textarea name="e_info" rows="3" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Google Map Link</label>
                                <input type="url" name="e_maplink" placeholder="https://maps.google.com/..." class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">External Event Link</label>
                                <input type="url" name="e_link" placeholder="https://example.com" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Images</label>
                                <input type="file" name="pics[]" accept="image/*" multiple class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white outline-none">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                            <button type="button" onclick="closeEventModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition text-sm">Cancel</button>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition text-sm">Save Event</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="viewEventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-3xl rounded-xl shadow-xl p-6 overflow-y-auto max-h-[90vh]">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-semibold text-indigo-900">Event Details</h2>
                        <button type="button" onclick="closeViewEventModal()" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div class="space-y-4 text-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><strong class="text-gray-700">Event Name:</strong> <p id="viewEventName" class="mt-1 bg-gray-50 p-2 rounded border"></p></div>
                            <div><strong class="text-gray-700">Date & Time:</strong> <p id="viewDatetime" class="mt-1 bg-gray-50 p-2 rounded border"></p></div>
                        </div>
                        <div><strong class="text-gray-700">Location:</strong> <p id="viewLocation" class="mt-1 bg-gray-50 p-2 rounded border"></p></div>
                        <div><strong class="text-gray-700">Event Info:</strong> <p id="viewInfo" class="mt-1 bg-gray-50 p-3 rounded border min-h-[60px]"></p></div>
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
                        <div><strong class="text-gray-700">Status:</strong> <p id="viewStatus" class="mt-1"></p></div>
                        <div>
                            <strong class="text-gray-700">Images:</strong>
                            <div id="viewImages" class="flex flex-wrap gap-2 mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="editEventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-3xl rounded-xl shadow-xl p-6 overflow-y-auto max-h-[90vh]">
                    <h2 class="text-xl font-semibold mb-4 text-indigo-900 border-b pb-2">Edit Event</h2>
                    <form id="editEventForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Event Name</label>
                                <input id="editEventName" type="text" name="events" required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Date & Time</label>
                                <input id="editEDatetime" type="datetime-local" name="e_datetime" required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Location</label>
                                <input id="editELocation" type="text" name="e_location" required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Event Info</label>
                                <textarea id="editEInfo" name="e_info" rows="3" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Google Map Link</label>
                                <input id="editMapLink" type="url" name="e_maplink" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">External Event Link</label>
                                <input id="editEventLink" type="url" name="e_link" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-2">Status</label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="editStatusToggle" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-500 relative transition">
                                        <div class="absolute top-1 left-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5 shadow-sm"></div>
                                    </div>
                                    <span id="editStatusLabel" class="ml-3 text-sm font-medium text-gray-700">Inactive</span>
                                </label>
                                <input type="hidden" name="status" id="editStatus">
                            </div>
                            <div class="md:col-span-2 border-t pt-3 mt-2">
                                <label class="block text-sm font-medium mb-2">Current Images</label>
                                <div id="editImagePreview" class="flex flex-wrap gap-2 mb-3"></div>
                                <label class="block text-sm font-medium mb-1 text-gray-500">Add More Images</label>
                                <input type="file" name="pics[]" accept="image/*" multiple class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white outline-none">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                            <button type="button" onclick="closeEditEventModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition text-sm">Cancel</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition text-sm">Update Event</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="deleteEventModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-sm shadow-lg text-center transform transition-all">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <i data-lucide="triangle-alert" class="w-8 h-8 text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Event</h3>
                    <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete this event? This action cannot be undone.</p>
                    <form id="deleteEventForm" method="POST" class="flex justify-center gap-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="closeDeleteEventModal()" class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-md text-sm font-medium transition">Cancel</button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">Delete</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            if(document.querySelector('.text-red-600 ul li')) {
                openEventModal();
            }
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#eventsTableBody tr');
            
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                if(text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function clearSearch() {
            window.location.href = "{{ route('admin.events.index') }}";
        }

        function openEventModal() { document.getElementById('addEventModal').classList.remove('hidden'); }
        function closeEventModal() { document.getElementById('addEventModal').classList.add('hidden'); }

        function openViewEvent(btn) {
            const data = JSON.parse(btn.dataset.event);
            
            document.getElementById('viewEventName').textContent = data.events;
            document.getElementById('viewDatetime').textContent = new Date(data.e_datetime).toLocaleString();
            document.getElementById('viewLocation').textContent = data.e_location;
            document.getElementById('viewInfo').textContent = data.e_info || 'No info provided.';
            
            const mapLink = document.getElementById('viewMapLink');
            if(data.e_maplink) { mapLink.href = data.e_maplink; mapLink.textContent = data.e_maplink; } else { mapLink.textContent = 'N/A'; mapLink.removeAttribute('href'); }
            
            const extLink = document.getElementById('viewExtLink');
            if(data.e_link) { extLink.href = data.e_link; extLink.textContent = data.e_link; } else { extLink.textContent = 'N/A'; extLink.removeAttribute('href'); }

            document.getElementById('viewStatus').innerHTML = data.status == 1 
                ? '<span class="text-green-600 font-semibold">Active</span>' 
                : '<span class="text-red-600 font-semibold">Inactive</span>';

            const imgContainer = document.getElementById('viewImages');
            imgContainer.innerHTML = '';
            if (data.pics && data.pics.length > 0) {
                data.pics.forEach(src => {
                    imgContainer.innerHTML += `<img src="/storage/${src}" class="w-20 h-20 object-cover rounded-md border shadow-sm">`;
                });
            } else {
                imgContainer.innerHTML = '<span class="text-gray-500 text-xs">No images uploaded.</span>';
            }

            document.getElementById('viewEventModal').classList.remove('hidden');
        }

        function closeViewEventModal() { document.getElementById('viewEventModal').classList.add('hidden'); }

        function openEditEvent(btn) {
            const data = JSON.parse(btn.dataset.event);
            const form = document.getElementById('editEventForm');
            form.action = `/admin/events/${data.id}`;

            document.getElementById('editEventName').value = data.events;
            document.getElementById('editEInfo').value = data.e_info;
            document.getElementById('editEDatetime').value = data.e_datetime;
            document.getElementById('editELocation').value = data.e_location;
            document.getElementById('editMapLink').value = data.e_maplink;
            document.getElementById('editEventLink').value = data.e_link;

            const toggle = document.getElementById('editStatusToggle');
            const hiddenStatus = document.getElementById('editStatus');
            const label = document.getElementById('editStatusLabel');

            if (data.status == 1) {
                toggle.checked = true; hiddenStatus.value = 1; label.innerText = "Active";
            } else {
                toggle.checked = false; hiddenStatus.value = 0; label.innerText = "Inactive";
            }

            toggle.onchange = function () {
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
                        <img src="/storage/${src}" class="w-20 h-20 object-cover rounded-md border shadow-sm">
                        <button type="button" onclick="removeEventImage(${data.id}, '${src}', this.parentElement)"
                            class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white w-5 h-5 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-md">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </button>
                    `;
                    preview.appendChild(wrapper);
                });
                if (typeof lucide !== 'undefined') lucide.createIcons();
            } else {
                preview.innerHTML = '<span class="text-gray-500 text-xs">No images currently uploaded.</span>';
            }

            document.getElementById('editEventModal').classList.remove('hidden');
        }

        function closeEditEventModal() { document.getElementById('editEventModal').classList.add('hidden'); }

        function confirmDeleteEvent(btn) {
            document.getElementById('deleteEventForm').action = `/admin/events/${btn.dataset.id}`;
            document.getElementById('deleteEventModal').classList.remove('hidden');
        }

        function closeDeleteEventModal() {
            document.getElementById('deleteEventModal').classList.add('hidden');
            document.getElementById('deleteEventForm').action = '';
        }

        function removeEventImage(eventId, imagePath, wrapperEl) {
            if (!confirm('Are you sure you want to delete this image?')) return;

            fetch(`/admin/events/${eventId}/remove-image`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ image: imagePath })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) wrapperEl.remove();
            });
        }
    </script>

</body>
</html>