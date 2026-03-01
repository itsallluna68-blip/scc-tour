<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
    <body class="bg-gray-50 font-sans text-gray-800">
    <style>
        #menu-btn.open span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
        }
        #menu-btn.open span:nth-child(2) {
        opacity: 0;
        }
        #menu-btn.open span:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -5px);
        }
        #menu-btn span {
        transition: all 0.3s ease;
        }
    </style>

    {{-- fnavbar --}}
    @include('components.fnavbar')
 
    <div class="mb-12"></div>

    <section class="bg-white py-16">
        <div class="max-w-6xl mx-auto px-4 mb-8 flex flex-col md:flex-row items-center justify-between gap-4 pb-4 border-b border-gray-300">
        <div class="text-left">
            <h2 class="text-4xl font-bold text-gray-900 mb-2">{{ $place->name }}</h2>
        </div>
        </div>

    <div class="max-w-6xl mx-auto px-4 py-1 space-y-12 animate-fadeIn">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
        <img src="{{ asset('image/' . $place->image0) }}" 
            alt="{{ $place->name }}" 
            class="w-full h-96 object-cover rounded-2xl shadow-md">
        </div>

        <div class="flex flex-col justify-start">
        <div class="flex gap-3 mb-4">
            @if ($place->image1)
                <img src="{{ asset('image/' . $place->image1) }}" 
                    alt="Image 1" 
                    class="w-1/3 h-28 object-cover rounded-lg shadow-sm">
            @endif

            @if ($place->image2)
                <img src="{{ asset('image/' . $place->image2) }}" 
                    alt="Image 2" 
                    class="w-1/3 h-28 object-cover rounded-lg shadow-sm">
            @endif

            @if ($place->image3)
                <img src="{{ asset('image/' . $place->image3) }}" 
                    alt="Image 3" 
                    class="w-1/3 h-28 object-cover rounded-lg shadow-sm">
            @endif
            </div>

        <!-- Address -->
        <p class="text-gray-600 text-lg mb-2">{{ $place->address }}</p>

        {{-- contact info --}}
        <p><span class="font-medium text-gray-800">📞 Contact:</span> {{ $place->contact }}</p>
        <p><span class="font-medium text-gray-800">📧 Email:</span> {{ $place->email }}</p>
        <p><span class="font-medium text-gray-800">📧 Opening Hours:</span> {{ $place->opening_hours }}</p>
        <p class="text-gray-700">
    <span class="font-medium text-gray-800">🌐 Website:</span><br>
        <a href="{{ $place->link1 }}" 
        target="_blank" 
        class="text-blue-600 hover:underline block">
            {{ $place->link1 }}
        </a>
        <a href="{{ $place->link2 }}" 
        target="_blank" 
        class="text-blue-600 hover:underline block">
            {{ $place->link2 }}
        </a>
    </p>

        {{-- Map --}}
        <p class="text-gray-700 font-semibold mr-1">Map:
        <a href="{{ $place->map_link }}"
            target="_blank"
            rel="noopener noreferrer"
            class="text-blue-600 hover:text-blue-800 underline">
            Click here
        </a>

        
        {{-- Category --}}
        <div class="flex items-center flex-wrap gap-2 mb-4">
        <span class="text-gray-700 font-semibold mr-1">Category:</span>

        @foreach ($exploreplaces as $place)
        <h3>{{ $place->name }}</h3>

        <ul>
            @foreach ($place->pcategories as $category)
            <li>{{ $category->category_name }}</li>
        @endforeach
        </ul>
    @endforeach
       
      </div> 

        <p class="text-gray-700 font-semibold mr-1">Rating: 
            @if ($averageRating)
                <span class="text-yellow-500">
                    ⭐ {{ number_format($averageRating, 1) }}/5
                </span>
                <span class="text-gray-500 text-sm">
                    ({{ $reviews->count() }} reviews)
                </span>
            @else
                <span class="text-gray-500 italic">No ratings yet</span>
            @endif
        </p>

        {{-- <p><span class="font-medium text-gray-800">Map</span> 
            <a href="https://www.google.com/maps/@10.4854233,123.4157313,15.75z?entry=ttu&g_ep=EgoyMDI1MTAyNy4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="text-blue-600 hover:underline">Click Here</a> --}}
        
</p>

        </div>
    </div>

    <!-- 📝 Container 2: Description + History -->
<div class="bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-200 space-y-6">

    <!-- TABS -->
    <div x-data="{ tab: 'about' }">

        <div class="flex border-b border-gray-200 mb-4">
            <button
                @click="tab = 'about'"
                :class="tab === 'about' 
                    ? 'border-blue-600 text-blue-600' 
                    : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 font-medium border-b-2 transition">
                About
            </button>

            <button
                @click="tab = 'history'"
                :class="tab === 'history' 
                    ? 'border-blue-600 text-blue-600' 
                    : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 font-medium border-b-2 transition">
                History
            </button>
        </div>

        <div x-show="tab === 'about'" x-transition>
            <p class="text-gray-700 leading-relaxed">
                {{ $place->description }}
            </p>
        </div>

        <div x-show="tab === 'history'" x-transition>
            <p class="text-gray-700 leading-relaxed">
                {{ $place->history }}
            </p>
        </div>

    </div>
        <!-- MAIN -->
    <div>
        <h4 class="text-2xl font-semibold text-gray-800 mb-3">
            How to get there
        </h4>
        <p class="text-gray-700 leading-relaxed">
            {!! nl2br(e($place->transport)) !!}
        </p>

    </div>

</div>



    
    {{-- ⭐ REVIEWS SECTION --}}
    <div class="px-2">
    <div class="flex items-center justify-between mb-4">
            <h4 class="text-2xl font-semibold text-gray-900">Reviews</h4>
            
            <div x-data="{ 
                openReviewModal: false,
                resetReviewForm() {
                    // reset all form fields
                    this.$refs.reviewForm.reset();

                    // reset star rating component (found inside x-data)
                    this.$dispatch('reset-rating');

                }
            }">
            <button 
                @click="openReviewModal = true"
                class="bg-blue-600 text-white px-5 py-1.5 rounded-lg hover:bg-blue-700 transition">
                Add a review
            </button>

    {{-- ⭐ REVIEW MODAL  --}}
            <div 
        x-show="openReviewModal"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

        <div 
            @click.away="openReviewModal = false"
            x-transition
            class="bg-white w-11/12 max-w-lg p-6 rounded-2xl shadow-xl">

            <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                Write a Review
            </h2>

            {{-- Display rate limit error --}}
            @if ($errors->has('review_cooldown'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <p class="font-medium">⏱️ {{ $errors->first('review_cooldown') }}</p>
                </div>
            @endif

            <form x-ref="reviewForm" action="{{ route('review.store', $place->id) }}" method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label class="block text-gray-700 font-medium mb-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200">
                </div>

                <!-- Name -->
                <div class="mb-3">
                    <label class="block text-gray-700 font-medium mb-1">Your Name</label>
                    <input type="text" name="name" required
                        class="w-full border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-200">
                </div>

                <!-- ⭐ STAR RATING -->
                <div 
                    x-data="{ 
                        rating: 0, 
                        hover: 0 
                    }" 
                    @reset-rating.window="
                        rating = 0;
                        hover = 0;
                    "
                    class="mb-3">

                    <label class="block text-gray-700 font-medium mb-1">Rating</label>

                    <input type="hidden" name="ratings" x-model="rating" required>

                    <div class="flex items-center space-x-4">
                        <template x-for="star in [1,2,3,4,5]">
                            <svg 
                                @click="rating = star"
                                @mouseover="hover = star"
                                @mouseleave="hover = 0"
                                class="w-9 h-9 cursor-pointer transition-all duration-200"
                                :class="{
                                    'text-yellow-400 scale-115': hover >= star || rating >= star,
                                    'text-gray-300 scale-100': hover < star && rating < star
                                }"
                                fill="currentColor" 
                                viewBox="0 0 20 20">
                                <path d="M9.049.927l2.267 6.987h7.346l-5.942 4.314 2.267 6.987L9.049 14.9l-5.938 4.315L5.378 12.22 0 7.914h7.346z"/>
                            </svg>
                        </template>

                        <p class="text-sm text-gray-600 mt-1"
                           x-text="
                                rating 
                                    ? (
                                        {
                                            5: 'Excellent',
                                            4: 'Good',
                                            3: 'Average',
                                            2: 'Poor',
                                            1: 'Terrible'
                                        }[rating]
                                    )
                                    : 'Select a rating'
                            ">
                        </p>
                    </div>
                </div>

                <!-- Subject -->
                <div class="mb-3">
                    <label class="block text-gray-700 font-medium mb-1">Subject</label>
                    <input type="text" name="subject" required
                        class="w-full border border-gray-300 p-2 rounded-lg">
                </div>

                <!-- Feedback -->
                <div class="mb-3">
                    <label class="block text-gray-700 font-medium mb-1">Feedback</label>
                    <textarea name="feedback" rows="4" required
                              class="w-full border border-gray-300 p-2 rounded-lg"></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-4">
                    <button 
                        type="button"
                        @click="resetReviewForm(); openReviewModal = false"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>

                    <button 
                        type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Submit Review
                    </button>
                </div>
            </form>

        </div>
    </div>

        </div>
    </div>

     {{-- ⭐ LIST OF REVIEWS --}}
    <div class="space-y-4">
    @forelse ($reviews ->take(3) as $review)
        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
            
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h5 class="font-bold text-gray-900">{{ $review->name }}</h5>
                    <div class="text-yellow-500 font-bold">
                    ⭐ {{ $review->ratings }}/5
                    </div>
                </div>

            <p class="text-xs text-gray-400 mt-2">
                {{ \Carbon\Carbon::parse($review->date)->format('F d, Y') }}
            </p>
                
            </div>
            <p class="font-medium text-gray-750 ml-2">
                {{ $review->subject }}
            </p>

            <p class="text-gray-600 mt-1 ml-2">
                {{ $review->feedback }}
            </p>

        </div>

        @empty
            <p class="text-gray-500 italic">No reviews yet. Be the first to leave one.</p>
    @endforelse

    {{-- LOOT IF REVIEWS IS MORE THAN 3 --}}
    @if ($reviews->count() > 3)
        <div class="text-center mt-4">
            <a href="{{ route('reviews.show', $place->id) }}" 
            class="text-blue-600 font-medium hover:underline">
                Read more reviews →
            </a>
        </div>
    @endif

</div>
        
    </div>

    <!-- Simple fade-in animation -->
    <style>
    @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
    animation: fadeIn 0.6s ease-out;
    }
    </style>

    <!-- 🏝️ SIMILAR PLACES SECTION -->
    <div class="mt-16 px-6 md:px-12 lg:px-20">
        
        <h4 class="text-2xl font-semibold text-gray-900 mb-6">Similar Places</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Card 1 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <img src="{{ asset('image/marina.jpg') }}" 
                    alt="Sample Place 1" 
                    class="w-full h-48 object-cover">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h5 class="text-lg font-semibold text-gray-900 mb-1">Park Marina</h5>
                        <p class="text-gray-600 text-sm">Park • Nature</p>
                    </div>
                    <a href="#"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm 
                            transition-colors duration-300 hover:bg-blue-700 focus:ring-2 focus:ring-blue-300">
                        Details
                    </a>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <img src="{{ asset('image/awani.png') }}" 
                    alt="Sample Place 2" 
                    class="w-full h-48 object-cover">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h5 class="text-lg font-semibold text-gray-900 mb-1">Ma Awani</h5>
                        <p class="text-gray-600 text-sm">Hotel • Restaurant • Pool</p>
                    </div>
                    <a href="#"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm 
                            transition-colors duration-300 hover:bg-blue-700 focus:ring-2 focus:ring-blue-300">
                        Details
                    </a>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <img src="{{ asset('image/sestra3.png') }}" 
                    alt="Sample Place 3" 
                    class="w-full h-48 object-cover">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h5 class="text-lg font-semibold text-gray-900 mb-1">Sestra Cafe</h5>
                        <p class="text-gray-600 text-sm">Cafes</p>
                    </div>
                    <a href="#"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm 
                            transition-colors duration-300 hover:bg-blue-700 focus:ring-2 focus:ring-blue-300">
                        Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    </section>



    {{-- ffooter --}}
    @include('components.ffooter')

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>
</html>