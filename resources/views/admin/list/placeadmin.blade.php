<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Places Overview</title>
    @vite('resources/css/app.css')
    <th class="p-3">Image</th>
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

        <main class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-indigo-900">
                    Places List
                </h2>

                <button onclick="openPlaceModal()"
                    class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition">
                    + Add Place
                </button>
            </div>

            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('admin.places.index') }}" class="flex items-center gap-2 mb-6">

                <div class="relative w-full md:w-1/3">
                    <input type="text" name="search" id="searchInput" placeholder="Search place..."
                        value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500
                                  focus:border-indigo-500 transition">
                    @if(request('search'))
                        <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2
                                                               text-gray-400 hover:text-red-500 text-sm">
                            ✕
                        </button>
                    @endif
                </div>

                <button type="submit"
                    class="bg-indigo-900 text-white px-4 py-2 rounded-md hover:bg-indigo-800 transition">
                    Search
                </button>

                <select name="status" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>


            </form>

            {{-- Places Table --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3">ID</th>
                            <th class="p-3">Name</th>
                            <th class="p-3">Categories</th>
                            <th class="p-3">Contact</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Popular</th>
                            <th class="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        @foreach($places as $place)
                            <tr class="hover:bg-gray-100">
                                <td>{{ $place->id }}</td>
                                <td>{{ $place->name }}</td>
                                <td class="p-3">
                                    @if($place->categories && $place->categories->count())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($place->categories as $cat)
                                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded">
                                                    {{ $cat->category }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $place->contact }}</td>
                                <td>
                                    @if($place->status == 1)
                                        <span class="text-green-600 font-semibold">Active</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($place->is_popular == 1)
                                        <span class="text-yellow-500 font-semibold">Yes</span>
                                    @else
                                        <span class="text-gray-500 font-semibold">No</span>
                                    @endif
                                </td>
                                <td class="flex gap-2 justify-center">
                                    <button onclick='openEditModal(@json($place))'
                                        class="text-indigo-600 hover:text-indigo-800 text-lg">
                                        ✏️
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>



            {{-- ADD MODAL --}}
            <div id="addPlaceModal"
                class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg w-full max-w-2xl shadow-lg flex flex-col max-h-[90vh]">

                    <h3 class="text-xl font-semibold text-indigo-900 mb-4">Add Place</h3>

                    <form id="addPlaceForm" method="POST" action="{{ route('admin.places.store') }}"
                        enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">

                        @csrf

                        <div class="overflow-y-auto flex-1 px-6 py-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Name of Place</label>
                                <input type="text" name="name" required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Address</label>
                                <input type="text" name="address"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Contact</label>
                                    <input type="text" name="contact"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Email</label>
                                    <input type="email" name="email"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Web Link 1</label>
                                    <input type="text" name="link1"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Web Link 2</label>
                                    <input type="text" name="link2"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Map Link</label>
                                <input type="text" name="map_link"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Opening Hours</label>
                                <input type="text" name="opening_hours"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">How to Get There</label>
                                <textarea name="transport" rows="4"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Description</label>
                                <textarea name="description" rows="4"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">History</label>
                                <textarea name="history" rows="4"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                            </div>

                            {{-- IMAGE PREVIEW --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">Main Image (Cover)</label>
                                <div id="mainImagePreview"
                                    class="w-32 h-32 mb-2 border rounded-md flex items-center justify-center text-gray-400 cursor-pointer">
                                    +
                                </div>
                                <input type="file" name="main_image" id="mainImageInput" accept="image/*"
                                    class="hidden">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Gallery Images</label>
                                <div id="galleryPreview" class="flex flex-wrap gap-2 mb-2"></div>
                                <input type="file" name="images[]" id="galleryInput" accept="image/*" multiple
                                    class="hidden">
                            </div>


                            <div class="flex items-center gap-6 pt-2">
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="status" value="1" checked
                                        class="border-gray-300 rounded">
                                    <span class="text-sm font-medium text-gray-700">Active</span>
                                </label>

                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="is_popular" value="1" class="border-gray-300 rounded">
                                    <span class="text-sm font-medium text-gray-700">Popular</span>
                                </label>
                            </div>

                            {{-- cocogrove 4 devs 2-21 --}}
                            <div>
                                <label class="block text-sm font-medium mb-3">Categories</label>
                                <div id="add_categories_list"
                                    class="grid grid-cols-3 gap-2 p-4 border border-gray-300 rounded-md bg-gray-50">
                                    @foreach($categories as $category)
                                        <label
                                            class="flex items-center gap-3 cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                            <input type="checkbox" name="categories[]" value="{{ $category->cid }}"
                                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <span class="text-sm font-medium text-gray-700">{{ $category->category }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="sticky bottom-0 bg-white pt-4 border-t flex justify-end gap-2 px-6">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                                Save
                            </button>
                            <button type="button" onclick="closePlaceModal()"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- perfect monthsary 222 --}}
            {{-- ai argument --}}
            <!-- EDIT PLACE MODAL -->
<div id="editPlaceModal"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">

    <div class="bg-white rounded-lg p-6 w-full max-w-3xl shadow-lg overflow-y-auto max-h-[90vh]">

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-indigo-900">Edit Place</h3>
            <button type="button" onclick="closeEditModal()" class="text-red-500 text-sm">Close</button>
        </div>

        <form id="editPlaceForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">

            <div class="space-y-4">

                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" id="edit_name"
                           class="w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Address</label>
                    <input type="text" name="address" id="edit_address"
                           class="w-full border rounded-md px-3 py-2">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Contact</label>
                        <input type="text" name="contact" id="edit_contact"
                               class="w-full border rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" id="edit_email"
                               class="w-full border rounded-md px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Web Link 1</label>
                        <input type="text" name="link1" id="edit_link1"
                               class="w-full border rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Web Link 2</label>
                        <input type="text" name="link2" id="edit_link2"
                               class="w-full border rounded-md px-3 py-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Map Link</label>
                    <input type="text" name="map_link" id="edit_map_link"
                           class="w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Opening Hours</label>
                    <input type="text" name="opening_hours" id="edit_opening_hours"
                           class="w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" id="edit_description" rows="4"
                              class="w-full border rounded-md px-3 py-2"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">History</label>
                    <textarea name="history" id="edit_history" rows="4"
                              class="w-full border rounded-md px-3 py-2"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">How to Get There</label>
                    <textarea name="transport" id="edit_transport" rows="4"
                              class="w-full border rounded-md px-3 py-2"></textarea>
                </div>

                <div class="flex gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="status" id="edit_status">
                        <span>Active</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_popular" id="edit_is_popular">
                        <span>Popular</span>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-3">Categories</label>
                    <div id="edit_categories_list" class="grid grid-cols-3 gap-2 p-4 border border-gray-300 rounded-md bg-gray-50">
                        @foreach($categories as $category)
                            <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                <input type="checkbox" name="categories[]" value="{{ $category->cid }}"
                                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="text-sm font-medium text-gray-700">{{ $category->category }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- IMAGE UPLOAD -->
                <div>
                    <label class="block text-sm font-medium mb-1">Photos</label>

                    <div id="view_images" class="flex flex-wrap gap-3 mb-2">
                        <!-- Existing images will be loaded here via JS -->
                    </div>

                    <p>Replace main image:</p>
                    <input type="file" name="main_image" accept="image/*"
                           class="w-full border rounded-md px-3 py-2 mt-2">

                    <p>Add gallery:</p>
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="w-full border rounded-md px-3 py-2 mt-2">
                </div>

            </div>

            <!-- SAVE & CANCEL -->
            <div class="sticky bottom-0 bg-white pt-4 border-t flex justify-end gap-2 px-6 mt-4">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                    Save
                </button>
                <button type="button" onclick="closeEditModal()"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>


        </main>
    </div>

    <script>
        // Clear search
        function clearSearch() {
            window.location.href = "{{ route('admin.places.index') }}";
        }

        // add
        const addPlaceModal = document.getElementById('addPlaceModal');

        function openPlaceModal() {
            addPlaceModal.classList.remove('hidden');
        }

        function closePlaceModal() {
            addPlaceModal.classList.add('hidden');
        }

        // perfect monsthsary
        
        let originalData = null;

// ai argument EDIT MODAL
        function openEditModal(place) {
    const modal = document.getElementById('editPlaceModal');
    modal.classList.remove('hidden');

    const form = document.getElementById('editPlaceForm');
    form.action = `/admin/places/${place.id}`;

    document.getElementById('edit_id').value = place.id;
    document.getElementById('edit_name').value = place.name ?? '';
    document.getElementById('edit_address').value = place.address ?? '';
    document.getElementById('edit_contact').value = place.contact ?? '';
    document.getElementById('edit_email').value = place.email ?? '';
    document.getElementById('edit_link1').value = place.link1 ?? '';
    document.getElementById('edit_link2').value = place.link2 ?? '';
    document.getElementById('edit_map_link').value = place.map_link ?? '';
    document.getElementById('edit_opening_hours').value = place.opening_hours ?? '';
    document.getElementById('edit_description').value = place.description ?? '';
    document.getElementById('edit_history').value = place.history ?? '';
    document.getElementById('edit_transport').value = place.transport ?? '';

    document.getElementById('edit_status').checked = place.status == 1;
    document.getElementById('edit_is_popular').checked = place.is_popular == 1;

    // Categories
    document.querySelectorAll('#edit_categories_list input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = place.categories?.some(cat => cat.cid == checkbox.value);
    });

    // Images
    const imageContainer = document.getElementById('view_images');
    imageContainer.innerHTML = '';
    if (place.images) {
        place.images.forEach((img, index) => {
            imageContainer.innerHTML += `
                <div class="relative w-24 h-24">
                    <img src="/storage/${img}" class="w-24 h-24 object-cover rounded-md border">
                    ${index === 0 ? `<span class="absolute bottom-0 left-0 bg-indigo-600 text-white text-xs px-2 py-1 rounded-tr-md">Main</span>` : ''}
                </div>
            `;
        });
    }
}

        function closeEditModal() {
    document.getElementById('editPlaceModal').classList.add('hidden');
}

        // img prev
        const mainImageInput = document.getElementById('mainImageInput');
        const mainImagePreview = document.getElementById('mainImagePreview');

        const galleryInput = document.getElementById('galleryInput');
        const galleryPreview = document.getElementById('galleryPreview');

        const editGalleryInput = document.getElementById('editGalleryInput');
        const editGalleryPreview = document.getElementById('editGalleryPreview');

        let galleryFiles = [];

        // Main image preview
        mainImageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (ev) => {
                mainImagePreview.style.backgroundImage = `url(${ev.target.result})`;
                mainImagePreview.style.backgroundSize = 'cover';
                mainImagePreview.style.backgroundPosition = 'center';
                mainImagePreview.innerText = '';
            };

            reader.readAsDataURL(file);
        });

        // Gallery preview
        function renderGallery() {
            galleryPreview.innerHTML = '';

            galleryFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative w-20 h-20';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 object-cover rounded-md shadow';

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.innerHTML = '✕';
                    removeBtn.className = 'absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs';
                    removeBtn.onclick = () => {
                        galleryFiles.splice(index, 1);
                        renderGallery();
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    galleryPreview.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });

            const addBtn = document.createElement('div');
            addBtn.className = 'w-20 h-20 flex items-center justify-center border-2 border-dashed border-gray-400 rounded-md cursor-pointer text-gray-400 text-2xl';
            addBtn.innerText = '+';
            addBtn.onclick = () => galleryInput.click();

            galleryPreview.appendChild(addBtn);
        }

        galleryInput.addEventListener('change', (e) => {
            Array.from(e.target.files).forEach(file => galleryFiles.push(file));
            renderGallery();

            // Update files for form submission
            const dt = new DataTransfer();
            galleryFiles.forEach(file => dt.items.add(file));
            galleryInput.files = dt.files;
        });

        // Initial render
        renderGallery();

        // remove image
        function removeGalleryImage(placeId, imagePath, btn) {

            if (!confirm("Delete this image?")) return;

            fetch(`/admin/places/${placeId}/remove-image`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ image: imagePath })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        btn.closest('.relative').remove();
                    }
                });
        }
    </script>

</body>

</html>