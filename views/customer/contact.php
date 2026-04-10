<?php
require_once './views/components/navbar.php';
?>

<!-- ═══════════════════════════════════════════════════════════
     HERO - CONTACT
═══════════════════════════════════════════════════════════ -->
<div class="bg-gradient-to-r from-[#0D47A1] via-[#1565C0] to-[#1976D2] py-20 px-4 relative overflow-hidden">
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/5 rounded-full"></div>
    <div class="absolute bottom-0 left-10 w-48 h-48 bg-white/5 rounded-full translate-y-1/2"></div>
  </div>
  <div class="max-w-[1200px] mx-auto text-center relative z-10">
    <span class="inline-block bg-white/15 text-white/90 text-sm font-medium px-4 py-1.5 rounded-full mb-5">📬 Liên hệ chúng tôi</span>
    <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-5">Chúng tôi luôn sẵn sàng<br>lắng nghe bạn</h1>
    <p class="text-white/80 text-lg max-w-xl mx-auto leading-relaxed">
      Có câu hỏi hay cần hỗ trợ? Đội ngũ của chúng tôi sẵn sàng giúp bạn 7 ngày trong tuần.
    </p>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     CONTACT CARDS
═══════════════════════════════════════════════════════════ -->
<div class="max-w-[1200px] mx-auto px-4 -mt-8 relative z-10 mb-14">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <?php
    $contactCards = [
      [
        'icon' => 'map-pin', 'title' => 'Địa chỉ',
        'lines' => ['123 Nguyễn Huệ, Quận 1', 'Thành phố Hồ Chí Minh'],
        'color' => 'bg-[#4CAF50]', 'light' => 'bg-green-50 border-green-100',
      ],
      [
        'icon' => 'phone', 'title' => 'Điện thoại',
        'lines' => ['Hotline: 1800 1234', 'Thứ 2–7: 8:00 – 21:00'],
        'color' => 'bg-[#FFC107]', 'light' => 'bg-yellow-50 border-yellow-100',
      ],
      [
        'icon' => 'mail', 'title' => 'Email',
        'lines' => ['support@bookstore.vn', 'Phản hồi trong 2–4 giờ'],
        'color' => 'bg-blue-600', 'light' => 'bg-blue-50 border-blue-100',
      ],
    ];
    foreach ($contactCards as $c): ?>
    <div class="<?= $c['light'] ?> border rounded-2xl p-7 text-center hover:shadow-lg transition-shadow">
      <div class="w-14 h-14 <?= $c['color'] ?> rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
        <i data-lucide="<?= $c['icon'] ?>" class="w-6 h-6 text-white"></i>
      </div>
      <h3 class="font-bold text-[#333] text-lg mb-2"><?= $c['title'] ?></h3>
      <?php foreach ($c['lines'] as $l): ?>
      <p class="text-gray-500 text-sm"><?= $l ?></p>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     MAIN CONTENT: FORM + INFO
