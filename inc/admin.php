<?php

//Administrador 
add_action( 'admin_menu', 'wpatg_plugin_menu' );
function wpatg_plugin_menu() {
	add_options_page( __('A tu gusto', 'wp-a-tu-gusto'), __('A tu gusto', 'wp-a-tu-gusto'), 'manage_options', 'wpatg', 'wpatg_page_settings');
}

function wpatg_page_settings() { 
	?><h1><?php _e("Configuración WP A tu gusto", 'wp-a-tu-gusto'); ?></h1><?php 
	if(isset($_REQUEST['send']) && $_REQUEST['send'] != '') { 
		?><p style="border: 1px solid green; color: green; text-align: center;"><?php _e("Datos guardados correctamente.", 'wp-a-tu-gusto'); ?></p><?php
		update_option('_wpatg_api_key', $_POST['_wpatg_api_key']);
		update_option('_wpatg_api_url', $_POST['_wpatg_api_url']);
		update_option('_wpatg_main_newsletter_id', $_POST['_wpatg_main_newsletter_id']);
		update_option('_wpatg_newsletter_filter', $_POST['_wpatg_newsletter_filter']);
	} ?>
	<form method="post">
    <h2><?php _e("Configuración Active Campaign", 'wp-a-tu-gusto'); ?></h2>
		<h3><?php _e("AC Api key", 'wp-a-tu-gusto'); ?>:</h3>
		<input type="text" name="_wpatg_api_key" value="<?php echo get_option("_wpatg_api_key"); ?>" style="width: calc(100% - 20px);" />
		<h3><?php _e("AC Api URL", 'wp-a-tu-gusto'); ?>:</h3>
		<input type="text" name="_wpatg_api_url" value="<?php echo get_option("_wpatg_api_url"); ?>" style="width: calc(100% - 20px);" />
		<h3><?php _e("Id de la lista principal de boletines", 'wp-a-tu-gusto'); ?>:</h3>
		<input type="text" name="_wpatg_main_newsletter_id" value="<?php echo get_option("_wpatg_main_newsletter_id"); ?>" style="width: calc(100% - 20px);" />
		<h2><?php _e("Configuración del panel \"A tu gusto\"", 'wp-a-tu-gusto'); ?></h2>
		<h3><?php _e("Filtro de boletines para archivo", 'wp-a-tu-gusto'); ?>:</h3>
		<input type="text" name="_wpatg_newsletter_filter" value="<?php echo get_option("_wpatg_newsletter_filter"); ?>" style="width: calc(100% - 20px);" /><br/><br/>
		<input type="submit" name="send" class="button button-primary" value="<?php _e("Guardar", 'wp-a-tu-gusto'); ?>" />
	</form>
	<?php
}
