<?php
require_once './views/components/navbar.php';
?>

<!-- ═══════════════════════════════════════════════════════════
     HERO - ABOUT
═══════════════════════════════════════════════════════════ -->
<div class="bg-gradient-to-r from-[#1B5E20] via-[#2E7D32] to-[#4CAF50] py-20 px-4 relative overflow-hidden">
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/5 rounded-full"></div>
    <div class="absolute bottom-0 left-10 w-48 h-48 bg-white/5 rounded-full translate-y-1/2"></div>
  </div>
  <div class="max-w-[1200px] mx-auto text-center relative z-10">
    <span class="inline-block bg-white/15 text-white/90 text-sm font-medium px-4 py-1.5 rounded-full mb-5">📚 Về chúng tôi</span>
    <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-5 leading-tight">
      BookStore — Cầu nối tri thức<br>đến mọi người
    </h1>
    <p class="text-white/80 text-lg max-w-2xl mx-auto leading-relaxed">
      Chúng tôi tin rằng mỗi cuốn sách là một hành trình, và sứ mệnh của BookStore là đưa những hành trình đó đến tay bạn một cách dễ dàng nhất.
    </p>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     STATS
═══════════════════════════════════════════════════════════ -->
<div class="bg-white border-b border-gray-100 shadow-sm">
  <div class="max-w-[1200px] mx-auto px-4 py-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
      <?php
      $stats = [
        ['num' => '10,000+', 'label' => 'Đầu sách', 'icon' => 'book', 'color' => 'text-[#4CAF50]', 'bg' => 'bg-green-50'],
        ['num' => '50,000+', 'label' => 'Khách hàng', 'icon' => 'users', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
        ['num' => '5 năm',   'label' => 'Hoạt động', 'icon' => 'calendar', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
        ['num' => '4.9 ★',  'label' => 'Đánh giá TB', 'icon' => 'star', 'color' => 'text-[#FFC107]', 'bg' => 'bg-yellow-50'],
      ];
      foreach ($stats as $s): ?>
      <div>
        <div class="w-14 h-14 <?= $s['bg'] ?> rounded-2xl flex items-center justify-center mx-auto mb-3">
          <i data-lucide="<?= $s['icon'] ?>" class="w-6 h-6 <?= $s['color'] ?>"></i>
        </div>
        <div class="text-3xl font-extrabold <?= $s['color'] ?> mb-1"><?= $s['num'] ?></div>
        <div class="text-sm text-gray-500"><?= $s['label'] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════════════════════ -->
<div class="max-w-[1200px] mx-auto px-4 py-14 space-y-20">

  <!-- ── CÂU CHUYỆN THƯƠNG HIỆU ── -->
  <section class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
    <div>
      <span class="text-[#4CAF50] text-sm font-semibold uppercase tracking-widest">Câu chuyện của chúng tôi</span>
      <h2 class="text-3xl font-extrabold text-[#333] mt-3 mb-5 leading-snug">Từ một cửa hàng nhỏ đến<br>thư viện trực tuyến lớn nhất</h2>
      <div class="space-y-4 text-gray-600 leading-relaxed">
        <p>BookStore ra đời năm 2019 với ước mơ đơn giản: đưa những cuốn sách chất lượng đến tay độc giả Việt Nam với giá phải chăng nhất.</p>
        <p>Từ một cửa hàng nhỏ với vài trăm đầu sách, chúng tôi đã phát triển thành nền tảng thương mại điện tử sách hàng đầu, phục vụ hơn 50.000 khách hàng trên toàn quốc.</p>
        <p>Mỗi ngày, chúng tôi không ngừng nỗ lực để mang đến những trải nghiệm mua sắm thuận tiện, nhanh chóng và đáng tin cậy nhất.</p>
      </div>
      <div class="mt-8 flex gap-4">
        <a href="<?= BASE_URL ?>?act=books" class="inline-flex items-center gap-2 bg-[#4CAF50] hover:bg-[#43A047] text-white px-6 py-3 rounded-xl font-semibold transition-all hover:shadow-lg">
          Khám phá sách <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
        <a href="<?= BASE_URL ?>?act=contact" class="inline-flex items-center gap-2 border-2 border-[#4CAF50] text-[#4CAF50] hover:bg-green-50 px-6 py-3 rounded-xl font-semibold transition-all">
          Liên hệ ngay
        </a>
      </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
      <?php
      $brandCards = [
        ['emoji' => '📚', 'title' => '10K+ Đầu sách', 'sub' => 'Đa dạng thể loại', 'color' => 'from-green-50 to-emerald-100 border-green-200'],
        ['emoji' => '🚀', 'title' => 'Giao hàng nhanh', 'sub' => 'Toàn quốc 24h', 'color' => 'from-blue-50 to-sky-100 border-blue-200'],
        ['emoji' => '💯', 'title' => 'Chính hãng 100%', 'sub' => 'Cam kết chất lượng', 'color' => 'from-yellow-50 to-amber-100 border-yellow-200'],
        ['emoji' => '🎁', 'title' => 'Ưu đãi mỗi ngày', 'sub' => 'Flash sale & voucher', 'color' => 'from-purple-50 to-violet-100 border-purple-200'],
      ];
      foreach ($brandCards as $c): ?>
      <div class="bg-gradient-to-br <?= $c['color'] ?> border rounded-2xl p-5 hover:shadow-md transition-shadow">
        <div class="text-3xl mb-3"><?= $c['emoji'] ?></div>
        <h4 class="font-bold text-[#333] text-sm"><?= $c['title'] ?></h4>
        <p class="text-xs text-gray-500 mt-0.5"><?= $c['sub'] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── SỨ MỆNH & TẦM NHÌN ── -->
  <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-gradient-to-br from-[#4CAF50] to-[#2E7D32] rounded-3xl p-8 text-white relative overflow-hidden">
      <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
      <div class="relative z-10">
        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mb-5">
          <i data-lucide="target" class="w-7 h-7"></i>
        </div>
        <h3 class="text-2xl font-extrabold mb-4">Sứ mệnh</h3>
        <p class="text-white/85 leading-relaxed text-base">
          Làm cho việc đọc sách trở nên phổ biến và tiếp cận được với mọi người dân Việt Nam. Chúng tôi cam kết cung cấp sách chất lượng cao với mức giá hợp lý nhất, thúc đẩy văn hoá đọc sách trong cộng đồng.
        </p>
        <div class="mt-6 space-y-2">
          <?php foreach (['Đa dạng thể loại sách', 'Giá cả phù hợp mọi đối tượng', 'Dịch vụ khách hàng tận tâm'] as $item): ?>
          <div class="flex items-center gap-2">
            <div class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center shrink-0">
              <i data-lucide="check" class="w-3 h-3"></i>
            </div>
            <span class="text-sm text-white/90"><?= $item ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="bg-gradient-to-br from-[#0D47A1] to-[#1976D2] rounded-3xl p-8 text-white relative overflow-hidden">
      <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
      <div class="relative z-10">
        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mb-5">
          <i data-lucide="eye" class="w-7 h-7"></i>
        </div>
        <h3 class="text-2xl font-extrabold mb-4">Tầm nhìn</h3>
        <p class="text-white/85 leading-relaxed text-base">
          Trở thành nền tảng mua bán sách trực tuyến hàng đầu Đông Nam Á vào năm 2030, kết nối hàng triệu độc giả với những kiến thức và câu chuyện hay nhất từ khắp nơi trên thế giới.
        </p>
        <div class="mt-6 space-y-2">
          <?php foreach (['Mở rộng ra thị trường Đông Nam Á', 'Hợp tác với 500+ NXB quốc tế', 'Xây dựng cộng đồng đọc sách 1M+'] as $item): ?>
          <div class="flex items-center gap-2">
            <div class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center shrink-0">
              <i data-lucide="check" class="w-3 h-3"></i>
            </div>
            <span class="text-sm text-white/90"><?= $item ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ── ĐỘI NGŨ ── -->
  <section>
    <div class="text-center mb-10">
      <span class="text-[#4CAF50] text-sm font-semibold uppercase tracking-widest">Con người</span>
      <h2 class="text-3xl font-extrabold text-[#333] mt-2">Đội ngũ của chúng tôi</h2>
      <p class="text-gray-500 mt-2 text-base">Những người đam mê sách, xây dựng BookStore mỗi ngày</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      <?php
      $team = [
        ['name' => 'Nguyễn Minh Tuấn', 'role' => 'CEO & Co-founder', 'emoji' => '👨‍💼', 'color' => 'from-green-400 to-emerald-500'],
        ['name' => 'Trần Thị Lan Anh',  'role' => 'Giám đốc SP',      'emoji' => '👩‍💻', 'color' => 'from-blue-400 to-indigo-500'],
        ['name' => 'Phạm Quốc Hùng',    'role' => 'Trưởng Marketing', 'emoji' => '👨‍🎨', 'color' => 'from-purple-400 to-violet-500'],
        ['name' => 'Lê Thị Bích Ngọc',  'role' => 'Chăm sóc KH',     'emoji' => '👩‍📞', 'color' => 'from-orange-400 to-amber-500'],
      ];
      foreach ($team as $t): ?>
      <div class="text-center group">
        <div class="w-24 h-24 mx-auto mb-4 rounded-3xl bg-gradient-to-br <?= $t['color'] ?> flex items-center justify-center shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300 text-5xl">
          <?= $t['emoji'] ?>
        </div>
        <h4 class="font-bold text-[#333] text-sm mb-1"><?= $t['name'] ?></h4>
        <p class="text-xs text-gray-500"><?= $t['role'] ?></p>
        <div class="flex justify-center gap-2 mt-3">
          <a href="#" class="w-7 h-7 bg-gray-100 hover:bg-[#4CAF50] hover:text-white rounded-lg flex items-center justify-center transition-colors">
            <i data-lucide="linkedin" class="w-3.5 h-3.5 text-gray-500 group-hover:text-white"></i>
          </a>
          <a href="#" class="w-7 h-7 bg-gray-100 hover:bg-[#4CAF50] hover:text-white rounded-lg flex items-center justify-center transition-colors">
            <i data-lucide="mail" class="w-3.5 h-3.5 text-gray-500"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── TIMELINE ── -->
  <section>
    <div class="text-center mb-10">
      <span class="text-[#4CAF50] text-sm font-semibold uppercase tracking-widest">Hành trình</span>
      <h2 class="text-3xl font-extrabold text-[#333] mt-2">5 năm phát triển</h2>
    </div>
    <div class="relative">
      <!-- Line -->
      <div class="absolute left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-[#4CAF50] to-blue-500 hidden md:block -translate-x-1/2"></div>
      <div class="space-y-8">
        <?php
        $timeline = [
          ['year' => '2019', 'title' => 'Khởi đầu', 'desc' => 'BookStore ra đời với 500 đầu sách đầu tiên và 3 nhân viên. Cửa hàng nhỏ nhưng đầy ắp đam mê.', 'side' => 'right', 'color' => 'bg-[#4CAF50]'],
          ['year' => '2020', 'title' => 'Mở rộng online', 'desc' => 'Ra mắt website thương mại điện tử, đạt 5.000 khách hàng trong năm đầu tiên bán online.', 'side' => 'left', 'color' => 'bg-blue-500'],
          ['year' => '2021', 'title' => 'Vượt mốc 10K sách', 'desc' => 'Danh mục sách đạt 10.000 đầu sách. Ra mắt ứng dụng mobile, tích hợp thanh toán điện tử.', 'side' => 'right', 'color' => 'bg-purple-500'],
          ['year' => '2022', 'title' => 'Top thương hiệu', 'desc' => 'Đạt danh hiệu "Top 10 thương hiệu sách uy tín" tại Việt Nam. 30.000 khách hàng trung thành.', 'side' => 'left', 'color' => 'bg-[#FFC107]'],
          ['year' => '2024', 'title' => 'Hôm nay', 'desc' => '50.000+ khách hàng, 10.000+ đầu sách. Mở rộng giao hàng toàn quốc với cam kết 24h.', 'side' => 'right', 'color' => 'bg-red-500'],
        ];
        foreach ($timeline as $i => $t): ?>
        <div class="flex items-center gap-6 <?= $t['side'] === 'left' ? 'md:flex-row-reverse' : '' ?>">
          <div class="flex-1 <?= $t['side'] === 'left' ? 'md:text-right' : '' ?>">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md p-6 inline-block max-w-sm transition-shadow">
              <span class="text-xs font-bold text-gray-400 uppercase tracking-wider"><?= $t['year'] ?></span>
              <h4 class="font-extrabold text-[#333] text-lg mt-1 mb-2"><?= $t['title'] ?></h4>
              <p class="text-sm text-gray-500 leading-relaxed"><?= $t['desc'] ?></p>
            </div>
          </div>
          <div class="w-12 h-12 <?= $t['color'] ?> rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shrink-0 z-10 hidden md:flex">
            <?= substr($t['year'], 2) ?>
          </div>
          <div class="flex-1 hidden md:block"></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── CTA ── -->
  <section class="bg-gradient-to-r from-[#1B5E20] to-[#4CAF50] rounded-3xl p-10 text-center relative overflow-hidden">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/10 rounded-full"></div>
      <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-white/10 rounded-full"></div>
    </div>
    <div class="relative z-10">
      <h3 class="text-3xl font-extrabold text-white mb-3">Sẵn sàng khám phá?</h3>
      <p class="text-white/80 mb-8 text-base">Hàng nghìn đầu sách đang chờ bạn — bắt đầu hành trình tri thức ngay hôm nay</p>
      <div class="flex flex-wrap justify-center gap-4">
        <a href="<?= BASE_URL ?>?act=books" class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-[#4CAF50] px-8 py-3.5 rounded-xl font-bold transition-all hover:shadow-xl">
          <i data-lucide="book-open" class="w-5 h-5"></i> Xem tất cả sách
        </a>
        <a href="<?= BASE_URL ?>?act=contact" class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white border border-white/30 px-8 py-3.5 rounded-xl font-semibold transition-all">
          <i data-lucide="message-circle" class="w-5 h-5"></i> Liên hệ chúng tôi
        </a>
      </div>
    </div>
  </section>

</div>

<?php require_once './views/components/customer_footer.php'; ?>
