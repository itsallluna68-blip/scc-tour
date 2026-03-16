<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    <title>Place Details</title>
    @vite(['resources/css/app.css', 'resources/js/public.js'])
    <style>
        #menu-btn.open span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
        #menu-btn.open span:nth-child(2) { opacity: 0; }
        #menu-btn.open span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }
        #menu-btn span { transition: all 0.3s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.6s ease-out; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    @include('components.fnavbar')
 
    <div class="mb-12"></div>

    <section class="bg-white py-16">
        <div class="max-w-6xl mx-auto px-4 mb-8 flex flex-col md:flex-row items-center justify-between gap-4 pb-4 border-b border-gray-300">
            <div class="text-left w-full">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $place->name }}</h2>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 py-1 space-y-12 animate-fadeIn">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <img src="{{ asset('image/' . $place->image0) }}" 
                        alt="{{ $place->name }}" 
                        class="w-full h-64 md:h-96 object-cover rounded-2xl shadow-md">
                </div>

                <div class="flex flex-col justify-start">
                    <div class="flex gap-3 mb-6 overflow-x-auto">
                        @if ($place->image1)
                            <img src="{{ asset('image/' . $place->image1) }}" alt="Gallery Image 1" class="w-1/3 h-24 md:h-28 object-cover rounded-lg shadow-sm shrink-0">
                        @endif
                        @if ($place->image2)
                            <img src="{{ asset('image/' . $place->image2) }}" alt="Gallery Image 2" class="w-1/3 h-24 md:h-28 object-cover rounded-lg shadow-sm shrink-0">
                        @endif
                        @if ($place->image3)
                            <img src="{{ asset('image/' . $place->image3) }}" alt="Gallery Image 3" class="w-1/3 h-24 md:h-28 object-cover rounded-lg shadow-sm shrink-0">
                        @endif
                    </div>

                    <p class="flex items-start gap-2 text-gray-600 text-lg mb-4">
                        <i data-lucide="map-pin" class="w-5 h-5 mt-1 shrink-0"></i>
                        <span>{{ $place->address }}</span>
                    </p>

                    <div class="space-y-3 text-gray-700">
                        <p class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-5 h-5 text-gray-500"></i>
                            <span class="font-medium text-gray-900">Contact:</span> {{ $place->contact }}
                        </p>
                        <p class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-500"></i>
                            <span class="font-medium text-gray-900">Email:</span> {{ $place->email }}
                        </p>
                        <p class="flex items-center gap-2">
                            <i data-lucide="clock" class="w-5 h-5 text-gray-500"></i>
                            <span class="font-medium text-gray-900">Opening Hours:</span> {{ $place->opening_hours }}
                        </p>
                    </div>
                    
                    <div class="flex items-start gap-2 mt-3 text-gray-700">
                        <i data-lucide="globe" class="w-5 h-5 text-gray-500 mt-1 shrink-0"></i>
                        <div>
                            <span class="font-medium text-gray-900">Website:</span><br>
                            @if($place->link1)
                                <a href="{{ $place->link1 }}" target="_blank" class="text-blue-600 hover:underline block break-words">{{ $place->link1 }}</a>
                            @endif
                            @if($place->link2)
                                <a href="{{ $place->link2 }}" target="_blank" class="text-blue-600 hover:underline block break-words">{{ $place->link2 }}</a>
                            @endif
                        </div>
                    </div>

                    <p class="flex items-center gap-2 text-gray-700 mt-3">
                        <i data-lucide="map" class="w-5 h-5 text-gray-500 shrink-0"></i>
                        <span class="font-medium text-gray-900">Map:</span>
                        <a href="{{ $place->map_link }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 underline break-words">
                            Click here to view Map
                        </a>
                    </p>

                    <div class="flex items-center flex-wrap gap-2 mb-4 mt-4">
                        <i data-lucide="tag" class="w-5 h-5 text-gray-500"></i>
                        <span class="font-medium text-gray-900 mr-1">Category:</span>
                        @foreach ($exploreplaces as $expPlace)
                            @foreach ($expPlace->pcategories as $category)
                                <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-md text-sm">{{ $category->category_name }}</span>
                            @endforeach
                        @endforeach
                    </div> 

                    <div class="flex items-center gap-2 text-gray-700 mt-2">
                        <i data-lucide="star" class="w-5 h-5 text-yellow-500 fill-current"></i>
                        <span class="font-medium text-gray-900">Rating:</span> 
                        @if ($averageRating)
                            <span class="text-gray-800 font-bold">{{ number_format($averageRating, 1) }}/5</span>
                            <span class="text-gray-500 text-sm">({{ $reviews->count() }} reviews)</span>
                        @else
                            <span class="text-gray-500 italic">No ratings yet</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-200 space-y-6">
                <div x-data="{ tab: 'about' }">
                    <div class="flex border-b border-gray-200 mb-4 overflow-x-auto">
                        <button @click="tab = 'about'" :class="tab === 'about' ? 'border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 font-medium border-b-2 transition whitespace-nowrap">
                            <span class="flex items-center gap-2"><i data-lucide="info" class="w-4 h-4"></i> About</span>
                        </button>
                        <button @click="tab = 'history'" :class="tab === 'history' ? 'border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 font-medium border-b-2 transition whitespace-nowrap">
                            <span class="flex items-center gap-2"><i data-lucide="book-open" class="w-4 h-4"></i> History</span>
                        </button>
                    </div>

                    <div x-show="tab === 'about'" x-transition>
                        <p class="text-gray-700 leading-relaxed text-justify">{{ $place->description }}</p>
                    </div>

                    <div x-show="tab === 'history'" x-transition x-cloak>
                        <p class="text-gray-700 leading-relaxed text-justify">{{ $place->history }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <h4 class="text-xl font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <i data-lucide="navigation" class="w-5 h-5 text-blue-600"></i> How to get there
                    </h4>
                    <p class="text-gray-700 leading-relaxed text-justify">{!! nl2br(e($place->transport)) !!}</p>
                </div>
            </div>

            <div class="px-2">
                <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
                    <h4 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="message-square" class="w-6 h-6 text-blue-600"></i> Reviews
                    </h4>
                    
                    <div x-data="{ openReviewModal: false, resetReviewForm() { this.$refs.reviewForm.reset(); this.$dispatch('reset-rating'); } }">
                        <button @click="openReviewModal = true" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <i data-lucide="pen-line" class="w-4 h-4"></i> Add a review
                        </button>

                        <div x-show="openReviewModal" x-transition.opacity x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
                            <div @click.away="openReviewModal = false" x-transition class="bg-white w-full max-w-lg p-6 rounded-2xl shadow-xl overflow-y-auto max-h-[90vh]">
                                
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-2xl font-bold text-gray-900">Write a Review</h2>
                                    <button @click="openReviewModal = false" class="text-gray-400 hover:text-red-500">
                                        <i data-lucide="x" class="w-6 h-6"></i>
                                    </button>
                                </div>

                                @if ($errors->has('review_cooldown'))
                                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2">
                                        <i data-lucide="timer" class="w-5 h-5"></i>
                                        <p class="font-medium">{{ $errors->first('review_cooldown') }}</p>
                                    </div>
                                @endif

                                <form x-ref="reviewForm" action="{{ route('review.store', $place->id) }}" method="POST">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="review_email" class="block text-gray-700 font-medium mb-1">Email</label>
                                        <input type="email" id="review_email" name="email" autocomplete="email" required class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
                                    </div>

                                    <div class="mb-4">
                                        <label for="review_name" class="block text-gray-700 font-medium mb-1">Your Name</label>
                                        <input type="text" id="review_name" name="name" autocomplete="name" required class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
                                    </div>

                                    <div x-data="{ rating: 0, hover: 0 }" @reset-rating.window="rating = 0; hover = 0;" class="mb-4">
                                        <label for="review_rating" class="block text-gray-700 font-medium mb-1">Rating</label>
                                        <input type="hidden" id="review_rating" name="ratings" x-model="rating" required autocomplete="off">
                                        <div class="flex items-center space-x-2">
                                            <template x-for="star in [1,2,3,4,5]">
                                                <svg @click="rating = star" @mouseover="hover = star" @mouseleave="hover = 0" 
                                                    class="w-9 h-9 cursor-pointer transition-all duration-200" 
                                                    :class="{ 'text-yellow-400 scale-110': hover >= star || rating >= star, 'text-gray-300 scale-100': hover < star && rating < star }" 
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049.927l2.267 6.987h7.346l-5.942 4.314 2.267 6.987L9.049 14.9l-5.938 4.315L5.378 12.22 0 7.914h7.346z"/>
                                                </svg>
                                            </template>
                                            <p class="text-sm text-gray-600 ml-2 font-medium" x-text="rating ? ({5: 'Excellent', 4: 'Good', 3: 'Average', 2: 'Poor', 1: 'Terrible'}[rating]) : 'Select a rating'"></p>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="review_subject" class="block text-gray-700 font-medium mb-1">Subject</label>
                                        <input type="text" id="review_subject" name="subject" autocomplete="off" required class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
                                    </div>

                                    <div class="mb-4">
                                        <label for="review_feedback" class="block text-gray-700 font-medium mb-1">Feedback</label>
                                        <textarea id="review_feedback" name="feedback" rows="4" autocomplete="off" required class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
                                    </div>

                                    <div class="flex justify-end gap-3 mt-6">
                                        <button type="button" @click="resetReviewForm(); openReviewModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">Cancel</button>
                                        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">Submit Review</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse ($reviews->take(3) as $review)
                        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-3">
                                <div>
                                    <h5 class="font-bold text-gray-900 text-lg">{{ $review->name }}</h5>
                                    <div class="flex items-center text-yellow-500 mt-1">
                                        <i data-lucide="star" class="w-4 h-4 fill-current mr-1"></i>
                                        <span class="font-bold">{{ $review->ratings }}/5</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-2 md:mt-0 flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                    {{ \Carbon\Carbon::parse($review->date)->format('F d, Y') }}
                                </p>
                            </div>
                            <p class="font-semibold text-gray-800">{{ $review->subject }}</p>
                            <p class="text-gray-600 mt-2 leading-relaxed">{{ $review->feedback }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8 bg-white border border-gray-200 rounded-xl">
                            <i data-lucide="message-square-dashed" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-gray-500 font-medium">No reviews yet. Be the first to leave one.</p>
                        </div>
                    @endforelse

                    @if ($reviews->count() > 3)
                        <div class="text-center mt-6">
                            <a href="{{ route('reviews.show', $place->id) }}" class="inline-flex items-center gap-2 text-blue-600 font-medium hover:underline px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                                Read more reviews <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-16 px-2 md:px-0 lg:px-0">
                <h4 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="map" class="w-6 h-6 text-blue-600"></i> Similar Places
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition group">
                        <div class="overflow-hidden h-48">
                            <img src="{{ asset('image/marina.jpg') }}" alt="Park Marina" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="flex flex-col justify-between p-5 h-[120px]">
                            <div>
                                <h5 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1">Park Marina</h5>
                                <p class="text-gray-500 text-sm flex items-center gap-1"><i data-lucide="tag" class="w-3 h-3"></i> Park • Nature</p>
                            </div>
                            <a href="#" class="mt-auto self-start text-blue-600 font-medium hover:underline text-sm inline-flex items-center gap-1">View Details <i data-lucide="chevron-right" class="w-4 h-4"></i></a>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition group">
                        <div class="overflow-hidden h-48">
                            <img src="{{ asset('image/awani.png') }}" alt="Ma Awani" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="flex flex-col justify-between p-5 h-[120px]">
                            <div>
                                <h5 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1">Ma Awani</h5>
                                <p class="text-gray-500 text-sm flex items-center gap-1"><i data-lucide="tag" class="w-3 h-3"></i> Hotel • Restaurant • Pool</p>
                            </div>
                            <a href="#" class="mt-auto self-start text-blue-600 font-medium hover:underline text-sm inline-flex items-center gap-1">View Details <i data-lucide="chevron-right" class="w-4 h-4"></i></a>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition group">
                        <div class="overflow-hidden h-48">
                            <img src="{{ asset('image/sestra3.png') }}" alt="Sestra Cafe" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="flex flex-col justify-between p-5 h-[120px]">
                            <div>
                                <h5 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1">Sestra Cafe</h5>
                                <p class="text-gray-500 text-sm flex items-center gap-1"><i data-lucide="tag" class="w-3 h-3"></i> Cafes</p>
                            </div>
                            <a href="#" class="mt-auto self-start text-blue-600 font-medium hover:underline text-sm inline-flex items-center gap-1">View Details <i data-lucide="chevron-right" class="w-4 h-4"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.ffooter')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>