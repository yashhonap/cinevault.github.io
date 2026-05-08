<?php
/**
 * Front page template.
 *
 * @package CineVault
 */

get_header();
?>

<main id="primary">
    <section class="hero">
        <div class="container hero-inner">
            <p class="eyebrow"><?php esc_html_e('Premium affiliate cinema guide', 'cinevault'); ?></p>
            <h1><?php esc_html_e('Curated movies and series worth opening tonight.', 'cinevault'); ?></h1>
            <p><?php esc_html_e('Publish sharp recommendation blogs, add affiliate watch links, grow paid members, and let visitors keep a personal watchlist from a sleek dark interface.', 'cinevault'); ?></p>
            <div class="hero-actions">
                <a class="button" href="<?php echo esc_url(get_post_type_archive_link('recommendation')); ?>"><?php esc_html_e('Explore Picks', 'cinevault'); ?></a>
                <a class="button secondary" href="#plans"><?php esc_html_e('View Plans', 'cinevault'); ?></a>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="trust-strip">
            <div class="trust-item">
                <strong><?php esc_html_e('Affiliate', 'cinevault'); ?></strong>
                <span><?php esc_html_e('Sponsored outbound links ready for every pick.', 'cinevault'); ?></span>
            </div>
            <div class="trust-item">
                <strong><?php esc_html_e('Members', 'cinevault'); ?></strong>
                <span><?php esc_html_e('Login, registration, and subscription prompts built in.', 'cinevault'); ?></span>
            </div>
            <div class="trust-item">
                <strong><?php esc_html_e('Watchlist', 'cinevault'); ?></strong>
                <span><?php esc_html_e('Favorites drawer saves picks in the browser.', 'cinevault'); ?></span>
            </div>
            <div class="trust-item">
                <strong><?php esc_html_e('Editorial', 'cinevault'); ?></strong>
                <span><?php esc_html_e('Built for reviews, comparisons, lists, and guides.', 'cinevault'); ?></span>
            </div>
        </div>
    </div>

    <section class="section" id="recommendations">
        <div class="container">
            <div class="section-header">
                <div>
                    <p class="section-kicker"><?php esc_html_e('Featured recommendations', 'cinevault'); ?></p>
                    <h2 class="section-title"><?php esc_html_e('High-converting picks', 'cinevault'); ?></h2>
                    <p class="section-copy"><?php esc_html_e('Each card supports rating, platform, year, affiliate URL, and a favorite button so visitors can save before they stream.', 'cinevault'); ?></p>
                </div>
                <div class="filter-row" aria-label="<?php esc_attr_e('Popular categories', 'cinevault'); ?>">
                    <a class="filter-pill" href="<?php echo esc_url(get_post_type_archive_link('recommendation')); ?>"><?php esc_html_e('Movies', 'cinevault'); ?></a>
                    <a class="filter-pill" href="<?php echo esc_url(get_post_type_archive_link('recommendation')); ?>"><?php esc_html_e('Web Series', 'cinevault'); ?></a>
                    <a class="filter-pill" href="<?php echo esc_url(get_post_type_archive_link('recommendation')); ?>"><?php esc_html_e('Thrillers', 'cinevault'); ?></a>
                </div>
            </div>

            <div class="recommendation-grid">
                <?php
                $recommendations = new WP_Query(
                    array(
                        'post_type' => 'recommendation',
                        'posts_per_page' => 6,
                    )
                );

                if ($recommendations->have_posts()) :
                    $index = 1;
                    while ($recommendations->have_posts()) :
                        $recommendations->the_post();
                        cinevault_recommendation_card(get_post(), $index);
                        $index++;
                    endwhile;
                    wp_reset_postdata();
                else :
                    foreach (cinevault_demo_recommendations() as $index => $item) {
                        cinevault_demo_card($item, $index + 1);
                    }
                endif;
                ?>
            </div>
        </div>
    </section>

    <section class="section plans" id="plans">
        <div class="container">
            <div class="section-header">
                <div>
                    <p class="section-kicker"><?php esc_html_e('Subscription plans', 'cinevault'); ?></p>
                    <h2 class="section-title"><?php esc_html_e('Turn loyal readers into members', 'cinevault'); ?></h2>
                </div>
                <p class="section-copy"><?php esc_html_e('Use these as landing sections for a membership plugin like WooCommerce Memberships, MemberPress, Paid Memberships Pro, or SureMembers.', 'cinevault'); ?></p>
            </div>

            <div class="pricing-grid">
                <article class="price-card">
                    <span class="plan-label"><?php esc_html_e('Starter', 'cinevault'); ?></span>
                    <h3><?php esc_html_e('Free Watcher', 'cinevault'); ?></h3>
                    <div class="price"><strong><?php esc_html_e('INR 0', 'cinevault'); ?></strong><span><?php esc_html_e('/mo', 'cinevault'); ?></span></div>
                    <ul class="feature-list">
                        <li><?php esc_html_e('Public recommendation blogs', 'cinevault'); ?></li>
                        <li><?php esc_html_e('Browser favorites panel', 'cinevault'); ?></li>
                        <li><?php esc_html_e('Weekly free picks', 'cinevault'); ?></li>
                    </ul>
                    <button class="button secondary js-auth-open" type="button"><?php esc_html_e('Join Free', 'cinevault'); ?></button>
                </article>

                <article class="price-card featured">
                    <span class="plan-label"><?php esc_html_e('Most popular', 'cinevault'); ?></span>
                    <h3><?php esc_html_e('Premium Curator', 'cinevault'); ?></h3>
                    <div class="price"><strong><?php esc_html_e('INR 299', 'cinevault'); ?></strong><span><?php esc_html_e('/mo', 'cinevault'); ?></span></div>
                    <ul class="feature-list">
                        <li><?php esc_html_e('Early access watchlists', 'cinevault'); ?></li>
                        <li><?php esc_html_e('Members-only rankings', 'cinevault'); ?></li>
                        <li><?php esc_html_e('Ad-light reading experience', 'cinevault'); ?></li>
                    </ul>
                    <button class="button js-auth-open" type="button"><?php esc_html_e('Start Premium', 'cinevault'); ?></button>
                </article>

                <article class="price-card">
                    <span class="plan-label"><?php esc_html_e('Power fans', 'cinevault'); ?></span>
                    <h3><?php esc_html_e('Family Vault', 'cinevault'); ?></h3>
                    <div class="price"><strong><?php esc_html_e('INR 599', 'cinevault'); ?></strong><span><?php esc_html_e('/mo', 'cinevault'); ?></span></div>
                    <ul class="feature-list">
                        <li><?php esc_html_e('Shared household watchlists', 'cinevault'); ?></li>
                        <li><?php esc_html_e('Monthly binge guides', 'cinevault'); ?></li>
                        <li><?php esc_html_e('Personalized genre drops', 'cinevault'); ?></li>
                    </ul>
                    <button class="button secondary js-auth-open" type="button"><?php esc_html_e('Choose Family', 'cinevault'); ?></button>
                </article>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container affiliate-band">
            <div>
                <p class="section-kicker"><?php esc_html_e('Affiliate workflow', 'cinevault'); ?></p>
                <h2 class="section-title"><?php esc_html_e('From review to watch link in one clean flow', 'cinevault'); ?></h2>
                <p class="section-copy"><?php esc_html_e('Create recommendation posts, add your outbound partner URL, tag the platform, and the theme displays a premium card with a direct Open Movie action.', 'cinevault'); ?></p>
            </div>
            <div class="affiliate-panel">
                <?php foreach (cinevault_demo_recommendations() as $index => $item) : ?>
                    <div class="panel-row">
                        <div class="panel-thumb">
                            <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                        </div>
                        <div>
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['platform'] . ' / ' . $item['type']); ?></p>
                        </div>
                        <a class="button secondary" href="<?php echo esc_url(get_post_type_archive_link('recommendation')); ?>"><?php esc_html_e('Open', 'cinevault'); ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-header">
                <div>
                    <p class="section-kicker"><?php esc_html_e('Latest affiliate blogs', 'cinevault'); ?></p>
                    <h2 class="section-title"><?php esc_html_e('Reviews, lists, and streaming guides', 'cinevault'); ?></h2>
                </div>
                <a class="button secondary" href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/blog/')); ?>"><?php esc_html_e('Read Blog', 'cinevault'); ?></a>
            </div>

            <div class="blog-list">
                <?php
                $posts = new WP_Query(
                    array(
                        'post_type' => 'post',
                        'posts_per_page' => 3,
                    )
                );

                if ($posts->have_posts()) :
                    while ($posts->have_posts()) :
                        $posts->the_post();
                        ?>
                        <article class="post-card">
                            <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                            <a href="<?php the_permalink(); ?>"><?php esc_html_e('Read review', 'cinevault'); ?></a>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    $demo_posts = array(
                        __('10 hidden thriller movies to stream this weekend', 'cinevault'),
                        __('Best web series for fans of slow-burn mysteries', 'cinevault'),
                        __('How to choose streaming affiliate offers that fit your audience', 'cinevault'),
                    );
                    foreach ($demo_posts as $title) :
                        ?>
                        <article class="post-card">
                            <time><?php echo esc_html(date_i18n(get_option('date_format'))); ?></time>
                            <h3><?php echo esc_html($title); ?></h3>
                            <p><?php esc_html_e('Publish posts here to replace this demo content with your own affiliate editorial strategy.', 'cinevault'); ?></p>
                            <a href="<?php echo esc_url(admin_url('post-new.php')); ?>"><?php esc_html_e('Create post', 'cinevault'); ?></a>
                        </article>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
