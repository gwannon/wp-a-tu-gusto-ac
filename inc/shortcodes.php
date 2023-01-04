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
      $user->executeAutomation (WPATG_AC_ENGAGEMENT_AUTOMATION);
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
    "editar-perfil" => __("Editar perfil", "wp-a-tu-gusto"),
    "mis-noticias" => __("Mis noticias", "wp-a-tu-gusto"),
    "archivo-boletines" => __("Archivo de boletines", "wp-a-tu-gusto"),
  ]; ?>
  <div id="wpatg">
    <h1><?php _e("A tu gusto", "wp-a-tu-gusto"); ?></h1>
    <ul class="menu">
    <li><a href="<?php echo get_the_permalink(); ?>"><?php _e("Inicio", "wp-a-tu-gusto"); ?></a></li>
      <?php foreach($menu as $tab => $label) { ?>
        <li><a href="<?php echo get_the_permalink()."?wpatg_tab=".$tab; ?>"><?=$label;?></a></li>
      <?php } ?>
      <li><a href="<?php echo get_the_permalink()."?wpatg_logout=yes"; ?>"><?php _e("Salir", "wp-a-tu-gusto"); ?></a></li>
    </ul>
    <?php if(isset($_REQUEST['wpatg_tab'])) { 
      if($_REQUEST['wpatg_tab'] == 'editar-perfil') {
        wpatg_zone_edit_profile();
      } else if($_REQUEST['wpatg_tab'] == 'mis-noticias') { ?>
        <h2><?php _e("Mis noticias", "wp-a-tu-gusto"); ?></h2>
        <p>Fase 3</p>
      <?php } else if($_REQUEST['wpatg_tab'] == 'archivo-boletines') wpatg_zone_archive();
      } else { ?>
      	<h2><?php _e("Solo te contamos cosas que te interesan", "wp-a-tu-gusto"); ?></h2>
        <p><?php _e("Te vamos a contar el día a día de la empresa. Para que en pocos segundos tengas una visión de la actualidad. De hacia dónde va tu sector, los eventos a los que no puedes faltar, las ayudas de las que te puedes beneficiar, coger ideas de la competencia o aprender de los éxitos y fracasos… porque eso, también te lo contamos.", "wp-a-tu-gusto"); ?></p>
        <p><?php _e("Elige lo que te interesa y recibe en tu email las comunicaciones según tus preferencias, Spricomunica adquiere el compromiso de informar solo lo que tú lo pides y cómo tú lo pides.", "wp-a-tu-gusto"); ?></p>
        <?php wpatg_gamification(); ?>
    <?php } ?>
    <?php echo do_shortcode("[wpatg_banner]"); ?>
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

