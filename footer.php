<?php
/**
 * Site footer.
 *
 * @package CineVault
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
                    <span class="brand-mark" aria-hidden="true">CV</span>
                    <span class="brand-name"><?php bloginfo('name'); ?></span>
                </a>
                <p><?php esc_html_e('Affiliate-first movie and web series recommendations with a premium member experience.', 'cinevault'); ?></p>
            </div>
            <nav class="footer-links" aria-label="<?php esc_attr_e('Footer navigation', 'cinevault'); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'container' => false,
                        'fallback_cb' => false,
                        'depth' => 1,
                    )
                );
                ?>
            </nav>
        </div>
    </footer>
</div>

<div class="auth-modal js-auth-modal" aria-hidden="true">
    <div class="modal-backdrop js-auth-close"></div>
    <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="auth-title">
        <div class="modal-head">
            <h2 id="auth-title"><?php esc_html_e('Member Access', 'cinevault'); ?></h2>
            <button class="close-button js-auth-close" type="button" aria-label="<?php esc_attr_e('Close sign in', 'cinevault'); ?>">&times;</button>
        </div>
        <?php
        wp_login_form(
            array(
                'echo' => true,
                'remember' => true,
                'label_username' => __('Email or username', 'cinevault'),
                'label_password' => __('Password', 'cinevault'),
                'label_log_in' => __('Sign in', 'cinevault'),
                'form_id' => 'cinevault-login-form',
            )
        );
        ?>
        <div class="auth-links">
            <a href="<?php echo esc_url(wp_registration_url()); ?>"><?php esc_html_e('Create account', 'cinevault'); ?></a>
            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Forgot password?', 'cinevault'); ?></a>
        </div>
    </div>
</div>

<aside class="favorites-drawer js-favorites-drawer" aria-hidden="true">
    <div class="drawer-backdrop js-favorites-close"></div>
    <div class="drawer-panel" role="dialog" aria-modal="true" aria-labelledby="favorites-title">
        <div class="drawer-head">
            <h2 id="favorites-title"><?php esc_html_e('My Favorites', 'cinevault'); ?></h2>
            <button class="close-button js-favorites-close" type="button" aria-label="<?php esc_attr_e('Close favorites', 'cinevault'); ?>">&times;</button>
        </div>
        <div class="favorites-list js-favorites-list"></div>
    </div>
</aside>

<?php wp_footer(); ?>
</body>
</html>
