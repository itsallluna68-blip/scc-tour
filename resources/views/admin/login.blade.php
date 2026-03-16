<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>Login - San Carlos Tourism</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>

<body class="bg-gray-100 font-sans text-gray-800 min-h-screen flex items-center justify-center">

  <main class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 mx-4">

    <div class="flex justify-center mb-6">
      <img src="{{ asset('image/scpng.png') }}" alt="San Carlos Logo" class="w-20 h-20 object-contain">
    </div>

    <h2 class="text-2xl font-bold text-indigo-900 text-center mb-8">
      Admin Login
    </h2>

    <form method="POST" action="{{ route('login.submit') }}">
      @csrf

      <div class="mb-5">
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Username
        </label>
        <input type="text" name="username" required
          class="w-full border border-gray-300 rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-indigo-500 transition">
      </div>

      <div class="mb-5">
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Password
        </label>
        <div class="relative">
          <input type="password" id="password" name="password" required
            class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-12 outline-none focus:ring-2 focus:ring-indigo-500 transition">

          <button type="button" onclick="togglePassword()"
            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-indigo-700 focus:outline-none">
            <i data-lucide="eye" id="eye-icon" class="w-5 h-5"></i>
            <i data-lucide="eye-off" id="eye-off-icon" class="w-5 h-5 hidden"></i>
          </button>
        </div>
      </div>

      @error('login')
      <p class="text-red-600 text-sm mb-4 text-center">{{ $message }}</p>
      @enderror

      <div class="pt-2">
        <button type="submit"
          class="w-full bg-indigo-900 hover:bg-indigo-800 text-white font-medium py-2.5 rounded-lg transition shadow-md hover:shadow-lg">
          Log In
        </button>
      </div>

      <div class="mt-6 text-center">
        <a href="/" class="text-sm text-gray-500 hover:text-indigo-700 transition">
          ← Back to Website
        </a>
      </div>

    </form>
  </main>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const eyeIcon = document.getElementById('eye-icon');
      const eyeOffIcon = document.getElementById('eye-off-icon');

      if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
      } else {
        input.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
      }
    }
  </script>
</body>

</html>