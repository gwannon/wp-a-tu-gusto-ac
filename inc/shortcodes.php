<?php

/* -------- SHORTCODES ------ */


/* wpatg_login */
function wpatg_login($params = array(), $content = null) {
  if(isset($_COOKIE['wpatg'])) return; //Si existe la cookie ni seguimos.
  ob_start();?>
   <form id="wpatg-form-login" method="post">
    <h2><?php _e("Personaliza tus boletines a tu gusto", "wp-a-tu-gusto"); ?></h2>
    <p><?php _e("Elige lo que te interesa y recibe en tu email las comunicaciones según tus preferecnias. <b>Indícanos tu email para verificar que realmente eres tú.</b>", "wp-a-tu-gusto"); ?></p>
    <?php if (isset($_REQUEST['wpatg-email']) && is_email($_REQUEST['wpatg-email'])) {
      remove_all_filters('wp_mail', 10);
      if(existsUserAC($_REQUEST['wpatg-email'])) {
        $user = new UserAC($_REQUEST['wpatg-email']);
        $user->executeAutomation (WPATG_AC_ENGAGEMENT_AUTOMATION);
        wpatg_send_login_email($user);
        $ok = __('Para actualizar tus preferencias de suscripción, comprueba tu correo electrónico porque te hemos enviado un mensaje con los pasos para poder hacerlo.', 'wp-a-tu-gusto');
      } else $error = __('Email incorrecto. El email suministrado no está en nuestra base de datos.', 'wp-a-tu-gusto');
    } else if (isset($_REQUEST['wpatg-email'])) $error = __('Email incorrecto. El email suministrado no tiene el formato adecuado.', 'wp-a-tu-gusto');?>
    <?php if(isset($ok)) echo "<p>".$ok."</p>"; ?>
    <?php if(isset($error)) echo "<p>".$error."</p>"; ?>
    <input type="email" name="wpatg-email" value="" placeholder="<?php _e('Email', 'wp-a-tu-gusto'); ?>" required />
    <button type="submit" name="wpatg-send"><?php _e('Enviar', 'wp-a-tu-gusto'); ?></button>
  </form>
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
    //"mis-noticias" => __("Mis noticias", "wp-a-tu-gusto"),
    "archivo-boletines" => __("Archivo de boletines", "wp-a-tu-gusto"),
  ]; ?>
  <div id="wpatg">
    <div class="menu-wpatg">
      <ul>
        <li><a href="<?php echo get_the_permalink(); ?>"><?php _e("Inicio", "wp-a-tu-gusto"); ?></a></li>
        <?php foreach($menu as $tab => $label) { ?>
          <li><a href="<?php echo get_the_permalink()."?wpatg_tab=".$tab; ?>"><?=$label;?></a></li>
        <?php } ?>
        <li><a href="<?php echo get_the_permalink()."?wpatg_logout=yes"; ?>"><?php _e("Salir", "wp-a-tu-gusto"); ?></a></li>
      </ul>
    </div>
    <div class="content-wpatg">
      <?php if(isset($_REQUEST['wpatg_tab'])) { 
        if($_REQUEST['wpatg_tab'] == 'editar-perfil') {
          wpatg_zone_edit_profile();
        } else if($_REQUEST['wpatg_tab'] == 'mis-noticias') { ?>
          <h2><?php _e("Mis noticias", "wp-a-tu-gusto"); ?></h2>
          <p>Fase 3</p>
        <?php } else if($_REQUEST['wpatg_tab'] == 'archivo-boletines') wpatg_zone_archive();
        } else { ?>
          <h2><?php _e("Personaliza tus boletines a tu gusto", "wp-a-tu-gusto"); ?></h2>
          <h3><?php _e("Solo te contamos cosas que te interesan", "wp-a-tu-gusto"); ?></h3>
          <p><?php _e("Te vamos a contar el día a día de la empresa. Para que en pocos segundos tengas una visión de la actualidad.", "wp-a-tu-gusto"); ?></p>
          <p><?php _e("De hacia dónde va tu sector, los eventos a los que no puedes faltar, las ayudas de las que te puedes beneficiar, coger ideas de la competencia o aprender de los éxitos y fracasos… porque eso, también te lo contamos.", "wp-a-tu-gusto"); ?></p>
          <p><b><?php _e("Elige lo que te interesa y recibe en tu email las comunicaciones según tus preferencias, Spricomunica adquiere el compromiso de informar solo lo que tú solicitas y cómo tú lo quieres.", "wp-a-tu-gusto"); ?></b></p>
          <p><a class="btn btn-primary" href="<?php echo get_the_permalink()."?wpatg_tab=editar-perfil"; ?>"><?php _e("Quiero editar mi perfil", ""); ?></a></p>
          <hr/>
          <?php wpatg_gamification(); ?>
      <?php } ?>
    </div>
    <?php echo do_shortcode("[wpatg_banner]"); ?>
  </div>
  <?php echo wpatg_zone_show_css(); ?>
  <?php 

  $file = WPATG_ARCHIVE_CACHE_FILE;
  if (isset($_REQUEST['wpatg_tab']) && $_REQUEST['wpatg_tab'] != 'archivo-boletines' && (!file_exists($file) || time()-filemtime($file) >= WPATG_ARCHIVE_CACHE_TIME)) { //Si el cacheo de newsletters es viejo lo recargamos via ajax sin que el usuario se de cuenta. ?>
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
    <?php echo file_get_contents(dirname(__FILE__)."/../assets/css/style.css"); ?>
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

    echo "<p class='adviseok'>".__('Datos actualizados correctamente', 'wp-a-tu-gusto')."</p>";
  } ?>
  <form id="wpatg_form_my_data" method="post" action="<?php echo get_the_permalink()."?wpatg_tab=editar-perfil"; ?>">
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
       <button class="btn btn-black" type="submit" name="wpatg_save_edit_profile"><?php _e('Guardar', 'wp-a-tu-gusto'); ?></button>
    </div>
  </form>
  <?php wpatg_gamification($contact, true); ?>
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

