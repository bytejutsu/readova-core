<?php
/**
 * Create demo chapters when the plugin is activated.
 */

if (!defined('ABSPATH')) exit;

function readova_core_add_demo_chapters()
{
    if (get_option('readova_demo_chapters_added')) {
        return;
    }

    if (!post_type_exists('chapter')) {
        return;
    }

    $chapters = [
        [
            'title' => 'Introduction',
            'content' => '<p>Welcome to the Readova theme...</p>',
            'filename' => 'demo-chapter1.jpg',
        ],
        [
            'title' => 'The Surfing Chronicles',
            'content' => '<p>Lorem ipsum dolor sit amet...</p>',
            'filename' => 'demo-chapter2.jpg',
        ],
        // ... (add the rest here, unchanged)
    ];

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    foreach ($chapters as $ch) {
        $post_id = wp_insert_post([
            'post_title' => wp_strip_all_tags($ch['title']),
            'post_content' => $ch['content'],
            'post_type' => 'chapter',
            'post_status' => 'publish',
        ]);

        if ($post_id && !empty($ch['filename'])) {
            $source_path = plugin_dir_path(__FILE__) . '../assets/' . $ch['filename'];
            if (!file_exists($source_path)) continue;

            $file_content = @file_get_contents($source_path);
            if (!$file_content) continue;

            $upload = wp_upload_bits($ch['filename'], null, $file_content);
            if (empty($upload['error'])) {
                $file_path = $upload['file'];
                $file_type = wp_check_filetype($file_path, null);

                $attachment = [
                    'post_mime_type' => $file_type['type'],
                    'post_title' => sanitize_file_name($ch['filename']),
                    'post_content' => '',
                    'post_status' => 'inherit'
                ];

                $attach_id = wp_insert_attachment($attachment, $file_path, $post_id);
                if (!is_wp_error($attach_id)) {
                    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
                    wp_update_attachment_metadata($attach_id, $attach_data);
                    set_post_thumbnail($post_id, $attach_id);
                }
            }
        }
    }

    update_option('readova_demo_chapters_added', true);
}
register_activation_hook(__FILE__, 'readova_core_add_demo_chapters');
