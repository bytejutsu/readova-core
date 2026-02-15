<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Run after plugin activation.
 */
function readova_core_activate_plugin()
{
    // Default: enable simplified admin once (do not overwrite later user choice).
    if (get_option('readova_core_simplified_admin', null) === null) {
        add_option('readova_core_simplified_admin', true);
    }

    // Register CPT before flushing.
    if (function_exists('readova_core_register_chapter_cpt')) {
        readova_core_register_chapter_cpt();
    }

    flush_rewrite_rules();

    // Demo content (guarded).
    if (function_exists('readova_core_add_demo_chapters')) {
        readova_core_add_demo_chapters();
    }
}

/**
 * Run on plugin deactivation.
 */
function readova_core_deactivate_plugin()
{
    flush_rewrite_rules();
}
