<?php

/**
 * Plugin Name: WP A tu gusto
 * Plugin URI:  https://github.com/gwannon/wp-a-tu-gusto-ac
 * Description: Plugin de Wordpress para personalizar tu perfil de Active Campaign desde una web en WordPress.
 * Version:     1.0
 * Author:      Gwannon
 * Author URI:  https://github.com/gwannon/
 * License:     GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-a-tu-gusto
 *
 * PHP 7.3
 * WordPress 6.1.1
 */


ini_set("display_errors", 1);

function wpatg_load_scripts(){
  wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'wpatg_load_scripts');

/* ----------- Includes ------------ */
include_once(plugin_dir_path(__FILE__).'inc/admin.php');
include_once(plugin_dir_path(__FILE__).'inc/shortcodes.php');
include_once(plugin_dir_path(__FILE__).'inc/fields.php');
include_once(plugin_dir_path(__FILE__).'/classes/curl.php');
include_once(plugin_dir_path(__FILE__).'/classes/user.php');
include_once(plugin_dir_path(__FILE__).'/classes/functions.php');

/* ---------- Globals ---------------- */
define('WPAT_AC_API_URL', get_option("_wpatg_api_url")); 
define('WPATG_AC_API_KEY', get_option("_wpatg_api_key"));
define('WPATG_AC_ENGAGEMENT_AUTOMATION', 105);
define('WPATG_NEWLETTERS_FILTER', get_option("_wpatg_newsletter_filter"));
define('WPATG_MAIN_NEWLETTER_ID', get_option("_wpatg_main_newsletter_id"));
define('WPATG_LAST_UPDATE_FIELD_ID', 39);
define('WPATG_ARCHIVE_MAX_ITEMS', 20);
define('WPATG_COOKIE_TIME', (60 * 60 * 24));
define('WPATG_ARCHIVE_CACHE_FILE', plugin_dir_path(__FILE__).'archive.json');
define('WPATG_ARCHIVE_CACHE_TIME', (60 * 60 * 24));

/* -------------------- Cookies ------------------ */
function wpatg_manage_cookie(){
  if(isset($_REQUEST['wpatg_logout']) && $_REQUEST['wpatg_logout'] == 'yes') {
    setcookie("wpatg", "");  //Borramos la cookie
    wp_redirect(get_the_permalink());
  }	else if(isset($_REQUEST['wpatg_hash']) && $_REQUEST['wpatg_hash'] != '' && isset($_REQUEST['wpatg_contact_id']) && $_REQUEST['wpatg_contact_id'] != '') {
    $value = [
      "wpatg_hash" => $_REQUEST['wpatg_hash'],
      "wpatg_contact_id" => $_REQUEST['wpatg_contact_id']
    ];
		setcookie("wpatg", json_encode($value), time() + WPATG_COOKIE_TIME);  /* expire in 1 hour */
    wp_redirect(get_the_permalink());
	}
}
add_action( "template_redirect", "wpatg_manage_cookie");