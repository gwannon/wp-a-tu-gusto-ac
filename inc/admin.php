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
	} ?>
	<form method="post">
    <h2><?php _e("Configuración Active Campaign", 'wp-a-tu-gusto'); ?></h2>
		<h3><?php _e("AC Api key", 'wp-a-tu-gusto'); ?>:</h3>
		<input type="text" name="_wpatg_api_key" value="<?php echo get_option("_wpatg_api_key"); ?>" style="width: calc(100% - 20px);" />
		<h3><?php _e("AC Api URL", 'wp-a-tu-gusto'); ?>:</h3>
		<input type="text" name="_wpatg_api_url" value="<?php echo get_option("_wpatg_api_url"); ?>" style="width: calc(100% - 20px);" /><br/><br/>
		<input type="submit" name="send" class="button button-primary" value="<?php _e("Guardar", 'wp-a-tu-gusto'); ?>" />
	</form>
	<?php
}
