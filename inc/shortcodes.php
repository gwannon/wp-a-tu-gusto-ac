<?php

/* -------- SHORTCODES ------ */


/* wpatg_login */
function wpatg_login($params = array(), $content = null) {
  if(isset($_COOKIE['wpatg'])) return; //Si existe la cookie ni seguimos.
  /*$menu = [
    "register" => __("Suscríbete", "wp-a-tu-gusto"),
    "login" => __("Iniciar sesión", "wp-a-tu-gusto"),
  ];*/
  ob_start();?>
  <div id="wpatg">
    <?php if(isset($menu)) wpatg_menu($menu); ?>
    <?php if(isset($_REQUEST['wpatg_tab']) && $_REQUEST['wpatg_tab'] == 'register') { ?>
      <form id="wpatg-form-register" method="post">
        <h2><?php _e("Suscripción a comunicaciones de Grupo SPRI", "wp-a-tu-gusto"); ?></h2>
        <p><?php _e("Te vamos a contar el día a día de la empresa vasca, hacia donde va tu sector, los eventos a los que no puedes faltar, las ayudas de las que te puedes beneficiar, inspirarte con ideas de la competencia o aprender de los éxitos y fracasos…, porque eso, también te lo contamos. Indícanos tu email para verificar que realmente eres tú."); ?></h2>
        <p><?php _e("Si quieres puedes echar un vistazo a <a href='#'>nuestras comunicaciones anteriores</a>.", "wp-a-tu-gusto"); ?></p>
        <?php if (isset($_REQUEST['wpatg-email']) && is_email($_REQUEST['wpatg-email'])) {
          remove_all_filters('wp_mail', 10);
          //Comprobar si no existe
          if(!existsUserAC($_REQUEST['wpatg-email'])) {
            $user = createUserAC($_REQUEST['wpatg-email']);
            if($user) {
              $user->executeAutomation (WPATG_AC_ENGAGEMENT_AUTOMATION);
              //Metemos etiqueta de idioma
              if(ICL_LANGUAGE_CODE == 'es') $user->setTag(18);
              else $user->setTag(30);
              //Metemos en lista de boletines
              $user->setList(17, 1);
              //Mandamos email
              wpatg_send_register_email($user);
              $ok = __('Ya estás registrado. Ahora para actualizar tus preferencias de suscripción, comprueba tu correo electrónico porque te hemos enviado un mensaje con los pasos para poder hacerlo.', 'wp-a-tu-gusto');
            } else $error = __('Ha ocurrido un error. Vuelve a intentarlo más tarde.', 'wp-a-tu-gusto'); 
          } else { 
            $user = new UserAC($_REQUEST['wpatg-email']);
            $user->executeAutomation (WPATG_AC_ENGAGEMENT_AUTOMATION);
            if(!$user->hasList(17)) { //Comprobamos si existe y no esta en boletines SPRI (lista)
              //Metemos etiqueta de idioma
              if(ICL_LANGUAGE_CODE == 'es') $user->setTag(18);
              else $user->setTag(30);
              //Metemos en lista de boletines
              $user->setList(17, 1);
              //Mandamos email
              wpatg_send_register_email($user);
              $ok = __('Ya estás registrado. Ahora para actualizar tus preferencias de suscripción, comprueba tu correo electrónico porque te hemos enviado un mensaje con los pasos para poder hacerlo.', 'wp-a-tu-gusto');
            } else $error = __('Email incorrecto. El email suministrado ya está en nuestra base de datos.', 'wp-a-tu-gusto');
          }
        } else if (isset($_REQUEST['wpatg-email'])) $error = __('Email incorrecto. El email suministrado no tiene el formato adecuado.', 'wp-a-tu-gusto'); ?>
        <?php if(isset($ok)) echo "<p style=' background-color: #21f3f3; color: #000; padding: 10px; text-align: center;'><b>".$ok."</b></p>"; ?>
        <?php if(isset($error)) echo "<p style='background-color: red; color: #fff; padding: 10px; text-align: center;'><b>".$error."</b></p>"; ?>
        <input type="email" name="wpatg-email" value="" placeholder="<?php _e('Email', 'wp-a-tu-gusto'); ?>" required />
        <select name="lang">
          <option value="" selected="selected" class="gf_placeholder"><?php _e('Idioma', 'wp-a-tu-gusto'); ?></option>
          <option value="newsletter-es" selected="selected"><?php _e('Castellano', 'wp-a-tu-gusto'); ?></option>
          <option value="newsletter-eu"><?php _e('Euskera', 'wp-a-tu-gusto'); ?></option>
        </select>
        <button type="submit" name="wpatg-send"><?php _e('¡Suscríbete!', 'wp-a-tu-gusto'); ?></button>
        <p class="legal"><?php _e('SPRI-Agencia Vasca de Desarrollo Empresarial, como responsable del tratamiento de los datos, recoge sus datos personales para la prestación de los servicios relacionados con nuestros programas y servicios. Tiene derecho a retirar su consentimiento en cualquier momento, oponerse al tratamiento, acceder, rectificar y suprimir los datos, así como otros derechos, mediante correo electrónico dirigido a la dirección <a href="mailto:lopd@spri.eus">lopd@spri.eus</a>. Así mismo, puede consultar la información adicional y detallada sobre Protección de Datos en el Apartado <a href="/es/politica-de-privacidad/">Política de privacidad</a>. Al pulsar "Enviar" consentirá el tratamiento de sus datos en los términos indicados.', 'wp-a-tu-gusto'); ?></p>
      </form>
    <?php } else if((isset($_REQUEST['wpatg_tab']) && $_REQUEST['wpatg_tab'] == 'login') ) { ?>
      <form id="wpatg-form-login" method="post">
        <h2><?php _e("Accede a las preferencias de tus suscripciones y personalizalas a tu gusto", "wp-a-tu-gusto"); ?></h2>
        <p><?php _e("Te vamos a contar el día a día de la empresa vasca, hacia donde va tu sector, los eventos a los que no puedes faltar, las ayudas de las que te puedes beneficiar, inspirarte con ideas de la competencia o aprender de los éxitos y fracasos…, porque eso, también te lo contamos. Indícanos tu email para verificar que realmente eres tú.", "wp-a-tu-gusto"); ?></p>
        <?php if (isset($_REQUEST['wpatg-email']) && is_email($_REQUEST['wpatg-email'])) {
          remove_all_filters('wp_mail', 10);
          if(existsUserAC($_REQUEST['wpatg-email'])) {
            $user = new UserAC($_REQUEST['wpatg-email']);
            $user->executeAutomation (WPATG_AC_ENGAGEMENT_AUTOMATION);
            wpatg_send_login_email($user);
            $ok = __('Para actualizar tus preferencias de suscripción, comprueba tu correo electrónico porque te hemos enviado un mensaje con los pasos para poder hacerlo.', 'wp-a-tu-gusto');
          } else $error = __('Email incorrecto. El email suministrado no está en nuestra base de datos.', 'wp-a-tu-gusto');
        } else if (isset($_REQUEST['wpatg-email'])) $error = __('Email incorrecto. El email suministrado no tiene el formato adecuado.', 'wp-a-tu-gusto');?>
        <?php if(isset($ok)) echo "<p style=' background-color: #21f3f3; color: #000; padding: 10px; text-align: center;'><b>".$ok."</b></p>"; ?>
        <?php if(isset($error)) echo "<p style='background-color: red; color: #fff; padding: 10px; text-align: center;'><b>".$error."</b></p>"; ?>
        <p><?php _e('Email', 'wp-a-tu-gusto'); ?></p>
        <input type="email" name="wpatg-email" value="" placeholder="<?php _e('tunombre@email.com', 'wp-a-tu-gusto'); ?>" required />
        <button type="submit" name="wpatg-send"><?php _e('Enviar', 'wp-a-tu-gusto'); ?></button>
      </form>
    <?php } else { ?>
      <div id="wpatg-form-home">
        <h2><?php _e("Personaliza tu perfil para recibir ", "wp-a-tu-gusto"); ?></h2>
        <h3><?php _e("sólo lo que te interesa", "wp-a-tu-gusto"); ?></h3>
        <div class="cols">
          <p>
            <?php _e("Te vamos a contar el día a día de la empresa, de hacia donde va tu sector, los eventos a los que no puedes faltar, las ayudas de las que te puedes beneficiar, coger ideas de la competencia o aprender de los éxitos y fracasos…, porque eso, también te lo contamos.", "wp-a-tu-gusto"); ?><br/><br/>
            <b><?php _e("Spricomunica te informa solo si lo solicitas.", "wp-a-tu-gusto"); ?></b>
          </p>
          <p>
            <?php _e("Elige lo que te interesa y recibe en tu email las comunicaciones según tus preferencias.", "wp-a-tu-gusto"); ?><br/><br/>
            <a class="btn btn-primary" href="<?php echo get_the_permalink()."?wpatg_tab=login"; ?>"><?php _e("Quiero editar mi perfil", "wp-a-tu-gusto"); ?></a>
            <a class="btn btn-primary" href="<?php echo get_the_permalink()."?wpatg_tab=register"; ?>"><?php _e("Quiero darme de alta", "wp-a-tu-gusto"); ?></a>
          </p>
        </div>
      </div>
    <?php } ?>
  </div>
  <?php echo wpatg_zone_show_css(); ?>  
  <?php return ob_get_clean();
}
add_shortcode('wpatg_login', 'wpatg_login');


