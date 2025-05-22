<?php require base_path('views/partials/header.php') ?>

<div id="verification-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-4 text-center">Enter Verification Code</h2>
        <p class="text-sm text-gray-600 mb-4 text-center">
            A verification code has been sent to your email. Enter it below to continue.
        </p>

        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="text-red-500 text-sm"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
        <?php endif; ?>
        
        <form method="POST" action="/verify" class="space-y-4">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['temp_user']->id ?? ''); ?>">
            <label for="code" class="block text-sm font-medium">Verification Code:</label>
            <!-- TODO: Test code in input, remove at production -->
            <input id="code"
                   type="text"
                   name="code"
                   class="w-full p-2 border rounded"
                   required
                   
            >
            <div class="flex justify-between mt-4">
                <button type="button"
                        onclick="window.location.href = '/login?username=<?= htmlspecialchars($_SESSION['temp_user']->username ?? '') ?>';"
                        class="bg-gray-300 text-gray-700 py-2 px-4 rounded hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" 
                        class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                    Verify
                </button>
            </div>
        </form>
    </div>
</div>

<?php if (isset($_SESSION['error_message'])): ?>
    <p class="text-red-500 text-sm"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
    <?php unset($_SESSION['error_message']);?>
<?php endif; ?>

<?php require base_path('views/partials/footer.php') ?>