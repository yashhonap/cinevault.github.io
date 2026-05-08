<?php
/**
 * Single post template.
 *
 * @package CineVault
 */

get_header();
?>

<main id="primary">
    <?php while (have_posts()) : the_post(); ?>
        <header class="page-head">
            <div class="container">
                <p class="eyebrow">
                    <?php echo get_post_type() === 'recommendation' ? esc_html__('Recommendation', 'cinevault') : esc_html__('Review', 'cinevault'); ?>
                </p>
                <h1 class="page-title"><?php the_title(); ?></h1>
                <p class="section-copy"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
            </div>
        </header>

        <section class="content-area">
            <div class="container entry-layout">
                <article <?php post_class('entry-content'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="poster">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <?php the_content(); ?>
                    <?php
                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">',
                            'after' => '</div>',
                        )
                    );
                    ?>
                </article>

                <?php if (get_post_type() === 'recommendation') : ?>
                    <?php
                    $data = cinevault_card_data(get_the_ID(), 1);
                    ?>
                    <aside class="single-panel">
                        <dl>
                            <div>
                                <dt><?php esc_html_e('Type', 'cinevault'); ?></dt>
                                <dd><?php echo esc_html($data['type']); ?></dd>
                            </div>
                            <div>
                                <dt><?php esc_html_e('Platform', 'cinevault'); ?></dt>
                                <dd><?php echo esc_html($data['platform']); ?></dd>
                            </div>
                            <div>
                                <dt><?php esc_html_e('Year', 'cinevault'); ?></dt>
                                <dd><?php echo esc_html($data['year']); ?></dd>
                            </div>
                            <div>
                                <dt><?php esc_html_e('Rating', 'cinevault'); ?></dt>
                                <dd><?php echo esc_html($data['rating']); ?></dd>
                            </div>
                            <?php if ($data['runtime']) : ?>
                                <div>
                                    <dt><?php esc_html_e('Runtime', 'cinevault'); ?></dt>
                                    <dd><?php echo esc_html($data['runtime']); ?></dd>
                                </div>
                            <?php endif; ?>
                        </dl>

                        <a class="button" href="<?php echo esc_url($data['affiliate']); ?>" target="_blank" rel="nofollow sponsored noopener">
                            <?php esc_html_e('Open Movie', 'cinevault'); ?>
                        </a>
                        <button
                            class="button secondary js-favorite-toggle"
                            type="button"
                            data-id="<?php echo esc_attr($data['id']); ?>"
                            data-title="<?php echo esc_attr($data['title']); ?>"
                            data-url="<?php echo esc_url($data['url']); ?>"
                            data-affiliate="<?php echo esc_url($data['affiliate']); ?>"
                            data-image="<?php echo esc_url($data['image']); ?>"
                            data-meta="<?php echo esc_attr(trim($data['platform'] . ' / ' . $data['year'])); ?>"
                        >
                            <?php esc_html_e('Save to Favorites', 'cinevault'); ?>
                        </button>
                    </aside>
                <?php endif; ?>
            </div>
        </section>
    <?php endwhile; ?>
</main>

<?php
get_footer();
