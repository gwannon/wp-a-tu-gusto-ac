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
		update_option('_wpatg_banner_title_es', $_POST['_wpatg_banner_title_es']);
		update_option('_wpatg_banner_subtitle_es', $_POST['_wpatg_banner_subtitle_es']);
		update_option('_wpatg_banner_button_es', $_POST['_wpatg_banner_button_es']);
		update_option('_wpatg_banner_image_es', $_POST['_wpatg_banner_image_es']);
		update_option('_wpatg_banner_link_es', $_POST['_wpatg_banner_link_es']);
		update_option('_wpatg_banner_title_eu', $_POST['_wpatg_banner_title_eu']);
		update_option('_wpatg_banner_subtitle_eu', $_POST['_wpatg_banner_subtitle_eu']);
		update_option('_wpatg_banner_button_eu', $_POST['_wpatg_banner_button_eu']);
		update_option('_wpatg_banner_image_eu', $_POST['_wpatg_banner_image_eu']);
		update_option('_wpatg_banner_link_eu', $_POST['_wpatg_banner_link_eu']);
	} ?>
	<form method="post">
    <h2><?php _e("Banner en el panel", 'wp-a-tu-gusto'); ?></h2>
		<b><?php _e("Título", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_title_es" value="<?php echo get_option("_wpatg_banner_title_es"); ?>" style="width: calc(100% - 20px);" placeholder="ES" /><br/>
		<input type="text" name="_wpatg_banner_title_eu" value="<?php echo get_option("_wpatg_banner_title_eu"); ?>" style="width: calc(100% - 20px);" placeholder="EU" /><br/>
		<b><?php _e("Subtítulo", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_subtitle_es" value="<?php echo get_option("_wpatg_banner_subtitle_es"); ?>" style="width: calc(100% - 20px);" placeholder="ES" /><br/>
		<input type="text" name="_wpatg_banner_subtitle_eu" value="<?php echo get_option("_wpatg_banner_subtitle_eu"); ?>" style="width: calc(100% - 20px);" placeholder="EU" /><br/>
		<b><?php _e("Botón", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_button_es" value="<?php echo get_option("_wpatg_banner_button_es"); ?>" style="width: calc(100% - 20px);" placeholder="ES" /><br/>
		<input type="text" name="_wpatg_banner_button_eu" value="<?php echo get_option("_wpatg_banner_button_eu"); ?>" style="width: calc(100% - 20px);" placeholder="EU" /><br/>
		<b><?php _e("Imagen", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_image_es" value="<?php echo get_option("_wpatg_banner_image_es"); ?>" style="width: calc(100% - 20px);" placeholder="ES" /><br/>
		<input type="text" name="_wpatg_banner_image_eu" value="<?php echo get_option("_wpatg_banner_image_eu"); ?>" style="width: calc(100% - 20px);" placeholder="EU" /><br/>
		<b><?php _e("Enlace", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_link_es" value="<?php echo get_option("_wpatg_banner_link_es"); ?>" style="width: calc(100% - 20px);" placeholder="ES" /><br/>
		<input type="text" name="_wpatg_banner_link_eu" value="<?php echo get_option("_wpatg_banner_link_eu"); ?>" style="width: calc(100% - 20px);" placeholder="EU" /><br/><br/>
		<input type="submit" name="sendbanner" class="button button-primary" value="<?php _e("Guardar", 'wp-a-tu-gusto'); ?>" />
	</form>
	<hr/>
	<?php if(isset($_REQUEST['sendbanneremail']) && $_REQUEST['sendbanneremail'] != '') { 
		?><p style="border: 1px solid green; color: green; text-align: center;"><?php _e("Datos guardados correctamente.", 'wp-a-tu-gusto'); ?></p><?php
		update_option('_wpatg_banner_email_title_es', $_POST['_wpatg_banner_email_title_es']);
		update_option('_wpatg_banner_email_subtitle_es', $_POST['_wpatg_banner_email_subtitle_es']);
		update_option('_wpatg_banner_email_button_es', $_POST['_wpatg_banner_email_button_es']);
		update_option('_wpatg_banner_email_image_es', $_POST['_wpatg_banner_email_image_es']);
		update_option('_wpatg_banner_email_link_es', $_POST['_wpatg_banner_email_link_es']);
		update_option('_wpatg_banner_email_title_eu', $_POST['_wpatg_banner_email_title_eu']);
		update_option('_wpatg_banner_email_subtitle_eu', $_POST['_wpatg_banner_email_subtitle_eu']);
		update_option('_wpatg_banner_email_button_eu', $_POST['_wpatg_banner_email_button_eu']);
		update_option('_wpatg_banner_email_image_eu', $_POST['_wpatg_banner_email_image_eu']);
		update_option('_wpatg_banner_email_link_eu', $_POST['_wpatg_banner_email_link_eu']);
	} ?>
	<form method="post">
		<h2><?php _e("Banner en los emails", 'wp-a-tu-gusto'); ?></h2>
		<b><?php _e("Título", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_email_title_es" value="<?php echo get_option("_wpatg_banner_email_title_es"); ?>" style="width: calc(100% - 20px);" placeholder="ES" /><br/>
		<input type="text" name="_wpatg_banner_email_title_eu" value="<?php echo get_option("_wpatg_banner_email_title_eu"); ?>" style="width: calc(100% - 20px);" placeholder="EU" /><br/>
		<b><?php _e("Subtítulo", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_email_subtitle_es" value="<?php echo get_option("_wpatg_banner_email_subtitle_es"); ?>" style="width: calc(100% - 20px);" placeholder="ES" /><br/>
		<input type="text" name="_wpatg_banner_email_subtitle_eu" value="<?php echo get_option("_wpatg_banner_email_subtitle_eu"); ?>" style="width: calc(100% - 20px);" placeholder="EU" /><br/>
		<b><?php _e("Botón", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_email_button_es" value="<?php echo get_option("_wpatg_banner_email_button_es"); ?>" style="width: calc(100% - 20px);" placeholder="ES" /><br/>
		<input type="text" name="_wpatg_banner_email_button_eu" value="<?php echo get_option("_wpatg_banner_email_button_eu"); ?>" style="width: calc(100% - 20px);" placeholder="EU" /><br/>
		<b><?php _e("Imagen", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_email_image_es" value="<?php echo get_option("_wpatg_banner_email_image_es"); ?>" style="width: calc(100% - 20px);" placeholder="ES" /><br/>
		<input type="text" name="_wpatg_banner_email_image_eu" value="<?php echo get_option("_wpatg_banner_email_image_eu"); ?>" style="width: calc(100% - 20px);" placeholder="EU" /><br/>
		<b><?php _e("Enlace", 'wp-a-tu-gusto'); ?>:</b><br/>
		<input type="text" name="_wpatg_banner_email_link_es" value="<?php echo get_option("_wpatg_banner_email_link_es"); ?>" style="width: calc(100% - 20px);" placeholder="ES" /><br/>
		<input type="text" name="_wpatg_banner_email_link_eu" value="<?php echo get_option("_wpatg_banner_email_link_eu"); ?>" style="width: calc(100% - 20px);" placeholder="EU" /><br/><br/>
		<input type="submit" name="sendbanneremail" class="button button-primary" value="<?php _e("Guardar", 'wp-a-tu-gusto'); ?>" />
	</form>
	<?php
}
