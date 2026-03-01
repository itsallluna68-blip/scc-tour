<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity Overview</title>
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
        <h2 class="text-2xl font-semibold text-indigo-900">
          Activities List
        </h2>

        <button onclick="openActivityModal()"
          class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition">
          + Add Activity
        </button>
      </div>

      {{-- search form --}}
      <form method="GET" action="{{ route('admin.activities.index') }}" class="flex items-center gap-2 mb-6">
        <div class="relative w-full md:w-1/3">

          <input type="text" name="search" id="searchInput" placeholder="Search activity..."
            value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10
                      focus:outline-none focus:ring-2 focus:ring-indigo-500
                      focus:border-indigo-500 transition">

          <!-- X Clear Button -->
          @if(request('search'))
            <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2
                           text-gray-400 hover:text-red-500 text-sm">
              ✕
            </button>
          @endif

        </div>
        <button type="submit" class="bg-indigo-900 text-white px-4 py-2 rounded-md hover:bg-indigo-800 transition">
          Search
        </button>

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


      {{-- table --}}
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Info</th>
              <th class="p-3">Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-300">
            @foreach($activities as $activity)
              <tr class="hover:bg-gray-100">
                <td>{{ $activity->aid }}</td>
                <td>{{ $activity->a_name }}</td>
                <td>{{ \Illuminate\Support\Str::limit($activity->a_info, 50, '...') }}</td>
                <td>
                  @if($activity->a_status == 1)
                    <span class="text-green-600 font-semibold">Active</span>
                  @else
                    <span class="text-red-600 font-semibold">Inactive</span>
                  @endif
                </td>
                <td class="flex gap-2 justify-center">
                  <button onclick="openViewActivity(
                    '{{ $activity->aid }}',
                    '{{ $activity->a_name }}',
                    `{{ $activity->a_info }}`,
                    '{{ asset($activity->img0) }}',
                    '{{ $activity->a_status }}'
                  )" class="text-blue-600 hover:text-blue-800 text-lg">
                    👁️
                  </button>
                  <button onclick='openEditActivity(
                        {{ $activity->aid }},
                        @json($activity->a_name),
                        @json($activity->a_info),
                        @json(asset($activity->img0)),
                        {{ $activity->a_status }},
                        @json($activity->categories->pluck("cid"))
                    )' class="text-indigo-600 hover:text-indigo-800 text-lg">
                    ✏️
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>


      <!-- View Modal -->
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
            <button onclick="closeViewModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
              Close
            </button>
          </div>

        </div>
      </div>

      <!-- add modal -->
    <!-- ADD ACTIVITY MODAL -->
