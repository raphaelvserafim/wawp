<?php

/**
 * @package Wawp
 * Plugin Name: WAWP - WhatsApp Post Notifier
 * Plugin URI: https://github.com/raphaelvserafim/wawp
 * Description: A powerful WordPress plugin that automatically sends notifications via WhatsApp whenever new posts are published. Perfect for keeping your readers informed quickly and efficiently.
 * Author: Raphael Serafim
 * Author URI: https://github.com/raphaelvserafim
 * Version: 1.0.0
 */

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

define("PATH_BASE_WAWP", plugin_dir_path(__FILE__));


if (!defined("ABSPATH")) {
  exit;
}


include_once PATH_BASE_WAWP . "/includes/WhatsApp.php";

include_once PATH_BASE_WAWP . "/includes/Wawp.php";

$wpwa = new Wawp();

$wpwa->init();

register_activation_hook(__FILE__, [$wpwa, 'createTableSettings']);
register_activation_hook(__FILE__, [$wpwa, 'createTableContacts']);


add_action('publish_post', [$wpwa, 'handlePostPublished'], 10, 2);


add_action('init', [$wpwa, 'registerRewriteRule']);
add_filter('query_vars', [$wpwa, 'addQueryVars']);
add_action('template_redirect', [$wpwa, 'handleCustomEndpoint']);
