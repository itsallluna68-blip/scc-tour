<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upcoming Events</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>
    /* Simple slideshow arrows */
    .arrow-btn {
      @apply absolute top-1/2 -translate-y-1/2 bg-black bg-opacity-30 text-white p-2 rounded-full cursor-pointer hover:bg-opacity-50;
    }
    </style>
  </script>
</head>

<body class="bg-gray-50 text-gray-800">

  {{-- fnavbar --}}
  @include('components.fnavbar')
  <div class="mb-12"></div>

<section class="max-w-6xl mx-auto px-4 py-12">

    <!-- 🎉 EVENT TITLE -->
    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
        {{ $event->events }}
    </h1>

    <!-- Divider -->
    <div class="w-24 h-1 bg-indigo-600 mb-10"></div>

    <!-- 🖼️ IMAGE GALLERY -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10" x-data="{
    images: @js(array_map(fn($img) => asset('storage/' . $img), $event->pics ?? [])),
    activeIndex: 0,
    showModal: false,
    zoom: 1,
    startX: 0,

    next() { this.zoom = 1; this.activeIndex = (this.activeIndex + 1) % this.images.length },
    prev() { this.zoom = 1; this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length },
    openModal() { this.showModal = true; this.zoom = 1 },
    handleScroll(e) { e.preventDefault(); e.deltaY<0? this.zoom=Math.min(this.zoom+0.2,3):this.zoom=Math.max(this.zoom-0.2,1) },
    touchStart(e) { this.startX = e.touches[0].clientX },
    touchEnd(e) { let diff=e.changedTouches[0].clientX-this.startX; diff>50?this.prev():diff<-50?this.next():'' }
}">

    <!-- Main Image -->
    <div class="md:col-span-2 w-full h-[480px] rounded-2xl overflow-hidden shadow cursor-pointer">
        <img x-show="images.length > 0" :src="images[activeIndex]" @click="openModal()"
             class="w-full h-full object-cover hover:scale-105 transition duration-300">
        <div x-show="images.length === 0" class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
            No Image Available
        </div>
    </div>

    <!-- Thumbnails -->
    <div x-show="images.length > 0" class="flex flex-col gap-4 h-[480px] overflow-y-auto">
        <template x-for="(image, index) in images" :key="index">
            <div class="w-full h-[100px] rounded-xl overflow-hidden shadow cursor-pointer border-2"
                 :class="activeIndex === index ? 'border-blue-500' : 'border-transparent'"
                 @click="activeIndex = index">
                <img :src="image"
                     class="w-full h-full object-cover hover:scale-105 transition duration-300">
            </div>
        </template>
    </div>

</div>

    <!-- 📅 EVENT DETAILS -->
    <div class="bg-white rounded-2xl shadow-md p-6 md:p-8 mb-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">

            <!-- Date & Time -->
            <div>
                <p class="text-sm text-gray-500">Date & Time</p>
                <p class="font-semibold text-lg">
                    {{ \Carbon\Carbon::parse($event->e_datetime)->format('F j, Y ') }}
                </p>
            </div>

            <!-- Location -->
            <div>
                <p class="text-sm text-gray-500">Location</p>
                <p class="font-semibold text-lg">
                    {{ $event->e_location ?? 'To be announced' }}
                </p>
            </div>

            <!-- Map Link -->
            @if(!empty($event->e_maplink))
            <div>
                <p class="text-sm text-gray-500">Map</p>
                <a href="{{ $event->e_maplink }}" target="_blank"
                   class="text-indigo-600 hover:underline font-medium">
                    View on Map →
                </a>
            </div>
            @endif

            <!-- Website Link -->
            @if(!empty($event->e_link))
            <div>
                <p class="text-sm text-gray-500">Official Link</p>
                <a href="{{ $event->e_link }}" target="_blank"
                   class="text-indigo-600 hover:underline font-medium">
                    Visit Website →
                </a>
            </div>
            @endif

        </div>

    </div>

    <!-- 📝 DESCRIPTION BOX -->
    <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
        <h2 class="text-2xl font-semibold mb-4 text-gray-900">
            About This Event
        </h2>

        <div class="text-gray-700 leading-relaxed text-justify">
            {{ $event->e_info }}
        </div>
    </div>

</section>

{{-- 🔽 Other Events Section --}}
@if($otherEvents->count())
<section class="mt-16 px-4 md:px-8 lg:px-16">
    <div class="border-t pt-10 max-w-6xl mx-auto">

        <h2 class="text-2xl font-bold text-indigo-900 mb-6">
            Other Upcoming Events
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($otherEvents as $other)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition flex flex-col">

                    <img
                        src="{{ $other->pics && is_array($other->pics) && count($other->pics) > 0
                              ? asset('storage/' . $other->pics[0]) 
                              : asset('image/sample-event.jpg') }}"
                        class="w-full h-44 object-cover"
                        alt="{{ $other->events }}"
                    >

                    <div class="p-5 flex flex-col flex-grow">
                        <span class="text-xs text-indigo-600 font-medium">
                            {{ \Carbon\Carbon::parse($other->e_datetime)->format('F d, Y ') }}
                        </span>

                        <h3 class="text-lg font-semibold text-gray-800 mt-1">
                            {{ $other->events }}
                        </h3>

                        <p class="text-sm text-gray-600 mt-2 line-clamp-2 flex-grow">
                            {{ $other->e_info }}
                        </p>

                        <a href="{{ route('events.show', $other->id) }}"
                           class="mt-4 inline-block bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-center">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
@endif


  {{-- Footer --}}
  @include('components.ffooter')

</body>

</body>
</html>
