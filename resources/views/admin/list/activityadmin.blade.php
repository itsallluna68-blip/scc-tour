<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>Activity Overview</title>
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
          Activities List
        </h2>

        <button onclick="openActivityModal()"
          class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition flex items-center gap-2">
          <i data-lucide="plus" class="w-4 h-4"></i> Add Activity
        </button>
      </div>

      <form method="GET" action="{{ route('admin.activities.index') }}" class="flex items-center gap-2 mb-6">
        <div class="relative w-full md:w-1/3">

          <input type="text" name="search" id="searchInput" placeholder="Search activity"
            value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10
                      focus:outline-none focus:ring-2 focus:ring-indigo-500
                      focus:border-indigo-500 transition">

          @if(request('search'))
            <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2
                           text-gray-400 hover:text-red-500 text-sm flex items-center justify-center">
              <i data-lucide="x" class="w-4 h-4"></i>
            </button>
          @endif

        </div>

        <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <option value="">All Status</option>
          <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
          <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
      </form>

      <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="p-3">ID</th>
              <th class="p-3">Name</th>
              <th class="p-3">Info</th>
              <th class="p-3">Status</th>
              <th class="p-3 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-300" id="activitiesTableBody">
            @foreach($activities as $activity)
              <tr class="hover:bg-gray-100 transition">
                <td class="p-3">{{ $activity->aid }}</td>
                <td class="p-3 font-medium">{{ $activity->a_name }}</td>
                <td class="p-3">{{ \Illuminate\Support\Str::limit($activity->a_info, 50, '...') }}</td>
                <td class="p-3">
                  @if($activity->a_status == 1)
                    <span class="text-green-600 font-semibold">Active</span>
                  @else
                    <span class="text-red-600 font-semibold">Inactive</span>
                  @endif
                </td>
                <td class="p-3">
                  <div class="flex items-center justify-center gap-2">
                    <button type="button"
                            data-activity="{{ json_encode($activity) }}"
                            data-image="{{ asset($activity->img0) }}"
                            onclick="openViewActivity(this)" 
                            class="bg-blue-50 text-blue-600 hover:bg-blue-100 p-2 rounded-md transition flex items-center justify-center" title="View">
                      <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                    
                    <button type="button"
                            data-activity="{{ json_encode($activity) }}"
                            data-image="{{ asset($activity->img0) }}"
                            data-categories="{{ json_encode($activity->categories->pluck('cid')) }}"
                            onclick="openEditActivity(this)" 
                            class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 p-2 rounded-md transition flex items-center justify-center" title="Edit">
                      <i data-lucide="edit" class="w-4 h-4"></i>
                    </button>

                    <button type="button" 
                            data-id="{{ $activity->aid }}"
                            onclick="confirmDeleteActivity(this)"
                            class="bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded-md transition flex items-center justify-center" title="Delete">
                      <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative">
          <h2 class="text-xl font-semibold mb-4 text-indigo-900">
            Activity Overview
          </h2>
          <div class="space-y-3 text-sm">
            <p><strong>ID:</strong> <span id="viewAid"></span></p>
            <p><strong>Name:</strong> <span id="viewName"></span></p>
            <p><strong>Info:</strong></p>
            <p class="text-gray-600" id="viewInfo"></p>
            <div>
              <strong>Image:</strong>
              <img id="viewImage" class="mt-2 w-full h-48 object-cover rounded-lg border">
            </div>
            <p>
              <strong>Status:</strong>
              <span id="viewStatus"></span>
            </p>
          </div>
          <div class="flex justify-end mt-6">
            <button onclick="closeViewModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
              Close
            </button>
          </div>
        </div>
      </div>

      <div id="addActivityModal"
           class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50
                  @if($errors->any()) flex @else hidden @endif">
          <div class="bg-white rounded-lg w-full max-w-lg shadow-lg flex flex-col max-h-[90vh]">
              <div class="px-6 py-4 border-b">
                  <h3 class="text-xl font-semibold text-indigo-900">
                      Add Activity
                  </h3>
              </div>
              <form id="addActivityForm"
                    method="POST"
                    action="{{ route('admin.activities.store') }}"
                    enctype="multipart/form-data"
                    class="flex flex-col flex-1 overflow-hidden">
                  @csrf
                  <div class="overflow-y-auto px-6 py-4 flex-1 space-y-4">
                      @if ($errors->any())
                          <div class="text-red-600 text-sm">
                              <ul class="list-disc pl-4 space-y-1">
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                      @endif

                      <div>
                          <label class="block text-sm font-medium mb-1">
                              Activity Name
                          </label>
                          <input type="text"
                                 name="a_name"
                                 required
                                 value="{{ old('a_name') }}"
                                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                      </div>

                      <div>
                          <label class="block text-sm font-medium mb-1">
                              Activity Info
                          </label>
                          <textarea name="a_info"
                                    rows="3"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('a_info') }}</textarea>
                      </div>

                      <div>
                          <label class="block text-sm font-medium mb-1">
                              Activity Image
                          </label>
                          <input type="file"
                                 name="img0"
                                 accept="image/*"
                                 required
                                 class="w-full text-sm text-gray-700 border border-gray-300 rounded-md px-3 py-2
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                      </div>

                      <div>
                          <label class="block text-sm font-medium mb-1">
                              Categories
                          </label>
                          <div class="grid grid-cols-3 gap-2">
                            @foreach($categories as $category)
                              <label class="inline-flex items-center space-x-2 text-sm p-1">
                                <input type="checkbox"
                                     name="categories[]"
                                     value="{{ $category->cid }}"
                                     {{ (collect(old('categories'))->contains($category->cid)) ? 'checked' : '' }}
                                     class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <span class="truncate">{{ $category->category }}</span>
                              </label>
                            @endforeach
                          </div>
                      </div>
                  </div>

                  <div class="border-t px-6 py-4 bg-white">
                      <div class="flex justify-between">
                          <button type="submit"
                                  class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm transition">
                              Save
                          </button>
                          <button type="button"
                                  onclick="closeActivityModal()"
                                  class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm transition">
                              Cancel
                          </button>
                      </div>
                  </div>
              </form>
          </div>
      </div>

      <div id="editActivityModal"
           class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-lg shadow-lg flex flex-col max-h-[90vh]">
          <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-semibold text-indigo-900">
              Edit Activity
            </h2>
          </div>
          <form id="editActivityForm" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="overflow-y-auto px-6 py-4 flex-1 space-y-4">
              <div>
                <label class="block text-sm font-medium mb-1">Activity Name</label>
                <input type="text" name="a_name" id="editName" class="w-full border rounded-md px-3 py-2 text-sm">
              </div>

              <div>
                <label class="block text-sm font-medium mb-1">Activity Info</label>
                <textarea name="a_info" id="editInfo" rows="3" class="w-full border rounded-md px-3 py-2 text-sm"></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium mb-1">Current Image</label>
                <img id="editImagePreview" class="w-full h-40 object-cover rounded-md border">
              </div>

              <div>
                <label class="block text-sm font-medium mb-1">Change Image (optional)</label>
                <input type="file" name="img0" class="w-full border rounded-md px-3 py-2 text-sm">
              </div>

              <div>
                <label class="block text-sm font-medium mb-1">Categories</label>
                <div id="editCategories" class="grid grid-cols-3 gap-2">
                  @foreach($categories as $category)
                    <label class="inline-flex items-center space-x-2 text-sm p-1">
                      <input type="checkbox" name="categories[]" value="{{ $category->cid }}" class="edit-category-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded">
                      <span class="truncate">{{ $category->category }}</span>
                    </label>
                  @endforeach
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium mb-2">Status</label>
                <label class="inline-flex items-center cursor-pointer">
                  <input type="checkbox" id="editStatusToggle" class="sr-only peer">
                  <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-500 relative transition">
                    <div class="absolute top-1 left-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-5"></div>
                  </div>
                  <span id="statusLabel" class="ml-3 text-sm font-medium text-gray-700">Inactive</span>
                </label>
                <input type="hidden" name="a_status" id="editStatus">
              </div>
            </div>

            <div class="sticky bottom-0 bg-white pt-4 border-t px-6 py-4">
              <div class="flex justify-between">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 transition text-white px-4 py-2 rounded-md">
                  Update
                </button>
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 hover:bg-gray-600 transition text-white px-4 py-2 rounded-md">
                  Cancel
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div id="deleteActivityModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-sm shadow-lg text-center transform transition-all">
          <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
            <i data-lucide="triangle-alert" class="w-8 h-8 text-red-600"></i>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Activity</h3>
          <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete this activity? This action cannot be undone.</p>
          <form id="deleteActivityForm" method="POST" class="flex justify-center gap-3">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteActivityModal()"
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
    document.addEventListener("DOMContentLoaded", function() {
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    });

    document.getElementById('searchInput').addEventListener('input', function() {
      let filter = this.value.toLowerCase();
      let rows = document.querySelectorAll('#activitiesTableBody tr');
      
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
      window.location.href = "{{ route('admin.activities.index') }}";
    }

    const addActivityModal = document.getElementById("addActivityModal");

    function openActivityModal() {
      addActivityModal.classList.remove("hidden");
    }

    function closeActivityModal() {
      addActivityModal.classList.add("hidden");
    }

    function openViewActivity(btnElement) {
      const data = JSON.parse(btnElement.dataset.activity);
      const image = btnElement.dataset.image;

      document.getElementById('viewAid').textContent = data.aid;
      document.getElementById('viewName').textContent = data.a_name;
      document.getElementById('viewInfo').textContent = data.a_info;
      document.getElementById('viewImage').src = image;
      
      const statusElement = document.getElementById('viewStatus');
      if (data.a_status == 1) {
        statusElement.innerHTML = '<span class="text-green-600 font-semibold">Active</span>';
      } else {
        statusElement.innerHTML = '<span class="text-red-600 font-semibold">Inactive</span>';
      }

      document.getElementById('viewModal').classList.remove('hidden');
    }

    function closeViewModal() {
      document.getElementById('viewModal').classList.add('hidden');
    }

    function openEditActivity(btnElement) {
      const data = JSON.parse(btnElement.dataset.activity);
      const image = btnElement.dataset.image;
      const categories = JSON.parse(btnElement.dataset.categories);

      document.getElementById('editActivityModal').classList.remove('hidden');
      document.getElementById('editActivityForm').action = "/admin/activities/" + data.aid;
      
      document.getElementById('editName').value = data.a_name;
      document.getElementById('editInfo').value = data.a_info;
      document.getElementById('editImagePreview').src = image;

      const toggle = document.getElementById('editStatusToggle');
      const hiddenStatus = document.getElementById('editStatus');
      const label = document.getElementById('statusLabel');

      if (data.a_status == 1) {
        toggle.checked = true;
        hiddenStatus.value = 1;
        label.textContent = "Active";
      } else {
        toggle.checked = false;
        hiddenStatus.value = 0;
        label.textContent = "Inactive";
      }

      const container = document.getElementById('editCategories');
      Array.from(container.querySelectorAll('input[type="checkbox"]')).forEach(cb => {
        cb.checked = categories.includes(parseInt(cb.value));
      });
    }

    function closeEditModal() {
      document.getElementById('editActivityModal').classList.add('hidden');
    }

    document.getElementById('editStatusToggle').addEventListener('change', function () {
      const hiddenStatus = document.getElementById('editStatus');
      const label = document.getElementById('statusLabel');

      if (this.checked) {
        hiddenStatus.value = 1;
        label.textContent = "Active";
      } else {
        hiddenStatus.value = 0;
        label.textContent = "Inactive";
      }
    });

    function confirmDeleteActivity(btnElement) {
      const id = btnElement.dataset.id;
      const form = document.getElementById('deleteActivityForm');
      form.action = `/admin/activities/${id}`;
      document.getElementById('deleteActivityModal').classList.remove('hidden');
    }

    function closeDeleteActivityModal() {
      document.getElementById('deleteActivityModal').classList.add('hidden');
      document.getElementById('deleteActivityForm').action = '';
    }

  </script>

</body>

</html>