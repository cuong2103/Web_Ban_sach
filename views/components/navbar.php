<?php
$currentAct = $_GET['act'] ?? 'home';
$isLoggedIn = isset($_SESSION['currentUser']);
$navbarCartCount = 0;

if ($isLoggedIn) {
    $currentUser = $_SESSION['currentUser'] ?? null;
    $currentUserId = (int)($currentUser['id'] ?? 0);
    if ($currentUserId > 0) {
        $navbarCartModel = new CartModel();
        $navbarCartCount = $navbarCartModel->getItemCount($currentUserId);
    }
}
?>
<!DOCTYPE html>
<html lang="vi" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore – Nhà sách trực tuyến</title>
    <script>
        const originalWarn = console.warn;
        console.warn = (...args) => {
            if (args[0] && typeof args[0] === 'string' && args[0].includes('cdn.tailwindcss.com should not be used in production')) return;
            originalWarn.apply(console, args);
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }

        /* Active underline indicator */
        .nav-link { position: relative; padding-bottom: 2px; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -18px; left: 0; right: 0;
            height: 2px;
            background: #4CAF50;
            border-radius: 2px;
            transform: scaleX(0);
            transition: transform 0.2s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after { transform: scaleX(1); }
        .nav-link.active { color: #4CAF50 !important; }

        /* Mega dropdown */
        .has-dropdown { position: relative; }
        .has-dropdown .mega-drop {
            display: none;
            position: absolute;
            top: calc(100% + 18px);
            left: -20px;
            z-index: 999;
        }
        .has-dropdown:hover .mega-drop { display: block; }
    </style>
</head>

<body class="h-full bg-[#F9F9F9]">

<!-- ══════════════════════════════════════════════════════════
     HEADER — single row, full width
══════════════════════════════════════════════════════════ -->
<header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="w-full px-8 flex items-center h-[60px] gap-6">

        <!-- ① Logo -->
        <a href="<?= BASE_URL ?>?act=home" class="flex items-center gap-2 shrink-0">
            <div class="w-8 h-8 bg-gradient-to-br from-[#4CAF50] to-[#2E7D32] rounded-lg flex items-center justify-center shadow-sm">
                <i data-lucide="book-open" class="w-4 h-4 text-white"></i>
            </div>
            <span class="text-[21px] font-extrabold text-[#222] tracking-tight leading-none hidden sm:block">
                Book<span class="text-[#4CAF50]">Store</span>
            </span>
        </a>

        <!-- ② Nav + Search wrapper (center) -->
        <div class="hidden md:flex flex-1 items-center justify-center gap-4">
        <nav class="flex items-center gap-4">

            <a href="<?= BASE_URL ?>?act=home"
               class="nav-link <?= in_array($currentAct, ['/', 'home']) ? 'active' : '' ?> px-4 text-sm font-medium text-gray-700 hover:text-[#4CAF50] transition-colors whitespace-nowrap">
                Trang chủ
            </a>

            <!-- Sản phẩm dropdown -->
            <div class="has-dropdown flex items-center">
                <a href="<?= BASE_URL ?>?act=books"
                   class="nav-link <?= $currentAct === 'books' ? 'active' : '' ?> px-4 text-sm font-medium text-gray-700 hover:text-[#4CAF50] transition-colors flex items-center gap-0.5 whitespace-nowrap">
                    Sản phẩm <i data-lucide="chevron-down" class="w-3.5 h-3.5 mt-0.5 text-gray-400"></i>
                </a>
                <div class="mega-drop">
                    <div class="bg-white shadow-2xl rounded-2xl border border-gray-100 p-6 w-[700px]">
                        <div class="grid grid-cols-3 gap-5">
                            <?php
                            $bookModel = new BookModel();
                            $navbarCategories = [
                                ['icon' => '📖', 'name' => 'Văn học',      'slug' => 'van-hoc'],
                                ['icon' => '💼', 'name' => 'Kinh tế',      'slug' => 'kinh-te'],
                                ['icon' => '🧒', 'name' => 'Thiếu nhi',    'slug' => 'thieu-nhi'],
                                ['icon' => '🌟', 'name' => 'Kỹ năng sống', 'slug' => 'ky-nang-song'],
                                ['icon' => '🔬', 'name' => 'Khoa học',     'slug' => 'khoa-hoc'],
                                ['icon' => '📜', 'name' => 'Lịch sử',      'slug' => 'lich-su'],
                            ];
                            foreach ($navbarCategories as $cat):
                                $navbarBooks = $bookModel->getBooksByCategory($cat['slug'], 4);
                            ?>
                            <div>
                                <a href="<?= BASE_URL ?>?act=books&category=<?= urlencode($cat['slug']) ?>"
                                   class="flex items-center gap-1.5 text-sm font-semibold text-[#333] hover:text-[#4CAF50] mb-2.5 transition-colors">
                                    <span><?= $cat['icon'] ?></span><?= $cat['name'] ?>
                                </a>
                                <div class="space-y-1">
                                    <?php foreach ($navbarBooks as $b): ?>
                                    <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $b['id'] ?>"
                                       class="block text-xs text-gray-400 hover:text-[#4CAF50] line-clamp-1 py-0.5 transition-colors">
                                        <?= htmlspecialchars($b['title']) ?>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <a href="<?= BASE_URL ?>?act=books" class="text-sm text-[#4CAF50] font-semibold hover:underline inline-flex items-center gap-1">
                                Xem tất cả <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="<?= BASE_URL ?>?act=books&tag=sale"
               class="nav-link px-4 text-sm font-medium text-red-500 hover:text-red-600 transition-colors whitespace-nowrap">
                🔥 Khuyến mãi
            </a>

            <a href="<?= BASE_URL ?>?act=about"
               class="nav-link <?= $currentAct === 'about' ? 'active' : '' ?> px-4 text-sm font-medium text-gray-700 hover:text-[#4CAF50] transition-colors whitespace-nowrap">
                Giới thiệu
            </a>

            <a href="<?= BASE_URL ?>?act=contact"
               class="nav-link <?= $currentAct === 'contact' ? 'active' : '' ?> px-4 text-sm font-medium text-gray-700 hover:text-[#4CAF50] transition-colors whitespace-nowrap">
                Liên hệ
            </a>
        </nav>

        <!-- ③ Search (inline with nav) -->
        <form action="<?= BASE_URL ?>?act=books" method="GET" class="flex">
            <input type="hidden" name="act" value="books">
            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden
                        hover:border-[#4CAF50] focus-within:border-[#4CAF50]
                        focus-within:ring-2 focus-within:ring-[#4CAF50]/15 transition-all"
                 style="width:350px;">
                <input type="text" name="search" placeholder="Tìm kiếm sách..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    class="flex-1 pl-4 pr-1 py-2 text-sm outline-none bg-transparent text-gray-700 placeholder-gray-400">
                <button type="submit" class="px-3 py-2 text-gray-400 hover:text-[#4CAF50] transition-colors">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </div>
        </form>
        </div><!-- end nav+search wrapper -->

        <!-- ④ Tài khoản + Giỏ hàng + Đăng ký -->
        <div class="hidden md:flex items-center gap-4 text-sm shrink-0">
            <?php
            $currentUser = $_SESSION['currentUser'] ?? null;
            if ($isLoggedIn): ?>
                <!-- Logged in -->
                <div class="has-dropdown flex items-center h-full">
                    <div class="flex items-center gap-1.5 text-gray-600 hover:text-[#4CAF50] transition-colors cursor-pointer py-2">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span class="max-w-[100px] truncate font-medium"><?= htmlspecialchars($currentUser['fullname'] ?? 'Tài khoản') ?></span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 mt-0.5"></i>
                    </div>
                    <!-- Dropdown Menu tài khoản -->
                    <div class="mega-drop" style="width: 200px; left: auto; right: -10px; top: 100%;">
                        <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-2 mt-2 relative">
                            <!-- Mũi tên chỉ lên -->
                            <div class="absolute -top-1.5 right-6 w-3 h-3 bg-white border-l border-t border-gray-100 rotate-45"></div>
                            
                            <a href="<?= BASE_URL ?>?act=profile" class="relative z-10 flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold text-gray-700 hover:bg-green-50 hover:text-[#4CAF50] rounded-xl transition-colors">
                                <i data-lucide="user-cog" class="w-4 h-4"></i> Tài khoản của tôi
                            </a>
                            <a href="<?= BASE_URL ?>?act=orders" class="relative z-10 flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold text-gray-700 hover:bg-green-50 hover:text-[#4CAF50] rounded-xl mt-1 transition-colors">
                                <i data-lucide="receipt" class="w-4 h-4"></i> Lịch sử đơn hàng
                            </a>
                            <div class="h-px w-full bg-gray-100 my-1 relative z-10"></div>
                            <a href="<?= BASE_URL ?>?act=logout" class="relative z-10 flex items-center gap-2.5 px-3 py-2 text-sm font-medium text-red-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition-colors">
                                <i data-lucide="log-out" class="w-4 h-4 text-red-400"></i> Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>
                <div class="h-4 w-px bg-gray-200"></div>
                <a href="<?= BASE_URL ?>?act=cart"
                   class="flex items-center gap-1.5 text-gray-600 hover:text-[#4CAF50] transition-colors relative py-2">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span>Giỏ hàng</span>
                    <span class="<?= $navbarCartCount > 0 ? '' : 'hidden' ?> bg-[#4CAF50] text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center"
                          id="cart-badge"><?= $navbarCartCount ?></span>
                </a>
            <?php else: ?>
                <!-- Guest: link đến trang đăng nhập -->
                <a href="<?= BASE_URL ?>?act=login"
                   class="flex items-center gap-1.5 text-gray-600 hover:text-[#4CAF50] transition-colors">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span>Tài khoản</span>
                </a>
                <div class="h-4 w-px bg-gray-200"></div>
                <a href="<?= BASE_URL ?>?act=cart"
                   class="flex items-center gap-1.5 text-gray-600 hover:text-[#4CAF50] transition-colors">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span>Giỏ hàng (<?= $navbarCartCount ?>)</span>
                </a>
            <?php endif; ?>
        </div>



        <!-- Mobile hamburger -->
        <button class="md:hidden ml-auto p-2 rounded-lg hover:bg-gray-50"
                onclick="document.getElementById('mob-nav').classList.toggle('hidden')">
            <i data-lucide="menu" class="w-5 h-5 text-gray-700"></i>
        </button>

    </div><!-- end row -->

    <!-- Mobile Menu -->
    <div id="mob-nav" class="md:hidden hidden border-t border-gray-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            <!-- Mobile search -->
            <form action="<?= BASE_URL ?>?act=books" method="GET" class="pb-2">
                <input type="hidden" name="act" value="books">
                <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden focus-within:border-[#4CAF50]">
                    <input type="text" name="search" placeholder="Tìm kiếm sách..."
                           class="flex-1 px-4 py-2.5 text-sm outline-none">
                    <button type="submit" class="px-4 py-2.5 bg-[#4CAF50] text-white">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
            <a href="<?= BASE_URL ?>?act=home"    class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">🏠 Trang chủ</a>
            <?php foreach ($navbarCategories as $cat): ?>
            <a href="<?= BASE_URL ?>?act=books&category=<?= urlencode($cat['slug']) ?>"
               class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">
                <?= $cat['icon'] ?> <?= $cat['name'] ?>
            </a>
            <?php endforeach; ?>
            <a href="<?= BASE_URL ?>?act=about"   class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">📋 Giới thiệu</a>
            <a href="<?= BASE_URL ?>?act=contact" class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">📬 Liên hệ</a>
            <?php if ($isLoggedIn): ?>
            <a href="<?= BASE_URL ?>?act=orders" class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">
                <i data-lucide="package" class="w-4 h-4"></i> Đơn hàng
            </a>
            <a href="<?= BASE_URL ?>?act=cart" class="flex items-center gap-2 px-3 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Giỏ hàng (<?= $navbarCartCount ?>)
            </a>
            <a href="<?= BASE_URL ?>?act=logout" class="flex items-center gap-2 px-3 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-lg font-medium">
                <i data-lucide="log-out" class="w-4 h-4"></i> Đăng xuất
            </a>
            <?php else: ?>
            <div class="flex gap-2 pt-1">
                <a href="<?= BASE_URL ?>?act=login"    class="flex-1 text-center py-2.5 border border-gray-300 rounded-xl text-sm font-medium">Đăng nhập</a>
                <a href="<?= BASE_URL ?>?act=register" class="flex-1 text-center py-2.5 bg-[#4CAF50] text-white rounded-xl text-sm font-semibold">Đăng ký</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

</header>

<!-- Page content starts here -->