═══════════════════════════════════════════════════════════ -->
<div class="max-w-[1200px] mx-auto px-4 pb-16">
  <div class="grid grid-cols-1 md:grid-cols-5 gap-10">

    <!-- ── FORM LIÊN HỆ ── -->
    <div class="md:col-span-3">
      <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-2xl font-extrabold text-[#333] mb-1">Gửi tin nhắn cho chúng tôi</h2>
        <p class="text-sm text-gray-500 mb-7">Điền thông tin bên dưới và chúng tôi sẽ liên hệ lại trong vòng 24 giờ.</p>

        <form class="space-y-5" onsubmit="handleSubmit(event)">
          <!-- Name + Email -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Họ và tên <span class="text-red-500">*</span></label>
              <input type="text" placeholder="Nguyễn Văn A" required
                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4CAF50] focus:ring-2 focus:ring-[#4CAF50]/20 transition-all">
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
              <input type="email" placeholder="email@example.com" required
                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4CAF50] focus:ring-2 focus:ring-[#4CAF50]/20 transition-all">
            </div>
          </div>

          <!-- Phone -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại</label>
            <input type="tel" placeholder="0901 234 567"
              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4CAF50] focus:ring-2 focus:ring-[#4CAF50]/20 transition-all">
          </div>

          <!-- Subject -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Chủ đề <span class="text-red-500">*</span></label>
            <select required
              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4CAF50] focus:ring-2 focus:ring-[#4CAF50]/20 transition-all bg-white text-gray-700">
              <option value="" class="text-gray-400">-- Chọn chủ đề --</option>
              <option>Hỗ trợ đơn hàng</option>
              <option>Tư vấn sản phẩm</option>
              <option>Chính sách đổi trả</option>
              <option>Hợp tác kinh doanh</option>
              <option>Báo lỗi / Góp ý</option>
              <option>Khác</option>
            </select>
          </div>

          <!-- Message -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nội dung <span class="text-red-500">*</span></label>
            <textarea rows="5" placeholder="Mô tả chi tiết vấn đề của bạn..." required
              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4CAF50] focus:ring-2 focus:ring-[#4CAF50]/20 transition-all resize-none"></textarea>
          </div>

          <!-- Submit -->
          <button type="submit" id="submit-btn"
            class="w-full bg-gradient-to-r from-[#4CAF50] to-[#2E7D32] hover:from-[#43A047] hover:to-[#1B5E20] text-white py-3.5 rounded-xl font-bold transition-all hover:shadow-lg flex items-center justify-center gap-2">
            <i data-lucide="send" class="w-5 h-5"></i>
            Gửi tin nhắn
          </button>
        </form>

        <!-- Success message (hidden by default) -->
        <div id="success-msg" class="hidden mt-5 bg-green-50 border border-green-200 rounded-2xl p-5 flex items-center gap-4">
          <div class="w-12 h-12 bg-[#4CAF50] rounded-full flex items-center justify-center shrink-0">
            <i data-lucide="check" class="w-6 h-6 text-white"></i>
          </div>
          <div>
            <h4 class="font-bold text-[#333]">Gửi tin nhắn thành công!</h4>
            <p class="text-sm text-gray-500">Chúng tôi sẽ liên hệ lại với bạn sớm nhất có thể.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- ── THÔNG TIN PHỤ ── -->
    <div class="md:col-span-2 space-y-6">

      <!-- Giờ làm việc -->
      <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-7">
        <div class="flex items-center gap-3 mb-5">
          <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
            <i data-lucide="clock" class="w-5 h-5 text-purple-500"></i>
          </div>
          <h3 class="font-bold text-[#333] text-lg">Giờ làm việc</h3>
        </div>
        <div class="space-y-3 text-sm">
          <?php
          $hours = [
            ['day' => 'Thứ Hai – Thứ Sáu', 'time' => '8:00 – 21:00', 'active' => true],
            ['day' => 'Thứ Bảy',            'time' => '8:00 – 18:00', 'active' => true],
            ['day' => 'Chủ Nhật',           'time' => '9:00 – 17:00', 'active' => true],
            ['day' => 'Ngày lễ',            'time' => 'Liên hệ trước', 'active' => false],
          ];
          foreach ($hours as $h): ?>
          <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
            <span class="text-gray-600"><?= $h['day'] ?></span>
            <span class="font-semibold <?= $h['active'] ? 'text-[#4CAF50]' : 'text-gray-400' ?>">
              <?= $h['time'] ?>
            </span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>


      <!-- Google Maps placeholder -->
      <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl overflow-hidden shadow-sm" style="height: 220px;">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4947878046!2d106.70040531480024!3d10.771625492325752!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f38f9ed887b%3A0x9b7aa47f0e37a51e!2zMTIzIE5ndXnhu4VuIEh14buHLCBC4bq_biBOZ2jDqSwgUXXhuq1uIDEsIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaCwgVmlldG5hbQ!5e0!3m2!1svi!2s!4v1611901234567!5m2!1svi!2s"
          width="100%" height="220"
          style="border:0; display:block;"
          allowfullscreen=""
          loading="lazy">
        </iframe>
      </div>
    </div>

  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     FAQ QUICK
═══════════════════════════════════════════════════════════ -->
<div class="bg-gray-50 border-t border-gray-100 py-14">
  <div class="max-w-[800px] mx-auto px-4">
    <div class="text-center mb-10">
      <h2 class="text-2xl font-extrabold text-[#333]">Câu hỏi thường gặp</h2>
      <p class="text-gray-500 mt-2 text-sm">Câu trả lời nhanh cho những thắc mắc phổ biến nhất</p>
    </div>
    <div class="space-y-3" id="faq-list">
      <?php
      $faqs = [
        ['q' => 'Thời gian giao hàng mất bao lâu?', 'a' => 'Đơn hàng nội thành TP.HCM giao trong 2–4 giờ. Các tỉnh thành khác từ 1–3 ngày làm việc.'],
        ['q' => 'Tôi có thể đổi/trả sách không?', 'a' => 'Có! Bạn có thể đổi trả trong vòng 30 ngày kể từ ngày nhận hàng với điều kiện sách còn nguyên vẹn.'],
        ['q' => 'Làm sao biết sách có chính hãng không?', 'a' => 'BookStore cam kết 100% sách chính hãng, có tem kiểm định và hóa đơn VAT đầy đủ.'],
        ['q' => 'Có ưu đãi cho khách hàng thân thiết không?', 'a' => 'Có! Đăng ký tài khoản và tích điểm mỗi lần mua để nhận voucher giảm giá hấp dẫn mỗi tháng.'],
      ];
      foreach ($faqs as $i => $faq): ?>
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden faq-item">
        <button onclick="toggleFaq(<?= $i ?>)" class="w-full flex items-center justify-between p-5 text-left">
          <span class="font-semibold text-[#333] text-sm pr-4"><?= $faq['q'] ?></span>
          <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 shrink-0 faq-icon-<?= $i ?> transition-transform duration-200"></i>
        </button>
        <div id="faq-ans-<?= $i ?>" class="hidden px-5 pb-5">
          <p class="text-sm text-gray-500 leading-relaxed"><?= $faq['a'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require_once './views/components/customer_footer.php'; ?>

<script>
function handleSubmit(e) {
  e.preventDefault();
  const btn = document.getElementById('submit-btn');
  btn.disabled = true;
  btn.innerHTML = '<svg class="animate-spin w-5 h-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg> Đang gửi...';
  setTimeout(() => {
    e.target.closest('form').style.display = 'none';
    document.getElementById('success-msg').classList.remove('hidden');
    lucide.createIcons();
  }, 1200);
}

function toggleFaq(i) {
  const ans = document.getElementById('faq-ans-' + i);
  const icon = document.querySelector('.faq-icon-' + i);
  const isOpen = !ans.classList.contains('hidden');
  // Close all
  document.querySelectorAll('[id^="faq-ans-"]').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('[class*="faq-icon-"]').forEach(el => el.style.transform = '');
  // Open clicked if was closed
  if (!isOpen) {
    ans.classList.remove('hidden');
    icon.style.transform = 'rotate(180deg)';
  }
}
</script>
