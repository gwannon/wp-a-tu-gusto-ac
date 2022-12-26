<?php

/* -------- SHORTCODES ------ */


/* wpatg_login */
function wpatg_login($params = array(), $content = null) {
  if(isset($_COOKIE['wpatg'])) return; //Si existe la cookie ni seguimos.
  ob_start();
  if (isset($_REQUEST['wpatg-email']) && is_email($_REQUEST['wpatg-email'])) {
    remove_all_filters('wp_mail', 10);
    if(existsUserAC($_REQUEST['wpatg-email'])) {
      $user = new UserAC($_REQUEST['wpatg-email']);
      wpatg_send_login_email($user);
      $ok = __('Para actualizar tus preferencias de suscripción, comprueba tu correo electrónico porque te hemos enviado un mensaje con los pasos para poder hacerlo.', 'wp-a-tu-gusto');
    } else $error = __('Email incorrecto. El email suministrado no está en nuestra base de datos.', 'wp-a-tu-gusto');
  } else if (isset($_REQUEST['wpatg-email'])) $error = __('Email incorrecto. El email suministrado no tiene el formato adecuado.', 'wp-a-tu-gusto');?>
    <form id="wpatg-form-login" method="post">
      <?php if(isset($ok)) echo "<p>".$ok."</p>"; ?>
      <?php if(isset($error)) echo "<p>".$error."</p>"; ?>
      <input type="email" name="wpatg-email" value="" placeholder="<?php _e('Email', 'wp-a-tu-gusto'); ?>" required />
      <button type="submit" name="wpatg-send"><?php _e('Enviar', 'wp-a-tu-gusto'); ?></button>
    </form>
  <?php return ob_get_clean();
}
add_shortcode('wpatg_login', 'wpatg_login');


/* wpatg_zone */
function wpatg_zone($params = array(), $content = null) {
  if(!isset($_COOKIE['wpatg'])) return; //Si existe la cookie ni seguimos.
  ob_start(); 
  $menu = [
    "mis-datos" => __("Mis datos", "wp-a-tu-gusto"),
    //"notificaciones" => __("Notificicaciones", "wp-a-tu-gusto"),
    //"mis-noticias" => __("Mis noticias", "wp-a-tu-gusto"),
    "boletines" => __("Boletines", "wp-a-tu-gusto"),
    "intereses" => __("Intereses", "wp-a-tu-gusto"),
    //"archivo-boletines" => __("Archivo de boletines", "wp-a-tu-gusto"),
  ]; ?>
  <div id="wpatg">
    <h1><?php _e("A tu gusto", "wp-a-tu-gusto"); ?></h1>
    <ul>
      <?php foreach($menu as $tab => $label) { ?>
        <li><a href="<?php echo get_the_permalink()."?wpatg_tab=".$tab; ?>"><?=$label;?></a></li>
      <?php } ?>
      <li><a href="<?php echo get_the_permalink()."?wpatg_logout=yes"; ?>">Salir</a></li>
    </ul>
    <?php if(isset($_REQUEST['wpatg_tab'])) { 
      if($_REQUEST['wpatg_tab'] == 'mis-datos') { ?>
        <h2><?php _e("Mis datos", "wp-a-tu-gusto"); ?></h2>
        <?php wpatg_zone_my_data (); ?>
      <?php } else if($_REQUEST['wpatg_tab'] == 'notificaciones') { ?>
        <h2><?php _e("Notificicaciones", "wp-a-tu-gusto"); ?></h2>

      <?php } else if($_REQUEST['wpatg_tab'] == 'mis-noticias') { ?>
        <h2><?php _e("Mis noticias", "wp-a-tu-gusto"); ?></h2>

      <?php } else if($_REQUEST['wpatg_tab'] == 'boletines') { ?>
        <h2><?php _e("Boletines", "wp-a-tu-gusto"); ?></h2>

      <?php } else if($_REQUEST['wpatg_tab'] == 'intereses') { ?>
        <h2><?php _e("Intereses", "wp-a-tu-gusto"); ?></h2>

      <?php } else if($_REQUEST['wpatg_tab'] == 'archivo-boletines') { ?>
        <h2><?php _e("Archivo de boletines", "wp-a-tu-gusto"); ?></h2>

      <?php } 
    } else { ?>
      <p>EXPLICACIÓN DE QUÉ ES ESTO. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    <?php } ?>
  </div>
  <?php echo wpatg_zone_show_css(); ?>
  <?php return ob_get_clean();
}
add_shortcode('wpatg_zone', 'wpatg_zone');

