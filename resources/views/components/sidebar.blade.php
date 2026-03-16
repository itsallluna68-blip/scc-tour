<aside class="w-48 bg-white h-[calc(100vh-4rem)] shadow-sm fixed top-16 left-0 flex flex-col border-r border-gray-200">
  <nav class="flex-1 px-3 py-4 space-y-1 text-gray-700 text-sm">

    <a href="{{ route('admindashboard') }}"
      class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
      <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
      <span>Dashboard</span>
    </a>

    @if(auth()->check() && auth()->user()->usertype === 'admin')
    <a href="{{ route('admin.settings.edit') }}"
      class="flex items-center gap-2 px-8 py-2 text-sm rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
      <i data-lucide="settings" class="w-4 h-4"></i>
      <span>Settings</span>
    </a>
    @endif

    <div class="mt-6 px-3 text-xs font-bold uppercase text-gray-500 tracking-wider">
      Explore Setting
    </div>

    <div class="mt-2 ml-3 space-y-1">
      <a href="{{ route('admin.activities.index') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        Activities
      </a>
      <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        Categories
      </a>
      <a href="{{ route('admin.places.index') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        Places
      </a>
      <a href="{{ route('admin.events.index') }}" class="block px-3 py-2 text-sm rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        Events
      </a>
    </div>

    @if(auth()->check() && auth()->user()->usertype === 'admin')
    <div x-data="{ open: false }" class="mt-4">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-300">
        <div class="flex items-center gap-2">
          <i data-lucide="shield" class="w-4 h-4"></i>
          <span class="font-medium">User Control</span>
        </div>
        <i data-lucide="chevron-down" class="w-4 h-4 transform transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
      </button>

      <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="mt-2 ml-6 space-y-1 origin-top">
        <a href="{{ route('users.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-md hover:bg-indigo-100 hover:text-indigo-800 transition">
          <i data-lucide="users" class="w-4 h-4"></i>
          <span>User Lists</span>
        </a>
        <a href="{{ route('userlog.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-md hover:bg-indigo-100 hover:text-indigo-800 transition">
          <i data-lucide="clipboard-list" class="w-4 h-4"></i>
          <span>User Log</span>
        </a>
      </div>
    </div>
    @endif

    <div x-data="{ open: false }" class="mt-6">
      <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-red-50 hover:text-red-700 transition">
        <div class="flex items-center gap-2">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
          <span class="font-medium">Trash Bin</span>
        </div>
        <i data-lucide="chevron-down" class="w-4 h-4 transform transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
      </button>

      <div x-show="open" x-transition class="mt-2 ml-3 space-y-1">
        <a href="#" class="block px-3 py-2 text-sm rounded-md hover:bg-red-50 hover:text-red-700 transition">Activities</a>
        <a href="#" class="block px-3 py-2 text-sm rounded-md hover:bg-red-50 hover:text-red-700 transition">Categories</a>
        <a href="#" class="block px-3 py-2 text-sm rounded-md hover:bg-red-50 hover:text-red-700 transition">Places</a>
        <a href="#" class="block px-3 py-2 text-sm rounded-md hover:bg-red-50 hover:text-red-700 transition">Events</a>
      </div>
    </div>

    <div class="mt-2">
      <a href="{{ route('monthlyvisits.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
        <span>Monthly Visits</span>
      </a>
    </div>

  </nav>
</aside>