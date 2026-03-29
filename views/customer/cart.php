<?php
require_once './views/components/navbar.php';
?>

<div class="max-w-[1200px] mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Giỏ hàng</h1>

    <?php if (empty($items)): ?>
        <div class="bg-white border border-gray-200 rounded-xl p-6 text-center">
            <p class="text-gray-600">Giỏ hàng của bạn đang trống.</p>
            <a href="<?= BASE_URL ?>?act=books" class="inline-block mt-4 px-5 py-2 bg-[#4CAF50] text-white rounded-lg">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-500 text-sm uppercase tracking-wide border-b border-gray-200">
                        <th class="py-3">Sản phẩm</th>
                        <th class="py-3">Giá</th>
                        <th class="py-3">Số lượng</th>
                        <th class="py-3">Tổng</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 flex items-center gap-3">
                                <img src="<?= htmlspecialchars($item['thumbnail']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-14 h-16 object-cover rounded-md">
                                <div>
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($item['title']) ?></div>
                                    <div class="text-xs text-gray-500">Còn lại <?= $item['stock'] ?></div>
                                </div>
                            </td>
                            <td class="py-3 font-semibold"><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
                            <td class="py-3"><?= $item['quantity'] ?></td>
                            <td class="py-3 font-semibold"><?= number_format($item['subtotal'], 0, ',', '.') ?>₫</td>
                            <td class="py-3">
                                <a href="<?= BASE_URL ?>?act=cart-remove&id=<?= $item['book_id'] ?>" class="text-red-500 hover:text-red-700 text-sm">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="mt-6 text-right">
                <span class="text-gray-600">Tổng đơn hàng:</span>
                <span class="text-2xl font-bold text-[#4CAF50]"><?= number_format($total, 0, ',', '.') ?>₫</span>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="<?= BASE_URL ?>?act=books" class="px-5 py-2 border border-gray-300 rounded-lg text-sm">Tiếp tục mua</a>
                <a href="<?= BASE_URL ?>?act=checkout" class="px-5 py-2 bg-[#4CAF50] text-white rounded-lg text-sm">Tiến hành thanh toán</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once './views/components/customer_footer.php'; ?>