function wpatg_zone_show_css() { ?>
  <style>
    <?php echo file_get_contents(dirname(__FILE__)."/../assets/css/style.css"); ?>
  </style>
<?php }

function wpatg_zone_my_data() {
  $contact = getLoggedUser();
  $contact->fields = $contact->getApiFields();
  $formFields = getFields("fields");
  $formData = getFields(); 
  
  if(isset($_REQUEST['wpatg_save_my_data'])){
    $contact->setNombre($_REQUEST['my-data']['data']['nombre']);
    $contact->setApellidos($_REQUEST['my-data']['data']['apellidos']);
    $contact->setTelefono($_REQUEST['my-data']['data']['telefono']);
    foreach($_REQUEST['my-data']['field'] as $field_id => $value) {
      $contact->setField($field_id, $value);
    }
    $contact->updateProfileAC();

    echo "<p style='color: green; borer: 1px solid green;'>DATOS ACTUALIZADOS</p>";
  }
  
  
  ?>
  <form method="post" action="<?php echo get_the_permalink()."?wpatg_tab=mis-datos"; ?>">
    <label><b><?php _e('Email', 'ac-update-forms'); ?></b><br/><?php echo $contact->email; ?></label>
    <?php foreach ($formFields as $id => $field ) { if($field['position'] == 'pre') { ?>
      <?php wptag_zone_my_data_draw_field($field, $contact); ?>
    <?php } } ?>
    <?php foreach ($formData as $label => $input ) { ?>
      <label><b><?php echo $input['name']; ?><?php echo ($input['required'] ? "*" : ""); ?></b><br/>
        <input type="<?php echo ($input['type'] != '' ? $input['type'] : "text"); ?>" name="my-data[data][<?php echo $label; ?>]" value="<?php echo $contact->{$label}; ?>" placeholder="<?php echo $input['name']; ?>" oninvalid="onError();"<?php echo ($input['required'] ? " required" : ""); ?> <?php echo ($input['pattern'] ? " pattern='".$input['pattern']."'" : ""); ?> />
      </label>
    <?php } ?>
    <?php foreach ($formFields as $id => $field ) { if($field['position'] == 'post') { ?>
      <?php wptag_zone_my_data_draw_field($field, $contact); ?>
    <?php } } ?>
    <button type="submit" name="wpatg_save_my_data"><?php _e('Guardar', 'wp-a-tu-gusto'); ?></button>
  </form>
<?php }

function wptag_zone_my_data_draw_field($field, $contact) { ?>
  <label><b><?php echo $field['text']; ?><?php echo ($field['required'] ? "*" : ""); ?></b><br/>
    <?php if(isset($field['select'])) { ?>
      <select name="my-data[field][<?php echo $field['id']; ?>]" oninvalid="onError();" <?php echo ($field['required'] ? " required" : ""); ?>>
        <option value=""><?php printf(__('Elige tu %s', 'ac-update-forms'), mb_strtolower($field['text'])); ?></option>
        <?php foreach($field['select'] as $select) { ?>
          <option value="<?php echo $select['label']; ?>"<?php echo ($contact->fields[$field['id']] == $select['label'] ? " selected='selected'" : ""); ?>><?php echo $select['text']; ?></option>
        <?php } ?>
      </select>
    <?php } else { ?>
      <input type="<?php echo ($field['type'] != '' ? $field['type'] : "text"); ?>" name="my-data[field][<?php echo $field['id']; ?>]" value="<?php echo $contact->fields[$field['id']]; ?>" placeholder="<?php echo $field['text']; ?>" oninvalid="onError();" <?php echo ($field['required'] ? " required" : ""); ?><?php echo ($field['pattern'] ? " pattern='".$field['pattern']."'" : ""); ?> />
    <?php } ?>
  </label>
<?php }
