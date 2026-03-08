<!-- Tired SIDEBAR -->
<aside class="w-48 bg-white h-[calc(100vh-4rem)] shadow-sm fixed top-16 left-0 flex flex-col border-r border-gray-200">
  <nav class="flex-1 px-3 py-4 space-y-1 text-gray-700 text-sm">

    @php $isStaff = auth()->check() && auth()->user()->usertype === 'Staff'; @endphp
    <!-- tired dashboard -->
    @unless($isStaff)
    <a href="{{ route('admindashboard') }}"
      class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3" />
        </svg>
        <span>Dashboard</span>
    </a>
    @endunless

  @unless($isStaff)
  <a href="{{ route('admin.settings.edit') }}"
    class="flex items-center gap-2 px-8 py-2 text-sm rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">

      <!-- Settings Icon -->
      <svg xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11.983 5.5a1.5 1.5 0 013.034 0l.286 1.47a1.5 1.5 0 001.125 1.125l1.47.286a1.5 1.5 0 010 3.034l-1.47.286a1.5 1.5 0 00-1.125 1.125l-.286 1.47a1.5 1.5 0 01-3.034 0l-.286-1.47a1.5 1.5 0 00-1.125-1.125l-1.47-.286a1.5 1.5 0 010-3.034l1.47-.286a1.5 1.5 0 001.125-1.125l.286-1.47z" />
      </svg>

      <span>Settings</span>
  </a>


    <div class="mt-6 px-3 text-xs font-bold uppercase text-gray-500 tracking-wider">
        Explore Setting
    </div>

<div class="mt-2 ml-3 space-y-1">

    <a href="{{ route('admin.activities.index') }}"
       class="block px-3 py-2 text-sm rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        Activities
    </a>

    <a href="{{ route('admin.categories.index') }}"
      class="block px-3 py-2 text-sm rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        Categories
    </a>

    <a href="{{ route('admin.places.index') }}"
       class="block px-3 py-2 text-sm rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        Places
    </a>

    <a href="{{ route('admin.events.index') }}"
       class="block px-3 py-2 text-sm rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        Events
    </a>

</div>


    {{-- <!-- Tourist Spots -->
    <a href="{{ route('touristplace.index') }}"
       class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a9 9 0 00-9 9v11h18V11a9 9 0 00-9-9z" />
      </svg>
      Tourist Spots
    </a> --}}

    @unless($isStaff)
    <!-- Users - Only Super Admin -->
    @if(auth()->check() && auth()->user()->usertype === 'Superadmin')
    <div x-data="{ open: false }" class="mt-4">
      <button 
        @click="open = !open"
        class="w-full flex items-center justify-between px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-300">

        <div class="flex items-center gap-2">
          <span class="font-medium">User Control</span>
        </div>

        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-4 h-4 transform transition-transform duration-300"
            :class="{ 'rotate-180': open }"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="mt-2 ml-6 space-y-1 origin-top">

        <a href="{{ route('users.index') }}"
          class="flex items-center gap-2 px-3 py-2 text-sm rounded-md hover:bg-indigo-100 hover:text-indigo-800 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                  d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m4-4a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span>User Lists</span>
        </a>

        <a href="{{ route('userlog.index') }}"
          class="flex items-center gap-2 px-3 py-2 text-sm rounded-md hover:bg-indigo-100 hover:text-indigo-800 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                  d="M12 8v4l3 3m6-3a9 9 0 11-9-9" />
          </svg>
          <span>User Log</span>
        </a>

      </div>
    </div>
    @endif
    @endunless


{{-- tired trash --}}
<div x-data="{ open: false }" class="mt-6">

    <button @click="open = !open"
        class="w-full flex items-center justify-between px-3 py-2 rounded-md hover:bg-red-50 hover:text-red-700 transition">

        <span class="font-medium">Trash Bin</span>

        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-4 h-4 transform transition-transform duration-300"
             :class="{ 'rotate-180': open }"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open"
         x-transition
         class="mt-2 ml-3 space-y-1">

        <!-- Users -->
        <a href="{{ route('bin.users') }}"
          class="flex items-center gap-2 px-3 py-2 text-sm rounded-md hover:bg-red-100 hover:text-red-700 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                  d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <span>Users</span>
        </a>
      </div>

    @endunless
    <!-- Monthly Visits -->
      <a href="{{ route('monthlyvisits.index') }}" 
        class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-indigo-50 hover:text-indigo-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3v18h18M6 16l4-4 3 1 5-8" />
        </svg>
      Monthly Visits
      </a>
    </div>


<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  </nav>
</aside>