function wpatg_zone_edit_profile() {
  $contact = getLoggedUser();
  $formData = getFields(); 
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
    foreach ($formInterests as $tag) {
      if(isset($_REQUEST['my-data']['tags'][$tag['id']]) && $_REQUEST['my-data']['tags'][$tag['id']] == 'add' ) {
        if(!$contact->hasTag($tag['id'])) {
          $contact->setTag($tag['id']);
          $contact->executeAutomation ($tag['automup']);
        }
      } else {
        if($contact->hasTag($tag['id'])) {
          $contact->deleteTag($tag['id']);
          $contact->executeAutomation ($tag['automdown']);
        }
      }
    }
    foreach ($formCompanies as $tag) {
      if(isset($_REQUEST['my-data']['tags'][$tag['id']]) && $_REQUEST['my-data']['tags'][$tag['id']] == 'add' ) {
        if(!$contact->hasTag($tag['id'])) $contact->setTag($tag['id']);
      } else {
        if($contact->hasTag($tag['id'])) $contact->deleteTag($tag['id']);
      }
    }

    //Boletines
    foreach ($formNewsletters as $tag) {
      if(isset($_REQUEST['my-data']['tags'][$tag['id']]) && $_REQUEST['my-data']['tags'][$tag['id']] == 'add' ) {
        if(!$contact->hasTag($tag['id'])) $contact->setTag($tag['id']);
        $contact->executeAutomation ($tag['automup']);
      } else {
        if($contact->hasTag($tag['id'])) $contact->deleteTag($tag['id']);
        $contact->executeAutomation ($tag['automdown']);
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

    echo "<p style='color: green; border: 1px solid green; text-align: center;'>".__('DATOS ACTUALIZADOS', 'wp-a-tu-gusto')."</p>";
  } ?>
  <form id="wpatg_form_my_data" method="post" action="<?php echo get_the_permalink()."?wpatg_tab=editar-perfil"; ?>">
    <div>
      <h2><?php _e("Mis datos", "wp-a-tu-gusto"); ?></h2>
      
    </div>
    <div>
      <label><b><?php _e('Email', 'wp-a-tu-gusto'); ?></b><br/><?php echo $contact->email; ?></label>
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
      <div>
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
      <h2><?php _e("Newsletters", "wp-a-tu-gusto"); ?></h2>
      <p><?php _e("Ideas clave y titulares que te avanzan los detalles en los que puedes profundizar.", "wp-a-tu-gusto"); ?></p>
      <p><?php printf(__("<a href='%s' target='_blank'>Mira nuestros boletines anteriores</a>, querrás suscribirte.", "wp-a-tu-gusto"), get_the_permalink()."?wpatg_tab=archivo-boletines"); ?></p>
    </div>
    <div id="newsletters">
      <?php foreach ($formNewsletters as $field ) { ?>
        <div>
          <img src="<?php echo $field['image']; ?>" alt="" />
          <label><input class="checkbox-boletines" type="checkbox" id="checkbox-<?php echo $field['field']; ?>" name="my-data[tags][<?php echo $field['id']; ?>]" value="add" <?=($contact->hasTag($field['id']) ? "checked" : "") ?> />
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
       <button type="submit" name="wpatg_save_edit_profile"><?php _e('Guardar', 'wp-a-tu-gusto'); ?></button>
    </div>
  </form>
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
  <label><b><?php echo $field['text']; ?><?php echo ($field['required'] ? "*" : ""); ?></b><br/>
    <?php if(isset($field['select'])) { ?>
      <select name="my-data[field][<?php echo $field['id']; ?>]" oninvalid="onError();" <?php echo ($field['required'] ? " required" : ""); ?>>
        <option value=""><?php printf(__('Elige tu %s', 'wp-a-tu-gusto'), mb_strtolower($field['text'])); ?></option>
        <?php foreach($field['select'] as $select) { ?>
          <option value="<?php echo $select['label']; ?>"<?php echo ($contact->fields[$field['id']] == $select['label'] ? " selected='selected'" : ""); ?>><?php echo $select['text']; ?></option>
        <?php } ?>
      </select>
    <?php } else { ?>
      <input type="<?php echo ($field['type'] != '' ? $field['type'] : "text"); ?>" name="my-data[field][<?php echo $field['id']; ?>]" value="<?php echo $contact->fields[$field['id']]; ?>" placeholder="<?php echo $field['text']; ?>" oninvalid="onError();" <?php echo ($field['required'] ? " required" : ""); ?><?php echo ($field['pattern'] ? " pattern='".$field['pattern']."'" : ""); ?> />
    <?php } ?>
  </label>
<?php }

function wpatg_zone_archive() { ?>
  <h2><?php _e("Archivo de boletines", "wp-a-tu-gusto"); ?></h2>
  <div id="archive">
    <?php
      $items = array();
      $json = curlCallGet("/campaigns?orders[sdate]=DESC&offset=0&limit=100");
      $codes = explode(",", WPATG_NEWLETTERS_FILTER);
      $counter = 0;
      foreach($json->campaigns as $campaign) {
        foreach ($codes as $key => $code) {
          if(preg_match("/".$code."/", $campaign->name)) {
            //unset ($codes[$key]);
            $message = curlCallGet(str_replace(WPAT_AC_API_URL, "", $campaign->links->campaignMessage));
            echo "<a href='".(parse_url(get_the_permalink(), PHP_URL_QUERY) ? '&' : '?') . "preview_newsletter=". md5($campaign->name). "'>".$message->campaignMessage->subject."<span style='background-image: url(".$message->campaignMessage->screenshot.");'></span></a><br/>";
            break;
          }
        }
      } ?>
  </div>
<?php }

function wpatg_gamification() { 
  $contact = getLoggedUser(); 
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
      "completedtext" => __("Has especificado el idioma donde quieres tu comunicación.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("No has especificado el idioma donde quieres tu comunicación.", "wp-a-tu-gusto")],
    "interests" => [
      "percent" => 14,
      "completedtext" => __("Has especificado tus intereses.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("No has especificado tus intereses.", "wp-a-tu-gusto")],
    "companies" => [
      "percent" => 14,
      "completedtext" => __("Has especificado tus perfiles de empresa.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("No has especificado tus perfiles de empresa.", "wp-a-tu-gusto")],
    "newsletters" => [
      "percent" => 14,
      "completedtext" => __("Estás suscrito a nuestras newsletters.", "wp-a-tu-gusto"), 
      "uncompletedtext" => __("No estás suscrito a ninguna de nuestras newsletters.", "wp-a-tu-gusto")],
    "notifications" => [
      "percent" => 14,
      "completedtext" => __("Estás suscrito a nuestras notificaciones especiales.", "wp-a-tu-gusto"),
      "uncompletedtext" => __("No estás suscrito a ninguna notificación especial.", "wp-a-tu-gusto")],
  ]; ?>
  <h2><?php _e("Calidad de tu perfil", "wp-a-tu-gusto"); ?></h2>
  <?php 
  if($contact->nombre != '' && $contact->apellidos != '' && $contact->fields[7] != '') {
    $completed['basicprofile'] = $uncompleted['basicprofile'];
    unset($uncompleted['basicprofile']);
    $total = $total +  $completed['basicprofile']['percent'];
  }

  if($contact->telefono != '' && $contact->fields[10] != '' && $contact->fields[40] != '' && $contact->fields[41] != '' && $contact->fields[42] != '' && $contact->fields[43] != '' && $contact->fields[44] != '') {
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
  <div class="chartbar" style="--percent: <?=$total;?>%;"><?php printf(__("Rellenado al<span>%s&#37;</span>", "wp-a-tu-gusto"), $total);?></div>
  <div id="tasks">
    <div class="advise">
      <?php if($total == 100) _e("<span>¡Bien hecho!</span> Has completado tu perfil.", "wp-a-tu-gusto");
        else if($total >= 75) _e("<span>El perfil es bueno.</span> Trata de mejorarlo para recibir los contenidos que mejor se ajustan a tus preferencias.", "wp-a-tu-gusto");
        else if($total >= 50) _e("<span>El perfil es bueno.</span> Trata de mejorarlo para recibir los contenidos que mejor se ajustan a tus preferencias.", "wp-a-tu-gusto");
        else if($total >= 25) _e("<span>Dedícale un poco de tiempo a tu perfil/span> y veras como nuestras comunicaciones contigo mejoran.", "wp-a-tu-gusto");
        else if($total >= 0) _e("<span>Tu perfil necesita dedicación por tu parte.</span> Si le dedicas tiempo, nosotros nos comprometemos a mejorar nuestras comunicaciones contigo para ofrecerte los temas que realmente te interesan.", "wp-a-tu-gusto"); ?>
    </div>
    <?php if(count($completed) > 0) { ?>
      <div id="completed">
        <ul>
        <?php foreach($completed as $item) { ?>
          <li><?=$item['completedtext'];?></li>
        <?php } ?>
        </ul>
      </div>
    <?php } ?>
    <?php if(count($uncompleted) > 0) { ?>
      <div id="uncompleted">
        <ul>
        <?php foreach($uncompleted as $item) { ?>
          <li><?=$item['uncompletedtext'];?></li>
        <?php } ?>
        </ul>
      </div>
    <?php } ?>
  </div>
<?php }



/* wpatg_login */
function wpatg_banner($params = array(), $content = null) {
  ob_start(); ?>
  <div id="wpatg_banner" style="background-image: url(<?php echo get_option("_wpatg_banner_image"); ?>);">
    <p class="title"><?php echo get_option("_wpatg_banner_title"); ?></p>
    <p class="subtitle"><?php echo get_option("_wpatg_banner_subtitle"); ?></p>
    <a href="<?php echo get_option("_wpatg_banner_link"); ?>"><?php echo get_option("_wpatg_banner_button"); ?></a>
  </div>
  <?php return ob_get_clean();
}
add_shortcode('wpatg_banner', 'wpatg_banner');