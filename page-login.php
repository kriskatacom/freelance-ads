<?php
/* Template Name: Custom Login */

if ( is_user_logged_in() ) {
    wp_safe_redirect(home_url());
    exit;
}

$error = '';

if ( isset($_POST['email']) && isset($_POST['password']) ) {
    $creds = array(
        'user_login'    => sanitize_text_field($_POST['email']),
        'user_password' => $_POST['password'],
        'remember'      => isset($_POST['rememberme']),
    );

    $user = wp_signon($creds, false);

    if ( is_wp_error($user) ) {
        $error = 'Грешно потребителско име или парола.';
    } else {
        wp_safe_redirect(home_url());
        exit;
    }
}

get_header();
?>

<div class="min-h-screen max-w-2xl mx-auto mb-10">
    <h1 class="text-3xl lg:text-4xl font-semibold text-center my-10">Влизане в системата</h1>

    <?php if ( $error ) : ?>
        <div class="flex items-center gap-5 bg-red-100 border-2 border-red-300 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <i class="fas fa-warning text-4xl"></i>
            <div class="flex flex-col">
                <strong class="font-bold">Грешка!</strong>
                <span class="block sm:inline">Моля, проверете въведените данни и опитайте отново.</span>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow rounded p-10">
        <form method="post" action="<?php echo esc_url(get_permalink()); ?>" class="space-y-5">
            <div class="space-y-2">
                <label for="email">Имейл</label>
                <input type="email" name="email" id="email" value="<?= $_POST["email"] ?? "" ?>" placeholder="Въведете имейл адресът си" title="Моля, попълнете имейл адресът си!" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none mt-2 duration-300" required>
            </div>
            <div>
                <label for="password">Парола</label>
                <input type="password" name="password" id="password" placeholder="Въведете паролата си" title="Моля, попълнете паролата си!" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none mt-2 duration-300" required>
            </div>
            <label class="flex items-center cursor-pointer select-none relative group">
                <div class="relative">
                    <input type="checkbox" name="rememberme" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition-colors"></div>
                    <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md peer-checked:translate-x-full transition-transform"></div>
                </div>
                <span class="ml-3 text-gray-700 font-medium">
                    <span>Запомни ме</span>
                    <i class="fas fa-question-circle"></i>
                </span>
                <div class="absolute left-0 -top-10 bg-gray-800 text-white rounded px-2 py-1 opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100 transition-all duration-200 pointer-events-none whitespace-nowrap">
                    Запомнете входа за по-бърз достъп следващия път
                </div>
            </label>
            <div>
                <button type="submit" class="text-white bg-black hover:bg-gray-900 cursor-pointer py-3 px-5 rounded flex items-center gap-2 w-full justify-center">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Влизане</span>
                </button>
            </div>
            <div class="text-center mt-4">
                <a href="<?= home_url("/register") ?>" class="text-blue-500 hover:text-blue-600">Създаване на профил</a>
            </div>
        </form>
    </div>
</div>

<?php get_footer(); ?>
