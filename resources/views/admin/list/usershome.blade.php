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
      @if(auth()->check() && auth()->user()->usertype === 'admin')
      <h2 class="text-2xl font-semibold text-indigo-900 flex items-center gap-2 mb-6">
        <i data-lucide="users" class="w-6 h-6"></i> Users List
      </h2>

      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div class="relative w-full md:w-80">
          <label for="searchInput" class="sr-only">Search</label>
          <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <input type="text" id="searchInput" autocomplete="off" placeholder="Search by name, username, or role" class="w-full border border-gray-300 rounded-md pl-9 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
        </div>
        <button onclick="openModal()" class="bg-indigo-900 hover:bg-indigo-800 text-white px-4 py-2 rounded-md shadow-md transition flex items-center justify-center gap-2 font-medium w-full md:w-auto">
          <i data-lucide="user-plus" class="w-4 h-4"></i> Add User
        </button>
      </div>

      <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
        <table class="w-full text-sm text-left">
          <thead class="bg-indigo-900 text-white">
            <tr>
              <th class="py-3 px-4">Full Name</th>
              <th class="py-3 px-4 w-49">Username</th>
              <th class="py-3 px-4 w-32">User Type</th>
              <th class="py-3 px-4 w-24 text-center">Status</th>
              <th class="py-3 px-4 text-center w-36">Actions</th>
            </tr>
          </thead>
          <tbody id="usersTableBody" class="divide-y divide-gray-200">
            @forelse($users as $user)
            <tr class="user-row hover:bg-gray-50 transition">
              <td class="py-3 px-4 font-medium text-gray-900">{{ $user->lname }}, {{ $user->fname }} {{ $user->mname }}</td>
              <td class="py-3 px-4 text-gray-600">{{ $user->username }}</td>
              <td class="py-3 px-4">
                <span class="px-2 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded text-xs font-semibold capitalize">
                  {{ $user->usertype }}
                </span>
              </td>
              <td class="py-3 px-4 text-center">
                @if($user->status === 'active')
                <span class="px-2 py-1 bg-green-50 text-green-700 border border-green-100 rounded text-xs font-semibold">Active</span>
                @else
                <span class="px-2 py-1 bg-red-50 text-red-700 border border-red-100 rounded text-xs font-semibold">Blocked</span>
                @endif
              </td>
              <td class="py-3 px-4 flex justify-center gap-2">
                <button type="button" data-user="{{ json_encode($user) }}" onclick="openViewModal(this)" class="text-green-500 p-1.5 rounded-md transition hover:bg-green-50">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
                <button type="button" data-user="{{ json_encode($user) }}" onclick="openEditModal(this)" class="text-indigo-500 p-1.5 rounded-md transition hover:bg-indigo-50">
                  <i data-lucide="edit" class="w-4 h-4"></i>
                </button>
                <button type="button" onclick="confirmSoftDelete('/users/delete/{{ $user->id }}', 'user')" class="text-red-500 p-1.5 rounded-md transition hover:bg-red-50">
                  <i data-lucide="trash-2" class="w-4 h-4"></i>
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
            <tr id="noSearchMatchRow" style="display: none;">
              <td colspan="5" class="py-8 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                  <i data-lucide="search-x" class="w-10 h-10 text-gray-300 mb-2"></i>
                  <p class="font-medium">No users match your search.</p>
                </div>
              </td>
            </tr>
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
          <div class="flex justify-between items-center mb-4 border-b pb-3">
            <h3 class="text-xl font-bold text-indigo-900 flex items-center gap-2">
              <i data-lucide="user" class="w-5 h-5"></i> User Details
            </h3>
            <button type="button" onclick="closeViewModal()" class="text-gray-400 hover:text-red-500 transition">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>
          <div class="space-y-4 text-sm text-gray-700">
            <div class="flex flex-col"><span class="text-gray-500 font-semibold mb-1">Full Name</span><span id="viewFullName" class="bg-gray-50 p-2 rounded border border-gray-200 font-medium"></span></div>
            <div class="flex flex-col"><span class="text-gray-500 font-semibold mb-1">Username</span><span id="viewUsername" class="bg-gray-50 p-2 rounded border border-gray-200"></span></div>
            <div class="flex flex-col"><span class="text-gray-500 font-semibold mb-1">User Type</span><span id="viewUsertype" class="bg-gray-50 p-2 rounded border border-gray-200 capitalize"></span></div>
            <div class="flex flex-col"><span class="text-gray-500 font-semibold mb-1">Status</span><span id="viewStatus" class="bg-gray-50 p-2 rounded border border-gray-200 capitalize"></span></div>
          </div>
          <div class="flex justify-end pt-5 mt-4 border-t">
            <button type="button" onclick="closeViewModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">Close</button>
          </div>
        </div>
      </div>

      <div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-4xl shadow-2xl max-h-[90vh] overflow-y-auto">
          <div class="flex justify-between items-center mb-4 border-b pb-3">
            <h3 class="text-xl font-bold text-indigo-900 flex items-center gap-2"><i data-lucide="user-plus" class="w-5 h-5"></i> Add New User</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition"><i data-lucide="x" class="w-5 h-5"></i></button>
          </div>
          <form id="addUserForm" action="{{ route('users.store') }}" method="POST" onsubmit="return validateForm(event)">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <label for="add_fname" class="block text-sm font-semibold mb-1 text-gray-700">First Name</label>
                <input type="text" id="add_fname" name="fname" value="{{ old('fname') }}" required autocomplete="given-name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              </div>
              <div>
                <label for="add_mname" class="block text-sm font-semibold mb-1 text-gray-700">Middle Name</label>
                <input type="text" id="add_mname" name="mname" value="{{ old('mname') }}" autocomplete="additional-name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              </div>
              <div>
                <label for="add_lname" class="block text-sm font-semibold mb-1 text-gray-700">Last Name</label>
                <input type="text" id="add_lname" name="lname" value="{{ old('lname') }}" required autocomplete="family-name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <label for="add_username" class="block text-sm font-semibold mb-1 text-gray-700">Username</label>
                <input type="text" id="add_username" name="username" value="{{ old('username') }}" required autocomplete="username" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              </div>
              <div>
                <label for="password" class="block text-sm font-semibold mb-1 text-gray-700">Password</label>
                <div class="relative">
                  <input type="password" name="password" id="password" required autocomplete="new-password" class="w-full border border-gray-300 rounded-md pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
                  <button type="button" onclick="togglePassword('password', 'pw-eye', 'pw-eye-off')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"><i id="pw-eye" data-lucide="eye" class="w-4 h-4"></i><i id="pw-eye-off" data-lucide="eye-off" class="w-4 h-4 hidden text-indigo-600"></i></button>
                </div>
              </div>
              <div>
                <label for="confirm_password" class="block text-sm font-semibold mb-1 text-gray-700">Confirm Password</label>
                <div class="relative">
                  <input type="password" name="confirm_password" id="confirm_password" required autocomplete="new-password" class="w-full border border-gray-300 rounded-md pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
                  <button type="button" onclick="togglePassword('confirm_password', 'cpw-eye', 'cpw-eye-off')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"><i id="cpw-eye" data-lucide="eye" class="w-4 h-4"></i><i id="cpw-eye-off" data-lucide="eye-off" class="w-4 h-4 hidden text-indigo-600"></i></button>
                </div>
                <p id="passwordError" class="text-red-600 text-xs mt-1.5 font-medium hidden">Passwords do not match</p>
              </div>
            </div>
            <div class="mb-6 md:w-1/3 pr-2">
              <label for="add_usertype" class="block text-sm font-semibold mb-1 text-gray-700">User Type</label>
              <select id="add_usertype" name="usertype" required autocomplete="off" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
                <option value="">Select User Type</option>
                <option value="admin" {{ old('usertype') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ old('usertype') == 'staff' ? 'selected' : '' }}>Staff</option>
              </select>
            </div>
            <div class="flex justify-end gap-3 pt-5 mt-2 border-t">
              <button type="button" onclick="closeModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2 rounded-md text-sm font-medium transition border border-gray-300">Cancel</button>
              <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">Save</button>
            </div>
          </form>
        </div>
      </div>

      <div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-xl p-6 w-full max-w-4xl shadow-2xl max-h-[90vh] overflow-y-auto">
          <div class="flex justify-between items-center mb-4 border-b pb-3">
            <h3 class="text-xl font-bold text-indigo-900 flex items-center gap-2"><i data-lucide="user-pen" class="w-5 h-5"></i> Edit User</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition"><i data-lucide="x" class="w-5 h-5"></i></button>
          </div>
          <form id="editUserForm" method="POST" onsubmit="return validateEditForm(event)">
            @csrf
            @method('PUT')
            <input type="hidden" id="editUserId" name="id">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <label for="editFname" class="block text-sm font-semibold mb-1 text-gray-700">First Name</label>
                <input type="text" id="editFname" name="fname" required autocomplete="given-name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              </div>
              <div>
                <label for="editMname" class="block text-sm font-semibold mb-1 text-gray-700">Middle Name</label>
                <input type="text" id="editMname" name="mname" autocomplete="additional-name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              </div>
              <div>
                <label for="editLname" class="block text-sm font-semibold mb-1 text-gray-700">Last Name</label>
                <input type="text" id="editLname" name="lname" required autocomplete="family-name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <label for="editUsername" class="block text-sm font-semibold mb-1 text-gray-700">Username</label>
                <input type="text" id="editUsername" name="username" required autocomplete="username" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
              </div>
              <div>
                <label for="editPassword" class="block text-sm font-semibold mb-1 text-gray-700">New Password</label>
                <div class="relative">
                  <input type="password" id="editPassword" name="password" placeholder="Leave blank to keep current" autocomplete="new-password" class="w-full border border-gray-300 rounded-md pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
                  <button type="button" onclick="togglePassword('editPassword', 'epw-eye', 'epw-eye-off')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"><i id="epw-eye" data-lucide="eye" class="w-4 h-4"></i><i id="epw-eye-off" data-lucide="eye-off" class="w-4 h-4 hidden text-indigo-600"></i></button>
                </div>
              </div>
              <div>
                <label for="editConfirmPassword" class="block text-sm font-semibold mb-1 text-gray-700">Confirm New Password</label>
                <div class="relative">
                  <input type="password" id="editConfirmPassword" name="confirm_password" autocomplete="new-password" class="w-full border border-gray-300 rounded-md pl-3 pr-10 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
                  <button type="button" onclick="togglePassword('editConfirmPassword', 'ecpw-eye', 'ecpw-eye-off')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"><i id="ecpw-eye" data-lucide="eye" class="w-4 h-4"></i><i id="ecpw-eye-off" data-lucide="eye-off" class="w-4 h-4 hidden text-indigo-600"></i></button>
                </div>
                <p id="editPasswordError" class="text-red-600 text-xs mt-1.5 font-medium hidden">Passwords do not match</p>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <label for="editUsertype" class="block text-sm font-semibold mb-1 text-gray-700">User Type</label>
                <select id="editUsertype" name="usertype" required autocomplete="off" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 focus:bg-white transition">
                  <option value="admin">Admin</option>
                  <option value="staff">Staff</option>
                </select>
              </div>
              <div>
                <span class="block text-sm font-semibold mb-1 text-gray-700">Status</span>
                <label for="editStatusToggle" class="inline-flex items-center cursor-pointer mt-1">
                  <input type="checkbox" id="editStatusToggle" class="sr-only peer">
                  <div class="relative w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                  <span id="editStatusLabel" class="ml-3 text-sm font-medium text-gray-700 capitalize">Active</span>
                </label>
                <input type="hidden" name="status" id="editStatus">
              </div>
            </div>
            <div class="flex justify-end gap-3 pt-5 mt-2 border-t">
              <button type="button" onclick="closeEditModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2 rounded-md text-sm font-medium transition border border-gray-300">Cancel</button>
              <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium transition shadow-sm">Update</button>
            </div>
          </form>
        </div>
      </div>
      @endif

    </div>
  </div>

  <script>
    function reRenderIcons() {
      setTimeout(() => {
        if (typeof window.lucide !== 'undefined') {
          window.lucide.createIcons({ icons: window.lucide.icons });
        }
      }, 50);
    }

    document.addEventListener("DOMContentLoaded", function() {
      reRenderIcons();

      const toggle = document.getElementById('editStatusToggle');
      if (toggle) {
        toggle.addEventListener('change', function() {
          const hiddenStatus = document.getElementById('editStatus');
          const label = document.getElementById('editStatusLabel');
          if (this.checked) {
            hiddenStatus.value = 'active';
            label.innerText = 'Active';
          } else {
            hiddenStatus.value = 'block';
            label.innerText = 'Block';
          }
        });
      }
    });

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.user-row');
        let visibleCount = 0;

        rows.forEach(row => {
          let text = row.textContent.toLowerCase();
          if (text.includes(filter)) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });

        const noSearchMatchRow = document.getElementById('noSearchMatchRow');
        if (noSearchMatchRow) {
          if (visibleCount === 0 && rows.length > 0) {
            noSearchMatchRow.style.display = '';
          } else {
            noSearchMatchRow.style.display = 'none';
          }
        }
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

    function openViewModal(btnElement) {
      const user = JSON.parse(btnElement.dataset.user);
      const mname = user.mname ? user.mname + ' ' : '';
      document.getElementById("viewFullName").textContent = `${user.fname} ${mname}${user.lname}`;
      document.getElementById("viewUsername").textContent = user.username;
      document.getElementById("viewUsertype").textContent = user.usertype;
      document.getElementById("viewStatus").textContent = user.status;
      viewModal.classList.remove("hidden");
    }

    function closeViewModal() { viewModal.classList.add("hidden"); }

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

    function closeModal() { modal.classList.add("hidden"); }

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
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm_password").value;
      if (password !== confirmPassword) {
        if (typeof window.Swal !== 'undefined') {
          window.Swal.fire('Error!', 'Passwords do not match.', 'error');
        } else {
          alert('Passwords do not match.');
        }
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

      const toggle = document.getElementById('editStatusToggle');
      const hiddenStatus = document.getElementById('editStatus');
      const label = document.getElementById('editStatusLabel');
      const currentStatus = (user.status || 'active').toLowerCase();

      if (currentStatus === 'active') {
        toggle.checked = true;
        hiddenStatus.value = 'active';
        label.innerText = 'Active';
      } else {
        toggle.checked = false;
        hiddenStatus.value = 'block';
        label.innerText = 'Block';
      }

      editForm.action = `/users/update/${user.id}`;
    }

    function closeEditModal() { editModal.classList.add("hidden"); }

    function validateEditForm(event) {
      const editPassword = document.getElementById("editPassword").value;
      const editConfirmPassword = document.getElementById("editConfirmPassword").value;
      if (editPassword || editConfirmPassword) {
        if (editPassword !== editConfirmPassword) {
          if (typeof window.Swal !== 'undefined') {
            window.Swal.fire('Error!', 'Passwords do not match.', 'error');
          } else {
            alert('Passwords do not match.');
          }
          event.preventDefault();
          return false;
        }
      }
      return true;
    }
  </script>
  @endif
</body>

</html>