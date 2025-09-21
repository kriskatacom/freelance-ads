<?php get_header(); 

use Theme\Admin\PolylangStrings;?>

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

                        <a href="<?php echo home_url(esc_url($btn['url'])); ?>"
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

    <?php
        $categories = get_terms([
            'taxonomy' => 'ad_category',
            'hide_empty' => false,
        ]);

        if (!empty($categories) && !is_wp_error($categories)) :
        ?>
        <section class="container mx-auto py-10 space-y-10">
            <div class="max-w-2xl mx-auto space-y-5">
                <h2 class="text-3xl font-semibold text-center">Категории</h2>
                <p class="text-lg text-gray-700 text-center max-sm:px-5">
                    Избери категория, за да видиш наличните проекти.
                </p>
            </div>

            <ul class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 px-5">
                <?php foreach ($categories as $category) : ?>
                    <li>
                        <a href="<?php echo esc_url(get_term_link($category)); ?>" class="bg-white space-y-2 block p-5 text-center rounded shadow-[0_0_4px_rgba(0,0,0,0.25)] hover:shadow-[0_0_20px_rgba(0,0,0,0.35)] hover:-translate-y-2 duration-300">
                            <h3 class="text-xl font-semibold"><?php echo esc_html($category->name); ?></h3>
                            <?php if (!empty($category->description)) : ?>
                                <p class="text-lg text-gray-700 line-clamp-2"><?php echo esc_html($category->description); ?></p>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="text-center">
                <a href="<?= home_url() . "/categories"; ?>" class="inline-block bg-white transition-all text-black md:text-xl font-semibold px-4 md:px-8 py-2 md:py-4 rounded-lg border-2 border-gray-200 active:scale-95 hover:-translate-y-1">
                    <?php echo esc_html(PolylangStrings::get('show_all_categories')); ?>
                </a>
            </div>
        </section>
        <?php endif; ?>
</main>

<?php get_footer(); ?>