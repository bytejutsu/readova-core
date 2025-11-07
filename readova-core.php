<?php
/**
 * Plugin Name: Readova Core
 * Plugin URI: https://bytejutsu.com/readova
 * Description: Core functionality for the Readova theme — includes Chapters custom post type and demo content setup.
 * Version: 1.0.0
 * Author: ByteJutsu
 * Author URI: https://bytejutsu.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: readova
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('READOVA_CORE_VERSION', '1.0.0');
define('READOVA_CORE_PATH', plugin_dir_path(__FILE__));
define('READOVA_CORE_URL', plugin_dir_url(__FILE__));

// Include core functionality
require_once plugin_dir_path(__FILE__) . 'inc/cpt-chapters.php';
require_once plugin_dir_path(__FILE__) . 'inc/demo-chapters.php';
require_once plugin_dir_path(__FILE__) . 'inc/editor/classic-editor.php';
