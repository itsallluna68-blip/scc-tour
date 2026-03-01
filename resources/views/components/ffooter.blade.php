
  <!-- 🌅 FOOTER -->
  <footer class="bg-blue-600 text-white py-10 mt-12 w-full">
    <div class="w-full px-6 text-center md:text-left">
      <div class="flex flex-col md:flex-row justify-between gap-8 mb-8">
        <div>
          <div class="flex items-center justify-center md:justify-start gap-3 mb-3">
            <img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" alt="Logo" class="w-8 h-8">
            <h3 class="text-xl font-semibold">San Carlos Tourism</h3>
          </div>
          <p class="text-sm text-gray-200">Discover the beauty, culture, and hospitality of San Carlos City.</p>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-3">Quick Links</h4>
          <ul class="space-y-2 text-gray-200">
            <li><a href="#hero" class="hover:text-white transition">Home</a></li>
            <li><a href="exploreplaces" class="hover:text-white transition">Explore</a></li>
            <li><a href="#a-activity" class="hover:text-white transition">Activities</a></li>
            <li><a href="#a-events" class="hover:text-white transition">Events</a></li>
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

          <p><strong>📍 Address:</strong> {!! nl2br(e($contactAddress)) !!}</p>
          <p><strong>☎ Telephone:</strong> {!! nl2br(e($contactTelephone)) !!}</p>
          <p><strong>📱 Mobile:</strong> {!! nl2br(e($contactMobile)) !!}</p>
          <p><strong>📧 Email:</strong> {!! nl2br(e($contactEmail)) !!}</p>
        </div>
      </div>

<div class="select-none border-t border-blue-400 pt-6 text-center text-sm text-gray-100">
    © 2025 
    <span id="secretTrigger" class="select-none">
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

        // Reset timer every click
        clearTimeout(clickTimeout);

        clickTimeout = setTimeout(() => {
            clickCount = 0; // reset if user waits too long
        }, 2000); // 2 seconds window to complete 5 clicks

        if (clickCount === 5) {
            window.location.href = "/login";
        }

        if (clickCount > 5) {
            clickCount = 0; // prevents 6+ clicks from working
        }
    });
</script>
  
  </footer>
