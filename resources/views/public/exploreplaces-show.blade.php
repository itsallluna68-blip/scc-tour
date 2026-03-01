<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $place->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    @include('components.fnavbar')
    <div class="mb-12"></div>

    <div class="max-w-5xl mx-auto px-4 py-10 flex-1">
        <h1 class="text-4xl font-bold mb-8">{{ $place->name }}</h1>

        <div class="flex flex-col md:flex-row gap-8">

            <div class="flex flex-col md:flex-row gap-8">

                <!-- LEFT: Image Gallery -->
                <div class="flex gap-4" x-data="{
                    images: @js(array_map(fn($img) => asset('storage/' . $img), $place->images)),
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
                    <div class="w-96 h-96 rounded-2xl overflow-hidden shadow cursor-pointer">
                        <img :src="images[activeIndex]" @click="openModal()"
                            class="w-full h-full object-cover hover:scale-105 transition duration-300">
                    </div>

                    <!-- Thumbnails -->
                    <div class="flex flex-col gap-4 h-96 overflow-y-auto pr-2">
                        <template x-for="(image, index) in images" :key="index">
                            <div class="w-40 h-28 rounded-xl overflow-hidden shadow cursor-pointer border-2"
                                :class="activeIndex === index ? 'border-blue-500' : 'border-transparent'"
                                @click="activeIndex = index">

                                <img :src="image"
                                    class="w-full h-full object-cover hover:scale-105 transition duration-300">
                            </div>
                        </template>
                    </div>

                    <!-- Fullscreen Modal (same as before) -->
                    <div x-show="showModal" x-transition.opacity
                        class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50"
                        @click.self="showModal = false" @wheel="handleScroll" @touchstart="touchStart"
                        @touchend="touchEnd">

                        <!-- Close -->
                        <button @click="showModal = false"
                            class="absolute top-6 right-6 text-white text-3xl z-50">✕</button>

                        <!-- Counter -->
                        <div class="absolute top-6 left-6 text-white text-lg">
                            <span x-text="activeIndex + 1"></span> / <span x-text="images.length"></span>
                        </div>

                        <!-- Prev -->
                        <button @click.stop="prev()" class="absolute left-6 text-white text-5xl select-none">❮</button>

                        <!-- Image -->
                        <div class="overflow-hidden flex items-center justify-center">
                            <img :src="images[activeIndex]"
                                class="max-h-[90vh] max-w-[90vw] object-contain transition-opacity duration-300"
                                :style="'transform: scale(' + zoom + ')'" x-transition:enter="opacity-0"
                                x-transition:enter-end="opacity-100" x-transition:leave="opacity-0">
                        </div>

                        <!-- Next -->
                        <button @click.stop="next()" class="absolute right-6 text-white text-5xl select-none">❯</button>
                    </div>

                </div>
            </div>

            <!-- RIGHT: Details Section -->
            <div class="flex-1 space-y-4">

                <div class="flex flex-wrap gap-2">
                    @foreach ($place->categories as $category)
                        <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-700">
                            {{ $category->category }}
                        </span>
                    @endforeach
                </div>

                <p class="text-gray-600 text-lg">{{ $place->address }}</p>

                <div class="space-y-2 text-gray-700">
                    <p><span class="font-semibold">Map:</span>
                        <a href="{{ $place->map_link }}" target="_blank" class="text-blue-600 hover:underline">
                            View Location
                        </a>
                    </p>

                    <p><span class="font-semibold">Contact:</span> {{ $place->contact }}</p>
                    <p><span class="font-semibold">Email:</span> {{ $place->email }}</p>
                    <p><span class="font-semibold">Opening Hours:</span> {{ $place->opening_hours }}</p>
                </div>

                <div>
                    <span class="font-semibold text-gray-800">Website:</span>
                    <div class="flex flex-col">
                        @if($place->link1)
                            <a href="{{ $place->link1 }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $place->link1 }}
                            </a>
                        @endif

                        @if($place->link2)
                            <a href="{{ $place->link2 }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $place->link2 }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-2">
                    <span class="text-yellow-400 text-xl">★</span>
                    <span class="text-gray-700 font-semibold">{{ $averageRating ?? 0 }}</span>
                    <span class="text-gray-500">({{ $reviewCount }}
                        {{ Str::plural('review', $reviewCount) }})</span>
                </div>

            </div>

        </div>


        {{-- <div class="flex items-center gap-2 mt-2">

            <span class="text-yellow-400 text-xl">★</span>
            <span class="text-gray-700 font-semibold">
                {{ $averageRating ?? 0 }}
            </span>
            <span class="text-gray-500">
                ({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})
            </span>

        </div>
    </div> --}}


    <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200 mt-12 mb-12">
        <div class="flex border-b border-gray-200 mb-4">
            <button
                class="tab-btn px-4 py-2 font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                data-tab="about">About</button>
            <button
                class="tab-btn px-4 py-2 font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                data-tab="history">History</button>
        </div>

        <div>
            <div class="tab-content" id="about">
                <div class="mb-4">
                    {!! nl2br(e($place->description)) !!}
                </div>
                <p class="font-bold">How to get there</p>
                <div>
                    {!! nl2br(e($place->transport)) !!}
                </div>
            </div>

            <div class="tab-content hidden" id="history">
                <p>{!! nl2br(e($place->history)) !!}</p>
            </div>
        </div>
    </div>


    <!-- REVIEWS SECTION -->
    <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200 mb-10" x-data="reviewModal({ open: {{ $errors->any() ? 'true' : 'false' }} })">>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-2 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <h2 class="text-2xl font-bold">Reviews</h2>
                <div class="flex items-center text-gray-700">
                    <span class="text-yellow-400 text-xl">★</span>
                    <span class="font-semibold ml-1">{{ $averageRating ?? 0 }}</span>
                    <span class="text-gray-500 text-sm ml-2">({{ $reviewCount }}
                        {{ Str::plural('review', $reviewCount) }})</span>
                </div>
            </div>
            <div class="relative">
                <button @click="open = true"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Add Review
                </button>
            </div>
        </div>

        <!-- Review List -->
        <div class="max-w-3xl mx-auto space-y-8">
            @forelse ($place->reviews as $review)
                <div class="bg-gray-50 p-4 rounded-lg border-b pb-6">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-lg">{{ $review->name }}</h3>
                        <span
                            class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($review->date)->format('F d, Y') }}</span>
                    </div>

                    <!-- Stars -->
                    <div class="flex items-center mt-1 mb-2 text-yellow-400 text-xl">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $review->ratings)
                                ★
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                    </div>

                    <p class="text-gray-600 mt-1">{{ $review->feedback }}</p>

                    <div class="flex gap-3 mt-3">
                        @if ($review->rpic0)
                            <img src="{{ asset('storage/' . $review->rpic0) }}" class="w-20 h-20 object-cover rounded-lg">
                        @endif
                        @if ($review->rpic1)
                            <img src="{{ asset('storage/' . $review->rpic1) }}" class="w-20 h-20 object-cover rounded-lg">
                        @endif
                        @if ($review->rpic2)
                            <img src="{{ asset('storage/' . $review->rpic2) }}" class="w-20 h-20 object-cover rounded-lg">
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No reviews yet. Be the first to review this place.</p>
            @endforelse
        </div>

        <!-- REVIEW MODAL -->
        <div x-show="open" x-transition.opacity x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
            <div @click.away="open = false" class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 relative">

                <!-- Close Button -->
                <button @click="open = false"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl">✕</button>

                <!-- Error Alerts -->
                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700 border border-red-400">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Success Alert -->
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700 border border-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Review Form -->
                <form action="{{ route('review.store', $place->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h3 class="text-xl font-bold mb-4">Add Review</h3>

                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Rating</label>
                        <input type="hidden" name="ratings" x-model="rating" value="{{ old('ratings', 0) }}">
                        <div class="flex space-x-1 text-3xl cursor-pointer">
                            <template x-for="star in 5" :key="star">
                                <span @click="rating = star"
                                    :class="rating >= star ? 'text-yellow-400' : 'text-gray-300'">★</span>
                            </template>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Feedback</label>
                        <textarea name="feedback" rows="3" required
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">{{ old('feedback') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Photos (max 3)</label>
                        <input type="file" name="images[]" multiple accept="image/*"
                            class="w-full border rounded-lg p-2" @change="handleImageUpload($event)">
                    </div>

                    <div class="mb-3">
                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="open = false"
                            class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
                        <button type="submit"
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">Submit</button>
                    </div>
                </form>

            </div>
        </div>

    </div>

    <script>
       function reviewModal(initial = {}) {
    return {
        open: initial.open || false,
        rating: 0,
        images: [],
        handleImageUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                if (this.images.length >= 3) return;
                const reader = new FileReader();
                reader.onload = (e) => { this.images.push(e.target.result); };
                reader.readAsDataURL(file);
            });
        },
        removeImage(index) {
            this.images.splice(index, 1);
        }
    }
}
    </script>


    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    {{-- SIMILAR PLACES --}}
    @if($similarPlaces->count())
        <div class="mt-16 max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Similar Places</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 justify-center">
                @foreach($similarPlaces as $similar)
                    <div
                        class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition">

                        <div class="h-48 w-full overflow-hidden">
                            @if(isset($similar->images[0]))
                                <img src="{{ asset('storage/' . $similar->images[0]) }}"
                                    class="w-full h-full object-cover hover:scale-105 transition duration-300">
                            @else
                                <img src="{{ asset('image/no-image.png') }}" class="w-full h-full object-cover">
                            @endif
                        </div>

                        <div class="p-4 space-y-3">

                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $similar->name }}
                            </h3>

                            <div class="text-sm text-gray-600">
                                @foreach($similar->categories as $category)
                                    {{ $category->category }}@if(!$loop->last) * @endif
                                @endforeach
                            </div>

                            <a href="{{ route('exploreplaces.show', $similar->id) }}"
                                class="inline-block mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                                View Details
                            </a>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    @endif

    <a href="{{ route('exploreplaces') }}" class="text-blue-600 hover:underline">
        ← Back to Explore Places
    </a>
    </>
    </div>

    <script>
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const target = button.dataset.tab;

                tabButtons.forEach(btn => {
                    btn.classList.remove('border-blue-600', 'text-blue-600');
                    btn.classList.add('text-gray-500');
                });
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                button.classList.add('border-blue-600', 'text-blue-600');
                button.classList.remove('text-gray-500');
                document.getElementById(target).classList.remove('hidden');
            });
        });

        document.querySelector('.tab-btn[data-tab="about"]').click();

        function reviewModal() {
            return {
                open: false,
                rating: 0,
                images: [],

                handleImageUpload(event) {
                    const files = Array.from(event.target.files);

                    files.forEach(file => {
                        if (this.images.length >= 3) return;

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.images.push(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    });

                },

            }
        }
    </script>

    @include('components.ffooter')

</body>

</html>