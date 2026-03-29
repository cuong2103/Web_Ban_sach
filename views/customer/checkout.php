<?php
require_once './views/components/navbar.php';
?>

<div class="max-w-[1200px] mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white border border-gray-200 rounded-xl p-6">
            <h2 class="font-semibold mb-4">Thông tin nhận hàng</h2>

            <form action="<?= BASE_URL ?>?act=create-order" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Họ tên</label>
                    <input type="text" name="fullname" value="<?= htmlspecialchars($userInfo['full_name'] ?? $currentUser['fullname']) ?>" disabled class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng</label>
                    <textarea name="shipping_address" required class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2" rows="3"><?= htmlspecialchars($userInfo['address'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                    <input type="text" name="phone" required value="<?= htmlspecialchars($userInfo['phone'] ?? '') ?>" class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ghi chú</label>
                    <textarea name="note" class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2" rows="3"></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <a href="<?= BASE_URL ?>?act=cart" class="px-5 py-2 border border-gray-300 rounded-lg text-sm">Quay lại giỏ hàng</a>
                    <button type="submit" class="px-5 py-2 bg-[#4CAF50] text-white rounded-lg text-sm">Xác nhận đặt hàng</button>
                </div>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h2 class="font-semibold mb-4">Tóm tắt đơn hàng</h2>
            <?php foreach ($items as $item): ?>
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="font-medium text-gray-800"><?= htmlspecialchars($item['title']) ?></div>
                        <div class="text-sm text-gray-500">x <?= $item['quantity'] ?></div>
                    </div>
                    <div class="font-semibold text-gray-800"><?= number_format($item['subtotal'], 0, ',', '.') ?>₫</div>
                </div>
            <?php endforeach; ?>

            <div class="border-t border-gray-200 pt-4 mt-4">
                <div class="flex justify-between text-gray-600">Thành tiền:</div>
                <div class="text-xl font-bold text-[#4CAF50]"><?= number_format($total, 0, ',', '.') ?>₫</div>
            </div>
        </div>
    </div>
</div>

<?php require_once './views/components/customer_footer.php'; ?>