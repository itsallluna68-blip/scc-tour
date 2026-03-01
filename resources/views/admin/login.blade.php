<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Admin</title>
  @vite('resources/css/app.css')

</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <div class="flex-1 ml-60">
      {{-- Header --}}
      @include('components.header2')

      {{-- Page Content --}}
      <main class="p-6">
        @yield('content')
      </main>
    </div>

    <main class="bg-gray-100 min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6">

    <h2 class="text-2xl font-semibold text-indigo-900 text-center mb-6">
      Login
    </h2>

    <form method="POST" action="{{ route('login.submit') }}">
      @csrf

      <!-- Username -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Username
        </label>
        <input type="text" name="username" required class="w-full border rounded px-3 py-2">
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Password
        </label>

        <div class="relative">
          <input type="password" name="password" required class="w-full border rounded px-3 py-2">

          <!-- Toggle button -->
          <button type="button"
                  onclick="togglePassword()"
                  class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-indigo-700">
            👁️
          </button>
        </div>
      </div>

       @error('login')
          <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
        @enderror

      <!-- Button -->
      <div class="pt-4">
        <button type="submit"
                class="w-full bg-indigo-900 hover:bg-indigo-800 text-white py-2 rounded-md transition">
          Log In
        </button>
      </div>

    </form>

  </div>
    </main>

    {{-- script --}}
    <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const btn = event.currentTarget;

      if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
      } else {
        input.type = 'password';
        btn.textContent = '👁️';
      }
    }
    </script>

    </body>
</html>
