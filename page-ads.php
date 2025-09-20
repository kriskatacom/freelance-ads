<?php
/* Template Name: All Ads */

get_header();
?>

<section class="container mx-auto py-10 space-y-10">
    <div class="max-w-2xl mx-auto space-y-5">
        <h2 class="text-3xl font-semibold text-center">All Ads</h2>
        <p class="text-lg text-gray-700 text-center max-sm:px-5">
            Browse all available freelance projects.
        </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 max-sm:px-5">
        <?php
        $ads_query = new WP_Query([
            'post_type' => 'ad',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        if ($ads_query->have_posts()):
            while ($ads_query->have_posts()):
                $ads_query->the_post();
                $price = get_post_meta(get_the_ID(), '_ad_price', true);
                $location = get_post_meta(get_the_ID(), '_ad_location', true);
                $skills = get_post_meta(get_the_ID(), '_ad_skills', true);
                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium'); // Featured Image
                ?>
                <a href="<?php the_permalink(); ?>"
                    class="bg-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-2 transform transition-all duration-300 overflow-hidden">
                    <?php if ($thumbnail): ?>
                        <div class="h-48 w-full overflow-hidden">
                            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>"
                                class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>

                    <div class="p-5 space-y-2 text-left">
                        <h3 class="text-xl font-semibold"><?php the_title(); ?></h3>
                        <p class="text-gray-600 text-sm"><?php the_excerpt(); ?></p>

                        <div class="flex flex-wrap gap-2 text-gray-500 text-sm mt-2">
                            <?php if ($price): ?>
                                <span class="bg-gray-100 px-2 py-1 rounded">Price: <?php echo esc_html($price); ?></span>
                            <?php endif; ?>
                            <?php if ($location): ?>
                                <span class="bg-gray-100 px-2 py-1 rounded">Location: <?php echo esc_html($location); ?></span>
                            <?php endif; ?>
                            <?php if ($skills): ?>
                                <span class="bg-gray-100 px-2 py-1 rounded">Skills: <?php echo esc_html($skills); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <?php
            endwhile;
            wp_reset_postdata();
        else:
            echo '<p class="text-center text-gray-500">No ads found.</p>';
        endif;
        ?>
    </div>
</section>

<?php get_footer(); ?>