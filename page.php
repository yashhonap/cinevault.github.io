<?php
/**
 * Page template.
 *
 * @package CineVault
 */

get_header();
?>

<main id="primary">
    <?php while (have_posts()) : the_post(); ?>
        <header class="page-head">
            <div class="container">
                <p class="eyebrow"><?php esc_html_e('CineVault', 'cinevault'); ?></p>
                <h1 class="page-title"><?php the_title(); ?></h1>
            </div>
        </header>

        <section class="content-area">
            <div class="container entry-content">
                <?php the_content(); ?>
            </div>
        </section>
    <?php endwhile; ?>
</main>

<?php
get_footer();