<div id="addActivityModal"
     class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50
            @if($errors->any()) flex @else hidden @endif">

    <div class="bg-white rounded-lg w-full max-w-lg shadow-lg flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="px-6 py-4 border-b">
            <h3 class="text-xl font-semibold text-indigo-900">
                Add Activity
            </h3>
        </div>

        <!-- Form -->
        <form id="addActivityForm"
              method="POST"
              action="{{ route('admin.activities.store') }}"
              enctype="multipart/form-data"
              class="flex flex-col flex-1 overflow-hidden">

            @csrf

            <!-- Scrollable Content -->
            <div class="overflow-y-auto px-6 py-4 flex-1 space-y-4">

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="text-red-600 text-sm">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Activity Name -->
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

                <!-- Activity Info -->
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Activity Info
                    </label>
                    <textarea name="a_info"
                              rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm
                                     focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('a_info') }}</textarea>
                </div>

                <!-- Activity Image -->
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

                <!-- Categories -->
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
                    <p class="text-xs text-gray-500 mt-1">
                        Hold Ctrl (Cmd on Mac) to select multiple.
                    </p>
                </div>

            </div>
            <!-- End Scrollable Content -->

            <!-- Sticky Footer Buttons -->
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

    <!-- Header -->
    <div class="px-6 py-4 border-b">
      <h2 class="text-xl font-semibold text-indigo-900">
        Edit Activity
      </h2>
    </div>

    <!-- Form -->
    <form id="editActivityForm" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
      @csrf
      @method('PUT')

      <!-- Scrollable Content -->
      <div class="overflow-y-auto px-6 py-4 flex-1 space-y-4">

        <!-- Activity Name -->
        <div>
          <label class="block text-sm font-medium mb-1">Activity Name</label>
          <input type="text" name="a_name" id="editName"
                 class="w-full border rounded-md px-3 py-2 text-sm">
        </div>

        <!-- Activity Info -->
        <div>
          <label class="block text-sm font-medium mb-1">Activity Info</label>
          <textarea name="a_info" id="editInfo" rows="3"
                    class="w-full border rounded-md px-3 py-2 text-sm"></textarea>
        </div>

        <!-- Current Image -->
        <div>
          <label class="block text-sm font-medium mb-1">Current Image</label>
          <img id="editImagePreview" class="w-full h-40 object-cover rounded-md border">
        </div>

        <!-- Change Image -->
        <div>
          <label class="block text-sm font-medium mb-1">Change Image (optional)</label>
          <input type="file" name="img0" class="w-full border rounded-md px-3 py-2 text-sm">
        </div>

        <!-- Categories --> 
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

        <!-- Status -->
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

      <!-- Sticky Buttons -->
      <div class="sticky bottom-0 bg-white pt-4 border-t">
        <div class="flex justify-between">
          <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md">
            Update
          </button>
          <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md">
            Cancel
          </button>
        </div>
      </div>
      </div>


    </form>
  </div>
</div>


    <script>
      // table clear filter search
      function clearSearch() {
        window.location.href = "{{ route('admin.activities.index') }}";
      }

      // add modal
      const addActivityModal = document.getElementById("addActivityModal");
      const addActivityForm = document.getElementById("addActivityForm");

      function openActivityModal() {
        addActivityModal.classList.remove("hidden");
      }

      function closeActivityModal() {
        addActivityModal.classList.add("hidden");
      }

      // Ensure form submits normally
      addActivityForm.addEventListener('submit', function (e) {
        // Do NOT close modal here
        // Laravel will redirect on success automatically
      });

      // view modal
      function openViewActivity(aid, name, info, image, status) {

        document.getElementById('viewAid').textContent = aid;
        document.getElementById('viewName').textContent = name;
        document.getElementById('viewInfo').textContent = info;
        document.getElementById('viewImage').src = image;
        const statusElement = document.getElementById('viewStatus');

        if (status == 1) {
          statusElement.innerHTML = '<span class="text-green-600 font-semibold">Active</span>';
        } else {
          statusElement.innerHTML = '<span class="text-sred-600 font-semibold">Inactive</span>';
        }

        document.getElementById('viewModal').classList.remove('hidden');
      }

      function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
      }

      // edit modalllingg grrrrrrrr
      function openEditActivity(id, name, info, image, status, categories) {

        // Show modal
        document.getElementById('editActivityModal').classList.remove('hidden');

        // Set form action
        document.getElementById('editActivityForm').action = "/admin/activities/" + id;

        // Fill inputs
        document.getElementById('editName').value = name;
        document.getElementById('editInfo').value = info;

        // Image preview
        document.getElementById('editImagePreview').src = image;

        // Status
        const toggle = document.getElementById('editStatusToggle');
        const hiddenStatus = document.getElementById('editStatus');
        const label = document.getElementById('statusLabel');

        if (status == 1) {
          toggle.checked = true;
          hiddenStatus.value = 1;
          label.textContent = "Active";
        } else {
          toggle.checked = false;
          hiddenStatus.value = 0;
          label.textContent = "Inactive";
        }

        // Categories (checkbox grid)
        const container = document.getElementById('editCategories');
        Array.from(container.querySelectorAll('input[type="checkbox"]')).forEach(cb => {
          cb.checked = categories.includes(parseInt(cb.value));
        });
      }

      // Close modal
      function closeEditModal() {
        document.getElementById('editActivityModal').classList.add('hidden');
      }

      // Toggle status switch
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

    </script>



</body>

</html>