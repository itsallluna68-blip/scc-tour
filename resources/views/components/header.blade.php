<!-- 🔷 HEADER -->
<header class="fixed top-0 left-0 right-0 flex justify-between items-center px-7 py-3 bg-indigo-900 text-white shadow z-50">

  <!-- Logo + Title -->
  <div class="flex items-center gap-2">
    <img src="{{ asset('image/scpng.png') }}" alt="Logo" class="w-8 h-8 rounded-md">
    <div>
      <h1 class="text-base font-semibold leading-tight">SCC Tourism Admin Portal</h1>
      @if(auth()->check())
        <span class="text-xs text-indigo-200">
          Welcome,
          {{ auth()->user()->fname }}
          {{ auth()->user()->mname ? auth()->user()->mname . ' ' : '' }}
          {{ auth()->user()->lname }}
        </span>
      @endif
    </div>
  </div>

  <!-- Logout -->
  @if(auth()->check())
  <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded-md text-sm transition">
      Sign Out
    </button>
  </form>
  @endif

</header>
