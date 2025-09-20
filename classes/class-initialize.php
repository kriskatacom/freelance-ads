<?php

namespace Theme\Admin;

class Initialize
{
    public static function init()
    {
        add_filter('wp_prepare_themes_for_js', [self::class, 'openAuthorUriInNewTab']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueueDependencies']);
        add_filter('use_block_editor_for_post_type', [self::class, 'forceClassicEditorForPages'], 10, 2);
    }

    public static function openAuthorUriInNewTab($themes)
    {
        foreach ($themes as $slug => &$theme) {
            if (!empty($theme['authorAndUri'])) {
                $theme['authorAndUri'] = preg_replace(
                    '/<a (.*?)>/i',
                    '<a $1 target="_blank" rel="noopener noreferrer">',
                    $theme['authorAndUri']
                );
            }
        }

        return $themes;
    }

    public static function enqueueDependencies()
    {
        $theme_dir = get_template_directory();
        $theme_uri = get_template_directory_uri();

        wp_enqueue_style(
            'freelance_ads_tailwind',
            $theme_uri . '/assets/css/tailwind.css',
            [],
            filemtime($theme_dir . '/assets/css/tailwind.css')
        );

        wp_deregister_script('jquery');
        wp_enqueue_script(
            'jquery',
            $theme_uri . '/assets/js/min/jquery.min.js',
            [],
            filemtime($theme_dir . '/assets/js/min/jquery.min.js'),
            true
        );

        wp_enqueue_script(
            'freelance_ads_homepage-js',
            $theme_uri . '/assets/js/homepage.js',
            ['jquery'],
            filemtime($theme_dir . '/assets/js/homepage.js'),
            true
        );
    }

    public static function forceClassicEditorForPages($use_block_editor, $post_type)
    {
        if ($post_type === 'page') {
            return false;
        }

        return $use_block_editor;
    }
}