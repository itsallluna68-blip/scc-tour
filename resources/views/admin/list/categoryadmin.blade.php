<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Category Management</title>
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
                <h2 class="text-2xl font-semibold text-indigo-900">
                    Categories List
                </h2>

                <button onclick="openCategoryModal()"
                    class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Category
                </button>
            </div>

            <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center gap-2 mb-6">
                <div class="relative w-full md:w-1/3">
                    <input type="text" name="search" id="searchInput" placeholder="Search category"
                        value="{{ request('search') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">

                    @if(request('search'))
                        <button type="button" onclick="clearSearch()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 flex items-center justify-center">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    @endif
                </div>

                <select name="status" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </form>

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

                    <tbody class="bg-white divide-y divide-gray-300" id="categoriesTableBody">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-100 transition">
                                <td class="p-3">{{ $category->cid }}</td>
                                <td class="p-3 font-medium">{{ $category->category }}</td>
                                <td class="p-3">{{ \Illuminate\Support\Str::limit($category->description, 50, '...') }}</td>
                                <td class="p-3">
                                    @if($category->status == 1)
                                        <span class="text-green-600 font-semibold">Active</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Inactive</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" data-category="{{ json_encode($category) }}"
                                            onclick="openViewCategory(this)"
                                            class="bg-blue-50 text-blue-600 hover:bg-blue-100 p-2 rounded-md transition flex items-center justify-center"
                                            title="View">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>

                                        <button type="button" data-category="{{ json_encode($category) }}"
                                            onclick="openEditCategory(this)"
                                            class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 p-2 rounded-md transition flex items-center justify-center"
                                            title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>

                                        <button type="button" data-id="{{ $category->cid }}"
                                            onclick="confirmDeleteCategory(this)"
                                            class="bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded-md transition flex items-center justify-center"
                                            title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </div>

            <div id="addCategoryModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6">
                    <h2 class="text-xl font-semibold mb-4 text-indigo-900">
                        Add Category
                    </h2>
                    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium mb-1">Category Name</label>
                            <input type="text" name="category" required
                                class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Description (Optional)</label>
                            <textarea name="description"
                                class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t mt-4">
                            <button type="button" onclick="closeCategoryModal()"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="viewCategoryModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6">
                    <h2 class="text-xl font-semibold mb-4 text-indigo-900 border-b pb-2">
                        Category Details
                    </h2>
                    <div class="space-y-4 text-sm">
                        <p><strong class="text-gray-700">CID:</strong> <span id="viewCid"></span></p>
                        <p><strong class="text-gray-700">Category Name:</strong> <span id="viewCategoryName"></span></p>
                        <div>
                            <strong class="text-gray-700">Description:</strong>
                            <p id="viewDescription" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                        </div>
                        <p><strong class="text-gray-700">Status:</strong> <span id="viewStatus"></span></p>
                    </div>
                    <div class="flex justify-end pt-4 border-t mt-6">
                        <button type="button" onclick="closeViewCategoryModal()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <div id="editCategoryModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6">
                    <h2 class="text-xl font-semibold mb-4 text-indigo-900 border-b pb-2">
                        Edit Category
                    </h2>
                    <form id="editCategoryForm" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="text-sm bg-gray-50 p-3 rounded-md">
                            <strong>CID:</strong>
                            <span id="editCid" class="text-gray-700 ml-1"></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Category Name</label>
                            <input type="text" name="category" id="editCategoryName"
                                class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="description" id="editDescription"
                                class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Status</label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="editStatusToggle" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-500 relative transition">
                                    <div
                                        class="absolute top-1 left-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5 shadow-sm">
                                    </div>
                                </div>
                                <span id="editStatusLabel"
                                    class="ml-3 text-sm font-medium text-gray-700">Inactive</span>
                            </label>
                            <input type="hidden" name="status" id="editStatus">
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t mt-4">
                            <button type="button" onclick="closeEditCategoryModal()"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="deleteCategoryModal"
                class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-sm shadow-lg text-center transform transition-all">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <i data-lucide="triangle-alert" class="w-8 h-8 text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Category</h3>
                    <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete this category? This action
                        cannot be undone.</p>
                    <form id="deleteCategoryForm" method="POST" class="flex justify-center gap-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="closeDeleteCategoryModal()"
                            class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-md text-sm font-medium transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        document.getElementById('searchInput').addEventListener('input', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#categoriesTableBody tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function clearSearch() {
            window.location.href = "{{ route('admin.categories.index') }}";
        }

        const categoryModal = document.getElementById('addCategoryModal');

        function openCategoryModal() {
            categoryModal.classList.remove('hidden');
        }

        function closeCategoryModal() {
            categoryModal.classList.add('hidden');
        }

        function openViewCategory(btnElement) {
            const data = JSON.parse(btnElement.dataset.category);

            document.getElementById('viewCid').textContent = data.cid;
            document.getElementById('viewCategoryName').textContent = data.category;
            document.getElementById('viewDescription').textContent = data.description ? data.description : 'No description provided.';

            const statusElement = document.getElementById('viewStatus');
            if (data.status == 1) {
                statusElement.innerHTML = '<span class="text-green-600 font-semibold">Active</span>';
            } else {
                statusElement.innerHTML = '<span class="text-red-600 font-semibold">Inactive</span>';
            }

            document.getElementById('viewCategoryModal').classList.remove('hidden');
        }

        function closeViewCategoryModal() {
            document.getElementById('viewCategoryModal').classList.add('hidden');
        }

        function openEditCategory(btnElement) {
            const data = JSON.parse(btnElement.dataset.category);
            const form = document.getElementById('editCategoryForm');

            form.action = `/admin/categories/${data.cid}`;
            document.getElementById('editCid').innerText = data.cid;
            document.getElementById('editCategoryName').value = data.category;
            document.getElementById('editDescription').value = data.description || '';

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

            toggle.onchange = function () {
                if (this.checked) {
                    hiddenStatus.value = 1;
                    label.innerText = "Active";
                } else {
                    hiddenStatus.value = 0;
                    label.innerText = "Inactive";
                }
            };

            document.getElementById('editCategoryModal').classList.remove('hidden');
        }

        function closeEditCategoryModal() {
            document.getElementById('editCategoryModal').classList.add('hidden');
        }

        function confirmDeleteCategory(btnElement) {
            const id = btnElement.dataset.id;
            const form = document.getElementById('deleteCategoryForm');
            form.action = `/admin/categories/${id}`;
            document.getElementById('deleteCategoryModal').classList.remove('hidden');
        }

        function closeDeleteCategoryModal() {
            document.getElementById('deleteCategoryModal').classList.add('hidden');
            document.getElementById('deleteCategoryForm').action = '';
        }
    </script>

</body>

</html>