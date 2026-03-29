<?php
require_once './views/components/navbar.php';
?>

<div class="max-w-[1000px] mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Chi tiết đơn hàng</h1>

    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Mã đơn hàng</p>
                <p class="font-bold"><?= htmlspecialchars($order['order_code']) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Trạng thái</p>
                <p class="font-bold"><?= htmlspecialchars($order['status_name']) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Ngày tạo</p>
                <p class="font-bold"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tổng</p>
                <p class="font-bold text-[#4CAF50]"><?= number_format($order['total_amount'], 0, ',', '.') ?>₫</p>
            </div>
        </div>

        <div class="mt-4 border-t border-gray-200 pt-4">
            <p class="text-sm text-gray-500">Địa chỉ giao hàng</p>
            <p><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
            <p class="text-sm text-gray-500 mt-2">Số điện thoại</p>
            <p><?= htmlspecialchars($order['phone']) ?></p>
            <?php if (!empty($order['note'])): ?>
                <p class="text-sm text-gray-500 mt-2">Ghi chú</p>
                <p><?= nl2br(htmlspecialchars($order['note'])) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="font-semibold mb-4">Sản phẩm trong đơn</h2>
        <div class="space-y-3">
            <?php foreach ($items as $item): ?>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold"><?= htmlspecialchars($item['title']) ?></p>
                        <p class="text-sm text-gray-500">Số lượng: <?= $item['quantity'] ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold"><?= number_format($item['subtotal'], 0, ',', '.') ?>₫</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="mt-4 text-right">
        <a href="<?= BASE_URL ?>?act=orders" class="inline-block px-5 py-2 rounded-lg border border-gray-300 text-sm">Quay lại danh sách đơn</a>
    </div>
</div>

<?php require_once './views/components/customer_footer.php'; ?>