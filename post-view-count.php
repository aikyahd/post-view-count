<?php
/**
 * Plugin Name:       Post View Count
 * Plugin URI:        https://webfixlab.com
 * Description:       Handle the post view counting.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Aikya Halder
 * Author URI:        https://author.example.com/
 * License:           GPL
 * Text Domain:       post-view-count
 * Domain Path:       /languages
 *
 * @package           WordPress
 * @subpackage        Post View Count
 * @since             1.0.0
 */

defined( 'ABSPATH' ) || exit;

// plugin path.
define( 'POSTVC', __FILE__ );
define( 'POSTVC_PATH', plugin_dir_path( POSTVC ) );

require_once __DIR__ . '/vendor/autoload.php';

add_action('init', 'postvc_init_plugin');
function postvc_init_plugin() {
    $postvc = new \Aikya\PostViewCount\PostVC();
    $postvc->init_plugin();
}

register_activation_hook( POSTVC, 'postvc_activate' );
function postvc_activate(){
    postvc_init_plugin();
    flush_rewrite_rules();
}

register_deactivation_hook( POSTVC, 'postvc_deactive' );
function postvc_deactive(){
    flush_rewrite_rules();
}
