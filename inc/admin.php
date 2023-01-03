<?php

//Administrador 
add_action( 'admin_menu', 'wpatg_plugin_menu' );
function wpatg_plugin_menu() {
	add_options_page( __('A tu gusto', 'wp-a-tu-gusto'), __('Panel A tu gusto', 'wp-a-tu-gusto'), 'manage_options', 'wpatg', 'wpatg_page_settings');
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
		<b><?php _e("AC Api key", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_api_key" value="<?php echo get_option("_wpatg_api_key"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("AC Api URL", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_api_url" value="<?php echo get_option("_wpatg_api_url"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Id de la lista principal de boletines", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_main_newsletter_id" value="<?php echo get_option("_wpatg_main_newsletter_id"); ?>" style="width: calc(100% - 20px);" /><br/>
		<h2><?php _e("Configuración del panel \"A tu gusto\"", 'wp-a-tu-gusto'); ?></h2>
		<b><?php _e("Filtro de boletines para archivo", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_newsletter_filter" value="<?php echo get_option("_wpatg_newsletter_filter"); ?>" style="width: calc(100% - 20px);" /><br/><br/>
		<input type="submit" name="send" class="button button-primary" value="<?php _e("Guardar", 'wp-a-tu-gusto'); ?>" />
	</form>
	<hr/>
	<h1><?php _e("Configuración de Banners", 'wp-a-tu-gusto'); ?></h1>
	<?php if(isset($_REQUEST['sendbanner']) && $_REQUEST['sendbanner'] != '') { 
		?><p style="border: 1px solid green; color: green; text-align: center;"><?php _e("Datos guardados correctamente.", 'wp-a-tu-gusto'); ?></p><?php
		update_option('_wpatg_banner_title', $_POST['_wpatg_banner_title']);
		update_option('_wpatg_banner_subtitle', $_POST['_wpatg_banner_subtitle']);
		update_option('_wpatg_banner_button', $_POST['_wpatg_banner_button']);
		update_option('_wpatg_banner_image', $_POST['_wpatg_banner_image']);
		update_option('_wpatg_banner_link', $_POST['_wpatg_banner_link']);
	} ?>
	<form method="post">
    <h2><?php _e("Banner en el panel", 'wp-a-tu-gusto'); ?></h2>
		<b><?php _e("Título", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_title" value="<?php echo get_option("_wpatg_banner_title"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Subtítulo", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_subtitle" value="<?php echo get_option("_wpatg_banner_subtitle"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Botón", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_button" value="<?php echo get_option("_wpatg_banner_button"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Imagen", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_image" value="<?php echo get_option("_wpatg_banner_image"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Enlace", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_link" value="<?php echo get_option("_wpatg_banner_link"); ?>" style="width: calc(100% - 20px);" /><br/><br/>
		<input type="submit" name="sendbanner" class="button button-primary" value="<?php _e("Guardar", 'wp-a-tu-gusto'); ?>" />
	</form>
	<hr/>
	<?php if(isset($_REQUEST['sendbanneremail']) && $_REQUEST['sendbanneremail'] != '') { 
		?><p style="border: 1px solid green; color: green; text-align: center;"><?php _e("Datos guardados correctamente.", 'wp-a-tu-gusto'); ?></p><?php
		update_option('_wpatg_banner_email_title', $_POST['_wpatg_banner_email_title']);
		update_option('_wpatg_banner_email_subtitle', $_POST['_wpatg_banner_email_subtitle']);
		update_option('_wpatg_banner_email_button', $_POST['_wpatg_banner_email_button']);
		update_option('_wpatg_banner_email_image', $_POST['_wpatg_banner_email_image']);
		update_option('_wpatg_banner_email_link', $_POST['_wpatg_banner_email_link']);
	} ?>
	<form method="post">
		<h2><?php _e("Banner en los emails", 'wp-a-tu-gusto'); ?></h2>
		<b><?php _e("Título", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_email_title" value="<?php echo get_option("_wpatg_banner_email_title"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Subtítulo", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_email_subtitle" value="<?php echo get_option("_wpatg_banner_email_subtitle"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Botón", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_email_button" value="<?php echo get_option("_wpatg_banner_email_button"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Imagen", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_email_image" value="<?php echo get_option("_wpatg_banner_email_image"); ?>" style="width: calc(100% - 20px);" /><br/>
		<b><?php _e("Enlace", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_email_link" value="<?php echo get_option("_wpatg_banner_email_link"); ?>" style="width: calc(100% - 20px);" /><br/><br/>
		<input type="submit" name="sendbanneremail" class="button button-primary" value="<?php _e("Guardar", 'wp-a-tu-gusto'); ?>" />
	</form>
	<?php
}