/* wpatg_zone */
function wpatg_zone($params = array(), $content = null) {
  if(!isset($_COOKIE['wpatg'])) return; //Si existe la cookie ni seguimos.
  ob_start(); 
  $menu = [
    "editar-perfil" => __("Editar perfil", "wp-a-tu-gusto"),
    "archivo-boletines" => __("Archivo de boletines", "wp-a-tu-gusto"),
    "logout" => __("Salir", "wp-a-tu-gusto"),
  ]; ?>
  <div id="wpatg" class="logged">
    <?php if(isset($menu)) wpatg_menu($menu); ?>
    <div id="wpatg-form-profile">
      <?php if(isset($_REQUEST['wpatg_tab'])) { 
        if($_REQUEST['wpatg_tab'] == 'editar-perfil' ) wpatg_zone_edit_profile();
        else if($_REQUEST['wpatg_tab'] == 'archivo-boletines') wpatg_zone_archive();
      } else wpatg_zone_edit_profile(); ?>
    </div>
    <?php echo do_shortcode("[wpatg_banner]"); ?>
  </div>
  <?php echo wpatg_zone_show_css(); ?>
  <?php $file = WPATG_ARCHIVE_CACHE_FILE;
  if (isset($_REQUEST['wpatg_tab']) && 
    $_REQUEST['wpatg_tab'] != 'archivo-boletines' && 
    (!file_exists($file) || time()-filemtime($file) >= WPATG_ARCHIVE_CACHE_TIME)) { //Si el cacheo de newsletters es viejo lo recargamos via ajax sin que el usuario se de cuenta. ?>
    <script>
      jQuery.ajax( "<?=admin_url('admin-ajax.php'); ?>?action=archive_cache" )
      .done(function() {
        console.log( "Cache Success" );
      })
      .fail(function() {
        console.log( "Cache Error" );
      });
    </script>
  <?php } ?>
  <?php return ob_get_clean();
}
add_shortcode('wpatg_zone', 'wpatg_zone');