function wpatg_zone_archive() { 
  $file = WPATG_ARCHIVE_CACHE_FILE;
  if (file_exists($file) && time()-filemtime($file) < WPATG_ARCHIVE_CACHE_TIME) { //Si es menos de 1 día usamos el cacheo
    $campaigns = json_decode(file_get_contents($file), true);
  } else {
    $campaigns = wptag_zone_archive_generate_json($file);
  } 
  if(isset($_GET['wpatg_offset'])) $offset = $_GET['wpatg_offset'];
  else $offset = 0; ?>
  <h2><?php _e("Archivo de boletines", "wp-a-tu-gusto"); ?></h2>
  <h3><?php _e("Consulta boletines antiguos", "wp-a-tu-gusto"); ?></h3>
  <div id="archive">
    <?php $sliced_campaigns = array_slice($campaigns, $offset, WPATG_ARCHIVE_MAX_ITEMS); foreach ($sliced_campaigns as $campaign) {
      echo "<a href='".(parse_url(get_the_permalink(), PHP_URL_QUERY) ? '&' : '?') . "wpatg_preview_newsletter=". md5($campaign['name']). "'>".$campaign['subject']."<div>".date(__("d/m/Y", "wp-atu-gusto"), $campaign['date'])."</div><span style='background-image: url(".$campaign['screenshot'].");'></span></a>";
    } ?>
  </div>
  <div class="paginator">
    <?php $counter = 0; for ($i = 0; $i < count($campaigns); $i = $i + WPATG_ARCHIVE_MAX_ITEMS) { $counter++; ?>
      <?php if($i != $offset) { ?>
        <a href="<?php echo get_the_permalink()."?wpatg_tab=archivo-boletines&wpatg_offset=".$i; ?>#archive"><?php echo $counter; ?></a>
      <?php } else { ?>
        <b><?php echo $counter; ?></b>
      <?php } ?>
    <?php } ?>
  </div>
<?php }

function wptag_zone_archive_generate_json($file) {
  $campaigns = array();
  $offset = 0;
  $max = 100;
  file_put_contents($file, "");
  while (count($campaigns) < 50) {
    $json = curlCallGet("/campaigns?orders[sdate]=DESC&offset=".$offset."&limit=".$max);
    $codes = explode(",", WPATG_NEWLETTERS_FILTER);
    $counter = 0;
    foreach($json->campaigns as $campaign) {
      foreach ($codes as $key => $code) {
        if(preg_match("/".$code."/", $campaign->name) && $campaign->mail_send == 1) {
          $message = curlCallGet(str_replace(WPAT_AC_API_URL, "", $campaign->links->campaignMessage));
          $campaigns[] = [
            "name" => $campaign->name,
            "subject" => $message->campaignMessage->subject,
            "screenshot" => $message->campaignMessage->screenshot,
            "date" => strtotime($campaign->sdate),
          ];
        }
      }
    }
    $offset = $offset + $max;
  }
  if (count($campaigns) > 0) { //Guardamos el nuevo cache
    file_put_contents($file, json_encode($campaigns));
  }
  return $campaigns;
}

