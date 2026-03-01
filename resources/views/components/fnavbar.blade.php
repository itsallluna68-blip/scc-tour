<header
    id="fnavbar"
    class="fixed top-0 left-0 w-full bg-white/80 backdrop-blur-md shadow-md z-50 transition-transform duration-300"
>
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
      
      <!-- Logo -->
      <a href="/" class="flex items-center gap-3">
    <img src="{{ asset('image/scpng.png') }}" 
         alt="Logo" 
         class="w-10 h-10">
         
    <h1 class="text-xl font-semibold text-gray-800">
        San Carlos Tourism
    </h1>
</a>

      <!-- Desktop Nav -->
      <nav class="hidden md:flex items-center gap-8 text-gray-700 font-medium">
        <a href="/" class="hover:text-blue-600 transition">Home</a>
        <a href="{{ route('exploreplaces') }}" class="hover:text-blue-600 transition">Explore</a>
        <a href="#a-activity" class="hover:text-blue-600 transition">Activities</a>
        <a href="#a-events" class="hover:text-blue-600 transition">Event</a>

        {{-- <a href="{{ route('aboutuspage') }}"
           target="_blank"
           rel="noopener noreferrer"
           class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
          About us <span class="text-lg">→</span>
        </a> --}}
      </nav>

      <!-- Mobile Menu Button -->
      <button
        id="fnavbar-menu-btn"
        class="md:hidden flex flex-col justify-center items-center space-y-1 focus:outline-none"
        aria-label="Toggle menu"
      >
        <span class="block w-6 h-0.5 bg-gray-700"></span>
        <span class="block w-6 h-0.5 bg-gray-700"></span>
        <span class="block w-6 h-0.5 bg-gray-700"></span>
      </button>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div id="fnavbar-mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-md shadow-md">
      <nav class="flex flex-col items-center gap-4 py-4 text-gray-700 font-medium">
        <a href="/" class="hover:text-blue-600 transition">Home</a>
        <a href="{{ route('exploreplaces') }}" class="hover:text-blue-600 transition">Explore</a>
        <a href="#a-activity" class="hover:text-blue-600 transition">Activities</a>
        <a href="#a-events" class="hover:text-blue-600 transition">Event</a>

        {{-- <a href="{{ route('aboutuspage') }}"
           class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
          About us <span class="text-lg">→</span>
        </a> --}}
      </nav>
    </div>
</header>

{{-- Component-scoped JS --}}
<script>
(() => {
    const menuBtn = document.getElementById('fnavbar-menu-btn');
    const mobileMenu = document.getElementById('fnavbar-mobile-menu');
    const navbar = document.getElementById('fnavbar');

    if (!menuBtn || !mobileMenu || !navbar) return;

    // Mobile menu toggle
    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        menuBtn.classList.toggle('open');
    });

    // Hide on scroll down, show on scroll up
    let lastScrollTop = 0;

    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop && scrollTop > 80) {
            navbar.style.transform = 'translateY(-100%)';
        } else {
            navbar.style.transform = 'translateY(0)';
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
})();
</script>

<style>
/* Scoped hamburger animation */
#fnavbar-menu-btn.open span:nth-child(1) {
  transform: rotate(45deg) translate(5px, 5px);
}
#fnavbar-menu-btn.open span:nth-child(2) {
  opacity: 0;
}
#fnavbar-menu-btn.open span:nth-child(3) {
  transform: rotate(-45deg) translate(5px, -5px);
}
#fnavbar-menu-btn span {
  transition: all 0.3s ease;
}
</style>
