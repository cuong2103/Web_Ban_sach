<?php

$flashMessage = Message::get('success');
$errorMsg = Message::get('error');
deleteSessionError();

include_once './views/components/header.php';
include_once './views/components/sidebar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full">

        <?php if ($flashMessage): ?>
            <div id="flash-success"
                class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-2 transition-opacity duration-500"
                role="alert">
                <i data-lucide="check" class="w-4 h-4 shrink-0"></i>
                <span><?= htmlspecialchars($flashMessage) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
            <div id="flash-error"
                class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative flex items-center gap-2 transition-opacity duration-500"
                role="alert">
                <i data-lucide="triangle-alert" class="w-4 h-4 shrink-0"></i>
                <span><?= htmlspecialchars($errorMsg) ?></span>
            </div>
        <?php endif; ?>
        <script>
            ['flash-success', 'flash-error'].forEach(function (id) {
                var el = document.getElementById(id);
                if (!el) return;
                setTimeout(function () {
                    el.style.opacity = '0';
                    setTimeout(function () { el.remove(); }, 500);
                }, 3000);
            });
        </script>

        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý Danh mục</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Tổng cộng <span class="font-semibold text-gray-700">
                        <?= number_format($total) ?>
                    </span> danh mục trong hệ thống
                </p>
            </div>
            <a href="<?= BASE_URL ?>?act=admin-categories-create"
                class="px-5 py-2.5 bg-[#4CAF50] text-white rounded-xl hover:bg-green-600 transition-colors font-medium flex items-center gap-2 shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Thêm danh mục
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <!-- Thanh tìm kiếm & lọc -->
            <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                <form action="" method="GET" class="flex flex-wrap gap-3 items-center w-full">
                    <input type="hidden" name="act" value="admin-categories">

                    <!-- Tìm kiếm -->
                    <div class="relative flex-1 min-w-[200px]">
                        <i data-lucide="search"
                            class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                            placeholder="Tìm theo tên danh mục..."
                            class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4CAF50]/20 focus:border-[#4CAF50] transition-colors text-sm">
                    </div>

                    <button type="submit"
                        class="px-5 py-2 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition-colors font-medium text-sm whitespace-nowrap">
                        Lọc
                    </button>

                    <?php if (!empty($_GET['search'])): ?>
                        <a href="<?= BASE_URL ?>?act=admin-categories"
                            class="px-4 py-2 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl transition-colors font-medium flex items-center gap-1.5 text-sm whitespace-nowrap">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i> Xóa lọc
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <i data-lucide="inbox" class="w-10 h-10 text-gray-300"></i>
                                        <p class="text-sm">Không tìm thấy danh mục nào.</p>
                                        <a href="<?= BASE_URL ?>?act=admin-categories-create"
                                            class="text-sm text-[#4CAF50] hover:underline font-medium">+ Thêm danh mục mới</a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $category): ?>
                                <tr class="hover:bg-gray-50 transition-colors">

                                    <!-- Tên danh mục -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </div>
                                    </td>

                                    <!-- Slug -->
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <code class="bg-gray-100 px-2 py-1 rounded text-xs">
                                            <?= htmlspecialchars($category['slug']) ?>
                                        </code>
                                    </td>

                                    <!-- Trạng thái -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold
                                          <?= $category['status'] == 1 ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-600 border border-gray-200' ?>">
                                            <i data-lucide="<?= $category['status'] == 1 ? 'check-circle' : 'circle' ?>"
                                                class="w-3 h-3"></i>
                                            <?= $category['status'] == 1 ? 'Hiển thị' : 'Đang ẩn' ?>
                                        </span>
                                    </td>

                                    <!-- Ngày tạo -->
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?= date('d/m/Y H:i', strtotime($category['created_at'])) ?>
                                    </td>

                                    <!-- Thao tác -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="<?= BASE_URL ?>?act=admin-categories-edit&id=<?= $category['id'] ?>"
                                                class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium flex items-center gap-1.5 whitespace-nowrap">
                                                <i data-lucide="edit" class="w-3.5 h-3.5"></i> Sửa
                                            </a>

                                            <a href="<?= BASE_URL ?>?act=admin-categories-delete&id=<?= $category['id'] ?>"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');"
                                                class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium flex items-center gap-1.5 whitespace-nowrap">
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

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="px-6 py-4 border-t border-gray-100 bg-white flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Trang <span class="font-semibold text-gray-900"><?= $page ?></span> / <span class="font-semibold text-gray-900"><?= $totalPages ?></span>
                    </p>
                    <div class="flex gap-2">
                        <?php if ($page > 1): ?>
                            <a href="<?= BASE_URL ?>?act=admin-categories&page=<?= $page - 1 ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
                                class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors text-sm font-medium">
                                ← Trước
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="px-3 py-1.5 bg-[#4CAF50] text-white rounded-lg text-sm font-medium">
                                    <?= $i ?>
                                </span>
                            <?php elseif ($i <= 3 || $i > $totalPages - 3 || abs($i - $page) <= 1): ?>
                                <a href="<?= BASE_URL ?>?act=admin-categories&page=<?= $i ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
                                    class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors text-sm font-medium">
                                    <?= $i ?>
                                </a>
                            <?php elseif ($i == 4 || ($i == $totalPages - 3 && $i > 4)): ?>
                                <span class="text-gray-400">...</span>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="<?= BASE_URL ?>?act=admin-categories&page=<?= $page + 1 ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
                                class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors text-sm font-medium">
                                Sau →
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
    lucide.createIcons();
</script>
