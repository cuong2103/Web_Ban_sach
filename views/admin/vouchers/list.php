<?php 
include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý Voucher</h1>
                <p class="text-sm text-gray-500 mt-1">Danh sách mã giảm giá của hệ thống</p>
            </div>
            <a href="<?= BASE_URL ?>?act=admin-voucher-add" class="px-4 py-2 bg-[#4CAF50] text-white rounded-xl hover:bg-[#43A047] transition-colors font-medium flex items-center gap-2">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Thêm Voucher
            </a>
        </div>

        <!-- Notification -->
        <?php if ($success = Message::get('success')): ?>
            <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-xl border border-green-200">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        <?php if ($error = Message::get('error')): ?>
            <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-xl border border-red-200">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã Voucher</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Loại & Giá trị</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Đơn tối thiểu</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thời gian</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($vouchers)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Không có voucher nào.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($vouchers as $v): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    <?= htmlspecialchars($v['code']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?php if ($v['discount_type'] === 'percent'): ?>
                                        <span class="text-[#4CAF50] font-medium"><?= (float)$v['discount_value'] ?>%</span>
                                        <?php if ($v['max_discount']): ?>
                                            <div class="text-xs text-gray-500">(Tối đa <?= number_format($v['max_discount'], 0, ',', '.') ?>đ)</div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-[#4CAF50] font-medium"><?= number_format($v['discount_value'], 0, ',', '.') ?>đ</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    Từ <?= number_format($v['min_order_value'], 0, ',', '.') ?>đ
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="mb-1"><span class="text-xs text-gray-400">Từ:</span> <?= date('d/m/Y H:i', strtotime($v['start_date'])) ?></div>
                                    <?php if ($v['end_date']): ?>
                                        <div><span class="text-xs text-gray-400">Đến:</span> <?= date('d/m/Y H:i', strtotime($v['end_date'])) ?></div>
                                    <?php else: ?>
                                        <div><span class="text-xs text-gray-400">Đến:</span> Không giới hạn</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if((int)$v['status'] === 1): ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Ngừng HĐ</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= BASE_URL ?>?act=admin-voucher-edit&id=<?= $v['voucher_id'] ?>" 
                                            class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-xs font-medium flex items-center gap-1.5">
                                            <i data-lucide="edit" class="w-3.5 h-3.5"></i> Sửa
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=admin-voucher-delete&id=<?= $v['voucher_id'] ?>" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này?')"
                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium flex items-center gap-1.5">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Xóa
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php include_once './views/components/footer.php'; ?>
