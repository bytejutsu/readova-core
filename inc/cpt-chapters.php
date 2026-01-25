<?php
/**
 * Register the "Chapter" custom post type.
 */

if (!defined('ABSPATH')) exit;

function readova_core_register_chapter_cpt()
{
    $labels = [
        'name' => __('Chapters', 'readova-core'),
        'singular_name' => __('Chapter', 'readova-core'),
        'add_new_item' => __('Add New Chapter', 'readova-core'),
        'edit_item' => __('Edit Chapter', 'readova-core'),
        'new_item' => __('New Chapter', 'readova-core'),
        'view_item' => __('View Chapter', 'readova-core'),
        'all_items' => __('All Chapters', 'readova-core'),
    ];

    $args = [
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-book-alt',
        'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'rewrite' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
    ];

    register_post_type('chapter', $args);
}
add_action('init', 'readova_core_register_chapter_cpt');

// Disable Gutenberg for 'chapter'
add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) {
    if ($post_type === 'chapter') {
        return false;
    }
    return $use_block_editor;
}, 10, 2);

// Add classic editor support
add_action('init', function () {
    add_post_type_support('chapter', 'editor');
});

// Hide media button for chapters
add_action('admin_head', function () {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'chapter') {
        remove_action('media_buttons', 'media_buttons');
    }
});

// Redirect chapter frontend access
add_action('template_redirect', function () {
    if (is_singular('chapter')) {
        wp_redirect(home_url());
        exit;
    }
});

// Remove "View" and permalink actions
add_filter('post_row_actions', function ($actions, $post) {
    if ($post->post_type === 'chapter') {
        unset($actions['view']);
    }
    return $actions;
}, 10, 2);

add_filter('get_sample_permalink_html', function ($html, $post_id) {
    $post = get_post($post_id);
    if ($post && $post->post_type === 'chapter') {
        return '';
    }
    return $html;
}, 10, 2);
