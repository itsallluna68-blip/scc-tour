<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us | San Carlos Tourism</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    .fade-up {
      opacity: 0;
      transform: translateY(30px);
      transition: all 1s ease-out;
    }
    .fade-up.show {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

  {{-- header --}}
  @include('components.fnavbar')
  <div class="mb-12"></div>
  
  {{-- 🌅 ABOUT US CONTENT --}}
  <section class="max-w-6xl mx-auto px-6 py-16">
    <!-- Title -->
    <div class="text-center mb-10">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">About San Carlos City</h1>
      <p class="text-gray-600 text-lg">Discover the beauty and culture of our vibrant city.</p>
    </div>

    <!-- Image and Text (Image on top, text below) -->
    <div class="text-center mb-16">
      <img src="{{ asset('image/cityview.jpg') }}" 
           alt="San Carlos City" 
           class="w-full h-96 object-cover rounded-2xl shadow-md mb-6"></div>
      <p class="text-gray-700 text-justify leading-relaxed">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Donec sodales lorem in urna varius, 
        sit amet pharetra purus volutpat. Suspendisse potenti. Sed nec metus eros. Proin dictum velit in dapibus sodales. 
        Integer in consequat felis, nec tempor ex. Morbi porttitor euismod ligula at dapibus. Phasellus iaculis neque ut 
        nunc suscipit gravida. Donec id nisi vel eros sodales sodales.
      </p>
  </section>

  {{-- footer --}}
  @include('components.ffooter')

</body>
</html>
