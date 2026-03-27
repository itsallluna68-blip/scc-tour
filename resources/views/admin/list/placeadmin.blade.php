<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Places Overview</title>
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
                Places List
            </h2>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <form method="GET" action="{{ route('admin.places.index') }}" class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                    <div class="relative w-full sm:w-64">
                        <label for="searchInput" class="sr-only">Search place here</label>
                        <input type="text" name="search" id="searchInput" autocomplete="off" placeholder="Search place here"
                            value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500
                                      focus:border-indigo-500 transition">
                        @if(request('search'))
                        <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2
                                           text-gray-400 hover:text-red-500 flex items-center justify-center">
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

                <button onclick="openPlaceModal()"
                    class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition flex items-center justify-center gap-2 whitespace-nowrap w-full sm:w-auto">
                    <i data-lucide="plus" class="w-5 h-5"></i> Add Place
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
                            <th class="p-3">Name</th>
                            <th class="p-3">Categories</th>
                            <th class="p-3">Contact</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Popular</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300" id="placesTableBody">
                        @forelse($places as $place)
                        <tr class="place-row hover:bg-gray-100 transition">
                            <td class="p-3 font-medium">{{ $place->name }}</td>
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
                            <td class="p-3">{{ $place->contact }}</td>
                            <td class="p-3">
                                @if($place->status == 1)
                                <span class="text-green-600 font-semibold">Active</span>
                                @else
                                <span class="text-red-600 font-semibold">Inactive</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($place->is_popular == 1)
                                <span class="text-yellow-500 font-semibold">Yes</span>
                                @else
                                <span class="text-gray-500 font-semibold">No</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                        data-place="{{ json_encode($place) }}"
                                        onclick="openPlaceView(this)"
                                        class="text-blue-600 p-2 rounded-md transition flex items-center justify-center" title="View">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>

                                    <button type="button"
                                        data-place="{{ json_encode($place) }}"
                                        onclick="openPlaceEdit(this)"
                                        class="text-indigo-600 p-2 rounded-md transition flex items-center justify-center" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>

                                    <button type="button"
                                        onclick="confirmAction('delete', '/admin/places/{{ $place->id }}', '{{ csrf_token() }}')"
                                        class="text-red-600 p-2 rounded-md transition flex items-center justify-center" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">No places found.</td>
                        </tr>
                        @endforelse
                        <tr id="jsNoResultsRow" style="display: none;">
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                No places found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $places->links() }}
            </div>

            <div id="addPlaceModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg w-full max-w-2xl shadow-lg flex flex-col max-h-[90vh]">
                    <div class="flex justify-between items-center mb-2 mt-6 mx-6">
                        <h3 class="text-xl font-semibold text-indigo-900">Add Place</h3>
                        <button type="button" onclick="closePlaceModal()" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                    <form id="addPlaceForm" method="POST" action="{{ route('admin.places.store') }}"
                        enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                        @csrf
                        <div class="overflow-y-auto flex-1 px-6 py-4 space-y-4">
                            <div>
                                <label for="add_name" class="block text-sm font-medium mb-1">Name of Place</label>
                                <input type="text" name="name" id="add_name" required autocomplete="organization"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label for="add_address" class="block text-sm font-medium mb-1">Address</label>
                                <input type="text" name="address" id="add_address" autocomplete="street-address"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="add_contact" class="block text-sm font-medium mb-1">Contact</label>
                                    <input type="text" name="contact" id="add_contact" autocomplete="tel"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>
                                <div>
                                    <label for="add_email" class="block text-sm font-medium mb-1">Email</label>
                                    <input type="email" name="email" id="add_email" autocomplete="email"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="add_link1" class="block text-sm font-medium mb-1">Web Link 1</label>
                                    <input type="text" name="link1" id="add_link1" autocomplete="url"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>
                                <div>
                                    <label for="add_link2" class="block text-sm font-medium mb-1">Web Link 2</label>
                                    <input type="text" name="link2" id="add_link2" autocomplete="url"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>
                            </div>

                            <div>
                                <label for="add_map_link" class="block text-sm font-medium mb-1">Map Link</label>
                                <input type="text" name="map_link" id="add_map_link" autocomplete="url"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label for="add_opening_hours" class="block text-sm font-medium mb-1">Opening Hours</label>
                                <input type="text" name="opening_hours" id="add_opening_hours" autocomplete="off"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            </div>

                            <div>
                                <label for="add_transport" class="block text-sm font-medium mb-1">How to Get There</label>
                                <textarea name="transport" id="add_transport" rows="4" autocomplete="off"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                            </div>

                            <div>
                                <label for="add_description" class="block text-sm font-medium mb-1">Description</label>
                                <textarea name="description" id="add_description" rows="4" autocomplete="off"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                            </div>

                            <div>
                                <label for="add_history" class="block text-sm font-medium mb-1">History</label>
                                <textarea name="history" id="add_history" rows="4" autocomplete="off"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                            </div>

                            <div>
                                <span class="block text-sm font-medium mb-1">Main Image (Cover)</span>
                                <div id="mainImagePreview"
                                    class="relative w-32 h-32 mb-2 border rounded-md flex items-center justify-center text-gray-400 cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                    <i data-lucide="image-plus" class="w-8 h-8"></i>
                                </div>
                                <label for="mainImageInput" class="sr-only">Upload Main Image</label>
                                <input type="file" name="main_image" id="mainImageInput" accept="image/*" class="hidden">
                            </div>

                            <div>
                                <span class="block text-sm font-medium mb-1">Gallery Images</span>
                                <div id="galleryPreview" class="flex flex-wrap gap-2 mb-2"></div>
                                <label for="galleryInput" class="sr-only">Upload Gallery Images</label>
                                <input type="file" name="images[]" accept="image/*" multiple id="galleryInput" class="hidden">
                            </div>

                            <div class="flex items-center gap-6 pt-2">
                                <label for="add_status" class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="status" id="add_status" value="1" checked
                                        class="border-gray-300 rounded cursor-pointer">
                                    <span class="text-sm font-medium text-gray-700">Active</span>
                                </label>

                                <label for="add_is_popular" class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_popular" id="add_is_popular" value="1" class="border-gray-300 rounded cursor-pointer">
                                    <span class="text-sm font-medium text-gray-700">Popular</span>
                                </label>
                            </div>

                            <div>
                                <span class="block text-sm font-medium mb-3">Categories</span>
                                <div class="grid grid-cols-3 gap-2 p-4 border border-gray-300 rounded-md bg-gray-50">
                                    @foreach($categories as $category)
                                    <label for="add_category_{{ $category->cid }}" class="flex items-center gap-3 cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                        <input type="checkbox" name="categories[]" id="add_category_{{ $category->cid }}" value="{{ $category->cid }}"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                                        <span class="text-sm font-medium text-gray-700">{{ $category->category }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="sticky bottom-0 bg-white pt-4 pb-4 border-t flex justify-end gap-2 px-6">
                            <button type="button" onclick="closePlaceModal()"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="viewPlaceModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-2xl rounded-xl shadow-xl p-6 relative max-h-[90vh] flex flex-col">
                    <div class="border-b pb-4 mb-4 flex justify-between items-center shrink-0">
                        <h2 class="text-xl font-semibold text-indigo-900">
                            Place Overview
                        </h2>
                        <button onclick="closeViewModal()" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                    <div class="space-y-3 text-sm overflow-y-auto pr-2 flex-1 text-gray-800">
                        <p><strong>Name:</strong> <span id="view_name_text" class="text-gray-600"></span></p>
                        <p><strong>Address:</strong> <span id="view_address_text" class="text-gray-600"></span></p>
                        <div class="grid grid-cols-2 gap-4">
                            <p><strong>Contact:</strong> <span id="view_contact_text" class="text-gray-600"></span></p>
                            <p><strong>Email:</strong> <span id="view_email_text" class="text-gray-600"></span></p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <p><strong>Web Link 1:</strong> <a href="#" id="view_link1_text" class="text-blue-600 hover:underline" target="_blank"></a></p>
                            <p><strong>Web Link 2:</strong> <a href="#" id="view_link2_text" class="text-blue-600 hover:underline" target="_blank"></a></p>
                        </div>
                        <p><strong>Map Link:</strong> <a href="#" id="view_map_link_text" class="text-blue-600 hover:underline" target="_blank"></a></p>
                        <p><strong>Opening Hours:</strong> <span id="view_opening_hours_text" class="text-gray-600"></span></p>
                        <p><strong>How to Get There:</strong></p>
                        <p class="text-gray-600 bg-gray-50 p-3 rounded-md border" id="view_transport_text"></p>
                        <p><strong>Description:</strong></p>
                        <p class="text-gray-600 bg-gray-50 p-3 rounded-md border" id="view_description_text"></p>
                        <p><strong>History:</strong></p>
                        <p class="text-gray-600 bg-gray-50 p-3 rounded-md border" id="view_history_text"></p>
                        <p class="mt-4"><strong>Categories:</strong></p>
                        <div id="view_categories_list" class="flex flex-wrap gap-2"></div>
                        <div class="flex gap-6 mt-4">
                            <p><strong>Status:</strong> <span id="view_status_text"></span></p>
                            <p><strong>Popular:</strong> <span id="view_popular_text"></span></p>
                        </div>
                        <div class="mt-4">
                            <strong>Photos:</strong>
                            <div id="view_images_list" class="flex flex-wrap gap-3 mt-2"></div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 pt-4 border-t shrink-0">
                        <button onclick="closeViewModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <div id="editPlaceModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg w-full max-w-2xl shadow-lg flex flex-col max-h-[90vh]">
                    <div class="flex justify-between items-center mb-2 mt-6 mx-6">
                        <h3 class="text-xl font-semibold text-indigo-900">Edit Place</h3>
                        <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                    <form id="editPlaceForm" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_id">
                        <div class="overflow-y-auto flex-1 px-6 py-4 space-y-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium mb-1">Name of Place</label>
                                <input type="text" name="name" id="edit_name" required autocomplete="organization"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            </div>
                            <div>
                                <label for="edit_address" class="block text-sm font-medium mb-1">Address</label>
                                <input type="text" name="address" id="edit_address" autocomplete="street-address"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_contact" class="block text-sm font-medium mb-1">Contact</label>
                                    <input type="text" name="contact" id="edit_contact" autocomplete="tel"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                                </div>
                                <div>
                                    <label for="edit_email" class="block text-sm font-medium mb-1">Email</label>
                                    <input type="email" name="email" id="edit_email" autocomplete="email"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_link1" class="block text-sm font-medium mb-1">Web Link 1</label>
                                    <input type="text" name="link1" id="edit_link1" autocomplete="url"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                                </div>
                                <div>
                                    <label for="edit_link2" class="block text-sm font-medium mb-1">Web Link 2</label>
                                    <input type="text" name="link2" id="edit_link2" autocomplete="url"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                                </div>
                            </div>
                            <div>
                                <label for="edit_map_link" class="block text-sm font-medium mb-1">Map Link</label>
                                <input type="text" name="map_link" id="edit_map_link" autocomplete="url"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            </div>
                            <div>
                                <label for="edit_opening_hours" class="block text-sm font-medium mb-1">Opening Hours</label>
                                <input type="text" name="opening_hours" id="edit_opening_hours" autocomplete="off"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            </div>
                            <div>
                                <label for="edit_transport" class="block text-sm font-medium mb-1">How to Get There</label>
                                <textarea name="transport" id="edit_transport" rows="4" autocomplete="off"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
                            </div>
                            <div>
                                <label for="edit_description" class="block text-sm font-medium mb-1">Description</label>
                                <textarea name="description" id="edit_description" rows="4" autocomplete="off"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
                            </div>
                            <div>
                                <label for="edit_history" class="block text-sm font-medium mb-1">History</label>
                                <textarea name="history" id="edit_history" rows="4" autocomplete="off"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
                            </div>
                            <div class="flex items-center gap-6 pt-2">
                                <label for="edit_status" class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="status" value="1" id="edit_status" class="border-gray-300 rounded transition cursor-pointer">
                                    <span class="text-sm font-medium text-gray-700">Active</span>
                                </label>
                                <label for="edit_is_popular" class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_popular" value="1" id="edit_is_popular" class="border-gray-300 rounded transition cursor-pointer">
                                    <span class="text-sm font-medium text-gray-700">Popular</span>
                                </label>
                            </div>
                            <div>
                                <span class="block text-sm font-medium mb-3">Categories</span>
                                <div class="grid grid-cols-3 gap-2 p-4 border border-gray-300 rounded-md bg-gray-50">
                                    @foreach($categories as $category)
                                    <label for="edit_category_{{ $category->cid }}" class="flex items-center gap-3 cursor-pointer hover:bg-gray-100 p-2 rounded transition">
                                        <input type="checkbox" name="categories[]" id="edit_category_{{ $category->cid }}" value="{{ $category->cid }}"
                                            class="edit-category-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                                        <span class="text-sm font-medium text-gray-700">{{ $category->category }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="border-t pt-4">
                                <span class="block text-sm font-medium mb-3 text-indigo-900">Current Photos</span>
                                <div id="edit_images_preview" class="flex flex-wrap gap-3 mb-6"></div>
                                <div>
                                    <span class="block text-sm font-medium mb-1">Main Image (Cover)</span>
                                    <div id="editMainImagePreview"
                                        class="relative w-32 h-32 mb-2 border rounded-md flex items-center justify-center text-gray-400 cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                        <i data-lucide="image-plus" class="w-8 h-8"></i>
                                    </div>
                                    <label for="editMainImageInput" class="sr-only">Upload Main Image</label>
                                    <input type="file" name="main_image" id="editMainImageInput" accept="image/*" class="hidden">
                                </div>
                                <div>
                                    <span class="block text-sm font-medium mb-1">Gallery Images</span>
                                    <div id="editGalleryPreview" class="flex flex-wrap gap-2 mb-2"></div>
                                    <label for="editGalleryInput" class="sr-only">Upload Gallery Images</label>
                                    <input type="file" name="images[]" accept="image/*" multiple id="editGalleryInput" class="hidden">
                                </div>
                            </div>
                        </div>
                        <div class="sticky bottom-0 bg-white pt-4 pb-4 border-t flex justify-end gap-2 px-6">
                            <button type="button" onclick="closeEditModal()"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                                Save
                            </button>
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
                const addModal = document.getElementById('addPlaceModal');
                const editModal = document.getElementById('editPlaceModal');
                
                if (document.getElementById('edit_id') && document.getElementById('edit_id').value) {
                    editModal.classList.remove('hidden');
                } else {
                    addModal.classList.remove('hidden');
                }
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
            let rows = document.querySelectorAll('.place-row');
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
            window.location.href = "{{ route('admin.places.index') }}";
        }

        function openPlaceModal() {
            document.getElementById('addPlaceModal').classList.remove('hidden');
        }

        function closePlaceModal() {
            document.getElementById('addPlaceModal').classList.add('hidden');
        }

        function openPlaceView(btnElement) {
            const place = JSON.parse(btnElement.getAttribute('data-place'));

            document.getElementById('view_name_text').textContent = place.name || 'N/A';
            document.getElementById('view_address_text').textContent = place.address || 'N/A';
            document.getElementById('view_contact_text').textContent = place.contact || 'N/A';
            document.getElementById('view_email_text').textContent = place.email || 'N/A';

            document.getElementById('view_link1_text').textContent = place.link1 || 'N/A';
            document.getElementById('view_link1_text').href = place.link1 || '#';

            document.getElementById('view_link2_text').textContent = place.link2 || 'N/A';
            document.getElementById('view_link2_text').href = place.link2 || '#';

            document.getElementById('view_map_link_text').textContent = place.map_link || 'N/A';
            document.getElementById('view_map_link_text').href = place.map_link || '#';

            document.getElementById('view_opening_hours_text').textContent = place.opening_hours || 'N/A';
            document.getElementById('view_transport_text').textContent = place.transport || 'N/A';
            document.getElementById('view_description_text').innerHTML = place.description ? place.description.replace(/\n/g, '<br>') : 'N/A';
            document.getElementById('view_history_text').innerHTML = place.history ? place.history.replace(/\n/g, '<br>') : 'N/A';

            if (place.status == 1) {
                document.getElementById('view_status_text').innerHTML = '<span class="text-green-600 font-semibold bg-green-50 px-2 py-1 rounded">Active</span>';
            } else {
                document.getElementById('view_status_text').innerHTML = '<span class="text-red-600 font-semibold bg-red-50 px-2 py-1 rounded">Inactive</span>';
            }

            if (place.is_popular == 1) {
                document.getElementById('view_popular_text').innerHTML = '<span class="text-yellow-600 font-semibold bg-yellow-50 px-2 py-1 rounded">Yes</span>';
            } else {
                document.getElementById('view_popular_text').innerHTML = '<span class="text-gray-500 font-semibold bg-gray-100 px-2 py-1 rounded">No</span>';
            }

            const categoryContainer = document.getElementById('view_categories_list');
            categoryContainer.innerHTML = '';
            if (place.categories && place.categories.length > 0) {
                place.categories.forEach(cat => {
                    categoryContainer.innerHTML += `<span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">${cat.category}</span>`;
                });
            } else {
                categoryContainer.innerHTML = '<span class="text-gray-500">None</span>';
            }

            const imageContainer = document.getElementById('view_images_list');
            imageContainer.innerHTML = '';
            if (place.images && place.images.length > 0) {
                place.images.forEach((img, index) => {
                    imageContainer.innerHTML += `
                        <div class="relative w-28 h-28">
                            <img src="{{ Storage::disk('s3')->url('') }}${img}" class="w-28 h-28 object-cover rounded-md border border-gray-300 shadow-sm">
                            ${index === 0 ? `<span class="absolute bottom-0 left-0 bg-indigo-600 text-white text-[10px] px-2 py-1 rounded-tr-md font-medium">MAIN COVER</span>` : ''}
                        </div>`;
                });
            } else {
                imageContainer.innerHTML = '<span class="text-gray-500">No photos available.</span>';
            }

            document.getElementById('viewPlaceModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewPlaceModal').classList.add('hidden');
        }

        function openPlaceEdit(btnElement) {
            const place = JSON.parse(btnElement.getAttribute('data-place'));

            document.getElementById('editPlaceForm').action = `/admin/places/${place.id}`;

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

            document.querySelectorAll('.edit-category-checkbox').forEach(checkbox => {
                checkbox.checked = place.categories?.some(cat => cat.cid == checkbox.value);
            });

            const imageContainer = document.getElementById('edit_images_preview');
            imageContainer.innerHTML = '';
            if (place.images) {
                place.images.forEach((img, index) => {
                    imageContainer.innerHTML += `
                        <div class="relative w-24 h-24">
                            <img src="{{ Storage::disk('s3')->url('') }}${img}" class="w-24 h-24 object-cover rounded-md border border-gray-300">
                            ${index === 0 ? `<span class="absolute bottom-0 left-0 bg-indigo-600 text-white text-[10px] px-2 py-1 rounded-tr-md font-medium">MAIN</span>` : ''}
                            <button type="button"
                                    onclick="removeGalleryImage(${place.id}, '${img}', this)"
                                    class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white w-5 h-5 rounded-full flex items-center justify-center shadow-md transition">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </div>`;
                });
            }

            editGalleryFiles = [];
            renderEditGallery();

            const mainImgPreview = document.getElementById('editMainImagePreview');
            if (mainImgPreview) {
                mainImgPreview.style.backgroundImage = 'none';
                mainImgPreview.innerHTML = '<i data-lucide="image-plus" class="w-8 h-8"></i>';
            }

            reRenderIcons();
            document.getElementById('editPlaceModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editPlaceModal').classList.add('hidden');
        }

        function removeGalleryImage(placeId, imagePath, btn) {
            btn.closest('.relative').remove();

            fetch(`/admin/places/${placeId}/remove-image`, {
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

        function clearMainImage(e, previewId, inputId) {
            e.stopPropagation();
            document.getElementById(inputId).value = '';
            const preview = document.getElementById(previewId);
            preview.style.backgroundImage = 'none';
            preview.innerHTML = '<i data-lucide="image-plus" class="w-8 h-8"></i>';
            reRenderIcons();
        }

        const mainImageInput = document.getElementById('mainImageInput');
        const mainImagePreview = document.getElementById('mainImagePreview');
        const galleryInput = document.getElementById('galleryInput');
        const galleryPreview = document.getElementById('galleryPreview');
        let galleryFiles = [];

        if (mainImagePreview && mainImageInput) {
            mainImagePreview.onclick = () => mainImageInput.click();
            mainImageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (ev) => {
                    mainImagePreview.style.backgroundImage = `url(${ev.target.result})`;
                    mainImagePreview.style.backgroundSize = 'cover';
                    mainImagePreview.style.backgroundPosition = 'center';
                    mainImagePreview.innerHTML = `<button type="button" onclick="clearMainImage(event, 'mainImagePreview', 'mainImageInput')" class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md"><i data-lucide="x" class="w-4 h-4"></i></button>`;
                    reRenderIcons();
                };
                reader.readAsDataURL(file);
            });
        }

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

        const editMainImageInput = document.getElementById('editMainImageInput');
        const editMainImagePreview = document.getElementById('editMainImagePreview');
        const editGalleryInput = document.getElementById('editGalleryInput');
        const editGalleryPreview = document.getElementById('editGalleryPreview');
        let editGalleryFiles = [];

        if (editMainImagePreview && editMainImageInput) {
            editMainImagePreview.onclick = () => editMainImageInput.click();
            editMainImageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (ev) => {
                    editMainImagePreview.style.backgroundImage = `url(${ev.target.result})`;
                    editMainImagePreview.style.backgroundSize = 'cover';
                    editMainImagePreview.style.backgroundPosition = 'center';
                    editMainImagePreview.innerHTML = `<button type="button" onclick="clearMainImage(event, 'editMainImagePreview', 'editMainImageInput')" class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md"><i data-lucide="x" class="w-4 h-4"></i></button>`;
                    reRenderIcons();
                };
                reader.readAsDataURL(file);
            });
        }

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