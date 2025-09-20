<?php

namespace Theme\Admin;

if (!defined('ABSPATH'))
    exit;

class PolylangStrings
{

    private static $strings = [
        'categories_menu_button' => 'Categories',
        'post_project_button' => 'Post a Project',
        'browse_projects' => 'Browse Projects',
        'about_page' => 'About',
    ];

    public function __construct()
    {
        add_action('init', [$this, 'register_strings']);
    }

    public function register_strings()
    {
        if (function_exists('pll_register_string')) {
            foreach (self::$strings as $name => $string) {
                pll_register_string($name, $string, 'Theme Strings', false);
            }
        }
    }

    public static function get($name)
    {
        if (function_exists('pll__') && isset(self::$strings[$name])) {
            return pll__(self::$strings[$name]);
        }
        return isset(self::$strings[$name]) ? self::$strings[$name] : $name;
    }
}
