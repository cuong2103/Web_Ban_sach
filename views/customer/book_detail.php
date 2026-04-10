<?php require_once './views/components/navbar.php'; ?>

<?php
$salePrice = $book['sale_price'];
$originalPrice = $book['price'];
$discount = 0;
if ($salePrice && $salePrice < $originalPrice) {
    $discount = round((1 - $salePrice / $originalPrice) * 100);
} else {
    $salePrice = $originalPrice;
    $originalPrice = null;
}

// Prepare image gallery
$mainImage = $book['thumbnail'] ?? '';
$mainImage = !empty($mainImage) ? (str_starts_with($mainImage, 'http') ? $mainImage : rtrim(BASE_URL, '/') . '/' . ltrim($mainImage, '/')) : 'https://placehold.co/600x800?text=No+Image';

$gallery = array_merge([['image_url' => $mainImage]], array_map(function ($img) {
    $url = $img['image_url'] ?? '';
    $img['image_url'] = !empty($url) ? (str_starts_with($url, 'http') ? $url : rtrim(BASE_URL, '/') . '/' . ltrim($url, '/')) : 'https://placehold.co/600x800?text=No+Image';
    return $img;
}, $images ?? []));
?>

<div class="bg-[#F8F9FA] py-6 min-h-screen font-sans">
    <div class="max-w-[1200px] mx-auto px-4">

        <!-- ── BREADCRUMBS ── -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="<?= BASE_URL ?>" class="hover:text-[#4CAF50] transition-colors"><i data-lucide="home"
                    class="w-4 h-4"></i></a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <a href="<?= BASE_URL ?>?act=books&category=<?= urlencode($book['slug'] ?? '') ?>"
                class="hover:text-[#4CAF50] transition-colors">
                <?= htmlspecialchars($book['category_name'] ?? 'Danh mục') ?>
            </a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium truncate max-w-xs">
                <?= htmlspecialchars($book['title']) ?>
            </span>
        </nav>

        <!-- ── MAIN PRODUCT SECTION ── -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 lg:p-8 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                <!-- LEFT COLUMN: IMAGES -->
                <div class="lg:col-span-5 relative">
                    <div class="sticky top-28">
                        <div
                            class="aspect-[3/4] rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 mb-4 group relative">
                            <img id="main-product-image" src="<?= htmlspecialchars($mainImage) ?>"
                                onerror="this.onerror=null; this.src='https://placehold.co/600x800?text=No+Image';"
                                alt="<?= htmlspecialchars($book['title']) ?>"
                                class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-500">
                            <?php if ($discount > 0): ?>
                                <div
                                    class="absolute top-4 left-4 bg-red-500 text-white font-bold px-3 py-1.5 rounded-full text-sm shadow-md">
                                    -
                                    <?= $discount ?>%
                                </div>
                            <?php endif; ?>
                            <?php if ($book['is_bestseller']): ?>
                                <div
                                    class="absolute top-4 right-4 bg-[#FFC107] text-[#333] font-bold px-3 py-1.5 rounded-full text-sm shadow-md flex items-center gap-1">
                                    <i data-lucide="trending-up" class="w-4 h-4"></i> Bestseller
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- THUMBNAILS -->
                        <?php if (count($gallery) > 1): ?>
                            <div class="flex gap-3 overflow-x-auto pb-2 custom-scrollbar">
                                <?php foreach ($gallery as $index => $img): ?>
                                    <button onclick="changeMainImage('<?= htmlspecialchars($img['image_url']) ?>', this)"
                                        class="thumbnail-btn flex-shrink-0 w-20 h-24 rounded-xl border-2 overflow-hidden transition-all <?= $index === 0 ? 'border-[#4CAF50] shadow-md' : 'border-transparent hover:border-gray-200' ?>">
                                        <img src="<?= htmlspecialchars($img['image_url']) ?>"
                                            onerror="this.onerror=null; this.src='https://placehold.co/600x800?text=No+Image';"
                                            class="w-full h-full object-cover">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- RIGHT COLUMN: PRODUCT INFO -->
                <div class="lg:col-span-7 flex flex-col">
                    <!-- Title & Meta -->
                    <h1 class="text-3xl lg:text-4xl font-extrabold text-[#333] mb-4 leading-tight">
                        <?= htmlspecialchars($book['title']) ?>
                    </h1>

                    <div
                        class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6 pb-6 border-b border-gray-100">
                        <div class="flex items-center gap-1.5"><i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                            Tác giả: <span class="text-[#4CAF50] font-medium">
                                <?= htmlspecialchars($book['author'] ?: 'Đang cập nhật') ?>
                            </span></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="flex items-center gap-1.5"><i data-lucide="book-open"
                                class="w-4 h-4 text-gray-400"></i> NXB: <span class="font-medium text-gray-800">
                                <?= htmlspecialchars($book['publisher'] ?: 'Đang cập nhật') ?>
                            </span></div>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <div class="flex items-center gap-1 text-[#FFC107]">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star-half" class="w-4 h-4 fill-current"></i>
                            <span class="text-gray-500 ml-1">(120 đánh giá)</span>
                        </div>
                    </div>

                    <!-- Price Box -->
                    <div class="bg-gradient-to-r from-green-50 to-white rounded-2xl p-6 mb-8 border border-green-100">
                        <div class="flex items-end gap-3 mb-2">
                            <span class="text-4xl font-extrabold text-[#4CAF50]">
                                <?= number_format($salePrice, 0, ',', '.') ?>₫
                            </span>
                            <?php if ($originalPrice): ?>
                                <span class="text-lg text-gray-400 line-through mb-1">
                                    <?= number_format($originalPrice, 0, ',', '.') ?>₫
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-green-700 font-medium mt-3">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Giá đã bao gồm VAT
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <?php if (!empty($book['cover_type'] ?? null)): ?>
                            <div class="bg-gray-50 p-3 rounded-xl flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-gray-500 shadow-sm">
                                    <i data-lucide="layout" class="w-5 h-5"></i></div>
                                <div>
                                    <p class="text-xs text-gray-500">Hình thức bìa</p>
                                    <p class="font-semibold text-gray-800 text-sm">
                                        <?= htmlspecialchars($book['cover_type'] ?? 'Bìa mềm') ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($book['weight'] ?? null)): ?>
                            <div class="bg-gray-50 p-3 rounded-xl flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-gray-500 shadow-sm">
                                    <i data-lucide="weight" class="w-5 h-5"></i></div>
                                <div>
                                    <p class="text-xs text-gray-500">Trọng lượng (gr)</p>
                                    <p class="font-semibold text-gray-800 text-sm">
                                        <?= htmlspecialchars($book['weight'] ?? 'Đang cập nhật') ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Action Area -->
                    <div class="mt-auto">
                        <form action="<?= BASE_URL ?>?act=cart-add" method="POST" id="add-to-cart-form">
                            <input type="hidden" name="id" value="<?= $book['id'] ?>">

                            <!-- Quantity -->
                            <div class="flex items-center gap-4 mb-6">
                                <span class="text-gray-700 font-medium">Số lượng:</span>
                                <div
                                    class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden w-auto h-11 bg-white">
                                    <button type="button" onclick="updateQty(-1)"
                                        class="w-11 h-full flex items-center justify-center hover:bg-gray-100 text-gray-600 transition-colors"><i
                                            data-lucide="minus" class="w-4 h-4"></i></button>
                                    <input type="number" name="quantity" id="quantity-input" value="1" min="1"
                                        max="<?= max(1, $book['stock']) ?>"
                                        class="w-14 h-full text-center border-x-2 border-gray-200 focus:outline-none font-bold text-gray-800 remove-arrow">
                                    <button type="button" onclick="updateQty(1)"
                                        class="w-11 h-full flex items-center justify-center hover:bg-gray-100 text-gray-600 transition-colors"><i
                                            data-lucide="plus" class="w-4 h-4"></i></button>
                                </div>
                                <span class="text-sm text-gray-500 ml-2">
                                    <?php if ($book['stock'] > 0): ?>
                                        Còn
                                        <?= $book['stock'] ?> sản phẩm
                                    <?php else: ?>
                                        <span class="text-red-500 font-semibold">Tạm hết hàng</span>
                                    <?php endif; ?>
                                </span>
                            </div>

                            <!-- Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3">
                                <?php if ($book['stock'] > 0): ?>
                                    <button type="button" onclick="addToCart(false)"
                                        class="flex-1 mt-0 bg-white border-2 border-[#4CAF50] text-[#4CAF50] hover:bg-green-50 py-3.5 px-6 rounded-xl font-bold flex items-center justify-center gap-2 transition-all hover:shadow-md">
                                        <i data-lucide="shopping-cart" class="w-5 h-5"></i> Thêm vào giỏ
                                    </button>
                                    <button type="button" onclick="addToCart(true)"
                                        class="flex-1 mt-0 bg-gradient-to-r from-[#4CAF50] to-[#388E3C] hover:from-[#43A047] hover:to-[#2E7D32] text-white py-3.5 px-6 rounded-xl font-bold flex items-center justify-center gap-2 transition-all hover:shadow-lg hover:-translate-y-0.5">
                                        MUA NGAY
                                    </button>
                                <?php else: ?>
                                    <button type="button" disabled
                                        class="w-full bg-gray-300 text-white cursor-not-allowed py-3.5 px-6 rounded-xl font-bold flex items-center justify-center gap-2">
                                        Hàng sắp về
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>

                        <!-- Trust Badges Under Buttons -->
                        <div class="grid grid-cols-2 gap-3 mt-8 pt-6 border-t border-gray-100">
                            <div class="flex items-center gap-2 text-sm text-gray-600"><i data-lucide="shield-check"
                                    class="w-5 h-5 text-blue-500"></i> Hàng chính hãng 100%</div>
                            <div class="flex items-center gap-2 text-sm text-gray-600"><i data-lucide="package"
                                    class="w-5 h-5 text-green-500"></i> Giao hàng toàn quốc</div>
                            <div class="flex items-center gap-2 text-sm text-gray-600"><i data-lucide="refresh-ccw"
                                    class="w-5 h-5 text-purple-500"></i> Đổi trả 30 ngày</div>
                            <div class="flex items-center gap-2 text-sm text-gray-600"><i data-lucide="credit-card"
                                    class="w-5 h-5 text-orange-500"></i> Thanh toán an toàn</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── DESCRIPTION & SPECS ── -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Left: Description -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 h-full">
                    <h2 class="text-2xl font-extrabold text-[#333] mb-6 flex items-center gap-2">
                        <i data-lucide="align-left" class="w-6 h-6 text-[#4CAF50]"></i> Mô tả sản phẩm
                    </h2>
                    <?php $isLongDesc = !empty($book['description']) && mb_strlen($book['description']) > 800; ?>
                    <div class="prose prose-sm sm:prose-base max-w-none text-gray-700 leading-relaxed font-sans mt-4 relative overflow-hidden"
                        id="desc-content" <?= $isLongDesc ? 'style="max-height: 400px;"' : '' ?>>
                        <?php if (!empty($book['description'])): ?>
                            <?= nl2br(htmlspecialchars($book['description'])) ?>
                        <?php else: ?>
                            <p class="text-gray-500 italic">Chưa có mô tả cho sách này.</p>
                        <?php endif; ?>

                        <?php if ($isLongDesc): ?>
                            <!-- Gradient Overlay -->
                            <div id="desc-gradient"
                                class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-white to-transparent pointer-events-none transition-opacity">
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($isLongDesc): ?>
                        <div class="mt-4 text-center">
                            <button id="desc-toggle-btn" onclick="toggleDesc()"
                                class="text-[#4CAF50] font-bold hover:underline flex items-center gap-1 mx-auto">
                                Xem thêm <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: Specifications -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 h-full">
                    <h2 class="text-xl font-extrabold text-[#333] mb-6 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-[#4CAF50]"></i> Thông tin chi tiết
                    </h2>
                    <div class="space-y-4 text-sm mt-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Kích thước</span>
                            <span class="font-medium text-gray-800">
                                <?= htmlspecialchars($book['dimensions'] ?? 'Đang cập nhật') ?>
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Trọng lượng</span>
                            <span class="font-medium text-gray-800">
                                <?= htmlspecialchars($book['weight'] ?? 'Đang cập nhật') ?>
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Hình thức</span>
                            <span class="font-medium text-gray-800">
                                <?= htmlspecialchars($book['cover_type'] ?? 'Bìa mềm') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── RELATED BOOKS ── -->
        <?php if (!empty($relatedBooks)): ?>
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-extrabold text-[#333] flex items-center gap-2">
                        <i data-lucide="layers" class="w-6 h-6 text-[#FFC107]"></i> Có thể bạn sẽ thích
                    </h2>
                    <a href="<?= BASE_URL ?>?act=books&category=<?= urlencode($book['slug'] ?? '') ?>"
                        class="text-[#4CAF50] font-medium text-sm hover:underline flex items-center gap-1">
                        Xem thêm <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5">
                    <?php foreach ($relatedBooks as $rBook):
                        $rDisplayPrice = $rBook['sale_price'] ?? $rBook['price'];
                        $rOriginalPrice = $rBook['sale_price'] ? $rBook['price'] : null;
                        ?>
                        <div
                            class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:shadow-lg transition-all hover:-translate-y-1 group">
                            <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $rBook['id'] ?>"
                                class="block mb-3 bg-gray-50 rounded-xl overflow-hidden aspect-[3/4] relative">
                                <img src="<?= !empty($rBook['thumbnail']) ? (str_starts_with($rBook['thumbnail'], 'http') ? htmlspecialchars($rBook['thumbnail']) : rtrim(BASE_URL, '/') . '/' . ltrim($rBook['thumbnail'], '/')) : 'https://placehold.co/300x400?text=No+Image' ?>"
                                    onerror="this.onerror=null; this.src='https://placehold.co/300x400?text=No+Image';"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <?php if ($rBook['sale_price']): ?>
                                    <div
                                        class="absolute top-2 right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                        -
                                        <?= round((1 - $rBook['sale_price'] / $rBook['price']) * 100) ?>%
                                    </div>
                                <?php endif; ?>
                            </a>
                            <h3
                                class="font-bold text-[#333] text-sm mb-1 line-clamp-2 min-h-[40px] group-hover:text-[#4CAF50] transition-colors">
                                <a href="<?= BASE_URL ?>?act=book-detail&id=<?= $rBook['id'] ?>">
                                    <?= htmlspecialchars($rBook['title']) ?>
                                </a></h3>
                            <p class="text-xs text-gray-500 mb-2 truncate">
                                <?= htmlspecialchars($rBook['author']) ?>
                            </p>
                            <div class="flex items-baseline gap-2 mb-3">
                                <span class="text-base font-extrabold text-[#4CAF50]">
                                    <?= number_format($rDisplayPrice, 0, ',', '.') ?>₫
                                </span>
                                <?php if ($rOriginalPrice): ?>
                                    <span class="text-xs text-gray-400 line-through">
                                        <?= number_format($rOriginalPrice, 0, ',', '.') ?>₫
                                    </span>
                                <?php endif; ?>
                            </div>
                            <button onclick="window.location.href='<?= BASE_URL ?>?act=cart-add&id=<?= $rBook['id'] ?>'"
                                class="w-full py-2 bg-gradient-to-r from-[#4CAF50] to-[#43A047] hover:from-[#43A047] hover:to-[#388E3C] text-white text-xs font-semibold rounded-xl transition-all relative z-20 shadow-[0_4px_10px_rgba(76,175,80,0.3)]">
                                Thêm giỏ hàng
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once './views/components/customer_footer.php'; ?>

<!-- ── SCRIPTS ── -->
<script>
    lucide.createIcons();

    // Image Gallery Handling
    function changeMainImage(url, btn) {
        document.getElementById('main-product-image').src = url;

        // Update styling
        document.querySelectorAll('.thumbnail-btn').forEach(el => {
            el.classList.remove('border-[#4CAF50]', 'shadow-md');
            el.classList.add('border-transparent');
        });
        btn.classList.remove('border-transparent');
        btn.classList.add('border-[#4CAF50]', 'shadow-md');
    }

    // Quantity Input Handling
    const qtyInput = document.getElementById('quantity-input');
    function updateQty(change) {
        let current = parseInt(qtyInput.value) || 1;
        let max = parseInt(qtyInput.getAttribute('max')) || 1;
        let newVal = current + change;

        if (newVal >= 1 && newVal <= max) {
            qtyInput.value = newVal;
        }
    }

    qtyInput.addEventListener('change', function () {
        let val = parseInt(this.value) || 1;
        let max = parseInt(this.getAttribute('max')) || 1;
        if (val < 1) val = 1;
        if (val > max) val = max;
        this.value = val;
    });

    // Add to Cart / Buy Now Handlers
    function addToCart(isBuyNow) {
        const form = document.getElementById('add-to-cart-form');
        if (isBuyNow) {
            // Biến url thành checkout hoặc giữ nguyên rồi redirect
            // Hiện tại CartController add xong redirect back hoặc cart
            // Thêm field đánh dấu buy_now
            const buyNowInput = document.createElement('input');
            buyNowInput.type = 'hidden';
            buyNowInput.name = 'buy_now';
            buyNowInput.value = '1';
            form.appendChild(buyNowInput);
        }
        form.submit();
    }

    // Description Toggle
    function toggleDesc() {
        const content = document.getElementById('desc-content');
        const gradient = document.getElementById('desc-gradient');
        const btn = document.getElementById('desc-toggle-btn');
        const icon = btn.querySelector('i');

        if (content.style.maxHeight === '400px') {
            content.style.maxHeight = 'none';
            gradient.style.opacity = '0';
            btn.innerHTML = 'Thu gọn <i data-lucide="chevron-up" class="w-4 h-4"></i>';
            lucide.createIcons();
        } else {
            content.style.maxHeight = '400px';
            gradient.style.opacity = '1';
            btn.innerHTML = 'Xem thêm <i data-lucide="chevron-down" class="w-4 h-4"></i>';
            lucide.createIcons();
            // Scroll back up gently
            content.closest('div').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // CSS Hide number input arrows
    const style = document.createElement('style');
    style.innerHTML = `
        input[type="number"].remove-arrow::-webkit-inner-spin-button,
        input[type="number"].remove-arrow::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"].remove-arrow {
            -moz-appearance: textfield;
        }
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    `;
    document.head.appendChild(style);
</script>
</body>

</html>