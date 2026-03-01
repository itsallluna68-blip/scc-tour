<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tourist Places</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans text-gray-800">

      {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="flex-1 ml-60">
      {{-- Header --}}
      @include('components.header')

      {{-- Page Content --}}
      <main class="p-6">
        @yield('content')
      </main>
    </div>

  {{-- Main Section --}}
  <div class="ml-56 mt-2 flex-1 p-6">
    {{-- Header Row --}}
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-semibold text-indigo-900">Tourist Spots</h2>

      <button onclick="openModal()" 
              class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition">
        + Add Place
      </button>
    </div>

    {{-- Text Bar (Below Users title) --}}
  <form class="flex items-center gap-2 mb-5">
    <input 
      type="text" 
      name="search"
      placeholder="Search by name..."
      value=""
      class="w-full md:w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
    >
    <button 
      type="submit"
      class="bg-indigo-900 text-white px-4 py-2 rounded-md hover:bg-indigo-800 transition"
    >
      Search
    </button>
  </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
      <table class="w-full border border-gray-300 rounded-lg overflow-hidden text-sm">
        <thead class="bg-indigo-900 text-white">
          <tr>
            <th class="py-3 px-4 text-left w-16">ID</th>
            <th class="py-3 px-4 text-left w-1/4">Name</th>
            <th class="py-3 px-4 text-left w-40 ">Contact</th>
            <th class="py-3 px-4 text-left w-48">Email</th>
            <th class="py-3 px-4 text-left">Category</th>
            <th class="py-3 px-4 text-center w-24">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-300">
          @foreach($tplaces as $tplace)
            <tr class="hover:bg-gray-100">
              <td class="py-2 px-4">{{ $tplace->id }}</td>
              <td class="py-2 px-4">{{ $tplace->name }}</td>
              <td class="py-2 px-4">{{ $tplace->contact }}</td>
              <td class="py-2 px-4">{{ $tplace->email }}</td>
              <td class="py-2 px-4">{{ $tplace->categories }}
                @php
                $placeCategories = array_filter([$tplace->category1, $tplace->category2, $tplace->category3]);
              @endphp
              @if (!empty($placeCategories))
                <span class="text-gray-500 text-sm"> — {{ implode(', ', $placeCategories) }}</span>
              @endif
          </td>

              <td class="py-2 px-4 flex justify-center gap-2">
                <button onclick='openViewModal(@json($tplace))' 
                  class="text-blue-600 hover:text-blue-800 transition text-lg">👁️</button>

                <button onclick='openEditModal(@json($tplace))' 
                  class="text-indigo-700 hover:text-indigo-900 transition text-lg">✏️</button>

                <button onclick="confirmDelete({{ $tplace->id }})"
                  class="text-yellow-600 hover:text-yellow-800 transition text-lg">🗑️</button>

              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    </div>
      </div>

    <!-- 🧭 VIEW DETAILS MODAL -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 overflow-y-auto max-h-[90vh]">
    <h2 class="text-2xl font-semibold text-indigo-900 mb-4">Place Details</h2>

    <div id="viewDetails" class="space-y-2 text-gray-800">
      <!-- Details will be filled by JS -->
    </div>

    <div class="flex justify-end mt-4">
      <button onclick="closeViewModal()" 
        class="bg-indigo-900 text-white px-4 py-2 rounded-md hover:bg-indigo-800 transition">
        Close
      </button>
    </div>
  </div>
</div>

{{-- Add Tourist Place Modal --}}
<div id="addPlaceModal" 
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50 transition-opacity duration-300">
  <div class="bg-white rounded-lg p-6 w-full max-w-2xl shadow-lg overflow-y-auto max-h-[90vh] transform transition-all scale-95">
    <h3 class="text-xl font-semibold text-indigo-900 mb-4">Add Tourist Place</h3>

    <form id="addPlaceForm" 
          action="{{ route('touristplace.store') }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-4">
      @csrf

      {{-- ID (optional) --}}
      <div class="flex items-center gap-2">
        <input type="checkbox" id="idCheckbox" checked onchange="toggleIdInput()" class="rounded text-indigo-600">
        <label for="idCheckbox" class="text-sm">Auto-generate ID</label>
      </div>
      <input type="text" id="idInput" name="id" placeholder="Enter ID" disabled 
             class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">

      {{-- Name --}}
      <div>
        <label class="block text-sm font-medium">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required
               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
      </div>

      {{-- Contact --}}
      <div>
        <label class="block text-sm font-medium">Contact</label>
        <input type="text" name="contact" value="{{ old('contact') }}" 
               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
      </div>

      {{-- Description --}}
      <div>
        <label class="block text-sm font-medium">Description</label>
        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>{{ old('description') }}</textarea>
      </div>

      {{-- History --}}
      <div>
        <label class="block text-sm font-medium">History</label>
        <textarea name="history" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">{{ old('history') }}</textarea>
      </div>

      {{-- Links --}}
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Link 1</label>
          <input type="url" name="link1" value="{{ old('link1') }}"
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium">Link 2</label>
          <input type="url" name="link2" value="{{ old('link2') }}"
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>
      </div>

      {{-- Address & Email --}}
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Address</label>
          <input type="text" name="address" value="{{ old('address') }}" required
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>
        <div>
          <label class="block text-sm font-medium">Email</label>
          <input type="email" name="email" value="{{ old('email') }}"
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>
      </div>

      {{-- Image Uploads --}}
      <div>
        <label class="block text-sm font-medium mb-1">Images</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
          @for($i = 0; $i < 4; $i++)
            <input type="file" name="image{{ $i }}" accept="image/*"
                   class="border border-gray-300 rounded-md px-2 py-1 text-sm">
          @endfor
        </div>
      </div>

      {{-- Categories (Dropdowns) --}}
      <div class="grid grid-cols-3 gap-4">
        @for ($i = 1; $i <= 3; $i++)
          <div>
            <label class="block text-sm font-medium">Category {{ $i }}</label>
            <select name="category{{ $i }}" 
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
              <option value="">Select category</option>
              @foreach ($categories as $category)
                <option value="{{ $category->category }}"
                  {{ old('category'.$i) == $category->category ? 'selected' : '' }}>
                  {{ $category->category }}
                </option>
              @endforeach
            </select>
          </div>
        @endfor
      </div>


      <input type="hidden" name="status" value="1">

      {{-- Buttons --}}
      <div class="flex justify-between pt-4">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
          Save
        </button>
        <button type="button" onclick="closeAddPlaceModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
          Cancel
        </button>
      </div>
    </form>
  </div>

  <!-- ✏️ Edit Tourist Place Modal -->
  <div id="editPlaceModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-lg">
      <h3 class="text-xl font-semibold text-indigo-900 mb-4">Edit Tourist Place</h3>

      <form id="editPlaceForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" id="editPlaceId" name="id">

        <div>
          <label class="block text-sm font-medium">Name</label>
          <input type="text" id="editName" name="name" required
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>

        <div>
          <label class="block text-sm font-medium">Contact</label>
          <input type="text" id="editContact" name="contact"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>

        <div>
          <label class="block text-sm font-medium">Description</label>
          <textarea id="editDescription" name="description" rows="3"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium">History</label>
          <textarea id="editHistory" name="history" rows="3"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium">Address</label>
          <input type="text" id="editAddress" name="address"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>

        <div>
          <label class="block text-sm font-medium">Email</label>
          <input type="email" id="editEmail" name="email"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>

        <div class="grid grid-cols-3 gap-2">
          <div>
            <label class="block text-sm font-medium">Category 1</label>
            <input type="text" id="editCategory1" name="category1" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
          </div>
          <div>
            <label class="block text-sm font-medium">Category 2</label>
            <input type="text" id="editCategory2" name="category2" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
          </div>
          <div>
            <label class="block text-sm font-medium">Category 3</label>
            <input type="text" id="editCategory3" name="category3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
          </div>
        </div>

        <div class="mt-4">
          <label class="block text-sm font-medium">Images</label>
          <input type="file" name="image0" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mb-2">
          <input type="file" name="image1" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mb-2">
          <input type="file" name="image2" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mb-2">
          <input type="file" name="image3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mb-2">
        </div>

        <input type="hidden" name="status" value="1">

        <div class="flex justify-between pt-4">
          <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">Update</button>
          <button type="button" onclick="closeEditModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">Cancel</button>
        </div>
      </form>
    </div>
  </div>



</div>

{{-- js --}}
<script>
  function openViewModal(tplace) {
    const modal = document.getElementById('viewModal');
    const detailsDiv = document.getElementById('viewDetails');

    const fields = [
      'id', 'name', 'contact', 'description', 'history',
      'link1', 'link2', 'address', 'email',
      'image0', 'image1', 'image2', 'image3',
      'category1', 'category2', 'category3'
    ];

    detailsDiv.innerHTML = fields.map(field => {
      const value = tplace[field] ?? '<span class="text-gray-400">N/A</span>';

      // 🖼️ For image fields, get from /image/ instead of /storage/
      if (['image0', 'image1', 'image2', 'image3'].includes(field)) {
        if (tplace[field]) {
          return `
            <div class="mt-3">
              <strong class="capitalize">${field}:</strong><br>
              <img src="/image/${tplace[field]}" alt="${tplace[field]}" 
                   class="rounded-lg mt-1 w-48 h-32 object-cover border" />
              <p class="text-sm text-gray-500 mt-1">${tplace[field]}</p>
            </div>
          `;
        } else {
          return `<p><strong class="capitalize">${field}:</strong> <span class="text-gray-400">N/A</span></p>`;
        }
      }

      // 🧩 Default (non-image) fields
        return `<p><strong class="capitalize">${field}:</strong> ${value}</p>`;
      }).join('');

      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeViewModal() {
      const modal = document.getElementById('viewModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

      function toggleIdInput() {
      const checkbox = document.getElementById('idCheckbox');
      const input = document.getElementById('idInput');
      input.disabled = checkbox.checked;
      if (checkbox.checked) input.value = '';
    }

    function closeAddPlaceModal() {
      document.getElementById('addPlaceModal').classList.add('hidden');
    }

      // Open the modal
  function openModal() {
    document.getElementById('addPlaceModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden'); // Prevent background scroll
  }

  // Close the modal
  function closeAddPlaceModal() {
    document.getElementById('addPlaceModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }

  // Optional: close modal when clicking outside the box
  window.addEventListener('click', function (event) {
    const modal = document.getElementById('addPlaceModal');
    if (event.target === modal) {
      closeAddPlaceModal();
    }
  });

  // Optional: toggle manual ID input
  function toggleIdInput() {
    const checkbox = document.getElementById('idCheckbox');
    const input = document.getElementById('idInput');
    input.disabled = checkbox.checked;
    if (checkbox.checked) input.value = '';
  }

  // EDIT TOURIST SPOT
  // 🟢 Open Edit Modal and populate fields
  function openEditModal(place) {
    // Open the modal
    document.getElementById('editPlaceModal').classList.remove('hidden');

    // Fill in existing data
    document.getElementById('editPlaceId').value = place.id || '';
    document.getElementById('editName').value = place.name || '';
    document.getElementById('editContact').value = place.contact || '';
    document.getElementById('editDescription').value = place.description || '';
    document.getElementById('editHistory').value = place.history || '';
    document.getElementById('editAddress').value = place.address || '';
    document.getElementById('editEmail').value = place.email || '';
    document.getElementById('editCategory1').value = place.category1 || '';
    document.getElementById('editCategory2').value = place.category2 || '';
    document.getElementById('editCategory3').value = place.category3 || '';

    // Update form action URL dynamically (for PUT request)
    const form = document.getElementById('editPlaceForm');
    form.action = `/admin/touristplace/update/${place.id}`;
  }

  // 🔴 Close Modal
  function closeEditModal() {
    document.getElementById('editPlaceModal').classList.add('hidden');
  }

</script>




</body>
</html>
