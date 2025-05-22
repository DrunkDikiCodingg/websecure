<?php require base_path('views/partials/header.php') ?>

<div class="flex items-center justify-center min-h-[calc(100vh-160px)]">
    <div class="w-full max-w-sm mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">WebSecure</h1>
        <div class="bg-white border border-gray-200 rounded-lg shadow-xl sm:max-w-md w-full">
            <div class="p-6 space-y-4 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl text-center">
                    Create your account
                </h1>
                <form method="POST" class="space-y-4">
                    <!-- General error message -->
                    <?php if (!empty($errors['form'])): ?>
                        <p class="text-red-500 text-sm"><?php echo htmlspecialchars($errors['form']); ?></p>
                    <?php endif; ?>
    
                    <!-- Username -->
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                        <input type="text" name="username" id="username"
                            value="<?= htmlspecialchars($old['username'] ?? '') ?>"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 outline-none <?= isset($errors['username']) ? 'border-red-500' : '' ?>"
                            placeholder="Your username">
                        <?php if (isset($errors['username'])): ?>
                            <p class="text-red-500 text-sm"><?= htmlspecialchars($errors['username']) ?></p>
                        <?php endif; ?>
                    </div>
    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" name="email" id="email"
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 outline-none <?= isset($errors['email']) ? 'border-red-500' : '' ?>"
                            placeholder="name@company.com">
                        <?php if (isset($errors['email'])): ?>
                            <p class="text-red-500 text-sm"><?= htmlspecialchars($errors['email']) ?></p>
                        <?php endif; ?>
                    </div>
    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                        <input type="password" name="password" id="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 outline-none <?= isset($errors['password']) ? 'border-red-500' : '' ?>"
                            placeholder="••••••••">
                        <?php if (isset($errors['password'])): ?>
                            <p class="text-red-500 text-sm"><?= htmlspecialchars($errors['password']) ?></p>
                        <?php endif; ?>
                    </div>
    
                    <!-- Submit -->
                    <button type="submit"
                            class="w-full bg-blue-600 text-white font-medium py-2.5 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        Register
                    </button>
                </form>
    
            </div>
        </div>
    </div>
</div>

<?php require base_path('views/partials/footer.php') ?>
