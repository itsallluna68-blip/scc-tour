@if(session('success'))
    <div id="toast-success" class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 text-gray-700 bg-white rounded-xl shadow-2xl z-[100] border-l-4 border-green-500 transform transition-all duration-500 translate-x-0" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
        </div>
        <div class="ml-3 text-sm font-semibold">{{ session('success') }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 transition" onclick="document.getElementById('toast-success').remove()" aria-label="Close">
            <span class="sr-only">Close</span>
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>

    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if(toast) {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }
        }, 3000);
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
@endif

@if(session('error'))
    <div id="toast-error" class="fixed top-5 right-5 flex items-center w-full max-w-xs p-4 text-gray-700 bg-white rounded-xl shadow-2xl z-[100] border-l-4 border-red-500 transform transition-all duration-500 translate-x-0" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
        </div>
        <div class="ml-3 text-sm font-semibold">{{ session('error') }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 transition" onclick="document.getElementById('toast-error').remove()" aria-label="Close">
            <span class="sr-only">Close</span>
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>

    <script>
        setTimeout(() => {
            const toastError = document.getElementById('toast-error');
            if(toastError) {
                toastError.classList.remove('translate-x-0');
                toastError.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toastError.remove(), 500);
            }
        }, 4000);
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
@endif