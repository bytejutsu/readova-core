<?php
/**
 * Plugin Name: Readova Core
 * Description: Core functionality plugin for the Readova theme. Registers the 'Readova Chapters' custom post type and enables demo content import.
 * Version: 1.0.2
 * Author: ByteJutsu
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: readova-core
 */

if (!defined('ABSPATH')) {
    exit;
}

define('READOVA_CORE_VERSION', '1.0.2');
define('READOVA_CORE_PATH', plugin_dir_path(__FILE__));
define('READOVA_CORE_URL', plugin_dir_url(__FILE__));
define('READOVA_CORE_CPT_CHAPTER', 'readova_chapter');

require_once READOVA_CORE_PATH . 'inc/cpt-chapters.php';
require_once READOVA_CORE_PATH . 'inc/demo-chapters.php';
require_once READOVA_CORE_PATH . 'inc/editor/classic-editor.php';
require_once READOVA_CORE_PATH . 'inc/activation.php';

if (is_admin()) {
    require_once READOVA_CORE_PATH . 'inc/admin/admin-dashboard-cleanup.php';
}

register_activation_hook(__FILE__, 'readova_core_activate_plugin');
register_deactivation_hook(__FILE__, 'readova_core_deactivate_plugin');
