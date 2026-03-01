<div id="placesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach ($exploreplaces as $place)
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
            <img src="{{ asset('storage/' . $place->images[0]) }}"
                 alt="{{ $place->name }}" 
                 class="w-full h-56 object-cover"> 

            <div class="p-5">
                <h3 class="text-black font-semibold mb-1">{{ $place->name }}</h3>

                <p class="text-gray-600 text-sm mb-4 line-clamp-3 text-justify">{{ $place->address }}</p>

                <div class="mt-auto flex justify-end">
                    <a href="{{ route('exploreplaces.show', $place->id) }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition">
                        Details
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
