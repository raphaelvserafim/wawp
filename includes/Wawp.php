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




    public function handlePostPublished($post_id, $post)
    {
        if (get_post_type($post_id) !== 'post') {
            return;
        }
        $titulo = $post->post_title;
    }


    public function createTable()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wawp_api_settings';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            api_server varchar(255) NOT NULL,
            api_key varchar(255) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            wp_die("Erro: a tabela $table_name não pôde ser criada.");
        }
    }
}
