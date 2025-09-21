<?php

use Theme\Admin\InitializeMenus;
use Theme\Admin\PolylangStrings;

$current_url = home_url(add_query_arg([], $wp->request));

$locations = get_nav_menu_locations();
$main_menu_items = [];
$categories_menu_items = [];

if (isset($locations['main_menu'])) {
    $menu = wp_get_nav_menu_object($locations['main_menu']);
    $main_menu_items = wp_get_nav_menu_items($menu->term_id);
}
if (isset($locations['categories_menu'])) {
    $menu = wp_get_nav_menu_object($locations['categories_menu']);
    $categories_menu_items = wp_get_nav_menu_items($menu->term_id);
}
?>

<nav class="bg-white shadow">
    <div class="container mx-auto px-5 py-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="<?= home_url(); ?>" class="text-2xl font-bold text-gray-800">FreelanceHub</a>

        <!-- Desktop Menu -->
        <ul class="hidden md:flex space-x-6 items-center">
            <li class="relative group">
                <button class="hover:text-black flex items-center">
                    <a href="<?= home_url() . "/categories"; ?>">
                        Категории
                    </a>
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <ul
                    class="absolute left-0 top-full pt-2 hidden group-hover:block bg-white shadow-md rounded min-w-[250px]">
                    <?php foreach ($categories_menu_items as $item): ?>
                        <li>
                            <a href="<?php echo esc_url($item->url); ?>"
                                class="block px-4 py-2 hover:text-white hover:bg-black">
                                <?php echo esc_html($item->title); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <ul class="flex items-center gap-2">
                <?php foreach ($main_menu_items as $item): ?>
                    <?php if (strtolower($item->title) === 'Вход'): ?>
                        <?php if (is_user_logged_in()): ?>
                            <li>
                                <a href="<?php echo esc_url(home_url("/wp-admin/profile.php")); ?>"
                                    class="<?php echo esc_attr(InitializeMenus::menuLinkClasses($item)); ?>">
                                    Профил
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>"
                                    class="<?php echo esc_attr(InitializeMenus::menuLinkClasses($item)); ?>">
                                    Изход
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo esc_url(home_url('/login')); ?>"
                                    class="<?php echo esc_attr(InitializeMenus::menuLinkClasses($item)); ?>">
                                    Вход
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li>
                            <a href="<?php echo esc_url($item->url); ?>"
                                class="<?php echo esc_attr(InitializeMenus::menuLinkClasses($item)); ?>">
                                <?php echo esc_html($item->title); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </ul>

        <!-- Mobile Menu Icon -->
        <div class="md:hidden flex items-center space-x-5">
            <div class="relative group">
                <button class="hover:text-black flex items-center">
                    <?php echo esc_html(PolylangStrings::get('categories_menu_button')); ?>
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <ul
                    class="absolute right-0 top-full pt-2 hidden group-hover:block bg-white shadow-md rounded min-w-[250px]">
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="<?php echo esc_url(get_term_link($cat)); ?>"
                                class="block px-4 py-2 hover:text-white hover:bg-black">
                                <?php echo esc_html($cat->name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <button id="mobile-menu-button" class="text-gray-700 focus:outline-none cursor-pointer">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden overflow-auto max-h-0 opacity-0 transition-all duration-300">
        <div class="border-t border-gray-200">
            <div class="p-5">
                <ul class="flex flex-col">
                    <?php foreach ($main_menu_items as $item): ?>
                        <li>
                            <a href="<?php echo esc_url($item->url); ?>"
                                class="<?php echo esc_attr(InitializeMenus::menuLinkClasses($item)); ?>">
                                <?php echo esc_html($item->title); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="text-xl font-semibold my-5">
                    <?php echo esc_html(PolylangStrings::get('categories_menu_button')); ?>
                </div>

                <ul>
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="<?php echo esc_url(get_term_link($cat)); ?>"
                                class="py-2 px-4 rounded hover:text-gray-100 hover:bg-black block">
                                <?php echo esc_html($cat->name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            if (menu.classList.contains('max-h-0')) {
                menu.classList.remove('max-h-0', 'opacity-0');
                menu.classList.add('max-h-screen', 'opacity-100');
            } else {
                menu.classList.remove('max-h-screen', 'opacity-100');
                menu.classList.add('max-h-0', 'opacity-0');
            }
        });
    });
</script>
