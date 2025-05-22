<?php require base_path('views/partials/header.php') ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-6">Index of Users</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-2 px-4 border-b text-left">ID</th>
                    <th class="py-2 px-4 border-b text-left">Username</th>
                    <th class="py-2 px-4 border-b text-left">Email</th>
                    <th class="py-2 px-4 border-b text-left">Created At</th>
                    <th class="py-2 px-4 border-b text-left">Roles</th>
                    <th class="py-2 px-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b"><?= $user->id ?></td>
                        <td class="py-2 px-4 border-b"><?= $user->username ?></td>
                        <td class="py-2 px-4 border-b"><?= $user->email ?></td>
                        <td class="py-2 px-4 border-b"><?= $user->created_at ?></td>
                        <td class="py-2 px-4 border-b">
                            [<?= $user->roles ?? '/'; ?>]
                        </td>
                        <td class="py-2 px-4 border-b text-center space-x-2">
                            <a href="/user/<?= $user->id ?>" 
                               class="inline-block text-blue-500 hover:text-blue-700 font-medium">
                                View
                            </a>
                            <?php if (hasRole('admin')): ?>
                                <a href="/delete-user?id=<?= $user->id ?>" 
                                   class="inline-block text-red-500 hover:text-red-700 font-medium"
                                   onclick="return confirm('Are you sure you want to delete this user?');">
                                    Delete
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require base_path('views/partials/footer.php') ?>
