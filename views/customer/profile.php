<?php
require_once './views/components/navbar.php';

$error = Message::get('error');
$success = Message::get('success');
?>

<div class="bg-[#F8F9FA] min-h-screen py-10 font-sans">
    <div class="w-full xl:px-12 mx-auto px-4 main-container">
        
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <a href="<?= BASE_URL ?>?act=home" class="hover:text-[#4CAF50] transition-colors"><i data-lucide="home" class="w-4 h-4"></i></a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="text-gray-800 font-medium">Tài khoản của tôi</span>
        </div>

        <?php if ($error): ?>
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700 text-sm flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i> <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-[#4CAF50] to-[#2E7D32] flex items-center justify-center text-white font-bold text-xl uppercase shadow-md overflow-hidden relative group">
                            <?php if (!empty($user['avatar'])): ?>
                                <img id="sidebar-avatar" src="<?= BASE_URL . ltrim($user['avatar'], '/') ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span id="sidebar-initial"><?= mb_substr($user['fullname'], 0, 1) ?></span>
                                <img id="sidebar-avatar" src="" class="w-full h-full object-cover hidden">
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 mb-0.5">Tài khoản của</p>
                            <p class="text-sm font-bold text-[#333] line-clamp-1"><?= htmlspecialchars($user['fullname']) ?></p>
                        </div>
                        <button type="button" onclick="document.getElementById('avatar-input').click()" class="shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 hover:bg-green-50 hover:text-[#4CAF50] transition-colors text-xs font-semibold" title="Đổi ảnh đại diện">
                            <i data-lucide="camera" class="w-3.5 h-3.5"></i> Đổi ảnh
                        </button>
                    </div>

                    <div class="h-px w-full bg-gray-100"></div>

                    <nav class="flex flex-col gap-2">
                        <a href="<?= BASE_URL ?>?act=profile" class="flex items-center gap-3 px-4 py-3 bg-green-50 text-[#4CAF50] font-semibold rounded-2xl transition-all border border-green-100">
                            <i data-lucide="user-cog" class="w-5 h-5"></i> Thông tin tài khoản
                        </a>
                        <a href="<?= BASE_URL ?>?act=orders" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-[#4CAF50] font-medium rounded-2xl transition-all border border-transparent">
                            <i data-lucide="receipt" class="w-5 h-5"></i> Đơn hàng của tôi
                        </a>
                        <a href="<?= BASE_URL ?>?act=logout" class="flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 hover:text-red-600 font-medium rounded-2xl transition-all mt-4 border border-red-100">
                            <i data-lucide="log-out" class="w-5 h-5"></i> Đăng xuất
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 grid grid-cols-1 xl:grid-cols-2 gap-8 items-start">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 h-fit">
                    <h2 class="text-xl font-extrabold text-[#333] flex items-center gap-2 border-b border-gray-100 pb-4 mb-6">
                        <i data-lucide="user-circle" class="w-6 h-6 text-[#4CAF50]"></i> Hồ sơ cá nhân
                    </h2>
                    
                    <form action="<?= BASE_URL ?>?act=profile-update" method="POST" enctype="multipart/form-data" class="max-w-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Họ và tên *</label>
                                <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#4CAF50] focus:ring-2 focus:ring-[#4CAF50]/20 outline-none transition-all text-sm text-[#333]" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Số điện thoại *</label>
                                <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#4CAF50] focus:ring-2 focus:ring-[#4CAF50]/20 outline-none transition-all text-sm text-[#333]" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email (Không thể thay đổi)</label>
                            <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl outline-none text-sm text-gray-500 cursor-not-allowed">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ giao hàng mặc định</label>
                            <input type="text" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#4CAF50] focus:ring-2 focus:ring-[#4CAF50]/20 outline-none transition-all text-sm text-[#333]" placeholder="Số nhà, Tên đường, Phường/Xã, Quận/Huyện, Tỉnh/Thành phố">
                        </div>

                        <!-- Hidden file input linked to sidebar button -->
                        <input type="file" name="avatar" id="avatar-input" accept="image/jpeg, image/png, image/gif" class="hidden">

                        <button type="submit" class="bg-[#4CAF50] text-white px-8 py-3.5 rounded-xl font-bold hover:bg-[#43A047] transition-all hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2">
                            <i data-lucide="save" class="w-5 h-5"></i> Lưu thay đổi
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 h-fit">
                    <h2 class="text-xl font-extrabold text-[#333] flex items-center gap-2 border-b border-gray-100 pb-4 mb-6">
                        <i data-lucide="shield" class="w-6 h-6 text-[#FFC107]"></i> Đổi mật khẩu
                    </h2>
                    
                    <form action="<?= BASE_URL ?>?act=profile-password" method="POST" class="max-w-2xl">
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu hiện tại *</label>
                            <input type="password" name="old_password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none transition-all text-sm text-[#333]" required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu mới *</label>
                                <input type="password" name="new_password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none transition-all text-sm text-[#333]" placeholder="Ít nhất 8 ký tự" required minlength="8">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Xác nhận mật khẩu mới *</label>
                                <input type="password" name="confirm_password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-[#FFC107] focus:ring-2 focus:ring-[#FFC107]/20 outline-none transition-all text-sm text-[#333]" required minlength="8">
                            </div>
                        </div>

                        <button type="submit" class="bg-gray-100 text-gray-700 px-8 py-3.5 rounded-xl font-bold hover:bg-[#FFC107] hover:text-[#333] transition-all flex items-center gap-2 border border-gray-200 hover:border-transparent">
                            <i data-lucide="key-round" class="w-5 h-5"></i> Cập nhật mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    lucide.createIcons();

    document.getElementById('avatar-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('sidebar-avatar');
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                const initial = document.getElementById('sidebar-initial');
                if (initial) initial.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-red-50, .bg-green-50');
        alerts.forEach(alert => {
            if (alert.closest('div').classList.contains('main-container')) { // Only main alerts
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
</script>

<?php require_once './views/components/customer_footer.php'; ?>
