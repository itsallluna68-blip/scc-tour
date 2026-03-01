<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Category Management</title>
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

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-indigo-900">
                    Categories List
                </h2>

                <button onclick="openCategoryModal()"
                    class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition">
                    + Add Category
                </button>
            </div>

            <!-- Search -->
            <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center gap-2 mb-6">

                <input type="text" name="search" placeholder="Search category..." value="{{ request('search') }}" class="w-full md:w-1/3 border border-gray-300 rounded-md px-3 py-2
                              focus:outline-none focus:ring-2 focus:ring-indigo-500">

                <button type="submit"
                    class="bg-indigo-900 text-white px-4 py-2 rounded-md hover:bg-indigo-800 transition">
                    Search
                </button>

                {{-- status filtering --}}
                <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2
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

            <!-- Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3 text-left">CID</th>
                            <th class="p-3 text-left">Category Name</th>
                            <th class="p-3 text-left">Description</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-300">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-100">
                                <td class="p-3">{{ $category->cid }}</td>
                                <td class="p-3">{{ $category->category }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($category->description, 50, '...') }}</td>

                                <td class="p-3">
                                    @if($category->status == 1)
                                        <span class="text-green-600 font-semibold">Active</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Inactive</span>
                                    @endif
                                </td>

                                <td class="p-3 text-center space-x-2">
                                    <button onclick="openEditCategory(
                                                '{{ $category->cid }}',
                                                '{{ $category->category }}',
                                                `{{ $category->description }}`,
                                                '{{ $category->status }}'
                                            )" class="text-indigo-600 hover:text-indigo-800 text-lg">
                                        ✏️
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <!-- Add Category Modal -->
            <div id="addCategoryModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

                <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6">

                    <h2 class="text-xl font-semibold mb-4 text-indigo-900">
                        Add Category
                    </h2>

                    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">

                        @csrf

                        <!-- Category Name -->
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Category Name
                            </label>
                            <input type="text" name="category" required
                                class="w-full border rounded-md px-3 py-2 text-sm">
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Description (Optional)
                            </label>
                            <textarea name="description" class="w-full border rounded-md px-3 py-2 text-sm"></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-between pt-4">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md">
                                Save
                            </button>

                            <button type="button" onclick="closeCategoryModal()"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md">
                                Cancel
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Edit Category Modal -->
            <div id="editCategoryModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

                <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6">

                    <h2 class="text-xl font-semibold mb-4 text-indigo-900">
                        Edit Category
                    </h2>

                    <form id="editCategoryForm" method="POST" class="space-y-4">

                        @csrf
                        @method('PUT')

                        <!-- CID (Display Only) -->
                        <div class="text-sm">
                            <strong>CID:</strong>
                            <span id="editCid" class="text-gray-700"></span>
                        </div>

                        <!-- Category Name -->
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Category Name
                            </label>
                            <input type="text" name="category" id="editCategoryName"
                                class="w-full border rounded-md px-3 py-2 text-sm">
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Description
                            </label>
                            <textarea name="description" id="editDescription"
                                class="w-full border rounded-md px-3 py-2 text-sm"></textarea>
                        </div>

                        <!-- Status Toggle -->
                        <div>
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

                        <!-- Buttons -->
                        <div class="flex justify-between pt-4">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">
                                Update
                            </button>

                            <button type="button" onclick="closeEditCategoryModal()"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md">
                                Cancel
                            </button>
                        </div>

                    </form>
                </div>
            </div>


        </div>
    </div>

    <script>
        // add modal
        const categoryModal = document.getElementById('addCategoryModal');

        function openCategoryModal() {
            categoryModal.classList.remove('hidden');
        }

        function closeCategoryModal() {
            categoryModal.classList.add('hidden');
        }

        // edit modal
        function openEditCategory(cid, name, description, status) {

            const form = document.getElementById('editCategoryForm');

            // Set correct update route
            form.action = `/admin/categories/${cid}`;

            document.getElementById('editCid').innerText = cid;
            document.getElementById('editCategoryName').value = name;
            document.getElementById('editDescription').value = description;

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
                if (this.checked) {
                    hiddenStatus.value = 1;
                    label.innerText = "Active";
                } else {
                    hiddenStatus.value = 0;
                    label.innerText = "Inactive";
                }
            };

            document.getElementById('editCategoryModal')
                .classList.remove('hidden');
        }

        function closeEditCategoryModal() {
            document.getElementById('editCategoryModal')
                .classList.add('hidden');
        }
    </script>


</body>

</html>