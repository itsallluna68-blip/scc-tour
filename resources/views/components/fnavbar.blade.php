<header id="fnavbar" class="fixed top-0 left-0 w-full bg-white/90 backdrop-blur-md shadow-md z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3">
        <a href="/" class="flex items-center gap-3">
            <img src="{{ asset('image/scpng.png') }}" alt="San Carlos Tourism Logo" class="w-10 h-10 object-contain">
            <h1 class="text-xl font-semibold text-gray-800">San Carlos Tourism</h1>
        </a>
        <nav class="hidden md:flex items-center gap-8 text-gray-700 font-medium">
            <a href="/" class="hover:text-blue-600 transition">Home</a>
            <a href="{{ route('exploreplaces') }}" class="hover:text-blue-600 transition">Explore</a>
            <a href="{{ route('activities.index') }}" class="hover:text-blue-600 transition">Activities</a>
            <a href="{{ route('events.list') }}" class="hover:text-blue-600 transition">Event</a>
        </nav>
        <button id="fnavbar-menu-btn" class="md:hidden flex flex-col justify-center items-center space-y-1 focus:outline-none" aria-label="Toggle menu">
            <span class="block w-6 h-0.5 bg-gray-700"></span>
            <span class="block w-6 h-0.5 bg-gray-700"></span>
            <span class="block w-6 h-0.5 bg-gray-700"></span>
        </button>
    </div>

    <div id="fnavbar-mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-md shadow-md border-t border-gray-100">
        <nav class="flex flex-col items-center gap-4 py-6 text-gray-700 font-medium">
            <a href="/" class="hover:text-blue-600 transition">Home</a>
            <a href="{{ route('exploreplaces') }}" class="hover:text-blue-600 transition">Explore</a>
            <a href="{{ route('activities.index') }}" class="hover:text-blue-600 transition">Activities</a>
            <a href="{{ route('events.list') }}" class="hover:text-blue-600 transition">Event</a>
        </nav>
    </div>
</header>

<script>
    (() => {
        const menuBtn = document.getElementById('fnavbar-menu-btn');
        const mobileMenu = document.getElementById('fnavbar-mobile-menu');

        if (!menuBtn || !mobileMenu) return;

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            menuBtn.classList.toggle('open');
        });
    })();
</script>

<style>
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