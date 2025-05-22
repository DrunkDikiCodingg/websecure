<?php require('partials/header.php');


// dd(auth());
// dd($_SESSION['roles']);


?>

<div class="flex flex-col items-center justify-center min-h-[calc(100vh-160px)] text-center">
    <?php if (isLoggedIn()): ?>
        <h1 class="text-3xl font-bold">
            Welcome 
            <span class="font-medium text-blue-500"><?= auth()->username ?></span>
        </h1>
        <div class="mt-4">
            <span class="text-gray-600">
                You are logged in. Explore the app or
                <form method="POST" action="/session" class="inline">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="text-blue-500">
                        sign out
                    </button>
                </form>
            </span>
        </div>
    <?php else: ?>
        <h1 class="text-3xl font-bold">Welcome to WebSecure!</h1>
        <p class="text-gray-600 mt-4">
            Please
            <a href="/login" class="text-blue-500">login</a> or
            <a href="/register" class="text-blue-500">register</a> to continue.
        </p>
    <?php endif; ?>
</div>

<?php require('partials/footer.php') ?>
