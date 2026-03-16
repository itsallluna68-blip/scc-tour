<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>
<meta name="csrf-token" content="{{ csrf_token() }}">

<body class="bg-gray-100 font-sans text-gray-800 flex">

    @include('components.sidebar')

    <div class="flex-1 ml-48">

        @include('components.header')

        <main class="p-6">

            <div class="container mx-auto bg-white p-6 rounded shadow mt-16">

                <h1 class="text-2xl font-bold mb-6">Website Settings</h1>

                @if(session('success'))
                <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('admin.settings.update') }}"
                    method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-8">
                        <label class="block font-medium mb-2">Background Images</label>

                        <div class="flex items-start gap-6">

                            <div class="w-64">
                                <input type="file" name="bgImg[]" multiple class="border p-2 rounded w-full">
                            </div>

                            @if(!empty($settings['bgImg']) && is_array($settings['bgImg']))
                            <div class="flex gap-3 flex-wrap">
                                @foreach($settings['bgImg'] as $img)
                                <div class="relative">
                                    <img src="{{ asset('uploads/settings/' . $img) }}"
                                        class="w-32 h-24 object-cover rounded shadow">

                                    <button type="button"
                                        class="delete-image bg-red-600 text-white text-xs px-2 py-1 rounded-full absolute top-1 right-1"
                                        data-image="{{ $img }}"
                                        data-type="background">
                                        ✕
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block font-medium mb-2">History Images</label>

                        <div class="flex items-start gap-6">

                            <div class="w-64">
                                <input type="file" name="historyImg[]" multiple class="border p-2 rounded w-full">
                            </div>

                            @if(!empty($settings['historyImg']) && is_array($settings['historyImg']))
                            <div class="flex gap-3 flex-wrap">
                                @foreach($settings['historyImg'] as $img)
                                <div class="relative">
                                    <img src="{{ asset('uploads/settings/' . $img) }}"
                                        class="w-28 h-20 object-cover rounded shadow">

                                    <button type="button"
                                        class="delete-image bg-red-600 text-white text-xs px-2 py-1 rounded-full absolute top-1 right-1"
                                        data-image="{{ $img }}"
                                        data-type="history">
                                        ✕
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Tagline</label>
                        <input type="text" name="tagline"
                            value="{{ $settings['tagline'] ?? '' }}"
                            class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">History</label>
                        <textarea name="historyTxt"
                            class="w-full border p-2 rounded"
                            rows="4">{{ $settings['historyTxt'] ?? '' }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">About Us</label>
                        <textarea name="aboutUs"
                            class="w-full border p-2 rounded"
                            rows="4">{{ $settings['aboutUs'] ?? '' }}</textarea>
                    </div>

                    <h2 class="text-xl font-semibold mt-6 mb-2">
                        Contact Information
                    </h2>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Address</label>
                        <input type="text" name="address"
                            value="{{ $settings['address'] ?? '' }}"
                            class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Telephone</label>
                        <input type="text" name="telephone"
                            value="{{ $settings['telephone'] ?? '' }}"
                            class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Mobile</label>
                        <input type="text" name="mobile"
                            value="{{ $settings['mobile'] ?? '' }}"
                            class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium mb-1">Email</label>
                        <input type="email" name="email"
                            value="{{ $settings['email'] ?? '' }}"
                            class="w-full border p-2 rounded">
                    </div>

                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                        Update Settings
                    </button>

                </form>

            </div>

        </main>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-image").forEach(button => {
                button.addEventListener("click", function() {
                    if (!confirm("Are you sure you want to delete this image?")) return;

                    let imageName = this.dataset.image;
                    let type = this.dataset.type;
                    let imageContainer = this.closest(".relative");

                    fetch("{{ route('admin.settings.ajaxDeleteImage') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                            },
                            body: JSON.stringify({
                                image: imageName,
                                type: type
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                imageContainer.remove();
                            } else {
                                alert("Failed to delete image.");
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            alert("Something went wrong.");
                        });
                });
            });
        });
    </script>

</body>

</html>