function wpatg_zone_show_css() { ?>
  <style>
    <?php echo str_replace("[URL]", plugin_dir_path(__FILE__)."/../", file_get_contents(dirname(__FILE__)."/../assets/css/style.css")); ?>
  </style>
<?php }

function wpatg_zone_edit_profile() {
  $contact = getLoggedUser(); ?>
  <?php $formData = getFields(); 
  $formFields = getFields("fields");
  $fomLangs = getFields("langs");
  $formNewsletters = getFields("newsletters");
  $formInterests = getFields("interests");
  $formCompanies = getFields("companies");
  $formNotifications = getFields("notifications");
  
  if(isset($_REQUEST['wpatg_save_edit_profile'])){ //Guardamos los datos
    $contact->setNombre($_REQUEST['my-data']['data']['nombre']);
    $contact->setApellidos($_REQUEST['my-data']['data']['apellidos']);
    $contact->setTelefono($_REQUEST['my-data']['data']['telefono']);
    foreach($_REQUEST['my-data']['field'] as $field_id => $value) {
      $contact->setField($field_id, $value);
    }
    $contact->setField(WPATG_LAST_UPDATE_FIELD_ID, date("Y/m/d")); //Actualziamos la fecha de la útlima automatización
    $contact->updateProfileAC();

    //Si no está apuntado a la lista lo apuntamos
    if(!$contact->hasList(WPATG_MAIN_NEWLETTER_ID)) $contact->setList(WPATG_MAIN_NEWLETTER_ID, 1);

    //Idiomas
    foreach ($fomLangs as $lang) {
      if(isset($_REQUEST['my-data']['langs'][$lang['id']]) && $_REQUEST['my-data']['langs'][$lang['id']] == 'add' ) {
        if(!$contact->hasTag($lang['id'])) $contact->setTag($lang['id']);
      } else {
        if($contact->hasTag($lang['id'])) $contact->deleteTag($lang['id']);
      }
    }

    //Intereses y tipos de empresa
    $controlTags = 0;
    foreach ($formInterests as $tag) {
      if(isset($_REQUEST['my-data']['tags'][$tag['id']]) && $_REQUEST['my-data']['tags'][$tag['id']] == 'add' ) {
        if(!$contact->hasTag($tag['id'])) {
          $controlTags ++;
          $contact->setTag($tag['id']);
          if(isset($tag['automup'])) $contact->executeAutomation ($tag['automup']);
        }
      } else {
        if($contact->hasTag($tag['id'])) {
          $contact->deleteTag($tag['id']);
          if(isset($tag['automdown'])) $contact->executeAutomation ($tag['automdown']);
        }
      }
    }
    if($controlTags > 0) { //Si elegimos algún interes, quitamos al etiqueta 322 => interes-newsletter-todos
      $contact->deleteTag(322);
    } else { //Si no elige ningún interes, añadimos la etiqueta 322 => interes-newsletter-todos
      $contact->setTag(322);
    }


    foreach ($formCompanies as $tag) {
      if(isset($_REQUEST['my-data']['tags'][$tag['id']]) && $_REQUEST['my-data']['tags'][$tag['id']] == 'add' ) {
        if(!$contact->hasTag($tag['id'])) {
          $contact->setTag($tag['id']);
          if(isset($tag['automup'])) $contact->executeAutomation ($tag['automup']);
        }
      } else {
        if($contact->hasTag($tag['id'])) {
          $contact->deleteTag($tag['id']);
          if(isset($tag['automdown'])) $contact->executeAutomation ($tag['automdown']);
        }
      }
    }

    //Boletines
    foreach ($formNewsletters as $tag) {
      if(isset($_REQUEST['my-data']['tags'][$tag['id']]) && $_REQUEST['my-data']['tags'][$tag['id']] == 'add' ) {
        if(!$contact->hasTag($tag['id'])) {
          $contact->setTag($tag['id']);
          if(isset($tag['automup'])) $contact->executeAutomation ($tag['automup']);
        }
      } else {
        if($contact->hasTag($tag['id'])) {
          $contact->deleteTag($tag['id']);
          if(isset($tag['automdown'])) $contact->executeAutomation ($tag['automdown']);
        }
      }
    }

    //Notificaciones
    foreach ($formNotifications as $tag) {
      if(isset($_REQUEST['my-data']['tags'][$tag['id']]) && $_REQUEST['my-data']['tags'][$tag['id']] == 'add' ) {
        if(!$contact->hasTag($tag['id'])) $contact->setTag($tag['id']);
      } else {
        if($contact->hasTag($tag['id'])) $contact->deleteTag($tag['id']);
      }
    }

    echo "<p class='adviseok'>".__('Datos actualizados correctamente', 'wp-a-tu-gusto')."</p>";?>
  <?php } ?>
  <?php wpatg_gamification($contact); ?>
  <form id="wpatg_form_my_data" method="post" action="<?php echo get_the_permalink()."?wpatg_tab=editar-perfil"; ?>">


    <div>
      <h2><?php _e("Mis intereses", "wp-a-tu-gusto"); ?></h2>
      <p><?php _e("Recibir solo aquello que es importante para ti. Ni más ni menos.", "wp-a-tu-gusto"); ?></p>
    </div>
    <div id="interests">
      <?php foreach ($formInterests as $tag ) { ?>
        <label><input type="checkbox" class="checkbox-tag-intereses" id="checkbox-tag-<?php echo $tag['id']; ?>" name="my-data[tags][<?php echo $tag['id']; ?>]" value="add" <?php echo ($contact->hasTag($tag['id']) == true ? "checked" : "") ?> /> <?php echo $tag['text']; ?></label>
      <?php } ?>
      <label><input type="checkbox" id="select-all-intereses" /> <?php _e('Me interesan los contenidos de todas estas temáticas.', 'wp-a-tu-gusto'); ?></label>
    </div>




    <div>
      <h2><?php _e("Boletines", "wp-a-tu-gusto"); ?></h2>
      <p><?php _e("Ideas clave y titulares que te avanzan los detalles en los que puedes profundizar.", "wp-a-tu-gusto"); ?></p>
      <p><?php printf(__("<a href='%s' target='_blank'>Mira nuestros boletines anteriores</a>, querrás suscribirte.", "wp-a-tu-gusto"), get_the_permalink()."?wpatg_tab=archivo-boletines"); ?></p>
    </div>
    <div id="newsletters">
      <?php foreach ($formNewsletters as $field ) { ?>
        <div>
          <img src="<?php echo $field['image']; ?>" alt="" />
          <label><input class="checkbox-boletines" type="checkbox" id="checkbox-<?php echo (isset($field['field']) ? $field['field'] : ""); ?>" name="my-data[tags][<?php echo $field['id']; ?>]" value="add" <?=($contact->hasTag($field['id']) ? "checked" : "") ?> />
          <b><?php echo $field['text']; ?></b></label>
          <p><?php echo $field['description']; ?></p>
        </div>
      <?php } ?>
    </div>



    <div>
      <h2><?php _e("Otras notificaciones", "wp-a-tu-gusto"); ?></h2>
      <p><?php _e("Recibir de manera independiente las alertas informativas con información especializada.", "wp-a-tu-gusto"); ?></p>
    </div>
    <div id="notifications">
      <div>
        <label><b><?php _e('¿Quieres recibir notificaciones especiales de alguno de estos tipos?', 'wp-a-tu-gusto'); ?></b></label>
        <div>
          <?php foreach ($formNotifications as $tag ) { ?>
            <label><input type="checkbox" class="checkbox-tag-notifications" id="checkbox-tag-<?php echo $tag['id']; ?>" name="my-data[tags][<?php echo $tag['id']; ?>]" value="add" <?php echo ($contact->hasTag($tag['id']) == true ? "checked" : "") ?> /> <?php echo $tag['text']; ?></label>
          <?php } ?>
          <label><input type="checkbox" id="select-all-notifications" /> <?php _e('Me interesan las comunicaciones de todos estos tipos.', 'wp-a-tu-gusto'); ?></label>
        </div>
      </div>
    </div>



    <div>
      <h2><?php _e("Mis datos", "wp-a-tu-gusto"); ?></h2>
    </div>
    <div>
      <label><b><?php _e('Email', 'wp-a-tu-gusto'); ?></b><br/><p style="margin: 12px 0px 0px 0px;"><?php echo $contact->email; ?></p></label>
      <?php foreach ($formFields as $id => $field ) { if($field['position'] == 'pre') { ?>
        <?php wptag_zone_my_data_draw_field($field, $contact); ?>
      <?php } } ?>
      <?php foreach ($formData as $label => $input ) { ?>
        <label <?php echo ($input['required'] ? "class='required'" : ""); ?>><b><?php echo $input['name']; ?></b>
          <input type="<?php echo (isset($input['type']) && $input['type'] != '' ? $input['type'] : "text"); ?>" name="my-data[data][<?php echo $label; ?>]" value="<?php echo $contact->{$label}; ?>" placeholder="<?php echo $input['name']; ?>" oninvalid="onError();"<?php echo ($input['required'] ? " required" : ""); ?> <?php echo (isset($input['pattern']) && $input['pattern'] ? " pattern='".$input['pattern']."'" : ""); ?> />
        </label>
      <?php } ?>
      <?php foreach ($formFields as $id => $field ) { if($field['position'] == 'post') { ?>
        <?php wptag_zone_my_data_draw_field($field, $contact); ?>
      <?php } } ?>
      <div id="langs">
        <label><b><?php _e('¿En qué <span>idioma</span> quieres recibirnos?', 'wp-a-tu-gusto'); ?></b></label>
        <?php foreach ($fomLangs as $lang) { ?>
          <label><input type="checkbox" class="checkbox-lang" id="checkbox-lang-<?php echo $lang['id']; ?>" name="my-data[langs][<?php echo $lang['id']; ?>]" value="add" <?php echo ($contact->hasTag($lang['id']) == true ? "checked" : "") ?> /> <?php echo $lang['text']; ?></label>
        <?php } ?>
        <label><input type="checkbox" id="select-all-lang" /> <?php _e('Seleccionar/Deseleccionar ambos', 'wp-a-tu-gusto'); ?></label>
      </div>
    </div>





    <div>
      <h2><?php _e("¿Con cual de estos perfiles de empresa te identificas?", "wp-a-tu-gusto"); ?></h2>
      <p><?php _e("Cuéntanos más sobre ti para poder acercarte las ayudas, eventos y servicios de los que te puedes beneficiar.", "wp-a-tu-gusto"); ?></p>
    </div>
    <div id="companies">
      <?php foreach ($formCompanies as $tag ) {  ?>
        <label><input type="checkbox" class="checkbox-tag-companies" id="checkbox-tag-<?php echo $tag['id']; ?>" name="my-data[tags][<?php echo $tag['id']; ?>]" value="add" <?php echo ($contact->hasTag($tag['id']) == true ? "checked" : "") ?> /> <?php echo $tag['text']; ?></label>
      <?php } ?>
      <label><input type="checkbox" id="select-all-companies" /> <?php _e('Me interesan los contenidos de todas estas temáticas.', 'wp-a-tu-gusto'); ?></label>
    </div>


























    <div>
       <button class="btn btn-black" type="submit" name="wpatg_save_edit_profile"><?php _e('Guardar', 'wp-a-tu-gusto'); ?></button>
    </div>
  </form>
  <?php /*wpatg_gamification();*/ ?>
  <script>
    jQuery(document).ready(function() {
      if(jQuery('.checkbox-lang:checked').length == jQuery('.checkbox-lang').length) {
        jQuery("#select-all-lang").prop('checked', true);
      }
      jQuery(".checkbox-lang").change(function() {
        if(jQuery('.checkbox-lang:checked').length == jQuery('.checkbox-lang').length) {
          jQuery("#select-all-lang").prop('checked', true);
        } else {
          jQuery("#select-all-lang").prop('checked', false);
        }
      });
      jQuery("#select-all-lang").change(function() {
        jQuery(".checkbox-lang").prop('checked', jQuery(this).is(':checked'));
      });

      if(jQuery('.checkbox-tag-notifications:checked').length == jQuery('.checkbox-tag-notifications').length) {
        jQuery("#select-all-notifications").prop('checked', true);
      }
      jQuery(".checkbox-tag-notifications").change(function() {
        if(jQuery('.checkbox-tag-notifications:checked').length == jQuery('.checkbox-tag-notifications').length) {
          jQuery("#select-all-notifications").prop('checked', true);
        } else {
          jQuery("#select-all-notifications").prop('checked', false);
        }
      });
      jQuery("#select-all-notifications").change(function() {
        jQuery(".checkbox-tag-notifications").prop('checked', jQuery(this).is(':checked'));
      });



      if(jQuery('.checkbox-tag-intereses:checked').length == jQuery('.checkbox-tag-intereses').length) {
        jQuery("#select-all-intereses").prop('checked', true);
      }
      jQuery(".checkbox-tag-intereses").change(function() {
        if(jQuery('.checkbox-tag-intereses:checked').length == jQuery('.checkbox-tag-intereses').length) {
          jQuery("#select-all-intereses").prop('checked', true);
        } else {
          jQuery("#select-all-intereses").prop('checked', false);
        }
      });
      jQuery("#select-all-intereses").change(function() {
        jQuery(".checkbox-tag-intereses").prop('checked', jQuery(this).is(':checked'));
      });

      if(jQuery('.checkbox-tag-companies:checked').length == jQuery('.checkbox-tag-companies').length) {
        jQuery("#select-all-companies").prop('checked', true);
      }
      jQuery(".checkbox-tag-companies").change(function() {
        if(jQuery('.checkbox-tag-companies:checked').length == jQuery('.checkbox-tag-companies').length) {
          jQuery("#select-all-companies").prop('checked', true);
        } else {
          jQuery("#select-all-companies").prop('checked', false);
        }
      });
      jQuery("#select-all-companies").change(function() {
        jQuery(".checkbox-tag-companies").prop('checked', jQuery(this).is(':checked'));
      });
    });
  </script>
<?php }

