<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>WebSecure</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen bg-gray-100 text-gray-800">
    <header class="bg-white shadow">
        <nav class="container mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Left Side Links -->
            <div class="flex items-center justify-start space-x-8">
                <!-- Brand -->
                <a href="/" class="flex items-center mr-6">
                    <img src="/img/logo.png" alt="Logo" class="h-8 w-8 mr-2">
                    <span class="font-medium text-2xl text-blue-500 text-gray-500">
                        WebSecure
                    </span>
                </a>
                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    <?php if (isLoggedIn()): ?>
                        <?php if (hasRole('admin')): ?>
                            <a href="/users"
                                class="<?= urlIs('/users') ? 'border-blue-500 text-blue-500' : 'border-gray-300 text-gray-700' ?> hover:border-blue-500 hover:text-blue-500 border px-3 py-2 rounded-md text-sm font-medium">
                                Users
                            </a>
                        <?php endif; ?>
                        <?php if (hasRole('admin') || hasJITAccess()): ?>
                            <a href="/resources"
                                class="<?= urlIs('/resources') ? 'border-blue-500 text-blue-500' : 'border-gray-300 text-gray-700' ?> hover:border-blue-500 hover:text-blue-500 border px-3 py-2 rounded-md text-sm font-medium">
                                Resources
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Side Links -->
            <div class="flex items-center space-x-4">
                <?php if (isLoggedIn()): ?>
                    <a href="/profile" class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full font-medium hover:shadow-lg transition-transform transform hover:scale-110 hover:font-bold">
                        <?= strtoupper(auth()->username[0]); ?>
                    </a>
                    <form method="POST" action="/session" class="inline">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="text-gray-600 hover:text-blue-500 font-medium">
                            Logout
                        </button>
                    </form>
                <?php else: ?>
                    <a href="/login" class="text-gray-600 hover:text-blue-500 font-medium">Login</a>
                    <a href="/register" class="text-gray-600 hover:text-blue-500 font-medium">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main class="flex-grow container mx-auto p-4">