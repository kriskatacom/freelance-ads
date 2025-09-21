<?php
/* Template Name: Custom Register */

if ( is_user_logged_in() ) {
    wp_safe_redirect(home_url());
    exit;
}

get_header();
?>

<div class="min-h-screen max-w-2xl mx-auto mb-10">
    <h1 class="text-3xl lg:text-4xl font-semibold text-center my-10">Регистрация</h1>

    <div id="registerMessage"></div>

    <div class="bg-white shadow rounded p-10">
        <form class="space-y-5" id="registerForm">
            <div class="space-y-2">
                <label for="name">Потребителско име</label>
                <input type="text" name="name" id="name" placeholder="Въведете потребителско име" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none mt-2 duration-300" required>
            </div>
            <div class="space-y-2">
                <label for="email">Имейл</label>
                <input type="email" name="email" id="email" placeholder="Въведете имейл адреса си" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none mt-2 duration-300" required>
            </div>
            <div class="space-y-2">
                <label for="password">Парола</label>
                <input type="password" name="password" id="password" placeholder="Въведете паролата си" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none mt-2 duration-300" required>
            </div>

            <label class="flex items-center cursor-pointer select-none">
                <div class="relative">
                    <input type="checkbox" name="rememberme" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition-colors"></div>
                    <div
                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md peer-checked:translate-x-full transition-transform">
                    </div>
                </div>
                <span class="ml-3 text-gray-700 font-medium">Запомни ме</span>
            </label>

            <div>
                <button type="submit" class="text-white bg-black hover:bg-gray-900 cursor-pointer py-3 px-5 rounded flex items-center gap-2 w-full justify-center">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Създай акаунт</span>
                </button>
            </div>

            <div class="text-center mt-4">
                <a href="<?= home_url("/login") ?>" class="text-blue-500 hover:text-blue-600">Вече имате профил? Влезте</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        const response = await fetch('<?php echo site_url('/wp-json/custom/v1/register'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username: name, email: email, password: password })
        });

        const result = await response.json();
        const messageDiv = document.getElementById('registerMessage');

        if (response.ok && result.success) {
            messageDiv.innerHTML =
            `<div class="flex items-center gap-5 bg-green-100 border-2 border-green-300 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <i class="fas fa-warning text-4xl"></i>
                <div class="flex flex-col">
                    <strong class="font-bold">Поздравления!</strong>
                    <span class="block sm:inline">${result.message}</span>
                </div>
            </div>`;
            document.getElementById('registerForm').reset();
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 3000);
        } else {
            messageDiv.innerHTML =
            `<div class="flex items-center gap-5 bg-red-100 border-2 border-red-300 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <i class="fas fa-check text-4xl"></i>
                <div class="flex flex-col">
                    <strong class="font-bold">Грешка!</strong>
                    <span class="block sm:inline">${result.message || 'Грешка при регистрацията.'}</span>
                </div>
            </div>`;
        }
    });
</script>

<?php get_footer(); ?>