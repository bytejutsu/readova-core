<?php
/**
 * Classic Editor customizations for Readova Core.
 *
 * @package Readova_Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove "Add Media" button for the 'readova chapter' CPT in Classic Editor.
 */
function readova_core_remove_add_media_button_for_chapters() {
    $screen = get_current_screen();

    if (!$screen || $screen->post_type !== READOVA_CORE_CPT_CHAPTER) {
        return;
    }

    global $post, $typenow;

    if (!isset($post) || $typenow !== READOVA_CORE_CPT_CHAPTER) {
        return;
    }

    remove_action('media_buttons', 'media_buttons');
}
add_action('admin_head', 'readova_core_remove_add_media_button_for_chapters');

/**
 * Restrict TinyMCE formatting to only 'Paragraph' for 'chapter' CPT.
 *
 * @param array $init TinyMCE init settings.
 * @return array
 */
function readova_core_limit_tinymce_for_chapters($init) {
    $screen = get_current_screen();

    if ($screen && $screen->post_type === READOVA_CORE_CPT_CHAPTER) {
        $init['block_formats'] = 'Paragraph=p;';
    }

    return $init;
}
add_filter('tiny_mce_before_init', 'readova_core_limit_tinymce_for_chapters');
