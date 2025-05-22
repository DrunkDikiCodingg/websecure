<?php require base_path('views/partials/header.php') ?>

<!-- Profile Section -->
<div class="min-h-[calc(100vh-160px)] py-8">
    <div class="max-w-4xl mx-auto bg-white p-12 rounded-lg shadow-lg">
        <!-- Account Information -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Account Information</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">The username associated with this account.</p>
                        <p class="text-lg font-semibold text-gray-800"><?= $user->username ?></p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">The email address used for notifications and login.</p>
                        <p class="text-lg font-semibold text-gray-800"><?= $user->email ?></p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Assigned Roles for this user.</p>
                        <p class="text-lg font-semibold text-gray-800"><?= implode(', ', $user->roles) ?: 'None' ?></p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">The date when this account was created.</p>
                        <p class="text-lg font-semibold text-gray-800">Member Since: <?= date("F j, Y", strtotime($user->created_at)) ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Divider -->
        <hr class="my-8 border-gray-300">

        <!-- User Settings -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6">User Settings</h2>
            <div class="space-y-6">
                <div class="p-4 bg-gray-100 rounded-md">
                    <p class="text-sm text-gray-500 mb-2">You can change the password for this account.</p>
                    <button type="button" onclick="showChangePasswordModal()" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Change Password</button>
                </div>
                <div class="p-4 bg-gray-100 rounded-md">
                    <p class="text-sm text-gray-500 mb-2">Update the username for this account.</p>
                    <button type="button" onclick="showChangeUsernameModal()" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Change Username</button>
                </div>
                <div class="p-4 bg-gray-100 rounded-md">
                    <p class="text-sm text-gray-500 mb-2">Permanently remove this account. This action cannot be undone.</p>
                    <button type="button" onclick="showDeleteUserModal()" class="bg-red-400 font-semibold hover:bg-red-500 px-2.5 py-1.5 ring-1 ring-gray-300 ring-inset rounded-md shadow-xs text-gray-900 text-sm text-white">Delete User</button>
                </div>
            </div>
        </section>

        <!-- Divider -->
        <hr class="my-8 border-gray-300">

        <!-- Authorization Section -->
        <section>
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Authorization</h2>

            <!-- Role Management Section -->
            <div class="mb-8">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Role Management</h3>
                <p class="text-sm text-gray-500 mb-4">Assign or remove permanent roles for this user. Roles define the user's permissions across the platform.</p>
                <form method="POST" action="/users/<?= $user->id ?>/roles" class="space-y-4">
                    <input type="hidden" name="_method" value="PATCH">
                    <?php foreach (['admin', 'user'] as $role): ?>
                        <div class="flex items-center space-x-3">
                            <input 
                                type="checkbox" 
                                id="role-<?= $role ?>" 
                                name="roles[]" 
                                value="<?= $role ?>" 
                                class="h-4 w-4 rounded border-gray-300" 
                                <?= in_array($role, $user->roles) ? 'checked' : '' ?>
                            >
                            <label for="role-<?= $role ?>" class="text-gray-700 font-medium">
                                <?= ucfirst($role) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" 
                            class="rounded-md bg-blue-500 text-white px-3 py-2 text-sm font-semibold hover:bg-blue-600">
                        Update Roles
                    </button>
                </form>
            </div>
            <!-- Grant Temporary Access Section -->
            <div class="bg-gray-100 p-4 rounded-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Grant Temporary Access</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Grant this user temporary access to resources for a specific duration. If the user already has temporary access, you can revoke it here.
                </p>
                <?php if ($user->jit_expiration && (new DateTime($user->jit_expiration, new DateTimeZone('UTC')) > new DateTime('now', new DateTimeZone('UTC')))): ?>
                    <div class="space-y-2">
                        <p class="text-sm text-green-600">
                            This user has temporary access until <?= (new DateTime($user->jit_expiration, new DateTimeZone('UTC')))->format('F j, Y, g:i a') ?> UTC.
                        </p>
                        <form method="POST" action="/users/<?= $user->id ?>/jit-access">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit"
                                class="bg-red-400 font-semibold hover:bg-red-500 px-2.5 py-1.5 ring-1 ring-gray-300 ring-inset rounded-md shadow-xs text-gray-900 text-sm text-white">
                                Revoke Access
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <form method="POST" action="/users/<?= $user->id ?>/jit-access" class="flex items-center space-x-2">
                        <input type="number" name="duration" placeholder="Minutes" required
                            class="rounded-md border-gray-300 px-2.5 py-1.5 text-sm">
                        <button type="submit"
                                class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">
                            Grant Access
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<?php if (\Core\Session::has('success')): ?>
    <div id="flash-success" class="fixed bottom-4 right-4 bg-green-100 text-green-800 p-4 rounded-lg shadow-lg flex items-center space-x-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
            <path d="M10 15.172l3.536-3.535a1 1 0 00-1.415-1.415L10 12.343l-1.122-1.121a1 1 0 10-1.415 1.414L10 15.172zM12 22a10 10 0 100-20 10 10 0 000 20z"/>
        </svg>
        <span><?= \Core\Session::get('success') ?></span>
    </div>
