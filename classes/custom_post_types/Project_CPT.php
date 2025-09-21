<?php

namespace Classes\Custom_post_types;

use Theme\Admin\Initialize;

class Project_CPT
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
            'name'               => 'Projects',
            'singular_name'      => 'Project',
            'menu_name'          => 'Projects',
            'name_admin_bar'     => 'Project',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Project',
            'new_item'           => 'New Project',
            'edit_item'          => 'Edit Project',
            'view_item'          => 'View Project',
            'all_items'          => 'All Projects',
            'search_items'       => 'Search Projects',
            'not_found'          => 'No projects found',
            'not_found_in_trash' => 'No projects found in Trash',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'menu_icon'          => 'dashicons-megaphone',
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite'            => ['slug' => 'projects'],
            'show_in_rest'       => true,
        ];

        register_post_type('project', $args);
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
            'rewrite'           => ['slug' => 'project-categories'],
            'show_in_rest'      => true, // Gutenberg + REST API
        ];

        register_taxonomy('project_category', ['project'], $args);
    }

    public function convert_post_slug($data, $postarr)
    {
        if ($data['post_type'] === 'project' && !empty($data['post_title'])) {
            $data['post_name'] = Initialize::transliterate($data['post_title']);
        }
        return $data;
    }

    public function convert_term_slug($data, $taxonomy)
    {
        if ($taxonomy === 'project_category' && !empty($data['name'])) {
            $data['slug'] = Initialize::transliterate($data['name']);
        }
        return $data;
    }

    // meta box for additional fields
    public function register_metabox()
    {
        add_meta_box(
            'project_details',
            'Project Details',
            [$this, 'render_metabox'],
            'project',
            'normal',
            'high'
        );
    }

    public function render_metabox($post)
    {
        wp_nonce_field('save_project_details', 'project_details_nonce');

        $price = get_post_meta($post->ID, '_project_price', true);
        $location = get_post_meta($post->ID, '_project_location', true);
        $contact = get_post_meta($post->ID, '_project_contact', true);
        $skills = get_post_meta($post->ID, '_project_skills', true);
        $deadline = get_post_meta($post->ID, '_project_deadline', true);

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

        echo '<label for="project_price">Price:</label>';
        echo '<input type="text" id="project_price" name="project_price" value="'.esc_attr($price).'" />';

        echo '<label for="project_location">Location:</label>';
        echo '<input type="text" id="project_location" name="project_location" value="'.esc_attr($location).'" />';

        echo '<label for="project_contact">Contact:</label>';
        echo '<input type="text" id="project_contact" name="project_contact" value="'.esc_attr($contact).'" />';

        echo '<label for="project_skills">Skills (comma separated):</label>';
        echo '<input type="text" id="project_skills" name="project_skills" value="'.esc_attr($skills).'" />';

        echo '<label for="project_deadline">Deadline:</label>';
        echo '<input type="text" id="project_deadline" name="project_deadline" value="'.esc_attr($deadline).'" />';

        echo '</div>';
    }

    public function save_metabox($post_id)
    {
        if (!isset($_POST['project_details_nonce']) || !wp_verify_nonce($_POST['project_details_nonce'], 'save_project_details')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        update_post_meta($post_id, '_project_price', sanitize_text_field($_POST['project_price'] ?? ''));
        update_post_meta($post_id, '_project_location', sanitize_text_field($_POST['project_location'] ?? ''));
        update_post_meta($post_id, '_project_contact', sanitize_text_field($_POST['project_contact'] ?? ''));
        update_post_meta($post_id, '_project_skills', sanitize_text_field($_POST['project_skills'] ?? ''));
        update_post_meta($post_id, '_project_deadline', sanitize_text_field($_POST['project_deadline'] ?? ''));
    }
}
