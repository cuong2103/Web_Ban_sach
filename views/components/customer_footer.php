<?php if ($success = Message::get('success')): ?>
<!-- success toast -->
<?php endif; ?>

<!-- Customer Footer -->
<footer class="bg-gradient-to-b from-[#2c2c2c] to-[#1a1a1a] text-gray-300 mt-16">
  <div class="max-w-[1230px] mx-auto px-4 py-14">
    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-5">

      <!-- Brand -->
      <div>
        <div class="flex items-center gap-2.5 mb-5">
          <div class="w-10 h-10 bg-gradient-to-br from-[#4CAF50] to-[#2E7D32] rounded-xl flex items-center justify-center shadow-md">
            <i data-lucide="book-open" class="w-5 h-5 text-white"></i>
          </div>
          <span class="text-white text-xl font-bold">Book<span class="text-[#4CAF50]">Store</span></span>
        </div>
        <p class="text-sm leading-relaxed mb-5 text-gray-400">Cửa hàng sách trực tuyến uy tín, cung cấp hàng nghìn đầu sách chất lượng với giá tốt nhất thị trường.</p>
        <div class="flex gap-2.5 items-center">
          <a href="#" class="w-9 h-9 bg-gray-700 hover:bg-[#4CAF50] rounded-lg flex items-center justify-center transition-colors duration-200 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="#" class="w-9 h-9 bg-gray-700 hover:bg-[#4CAF50] rounded-lg flex items-center justify-center transition-colors duration-200 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
          </a>
          <a href="#" class="w-9 h-9 bg-gray-700 hover:bg-[#4CAF50] rounded-lg flex items-center justify-center transition-colors duration-200 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-youtube"><path d="M2.5 7.1c.1-1.6 1.4-2.8 3-2.9h13c1.6.1 2.9 1.3 3 2.9v9.8c-.1 1.6-1.4 2.8-3 2.9h-13c-1.6-.1-2.9-1.3-3-2.9v-9.8Z"/><path d="M10 15.5v-7l5.5 3.5-5.5 3.5Z"/></svg>
          </a>
          <a href="#" class="w-9 h-9 bg-gray-700 hover:bg-[#4CAF50] rounded-lg flex items-center justify-center transition-colors duration-200 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
          </a>
        </div>
      </div>

      <!-- Hỗ trợ -->
      <div>
        <h4 class="text-white font-semibold mb-5 text-sm uppercase tracking-wider">Hỗ trợ</h4>
        <ul class="space-y-2.5 text-sm">
          <li><a href="#" class="hover:text-[#4CAF50] transition-colors flex items-center gap-2 group"><i data-lucide="chevron-right" class="w-3 h-3 text-gray-600 group-hover:text-[#4CAF50]"></i>Hướng dẫn mua hàng</a></li>
          <li><a href="#" class="hover:text-[#4CAF50] transition-colors flex items-center gap-2 group"><i data-lucide="chevron-right" class="w-3 h-3 text-gray-600 group-hover:text-[#4CAF50]"></i>Chính sách đổi trả</a></li>
          <li><a href="#" class="hover:text-[#4CAF50] transition-colors flex items-center gap-2 group"><i data-lucide="chevron-right" class="w-3 h-3 text-gray-600 group-hover:text-[#4CAF50]"></i>Chính sách vận chuyển</a></li>
          <li><a href="#" class="hover:text-[#4CAF50] transition-colors flex items-center gap-2 group"><i data-lucide="chevron-right" class="w-3 h-3 text-gray-600 group-hover:text-[#4CAF50]"></i>Câu hỏi thường gặp</a></li>
          <li><a href="<?= BASE_URL ?>?act=about" class="hover:text-[#4CAF50] transition-colors flex items-center gap-2 group"><i data-lucide="chevron-right" class="w-3 h-3 text-gray-600 group-hover:text-[#4CAF50]"></i>Giới thiệu</a></li>
          <li><a href="<?= BASE_URL ?>?act=contact" class="hover:text-[#4CAF50] transition-colors flex items-center gap-2 group"><i data-lucide="chevron-right" class="w-3 h-3 text-gray-600 group-hover:text-[#4CAF50]"></i>Liên hệ chúng tôi</a></li>
        </ul>
      </div>

      <!-- Liên hệ -->
      <div>
        <h4 class="text-white font-semibold mb-5 text-sm uppercase tracking-wider">Liên hệ</h4>
        <ul class="space-y-3.5 text-sm">
          <li class="flex items-start gap-3">
            <div class="w-8 h-8 bg-[#4CAF50]/20 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
              <i data-lucide="map-pin" class="w-4 h-4 text-[#4CAF50]"></i>
            </div>
            <span class="text-gray-400 leading-relaxed">123 Nguyễn Huệ, Quận 1, TP.HCM</span>
          </li>
          <li class="flex items-center gap-3">
            <div class="w-8 h-8 bg-[#4CAF50]/20 rounded-lg flex items-center justify-center shrink-0">
              <i data-lucide="phone" class="w-4 h-4 text-[#4CAF50]"></i>
            </div>
            <a href="tel:18001234" class="text-gray-400 hover:text-[#4CAF50] transition-colors">1800 1234</a>
          </li>
          <li class="flex items-center gap-3">
            <div class="w-8 h-8 bg-[#4CAF50]/20 rounded-lg flex items-center justify-center shrink-0">
              <i data-lucide="mail" class="w-4 h-4 text-[#4CAF50]"></i>
            </div>
            <a href="mailto:support@bookstore.vn" class="text-gray-400 hover:text-[#4CAF50] transition-colors">support@bookstore.vn</a>
          </li>
          <li class="flex items-center gap-3">
            <div class="w-8 h-8 bg-[#4CAF50]/20 rounded-lg flex items-center justify-center shrink-0">
              <i data-lucide="clock" class="w-4 h-4 text-[#4CAF50]"></i>
            </div>
            <span class="text-gray-400">Thứ 2 – 7: 8:00 – 21:00</span>
          </li>
        </ul>
      </div>
    </div>

  </div>
</footer>

</body>
<script>lucide.createIcons();</script>
</html>
