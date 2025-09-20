<?php

use Theme\Admin\PolylangStrings;

$categories = get_terms([
    'taxonomy' => 'ad_category',
    'hide_empty' => false,
]);
?>

<nav class="bg-white shadow-md">
    <div class="container mx-auto px-5 py-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="<?= home_url(); ?>" class="text-2xl font-bold text-gray-800">FreelanceHub</a>

        <!-- Desktop Menu -->
        <ul class="hidden md:flex space-x-6 items-center">
            <li class="relative group">
                <button class="text-gray-700 hover:text-blue-600 flex items-center">
                    <?php echo esc_html(PolylangStrings::get('categories_menu_button')); ?>
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <ul
                    class="absolute left-0 top-full pt-2 hidden group-hover:block bg-white shadow-md rounded min-w-[250px]">
                    <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="<?php echo esc_url(get_term_link($cat)); ?>"
                                class="block px-4 py-2 hover:text-white hover:bg-black">
                                <?php echo esc_html($cat->name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php
            wp_nav_menu([
                'theme_location' => '',
                'container' => false,
                'items_wrap' => '%3$s',
            ]);
            ?>
        </ul>

        <!-- Mobile Menu Icon -->
        <div class="md:hidden flex items-center">
            <button id="mobile-menu-button" class="text-gray-700 focus:outline-none cursor-pointer">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden overflow-hidden max-h-0 opacity-0 transition-all duration-300">
        <ul class="flex flex-col space-y-4 p-5 bg-white shadow-md">
            <?php
            wp_nav_menu([
                'theme_location' => 'main-menu',
                'container' => false,
                'items_wrap' => '%3$s',
            ]);
            ?>
            <?php foreach ($categories as $cat): ?>
                <li>
                    <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="text-gray-700 hover:text-blue-600">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
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