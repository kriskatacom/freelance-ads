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

    <section class="container mx-auto py-10 space-y-10">
        <div class="max-w-2xl mx-auto space-y-5">
            <h2 class="text-3xl font-semibold text-center">Категории</h2>
            <p class="text-lg text-gray-700 text-center max-sm:px-5">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolorem consectetur, at explicabo excepturi vel dolorum! Ipsam, recusandae maiores</p>
        </div>
        <ul class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <li>
                <a href="#" class="bg-white space-y-2 block p-5 text-center rounded shadow-[0_0_4px_rgba(0,0,0,0.25)] hover:shadow-[0_0_20px_rgba(0,0,0,0.35)] hover:-translate-y-2 duration-300">
                    <h3 class="text-xl font-semibold">Уеб разработка</h3>
                    <p class="text-lg">Проекти, свързани със създаване на сайтове, онлайн магазини и уеб приложения.</p>
                </a>
            </li>
            <li>
                <a href="#" class="bg-white space-y-2 block p-5 text-center rounded shadow-[0_0_4px_rgba(0,0,0,0.25)] hover:shadow-[0_0_20px_rgba(0,0,0,0.35)] hover:-translate-y-2 duration-300">
                    <h3 class="text-xl font-semibold">Дизайн и креатив</h3>
                    <p class="text-lg">Графичен, UI/UX и бранд дизайн за уеб и печатни материали.</p>
                </a>
            </li>
            <li>
                <a href="#" class="bg-white space-y-2 block p-5 text-center rounded shadow-[0_0_4px_rgba(0,0,0,0.25)] hover:shadow-[0_0_20px_rgba(0,0,0,0.35)] hover:-translate-y-2 duration-300">
                    <h3 class="text-xl font-semibold">Маркетинг и съдържание</h3>
                    <p class="text-lg">Копирайтинг, SEO и стратегии за социални мрежи.</p>
                </a>
            </li>
            <li>
                <a href="#" class="bg-white space-y-2 block p-5 text-center rounded shadow-[0_0_4px_rgba(0,0,0,0.25)] hover:shadow-[0_0_20px_rgba(0,0,0,0.35)] hover:-translate-y-2 duration-300">
                    <h3 class="text-xl font-semibold">Видео и анимация</h3>
                    <p class="text-lg">Видео монтаж, анимация и визуални ефекти.</p>
                </a>
            </li>
            <li>
                <a href="#" class="bg-white space-y-2 block p-5 text-center rounded shadow-[0_0_4px_rgba(0,0,0,0.25)] hover:shadow-[0_0_20px_rgba(0,0,0,0.35)] hover:-translate-y-2 duration-300">
                    <h3 class="text-xl font-semibold">ИТ поддръжка</h3>
                    <p class="text-lg">Техническа помощ, тестване и киберсигурност.</p>
                </a>
            </li>
        </ul>
    </section>
</main>

<?php get_footer(); ?>
