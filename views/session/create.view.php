<?php require base_path('views/partials/header.php');

$usernameOrEmail = $_GET['username'] ?? old('usernameOrEmail') ?? '';

?>

<div class="flex items-center justify-center min-h-[calc(100vh-160px)]">
    <div class="w-full max-w-sm">
        <h1 class="text-3xl font-bold mb-6 text-center">WebSecure</h1>
        <div class="bg-white border border-gray-200 rounded-lg shadow-xl sm:max-w-md w-full xl:p-0">
            <div class="p-6 space-y-4 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Sign in to your account
                </h1>
                <form class="space-y-4" action="/session" method="POST">
                    <!-- General Form Error -->
                    <?php if (isset($errors['form'])): ?>
                        <p class="text-red-500 text-sm text-center">
                            <?php echo htmlspecialchars($errors['form']); ?>
                        </p>
                    <?php endif; ?>
                    <!-- Username/Email Field -->
                    <div>
                        <label for="usernameOrEmail" class="block mb-2 text-sm font-medium text-gray-900">Your email/username</label>
                        <input id="usernameOrEmail"
                               type="text"
                               name="usernameOrEmail"
                               class="bg-gray-50 block border <?php echo !empty($errors['usernameOrEmail']) ? 'border-red-500' : 'border-gray-300'; ?> focus:border-blue-500 focus:ring-blue-500 outline-none p-2.5 rounded-lg text-gray-900 w-full"
                               placeholder="name@company.com"
                               value="<?= $usernameOrEmail ?>">
                        <?php if (!empty($errors['usernameOrEmail'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo htmlspecialchars($errors['usernameOrEmail']); ?></p>
                        <?php endif; ?>
                    </div>
    
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                        <input id="password"
                               type="password"
                               name="password"
                               placeholder="••••••••"
                               class="bg-gray-50 block border <?php echo !empty($errors['password']) ? 'border-red-500' : 'border-gray-300'; ?> focus:border-blue-500 focus:ring-blue-500 outline-none p-2.5 rounded-lg text-gray-900 w-full">
                        <?php if (!empty($errors['password'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?php echo htmlspecialchars($errors['password']); ?></p>
                        <?php endif; ?>
                    </div>
    
                    <!-- Submit Button -->
                    <button type="submit"
                            class="bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium hover:bg-blue-700 px-5 py-2.5 rounded-lg text-center text-sm text-white">
                        Sign in
                    </button>
    
                    <!-- Signup Link -->
                    <p class="text-sm font-light text-gray-500">
                        Don't have an account yet? <a href="/register" class="font-medium text-blue-600 hover:underline">Sign up</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require base_path('views/partials/footer.php') ?>
