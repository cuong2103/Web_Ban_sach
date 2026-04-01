<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<?php
$old    = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
    <div class="w-full mx-auto">
        
        <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <a href="<?= BASE_URL ?>?act=admin-users" class="hover:text-indigo-600">Quản lý tài khoản</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="font-medium text-gray-800">Thêm mới</span>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Thêm người dùng mới</h2>
            <p class="text-gray-500 text-sm mt-1">Khởi tạo nhanh tài khoản cho Admin hoặc Khách</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="<?= BASE_URL ?>?act=admin-users-store" method="POST" enctype="multipart/form-data" id="createUserForm">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Họ và tên -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Họ và tên <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            name="fullname"
                            id="field-fullname"
                            value="<?= htmlspecialchars($old['fullname'] ?? '') ?>"
                            class="w-full px-4 py-2.5 bg-gray-50 border <?= !empty($errors['fullname']) ? 'border-red-400' : 'border-gray-200' ?> rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm"
                            placeholder="Nguyễn Văn A">
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
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                            class="w-full px-4 py-2.5 bg-gray-50 border <?= !empty($errors['email']) ? 'border-red-400' : 'border-gray-200' ?> rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm"
                            placeholder="email@example.com">
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
                            value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                            class="w-full px-4 py-2.5 bg-gray-50 border <?= !empty($errors['phone']) ? 'border-red-400' : 'border-gray-200' ?> rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm"
                            placeholder="09xxxx">
                        <?php if (!empty($errors['phone'])): ?>
                            <p class="field-error mt-1 text-xs text-red-500 flex items-center gap-1">
                                <i data-lucide="circle-alert" class="w-3 h-3"></i> <?= htmlspecialchars($errors['phone']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Mật khẩu -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu khởi tạo <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="field-password"
                                class="w-full px-4 py-2.5 pr-10 bg-gray-50 border <?= !empty($errors['password']) ? 'border-red-400' : 'border-gray-200' ?> rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm"
                                minlength="8"
                                placeholder="Mật khẩu ít nhất 8 ký tự">
                            <button type="button" onclick="togglePass('field-password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <?php if (!empty($errors['password'])): ?>
                            <p class="field-error mt-1 text-xs text-red-500 flex items-center gap-1">
                                <i data-lucide="circle-alert" class="w-3 h-3"></i> <?= htmlspecialchars($errors['password']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Nhập lại mật khẩu -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nhập lại mật khẩu <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password_confirm"
                                id="field-password-confirm"
                                class="w-full px-4 py-2.5 pr-10 bg-gray-50 border <?= !empty($errors['password_confirm']) ? 'border-red-400' : 'border-gray-200' ?> rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm"
                                minlength="8"
                                placeholder="Nhập lại mật khẩu">
                            <button type="button" onclick="togglePass('field-password-confirm', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <?php if (!empty($errors['password_confirm'])): ?>
                            <p class="field-error mt-1 text-xs text-red-500 flex items-center gap-1">
                                <i data-lucide="circle-alert" class="w-3 h-3"></i> <?= htmlspecialchars($errors['password_confirm']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vai trò</label>
                        <select name="roles" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm">
                            <option value="customer" <?= ($old['roles'] ?? '') == 'customer' ? 'selected' : '' ?>>Khách hàng</option>
                            <option value="admin" <?= ($old['roles'] ?? '') == 'admin' ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                        </select>
                    </div>
                </div>

                                    

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($old['address'] ?? '') ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm" placeholder="Địa chỉ giao hàng...">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh đại diện</label>
                        <input type="file" name="avatar" accept="image/jpeg, image/png, image/gif" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-colors cursor-pointer border border-gray-200 rounded-xl bg-gray-50 focus:outline-none shadow-sm">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Trạng thái tài khoản</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm">
                            <option value="1" <?= ($old['status'] ?? 1) == 1 ? 'selected' : '' ?>>Kích hoạt (Hoạt động)</option>
                            <option value="0" <?= ($old['status'] ?? -1) == 0 ? 'selected' : '' ?>>Khóa (Vô hiệu hóa)</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="<?= BASE_URL ?>?act=admin-users" class="px-6 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-xl transition-colors">
                        Hủy
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-xl transition-colors shadow-sm">
                        Tạo tài khoản
                    </button>
                </div>
            </form>
        </div>

    </div>
</main>

<script>
    lucide.createIcons();
    <?php unset($_SESSION['old']); ?>

    // Toggle hiện/ẩn mật khẩu
    function togglePass(fieldId, btn) {
        var input = document.getElementById(fieldId);
        var isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        // Đổi icon
        btn.innerHTML = isPassword
            ? '<i data-lucide="eye-off" class="w-4 h-4"></i>'
            : '<i data-lucide="eye" class="w-4 h-4"></i>';
        lucide.createIcons();
    }

    // Validate nhập lại mật khẩu khớp khi submit
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
        var pw  = document.getElementById('field-password').value;
        var pw2 = document.getElementById('field-password-confirm').value;
        if (pw !== pw2) {
            e.preventDefault();
            var confirmEl = document.getElementById('field-password-confirm');
            confirmEl.classList.add('border-red-400');
            confirmEl.classList.remove('border-gray-200');
            var errEl = confirmEl.closest('.relative').parentElement.querySelector('.field-error');
            if (!errEl) {
                var p = document.createElement('p');
                p.className = 'field-error mt-1 text-xs text-red-500 flex items-center gap-1';
                p.textContent = 'Mật khẩu nhập lại không khớp.';
                confirmEl.closest('.relative').insertAdjacentElement('afterend', p);
                lucide.createIcons();
            }
        }
    });

    // Xóa lỗi khi người dùng bắt đầu nhập
    document.querySelectorAll('#createUserForm input, #createUserForm select').forEach(function(input) {
        input.addEventListener('input', function() {
            var parent = this.closest('.relative') ? this.closest('.relative').parentElement : this.parentElement;
            var errorEl = parent.querySelector('.field-error');
            if (errorEl) errorEl.remove();
            this.classList.remove('border-red-400');
            this.classList.add('border-gray-200');
        });
    });
</script>

<?php require_once './views/components/footer.php'; ?>
