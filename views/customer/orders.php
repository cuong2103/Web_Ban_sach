<?php
require_once './views/components/navbar.php';
?>

<div class="max-w-[1200px] mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Đơn hàng của tôi</h1>

    <?php if (empty($orders)): ?>
        <div class="bg-white border border-gray-200 rounded-xl p-6 text-center">
            <p class="text-gray-600">Bạn chưa có đơn hàng nào.</p>
            <a href="<?= BASE_URL ?>?act=books" class="inline-block mt-4 px-5 py-2 bg-[#4CAF50] text-white rounded-lg">Mua sắm ngay</a>
        </div>
    <?php else: ?>
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-500 text-sm uppercase tracking-wide border-b border-gray-200">
                        <th class="py-3">Mã đơn</th>
                        <th class="py-3">Ngày</th>
                        <th class="py-3">Tổng tiền</th>
                        <th class="py-3">Trạng thái</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 font-semibold"><?= htmlspecialchars($order['order_code']) ?></td>
                            <td class="py-3"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="py-3 font-semibold text-[#4CAF50]"><?= number_format($order['total_amount'], 0, ',', '.') ?>₫</td>
                            <td class="py-3"><?= htmlspecialchars($order['status_name']) ?></td>
                            <td class="py-3"><a href="<?= BASE_URL ?>?act=order-detail&id=<?= $order['order_id'] ?>" class="text-[#4CAF50] hover:text-[#388E3C]">Xem</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once './views/components/customer_footer.php'; ?>