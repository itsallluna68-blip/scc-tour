<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users Trash List</title>
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

  @if (session('success'))
    <div id="alert-success" 
        class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
      ✅ {{ session('success') }}
    </div>

    <script>
      // Automatically hide the alert after 3 seconds
      setTimeout(() => {
        const alert = document.getElementById('alert-success');
        if (alert) {
          alert.style.transition = 'opacity 0.5s ease';
          alert.style.opacity = '0';
          setTimeout(() => alert.remove(), 1000);
        }
      }, 3000);
    </script>
  @endif

{{-- Main Section --}}
<main class="ml-56 mt-2 flex-1 p-6">
  {{-- Header Row --}}
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-semibold text-red-900">Deleted Users</h2>

  </div>

  {{-- Text Bar (Below Users title) --}}
  <form method="GET" action="{{ route('bin.users') }}" class="flex items-center gap-2 mb-5">
    <input 
      type="text" 
      name="search"
      placeholder="Search by name..."
      value="{{ request('search') }}"
      class="w-full md:w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
    <button 
      type="submit"
      class="bg-red-900 text-white px-4 py-2 rounded-md hover:bg-red-800 transition"
    >
      Search
    </button>
  </form>

  {{-- Table --}}
  <div class="overflow-x-auto">
    <table class="w-full border border-gray-300 rounded-lg overflow-hidden text-sm">
      <thead class="bg-red-900 text-white">
        <tr>
          <th class="py-3 px-4 text-left w-16">ID</th>
          <th class="py-3 px-4 text-left">Full Name</th>
          <th class="py-3 px-4 text-left w-34">Birthdate</th>
          <th class="py-3 px-4 text-left w-49">Username</th>
          <th class="py-3 px-4 text-left w-32">User Type</th>
          <th class="py-3 px-4 text-center w-24">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-300">
        @foreach($users as $user)
          <tr class="hover:bg-gray-100">
            <td class="py-2 px-4">{{ $user->id }}</td>
            <td class="py-2 px-4">{{ $user->fname }} {{ $user->mname }} {{ $user->lname }}</td>
            <td class="py-2 px-4">{{ $user->bdate }}</td>
            <td class="py-2 px-4">{{ $user->username }}</td>
            <td class="py-2 px-4">{{ $user->usertype }}</td>
            <td class="py-2 px-4 flex justify-center gap-2">

                  {{-- <button onclick="openModal()" 
                          class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition">
                    + Add User
                  </button> --}}
              <button onclick="restoreUser({{ $user->id }})"
                    class="px-3 py-1 rounded hover:bg-green-50 transition">♻️ Restore</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</main>

<script>
    //  Restore a deleted user (set status = 1)
    function restoreUser(id) {
        if (confirm('Are you sure you want to restore this user?')) {
        fetch(`/bin/users/${id}/restore`, {
            method: 'PATCH',
            headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}', // ✅ needed for Laravel PATCH
            'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            alert(data.message);
            location.reload(); // Refresh the page to update list
            } else {
            alert('Failed to restore user.');
            }
        })
        .catch(error => console.error('Error restoring user:', error));
        }
    }

      // Search
    document.getElementById('searchUser').addEventListener('keyup', function() {
      const query = this.value;

      fetch(`/admin/users/search?query=${query}`)
        .then(response => response.text())
        .then(html => {
          document.getElementById('usersTableBody').innerHTML = html;
        })
        .catch(error => console.error('Search error:', error));
    });

</script>


</body>
</html>
