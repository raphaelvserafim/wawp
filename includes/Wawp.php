<?php

/**
 * @package  Wawp
 */

class Wawp
{
    public function init()
    {
        $this->includeScripts();
        $this->includeStyles();
        $this->menu();
    }

    private function menu()
    {
        add_action('admin_menu', function () {
            add_menu_page(
                'WaWp',
                'WaWp',
                'manage_options',
                'wawp-settings',
                [$this, 'menuPageContent'],
                'dashicons-whatsapp',
                20
            );
        });
    }

    public function menuPageContent()
    {
        include_once PATH_BASE_WAWP . "/tamplates/settings.php";
    }

    private function includeScripts()
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_script("wa-main-js", plugins_url("/assets/js/main.js", __DIR__));
        });
    }

    private function includeStyles()
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style("wa-styles", plugins_url("/assets/css/style.css", __DIR__));
        });
    }
}
