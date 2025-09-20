<?php

namespace Classes\Custom_post_types;

use Theme\Admin\Initialize;

class Ads_CPT
{
    public function __construct()
    {
        add_action('init', [$this, 'register_cpt']);
        add_action('init', [$this, 'register_taxonomy']);

        add_filter('wp_insert_post_data', [$this, 'convert_post_slug'], 10, 2);
        add_filter('wp_insert_term_data', [$this, 'convert_term_slug'], 10, 2);

        add_action('add_meta_boxes', [$this, 'register_metabox']);
        add_action('save_post', [$this, 'save_metabox']);
    }

    public function register_cpt()
    {
        $labels = [
            'name'               => 'Ads',
            'singular_name'      => 'Ad',
            'menu_name'          => 'Ads',
            'name_admin_bar'     => 'Ad',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Ad',
            'new_item'           => 'New Ad',
            'edit_item'          => 'Edit Ad',
            'view_item'          => 'View Ad',
            'all_items'          => 'All Ads',
            'search_items'       => 'Search Ads',
            'not_found'          => 'No ads found',
            'not_found_in_trash' => 'No ads found in Trash',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'menu_icon'          => 'dashicons-megaphone',
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite'            => ['slug' => 'ads'],
            'show_in_rest'       => true,
        ];

        register_post_type('ad', $args);
    }

    public function register_taxonomy()
    {
        $labels = [
            'name'              => 'Categories',
            'singular_name'     => 'Category',
            'search_items'      => 'Search Categories',
            'all_items'         => 'All Categories',
            'parent_item'       => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'edit_item'         => 'Edit Category',
            'update_item'       => 'Update Category',
            'add_new_item'      => 'Add New Category',
            'new_item_name'     => 'New Category',
            'menu_name'         => 'Categories',
        ];

        $args = [
            'hierarchical'      => true, // behaves like normal categories
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'ad-category'],
            'show_in_rest'      => true, // Gutenberg + REST API
        ];

        register_taxonomy('ad_category', ['ad'], $args);
    }

    public function convert_post_slug($data, $postarr)
    {
        if ($data['post_type'] === 'ad' && !empty($data['post_title'])) {
            $data['post_name'] = Initialize::transliterate($data['post_title']);
        }
        return $data;
    }

    public function convert_term_slug($data, $taxonomy)
    {
        if ($taxonomy === 'ad_category' && !empty($data['name'])) {
            $data['slug'] = Initialize::transliterate($data['name']);
        }
        return $data;
    }

    // meta box for additional fields
    public function register_metabox()
    {
        add_meta_box(
            'ad_details',
            'Ad Details',
            [$this, 'render_metabox'],
            'ad',
            'normal',
            'high'
        );
    }

    public function render_metabox($post)
    {
        wp_nonce_field('save_ad_details', 'ad_details_nonce');

        $price = get_post_meta($post->ID, '_ad_price', true);
        $location = get_post_meta($post->ID, '_ad_location', true);
        $contact = get_post_meta($post->ID, '_ad_contact', true);
        $skills = get_post_meta($post->ID, '_ad_skills', true);
        $deadline = get_post_meta($post->ID, '_ad_deadline', true);

        echo '<style>
            .ad-meta-box { display: grid; grid-template-columns: 250px 1fr; gap: 10px; align-items: center; }
            .ad-meta-box label { font-weight: normal; font-size: 18px; }
            .ad-meta-box input[type="text"], .ad-meta-box input[type="number"], .ad-meta-box textarea {
                width: 100%;
                padding: 6px 12px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 14px;
            }
            .ad-meta-box textarea { resize: vertical; }
        </style>';

        echo '<div class="ad-meta-box">';

        echo '<label for="ad_price">Price:</label>';
        echo '<input type="text" id="ad_price" name="ad_price" value="'.esc_attr($price).'" />';

        echo '<label for="ad_location">Location:</label>';
        echo '<input type="text" id="ad_location" name="ad_location" value="'.esc_attr($location).'" />';

        echo '<label for="ad_contact">Contact:</label>';
        echo '<input type="text" id="ad_contact" name="ad_contact" value="'.esc_attr($contact).'" />';

        echo '<label for="ad_skills">Skills (comma separated):</label>';
        echo '<input type="text" id="ad_skills" name="ad_skills" value="'.esc_attr($skills).'" />';

        echo '<label for="ad_deadline">Deadline:</label>';
        echo '<input type="text" id="ad_deadline" name="ad_deadline" value="'.esc_attr($deadline).'" />';

        echo '</div>';
    }

    public function save_metabox($post_id)
    {
        if (!isset($_POST['ad_details_nonce']) || !wp_verify_nonce($_POST['ad_details_nonce'], 'save_ad_details')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        update_post_meta($post_id, '_ad_price', sanitize_text_field($_POST['ad_price'] ?? ''));
        update_post_meta($post_id, '_ad_location', sanitize_text_field($_POST['ad_location'] ?? ''));
        update_post_meta($post_id, '_ad_contact', sanitize_text_field($_POST['ad_contact'] ?? ''));
        update_post_meta($post_id, '_ad_skills', sanitize_text_field($_POST['ad_skills'] ?? ''));
        update_post_meta($post_id, '_ad_deadline', sanitize_text_field($_POST['ad_deadline'] ?? ''));
    }
}
