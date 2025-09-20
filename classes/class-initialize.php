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

        if (!is_admin() && current_user_can('administrator')) return;

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
        if ($post_type === 'page' || $post_type === 'ad') {
            return false;
        }

        return $use_block_editor;
    }

    public static function transliterate($text)
    {
        $cyr = ['ж','ч','щ','ш','ю','я','а','б','в','г','д','е','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ъ','ь','ы','э',
                'Ж','Ч','Щ','Ш','Ю','Я','А','Б','В','Г','Д','Е','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ъ','Ь','Ы','Э'];
        $lat = ['zh','ch','sht','sh','yu','ya','a','b','v','g','d','e','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','ts','a','y','y','e',
                'Zh','Ch','Sht','Sh','Yu','Ya','A','B','V','G','D','E','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','H','Ts','A','Y','Y','E'];

        $text = str_replace($cyr, $lat, $text);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
}