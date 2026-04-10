<?php 
include_once './views/components/header.php';
include_once './views/components/sidebar.php';

$isEdit = isset($voucher) && $voucher;
$formTitle = $isEdit ? 'Sửa Voucher' : 'Thêm Voucher';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
        <!-- Header -->
        <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <a href="<?= BASE_URL ?>?act=admin-vouchers" class="hover:text-[#4CAF50] transition-colors">Quản lý voucher</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="font-medium text-gray-800"><?= $formTitle ?></span>
        </div>

        <div class="mb-8 flex items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900"><?= $formTitle ?></h2>
                <p class="text-gray-500 text-sm mt-1">Tùy chỉnh thông tin mã giảm giá</p>
            </div>
        </div>
        
        <!-- Notification -->
        <?php if ($error = Message::get('error')): ?>
            <div class="mb-4 p-4 text-red-700 bg-red-100 rounded-xl border border-red-200">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="<?= BASE_URL ?>?act=admin-voucher-save" method="POST" class="p-6">
                <!-- ID is required for edit -->
                <?php if ($isEdit): ?>
                    <input type="hidden" name="voucher_id" value="<?= $voucher['voucher_id'] ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mã Voucher <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="<?= htmlspecialchars($voucher['code'] ?? '') ?>" required
                               placeholder="VD: KHUYENMAI20"
                               class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors uppercase">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]">
                            <option value="1" <?= ($voucher['status'] ?? 1) == 1 ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= ($voucher['status'] ?? 1) == 0 ? 'selected' : '' ?>>Ngừng hoạt động</option>
                        </select>
                    </div>

                    <!-- Discount Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loại giảm giá <span class="text-red-500">*</span></label>
                        <select name="discount_type" required class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]">
                            <option value="percent" <?= ($voucher['discount_type'] ?? '') == 'percent' ? 'selected' : '' ?>>Phần trăm (%)</option>
                            <option value="fixed" <?= ($voucher['discount_type'] ?? '') == 'fixed' ? 'selected' : '' ?>>Số tiền (VNĐ)</option>
                        </select>
                    </div>

                    <!-- Discount Value -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giá trị giảm <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="discount_value" value="<?= htmlspecialchars($voucher['discount_value'] ?? '') ?>" required
                               placeholder="VD: 20" min="0"
                               class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]">
                    </div>

                    <!-- Min Order Value -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Đơn hàng tối thiểu (VNĐ) <span class="text-red-500">*</span></label>
                        <input type="number" name="min_order_value" value="<?= htmlspecialchars($voucher['min_order_value'] ?? 0) ?>" required min="0"
                               class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]">
                    </div>

                    <!-- Max Discount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Giảm tối đa (VNĐ) <span class="text-gray-500 text-xs">(Bỏ trống nếu không giới hạn)</span></label>
                        <input type="number" name="max_discount" value="<?= htmlspecialchars($voucher['max_discount'] ?? '') ?>" min="0"
                               class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]">
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian bắt đầu <span class="text-red-500">*</span></label>
                        <?php 
                        $startDate = '';
                        if (!empty($voucher['start_date'])) {
                            $startDate = date('Y-m-d\TH:i', strtotime($voucher['start_date']));
                        }
                        ?>
                        <input type="datetime-local" name="start_date" value="<?= $startDate ?>" required
                               class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]">
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian kết thúc <span class="text-gray-500 text-xs">(Bỏ trống nếu không hết hạn)</span></label>
                        <?php 
                        $endDate = '';
                        if (!empty($voucher['end_date'])) {
                            $endDate = date('Y-m-d\TH:i', strtotime($voucher['end_date']));
                        }
                        ?>
                        <input type="datetime-local" name="end_date" value="<?= $endDate ?>"
                               class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50]">
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <a href="<?= BASE_URL ?>?act=admin-vouchers" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                        Hủy
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#1B2537] text-white rounded-xl hover:bg-gray-800 transition-colors font-medium flex items-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// Prevent minus inputs (already added min="0" but JS can help)
document.querySelectorAll('input[type="number"]').forEach(function(input) {
    input.addEventListener('input', function() {
        if (this.value < 0) this.value = 0;
    });
});
</script>

<?php include_once './views/components/footer.php'; ?>
