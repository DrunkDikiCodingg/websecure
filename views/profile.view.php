<?php require('partials/header.php') ?>


<?php
                    // dd($user);
                    ?>

<div class="flex items-center justify-center min-h-[calc(100vh-160px)]">
    <div class="bg-white max-w-4xl mb-6 mx-auto p-12 rounded-2xl shadow-lg">
        <!-- Profile Section -->
        <section>
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Your Profile</h1>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Username associated with your account:</p>
                    <p class="text-lg font-semibold text-gray-800"><?= auth()->username ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email address used for notifications and login:</p>
                    <p class="text-lg font-semibold text-gray-800"><?= auth()->email ?></p>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Assigned Roles for this user.</p>
                        <p class="text-lg font-semibold text-gray-800"><?= implode(', ', $user->roles) ?: 'None' ?></p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date you joined:</p>
                    <p class="text-lg font-semibold text-gray-800"><?= date("F j, Y", strtotime(auth()->created_at)) ?></p>
                </div>
            </div>
        </section>

        <!-- Divider -->
        <hr class="my-8 border-gray-300">

        <!-- User Settings -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">User Settings</h2>
            <div class="space-y-6">
                <div>
                    <p class="text-sm text-gray-500 mb-2">Change your account password.</p>
                    <button disabled="disabled" type="button" onclick="showChangePasswordModal()" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Change Password</button>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-2">Update your username.</p>
                    <button disabled="disabled" type="button" onclick="showChangeUsernameModal()" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Change Username</button>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-2">Delete your account permanently. This action cannot be undone.</p>
                    <button disabled="disabled" type="button" onclick="showDeleteUserModal()" class="bg-red-400 font-semibold hover:bg-red-500 px-2.5 py-1.5 ring-1 ring-gray-300 ring-inset rounded-md shadow-xs text-gray-900 text-sm text-white">Delete Account</button>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modals -->
<div id="changePasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Change Password</h2>
        <form method="POST" action="/profile/password">
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
        <form method="POST" action="/profile/username">
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
        <h2 class="text-lg font-bold text-gray-800 mb-4">Delete Account</h2>
        <p class="text-gray-600 mb-6">Are you sure you want to delete your account? This action cannot be undone.</p>
        <div class="flex justify-end space-x-4">
            <button type="button" onclick="hideDeleteUserModal()" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">Cancel</button>
            <form method="POST" action="/profile/delete">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="bg-red-400 font-semibold hover:bg-red-500 px-2.5 py-1.5 ring-1 ring-gray-300 ring-inset rounded-md shadow-xs text-gray-900 text-sm text-white">Delete</button>
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

<?php require('partials/footer.php') ?>
