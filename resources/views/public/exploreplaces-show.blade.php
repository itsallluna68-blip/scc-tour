<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $place->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/public.js'])
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <script src="https://www.google.com/recaptcha/api.js" async defer crossorigin="anonymous"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    @include('components.fnavbar')
    <div class="mb-12"></div>

    <div class="max-w-5xl mx-auto px-4 py-10 flex-1 w-full">
        <h1 class="text-3xl md:text-4xl font-bold mb-8">{{ $place->name }}</h1>

        <div class="flex flex-col md:flex-row gap-8">

            <div class="flex flex-col md:flex-row gap-4 md:w-2/3" x-data="{
                images: @js(array_map(fn($img) => asset('storage/' . $img), $place->images ?? [])),
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

                <div class="w-full md:w-[400px] h-64 md:h-96 rounded-2xl overflow-hidden shadow cursor-pointer shrink-0">
                    <template x-if="images.length > 0">
                        <img :src="images[activeIndex]" @click="openModal()"
                            class="w-full h-full object-cover hover:scale-105 transition duration-300">
                    </template>
                    <template x-if="images.length === 0">
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">No Image</div>
                    </template>
                </div>

                <div class="flex flex-row md:flex-col gap-4 w-full md:w-32 h-auto md:h-96 overflow-x-auto md:overflow-y-auto pb-2 md:pb-0 md:pr-2">
                    <template x-for="(image, index) in images" :key="index">
                        <div class="w-24 h-24 md:w-full md:h-24 flex-shrink-0 rounded-xl overflow-hidden shadow cursor-pointer border-2"
                            :class="activeIndex === index ? 'border-blue-500' : 'border-transparent'"
                            @click="activeIndex = index">
                            <img :src="image" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                        </div>
                    </template>
                </div>

                <div x-show="showModal" x-transition.opacity x-cloak
                    class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50"
                    @click.self="showModal = false" @wheel="handleScroll" @touchstart="touchStart"
                    @touchend="touchEnd">
                    <button @click="showModal = false" class="absolute top-6 right-6 text-white text-3xl z-50">✕</button>
                    <div class="absolute top-6 left-6 text-white text-lg">
                        <span x-text="activeIndex + 1"></span> / <span x-text="images.length"></span>
                    </div>
                    <button @click.stop="prev()" class="absolute left-4 md:left-6 text-white text-4xl md:text-5xl select-none">❮</button>
                    <div class="overflow-hidden flex items-center justify-center px-12">
                        <img :src="images[activeIndex]"
                            class="max-h-[80vh] max-w-[80vw] object-contain transition-opacity duration-300"
                            :style="'transform: scale(' + zoom + ')'">
                    </div>
                    <button @click.stop="next()" class="absolute right-4 md:right-6 text-white text-4xl md:text-5xl select-none">❯</button>
                </div>
            </div>

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
                        <a href="{{ $place->map_link }}" target="_blank" class="text-blue-600 hover:underline break-all">
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
                            <a href="{{ $place->link1 }}" target="_blank" class="text-blue-600 hover:underline break-all">
                                {{ $place->link1 }}
                            </a>
                        @endif
                        @if($place->link2)
                            <a href="{{ $place->link2 }}" target="_blank" class="text-blue-600 hover:underline break-all">
                                {{ $place->link2 }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-2">
                    <span class="text-yellow-400 text-xl">★</span>
                    <span class="text-gray-700 font-semibold">{{ number_format($averageRating ?? 0, 1) }}</span>
                    <span class="text-gray-500">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                </div>
            </div>

        </div>

        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200 mt-12 mb-12">
            <div class="flex border-b border-gray-200 mb-4 overflow-x-auto">
                <button class="tab-btn px-4 py-2 font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="about">About</button>
                <button class="tab-btn px-4 py-2 font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="history">History</button>
            </div>

            <div>
                <div class="tab-content" id="about">
                    <div class="mb-4 text-gray-700 leading-relaxed text-justify">
                        {!! nl2br(e($place->description)) !!}
                    </div>
                    <p class="font-bold text-gray-900 mt-6 mb-2">How to get there</p>
                    <div class="text-gray-700 leading-relaxed text-justify">
                        {!! nl2br(e($place->transport)) !!}
                    </div>
                </div>
                <div class="tab-content hidden" id="history">
                    <p class="text-gray-700 leading-relaxed text-justify">{!! nl2br(e($place->history)) !!}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200 mb-10" x-data="reviewModal({ open: {{ $errors->any() ? 'true' : 'false' }} })">
            
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-4">
                    <h2 class="text-2xl font-bold">Reviews</h2>
                    <div class="flex items-center text-gray-700">
                        <span class="text-yellow-400 text-xl">★</span>
                        <span class="font-semibold ml-1">{{ number_format($averageRating ?? 0, 1) }}</span>
                        <span class="text-gray-500 text-sm ml-2">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                    </div>
                </div>
                <button @click="open = true" class="bg-blue-600 text-white px-5 py-2 w-full sm:w-auto rounded-lg hover:bg-blue-700 transition">
                    Add Review
                </button>
            </div>

            <div class="max-w-3xl mx-auto space-y-6">
                {{-- LIMIT TO 5 REVIEWS ONLY --}}
                @forelse ($place->reviews->take(5) as $review)
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $review->name }}</h3>
                                <div class="flex items-center mt-1 text-yellow-400 text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $review->ratings ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($review->date)->format('M d, Y') }}</span>
                        </div>
                        <p class="text-gray-700 mt-2 text-sm md:text-base">{{ $review->feedback }}</p>
                        <div class="flex gap-2 mt-3 overflow-x-auto">
                            @if ($review->rpic0) <img src="{{ asset('storage/' . $review->rpic0) }}" class="w-16 h-16 object-cover rounded-lg shrink-0 border"> @endif
                            @if ($review->rpic1) <img src="{{ asset('storage/' . $review->rpic1) }}" class="w-16 h-16 object-cover rounded-lg shrink-0 border"> @endif
                            @if ($review->rpic2) <img src="{{ asset('storage/' . $review->rpic2) }}" class="w-16 h-16 object-cover rounded-lg shrink-0 border"> @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No reviews yet. Be the first to review this place.</p>
                @endforelse

                {{-- SHOW MORE BUTTON --}}
                @if ($place->reviews->count() > 5)
                    <div class="mt-6 flex justify-center">
                        <button @click="showAllReviews = true" class="px-6 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium">
                            Show all {{ $reviewCount }} reviews
                        </button>
                    </div>
                @endif
            </div>

            {{-- ALL REVIEWS MODAL --}}
            <div x-show="showAllReviews" x-transition.opacity x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                <div @click.away="showAllReviews = false" class="bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 relative max-h-[90vh] flex flex-col">
                    
                    <div class="flex justify-between items-center mb-4 shrink-0 border-b pb-4">
                        <h3 class="text-xl font-bold">All Reviews</h3>
                        <button @click="showAllReviews = false" class="text-gray-500 hover:text-gray-700 text-2xl">✕</button>
                    </div>

                    <div class="overflow-y-auto pr-2 space-y-4 flex-1">
                        @foreach ($place->reviews as $review)
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $review->name }}</h3>
                                        <div class="flex items-center mt-1 text-yellow-400 text-sm">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $review->ratings ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($review->date)->format('M d, Y') }}</span>
                                </div>
                                <p class="text-gray-700 mt-2 text-sm md:text-base">{{ $review->feedback }}</p>
                                <div class="flex gap-2 mt-3 overflow-x-auto">
                                    @if ($review->rpic0) <img src="{{ asset('storage/' . $review->rpic0) }}" class="w-16 h-16 object-cover rounded-lg shrink-0 border"> @endif
                                    @if ($review->rpic1) <img src="{{ asset('storage/' . $review->rpic1) }}" class="w-16 h-16 object-cover rounded-lg shrink-0 border"> @endif
                                    @if ($review->rpic2) <img src="{{ asset('storage/' . $review->rpic2) }}" class="w-16 h-16 object-cover rounded-lg shrink-0 border"> @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ADD REVIEW MODAL --}}
            <div x-show="open" x-transition.opacity x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                <div @click.away="open = false" class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 relative max-h-[90vh] overflow-y-auto">
                    
                    <button @click="open = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl">✕</button>

                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-600 border border-red-200 text-sm">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700 border border-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('review.store', $place->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h3 class="text-xl font-bold mb-4">Add a Review</h3>

                        <div class="mb-3">
                            <label for="reviewer_name" class="block text-sm font-medium mb-1 text-gray-700">Name</label>
                            <input type="text" id="reviewer_name" name="name" autocomplete="name" value="{{ old('name') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>

                        <div class="mb-3">
                            <label for="reviewer_email" class="block text-sm font-medium mb-1 text-gray-700">Email</label>
                            <input type="email" id="reviewer_email" name="email" autocomplete="email" value="{{ old('email') }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        </div>

                        <div class="mb-3">
                            <label for="review_rating" class="block text-sm font-medium mb-1 text-gray-700">Rating</label>
                            <input type="hidden" name="ratings" x-model="rating" id="review_rating" value="{{ old('ratings', 0) }}">
                            <div class="flex space-x-1 text-3xl cursor-pointer">
                                <template x-for="star in 5" :key="star">
                                    <span @click="rating = star" :class="rating >= star ? 'text-yellow-400' : 'text-gray-300'">★</span>
                                </template>
                            </div>
                        </div>

                        <div class="mb-3" x-data="{ feedbackLength: {{ strlen(old('feedback', '')) }} }">
                            <div class="flex justify-between items-center mb-1">
                                <label for="review_feedback" class="block text-sm font-medium text-gray-700">Feedback</label>
                                <span class="text-xs text-gray-500" :class="feedbackLength >= 200 ? 'text-red-500' : ''">
                                    <span x-text="feedbackLength"></span>/200 max
                                </span>
                            </div>
                            <textarea id="review_feedback" name="feedback" rows="3" autocomplete="off" required maxlength="200"
                                @input="feedbackLength = $event.target.value.length"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">{{ old('feedback') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="review_images" class="block text-sm font-medium mb-1 text-gray-700">Photos (max 3)</label>
                            <input type="file" id="review_images" name="images[]" multiple accept="image/*"
                                class="w-full border border-gray-300 rounded-lg p-2 text-sm" @change="handleImageUpload($event)">
                        </div>

                        <div class="mb-4 overflow-hidden">
                            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" @click="open = false" class="px-4 py-2 rounded-lg border hover:bg-gray-100 transition">Cancel</button>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- SIMILAR PLACES --}}
        @if($similarPlaces->count())
            <div class="mt-16 max-w-5xl mx-auto">
                <h2 class="text-2xl font-bold mb-6 text-gray-900">Similar Places</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($similarPlaces as $similar)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition flex flex-col">
                            <div class="h-48 w-full overflow-hidden shrink-0">
                                @if(isset($similar->images[0]))
                                    <img src="{{ asset('storage/' . $similar->images[0]) }}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                @else
                                    <img src="{{ asset('image/no-image.png') }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 line-clamp-1">{{ $similar->name }}</h3>
                                <div class="text-sm text-gray-500 mt-1 line-clamp-1">
                                    @foreach($similar->categories as $category)
                                        {{ $category->category }}@if(!$loop->last) • @endif
                                    @endforeach
                                </div>
                                <div class="mt-auto pt-4">
                                    <a href="{{ route('exploreplaces.show', $similar->id) }}"
                                        class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition text-center w-full md:w-auto">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-8 mb-4">
            <a href="{{ route('exploreplaces') }}" class="text-blue-600 font-medium hover:underline inline-flex items-center">
                ← Back to Explore Places
            </a>
        </div>
    </div>

    @include('components.ffooter')

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
                tabContents.forEach(content => content.classList.add('hidden'));

                button.classList.add('border-blue-600', 'text-blue-600');
                button.classList.remove('text-gray-500');
                document.getElementById(target).classList.remove('hidden');
            });
        });

        document.querySelector('.tab-btn[data-tab="about"]').click();

        function reviewModal(initial = {}) {
            return {
                open: initial.open || false,
                showAllReviews: false,
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
                }
            }
        }
    </script>
</body>
</html>