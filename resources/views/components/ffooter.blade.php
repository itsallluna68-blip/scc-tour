<!-- 🌅 FOOTER -->
<footer class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-16 mt-16">
  <div class="max-w-7xl mx-auto px-6">

    <div class="flex flex-col md:flex-row justify-between gap-12 mb-12">

      <div class="flex flex-col md:w-1/3">
        <div class="flex items-center gap-3 mb-4">
          
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2L2 7h20L12 2zM2 17h20M2 17l10 5 10-5" />
          </svg>
          <h3 class="text-xl font-semibold tracking-tight text-white">San Carlos Tourism</h3>
        </div>
        <p class="text-white/80 leading-relaxed">
          Discover the beauty, culture, and hospitality of San Carlos City. Explore, experience, and embrace the spirit of adventure.
        </p>
      </div>

      <div class="md:w-1/3">
        <h4 class="text-lg font-semibold mb-4 text-white">Quick Links</h4>
        <ul class="space-y-2">
          <li><a href="#hero" class="hover:text-gray-200 transition">Home</a></li>
          <li><a href="exploreplaces" class="hover:text-gray-200 transition">Explore</a></li>
          <li><a href="#a-activity" class="hover:text-gray-200 transition">Activities</a></li>
          <li><a href="#a-events" class="hover:text-gray-200 transition">Events</a></li>
        </ul>
      </div>

      <div class="md:w-1/3">
        <h4 class="text-lg font-semibold mb-4 text-white">Contact Us</h4>
        @php
            $contactAddress   = $settings['address'] ?? 'No address provided.';
            $contactTelephone = $settings['telephone'] ?? 'No telephone provided.';
            $contactMobile    = $settings['mobile'] ?? 'No mobile number provided.';
            $contactEmail     = $settings['email'] ?? 'No email provided.';
        @endphp

        <ul class="space-y-3 text-white/80">
          <li class="flex items-start gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v4" />
            </svg>
            <span>{!! nl2br(e($contactAddress)) !!}</span>
          </li>
          <li class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h2l.4 2M7 7h10l1 5H6L7 7zm0 0l-1 5h12l-1-5M7 7l-1-5h12l-1 5" />
            </svg>
            <span>{!! nl2br(e($contactTelephone)) !!}</span>
          </li>
          <li class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2 8v4h20V8H2zm0 4v4h20v-4H2z" />
            </svg>
            <span>{!! nl2br(e($contactMobile)) !!}</span>
          </li>
          <li class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m0 0l4-4m-4 4l4 4" />
            </svg>
            <span>{!! nl2br(e($contactEmail)) !!}</span>
          </li>
        </ul>
      </div>

    </div>

    <div class="border-t border-white/30 mb-6"></div>

    <div class="text-center text-white/70 text-sm select-none">
      © 2025 
      <span id="secretTrigger" class="select-none font-medium text-white">
          Group
      </span> 
      3 - BSIT 4. All Rights Reserved.
    </div>

  </div>

  <script>
      let clickCount = 0;
      let clickTimeout;
      const trigger = document.getElementById("secretTrigger");

      trigger.addEventListener("click", function () {
          clickCount++;
          clearTimeout(clickTimeout);
          clickTimeout = setTimeout(() => { clickCount = 0; }, 2000);
          if (clickCount === 5) window.location.href = "/login";
          if (clickCount > 5) clickCount = 0;
      });
  </script>
</footer>