<?php
/* Template Name: Ad Categories */
get_header(); 
?>

<section class="container mx-auto py-10 space-y-10">
    <div class="max-w-2xl mx-auto space-y-5">
        <h2 class="text-3xl font-semibold text-center">Всички категории</h2>
        <p class="text-lg text-gray-700 text-center max-sm:px-5">
            Разгледайте всички категории с налични обяви.
        </p>
    </div>

    <?php
    $categories = get_terms([
        'taxonomy' => 'ad_category',
        'hide_empty' => false,
    ]);
    ?>

    <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
        <ul class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <?php foreach ($categories as $category) : ?>
                <li>
                    <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                       class="bg-white space-y-2 block p-5 text-center rounded shadow-[0_0_4px_rgba(0,0,0,0.25)] hover:shadow-[0_0_20px_rgba(0,0,0,0.35)] hover:-translate-y-2 duration-300">
                        <h3 class="text-xl font-semibold"><?php echo esc_html($category->name); ?></h3>
                        <?php if (!empty($category->description)) : ?>
                            <p class="text-gray-700 line-clamp-2 text-lg"><?php echo esc_html($category->description); ?></p>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php get_footer(); ?>
