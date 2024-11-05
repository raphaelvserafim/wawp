<?php

/**
 * @package  Wawp
 */

class Wawp
{
  /**
   * Summary of db
   * @var wpdb
   */
  public  $db;
  public string $prefix;
  public string $charsetCollate;

  public   function __construct()
  {
    global $wpdb;
    $this->db =  $wpdb;
    $this->prefix =  $wpdb->prefix;
    $this->charsetCollate =  $wpdb->get_charset_collate();
  }
  public function init()
  {
    $this->includeScripts();
    $this->includeStyles();
    $this->menu();
  }



  public function registerRewriteRule()
  {
    add_rewrite_rule(
      '^wame-webhook/?$',
      'index.php?wame-webhook=1',
      'top'
    );

    flush_rewrite_rules();
  }

  public function addQueryVars($vars)
  {
    $vars[] = 'wame-webhook';
    return $vars;
  }


  public function handleCustomEndpoint()
  {
    if (get_query_var('wame-webhook') === '1') {
      $data = file_get_contents('php://input');
      $decodedData = json_decode($data, true);

      header('Content-Type: application/json');
      echo json_encode(['status' => 'success', 'message' => 'Evento recebido!']);
      exit;
    }
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


  public function createTableSettings()
  {
    $table_name = $this->prefix . 'wame_api_settings';

    $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            api_name varchar(255) NOT NULL,
            api_server varchar(255) NOT NULL,
            api_key varchar(255) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ){$this->charsetCollate};";

    dbDelta($sql);

    if ($this->db->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
      wp_die("Erro: a tabela $table_name não pôde ser criada.");
    }
  }

  public function createTableContacts()
  {
    $table_name = $this->prefix . 'wame_api_contacts';

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        contact_number varchar(255) NOT NULL,
        contact_name varchar(255) NOT NULL,
        is_group TINYINT(1) NOT NULL DEFAULT 0,  
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) {$this->charsetCollate};";

    dbDelta($sql);

    if ($this->db->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
      wp_die("Erro: a tabela $table_name não pôde ser criada.");
    }
  }
}
