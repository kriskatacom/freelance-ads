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

    public static function menuLinkClasses($item)
    {
        $current_id   = get_queried_object_id();
        $is_active    = intval($item->object_id) === $current_id;

        $classes = 'py-2 px-4 rounded block hover:text-gray-100 hover:bg-black';

        if ($is_active) {
            $classes .= ' text-gray-100 bg-black';
        }

        return $classes;
    }
}