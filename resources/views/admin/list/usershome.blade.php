<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users List</title>
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

    <script> //alert
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
  @if(auth()->check() && auth()->user()->usertype === 'Superadmin')

  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-semibold text-indigo-900">Users List</h2>

    <button onclick="openModal()" 
            class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition">
      + Add User
    </button>
  </div>

  {{-- search --}}
  <form id="searchForm" method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2 mb-5">
    <input 
      type="text" 
      id="searchUser"
      name="search"
      placeholder="Search by name..."
      value="{{ request('search') }}"
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
          <th class="py-3 px-4 text-left">Full Name</th>
          <!-- Birthdate column removed -->
          <th class="py-3 px-4 text-left w-49">Username</th>
          <th class="py-3 px-4 text-left w-32">User Type</th>
          <th class="py-3 px-4 text-center w-24">Actions</th>
        </tr>
      </thead>
      <tbody id="usersTableBody" class="bg-white divide-y divide-gray-300">
        @foreach($users as $user)
          <tr class="hover:bg-gray-100">
            <td class="py-2 px-4">{{ $user->id }}</td>
            <td class="py-2 px-4">{{ $user->lname }}, {{ $user->fname }} {{ $user->mname }}</td>
            <!-- birthdate cell removed -->
            <td class="py-2 px-4">{{ $user->username }}</td>
            <td class="py-2 px-4">{{ $user->usertype }}</td>
            <td class="py-2 px-4 flex justify-center gap-2">
                  {{-- <button onclick="openModal()" 
                          class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition">
                    + Add User
                  </button> --}} 
              <button onclick='openEditModal(@json($user))' 
                class="text-indigo-700 hover:text-indigo-900 transition text-lg">
                ✏️</button>
              <button onclick="confirmDelete({{ $user->id }})"
                class="text-yellow-600 hover:text-yellow-800 transition text-lg">🗑️</button>
                      
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @else
    <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
      </svg>
      <h3 class="text-xl font-semibold text-red-900 mb-2">Access Denied</h3>
      <p class="text-red-700">You do not have permission to access user management. Only Super Admin can manage users.</p>
    </div>
  @endif

  {{-- Modal --}}
  @if(auth()->check() && auth()->user()->usertype === 'Superadmin')
  <div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-lg">
      <h3 class="text-xl font-semibold text-indigo-900 mb-4">Add User</h3>

      <form id="addUserForm" action="{{ route('users.store') }}" method="POST" onsubmit="return validateForm(event)" class="space-y-3">
        @csrf

        {{-- ID --}}
        {{-- <input type="text" id="idInput" name="id" placeholder="Auto-generate ID" disabled 
               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"> --}}

        {{-- First Name --}}
        <div>
          <label class="block text-sm font-medium">First Name</label>
          <input type="text" name="fname" value="{{ old('fname') }}" required
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>

        {{-- Middle Name --}}
        <div>
          <label class="block text-sm font-medium">Middle Name</label>
          <input type="text" name="mname" value="{{ old('mname') }}"
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>

        {{-- Last Name --}}
        <div>
          <label class="block text-sm font-medium">Last Name</label>
          <input type="text" name="lname" value="{{ old('lname') }}" required
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>

        {{-- Birthdate --}}
        {{-- <div>
          <label class="block text-sm font-medium">Birthdate</label>
          <input type="date" name="bdate" value="{{ old('bdate') }}" required
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div> --}}

        {{-- Username --}}
        <div>
          <label class="block text-sm font-medium">Username</label>
          <input type="text" name="username" placeholder="Create username" value="{{ old('username') }}" required
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>

        {{-- Password --}}
        <div>
          <label class="block text-sm font-medium">Password</label>
          <input type="password" name="password" id="password" placeholder="Create password" required
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>

        {{-- Confirm Password --}}
        <div>
          <label class="block text-sm font-medium">Confirm Password</label>
          <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required
                 class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
          <p id="passwordError" class="text-red-600 text-xs mt-1 hidden">Passwords do not match</p>
        </div>

        {{-- User Type --}}
        <div>
          <label class="block text-sm font-medium">User Type</label>
          <select name="usertype" required
                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Select User Type</option>
            <option value="Super Admin" {{ old('usertype') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
            <option value="Admin" {{ old('usertype') == 'Admin' ? 'selected' : '' }}>Admin</option>
            <option value="Staff" {{ old('usertype') == 'Staff' ? 'selected' : '' }}>Staff</option>
          </select>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-between pt-4">
          <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">Save</button>
          <button type="button" onclick="closeModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">Cancel</button>
        </div>
      </form>
    </div>
  </div>

    <!-- Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-lg shadow-lg">
    <h3 class="text-xl font-semibold text-indigo-900 mb-4">Edit User</h3>

    <form id="editUserForm" method="POST" onsubmit="return validateEditForm(event)">
      @csrf
      @method('PUT')

      <input type="hidden" id="editUserId" name="id">

      <div>
        <label class="block text-sm font-medium">First Name</label>
        <input type="text" id="editFname" name="fname" required
               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium">Middle Name</label>
        <input type="text" id="editMname" name="mname"
               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium">Last Name</label>
        <input type="text" id="editLname" name="lname" required
               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
      </div>



      <div>
        <label class="block text-sm font-medium">Username</label>
        <input type="text" id="editUsername" name="username" required
               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium">New Password</label>
        <input type="password" id="editPassword" name="password"
              placeholder="Leave blank to keep current password"
              class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
      </div>

      <div>
        <label class="block text-sm font-medium">Confirm New Password</label>
        <input type="password" id="editConfirmPassword" name="confirm_password"
              placeholder="Confirm new password"
              class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
        <p id="editPasswordError" class="text-red-600 text-xs mt-1 hidden">Passwords do not match</p>
      </div>

      <div>
        <label class="block text-sm font-medium">User Type</label>
        <select id="editUsertype" name="usertype"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
          <option value="Super Admin">Super Admin</option>
          <option value="Admin">Admin</option>
          <option value="Staff">Staff</option>
        </select>
      </div>

      <div class="flex justify-between pt-4">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">Update</button>
        <button type="button" onclick="closeEditModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">Cancel</button>
      </div>
    </form>
  </div>
</div>
@endif




<script>
@if(auth()->check() && auth()->user()->usertype === 'Superadmin')
  const modal = document.getElementById("addUserModal"); 
  const idInput = document.getElementById("idInput"); 
  const idCheckbox = document.getElementById("idCheckbox"); 

  // add user
  function openModal() {
    modal.classList.remove("hidden"); 
    document.getElementById("passwordError").classList.add("hidden");
    document.getElementById("password").value = "";
    document.getElementById("confirm_password").value = "";
    document.getElementById("confirm_password").setCustomValidity("");
  }

  function checkPasswordMatch() {
    const pw = document.getElementById("password").value;
    const cpw = document.getElementById("confirm_password").value;
    const errorEl = document.getElementById("passwordError");
    if (pw && cpw && pw !== cpw) {
      errorEl.classList.remove("hidden");
      document.getElementById("confirm_password").setCustomValidity("Passwords do not match");
    } else {
      errorEl.classList.add("hidden");
      document.getElementById("confirm_password").setCustomValidity("");
    }
  }

  function checkEditPasswordMatch() {
    const pw = document.getElementById("editPassword").value;
    const cpw = document.getElementById("editConfirmPassword").value;
    const errorEl = document.getElementById("editPasswordError");
    if ((pw || cpw) && pw !== cpw) {
      errorEl.classList.remove("hidden");
      document.getElementById("editConfirmPassword").setCustomValidity("Passwords do not match");
    } else {
      errorEl.classList.add("hidden");
      document.getElementById("editConfirmPassword").setCustomValidity("");
    }
  }

  function closeModal() {
    modal.classList.add("hidden");
  }

  function validateForm(event) {
    const inputs = document.querySelectorAll("#addUserForm input[required], #addUserForm select[required]");

    for (let input of inputs) {
      if (!input.value.trim()) {
        alert("Please fill out all required fields.");
        event.preventDefault(); 
        return false; 
      }
    }

    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;

    if (password !== confirmPassword) {
      alert("Passwords do not match. Please try again.");
      event.preventDefault();
      return false;
    }

    return true;
  }

  const editModal = document.getElementById("editUserModal");
  const editForm = document.getElementById("editUserForm");

  // add event listeners for real-time match
  document.getElementById("password").addEventListener("input", checkPasswordMatch);
  document.getElementById("confirm_password").addEventListener("input", checkPasswordMatch);
  document.getElementById("editPassword").addEventListener("input", checkEditPasswordMatch);
  document.getElementById("editConfirmPassword").addEventListener("input", checkEditPasswordMatch);

  // edit modal
  function openEditModal(user) {
    editModal.classList.remove("hidden");

    document.getElementById("editUserId").value = user.id;
    document.getElementById("editFname").value = user.fname;
    document.getElementById("editMname").value = user.mname;
    document.getElementById("editLname").value = user.lname;

    document.getElementById("editUsername").value = user.username;
    document.getElementById("editUsertype").value = user.usertype;

    document.getElementById("editPassword").value = "";
    document.getElementById("editConfirmPassword").value = "";
    document.getElementById("editPasswordError").classList.add("hidden");
    document.getElementById("editConfirmPassword").setCustomValidity("");

    editForm.action = `/users/update/${user.id}`;
  }


  function closeEditModal() {
    editModal.classList.add("hidden");
  }

  function validateEditForm(event) {
    const inputs = document.querySelectorAll("#editUserForm input[required], #editUserForm select[required]");
    for (let input of inputs) {
      if (!input.value.trim()) {
        alert("Please fill out all required fields.");
        event.preventDefault();
        return false;
      }
    }

    const editPassword = document.getElementById("editPassword").value;
    const editConfirmPassword = document.getElementById("editConfirmPassword").value;

    if (editPassword || editConfirmPassword) {
      if (editPassword !== editConfirmPassword) {
        alert("Passwords do not match. Please try again.");
        event.preventDefault();
        return false;
      }
    }

    return true;
  }

  // trash bin
      function confirmDelete(id) {
    if (confirm("Are you sure you want to deactivate this user?")) {
      window.location.href = `/users/delete/${id}`;
    }
  }
  // search
    document.getElementById('searchUser').addEventListener('keyup', function() {
      const query = this.value;

      fetch(`/admin/users/search?query=${query}`)
        .then(response => response.text())
        .then(html => {
          document.getElementById('usersTableBody').innerHTML = html;
        })
        .catch(error => console.error('Search error:', error));
    });

@endif

</script>


</body>
</html>
