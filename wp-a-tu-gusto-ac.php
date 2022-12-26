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
		setcookie("wpatg", json_encode($value), time()+3600);  /* expire in 1 hour */
    wp_redirect(get_the_permalink());
	}
}
add_action( "template_redirect", "wpatg_manage_cookie");