<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>Manage Reviews</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>

<body class="bg-gray-100 font-sans text-gray-800">

  @include('components.sidebar')

  <div class="flex-1 ml-48">
    @include('components.header')

    <main class="p-6 pt-20">

      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-indigo-900">User Reviews</h2>
      </div>

      <form method="GET" class="flex flex-wrap items-center gap-2 mb-6">

        <div class="relative w-full md:w-1/4">
          <input type="text" name="search" placeholder="Search name or email..." value="{{ request('search') }}" class="w-full border border-gray-300 rounded-md px-3 py-2
             focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="w-full md:w-1/4">
          <input type="text" name="place" placeholder="Search place..." value="{{ request('place') }}" class="w-full border border-gray-300 rounded-md px-3 py-2
             focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <a href="{{ route('admin.reviews.index') }}"
          class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">
          Clear
        </a>

        <button type="submit" class="bg-indigo-900 text-white px-4 py-2 rounded-md hover:bg-indigo-800">
          Search
        </button>

        <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 
           focus:outline-none focus:ring-2 focus:ring-indigo-500">

          <option value="">All Status</option>
          <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
            Pending
          </option>
          <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
            Approved
          </option>

        </select>

      </form>

      {{-- Reviews Table --}}
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
              <th class="p-3 w-36">Place</th>
              <th class="p-3 w-24">Name</th>
              <th class="p-3 w-32">Email</th>
              <th class="p-3 w-24">Rating</th>
              <th class="p-3">Feedback</th>
              <th class="p-3 w-32">Status</th>
              <th class="p-3 text-center w-40">Actions</th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-300">
            @foreach($reviews as $review)
              <tr class="hover:bg-gray-50 transition">
                <td class="p-3">{{ $review->place->name ?? 'N/A' }}</td>
                <td class="p-3">{{ $review->name }}</td>
                <td class="p-3">{{ $review->email }}</td>
                <td class="p-3">⭐ {{ $review->ratings }}</td>
                <td class="p-3 max-w-xs truncate" title="{{ $review->feedback }}">{{ $review->feedback }}</td>
                <td class="p-3">
                  @if($review->status == 0)
                    <span class="text-yellow-600 font-semibold">Pending</span>
                  @else
                    <span class="text-green-600 font-semibold">Approved</span>
                  @endif
                </td>

                <td class="p-3">
                  <div class="flex items-center justify-center gap-2">

                    {{-- View --}}
                    <button type="button" onclick='openViewModal(@json($review))'
                      class="bg-blue-50 text-blue-600 hover:bg-blue-100 p-2 rounded-md transition flex items-center justify-center"
                      title="View">
                      <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>

                    {{-- Approve --}}
                    @if($review->status == 0)
                      <form action="{{ route('admin.reviews.approve', $review->rid) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                          class="bg-green-50 text-green-600 hover:bg-green-100 p-2 rounded-md transition flex items-center justify-center"
                          title="Approve">
                          <i data-lucide="check" class="w-4 h-4"></i>
                        </button>
                      </form>
                    @endif

                    {{-- Delete --}}
                    <form action="{{ route('admin.reviews.destroy', $review->rid) }}" method="POST"
                      onsubmit="return confirm('Delete this review?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        class="bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded-md transition flex items-center justify-center"
                        title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                      </button>
                    </form>

                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </main>
  </div>

  {{-- VIEW MODAL --}}
  <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">

      <h2 class="text-xl font-semibold text-indigo-900 mb-4">Review Details</h2>

      <div id="viewDetails" class="space-y-2 text-gray-800"></div>

      <div class="flex justify-end mt-4">
        <button onclick="closeViewModal()" class="bg-indigo-900 text-white px-4 py-2 rounded-md hover:bg-indigo-800">
          Close
        </button>
      </div>

    </div>
  </div>

  <script>
    function openViewModal(review) {
      const modal = document.getElementById('viewModal');
      const details = document.getElementById('viewDetails');

      details.innerHTML = `
        <p><strong>Name:</strong> ${review.name}</p>
        <p><strong>Email:</strong> ${review.email}</p>
        <p><strong>Rating:</strong> ⭐ ${review.ratings}</p>
        <p><strong>Feedback:</strong> ${review.feedback}</p>
        <p><strong>Date:</strong> ${review.date}</p>
      `;

      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeViewModal() {
      const modal = document.getElementById('viewModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  </script>

</body>

</html>