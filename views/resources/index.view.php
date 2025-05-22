<?php require base_path('views/partials/header.php') ?>

<div class="flex justify-center">
    <div class="bg-white max-w-4xl mb-6 mx-auto p-12 rounded-2xl shadow-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Resources Page</h1>
        <p class="text-sm text-gray-500 mb-6">These resources are granted to you under limited access. Please check your access permissions and the allowed time to download.</p>

        <ul class="space-y-8">
            <?php foreach ($resources as $folder => $files): ?>
                <li>
                    <h2 class="text-xl font-bold text-gray-700"><?= htmlspecialchars($folder) ?></h2>
                    <ul class="space-y-4 mt-4">
                        <?php foreach ($files as $file): ?>
                            <li class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6h-4z" />
                                    <path d="M14 2v6h6M8 14h8v2H8zM8 10h8v2H8z" />
                                </svg>
                                <div class="flex-grow">
                                    <p class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($file) ?></p>
                                    <p class="text-sm text-gray-500">Click to download this file.</p>
                                </div>
                                <a href="/resources/<?= urlencode($folder) ?>/<?= urlencode($file) ?>"
                                   class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50">
                                   Download
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>


<?php require base_path('views/partials/footer.php') ?>
