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
            'name'               => 'Проекти',
            'singular_name'      => 'Проект',
            'menu_name'          => 'Проекти',
            'name_admin_bar'     => 'Проект',
            'add_new'            => 'Добави нов',
            'add_new_item'       => 'Добавяне на нов проект',
            'new_item'           => 'Нов проект',
            'edit_item'          => 'Редактиране на проект',
            'view_item'          => 'Преглед на проект',
            'all_items'          => 'Всички проекти',
            'search_items'       => 'Търсене на проекти',
            'not_found'          => 'Няма намерени проекти',
            'not_found_in_trash' => 'Няма намерени проекти в кошчето',
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'menu_icon'          => 'dashicons-megaphone',
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'author'],
            'rewrite'            => ['slug' => 'projects'],
            'show_in_rest'       => true,
            'capability_type'    => 'project',
            'map_meta_cap'    => true,
            'show_in_menu'    => true,
        ];

        add_action('pre_get_posts', function ($query) {
            if (
                is_admin() &&
                $query->is_main_query() &&
                $query->get('post_type') === 'project' &&
                !current_user_can('manage_options')
            ) {
                $query->set('author', get_current_user_id());
            }
        });

        add_action('init', function () {
            $role = get_role('subscriber');
            if ($role) {
                $role->add_cap('read');
                $role->add_cap('edit_project');
                $role->add_cap('edit_projects');
                $role->add_cap('publish_projects');
                $role->add_cap('delete_project');
                $role->add_cap('delete_projects');
            }
        });

        register_post_type('project', $args);
    }

    public function register_taxonomy()
    {
        $labels = [
            'name'              => 'Категории',
            'singular_name'     => 'Категория',
            'search_items'      => 'Търсене в категории',
            'all_items'         => 'Всички категории',
            'parent_item'       => 'Родителска категория',
            'parent_item_colon' => 'Родителска категория:',
            'edit_item'         => 'Редактиране на категория',
            'update_item'       => 'Обновяване на категория',
            'add_new_item'      => 'Добавяне на нова категория',
            'new_item_name'     => 'Нова категория',
            'menu_name'         => 'Категории',
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

        echo '<label for="project_price">Сума:</label>';
        echo '<input type="text" id="project_price" name="project_price" value="'.esc_attr($price).'" />';

        echo '<label for="project_location">Локация:</label>';
        echo '<input type="text" id="project_location" name="project_location" value="'.esc_attr($location).'" />';

        echo '<label for="project_contact">Контакти:</label>';
        echo '<input type="text" id="project_contact" name="project_contact" value="'.esc_attr($contact).'" />';

        echo '<label for="project_skills">Умения (разделени със запетая):</label>';
        echo '<input type="text" id="project_skills" name="project_skills" value="'.esc_attr($skills).'" />';

        echo '<label for="project_deadline">Краен срок:</label>';
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