function wptag_zone_my_data_draw_field($field, $contact) { ?>
  <label <?php echo ($field['required'] ? "class='required'" : ""); ?>><b><?php echo $field['text']; ?></b>
    <?php if(isset($field['select'])) { ?>
      <select name="my-data[field][<?php echo $field['id']; ?>]" oninvalid="onError();" <?php echo ($field['required'] ? " required" : ""); ?>>
        <option value=""><?php printf(__('Elige tu %s', 'wp-a-tu-gusto'), mb_strtolower($field['text'])); ?></option>
        <?php foreach($field['select'] as $select) { ?>
          <option value="<?php echo $select['label']; ?>"<?php echo ($contact->fields[$field['id']] == $select['label'] ? " selected='selected'" : ""); ?>><?php echo $select['text']; ?></option>
        <?php } ?>
      </select>
    <?php } else { ?>
      <input type="<?php echo (isset($field['type']) && $field['type'] != '' ? $field['type'] : "text"); ?>" name="my-data[field][<?php echo $field['id']; ?>]" value="<?php echo $contact->fields[$field['id']]; ?>" placeholder="<?php echo $field['text']; ?>" oninvalid="onError();" <?php echo ($field['required'] ? " required" : ""); ?><?php echo (isset($field['pattern']) && $field['pattern'] ? " pattern='".$field['pattern']."'" : ""); ?> />
    <?php } ?>
  </label>
<?php }

