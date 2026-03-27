<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <link rel="icon" href="{{ asset('image/scpng.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/public.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 font-sans text-gray-800 flex">

    @if(session('success'))
    <div id="flash-success" data-message="{{ session('success') }}" class="hidden"></div>
    @endif
    @if(session('error'))
    <div id="flash-error" data-message="{{ session('error') }}" class="hidden"></div>
    @endif

    @include('components.sidebar')

    <div class="flex-1 ml-48">
        @include('components.header')
        <main class="p-6">
            <div class="container mx-auto bg-white p-6 rounded shadow mt-16">
                <h1 class="text-2xl font-bold mb-6">Website Settings</h1>

                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-8">
                        <label for="historyImg" class="block font-medium mb-2">History Images</label>
                        <div class="flex items-start gap-6">
                            <div class="w-64">
                                <input type="file" id="historyImg" name="historyImg[]" multiple autocomplete="off" class="border p-2 rounded w-full">
                            </div>

                            @if(!empty($settings['historyImg']) && is_array($settings['historyImg']))
                            <div class="flex gap-3 flex-wrap">
                                @foreach($settings['historyImg'] as $img)
                                <div class="relative image-wrapper">
                                    <img src="{{ Storage::disk('s3')->url($img) }}" class="w-28 h-20 object-cover rounded shadow">
                                    <button type="button" class="delete-image bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded-full absolute top-1 right-1 shadow-md transition" data-image="{{ $img }}" data-type="history">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="aboutUsImg" class="block font-medium mb-2">About Us Images</label>
                        <div class="flex items-start gap-6">
                            <div class="w-64">
                                <input type="file" id="aboutUsImg" name="aboutUsImg[]" multiple autocomplete="off" class="border p-2 rounded w-full">
                            </div>

                            @if(!empty($settings['aboutUsImg']) && is_array($settings['aboutUsImg']))
                            <div class="flex gap-3 flex-wrap">
                                @foreach($settings['aboutUsImg'] as $img)
                                <div class="relative image-wrapper">
                                    <img src="{{ Storage::disk('s3')->url($img) }}" class="w-28 h-20 object-cover rounded shadow">
                                    <button type="button" class="delete-image bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded-full absolute top-1 right-1 shadow-md transition" data-image="{{ $img }}" data-type="aboutus">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="tagline" class="block font-medium mb-1">Tagline</label>
                        <input type="text" id="tagline" name="tagline" value="{{ $settings['tagline'] ?? '' }}" autocomplete="organization-title" class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-4">
                        <label for="historyTxt" class="block font-medium mb-1">History</label>
                        <textarea id="historyTxt" name="historyTxt" autocomplete="off" class="w-full border p-2 rounded" rows="4">{{ $settings['historyTxt'] ?? '' }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="aboutUs" class="block font-medium mb-1">About Us</label>
                        <textarea id="aboutUs" name="aboutUs" autocomplete="off" class="w-full border p-2 rounded" rows="4">{{ $settings['aboutUs'] ?? '' }}</textarea>
                    </div>

                    <h2 class="text-xl font-semibold mt-6 mb-2">Contact Information</h2>

                    <div class="mb-4">
                        <label for="address" class="block font-medium mb-1">Address</label>
                        <input type="text" id="address" name="address" value="{{ $settings['address'] ?? '' }}" autocomplete="street-address" class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-4">
                        <label for="telephone" class="block font-medium mb-1">Telephone</label>
                        <input type="text" id="telephone" name="telephone" value="{{ $settings['telephone'] ?? '' }}" autocomplete="tel-local" class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-4">
                        <label for="mobile" class="block font-medium mb-1">Mobile</label>
                        <input type="text" id="mobile" name="mobile" value="{{ $settings['mobile'] ?? '' }}" autocomplete="tel" class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block font-medium mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ $settings['email'] ?? '' }}" autocomplete="email" class="w-full border p-2 rounded">
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition shadow-sm">
                        Update
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        function reRenderIcons() {
            setTimeout(() => {
                if (typeof window.lucide !== 'undefined') {
                    window.lucide.createIcons({
                        icons: window.lucide.icons
                    });
                }
            }, 50);
        }

        document.addEventListener("DOMContentLoaded", function() {
            reRenderIcons();

            document.querySelectorAll(".delete-image").forEach(button => {
                button.addEventListener("click", function() {
                    let imageName = this.dataset.image;
                    let type = this.dataset.type;
                    let wrapper = this.closest(".image-wrapper");

                    wrapper.style.display = 'none';

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
                                wrapper.remove();
                            } else {
                                wrapper.style.display = 'block';
                                if (typeof window.Swal !== 'undefined') {
                                    window.Swal.fire('Error!', 'Failed to delete image.', 'error');
                                }
                            }
                        })
                        .catch(error => {
                            wrapper.style.display = 'block';
                            if (typeof window.Swal !== 'undefined') {
                                window.Swal.fire('Error!', 'Something went wrong.', 'error');
                            }
                        });
                });
            });
        });
    </script>
</body>

</html>