<?php

namespace Classes\Pages;

class HomepageSettings
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'register_meta_box']);
        add_action('save_post_page', [$this, 'save_meta_box']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function register_meta_box()
    {
        add_meta_box(
            'homepage_settings',
            'Настройки на първата секция',
            [$this, 'render_meta_box'],
            'page', // показва се при редакция на страници
            'normal',
            'high'
        );
    }

    public function render_meta_box($post)
    {
        $values = get_post_meta($post->ID, '_homepage_settings', true);
        $values = is_array($values) ? $values : [];

        wp_nonce_field('homepage_meta_box', 'homepage_meta_box_nonce');
        ?>
        <div style="padding:10px;">
            <!-- Заглавие -->
            <label><strong>Заглавие</strong></label>
            <input type="text" name="homepage_settings[title]" 
                   value="<?php echo esc_attr($values['title'] ?? ''); ?>" 
                   class="widefat" style="margin-bottom:10px;" />

            <!-- Описание -->
            <?php
                $description = $values['description'] ?? '';
                $editor_id = 'homepage_description';
                $settings = [
                    'textarea_name' => 'homepage_settings[description]',
                    'media_buttons' => true,
                    'textarea_rows' => 6,
                    'teeny'         => false,
                    'quicktags'     => true,
                ];

                wp_editor($description, $editor_id, $settings);
            ?>

            <!-- Фонова снимка -->
            <label><strong>Фонова снимка</strong></label><br>
            <input type="text" id="homepage_background" 
                   name="homepage_settings[background]" 
                   value="<?php echo esc_attr($values['background'] ?? ''); ?>" 
                   class="widefat" style="width:70%; display:inline-block;" />
            <input type="button" class="button select-background" value="Избери снимка">
            <?php if (!empty($values['background'])): ?>
                <div><img src="<?php echo esc_url($values['background']); ?>" 
                          style="max-width:200px; margin-top:10px;"></div>
            <?php endif; ?>

            <!-- Бутони -->
            <h3 style="margin-top:20px;">Бутони</h3>
            <div id="buttons-wrapper">
                <?php
                $buttons = $values['buttons'] ?? [];
                if (!empty($buttons)) {
                    foreach ($buttons as $index => $btn) { ?>
                        <div class="button-item" style="margin-bottom:10px;">
                            <input type="text" name="homepage_settings[buttons][<?php echo $index; ?>][text]" placeholder="Text" value="<?php echo esc_attr($btn['text']); ?>" />
                            <input type="text" name="homepage_settings[buttons][<?php echo $index; ?>][url]" placeholder="URL" value="<?php echo esc_attr($btn['url']); ?>" />
                            <input type="text" name="homepage_settings[buttons][<?php echo $index; ?>][title]" placeholder="SEO Title" value="<?php echo esc_attr($btn['title']); ?>" />
                            <input type="text" name="homepage_settings[buttons][<?php echo $index; ?>][rel]" placeholder="rel attribute" value="<?php echo esc_attr($btn['rel']); ?>" />
                            <input type="text" name="homepage_settings[buttons][<?php echo $index; ?>][classes]" placeholder="CSS classes" value="<?php echo esc_attr($btn['classes'] ?? ''); ?>" />
                            <button type="button" class="button remove-button">Премахни</button>
                        </div>
                    <?php }
                } ?>
            </div>
            <button type="button" class="button add-button">Добави бутон</button>
        </div>
        <?php
    }

    public function save_meta_box($post_id)
    {
        if (!isset($_POST['homepage_meta_box_nonce']) || 
            !wp_verify_nonce($_POST['homepage_meta_box_nonce'], 'homepage_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['homepage_settings'])) {
            update_post_meta($post_id, '_homepage_settings', $_POST['homepage_settings']);
        }
    }

    public function enqueue_assets($hook)
    {
        global $post;
        if (($hook === 'post.php' || $hook === 'post-new.php') && $post->post_type === 'page') {
            wp_enqueue_media();

            wp_enqueue_script(
                'homepage-meta-js',
                get_template_directory_uri() . '/assets/js/homepage.js',
                ['jquery'],
                filemtime(get_template_directory() . '/assets/js/homepage.js'),
                true
            );
        }
    }
}
