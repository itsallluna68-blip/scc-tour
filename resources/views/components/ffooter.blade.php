<footer class="bg-blue-600 text-white py-10 mt-12 w-full">
    <div class="w-full px-6 text-center md:text-left">
      <div class="flex flex-col md:flex-row justify-between gap-8 mb-8">
        <div>
          <div class="flex items-center justify-center md:justify-start gap-3 mb-3">
            <img src="{{ asset('image/scpng.png') }}" alt="San Carlos Tourism Logo" class="w-10 h-10 object-contain">
            <h3 class="text-xl font-semibold">San Carlos Tourism</h3>
          </div>
          <p class="text-sm text-gray-200">Discover the beauty, culture, and hospitality of San Carlos City.</p>
        </div>
        
        <div>
          <h4 class="text-lg font-semibold mb-3">Quick Links</h4>
          <ul class="space-y-2 text-gray-200">
            <li><a href="/#hero" class="hover:text-white transition">Home</a></li>
            <li><a href="{{ route('exploreplaces') }}" class="hover:text-white transition">Explore</a></li>
            <li><a href="{{ route('activities.index') }}" class="hover:text-white transition">Activities</a></li>
            <li><a href="{{ route('events.list') }}" class="hover:text-white transition">Events</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-lg font-semibold mb-3">Contact Us</h4>
          @php
              $contactAddress   = $settings['address'] ?? 'No address provided.';
              $contactTelephone = $settings['telephone'] ?? 'No telephone provided.';
              $contactMobile    = $settings['mobile'] ?? 'No mobile number provided.';
              $contactEmail     = $settings['email'] ?? 'No email provided.';
          @endphp

          <div class="space-y-2">
            <p class="flex items-center justify-center md:justify-start gap-2">
              <i data-lucide="map-pin" class="w-5 h-5"></i> 
              <strong>Address:</strong> {!! nl2br(e($contactAddress)) !!}
            </p>
            <p class="flex items-center justify-center md:justify-start gap-2">
              <i data-lucide="phone" class="w-5 h-5"></i> 
              <strong>Telephone:</strong> {!! nl2br(e($contactTelephone)) !!}
            </p>
            <p class="flex items-center justify-center md:justify-start gap-2">
              <i data-lucide="smartphone" class="w-5 h-5"></i> 
              <strong>Mobile:</strong> {!! nl2br(e($contactMobile)) !!}
            </p>
            <p class="flex items-center justify-center md:justify-start gap-2">
              <i data-lucide="mail" class="w-5 h-5"></i> 
              <strong>Email:</strong> {!! nl2br(e($contactEmail)) !!}
            </p>
          </div>
        </div>
      </div>

      <div class="select-none border-t border-blue-400 pt-6 text-center text-sm text-gray-100">
          © 2026 
          <span id="secretTrigger" class="select-none cursor-default">
              Group
          </span> 
          3 - BSIT 4. All Rights Reserved.
      </div>
    </div>

    <script>
        let clickCount = 0;
        let clickTimeout;

        const trigger = document.getElementById("secretTrigger");

        if(trigger) {
            trigger.addEventListener("click", function () {
                clickCount++;

                clearTimeout(clickTimeout);

                clickTimeout = setTimeout(() => {
                    clickCount = 0; 
                }, 2000); 

                if (clickCount === 5) {
                    window.location.href = "/login";
                }

                if (clickCount > 5) {
                    clickCount = 0; 
                }
            });
        }
    </script>
</footer>