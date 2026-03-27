<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>Activity Overview</title>
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
        Activities List
      </h2>

      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">

        <form method="GET" action="{{ route('admin.activities.index') }}" class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
          <div class="relative w-full sm:w-64">
            <label for="searchInput" class="sr-only">Search activity</label>
            <input type="text" name="search" id="searchInput" placeholder="Search activity" autocomplete="off"
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

          <label for="statusFilter" class="sr-only">Filter by Status</label>
          <select name="status" id="statusFilter" onchange="this.form.submit()" autocomplete="off" class="border border-gray-300 rounded-md py-2 pl-3 pr-10 appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:1em_1em] bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
            style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%2F%3E%3C%2Fsvg%3E');">
            <option value="">All Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
          </select>
        </form>

        <button onclick="openActivityModal()"
          class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition flex items-center justify-center gap-2 whitespace-nowrap w-full sm:w-auto">
          <i data-lucide="plus" class="w-4 h-4"></i> Add Activity
        </button>

      </div>

      <div class="bg-white shadow rounded-lg overflow-hidden mb-4">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="p-3">Name</th>
              <th class="p-3">Info</th>
              <th class="p-3">Status</th>
              <th class="p-3 text-center">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-300" id="activitiesTableBody">
            @forelse($activities as $activity)
            <tr class="activity-row hover:bg-gray-100 transition">
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
                    data-image="{{ $activity->img0 ? Storage::disk('s3')->url($activity->img0) : '' }}"
                    onclick="openViewActivity(this)"
                    class="text-blue-600 p-2 rounded-md transition flex items-center justify-center" title="View">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                  </button>

                  <button type="button"
                    data-activity="{{ json_encode($activity) }}"
                    data-image="{{ $activity->img0 ? Storage::disk('s3')->url($activity->img0) : '' }}"
                    data-categories="{{ json_encode($activity->categories->pluck('cid')) }}"
                    onclick="openEditActivity(this)"
                    class="text-indigo-600 p-2 rounded-md transition flex items-center justify-center" title="Edit">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                  </button>

                  <button type="button"
                    onclick="confirmAction('delete', '/admin/activities/{{ $activity->aid }}', '{{ csrf_token() }}')"
                    class="text-red-600 p-2 rounded-md transition flex items-center justify-center" title="Delete">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="p-4 text-center text-gray-500">No activities found.</td>
            </tr>
            @endforelse
            <tr id="jsNoResultsRow" style="display: none;">
              <td colspan="4" class="p-4 text-center text-gray-500">
                No activities found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        {{ $activities->links() }}
      </div>

      <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 relative flex flex-col max-h-[90vh]">
          <div class="flex justify-between items-center mb-4 border-b pb-2 shrink-0">
            <h2 class="text-xl font-semibold text-indigo-900">
              Activity Overview
            </h2>
            <button type="button" onclick="closeViewModal()" class="text-gray-400 hover:text-red-500 transition">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>

          <div class="space-y-3 text-sm overflow-y-auto pr-2 flex-1">
            <p><strong class="text-gray-700">Name:</strong> <span id="viewName"></span></p>
            <p><strong class="text-gray-700">Info:</strong></p>
            <p class="text-gray-600 bg-gray-50 p-3 rounded-md border" id="viewInfo"></p>
            <div>
              <strong class="text-gray-700">Image:</strong>
              <img id="viewImage" class="mt-2 w-full h-48 object-cover rounded-lg border shadow-sm">
            </div>
            <p>
              <strong class="text-gray-700">Status:</strong>
              <span id="viewStatus"></span>
            </p>
          </div>

          <div class="flex justify-end mt-6 pt-4 border-t shrink-0">
            <button onclick="closeViewModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition text-sm">
              Close
            </button>
          </div>
        </div>
      </div>

      <div id="addActivityModal"
        class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50
                  @if($errors->any()) flex @else hidden @endif">
        <div class="bg-white rounded-lg w-full max-w-lg shadow-lg flex flex-col max-h-[90vh]">
          <div class="flex justify-between items-center px-6 py-4 border-b shrink-0">
            <h3 class="text-xl font-semibold text-indigo-900">
              Add Activity
            </h3>
            <button type="button" onclick="closeActivityModal()" class="text-gray-400 hover:text-red-500 transition">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>
          <form id="addActivityForm"
            method="POST"
            action="{{ route('admin.activities.store') }}"
            enctype="multipart/form-data"
            class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="overflow-y-auto px-6 py-4 flex-1 space-y-4">
              @if ($errors->any())
              <div class="text-red-600 text-sm bg-red-50 p-3 rounded-md border border-red-200">
                <ul class="list-disc pl-4 space-y-1">
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif

              <div>
                <label for="addAName" class="block text-sm font-medium mb-1">
                  Activity Name
                </label>
                <input type="text"
                  id="addAName"
                  name="a_name"
                  required
                  autocomplete="off"
                  value="{{ old('a_name') }}"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              </div>

              <div>
                <label for="addAInfo" class="block text-sm font-medium mb-1">
                  Activity Info
                </label>
                <textarea id="addAInfo"
                  name="a_info"
                  rows="3"
                  autocomplete="off"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('a_info') }}</textarea>
              </div>

              <div>
                <span class="block text-sm font-medium mb-1">Activity Image</span>
                <div id="mainImagePreview"
                  class="relative w-32 h-32 mb-2 border rounded-md flex items-center justify-center text-gray-400 cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                  <i data-lucide="image-plus" class="w-8 h-8"></i>
                </div>
                <label for="mainImageInput" class="sr-only">Upload Activity Image</label>
                <input type="file" name="img0" id="mainImageInput" accept="image/*" class="hidden" required>
              </div>

              <div>
                <span class="block text-sm font-medium mb-1">
                  Categories
                </span>
                <div class="grid grid-cols-3 gap-2 p-4 border border-gray-300 rounded-md bg-gray-50">
                  @foreach($categories as $category)
                  <label for="addCat{{ $category->cid }}" class="inline-flex items-center space-x-2 text-sm p-1 cursor-pointer">
                    <input type="checkbox"
                      id="addCat{{ $category->cid }}"
                      name="categories[]"
                      value="{{ $category->cid }}"
                      {{ (collect(old('categories'))->contains($category->cid)) ? 'checked' : '' }}
                      class="h-4 w-4 text-indigo-600 border-gray-300 rounded cursor-pointer">
                    <span class="truncate">{{ $category->category }}</span>
                  </label>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="sticky bottom-0 bg-white pt-4 pb-4 border-t flex justify-end gap-2 px-6">
              <button type="button"
                onclick="closeActivityModal()"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">
                Cancel
              </button>
              <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">
                Save
              </button>
            </div>
          </form>
        </div>
      </div>

      <div id="editActivityModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-lg shadow-lg flex flex-col max-h-[90vh]">
          <div class="flex justify-between items-center px-6 py-4 border-b shrink-0">
            <h2 class="text-xl font-semibold text-indigo-900">
              Edit Activity
            </h2>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>
          <form id="editActivityForm" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
            <div class="overflow-y-auto px-6 py-4 flex-1 space-y-4">
              <div>
                <label for="editName" class="block text-sm font-medium mb-1">Activity Name</label>
                <input type="text" name="a_name" id="editName" autocomplete="off" class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
              </div>

              <div>
                <label for="editInfo" class="block text-sm font-medium mb-1">Activity Info</label>
                <textarea name="a_info" id="editInfo" rows="3" autocomplete="off" class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
              </div>

              <div>
                <span class="block text-sm font-medium mb-1">Activity Image</span>
                <div id="editMainImagePreview" class="relative w-32 h-32 mb-2 border rounded-md flex items-center justify-center text-gray-400 cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                  <i data-lucide="image-plus" class="w-8 h-8"></i>
                </div>
                <label for="editMainImageInput" class="sr-only">Change Activity Image</label>
                <input type="file" name="img0" id="editMainImageInput" accept="image/*" class="hidden">
              </div>

              <div>
                <span class="block text-sm font-medium mb-1">Categories</span>
                <div id="editCategories" class="grid grid-cols-3 gap-2 p-4 border border-gray-300 rounded-md bg-gray-50">
                  @foreach($categories as $category)
                  <label for="editCat{{ $category->cid }}" class="inline-flex items-center space-x-2 text-sm p-1 cursor-pointer">
                    <input type="checkbox" id="editCat{{ $category->cid }}" name="categories[]" value="{{ $category->cid }}" class="edit-category-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded cursor-pointer">
                    <span class="truncate">{{ $category->category }}</span>
                  </label>
                  @endforeach
                </div>
              </div>

              <div>
                <span class="block text-sm font-medium mb-2">Status</span>
                <label for="editStatusToggle" class="inline-flex items-center cursor-pointer">
                  <input type="checkbox" id="editStatusToggle" class="sr-only peer">
                  <div class="relative w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                  <span id="statusLabel" class="ml-3 text-sm font-medium text-gray-700">Inactive</span>
                </label>
                <input type="hidden" name="a_status" id="editStatus">
              </div>
            </div>

            <div class="sticky bottom-0 bg-white pt-4 pb-4 border-t flex justify-end gap-2 px-6">
              <button type="button" onclick="closeEditModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">
                Cancel
              </button>
              <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">
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
      let rows = document.querySelectorAll('.activity-row');
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

      document.getElementById('viewName').textContent = data.a_name;
      document.getElementById('viewInfo').textContent = data.a_info || 'No info provided.';

      const imgElem = document.getElementById('viewImage');
      if (image) {
        imgElem.src = image;
        imgElem.style.display = 'block';
      } else {
        imgElem.style.display = 'none';
      }

      const statusElement = document.getElementById('viewStatus');
      if (data.a_status == 1) {
        statusElement.innerHTML = '<span class="text-green-600 font-semibold bg-green-50 px-2 py-1 rounded">Active</span>';
      } else {
        statusElement.innerHTML = '<span class="text-red-600 font-semibold bg-red-50 px-2 py-1 rounded">Inactive</span>';
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
      
      document.getElementById('removeImageFlag').value = '0';

      document.getElementById('editName').value = data.a_name;
      document.getElementById('editInfo').value = data.a_info;

      const imgPreview = document.getElementById('editMainImagePreview');
      if (image) {
        imgPreview.style.backgroundImage = `url(${image})`;
        imgPreview.style.backgroundSize = 'cover';
        imgPreview.style.backgroundPosition = 'center';
        imgPreview.innerHTML = `<button type="button" onclick="clearEditImage(event)" class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md"><i data-lucide="x" class="w-4 h-4"></i></button>`;
      } else {
        imgPreview.style.backgroundImage = 'none';
        imgPreview.innerHTML = '<i data-lucide="image-plus" class="w-8 h-8"></i>';
      }
      reRenderIcons();

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

    document.getElementById('editStatusToggle').addEventListener('change', function() {
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

    function clearMainImage(e, previewId, inputId) {
      e.stopPropagation();
      document.getElementById(inputId).value = '';
      const preview = document.getElementById(previewId);
      preview.style.backgroundImage = 'none';
      preview.innerHTML = '<i data-lucide="image-plus" class="w-8 h-8"></i>';
      reRenderIcons();
    }

    function clearEditImage(e) {
      e.stopPropagation();
      document.getElementById('editMainImageInput').value = '';
      document.getElementById('removeImageFlag').value = '1';
      const preview = document.getElementById('editMainImagePreview');
      preview.style.backgroundImage = 'none';
      preview.innerHTML = '<i data-lucide="image-plus" class="w-8 h-8"></i>';
      reRenderIcons();
    }

    const mainImageInput = document.getElementById('mainImageInput');
    const mainImagePreview = document.getElementById('mainImagePreview');

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

    const editMainImageInput = document.getElementById('editMainImageInput');
    const editMainImagePreview = document.getElementById('editMainImagePreview');

    if (editMainImagePreview && editMainImageInput) {
      editMainImagePreview.onclick = () => editMainImageInput.click();
      editMainImageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        document.getElementById('removeImageFlag').value = '0';
        const reader = new FileReader();
        reader.onload = (ev) => {
          editMainImagePreview.style.backgroundImage = `url(${ev.target.result})`;
          editMainImagePreview.style.backgroundSize = 'cover';
          editMainImagePreview.style.backgroundPosition = 'center';
          editMainImagePreview.innerHTML = `<button type="button" onclick="clearEditImage(event)" class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md"><i data-lucide="x" class="w-4 h-4"></i></button>`;
          reRenderIcons();
        };
        reader.readAsDataURL(file);
      });
    }
  </script>

</body>

</html>