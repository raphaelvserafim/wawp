<?php

/**
 * @package Wawp
 * Plugin name: WAWP
 * Plugin URI:
 * Description: send posts WhastAApp
 * Author: Raphael Serafim
 * Author URI: https://github.com/raphaelvserafim
 * Version: 1.0.0
 * */

define("PATH_BASE_WAWP", plugin_dir_path(__FILE__));


if (!defined("ABSPATH")) {
    exit;
}

include_once PATH_BASE_WAWP . "/includes/Wawp.php";

$wpwa = new Wawp();

$wpwa->init();
