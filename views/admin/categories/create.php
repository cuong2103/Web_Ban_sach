<?php

include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">
        <div class="flex items-center gap-4 mb-6">
            <a href="<?= BASE_URL ?>?act=admin-categories" class="p-2 hover:bg-gray-200 rounded-lg transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Thêm danh mục mới</h1>
                <p class="text-sm text-gray-500 mt-1">Điền thông tin để thêm danh mục sách</p>
            </div>
        </div>

        <form action="<?= BASE_URL ?>?act=admin-categories-store" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Cột trái: Thông tin chính -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Thông tin danh mục</h2>

                    <div class="space-y-4">
                        <!-- Tên danh mục -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="<?= htmlspecialchars(old('name') ?? '') ?>"
                                placeholder="Ví dụ: Văn học, Khoa học..."
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors">
                            <?php if (isset($errors['name'])): ?>
                                <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                    <?= $errors['name'][0] ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Slug <span class="text-red-500">*</span></label>
                            <input type="text" name="slug" value="<?= htmlspecialchars(old('slug') ?? '') ?>"
                                placeholder="Ví dụ: van-hoc, khoa-hoc..."
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors"
                                onchange="this.value = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '')">
                            <p class="text-xs text-gray-500 mt-1">URL-friendly name (chỉ chữ thường, số, dấu gạch ngang)</p>
                            <?php if (isset($errors['slug'])): ?>
                                <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                    <?= $errors['slug'][0] ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Mô tả -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                            <textarea name="description" rows="6"
                                placeholder="Mô tả chi tiết về danh mục sách..."
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors resize-none"><?= htmlspecialchars(old('description') ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Trạng thái & Action -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24 space-y-4">
                    <h2 class="text-lg font-bold text-gray-900 border-b pb-2">Cài đặt</h2>

                    <!-- Trạng thái -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors
                                <?= (old('status') ?? 1) == 1 ? 'border-[#4CAF50] bg-green-50' : '' ?>">
                                <input type="radio" name="status" value="1" <?= (old('status') ?? 1) == 1 ? 'checked' : '' ?> class="w-4 h-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Hiển thị</p>
                                    <p class="text-xs text-gray-500">Danh mục sẽ hiển thị trên website</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors
                                <?= (old('status') ?? 1) == 0 ? 'border-gray-400 bg-gray-50' : '' ?>">
                                <input type="radio" name="status" value="0" <?= (old('status') ?? 1) == 0 ? 'checked' : '' ?> class="w-4 h-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Ẩn</p>
                                    <p class="text-xs text-gray-500">Danh mục sẽ bị ẩn trên website</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Button lưu -->
                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit"
                            class="w-full px-6 py-3 bg-[#4CAF50] text-white font-semibold rounded-xl hover:bg-green-600 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Thêm danh mục
                        </button>
                        <a href="<?= BASE_URL ?>?act=admin-categories"
                            class="w-full mt-2 px-6 py-3 border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="x" class="w-4 h-4"></i> Hủy
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    lucide.createIcons();
</script>
