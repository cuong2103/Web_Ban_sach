<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<?php $success = Message::get('success'); ?>
<?php $error = Message::get('error'); ?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full mx-auto">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Quản lý Tài khoản</h2>
                <p class="text-sm text-gray-500 mt-1">Quản lý khách hàng và phân quyền quản trị viên</p>
            </div>
            <a href="<?= BASE_URL ?>?act=admin-users-create" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Thêm người dùng
            </a>
        </div>

        <?php if ($success): ?>
            <div class="alert-box mb-4 bg-green-50 text-green-700 p-4 rounded-lg flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert-box mb-4 bg-red-50 text-red-700 p-4 rounded-lg flex items-center">
                <i data-lucide="alert-circle" class="w-5 h-5 mr-2"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-gray-500 text-sm uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">Người dùng</th>
                            <th class="px-6 py-4">Vai trò</th>
                            <th class="px-6 py-4">Email / SĐT</th>
                            <th class="px-6 py-4">Trạng thái</th>
                            <th class="px-6 py-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold overflow-hidden shadow-sm shrink-0">
                                        <?php if (!empty($user['avatar'])): ?>
                                            <img src="<?= BASE_URL . ltrim($user['avatar'], '/') ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <?= mb_substr($user['fullname'], 0, 1) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($user['fullname']) ?></div>
                                        <div class="text-xs text-gray-500">Tham gia: <?= date('d/m/Y', strtotime($user['created_at'])) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ((int)$user['roles'] === 1): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 border border-purple-200"><i data-lucide="shield" class="w-3 h-3"></i> Admin</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100"><i data-lucide="user" class="w-3 h-3"></i> Khách</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="text-gray-900 font-medium"><?= htmlspecialchars($user['email']) ?></div>
                                <div class="text-gray-500"><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($user['status'] == 1): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hoạt động
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Khóa
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= BASE_URL ?>?act=admin-users-edit&id=<?= $user['id'] ?>" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors tooltip" title="Chỉnh sửa">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    
                                    <form action="<?= BASE_URL ?>?act=admin-users-toggle-status" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái user này?');">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <?php if ($user['status'] == 1): ?>
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors tooltip" title="Khóa tài khoản">
                                            <i data-lucide="lock" class="w-4 h-4"></i>
                                        </button>
                                        <?php else: ?>
                                        <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors tooltip" title="Mở khóa tài khoản">
                                            <i data-lucide="unlock" class="w-4 h-4"></i>
                                        </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($users)): ?>
                <div class="p-12 text-center text-gray-500">
                    <i data-lucide="users" class="w-12 h-12 mx-auto text-gray-300 mb-3"></i>
                    <p>Hệ thống chưa có tài khoản nào hợp lệ.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
    lucide.createIcons();
    
    // Auto remove alert
    setTimeout(function() {
        const arr = document.querySelectorAll('.alert-box');
        arr.forEach(a => {
            a.style.transition = 'opacity 0.5s';
            a.style.opacity = '0';
            setTimeout(() => a.remove(), 500);
        });
    }, 4000);
</script>

<?php require_once './views/components/footer.php'; ?>