function wptag_zone_archive_cache_ajax() {
  $file = WPATG_ARCHIVE_CACHE_FILE;
  if (file_exists($file) && time()-filemtime($file) < WPATG_ARCHIVE_CACHE_TIME) { //Si es menos de 1 día usamos el cacheo
    $campaigns = json_decode(file_get_contents($file), true);
  } else {
    $campaigns = wptag_zone_archive_generate_json($file);
  }
}
add_action('wp_ajax_archive_cache', 'wptag_zone_archive_cache_ajax');
add_action('wp_ajax_nopriv_archive_cache', 'wptag_zone_archive_cache_ajax');

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
  <h4>// <?php _e("Calidad de tu perfil", "wp-a-tu-gusto"); ?></h4>
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
  <?php } ?>
<?php }



/* wpatg_login */
function wpatg_banner($params = array(), $content = null) {
  ob_start(); ?>
  <div id="wpatg_banner" style="background-image: url(<?php echo get_option("_wpatg_banner_image"); ?>);">
    <div>
      <div>
        <div>
          <?php if(get_option("_wpatg_banner_title") != '') { ?><p class="title"><?php echo get_option("_wpatg_banner_title"); ?></p><?php } ?>
          <?php if(get_option("_wpatg_banner_subtitle") != '') { ?><p class="subtitle"><?php echo get_option("_wpatg_banner_subtitle"); ?></p><?php } ?>
        </div>
        <?php if(get_option("_wpatg_banner_link") != '' && get_option("_wpatg_banner_button") != '') { ?><a href="<?php echo get_option("_wpatg_banner_link"); ?>"><?php echo get_option("_wpatg_banner_button"); ?></a><?php } ?>
      </div>
    </div>
  </div>
  <?php return ob_get_clean();
}
add_shortcode('wpatg_banner', 'wpatg_banner');


function wpatg_preview_newsletter(){
	if(isset($_REQUEST['wpatg_preview_newsletter'])) {
		
		$max = 50;
		$json = curlCallGet("/campaigns?orders[sdate]=DESC&offset=0&limit=".$max);
		foreach($json->campaigns as $campaign) {
			if(md5($campaign->name) == $_REQUEST['wpatg_preview_newsletter']) {
				$message = curlCallGet(str_replace(WPAT_AC_API_URL, "", $campaign->links->campaignMessage,));
				$htmlcode = curlCallGet(str_replace(WPAT_AC_API_URL, "", $message->campaignMessage->links->message));
				echo $htmlcode->message->html;
				break;
			}
		}
		die;
	}
}
add_action( "template_redirect", "wpatg_preview_newsletter" );

/*
function wptag_zone_archive_generate_json($file) {
  $campaigns = array();
  $offset = 0;
  $max = 100;
  file_put_contents($file, "");
  while (count($campaigns) < 50) {
    $json = curlCallGet("/campaigns?orders[sdate]=DESC&offset=".$offset."&limit=".$max);
    $codes = explode(",", WPATG_NEWLETTERS_FILTER);
    $counter = 0;
    foreach($json->campaigns as $campaign) {
      foreach ($codes as $key => $code) {
        if(preg_match("/".$code."/", $campaign->name) && $campaign->mail_send == 1) {
          $message = curlCallGet(str_replace(WPAT_AC_API_URL, "", $campaign->links->campaignMessage));
          $campaigns[] = [
            "name" => $campaign->name,
            "subject" => $message->campaignMessage->subject,
            "screenshot" => $message->campaignMessage->screenshot,
            "date" => strtotime($campaign->sdate),
          ];
        }
      }
    }
    $offset = $offset + $max;
  }
  if (count($campaigns) > 0) { //Guardamos el nuevo cache
    file_put_contents($file, json_encode($campaigns));
  }
  return $campaigns;
}
*/