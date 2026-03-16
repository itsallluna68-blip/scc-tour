<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>Users List</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>

<body class="bg-gray-100 font-sans text-gray-800">

  @include('components.sidebar')

  <div class="flex-1 ml-60">
    @include('components.header')

    <main class="p-6">
      @yield('content')
    </main>
  </div>

  @if (session('success'))
  <div id="alert-success"
    class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2 transition-all duration-500">
    <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
  </div>

  <script>
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

  <main class="ml-56 mt-2 flex-1 p-6">
    @if(auth()->check() && auth()->user()->usertype === 'admin')

    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-indigo-900 flex items-center gap-2">
        <i data-lucide="users" class="w-6 h-6"></i> Users List
      </h2>

      <button onclick="openModal()"
        class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition flex items-center gap-2 font-medium">
        <i data-lucide="user-plus" class="w-4 h-4"></i> Add User
      </button>
    </div>

    <div class="flex items-center gap-2 mb-6">
      <div class="relative w-full md:w-1/3">
        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input
          type="text"
          id="searchInput"
          placeholder="Search by name, username, or role"
          class="w-full border border-gray-300 rounded-md pl-9 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
      </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
      <table class="w-full text-sm text-left">
        <thead class="bg-indigo-900 text-white">
          <tr>
            <th class="py-3 px-4 w-16">ID</th>
            <th class="py-3 px-4">Full Name</th>
            <th class="py-3 px-4 w-49">Username</th>
            <th class="py-3 px-4 w-32">User Type</th>
            <th class="py-3 px-4 text-center w-36">Actions</th>
          </tr>
        </thead>
        <tbody id="usersTableBody" class="divide-y divide-gray-200">
          @forelse($users as $user)
          <tr class="hover:bg-gray-50 transition">
            <td class="py-3 px-4">{{ $user->id }}</td>
            <td class="py-3 px-4 font-medium text-gray-900">{{ $user->lname }}, {{ $user->fname }} {{ $user->mname }}</td>
            <td class="py-3 px-4 text-gray-600">{{ $user->username }}</td>
            <td class="py-3 px-4">
              <span class="px-2 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded text-xs font-semibold">
                {{ ucfirst($user->usertype) }}
              </span>
            </td>
            <td class="py-3 px-4 flex justify-center gap-2">
              <button type="button"
                data-user="{{ json_encode($user) }}"
                onclick="openViewModal(this)"
                class="text-green-500 hover:text-green-700 hover:bg-green-50 p-1.5 rounded-md transition flex items-center justify-center" title="View User">
                <i data-lucide="eye" class="w-5 h-5"></i>
              </button>

              <button type="button"
                data-user="{{ json_encode($user) }}"
                onclick="openEditModal(this)"
                class="text-indigo-500 hover:text-indigo-700 hover:bg-indigo-50 p-1.5 rounded-md transition flex items-center justify-center" title="Edit User">
                <i data-lucide="edit" class="w-5 h-5"></i>
              </button>

              <button type="button"
                data-id="{{ $user->id }}"
                onclick="openDeleteModal(this)"
                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-md transition flex items-center justify-center" title="Deactivate User">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="py-8 text-center text-gray-500">
              <div class="flex flex-col items-center justify-center">
                <i data-lucide="users" class="w-10 h-10 text-gray-300 mb-2"></i>
                <p class="font-medium">No users found.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @else
    <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center mt-6 shadow-sm">
      <i data-lucide="shield-alert" class="h-16 w-16 mx-auto mb-4 text-red-500"></i>
      <h3 class="text-xl font-bold text-red-900 mb-2">Access Denied</h3>
      <p class="text-red-700">You do not have permission to access user management. Only Admin can manage users.</p>
    </div>
    @endif

    @if(auth()->check() && auth()->user()->usertype === 'admin')

    <div id="viewUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl">
        <h3 class="text-xl font-bold text-indigo-900 mb-4 border-b pb-3 flex items-center gap-2">
          <i data-lucide="user" class="w-5 h-5"></i> User Details
        </h3>

        <div class="space-y-4 text-sm text-gray-700">
          <div class="flex flex-col">
            <span class="text-gray-500 font-semibold mb-1">Full Name</span>
            <span id="viewFullName" class="bg-gray-50 p-2 rounded border border-gray-200 font-medium"></span>
          </div>
          <div class="flex flex-col">
            <span class="text-gray-500 font-semibold mb-1">Username</span>
            <span id="viewUsername" class="bg-gray-50 p-2 rounded border border-gray-200"></span>
          </div>
          <div class="flex flex-col">
            <span class="text-gray-500 font-semibold mb-1">User Type</span>
            <span id="viewUsertype" class="bg-gray-50 p-2 rounded border border-gray-200 capitalize"></span>
          </div>
          <div class="flex flex-col">
            <span class="text-gray-500 font-semibold mb-1">Status</span>
            <span id="viewStatus" class="bg-gray-50 p-2 rounded border border-gray-200 capitalize"></span>
          </div>
        </div>

        <div class="flex justify-end pt-5 mt-4 border-t">
          <button type="button" onclick="closeViewModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-md text-sm font-medium transition shadow-sm">Close</button>
        </div>
      </div>
    </div>

    <div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-xl p-6 w-full max-w-lg shadow-2xl">
        <h3 class="text-xl font-bold text-indigo-900 mb-4 border-b pb-3 flex items-center gap-2">
          <i data-lucide="user-plus" class="w-5 h-5"></i> Add New User
        </h3>

        <form id="addUserForm" action="{{ route('users.store') }}" method="POST" onsubmit="return validateForm(event)" class="space-y-4">
          @csrf

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">First Name</label>
            <input type="text" name="fname" value="{{ old('fname') }}" required
              class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Middle Name</label>
            <input type="text" name="mname" value="{{ old('mname') }}"
              class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Last Name</label>
            <input type="text" name="lname" value="{{ old('lname') }}" required
              class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Username</label>
            <input type="text" name="username" placeholder="Create username" value="{{ old('username') }}" required
              class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Password</label>
            <div class="relative">
              <input type="password" name="password" id="password" placeholder="Create password" required
                class="w-full border border-gray-300 rounded-md pl-3 pr-10 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              <button type="button" onclick="togglePassword('password', 'pw-eye', 'pw-eye-off')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                <i id="pw-eye" data-lucide="eye" class="w-4 h-4"></i>
                <i id="pw-eye-off" data-lucide="eye-off" class="w-4 h-4 hidden text-indigo-600"></i>
              </button>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Confirm Password</label>
            <div class="relative">
              <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" required
                class="w-full border border-gray-300 rounded-md pl-3 pr-10 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              <button type="button" onclick="togglePassword('confirm_password', 'cpw-eye', 'cpw-eye-off')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                <i id="cpw-eye" data-lucide="eye" class="w-4 h-4"></i>
                <i id="cpw-eye-off" data-lucide="eye-off" class="w-4 h-4 hidden text-indigo-600"></i>
              </button>
            </div>
            <p id="passwordError" class="text-red-600 text-xs mt-1.5 font-medium hidden">Passwords do not match</p>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">User Type</label>
            <select name="usertype" required
              class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              <option value="">Select User Type</option>
              <option value="admin" {{ old('usertype') == 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="staff" {{ old('usertype') == 'staff' ? 'selected' : '' }}>Staff</option>
            </select>
          </div>

          <div class="flex justify-end gap-3 pt-5 mt-2 border-t">
            <button type="button" onclick="closeModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2.5 rounded-md text-sm font-medium transition border border-gray-300">Cancel</button>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-md text-sm font-medium transition shadow-sm">Save User</button>
          </div>
        </form>
      </div>
    </div>

    <div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-xl p-6 w-full max-w-lg shadow-2xl">
        <h3 class="text-xl font-bold text-indigo-900 mb-4 border-b pb-3 flex items-center gap-2">
          <i data-lucide="user-pen" class="w-5 h-5"></i> Edit User
        </h3>

        <form id="editUserForm" method="POST" onsubmit="return validateEditForm(event)" class="space-y-4">
          @csrf
          @method('PUT')

          <input type="hidden" id="editUserId" name="id">

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">First Name</label>
            <input type="text" id="editFname" name="fname" required
              class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Middle Name</label>
            <input type="text" id="editMname" name="mname"
              class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Last Name</label>
            <input type="text" id="editLname" name="lname" required
              class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Username</label>
            <input type="text" id="editUsername" name="username" required
              class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">New Password</label>
            <div class="relative">
              <input type="password" id="editPassword" name="password"
                placeholder="Leave blank to keep current password"
                class="w-full border border-gray-300 rounded-md pl-3 pr-10 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              <button type="button" onclick="togglePassword('editPassword', 'epw-eye', 'epw-eye-off')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                <i id="epw-eye" data-lucide="eye" class="w-4 h-4"></i>
                <i id="epw-eye-off" data-lucide="eye-off" class="w-4 h-4 hidden text-indigo-600"></i>
              </button>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Confirm New Password</label>
            <div class="relative">
              <input type="password" id="editConfirmPassword" name="confirm_password"
                placeholder="Confirm new password"
                class="w-full border border-gray-300 rounded-md pl-3 pr-10 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              <button type="button" onclick="togglePassword('editConfirmPassword', 'ecpw-eye', 'ecpw-eye-off')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                <i id="ecpw-eye" data-lucide="eye" class="w-4 h-4"></i>
                <i id="ecpw-eye-off" data-lucide="eye-off" class="w-4 h-4 hidden text-indigo-600"></i>
              </button>
            </div>
            <p id="editPasswordError" class="text-red-600 text-xs mt-1.5 font-medium hidden">Passwords do not match</p>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">User Type</label>
            <select id="editUsertype" name="usertype" required
              class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              <option value="admin">Admin</option>
              <option value="staff">Staff</option>
            </select>
          </div>

          <div class="flex justify-end gap-3 pt-5 mt-2 border-t">
            <button type="button" onclick="closeEditModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2.5 rounded-md text-sm font-medium transition border border-gray-300">Cancel</button>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-md text-sm font-medium transition shadow-sm">Update User</button>
          </div>
        </form>
      </div>
    </div>

    <div id="deleteUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-xl p-6 w-full max-w-sm shadow-2xl text-center transform transition-all">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4 mt-2">
          <i data-lucide="triangle-alert" class="w-8 h-8 text-red-600"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Deactivate User</h3>
        <p class="text-sm text-gray-500 mb-6">Are you sure you want to deactivate this user? They will no longer be able to log in to the system.</p>
        <div class="flex justify-center gap-3">
          <button type="button" onclick="closeDeleteModal()" class="bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 px-5 py-2.5 rounded-lg text-sm font-medium transition">Cancel</button>
          <a id="confirmDeleteLink" href="#" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm flex items-center">Deactivate</a>
        </div>
      </div>
    </div>

    @endif

  </main>


  <script>
    document.addEventListener("DOMContentLoaded", function() {
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    });

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#usersTableBody tr');

        rows.forEach(row => {
          let text = row.textContent.toLowerCase();
          if (text.includes(filter)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    }

    function togglePassword(inputId, eyeId, eyeOffId) {
      const input = document.getElementById(inputId);
      const eye = document.getElementById(eyeId);
      const eyeOff = document.getElementById(eyeOffId);

      if (input.type === "password") {
        input.type = "text";
        eye.classList.add("hidden");
        eyeOff.classList.remove("hidden");
      } else {
        input.type = "password";
        eye.classList.remove("hidden");
        eyeOff.classList.add("hidden");
      }
    }
  </script>

  @if(auth()->check() && auth()->user()->usertype === 'admin')
  <script>
    const modal = document.getElementById("addUserModal");
    const viewModal = document.getElementById("viewUserModal");
    const editModal = document.getElementById("editUserModal");
    const deleteModal = document.getElementById("deleteUserModal");
    const confirmDeleteLink = document.getElementById("confirmDeleteLink");

    function openViewModal(btnElement) {
      const user = JSON.parse(btnElement.dataset.user);

      const mname = user.mname ? user.mname + ' ' : '';
      document.getElementById("viewFullName").textContent = `${user.fname} ${mname}${user.lname}`;
      document.getElementById("viewUsername").textContent = user.username;
      document.getElementById("viewUsertype").textContent = user.usertype;
      document.getElementById("viewStatus").textContent = user.status;

      viewModal.classList.remove("hidden");
    }

    function closeViewModal() {
      viewModal.classList.add("hidden");
    }

    function openModal() {
      modal.classList.remove("hidden");
      document.getElementById("passwordError").classList.add("hidden");
      document.getElementById("password").value = "";
      document.getElementById("confirm_password").value = "";
      document.getElementById("confirm_password").setCustomValidity("");
      document.getElementById("password").type = "password";
      document.getElementById("confirm_password").type = "password";
      document.getElementById("pw-eye").classList.remove("hidden");
      document.getElementById("pw-eye-off").classList.add("hidden");
      document.getElementById("cpw-eye").classList.remove("hidden");
      document.getElementById("cpw-eye-off").classList.add("hidden");
    }

    function closeModal() {
      modal.classList.add("hidden");
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

    const editForm = document.getElementById("editUserForm");

    document.getElementById("password").addEventListener("input", checkPasswordMatch);
    document.getElementById("confirm_password").addEventListener("input", checkPasswordMatch);
    document.getElementById("editPassword").addEventListener("input", checkEditPasswordMatch);
    document.getElementById("editConfirmPassword").addEventListener("input", checkEditPasswordMatch);

    function openEditModal(btnElement) {
      const user = JSON.parse(btnElement.dataset.user);

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
      document.getElementById("editPassword").type = "password";
      document.getElementById("editConfirmPassword").type = "password";
      document.getElementById("epw-eye").classList.remove("hidden");
      document.getElementById("epw-eye-off").classList.add("hidden");
      document.getElementById("ecpw-eye").classList.remove("hidden");
      document.getElementById("ecpw-eye-off").classList.add("hidden");

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

    function openDeleteModal(btnElement) {
      const id = btnElement.dataset.id;
      confirmDeleteLink.href = `/users/delete/${id}`;
      deleteModal.classList.remove("hidden");
    }

    function closeDeleteModal() {
      deleteModal.classList.add("hidden");
    }
  </script>
  @endif

</body>

</html>