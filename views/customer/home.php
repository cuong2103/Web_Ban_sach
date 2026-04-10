<?php
require_once './views/components/navbar.php';
?>

<!-- ═══════════════════════════════════════════════════════════
     HERO BANNER SLIDER - Dynamic based on active Flash Sale & New Books
═══════════════════════════════════════════════════════════ -->
<div class="relative overflow-hidden" id="banner-slider" style="min-height:340px;">
    <?php
    // Build dynamic banners from real data
    $banners = [];

    // Banner 1: Flash Sale nếu có
    if (!empty($activeFlashSale)) {
        $banners[] = [
            'title'  => 'Flash Sale: ' . htmlspecialchars($activeFlashSale['name']),
            'sub'    => 'Giảm giá sốc chỉ trong thời gian có hạn — Đừng bỏ lỡ!',
            'cta'    => 'Mua ngay',
            'badge'  => '⚡ Flash Sale',
            'from'   => '#BF360C',
            'to'     => '#E64A19',
            'link'   => BASE_URL . '?act=books',
        ];
    }

    // Banner 2: Sách mới (dùng sách mới nhất nếu có)
    if (!empty($newBooks)) {
        $firstNew = $newBooks[0];
        $banners[] = [
            'title'  => 'Sách Mới Nhất',
            'sub'    => 'Cập nhật hàng trăm đầu sách mới nhất mỗi tuần tại BookStore',
            'cta'    => 'Xem sách mới',
            'badge'  => '✨ Mới về',
            'from'   => '#0D47A1',
            'to'     => '#1976D2',
            'link'   => BASE_URL . '?act=books',
        ];
    }

    // Banner 3: Danh mục sách (từ danh mục đầu tiên trong DB)
    if (!empty($categories)) {
        $firstCat = $categories[0];
        $banners[] = [
            'title'  => 'Khai phá tri thức',
            'sub'    => 'Hàng nghìn đầu sách chất lượng — Giá tốt nhất thị trường',
            'cta'    => 'Khám phá ngay',
            'badge'  => '📚 ' . htmlspecialchars($firstCat['name']),
            'from'   => '#1B5E20',
            'to'     => '#4CAF50',
            'link'   => BASE_URL . '?act=books&category=' . urlencode($firstCat['slug']),
        ];
    }

    // Fallback nếu không có dữ liệu nào
    if (empty($banners)) {
        $banners[] = [
            'title'  => 'Chào mừng đến BookStore',
            'sub'    => 'Hàng nghìn đầu sách chất lượng đang chờ bạn khám phá',
            'cta'    => 'Xem sách',
            'badge'  => '📚 Sách hay',
            'from'   => '#1B5E20',
            'to'     => '#4CAF50',
            'link'   => BASE_URL . '?act=books',
        ];
    }

    foreach ($banners as $i => $b): ?>
        <div class="banner-slide absolute inset-0 flex flex-col justify-center px-8 md:px-20 transition-opacity duration-700 <?= $i === 0 ? 'opacity-100 relative' : 'opacity-0 pointer-events-none' ?>"
            style="background: linear-gradient(135deg, <?= $b['from'] ?>, <?= $b['to'] ?>); min-height:340px;">

            <div class="absolute right-0 top-0 w-64 h-64 rounded-full opacity-10 bg-white"
                style="transform:translate(30%, -30%)"></div>
            <div class="absolute right-20 bottom-0 w-40 h-40 rounded-full opacity-10 bg-white"
                style="transform:translateY(40%)"></div>

            <div class="relative z-10 max-w-xl">
                <span class="inline-block bg-white/20 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1 rounded-full mb-4">
                    <?= $b['badge'] ?>
                </span>
                <h1 class="text-4xl md:text-5xl text-white font-extrabold mb-4 leading-tight">
                    <?= $b['title'] ?>
                </h1>
                <p class="text-white/85 text-lg mb-7 leading-relaxed">
                    <?= $b['sub'] ?>
                </p>
                <a href="<?= $b['link'] ?>"
                    class="inline-flex items-center gap-2 bg-[#FFC107] hover:bg-[#FFB300] text-[#333] px-7 py-3 rounded-xl font-bold transition-all hover:shadow-lg hover:-translate-y-0.5 transform">
                    <?= $b['cta'] ?> <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Controls -->
    <?php if (count($banners) > 1): ?>
    <button id="banner-prev"
        class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/40 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-all z-10 hover:scale-110">
        <i data-lucide="chevron-left" class="w-5 h-5"></i>
    </button>
    <button id="banner-next"
        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/40 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-all z-10 hover:scale-110">
        <i data-lucide="chevron-right" class="w-5 h-5"></i>
    </button>
    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2 z-10" id="banner-dots">
        <?php for ($i = 0; $i < count($banners); $i++): ?>
            <button class="banner-dot h-2 rounded-full transition-all duration-300 <?= $i === 0 ? 'bg-white w-8' : 'bg-white/50 w-2' ?>"></button>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ═══════════════════════════════════════════════════════════
     TRUST BADGES BAR
