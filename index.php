<?php
/**
 * Main blog index.
 *
 * @package CineVault
 */

get_header();
?>

<main id="primary">
    <header class="page-head">
        <div class="container">
            <p class="eyebrow"><?php esc_html_e('Editorial', 'cinevault'); ?></p>
            <h1 class="page-title"><?php single_post_title('', true); ?></h1>
        </div>
    </header>

    <section class="content-area">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="blog-list">
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('post-card'); ?>>
                            <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time>
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24)); ?></p>
                            <a href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'cinevault'); ?></a>
                        </article>
                    <?php endwhile; ?>
                </div>
                <?php the_posts_pagination(); ?>
            <?php else : ?>
                <p class="notice"><?php esc_html_e('No posts yet. Add your first affiliate blog from the WordPress dashboard.', 'cinevault'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
