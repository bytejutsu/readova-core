<?php
/**
 * Readova Core - Admin Dashboard Cleanup
 *
 * Optional admin simplification for Readova sites.
 *
 * @package ReadovaCore
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if the active theme is Readova or a Readova child theme.
 */
function readova_core_is_readova_theme_active()
{
    $theme = wp_get_theme();

    $template   = (string) $theme->get_template();
    $stylesheet = (string) $theme->get_stylesheet();

    return ($template === 'readova' || $stylesheet === 'readova');
}

/**
 * Should we apply admin cleanup?
 * Default is OFF (option must be enabled).
 */
function readova_core_should_cleanup_admin()
{
    if (!is_admin() || !current_user_can('manage_options')) {
        return false;
    }

    if (!readova_core_is_readova_theme_active()) {
        return false;
    }

    $enabled = (bool) get_option('readova_core_simplified_admin', false);

    /**
     * Allow developers to override behavior.
     * Returning true forces enable, false forces disable.
     */
    $enabled = (bool) apply_filters('readova_core_simplified_admin_enabled', $enabled);

    return $enabled;
}

/**
 * Clean up admin menu.
 */
function readova_core_cleanup_admin_menu()
{
    if (!readova_core_should_cleanup_admin()) {
        return;
    }

    remove_menu_page('edit.php'); // Posts
    remove_menu_page('edit-comments.php'); // Comments
    remove_menu_page('edit.php?post_type=page'); // Pages
}
add_action('admin_menu', 'readova_core_cleanup_admin_menu', 999);

/**
 * Disable comments and trackbacks for selected post types only.
 */
function readova_core_disable_comments_support()
{
    if (!readova_core_should_cleanup_admin()) {
        return;
    }

    $post_types = array(
        'post',
        'page',
        defined('READOVA_CORE_CPT_CHAPTER') ? READOVA_CORE_CPT_CHAPTER : 'readova_chapter',
    );

    $post_types = apply_filters('readova_core_disable_comments_post_types', $post_types);

    foreach ($post_types as $post_type) {
        if (!post_type_exists($post_type)) {
            continue;
        }

        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
        }

        if (post_type_supports($post_type, 'trackbacks')) {
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'readova_core_disable_comments_support', 100);

/**
 * Remove comments from admin bar.
 */
function readova_core_remove_comments_admin_bar($wp_admin_bar)
{
    if (!readova_core_should_cleanup_admin()) {
        return;
    }

    $wp_admin_bar->remove_node('comments');
}
add_action('admin_bar_menu', 'readova_core_remove_comments_admin_bar', 999);

/**
 * Redirect attempts to access comment pages.
 */
function readova_core_disable_comments_admin_redirect()
{
    if (!readova_core_should_cleanup_admin()) {
        return;
    }

    global $pagenow; // core global

    if ($pagenow === 'edit-comments.php' || $pagenow === 'comment.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
}
add_action('admin_init', 'readova_core_disable_comments_admin_redirect');

/**
 * Optional: Clean up dashboard widgets.
 */
function readova_core_cleanup_dashboard_widgets()
{
    if (!readova_core_should_cleanup_admin()) {
        return;
    }

    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'readova_core_cleanup_dashboard_widgets');
