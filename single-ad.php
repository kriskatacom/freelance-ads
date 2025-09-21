<?php get_header(); ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php if (have_posts()):
        while (have_posts()):
            the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white shadow-lg rounded-lg overflow-hidden'); ?>>
                <?php if (has_post_thumbnail()): ?>
                    <div class="w-full h-64 sm:h-96 overflow-hidden">
                        <?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover']); ?>
                    </div>
                <?php endif; ?>

                <div class="p-6 sm:p-10">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4"><?php the_title(); ?></h1>
                    <?php
                        $price = get_post_meta(get_the_ID(), '_ad_price', true);
                        $location = get_post_meta(get_the_ID(), '_ad_location', true);
                        $skills = get_post_meta(get_the_ID(), '_ad_skills', true);
                        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    ?>
                    <div class="flex flex-wrap gap-2 text-gray-500">
                        <?php if ($price): ?>
                            <span class="bg-gray-100 px-2 py-1 rounded flex items-center gap-1">
                                <i class="fa-solid fa-hand-holding-dollar text-black"></i>
                                <?php echo esc_html($price); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($location): ?>
                            <span class="bg-gray-100 px-2 py-1 rounded flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-black"></i>
                                <span class="line-clamp-1"><?php echo esc_html($location); ?></span>
                            </span>
                        <?php endif; ?>

                        <?php if ($skills): ?>
                            <span class="bg-gray-100 px-2 py-1 rounded flex items-center gap-1">
                                <i class="fa-solid fa-brain text-black"></i>
                                <span class="line-clamp-1"><?php echo $skills; ?></span>
                            </span>
                        <?php endif; ?>
                    </div>

                    <p class="text-gray-500 text-center mt-5">
                        Публикувана преди <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?>
                    </p>

                    <div class="flex flex-wrap gap-4 mb-6 text-gray-700 text-sm">
                        <?php if ($location = get_post_meta(get_the_ID(), 'location', true)): ?>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-gray-500" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 6a7 7 0 1114 0 7 7 0 01-14 0zm7-4a4 4 0 100 8 4 4 0 000-8z"
                                        clip-rule="evenodd" />
                                </svg><?php echo esc_html($location); ?></span>
                        <?php endif; ?>

                        <?php if ($price = get_post_meta(get_the_ID(), 'price', true)): ?>
                            <span class="font-semibold"><?php echo esc_html($price); ?> лв.</span>
                        <?php endif; ?>
                    </div>

                    <div class="edited-content">
                        <?php echo wp_kses_post(the_content()); ?>
                    </div>

                    <?php
                    $buttons = get_post_meta(get_the_ID(), '_project_buttons', true);
                    if (!empty($buttons) && is_array($buttons)): ?>
                        <div class="flex flex-wrap gap-3 mb-6">
                            <?php foreach ($buttons as $btn): ?>
                                <a href="<?php echo esc_url($btn['url']); ?>"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                    <?php echo esc_html($btn['text']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <p class="text-gray-600">
                        <a href="<?php echo get_post_type_archive_link('ad'); ?>" class="hover:underline">
                            <span>Всички проекти</span>
                            <i class="fa-solid fa-left-arrow"></i>
                        </a>
                    </p>
                </div>
            </article>
        <?php endwhile; else: ?>
        <p class="text-center text-gray-500">Няма намерени проекти.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
