<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Management</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 font-sans text-gray-800">

      {{-- Sidebar --}}
    @include('components.sidebar')

     <div class="flex-1 ml-48">
      {{-- Header --}}
      @include('components.header')
        <div class="flex-1 ml-60"></div>
        <div class="flex-1"></div>
    <main class="p-6">
        @yield('content')
    </main>
<div class="p-6">

<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Events</h2>

    <button onclick="openEventModal()"
        class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition">
        + Add Event
    </button>
</div>

<form method="GET" class="mb-6 flex gap-3 items-center">

    <!-- Search -->
    <input type="text"
           name="search"
           value="{{ request(key: 'search') }}"
           placeholder="Search events..."
           class="border border-gray-300 rounded-md px-3 py-2
                  focus:outline-none focus:ring-2 focus:ring-indigo-500">

    <button type="submit"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
        Search
    </button>

    <!-- Status Filter -->
    <select name="status"
            onchange="this.form.submit()"
            class="border border-gray-300 rounded-md px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">

        <option value="">All</option>

        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
            Active
        </option>

        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
            Inactive
        </option>

    </select>

</form>

@if ($errors->any())
    <div class="mb-4 text-red-600">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', () => openEventModal());
</script>
@endif

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="p-3">Event</th>
                <th class="p-3">Date</th>
                <th class="p-3">Location</th>
                <th class="p-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr class="border-t">
                <td class="p-3">{{ $event->events }}</td>
                <td class="p-3">{{ $event->e_datetime }}</td>
                <td class="p-3">{{ $event->e_location }}</td>
                <td class="p-3 flex gap-3">
                    <button 
                    onclick='editEvent(
                        {{ $event->id }},
                        {!! json_encode($event->events) !!},
                        {!! json_encode($event->e_info) !!},
                        {!! json_encode(\Carbon\Carbon::parse($event->e_datetime)->format("Y-m-d")) !!},
                        {!! json_encode($event->e_location) !!},
                        {!! json_encode($event->e_maplink) !!},
                        {!! json_encode($event->e_link) !!},
                        {{ $event->status }},
                        {!! json_encode($event->pic0 ? (is_array($event->pic0) ? $event->pic0 : [$event->pic0]) : []) !!} 
                    )'>✏️</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="addEventModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl p-6 relative">
        <h2 class="text-2xl font-bold mb-6 text-indigo-900">Add New Event</h2>

        <form action="{{ route('admin.events.store') }}" 
              method="POST" 
              enctype="multipart/form-data">

            @csrf
            <input type="hidden" name="status" value="1"> 

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium mb-1">Event Name</label>
                    <input type="text" name="events" required
                        class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Date & Time</label>
                    <input type="date" name="e_datetime" required
                        class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Location</label>
                    <input type="text" name="e_location"
                        class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Event Info</label>
                    <textarea name="e_info" rows="3"
                        class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Google Map Link</label>
                    <input type="text" name="e_maplink"
                        placeholder="https://maps.google.com/..."
                        class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">External Event Link</label>
                    <input type="text" name="e_link"
                        placeholder="https://example.com"
                        class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Images:</label>
                    <input type="file" name="pics[]" accept="image/*" multiple
                        class="w-full border rounded-md px-3 py-2">
                    <div id="addImagePreview" class="flex mt-2"></div> {{-- thumbnails --}}
                 </div>

            </div>

            <!-- Submit -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-indigo-900 hover:bg-indigo-800 text-white px-6 py-2 rounded-md transition">
                    Save
                </button>
                <button onclick="closeEventModal()"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-md transition">
                    Cancel
                </button>
            </div>

        </form>
    </div>
</div>

<!-- ===== edit modal ===== -->
<div id="editEventModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl p-6 relative">
        <h2 class="text-2xl font-bold mb-6 text-indigo-900">Update Event</h2>

        <form id="editEventForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Event Name -->
                <div>
                    <label class="block text-sm font-medium mb-1">Event Name</label>
                    <input id="editEventName" type="text" name="events" required
                           class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Date & Time -->
                <div>
                    <label class="block text-sm font-medium mb-1">Date & Time</label>
                    <input id="editEDatetime" type="date" name="e_datetime" required
                           class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Location -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Location</label>
                    <input id="editELocation" type="text" name="e_location"
                           class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Event Info -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Event Info</label>
                    <textarea id="editEInfo" name="e_info" rows="3"
                              class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <!-- Google Map Link -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Google Map Link</label>
                    <input id="editMapLink" type="text" name="e_maplink"
                           placeholder="https://maps.google.com/..."
                           class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- External Link -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">External Event Link</label>
                    <input id="editEventLink" type="text" name="e_link"
                           placeholder="https://example.com"
                           class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Status Toggle -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="editStatusToggle" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer
                                    peer-checked:bg-green-500 relative transition">
                            <div class="absolute top-1 left-1 bg-white
                                        w-4 h-4 rounded-full transition
                                        peer-checked:translate-x-5"></div>
                        </div>
                        <span id="editStatusLabel" class="ml-3 text-sm font-medium text-gray-700">
                            Inactive
                        </span>
                    </label>
                    <input type="hidden" name="status" id="editStatus">
                </div>

                <!-- Images -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Images</label>
                    <input type="file" name="pics[]" accept="image/*" multiple
                           class="w-full border rounded-md px-3 py-2">
                    <div id="editImagePreview" class="flex mt-2"></div>
                </div>

            </div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-end gap-2">
                <button type="submit"
                        class="bg-indigo-900 hover:bg-indigo-800 text-white px-6 py-2 rounded-md transition">
                    Update
                </button>
                <button type="button" onclick="closeEditEventModal()"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-md transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div> 

</div>
</div>

<script>
    function openEventModal() {
        document.getElementById('addEventModal').classList.remove('hidden');
    }

    function closeEventModal() {
        document.getElementById('addEventModal').classList.add('hidden');
    }

    function openEditEventModal() {
        document.getElementById('editEventModal').classList.remove('hidden');
    }

    function closeEditEventModal() {
        document.getElementById('editEventModal').classList.add('hidden');
    }

    function editEvent(id, name, info, datetime, location, maplink, link, status, images) {
        const form = document.getElementById('editEventForm');
        form.action = `/admin/events/${id}`;

        document.getElementById('editEventName').value = name;
        document.getElementById('editEInfo').value = info;
        document.getElementById('editEDatetime').value = datetime;
        document.getElementById('editELocation').value = location;
        document.getElementById('editMapLink').value = maplink;
        document.getElementById('editEventLink').value = link;

        // Status toggle
        const toggle = document.getElementById('editStatusToggle');
        const hiddenStatus = document.getElementById('editStatus');
        const label = document.getElementById('editStatusLabel');

        if (status == 1) {
            toggle.checked = true;
            hiddenStatus.value = 1;
            label.innerText = "Active";
        } else {
            toggle.checked = false;
            hiddenStatus.value = 0;
            label.innerText = "Inactive";
        }

        toggle.onchange = function () {
            hiddenStatus.value = this.checked ? 1 : 0;
            label.innerText = this.checked ? "Active" : "Inactive";
        };

        // Render image thumbnails
        const preview = document.getElementById('editImagePreview');
        preview.innerHTML = '';
        if (images && images.length > 0) {
            images.forEach(src => {
                if (src) {
                    const img = document.createElement('img');
                    img.src = `${window.location.origin}/storage/${src}`;
                    img.className = 'h-20 w-20 object-cover mr-2 rounded';
                    preview.appendChild(img);
                }
            });
        }

        openEditEventModal();
    }
</script>

</body>
</html>