function wpatg_gamification($current_contact = '', $mini = false) { 
  if (is_object($current_contact)) $contact = $current_contact;
  else $contact = getLoggedUser(); 
  $total = 0;
  $completed = []; 
  $uncompleted = [
    "basicprofile" => [
      "percent" => 10,
      "completedtext" => __("Has rellenado los datos básicos de tu perfil.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("No has rellenado los datos básicos de tu perfil.", "wp-a-tu-gusto")],
    "advancedprofile" => [
      "percent" => 10,
      "completedtext" => __("Has rellenado todos los datos de tu perfil.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("No has rellenado todos los datos de tu perfil.", "wp-a-tu-gusto")],
    "lastupdate" => [
      "percent" => 10,
      "completedtext" => __("Has actualizado tu perfíl hace poco.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("Hace mucho tiempo desde la última vez que actualizaste tu perfil.", "wp-a-tu-gusto")],
    "langs" => [
      "percent" => 14,
      "completedtext" => __("Has elegido el idioma.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("No has elegido el idioma.", "wp-a-tu-gusto")],
    "interests" => [
      "percent" => 14,
      "completedtext" => __("Has especificado tus intereses.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("No has especificado tus intereses.", "wp-a-tu-gusto")],
    "companies" => [
      "percent" => 14,
      "completedtext" => __("Has detallado tu perfil de empresa.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("No has detallado tu perfil de empresa.", "wp-a-tu-gusto")],
    "newsletters" => [
      "percent" => 14,
      "completedtext" => __("Estás suscrito a nuestras newsletters.", "wp-a-tu-gusto"), 
      "uncompletedtext" => __("No estás suscrito a ninguna de nuestras newsletters.", "wp-a-tu-gusto")],
    "notifications" => [
      "percent" => 14,
      "completedtext" => __("Estás suscrito a nuestras notificaciones especiales.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("No estás suscrito a ninguna notificación especial.", "wp-a-tu-gusto")],
  ]; ?>
  <p>// <?php _e("Calidad de tu perfil", "wp-a-tu-gusto"); ?></p>
  <?php 
  if($contact->nombre != '' && $contact->apellidos != '' && $contact->fields[7] != '') {
    $completed['basicprofile'] = $uncompleted['basicprofile'];
    unset($uncompleted['basicprofile']);
    $total = $total +  $completed['basicprofile']['percent'];
  }

  if($contact->telefono != '' && 
  $contact->fields[10] != '' && 
  /*$contact->fields[40] != '' &&*/ 
  /*$contact->fields[44] != '' &&*/ 
  /*$contact->fields[42] != '' &&*/
  /*$contact->fields[43] != '' &&*/ 
  $contact->fields[41] != '') {
    $completed['advancedprofile'] = $uncompleted['advancedprofile'];
    unset($uncompleted['advancedprofile']);
    $total = $total +  $completed['advancedprofile']['percent'];
  }

  if((strtotime("now") - strtotime($contact->fields[WPATG_LAST_UPDATE_FIELD_ID])) < (60*60*24*30)) { //Si hace menos de un mes de la ultima actualziación del perfil 
    $completed['lastupdate'] = $uncompleted['lastupdate'];
    unset($uncompleted['lastupdate']);
    $total = $total +  $completed['lastupdate']['percent'];
  }

  foreach (array("langs", "interests", "companies", "newsletters", "notifications") as $label) {
    foreach (getFields($label) as $tag) {
      if($contact->hasTag($tag['id'])) {
        $completed[$label] = $uncompleted[$label];
        unset($uncompleted[$label]);
        $total = $total +  $completed[$label]['percent'];
        break;
      }
    }
  } ?>
  <div class="chartbar" style="--percent: <?=$total;?>%;"><span><?php printf(__("> Rellenado al %s&#37;", "wp-a-tu-gusto"), $total);?></div>
  <?php if(!$mini) { ?>
    <div id="tasks">
      <div class="advise">
        <?php if($total == 100) _e("<span>¡Bien hecho!</span> Has completado tu perfil.", "wp-a-tu-gusto");
          else if($total >= 75) _e("<span>El perfil es bueno.</span> Trata de mejorarlo para recibir los contenidos que mejor se ajustan a tus preferencias.", "wp-a-tu-gusto");
          else if($total >= 50) _e("<span>El perfil es bueno.</span> Trata de mejorarlo para recibir los contenidos que mejor se ajustan a tus preferencias.", "wp-a-tu-gusto");
          else if($total >= 25) _e("<span>Dedícale un poco de tiempo a tu perfil/span> y veras como nuestras comunicaciones contigo mejoran.", "wp-a-tu-gusto");
          else if($total >= 0) _e("<span>Tu perfil necesita dedicación por tu parte.</span> Si le dedicas tiempo, nosotros nos comprometemos a mejorar nuestras comunicaciones contigo para ofrecerte los temas que realmente te interesan.", "wp-a-tu-gusto"); ?>
      </div>
      <div id="completed">
        <ul>
          <?php if(count($completed) > 0) { ?>
            <?php foreach($completed as $item) { ?>
              <li><?=$item['completedtext'];?></li>
            <?php } ?>
          <?php } ?>
          <?php if(count($uncompleted) > 0) { ?>
            <?php foreach($uncompleted as $item) { ?>
              <li class="uncompleted"><?=$item['uncompletedtext'];?></li>
            <?php } ?>
          <?php } ?>
        </ul>
      </div>
    </div>
    <script>
      jQuery(document).ready(function() {
        jQuery('#wpatg #tasks .advise').click(function() {
          jQuery('#wpatg #tasks #completed').fadeToggle();
          jQuery('#wpatg #tasks .advise').toggleClass("opened");
        });
      });
    </script>
  <?php } ?>
<?php }

function wpatg_menu($menu) { ?>
  <div class="menu-wpatg">
    <ul>
      <?php foreach($menu as $tab => $label) { ?>
        <li class="<?=$tab;?>"><a href="<?php echo get_the_permalink()."?wpatg_tab=".$tab; ?>"><?=$label;?></a></li>
      <?php } ?>
    </ul>
  </div>
<?php }

/* wpatg_zone */
function wpatg_banner($params = array(), $content = null) {
  ob_start(); ?>
  <div id="wpatg_banner" style="background-image: url(<?php echo get_option("_wpatg_banner_image"); ?>);">
    <div>
      <div>
        <div>
          <?php if(get_option("_wpatg_banner_title_".ICL_LANGUAGE_CODE) != '') { ?><p class="title"><?php echo get_option("_wpatg_banner_title_".ICL_LANGUAGE_CODE); ?></p><?php } ?>
          <?php if(get_option("_wpatg_banner_subtitle_".ICL_LANGUAGE_CODE) != '') { ?><p class="subtitle"><?php echo get_option("_wpatg_banner_subtitle_".ICL_LANGUAGE_CODE); ?></p><?php } ?>
        </div>
        <?php if(get_option("_wpatg_banner_link_".ICL_LANGUAGE_CODE) != '' && get_option("_wpatg_banner_button_".ICL_LANGUAGE_CODE) != '') { ?><a href="<?php echo get_option("_wpatg_banner_link_".ICL_LANGUAGE_CODE); ?>"><?php echo get_option("_wpatg_banner_button_".ICL_LANGUAGE_CODE); ?></a><?php } ?>
      </div>
    </div>
  </div>
  <?php return ob_get_clean();
}
add_shortcode('wpatg_banner', 'wpatg_banner');