═══════════════════════════════════════════════════════════ -->
<div class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-[1200px] mx-auto px-4 py-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php
            $badges = [
                ['icon' => 'truck',        'title' => 'Miễn phí vận chuyển', 'sub' => 'Đơn hàng từ 299K',       'color' => 'text-[#4CAF50]',   'bg' => 'bg-green-50'],
                ['icon' => 'shield-check', 'title' => 'Hàng chính hãng',     'sub' => '100% đảm bảo',            'color' => 'text-[#FFC107]',   'bg' => 'bg-yellow-50'],
                ['icon' => 'refresh-ccw',  'title' => 'Đổi trả dễ dàng',     'sub' => 'Trong vòng 30 ngày',     'color' => 'text-blue-500',    'bg' => 'bg-blue-50'],
                ['icon' => 'headphones',   'title' => 'Hỗ trợ 24/7',         'sub' => 'Tư vấn tận tâm',         'color' => 'text-purple-500',  'bg' => 'bg-purple-50'],
            ];
            foreach ($badges as $b): ?>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 <?= $b['bg'] ?> rounded-xl flex items-center justify-center shrink-0">
                        <i data-lucide="<?= $b['icon'] ?>" class="w-5 h-5 <?= $b['color'] ?>"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-800"><?= $b['title'] ?></p>
                        <p class="text-xs text-gray-400"><?= $b['sub'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════════════════════ -->
<div class="max-w-[1200px] mx-auto px-4 py-10 space-y-14">

    <!-- ── FLASH SALE ── -->
    <?php if (!empty($activeFlashSale) && !empty($saleBooks)): ?>
    <section>
        <div class="bg-gradient-to-r from-red-500 via-orange-500 to-red-600 rounded-t-2xl px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <i data-lucide="zap" class="w-5 h-5 text-white fill-white"></i>
                <span class="text-white font-extrabold text-xl tracking-wide">FLASH SALE</span>
                <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full ml-1"><?= htmlspecialchars($activeFlashSale['name']) ?></span>
            </div>
            <div class="flex items-center gap-2 text-white">
                <i data-lucide="clock" class="w-4 h-4"></i>
                <span class="text-sm">Kết thúc trong:</span>
                <span class="bg-white/20 rounded-lg px-2.5 py-1 text-sm font-mono font-bold" id="t-h">00</span>
                <span class="font-bold">:</span>
                <span class="bg-white/20 rounded-lg px-2.5 py-1 text-sm font-mono font-bold" id="t-m">00</span>
                <span class="font-bold">:</span>
                <span class="bg-white/20 rounded-lg px-2.5 py-1 text-sm font-mono font-bold" id="t-s">00</span>
            </div>
        </div>
        <div class="bg-white rounded-b-2xl p-6 shadow-sm">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                <?php foreach ($saleBooks as $book):
                    $pct = $book['discount_percent'] ?? round((1 - $book['sale_price'] / $book['price']) * 100);
                    ?>
                    <div class="group relative bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-lg transition-all hover:-translate-y-1 cursor-pointer">
                        <div class="absolute top-3 left-3 z-10">
                            <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">-<?= $pct ?>%</span>
                        </div>
                        <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $book['book_id'] ?>"
                            class="block h-44 bg-gray-50 flex items-center justify-center relative overflow-hidden">
                            <img src="<?= !empty($book['thumbnail']) ? (str_starts_with($book['thumbnail'], 'http') ? htmlspecialchars($book['thumbnail']) : rtrim(BASE_URL, '/') . '/' . ltrim($book['thumbnail'], '/')) : 'https://placehold.co/300x400?text=No+Image' ?>"
                                onerror="this.onerror=null; this.src='https://placehold.co/300x400?text=No+Image';"
                                class="h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                alt="<?= htmlspecialchars($book['title']) ?>">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                        </a>
                        <div class="p-4">
                            <p class="text-sm font-semibold text-[#333] truncate mb-0.5">
                                <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $book['book_id'] ?>"><?= htmlspecialchars($book['title']) ?></a>
                            </p>
                            <p class="text-xs text-gray-400 mb-3 truncate"><?= htmlspecialchars($book['author']) ?></p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-base font-extrabold text-[#4CAF50]"><?= number_format($book['sale_price'], 0, ',', '.') ?>₫</span>
                                <span class="text-xs text-gray-400 line-through"><?= number_format($book['price'], 0, ',', '.') ?>₫</span>
                            </div>
                            <button onclick="window.location.href='<?= BASE_URL ?>?act=cart-add&id=<?= $book['book_id'] ?>'"
                                class="w-full py-2 bg-gradient-to-r from-[#4CAF50] to-[#43A047] hover:from-[#43A047] hover:to-[#388E3C] text-white text-xs font-semibold rounded-xl transition-all relative z-20">
                                Thêm giỏ hàng
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── SÁCH MỚI ── -->
    <?php if (!empty($newBooks)): ?>
    <section>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2.5">
                <div class="w-1 h-7 bg-[#4CAF50] rounded-full"></div>
                <i data-lucide="sparkles" class="w-5 h-5 text-[#4CAF50]"></i>
                <h2 class="text-2xl font-extrabold text-[#333]">SÁCH MỚI</h2>
            </div>
            <a href="<?= BASE_URL ?>?act=books" class="text-sm text-[#4CAF50] font-medium hover:underline flex items-center gap-1">
                Xem tất cả <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <?php foreach ($newBooks as $book):
                $displayPrice = $book['sale_price'] ?? $book['price'];
                ?>
                <div class="group bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-lg transition-all hover:-translate-y-1">
                    <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $book['id'] ?>"
                        class="relative block h-44 bg-gray-50 flex items-center justify-center overflow-hidden">
                        <span class="absolute top-3 left-3 bg-[#4CAF50] text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm z-10">MỚI</span>
                        <img src="<?= !empty($book['thumbnail']) ? (str_starts_with($book['thumbnail'], 'http') ? htmlspecialchars($book['thumbnail']) : rtrim(BASE_URL, '/') . '/' . ltrim($book['thumbnail'], '/')) : 'https://placehold.co/300x400?text=No+Image' ?>"
                            onerror="this.onerror=null; this.src='https://placehold.co/300x400?text=No+Image';"
                            class="h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            alt="<?= htmlspecialchars($book['title']) ?>">
                    </a>
                    <div class="p-4">
                        <p class="text-sm font-semibold text-[#333] truncate mb-0.5">
                            <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?></a>
                        </p>
                        <p class="text-xs text-gray-400 mb-3 truncate"><?= htmlspecialchars($book['author']) ?></p>
                        <div class="mb-3">
                            <span class="text-base font-extrabold text-[#4CAF50]"><?= number_format($displayPrice, 0, ',', '.') ?>₫</span>
                            <?php if (!empty($book['sale_price']) && $book['sale_price'] < $book['price']): ?>
                                <span class="text-xs text-gray-400 line-through ml-1"><?= number_format($book['price'], 0, ',', '.') ?>₫</span>
                            <?php endif; ?>
                        </div>
                        <button onclick="window.location.href='<?= BASE_URL ?>?act=cart-add&id=<?= $book['id'] ?>'"
                            class="w-full py-2 bg-gradient-to-r from-[#4CAF50] to-[#43A047] hover:from-[#43A047] hover:to-[#388E3C] text-white text-xs font-semibold rounded-xl transition-all relative z-20 shadow-[0_4px_10px_rgba(76,175,80,0.3)]">
                            Thêm giỏ hàng
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── SÁCH BÁN CHẠY ── -->
    <?php if (!empty($hotBooks)): ?>
    <section>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2.5">
                <div class="w-1 h-7 bg-[#FFC107] rounded-full"></div>
                <i data-lucide="trending-up" class="w-5 h-5 text-[#FFC107]"></i>
                <h2 class="text-2xl font-extrabold text-[#333]">SÁCH BÁN CHẠY</h2>
            </div>
            <a href="<?= BASE_URL ?>?act=books" class="text-sm text-[#4CAF50] font-medium hover:underline flex items-center gap-1">
                Xem tất cả <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <?php
            $rank = 1;
            foreach ($hotBooks as $book):
                $displayPrice = $book['sale_price'] ?? $book['price'];
                $sold = (int)($book['sold_count'] ?? 0);
                ?>
                <div class="group bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-lg transition-all hover:-translate-y-1">
                    <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $book['id'] ?>"
                        class="relative block h-44 bg-gray-50 flex items-center justify-center overflow-hidden">
                        <span class="absolute top-3 left-3 bg-[#FFC107] text-[#333] text-[10px] font-bold w-7 h-7 rounded-full flex items-center justify-center shadow-sm z-10">#<?= $rank++ ?></span>
                        <img src="<?= !empty($book['thumbnail']) ? (str_starts_with($book['thumbnail'], 'http') ? htmlspecialchars($book['thumbnail']) : rtrim(BASE_URL, '/') . '/' . ltrim($book['thumbnail'], '/')) : 'https://placehold.co/300x400?text=No+Image' ?>"
                            onerror="this.onerror=null; this.src='https://placehold.co/300x400?text=No+Image';"
                            class="h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            alt="<?= htmlspecialchars($book['title']) ?>">
                    </a>
                    <div class="p-4">
                        <p class="text-sm font-semibold text-[#333] truncate mb-0.5">
                            <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?></a>
                        </p>
                        <p class="text-xs text-gray-400 mb-1 truncate"><?= htmlspecialchars($book['author']) ?></p>
                        <p class="text-xs text-gray-400 mb-3 flex items-center gap-1">
                            <i data-lucide="bar-chart-2" class="w-3 h-3"></i>
                            Đã bán: <span class="font-medium text-gray-600"><?= $sold > 0 ? number_format($sold) : 'Mới' ?></span>
                        </p>
                        <div class="mb-3">
                            <span class="text-base font-extrabold text-[#4CAF50]"><?= number_format($displayPrice, 0, ',', '.') ?>₫</span>
                            <?php if (!empty($book['sale_price']) && $book['sale_price'] < $book['price']): ?>
                                <span class="text-xs text-gray-400 line-through ml-1"><?= number_format($book['price'], 0, ',', '.') ?>₫</span>
                            <?php endif; ?>
                        </div>
                        <button onclick="window.location.href='<?= BASE_URL ?>?act=cart-add&id=<?= $book['id'] ?>'"
                            class="w-full py-2 bg-gradient-to-r from-[#4CAF50] to-[#43A047] hover:from-[#43A047] hover:to-[#388E3C] text-white text-xs font-semibold rounded-xl transition-all relative z-20 shadow-[0_4px_10px_rgba(76,175,80,0.3)]">
                            Thêm giỏ hàng
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── DANH MỤC NỔI BẬT ── -->
    <?php if (!empty($categories)): ?>
    <section>
        <div class="flex items-center gap-2.5 mb-6">
            <div class="w-1 h-7 bg-purple-500 rounded-full"></div>
            <i data-lucide="grid-3x3" class="w-5 h-5 text-purple-500"></i>
            <h2 class="text-2xl font-extrabold text-[#333]">DANH MỤC NỔI BẬT</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <?php
            $paletteList = [
                ['color' => 'from-blue-50 to-blue-100',     'border' => 'border-blue-200',   'hover' => 'hover:border-blue-400',   'icon' => '📖'],
                ['color' => 'from-green-50 to-green-100',   'border' => 'border-green-200',  'hover' => 'hover:border-green-400',  'icon' => '💼'],
                ['color' => 'from-yellow-50 to-yellow-100', 'border' => 'border-yellow-200', 'hover' => 'hover:border-yellow-400', 'icon' => '🧒'],
                ['color' => 'from-purple-50 to-purple-100', 'border' => 'border-purple-200', 'hover' => 'hover:border-purple-400', 'icon' => '🌟'],
                ['color' => 'from-cyan-50 to-cyan-100',     'border' => 'border-cyan-200',   'hover' => 'hover:border-cyan-400',   'icon' => '🔬'],
                ['color' => 'from-orange-50 to-orange-100', 'border' => 'border-orange-200', 'hover' => 'hover:border-orange-400', 'icon' => '�'],
            ];
            // icon map theo slug
            $slugIconMap = [
                'van-hoc'      => '📖',
                'kinh-te'      => '💼',
                'thieu-nhi'    => '🧒',
                'ky-nang-song' => '🌟',
                'khoa-hoc'     => '🔬',
                'lich-su'      => '📜',
                'tam-ly'       => '🧠',
                'the-thao'     => '⚽',
                'nghe-thuat'   => '🎨',
            ];

            $displayCats = array_slice($categories, 0, 6);
            foreach ($displayCats as $idx => $cat):
                $palette = $paletteList[$idx % count($paletteList)];
                $icon    = $slugIconMap[$cat['slug']] ?? $palette['icon'];
                ?>
                <a href="<?= BASE_URL ?>?act=books&category=<?= urlencode($cat['slug']) ?>"
                    class="flex items-center gap-4 p-5 bg-gradient-to-r <?= $palette['color'] ?> border <?= $palette['border'] ?> <?= $palette['hover'] ?> rounded-2xl hover:shadow-md transition-all hover:-translate-y-0.5">
                    <span class="text-4xl"><?= $icon ?></span>
                    <div>
                        <p class="font-bold text-[#333]"><?= htmlspecialchars($cat['name']) ?></p>
                        <p class="text-sm text-gray-500">Khám phá ngay →</p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ── TẠI SAO CHỌN CHÚNG TÔI ── -->
    <section class="rounded-3xl overflow-hidden bg-gradient-to-br from-[#F9F9F9] to-white border border-gray-100 shadow-sm p-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-extrabold text-[#333] mb-2">Tại sao chọn BookStore?</h2>
            <p class="text-gray-500 text-sm">Chúng tôi cam kết mang lại trải nghiệm mua sách tốt nhất</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php
            $whys = [
                ['icon' => 'package', 'title' => 'Giao hàng siêu tốc', 'sub' => 'Nhận hàng trong 2–4 giờ nội thành',   'color' => 'bg-green-500'],
                ['icon' => 'award',   'title' => 'Sách chính hãng',    'sub' => '100% sách có nguồn gốc rõ ràng',       'color' => 'bg-yellow-500'],
                ['icon' => 'percent', 'title' => 'Giá tốt nhất',       'sub' => 'Cam kết giá cạnh tranh thị trường',    'color' => 'bg-blue-500'],
                ['icon' => 'smile',   'title' => 'Khách hàng hài lòng','sub' => '4.9★ từ hơn 50.000 đánh giá',          'color' => 'bg-purple-500'],
            ];
            foreach ($whys as $w): ?>
                <div class="text-center group">
                    <div class="w-16 h-16 <?= $w['color'] ?> rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                        <i data-lucide="<?= $w['icon'] ?>" class="w-7 h-7 text-white"></i>
                    </div>
                    <h3 class="font-bold text-[#333] mb-1.5 text-sm"><?= $w['title'] ?></h3>
                    <p class="text-xs text-gray-500 leading-relaxed"><?= $w['sub'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ── EMAIL SIGNUP ── -->
    <section class="rounded-3xl overflow-hidden bg-gradient-to-r from-[#1B5E20] via-[#2E7D32] to-[#4CAF50] p-10 flex flex-col md:flex-row items-center justify-between gap-8 relative">
        <div class="absolute right-0 top-0 w-64 h-64 rounded-full bg-white/5" style="transform:translate(20%, -30%)"></div>
        <div class="text-white relative z-10">
            <h3 class="text-3xl font-extrabold mb-2">Đăng ký nhận ưu đãi 🎁</h3>
            <p class="text-white/80 text-base">Nhận thông báo sách mới &amp; voucher giảm giá độc quyền mỗi tuần</p>
        </div>
        <div class="flex gap-2.5 w-full md:w-auto relative z-10">
            <input type="email" placeholder="Nhập email của bạn..."
                class="flex-1 md:w-72 px-5 py-3 rounded-xl outline-none text-sm shadow-inner border-2 border-white/30 bg-white/10 text-white placeholder-white/60 focus:border-white focus:bg-white/20 transition-all">
            <button class="bg-[#FFC107] hover:bg-[#FFB300] text-[#333] px-6 py-3 rounded-xl font-bold transition-all hover:shadow-lg whitespace-nowrap">
                Đăng ký
            </button>
        </div>
    </section>

</div>

<?php require_once './views/components/customer_footer.php'; ?>

<!-- Scripts -->
<script>
    (function () {
        const slides = document.querySelectorAll('.banner-slide');
        const dots   = document.querySelectorAll('.banner-dot');
        let cur = 0;

        if (slides.length > 1) {
            function go(n) {
                slides[cur].classList.remove('opacity-100', 'relative');
                slides[cur].classList.add('opacity-0', 'pointer-events-none');
                if (dots[cur]) { dots[cur].classList.remove('bg-white', 'w-8'); dots[cur].classList.add('bg-white/50', 'w-2'); }
                cur = (n + slides.length) % slides.length;
                slides[cur].classList.remove('opacity-0', 'pointer-events-none');
                slides[cur].classList.add('opacity-100', 'relative');
                if (dots[cur]) { dots[cur].classList.remove('bg-white/50', 'w-2'); dots[cur].classList.add('bg-white', 'w-8'); }
            }

            document.getElementById('banner-prev')?.addEventListener('click', () => go(cur - 1));
            document.getElementById('banner-next')?.addEventListener('click', () => go(cur + 1));
            dots.forEach((d, i) => d.addEventListener('click', () => go(i)));
            setInterval(() => go(cur + 1), 4500);
        }

        // Flash sale countdown timer (real end time from DB)
        <?php if (!empty($activeFlashSale)): ?>
        const endTime = new Date('<?= str_replace(' ', 'T', $activeFlashSale['end_time']) ?>').getTime();
        function updateTimer() {
            const now      = new Date().getTime();
            const distance = endTime - now;

            if (distance <= 0) {
                ['t-h','t-m','t-s'].forEach(id => { const el = document.getElementById(id); if(el) el.textContent = '00'; });
                return;
            }

            const totalHours = Math.floor(distance / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);

            const eh = document.getElementById('t-h');
            const em = document.getElementById('t-m');
            const es = document.getElementById('t-s');
            if (eh) eh.textContent = String(totalHours).padStart(2, '0');
            if (em) em.textContent = String(m).padStart(2, '0');
            if (es) es.textContent = String(s).padStart(2, '0');
        }
        updateTimer();
        setInterval(updateTimer, 1000);
        <?php endif; ?>
    })();
</script>