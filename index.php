<?php get_header(); ?>

<?php
    $page_id = get_option('page_on_front');
    $settings = get_post_meta($page_id, '_homepage_settings', true);

    $title = $settings['title'] ?? 'Section Title';
    $description = $settings['description'] ?? 'Section Description';
    $buttons = $settings['buttons'] ?? [];
?>

<main>
    <section class="home-hero-background text-white min-h-[600px] lg:min-h-[768px] flex items-center">
        <div class="container mx-auto px-6 md:px-12 lg:px-24">
            <div class="text-center md:text-left max-w-2xl">
                <h1 class="text-4xl md:text-6xl font-bold mb-6"><?php echo esc_attr($title); ?></h1>
                <p class="text-lg md:text-xl mb-8 text-red-100">
                    <?php echo wp_kses_post($description); ?>
                </p>
                <div class="flex max-lg:flex-col items-center gap-5">
                    <?php foreach ($buttons as $btn): ?>

                        <a href="<?php echo esc_url($btn['url']); ?>"
                            class="<?php echo esc_attr($btn['classes'] ?? 'inline-block bg-white transition-all text-black text-xl font-semibold px-8 py-4 rounded-lg shadow-lg hover:shadow-xl active:scale-95 hover:-translate-y-1'); ?>"
                            title="<?php echo esc_attr($btn['title'] ?? ''); ?>"
                            rel="<?php echo esc_attr($btn['rel'] ?? ''); ?>"
                            <?php echo isset($btn['target']) && $btn['target'] === '_blank' ? 'target="_blank"' : ''; ?>
                        >
                            <?php echo esc_html($btn['text']); ?>
                        </a>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
