<?php
/**
 * Recommendation archive.
 *
 * @package CineVault
 */

get_header();
?>

<main id="primary">
    <header class="page-head">
        <div class="container">
            <p class="eyebrow"><?php esc_html_e('Watch guide', 'cinevault'); ?></p>
            <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
            <p class="section-copy"><?php esc_html_e('Browse every movie and web series recommendation, then save favorites or jump directly to the streaming affiliate link.', 'cinevault'); ?></p>
        </div>
    </header>

    <section class="content-area">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="recommendation-grid">
                    <?php
                    $index = 1;
                    while (have_posts()) :
                        the_post();
                        cinevault_recommendation_card(get_post(), $index);
                        $index++;
                    endwhile;
                    ?>
                </div>
                <?php the_posts_pagination(); ?>
            <?php else : ?>
                <div class="recommendation-grid">
                    <?php
                    foreach (cinevault_demo_recommendations() as $index => $item) {
                        cinevault_demo_card($item, $index + 1);
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
