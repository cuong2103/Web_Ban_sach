<?php require_once './views/components/navbar.php'; ?>

<div class="bg-[#F8F9FA] py-6 min-h-[calc(100vh-80px)] font-sans">
    <div class="max-w-[1200px] mx-auto px-4">

        <!-- ── BREADCRUMBS ── -->
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="<?= BASE_URL ?>" class="hover:text-[#4CAF50] transition-colors"><i data-lucide="home"
                    class="w-4 h-4"></i></a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">Giỏ hàng</span>
        </nav>

        <!-- ── PAGE HEADER ── -->
        <div class="mb-8">
            <h1 class="text-3xl lg:text-4xl font-extrabold text-[#333] mb-2">Giỏ hàng của bạn</h1>
            <p class="text-gray-600">
                <?= $itemCount ?> sản phẩm trong giỏ
            </p>
        </div>

        <?php if (empty($cartItems)): ?>
            <!-- ── EMPTY CART ── -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="flex justify-center mb-6">
                    <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Giỏ hàng của bạn đang trống</h2>
                <p class="text-gray-500 mb-8">Hãy thêm một số sản phẩm để bắt đầu mua sắm</p>
                <a href="<?= BASE_URL ?>?act=books"
                    class="inline-flex items-center gap-2 bg-[#4CAF50] text-white px-6 py-3 rounded-xl font-bold hover:bg-green-600 transition-colors">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i> Tiếp tục mua sắm
                </a>
            </div>

        <?php else: ?>
            <!-- ── CART ITEMS ── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Products List (Left) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 divide-y divide-gray-100">

                        <?php foreach ($cartItems as $index => $item): ?>
                            <div class="cart-item p-4 lg:p-6 hover:bg-gray-50 transition-colors" data-cart-item-id="<?= $item['cart_item_id'] ?>">
                                <div class="flex gap-4 lg:gap-6">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-24 h-32 lg:w-28 lg:h-36 rounded-xl overflow-hidden bg-gray-100 border border-gray-200">
                                            <img src="<?= htmlspecialchars($item['thumbnail'] ?? 'https://placehold.co/400x500?text=No+Image') ?>"
                                                alt="<?= htmlspecialchars($item['title']) ?>"
                                                onerror="this.src='https://placehold.co/400x500?text=No+Image'"
                                                class="w-full h-full object-cover">
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 flex flex-col">
                                        <div class="mb-4">
                                            <h3 class="text-lg lg:text-xl font-bold text-[#333] mb-1 line-clamp-2">
                                                <?= htmlspecialchars($item['title']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-600 mb-2">
                                                Tác giả: <span class="font-medium text-[#4CAF50]">
                                                    <?= htmlspecialchars($item['author'] ?? 'N/A') ?>
                                                </span>
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Giá: <span class="font-bold text-[#333]">
                                                    <?= number_format($item['price'], 0, ',', '.') ?> ₫
                                                </span>
                                            </p>
                                        </div>

                                        <!-- Quantity & Total -->
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-auto">
                                            <!-- Quantity Control -->
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm text-gray-600 mr-2">Số lượng:</span>
                                                <div class="flex items-center border-2 border-gray-200 rounded-lg overflow-hidden bg-white h-9">
                                                    <button type="button" class="qty-decrease w-9 h-9 flex items-center justify-center hover:bg-gray-100 text-gray-600 transition-colors"
                                                        data-cart-item-id="<?= $item['cart_item_id'] ?>">
                                                        <i data-lucide="minus" class="w-4 h-4"></i>
                                                    </button>
                                                    <input type="number" class="qty-input w-12 h-9 text-center border-x-2 border-gray-200 focus:outline-none font-bold remove-arrow"
                                                        value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>"
                                                        data-cart-item-id="<?= $item['cart_item_id'] ?>">
                                                    <button type="button" class="qty-increase w-9 h-9 flex items-center justify-center hover:bg-gray-100 text-gray-600 transition-colors"
                                                        data-cart-item-id="<?= $item['cart_item_id'] ?>">
                                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                                <span class="text-xs text-gray-500 ml-2">Còn: <?= $item['stock'] ?></span>
                                            </div>

                                            <!-- Item Total & Delete -->
                                            <div class="flex items-center gap-4">
                                                <div class="text-right">
                                                    <p class="text-xs text-gray-500">Thành tiền</p>
                                                    <p class="text-lg lg:text-xl font-bold text-[#4CAF50] item-total">
                                                        <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> ₫
                                                    </p>
                                                </div>
                                                <button type="button" class="btn-remove text-red-500 hover:text-red-700 transition-colors p-2 rounded-lg hover:bg-red-50"
                                                    data-cart-item-id="<?= $item['cart_item_id'] ?>"
                                                    title="Xoá sản phẩm">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>

                    <!-- Continue Shopping -->
                    <div class="mt-6">
                        <a href="<?= BASE_URL ?>?act=books"
                            class="inline-flex items-center gap-2 text-[#4CAF50] font-bold hover:text-green-600 transition-colors">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                </div>

                <!-- ── ORDER SUMMARY (Right) ── -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-28 h-fit">
                        <h2 class="text-xl font-bold text-[#333] mb-6">Tóm tắt đơn hàng</h2>

                        <!-- Summary Details -->
                        <div class="space-y-4 mb-6 pb-6 border-b border-gray-100">
                            <div class="flex justify-between text-gray-700">
                                <span>Số sản phẩm:</span>
                                <span class="font-bold item-count"><?= $itemCount ?></span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Tổng tiền hàng:</span>
                                <span class="font-bold total-amount"><?= number_format($totalPrice, 0, ',', '.') ?> ₫</span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Phí vận chuyển:</span>
                                <span class="font-bold text-[#4CAF50]">Miễn phí</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-baseline mb-6 pb-6 border-b border-gray-100">
                            <span class="text-lg font-bold text-[#333]">Tổng cộng:</span>
                            <span class="text-2xl lg:text-3xl font-extrabold text-[#4CAF50] final-total">
                                <?= number_format($totalPrice, 0, ',', '.') ?> ₫
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button type="button" onclick="proceedToCheckout()"
                                class="w-full bg-[#4CAF50] text-white py-3.5 px-4 rounded-xl font-bold hover:bg-green-600 transition-colors flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                                <i data-lucide="check-circle" class="w-5 h-5"></i> Thanh toán
                            </button>
                            <button type="button" onclick="confirmClearCart()"
                                class="w-full bg-white border-2 border-red-200 text-red-600 py-3.5 px-4 rounded-xl font-bold hover:bg-red-50 transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="trash-2" class="w-5 h-5"></i> Xoá giỏ hàng
                            </button>
                        </div>

                        <!-- Promo Code -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <label class="text-sm font-medium text-gray-700 block mb-2">Mã giảm giá (sắp có)</label>
                            <div class="flex gap-2">
                                <input type="text" placeholder="Nhập mã" class="flex-1 px-3 py-2 border-2 border-gray-200 rounded-lg text-sm focus:border-[#4CAF50] focus:outline-none" disabled>
                                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-bold cursor-not-allowed" disabled>
                                    Áp dụng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<script>
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
    `;
    document.head.appendChild(style);

    // Update quantity handlers
    document.querySelectorAll('.qty-increase').forEach(btn => {
        btn.addEventListener('click', function () {
            const cartItemId = this.dataset.cartItemId;
            const qtyInput = document.querySelector(`.qty-input[data-cart-item-id="${cartItemId}"]`);
            const currentQty = parseInt(qtyInput.value) || 1;
            const maxQty = parseInt(qtyInput.max) || 1;
            if (currentQty < maxQty) {
                qtyInput.value = currentQty + 1;
                updateCartItem(cartItemId, currentQty + 1);
            }
        });
    });

    document.querySelectorAll('.qty-decrease').forEach(btn => {
        btn.addEventListener('click', function () {
            const cartItemId = this.dataset.cartItemId;
            const qtyInput = document.querySelector(`.qty-input[data-cart-item-id="${cartItemId}"]`);
            const currentQty = parseInt(qtyInput.value) || 1;
            if (currentQty > 1) {
                qtyInput.value = currentQty - 1;
                updateCartItem(cartItemId, currentQty - 1);
            }
        });
    });

    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function () {
            const cartItemId = this.dataset.cartItemId;
            let qty = parseInt(this.value) || 1;
            const maxQty = parseInt(this.max) || 1;
            if (qty < 1) qty = 1;
            if (qty > maxQty) qty = maxQty;
            this.value = qty;
            updateCartItem(cartItemId, qty);
        });
    });

    // Remove product handler
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function () {
            if (confirm('Bạn chắc chắn muốn xoá sản phẩm này?')) {
                removeCartItem(this.dataset.cartItemId);
            }
        });
    });

    // Update cart item via AJAX
    function updateCartItem(cartItemId, quantity) {
        const formData = new FormData();
        formData.append('cart_item_id', cartItemId);
        formData.append('quantity', quantity);

        fetch('<?= BASE_URL ?>?act=cart-update-qty', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update individual item total
                    const cartItem = document.querySelector(`.cart-item[data-cart-item-id="${cartItemId}"]`);
                    if (cartItem) {
                        cartItem.querySelector('.item-total').textContent = data.itemTotal + ' ₫';
                    }
                    // Update summary
                    updateSummary(data);
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra!');
            });
    }

    // Remove cart item via AJAX
    function removeCartItem(cartItemId) {
        const formData = new FormData();
        formData.append('cart_item_id', cartItemId);

        fetch('<?= BASE_URL ?>?act=cart-remove', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the product row
                    const cartItem = document.querySelector(`.cart-item[data-cart-item-id="${cartItemId}"]`);
                    if (cartItem) {
                        cartItem.style.opacity = '0.5';
                        toastr.success(data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    }
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra!');
            });
    }

    // Clear entire cart
    function confirmClearCart() {
        if (confirm('Bạn chắc chắn muốn xoá toàn bộ giỏ hàng?')) {
            const formData = new FormData();

            fetch('<?= BASE_URL ?>?act=cart-clear', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra!');
                });
        }
    }

    // Update summary after item change
    function updateSummary(data) {
        document.querySelector('.item-count').textContent = data.itemCount;
        document.querySelector('.total-amount').textContent = data.totalPrice + ' ₫';
        document.querySelector('.final-total').textContent = data.totalPrice + ' ₫';
    }

    // Proceed to checkout (placeholder)
    function proceedToCheckout() {
        alert('Tính năng thanh toán sắp được phát triển!');
        // Redirect to checkout page
        // window.location.href = '<?= BASE_URL ?>?act=checkout';
    }

    lucide.createIcons();
</script>

<?php require_once './views/components/customer_footer.php'; ?>
