<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<?php
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
$success = Message::get('success');
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full mx-auto">
        
        <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <a href="<?= BASE_URL ?>?act=admin-users" class="hover:text-indigo-600">Quản lý tài khoản</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="font-medium text-gray-800">Chỉnh sửa: <?= htmlspecialchars($user['fullname']) ?></span>
        </div>

        <div class="mb-8 flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-2xl overflow-hidden shadow-sm shrink-0 border border-indigo-200">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= BASE_URL . ltrim($user['avatar'], '/') ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <?= mb_substr($user['fullname'], 0, 1) ?>
                <?php endif; ?>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Cập nhật hồ sơ</h2>
                <p class="text-gray-500 text-sm mt-1">ID: #<?= $user['id'] ?> | Tham gia: <?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert-box mb-6 bg-green-50 text-green-700 p-4 rounded-xl flex items-center border border-green-100">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="<?= BASE_URL ?>?act=admin-users-update" method="POST" enctype="multipart/form-data" id="editUserForm">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Họ và tên -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Họ và tên <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            name="fullname"
                            id="field-fullname"
                            value="<?= htmlspecialchars($user['fullname']) ?>"
                            class="w-full px-4 py-2.5 bg-gray-50 border <?= !empty($errors['fullname']) ? 'border-red-400' : 'border-gray-200' ?> rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm">
                        <?php if (!empty($errors['fullname'])): ?>
                            <p class="field-error mt-1 text-xs text-red-500 flex items-center gap-1">
                                <i data-lucide="circle-alert" class="w-3 h-3"></i> <?= htmlspecialchars($errors['fullname']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input
                            type="email"
                            name="email"
                            id="field-email"
                            value="<?= htmlspecialchars($user['email']) ?>"
                            class="w-full px-4 py-2.5 bg-gray-50 border <?= !empty($errors['email']) ? 'border-red-400' : 'border-gray-200' ?> rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm">
                        <?php if (!empty($errors['email'])): ?>
                            <p class="field-error mt-1 text-xs text-red-500 flex items-center gap-1">
                                <i data-lucide="circle-alert" class="w-3 h-3"></i> <?= htmlspecialchars($errors['email']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Số điện thoại -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại</label>
                        <input
                            type="tel"
                            name="phone"
                            id="field-phone"
                            value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                            class="w-full px-4 py-2.5 bg-gray-50 border <?= !empty($errors['phone']) ? 'border-red-400' : 'border-gray-200' ?> rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm"
                            placeholder="10 chữ số">
                        <?php if (!empty($errors['phone'])): ?>
                            <p class="field-error mt-1 text-xs text-red-500 flex items-center gap-1">
                                <i data-lucide="circle-alert" class="w-3 h-3"></i> <?= htmlspecialchars($errors['phone']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Tải ảnh đại diện mới -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tải ảnh đại diện mới</label>
                        <input type="file" name="avatar" accept="image/jpeg, image/png, image/gif" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-colors cursor-pointer border border-gray-200 rounded-xl bg-gray-50 focus:outline-none shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm" placeholder="Nhập địa chỉ của khách hàng...">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vai trò</label>
                        <select name="roles" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm">
                            <option value="customer" <?= (int)$user['roles'] !== 1 ? 'selected' : '' ?>>Khách hàng</option>
                            <option value="admin" <?= (int)$user['roles'] === 1 ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Trạng thái</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm">
                            <option value="1" <?= $user['status'] == 1 ? 'selected' : '' ?>>Kích hoạt (Hoạt động)</option>
                            <option value="0" <?= $user['status'] == 0 ? 'selected' : '' ?>>Khóa (Vô hiệu hóa)</option>
                        </select>
                    </div>
                </div>

                <!-- Đặt lại mật khẩu -->
                <div class="p-5 bg-yellow-50 rounded-xl border border-yellow-100 mb-8">
                    <p class="text-sm font-semibold text-yellow-800 mb-4">Đặt lại mật khẩu <span class="font-normal text-yellow-600">(Tùy chọn — bỏ trống nếu không muốn đổi)</span></p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-yellow-800 mb-1">Mật khẩu mới</label>
                            <div class="relative">
                                <input
                                    type="password"
                                    name="password"
                                    id="field-password"
                                    class="w-full px-4 py-2.5 pr-10 border <?= !empty($errors['password']) ? 'border-red-400' : 'border-yellow-200' ?> rounded-lg focus:ring-2 focus:ring-yellow-500/20 focus:border-yellow-500 outline-none transition-all text-sm bg-white"
                                    placeholder="Tối thiểu 8 ký tự">
                                <button type="button" onclick="togglePass('field-password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-yellow-400 hover:text-yellow-600">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <?php if (!empty($errors['password'])): ?>
                                <p class="field-error mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <i data-lucide="circle-alert" class="w-3 h-3"></i> <?= htmlspecialchars($errors['password']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-yellow-800 mb-1">Nhập lại mật khẩu</label>
                            <div class="relative">
                                <input
                                    type="password"
                                    name="password_confirm"
                                    id="field-password-confirm"
                                    class="w-full px-4 py-2.5 pr-10 border <?= !empty($errors['password_confirm']) ? 'border-red-400' : 'border-yellow-200' ?> rounded-lg focus:ring-2 focus:ring-yellow-500/20 focus:border-yellow-500 outline-none transition-all text-sm bg-white"
                                    placeholder="Nhập lại mật khẩu mới">
                                <button type="button" onclick="togglePass('field-password-confirm', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-yellow-400 hover:text-yellow-600">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <?php if (!empty($errors['password_confirm'])): ?>
                                <p class="field-error mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <i data-lucide="circle-alert" class="w-3 h-3"></i> <?= htmlspecialchars($errors['password_confirm']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="<?= BASE_URL ?>?act=admin-users" class="px-6 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-xl transition-colors">
                        Trở lại
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-xl transition-colors shadow-sm">
                        Lưu cấu hình
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<script>
    lucide.createIcons();

    // Toggle hiện/ẩn mật khẩu
    function togglePass(fieldId, btn) {
        var input = document.getElementById(fieldId);
        var isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        btn.innerHTML = isPassword
            ? '<i data-lucide="eye-off" class="w-4 h-4"></i>'
            : '<i data-lucide="eye" class="w-4 h-4"></i>';
        lucide.createIcons();
    }

    // Auto remove success alert
    setTimeout(function() {
        var arr = document.querySelectorAll('.alert-box');
        arr.forEach(function(a) {
            a.style.transition = 'opacity 0.5s';
            a.style.opacity = '0';
            setTimeout(function() { a.remove(); }, 500);
        });
    }, 4000);

    // Validate mật khẩu khớp khi submit
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        var pw  = document.getElementById('field-password').value;
        var pw2 = document.getElementById('field-password-confirm').value;
        if (pw !== '' && pw !== pw2) {
            e.preventDefault();
            var confirmEl = document.getElementById('field-password-confirm');
            confirmEl.classList.add('border-red-400');
            confirmEl.classList.remove('border-yellow-200');
            var parent = confirmEl.closest('.relative').parentElement;
            var errEl = parent.querySelector('.field-error');
            if (!errEl) {
                var p = document.createElement('p');
                p.className = 'field-error mt-1 text-xs text-red-500 flex items-center gap-1';
                p.textContent = 'Mật khẩu nhập lại không khớp.';
                confirmEl.closest('.relative').insertAdjacentElement('afterend', p);
                lucide.createIcons();
            }
        }
    });

    // Xóa lỗi inline khi người dùng bắt đầu nhập
    document.querySelectorAll('#editUserForm input, #editUserForm select').forEach(function(input) {
        input.addEventListener('input', function() {
            var parent = this.closest('.relative') ? this.closest('.relative').parentElement : this.parentElement;
            var errorEl = parent.querySelector('.field-error');
            if (errorEl) errorEl.remove();
            this.classList.remove('border-red-400');
            this.classList.add(this.name === 'password' || this.name === 'password_confirm' ? 'border-yellow-200' : 'border-gray-200');
        });
    });
</script>

<?php require_once './views/components/footer.php'; ?>

