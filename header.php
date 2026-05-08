<?php
/**
 * Site header.
 *
 * @package CineVault
 */

if (!defined('ABSPATH')) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="site-shell">
    <header class="site-header">
        <div class="container nav-wrap">
            <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php bloginfo('name'); ?>">
                <span class="brand-mark" aria-hidden="true">CV</span>
                <span class="brand-name"><?php bloginfo('name'); ?></span>
            </a>

            <nav class="site-nav js-site-nav" aria-label="<?php esc_attr_e('Primary navigation', 'cinevault'); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'fallback_cb' => 'cinevault_fallback_menu',
                    )
                );
                ?>
            </nav>

            <div class="header-actions">
                <button class="icon-button js-favorites-open" type="button" aria-label="<?php esc_attr_e('Open favorites', 'cinevault'); ?>">
                    <span aria-hidden="true">&#9825;</span>
                    <span class="badge-count js-favorites-count">0</span>
                </button>

                <?php if (is_user_logged_in()) : ?>
                    <a class="button secondary" href="<?php echo esc_url(admin_url('profile.php')); ?>"><?php esc_html_e('Account', 'cinevault'); ?></a>
                <?php else : ?>
                    <button class="button secondary js-auth-open" type="button"><?php esc_html_e('Sign in', 'cinevault'); ?></button>
                <?php endif; ?>

                <button class="menu-toggle js-menu-toggle" type="button" aria-label="<?php esc_attr_e('Toggle menu', 'cinevault'); ?>" aria-expanded="false">
                    <span aria-hidden="true">&#9776;</span>
                </button>
            </div>
        </div>
    </header>
