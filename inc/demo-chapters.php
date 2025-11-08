<?php
/**
 * Create demo "Chapters" posts with featured images content on first activation of the Readova Core plugin.
 *
 * @package Readova_Core
 */

if (!defined('ABSPATH')) {
    exit;
}

function readova_core_add_demo_chapters() {

    // Only run once
    if (get_option('readova_demo_chapters_added')) {
        return;
    }

    // Make sure CPT is registered first
    if (!post_type_exists('chapter')) {
        return;
    }

    // Define demo chapters (with HEREDOC content)
    $chapters = [
        [
            'title' => 'Introduction',
            'content' => <<<HTML
                <p>Welcome to the Readova theme. This is a brief introduction to the story, setting the stage for the adventure to come. We hope you enjoy the journey.</p>
                <blockquote><p>Every story has a beginning, and this is ours.</p></blockquote>
                <p>The following chapters will introduce you to new worlds and exciting characters. Get ready to explore.</p>
                HTML,
            'filename' => 'demo-chapter1.jpg',
        ],
        [
            'title' => 'The Surfing Chronicles',
            'content' => <<<HTML
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. This is a standard paragraph.</p>
                <blockquote><p>Surfing is not just a sport; it's a way of life.</p></blockquote>
                <p>Here is some more text to continue the chapter after the quote.</p>
                HTML,
            'filename' => 'demo-chapter2.jpg',
        ],
        [
            'title' => 'Beyond the Horizon',
            'content' => <<<HTML
                <p>Cras suscipit, quam vitae dapibus facilisis, odio sem pulvinar risus, non suscipit lacus est in sapien. This chapter explores the unknown.</p>
                <blockquote><p>The only way to discover the limits of the possible is to go beyond them.</p></blockquote>
                <p>Key preparations include:</p>
                <ul>
                    <li>Checking the navigation systems.</li>
                    <li>Stocking up on supplies.</li>
                    <li>Reviewing the star charts.</li>
                </ul>
                HTML,
            'filename' => 'demo-chapter3.jpg',
        ],
        [
            'title' => 'A Whisper in the Woods',
            'content' => <<<HTML
                <p>Curabitur vel sem sit amet dolor placerat vehicula. Nullam a lectus at leo tincidunt aliquam. The forest holds many secrets.</p>
                <blockquote><p>In every walk with nature, one receives far more than he seeks.</p></blockquote>
                <p>To survive, you must remember the steps in order:</p>
                <ol>
                    <li><strong>Find</strong> a water source.</li>
                    <li><strong>Build</strong> a shelter.</li>
                    <li><strong>Start</strong> a fire.</li>
                </ol>
                HTML,
            'filename' => 'demo-chapter4.jpg',
        ],
        [
            'title' => 'The Summer Escape',
            'content' => <<<HTML
                <p>Aenean lacinia bibendum nulla sed consectetur. This chapter is all about relaxing and finding joy in the simple things, like a cool pool on a hot day.</p>
                <blockquote><p>Summer is a state of mind, filled with sunshine and poolside dreams.</p></blockquote>
                <p>Nothing beats the feeling of diving into the clear, refreshing water.</p>
                HTML,
            'filename' => 'demo-chapter5.jpg',
        ],
        [
            'title' => 'Flavors of Life',
            'content' => <<<HTML
                <p>Integer posuere erat a ante venenatis dapibus posuere velit aliquet. We explore the vibrant tastes that make life interesting.</p>
                <blockquote><p>Variety is the very spice of life, That gives it all its flavor.</p></blockquote>
                <p>From sweet to sour, each one offers a different experience, a new memory.</p>
                HTML,
            'filename' => 'demo-chapter6.jpg',
        ],
    ];

    // Ensure necessary WordPress includes are available
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    foreach ($chapters as $ch) {

        // Create the post
        $post_id = wp_insert_post([
            'post_title'   => wp_strip_all_tags($ch['title']),
            'post_content' => $ch['content'],
            'post_type'    => 'chapter',
            'post_status'  => 'publish',
        ]);

        // Attach featured image if available
        if ($post_id && !empty($ch['filename'])) {

            // Path to plugin image folder
            $source_path = plugin_dir_path(__FILE__) . '../images/' . $ch['filename'];

            if (!file_exists($source_path)) {
                continue;
            }

            $file_content = @file_get_contents($source_path);
            if (!$file_content) {
                continue;
            }

            // Upload image to Media Library
            $upload = wp_upload_bits($ch['filename'], null, $file_content);

            if (empty($upload['error'])) {
                $file_path = $upload['file'];
                $file_type = wp_check_filetype($file_path, null);

                $attachment = [
                    'post_mime_type' => $file_type['type'],
                    'post_title'     => sanitize_file_name($ch['filename']),
                    'post_content'   => '',
                    'post_status'    => 'inherit',
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

    // Mark demo content as imported
    update_option('readova_demo_chapters_added', true);
}

/**
 * Run after plugin activation.
 */
function readova_core_activate_plugin() {
    // Register the CPT before running demo importer
    if (function_exists('readova_core_register_chapter_cpt')) {
        readova_core_register_chapter_cpt();
    }

    // Add demo content
    readova_core_add_demo_chapters();
}
