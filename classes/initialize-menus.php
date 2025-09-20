<?php

namespace Theme\Admin;

class InitializeMenus
{
    public static function init()
    {
        add_action('after_setup_theme', [self::class, 'registerMenus']);
    }

    public static function registerMenus()
    {
        register_nav_menus([
            'main_menu' => __('Main Menu', 'freelance-ads'),
        ]);
    }
}
