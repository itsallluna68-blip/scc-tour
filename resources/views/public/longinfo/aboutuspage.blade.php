<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
  <title>About Us | San Carlos Tourism</title>
  @vite(['resources/css/app.css', 'resources/js/public.js'])
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

  @php
      $settingsRaw = \Illuminate\Support\Facades\DB::table('tblsetting')->pluck('details', 'term');
      
      $defaultAbout = "The San Carlos City Tourism Web Hub serves as a monument of appreciation for San Carlos City, Negros Occidental. It was made in order to encourage full exploration of everything the city has to offer, the SCC Tourism Web Hub brings everything for you to discover all in one place. Developed as a final capstone by students from Colegio De Sta. Rita, we welcome you to stop by. Vamos San Carlos!";
      
      $aboutUsText = !empty($settingsRaw['aboutUs']) ? $settingsRaw['aboutUs'] : $defaultAbout;
      
      $bgImages = isset($settingsRaw['bgImg']) ? json_decode($settingsRaw['bgImg'], true) : [];
      $aboutImage = (!empty($bgImages) && is_array($bgImages)) ? asset('uploads/settings/' . $bgImages[0]) : asset('image/cityview.jpg');
  @endphp

  @include('components.fnavbar')
  
  <div class="mb-12"></div>
  
  <section class="max-w-6xl mx-auto px-6 py-16">
    <div class="text-center mb-10">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">About San Carlos City</h1>
      <p class="text-gray-600 text-lg">Discover the beauty and culture of our vibrant city.</p>
    </div>

    <div class="text-center mb-16">
      <img src="{{ $aboutImage }}" 
           alt="San Carlos City" 
           class="w-full h-96 object-cover rounded-2xl shadow-md mb-6">
      
      <p class="text-gray-700 text-justify leading-relaxed text-lg">
        {{ $aboutUsText }}
      </p>
    </div>
  </section>

  @include('components.ffooter')

</body>
</html>