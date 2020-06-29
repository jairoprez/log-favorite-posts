<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation function and defines a function that starts the plugin.
 *
 * @since             1.0.0
 * @package           LOG_FAVORITE_POSTS
 * 
 * @wordpress-plugin
 * Plugin Name: Log Favorite Posts
 * Plugin URI: https://github.com/jairoprez
 * Description: Allows users to add favorite posts
 * Version: 1.0.0
 * Author: Jairo Pérez
 * Author URI: https://github.com/jairoprez
 * Text Domain: log-favorite-posts
 * License: GPL-3.0+
 */

namespace LogFavoritePosts;

use LogFavoritePosts\Includes\Log_Favorite_Posts_Button_Display;
use LogFavoritePosts\Includes\My_REST_Favorite_Posts_Controller;
use LogFavoritePosts\Includes\Log_Favorite_Posts_Widget;
use LogFavoritePosts\Includes\Log_Favorite_Posts_Shortcode;

// Prevent this file from being called directly.
defined('WPINC') || die;

/**
 * Define constants
 */
define( 'LOG_FAVORITE_POSTS_VERSION', '1.0.0' );
define( 'LOG_FAVORITE_POSTS_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'LOG_FAVORITE_POSTS_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

// Include the autoloader so we can dynamically include the rest of the classes.
require_once LOG_FAVORITE_POSTS_PLUGIN_DIR . '/lib/autoloader.php';

/**
 * Instantiates the main class and initializes the plugin.
 */
function log_favorite_posts_start() {
    $log_favorite_posts = new Log_Favorite_Posts_Button_Display();
    $log_favorite_posts->initialize();

    $controller = new My_REST_Favorite_Posts_Controller();
    $controller->initialize();

    $log_favorite_posts_shortcode = new Log_Favorite_Posts_Shortcode();
    $log_favorite_posts_shortcode->initialize();
}
log_favorite_posts_start();

add_action( 'widgets_init', function(){
    register_widget( 'LogFavoritePosts\Includes\Log_Favorite_Posts_Widget' );
});