<?php endif; ?>

<?php if (\Core\Session::has('errors')): ?>
    <div id="flash-error" class="fixed bottom-4 right-4 bg-red-100 text-red-800 p-4 rounded-lg shadow-lg flex items-center space-x-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18a8 8 0 110-16 8 8 0 010 16zm-.707-11.293a1 1 0 011.414 0L12 10.586l.293-.293a1 1 0 011.414 1.414L12.707 12l.293.293a1 1 0 01-1.414 1.414L12 13.414l-.293.293a1 1 0 01-1.414-1.414L11.293 12l-.293-.293a1 1 0 010-1.414z"/>
        </svg>
        <ul>
            <?php foreach (\Core\Session::get('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<script>
    // Automatically hide flash messages after 5 seconds
    setTimeout(() => {
        const success = document.getElementById('flash-success');
        const error = document.getElementById('flash-error');
        if (success) success.style.display = 'none';
        if (error) error.style.display = 'none';
    }, 5000);
</script>
<script>
    // Automatically hide flash messages after 5 seconds
    setTimeout(() => {
        const success = document.getElementById('flash-success');
        const error = document.getElementById('flash-error');
        if (success) success.style.display = 'none';
        if (error) error.style.display = 'none';
    }, 5000);
</script>


<!-- Modals -->
<div id="changePasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Change Password</h2>
        <form method="POST" action="/users/<?= $user->id ?>/password">
            <input type="hidden" name="_method" value="PATCH">
            <label for="newPassword" class="block text-gray-700 mb-2">New Password:</label>
            <input type="password" id="newPassword" name="password" class="w-full border rounded-lg p-2 mb-4" required>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="hideChangePasswordModal()" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Cancel</button>
                <button type="submit" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Change</button>
            </div>
        </form>
    </div>
</div>

<div id="changeUsernameModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Change Username</h2>
        <form method="POST" action="/users/<?= $user->id ?>/username">
            <input type="hidden" name="_method" value="PATCH">
            <label for="newUsername" class="block text-gray-700 mb-2">New Username:</label>
            <input type="text" id="newUsername" name="username" class="w-full border rounded-lg p-2 mb-4" required>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="hideChangeUsernameModal()" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Cancel</button>
                <button type="submit" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Change</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Confirm Deletion</h2>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
        <div class="flex justify-end space-x-4">
            <button type="button" onclick="hideDeleteUserModal()" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Cancel</button>
            <form method="POST" action="/users/<?= $user->id ?>">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    function showChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.remove('hidden');
    }
    function hideChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.add('hidden');
    }

    function showChangeUsernameModal() {
        document.getElementById('changeUsernameModal').classList.remove('hidden');
    }
    function hideChangeUsernameModal() {
        document.getElementById('changeUsernameModal').classList.add('hidden');
    }

    function showDeleteUserModal() {
        document.getElementById('deleteUserModal').classList.remove('hidden');
    }
    function hideDeleteUserModal() {
        document.getElementById('deleteUserModal').classList.add('hidden');
    }
</script>

<?php require base_path('views/partials/footer.php') ?>
