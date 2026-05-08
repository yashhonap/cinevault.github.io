<?php
/**
 * CineVault Affiliate theme functions.
 *
 * @package CineVault
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CINEVAULT_VERSION', '1.0.0');

function cinevault_setup(): void
{
    load_theme_textdomain('cinevault', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('automatic-feed-links');
    add_theme_support('responsive-embeds');
    add_image_size('cinevault-card', 760, 480, true);

    register_nav_menus(
        array(
            'primary' => __('Primary Menu', 'cinevault'),
            'footer' => __('Footer Menu', 'cinevault'),
        )
    );
}
add_action('after_setup_theme', 'cinevault_setup');

function cinevault_assets(): void
{
    wp_enqueue_style(
        'cinevault-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Playfair+Display:wght@700;800&display=swap',
        array(),
        null
    );

    wp_enqueue_style('cinevault-style', get_stylesheet_uri(), array('cinevault-fonts'), CINEVAULT_VERSION);

    wp_enqueue_script(
        'cinevault-theme',
        get_template_directory_uri() . '/assets/js/theme.js',
        array(),
        CINEVAULT_VERSION,
        true
    );

    wp_localize_script(
        'cinevault-theme',
        'cinevaultSettings',
        array(
            'isLoggedIn' => is_user_logged_in(),
            'loginUrl' => wp_login_url(),
            'registerUrl' => wp_registration_url(),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'favoritesNonce' => wp_create_nonce('cinevault_favorites'),
            'favoritesLabel' => __('Favorites', 'cinevault'),
        )
    );
}
add_action('wp_enqueue_scripts', 'cinevault_assets');

function cinevault_fallback_menu(): void
{
    ?>
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'cinevault'); ?></a></li>
        <li><a href="<?php echo esc_url(get_post_type_archive_link('recommendation')); ?>"><?php esc_html_e('Recommendations', 'cinevault'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#plans')); ?>"><?php esc_html_e('Plans', 'cinevault'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'cinevault'); ?></a></li>
    </ul>
    <?php
}

function cinevault_register_recommendation_type(): void
{
    $labels = array(
        'name' => __('Recommendations', 'cinevault'),
        'singular_name' => __('Recommendation', 'cinevault'),
        'add_new_item' => __('Add New Recommendation', 'cinevault'),
        'edit_item' => __('Edit Recommendation', 'cinevault'),
        'new_item' => __('New Recommendation', 'cinevault'),
        'view_item' => __('View Recommendation', 'cinevault'),
        'search_items' => __('Search Recommendations', 'cinevault'),
        'not_found' => __('No recommendations found', 'cinevault'),
    );

    register_post_type(
        'recommendation',
        array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-video-alt3',
            'rewrite' => array('slug' => 'recommendations'),
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments'),
        )
    );

    register_taxonomy(
        'recommendation_genre',
        'recommendation',
        array(
            'label' => __('Genres', 'cinevault'),
            'hierarchical' => true,
            'rewrite' => array('slug' => 'genre'),
            'show_in_rest' => true,
        )
    );

    register_taxonomy(
        'recommendation_platform',
        'recommendation',
        array(
            'label' => __('Platforms', 'cinevault'),
            'hierarchical' => true,
            'rewrite' => array('slug' => 'platform'),
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'cinevault_register_recommendation_type');

function cinevault_add_recommendation_meta_box(): void
{
    add_meta_box(
        'cinevault_recommendation_details',
        __('Affiliate Recommendation Details', 'cinevault'),
        'cinevault_recommendation_meta_box',
        'recommendation',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'cinevault_add_recommendation_meta_box');

function cinevault_recommendation_meta_box(WP_Post $post): void
{
    wp_nonce_field('cinevault_save_recommendation_meta', 'cinevault_recommendation_nonce');

    $fields = array(
        '_cinevault_affiliate_url' => __('Affiliate or watch URL', 'cinevault'),
        '_cinevault_type' => __('Type, e.g. Movie or Web Series', 'cinevault'),
        '_cinevault_platform' => __('Main platform', 'cinevault'),
        '_cinevault_year' => __('Release year', 'cinevault'),
        '_cinevault_rating' => __('Curator rating, e.g. 9.1', 'cinevault'),
        '_cinevault_runtime' => __('Runtime or seasons', 'cinevault'),
    );

    echo '<div class="cinevault-admin-grid">';
    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, $key, true);
        printf(
            '<p><label for="%1$s"><strong>%2$s</strong></label><input id="%1$s" name="%1$s" type="text" value="%3$s" class="widefat" /></p>',
            esc_attr($key),
            esc_html($label),
            esc_attr($value)
        );
    }
    echo '</div>';
}

function cinevault_save_recommendation_meta(int $post_id): void
{
    if (!isset($_POST['cinevault_recommendation_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cinevault_recommendation_nonce'])), 'cinevault_save_recommendation_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        '_cinevault_affiliate_url' => 'esc_url_raw',
        '_cinevault_type' => 'sanitize_text_field',
        '_cinevault_platform' => 'sanitize_text_field',
        '_cinevault_year' => 'sanitize_text_field',
        '_cinevault_rating' => 'sanitize_text_field',
        '_cinevault_runtime' => 'sanitize_text_field',
    );

    foreach ($fields as $key => $callback) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, call_user_func($callback, wp_unslash($_POST[$key])));
        }
    }
}
add_action('save_post_recommendation', 'cinevault_save_recommendation_meta');

function cinevault_sanitize_favorite_item(array $item): array
{
    return array(
        'id' => isset($item['id']) ? sanitize_text_field((string) $item['id']) : '',
        'title' => isset($item['title']) ? sanitize_text_field((string) $item['title']) : '',
        'url' => isset($item['url']) ? esc_url_raw((string) $item['url']) : '',
        'affiliate' => isset($item['affiliate']) ? esc_url_raw((string) $item['affiliate']) : '',
        'image' => isset($item['image']) ? esc_url_raw((string) $item['image']) : '',
        'meta' => isset($item['meta']) ? sanitize_text_field((string) $item['meta']) : '',
    );
}

function cinevault_load_favorites(): void
{
    check_ajax_referer('cinevault_favorites', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('Sign in to sync favorites.', 'cinevault')), 401);
    }

    $favorites = get_user_meta(get_current_user_id(), 'cinevault_favorites', true);
    wp_send_json_success(is_array($favorites) ? array_values($favorites) : array());
}
add_action('wp_ajax_cinevault_load_favorites', 'cinevault_load_favorites');

function cinevault_save_favorites(): void
{
    check_ajax_referer('cinevault_favorites', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('Sign in to sync favorites.', 'cinevault')), 401);
    }

    $raw = isset($_POST['favorites']) ? wp_unslash($_POST['favorites']) : '[]';
    $decoded = json_decode((string) $raw, true);
    $favorites = array();

    if (is_array($decoded)) {
        foreach ($decoded as $item) {
            if (is_array($item)) {
                $clean = cinevault_sanitize_favorite_item($item);
                if ($clean['id'] && $clean['title']) {
                    $favorites[] = $clean;
                }
            }
        }
    }

    update_user_meta(get_current_user_id(), 'cinevault_favorites', array_slice($favorites, 0, 100));
    wp_send_json_success(array('favorites' => $favorites));
}
add_action('wp_ajax_cinevault_save_favorites', 'cinevault_save_favorites');

function cinevault_meta(int $post_id, string $key, string $fallback = ''): string
{
    $value = get_post_meta($post_id, $key, true);
    return $value ? (string) $value : $fallback;
}

function cinevault_fallback_image(int $index = 1): string
{
    $images = array(
        get_template_directory_uri() . '/assets/images/poster-aurora.svg',
        get_template_directory_uri() . '/assets/images/poster-neon.svg',
        get_template_directory_uri() . '/assets/images/poster-noir.svg',
    );

    return $images[($index - 1) % count($images)];
}

function cinevault_card_data(int $post_id, int $index = 1): array
{
    $affiliate = cinevault_meta($post_id, '_cinevault_affiliate_url', get_permalink($post_id));
    $image = get_the_post_thumbnail_url($post_id, 'cinevault-card') ?: cinevault_fallback_image($index);

    return array(
        'id' => (string) $post_id,
        'title' => get_the_title($post_id),
        'url' => get_permalink($post_id),
        'affiliate' => $affiliate,
        'image' => $image,
        'type' => cinevault_meta($post_id, '_cinevault_type', __('Editor Pick', 'cinevault')),
        'platform' => cinevault_meta($post_id, '_cinevault_platform', __('Streaming', 'cinevault')),
        'year' => cinevault_meta($post_id, '_cinevault_year', get_the_date('Y', $post_id)),
        'rating' => cinevault_meta($post_id, '_cinevault_rating', '9.0'),
        'runtime' => cinevault_meta($post_id, '_cinevault_runtime', ''),
    );
}

function cinevault_recommendation_card(?WP_Post $post = null, int $index = 1): void
{
    $post = $post ?: get_post();

    if (!$post instanceof WP_Post) {
        return;
    }

    $data = cinevault_card_data($post->ID, $index);
    $excerpt = has_excerpt($post) ? get_the_excerpt($post) : wp_trim_words(wp_strip_all_tags($post->post_content), 20);
    ?>
    <article class="recommendation-card">
        <a class="poster" href="<?php echo esc_url($data['url']); ?>" aria-label="<?php echo esc_attr($data['title']); ?>">
            <img src="<?php echo esc_url($data['image']); ?>" alt="<?php echo esc_attr($data['title']); ?>">
            <span class="type-chip"><?php echo esc_html($data['type']); ?></span>
            <span class="rating-chip"><?php echo esc_html($data['rating']); ?></span>
        </a>
        <div class="card-body">
            <div class="card-meta">
                <span><?php echo esc_html($data['platform']); ?></span>
                <span><?php echo esc_html($data['year']); ?></span>
                <?php if ($data['runtime']) : ?>
                    <span><?php echo esc_html($data['runtime']); ?></span>
                <?php endif; ?>
            </div>
            <h3 class="card-title"><a href="<?php echo esc_url($data['url']); ?>"><?php echo esc_html($data['title']); ?></a></h3>
            <p class="card-excerpt"><?php echo esc_html($excerpt); ?></p>
            <div class="card-actions">
                <a class="button" href="<?php echo esc_url($data['affiliate']); ?>" target="_blank" rel="nofollow sponsored noopener">
                    <?php esc_html_e('Open Movie', 'cinevault'); ?>
                </a>
                <button
                    class="icon-button favorite-toggle js-favorite-toggle"
                    type="button"
                    aria-label="<?php esc_attr_e('Add to favorites', 'cinevault'); ?>"
                    data-id="<?php echo esc_attr($data['id']); ?>"
                    data-title="<?php echo esc_attr($data['title']); ?>"
                    data-url="<?php echo esc_url($data['url']); ?>"
                    data-affiliate="<?php echo esc_url($data['affiliate']); ?>"
                    data-image="<?php echo esc_url($data['image']); ?>"
                    data-meta="<?php echo esc_attr(trim($data['platform'] . ' / ' . $data['year'])); ?>"
                >
                    <span aria-hidden="true">&#9825;</span>
                </button>
            </div>
        </div>
    </article>
    <?php
}

function cinevault_demo_recommendations(): array
{
    return array(
        array(
            'title' => __('Midnight Signal', 'cinevault'),
            'type' => __('Web Series', 'cinevault'),
            'platform' => __('Netflix', 'cinevault'),
            'year' => '2026',
            'rating' => '9.3',
            'image' => cinevault_fallback_image(1),
            'excerpt' => __('A tense mystery series for viewers who like sharp reveals, premium pacing, and late-night binge energy.', 'cinevault'),
        ),
        array(
            'title' => __('The Velvet Case', 'cinevault'),
            'type' => __('Movie', 'cinevault'),
            'platform' => __('Prime Video', 'cinevault'),
            'year' => '2025',
            'rating' => '8.8',
            'image' => cinevault_fallback_image(2),
            'excerpt' => __('A stylish crime thriller with a glamorous surface, a bitter core, and strong affiliate conversion potential.', 'cinevault'),
        ),
        array(
            'title' => __('Orbit Nine', 'cinevault'),
            'type' => __('Movie', 'cinevault'),
            'platform' => __('Disney+', 'cinevault'),
            'year' => '2026',
            'rating' => '9.0',
            'image' => cinevault_fallback_image(3),
            'excerpt' => __('Premium science fiction with big-screen visuals, human stakes, and an easy recommendation hook.', 'cinevault'),
        ),
    );
}

function cinevault_demo_card(array $item, int $index): void
{
    $id = 'demo-' . $index;
    $url = home_url('/recommendations/');
    ?>
    <article class="recommendation-card">
        <a class="poster" href="<?php echo esc_url($url); ?>">
            <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
            <span class="type-chip"><?php echo esc_html($item['type']); ?></span>
            <span class="rating-chip"><?php echo esc_html($item['rating']); ?></span>
        </a>
        <div class="card-body">
            <div class="card-meta">
                <span><?php echo esc_html($item['platform']); ?></span>
                <span><?php echo esc_html($item['year']); ?></span>
            </div>
            <h3 class="card-title"><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($item['title']); ?></a></h3>
            <p class="card-excerpt"><?php echo esc_html($item['excerpt']); ?></p>
            <div class="card-actions">
                <a class="button" href="<?php echo esc_url($url); ?>"><?php esc_html_e('Open Movie', 'cinevault'); ?></a>
                <button
                    class="icon-button favorite-toggle js-favorite-toggle"
                    type="button"
                    aria-label="<?php esc_attr_e('Add to favorites', 'cinevault'); ?>"
                    data-id="<?php echo esc_attr($id); ?>"
                    data-title="<?php echo esc_attr($item['title']); ?>"
                    data-url="<?php echo esc_url($url); ?>"
                    data-affiliate="<?php echo esc_url($url); ?>"
                    data-image="<?php echo esc_url($item['image']); ?>"
                    data-meta="<?php echo esc_attr($item['platform'] . ' / ' . $item['year']); ?>"
                >
                    <span aria-hidden="true">&#9825;</span>
                </button>
            </div>
        </div>
    </article>
    <?php
}
