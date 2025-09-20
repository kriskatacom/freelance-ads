<header class="site-header">
    <div class="container site-branding">
        <?php if (function_exists('the_custom_logo') && has_custom_logo()): ?>
            <div class="site-logo"><?php the_custom_logo(); ?></div>
        <?php endif; ?>
        <div class="site-title-description">
            <h1 class="site-title"><?php bloginfo('name'); ?></h1>
            <p class="site-description">
                <?php
                echo __('Lightweight, accessible, and extendable starter for any WordPress theme. Includes base styles, variables, and responsive rules.', 'freelance-ads');
                ?>
            </p>
        </div>
    </div>

    <nav class="main-navigation">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'menu',
        ));
        ?>
    </nav>
</header>
