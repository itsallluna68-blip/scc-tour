<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Category Management</title>
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
                Categories List
            </h2>

            <div class="flex justify-between items-center mb-6">
                <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center gap-2">
                    <div class="relative w-64">
                        <label for="searchInput" class="sr-only">Search category</label>
                        <input type="text" name="search" id="searchInput" placeholder="Search category" value="{{ request('search') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" autocomplete="off">

                        @if(request('search'))
                        <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 flex items-center justify-center">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                        @endif
                    </div>

                    <label for="statusFilter" class="sr-only">Filter by Status</label>
                    <select name="status" id="statusFilter" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-md py-2 pl-3 pr-10 appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:1em_1em] bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </form>

                <button onclick="openCategoryModal()"
                    class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Category
                </button>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden mb-4">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3 text-left">Category Name</th>
                            <th class="p-3 text-left">Description</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-300" id="categoriesTableBody">
                        @forelse($categories as $category)
                        <tr class="category-row hover:bg-gray-100 transition">
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
                                    <button type="button"
                                        data-category="{{ json_encode($category) }}"
                                        onclick="openViewCategory(this)"
                                        class="text-blue-600 p-2 rounded-md transition flex items-center justify-center" title="View">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>

                                    <button type="button"
                                        data-category="{{ json_encode($category) }}"
                                        onclick="openEditCategory(this)"
                                        class="text-indigo-600 p-2 rounded-md transition flex items-center justify-center" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>

                                    <button type="button"
                                        onclick="confirmAction('delete', '/admin/categories/{{ $category->cid }}', '{{ csrf_token() }}')"
                                        class="text-red-600 p-2 rounded-md transition flex items-center justify-center" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">
                                No categories found.
                            </td>
                        </tr>
                        @endforelse
                        <tr id="jsNoResultsRow" style="display: none;">
                            <td colspan="4" class="p-4 text-center text-gray-500">
                                No categories found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>

            <div id="addCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-semibold text-indigo-900">Add Category</h2>
                        <button type="button" onclick="closeCategoryModal()" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="addCategoryName" class="block text-sm font-medium mb-1">Category Name</label>
                            <input type="text" name="category" id="addCategoryName" required autocomplete="off" class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="addDescription" class="block text-sm font-medium mb-1">Description (Optional)</label>
                            <textarea name="description" id="addDescription" autocomplete="off" class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t mt-4">
                            <button type="button" onclick="closeCategoryModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition text-sm">
                                Cancel
                            </button>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition text-sm">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="viewCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-semibold text-indigo-900">Category Details</h2>
                        <button type="button" onclick="closeViewCategoryModal()" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div class="space-y-4 text-sm">
                        <p><strong class="text-gray-700">Category Name:</strong> <span id="viewCategoryName"></span></p>
                        <div>
                            <strong class="text-gray-700">Description:</strong>
                            <p id="viewDescription" class="mt-1 text-gray-600 bg-gray-50 p-3 rounded-md"></p>
                        </div>
                        <p><strong class="text-gray-700">Status:</strong> <span id="viewStatus"></span></p>
                    </div>
                    <div class="flex justify-end pt-4 border-t mt-6">
                        <button type="button" onclick="closeViewCategoryModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <div id="editCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-semibold text-indigo-900">Edit Category</h2>
                        <button type="button" onclick="closeEditCategoryModal()" class="text-gray-400 hover:text-red-500 transition">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form id="editCategoryForm" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="editCategoryName" class="block text-sm font-medium mb-1">Category Name</label>
                            <input type="text" name="category" id="editCategoryName" autocomplete="off" class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="editDescription" class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="description" id="editDescription" autocomplete="off" class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                        <div>
                            <span class="block text-sm font-medium mb-2">Status</span>
                            <label for="editStatusToggle" class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="editStatusToggle" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                <span id="editStatusLabel" class="ml-3 text-sm font-medium text-gray-700">Inactive</span>
                            </label>
                            <input type="hidden" name="status" id="editStatus">
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t mt-4">
                            <button type="button" onclick="closeEditCategoryModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition text-sm">
                                Cancel
                            </button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition text-sm">
                                Update
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
            let rows = document.querySelectorAll('.category-row');
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

            document.getElementById('viewCategoryName').textContent = data.category;
            document.getElementById('viewDescription').textContent = data.description ? data.description : 'No description provided.';

            const statusElement = document.getElementById('viewStatus');
            if (data.status == 1) {
                statusElement.innerHTML = '<span class="text-green-600 font-semibold bg-green-50 px-2 py-1 rounded">Active</span>';
            } else {
                statusElement.innerHTML = '<span class="text-red-600 font-semibold bg-red-50 px-2 py-1 rounded">Inactive</span>';
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

            toggle.onchange = function() {
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
    </script>

</body>

</html>