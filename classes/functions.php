<?php

function getLoggedUser() {
  $cookie = json_decode(stripslashes(urldecode($_COOKIE['wpatg']))); 
  $user = new UserAC($cookie->wpatg_contact_id);
  if($user->hash != $cookie->wpatg_hash) {
    die;
  }
  return $user;
}

function wpatg_send_login_email($user) {
  $headers = array(
    "From: info@spri.eus",
    "Reply-To: info@spri.eus",
    "X-Mailer: PHP/".phpversion(),
    "Content-type: text/html; charset=utf-8"
  );
  $message = str_replace("[LINK]", get_the_permalink()."?wpatg_tab=editar-perfil&wpatg_hash=".$user->hash."&wpatg_contact_id=".$user->id, file_get_contents(dirname(__FILE__)."/../emails/email_login_".ICL_LANGUAGE_CODE.".html"));
  $message = str_replace("[URL]", plugin_dir_url(__FILE__)."..", $message);
  $message = str_replace("[BANNER]", wptag_generate_email_banner(), $message);
  wp_mail ($user->email, __("Aquí puedes actualizar tus preferencias de suscripción a Grupo SPRI", 'wp-a-tu-gusto'), $message, $headers);
}

function wpatg_send_register_email($user) {
  $headers = array(
    "From: info@spri.eus",
    "Reply-To: info@spri.eus",
    "X-Mailer: PHP/".phpversion(),
    "Content-type: text/html; charset=utf-8"
  );
  $message = str_replace("[LINK]", get_the_permalink()."?wpatg_tab=editar-perfil&wpatg_hash=".$user->hash."&wpatg_contact_id=".$user->id, file_get_contents(dirname(__FILE__)."/../emails/email_register_".ICL_LANGUAGE_CODE.".html"));
  $message = str_replace("[URL]", plugin_dir_url(__FILE__)."..", $message);
  $message = str_replace("[BANNER]", wptag_generate_email_banner(), $message);
  wp_mail ($user->email, __("Gracias por registrarte ", 'wp-a-tu-gusto'), $message, $headers);
}

function print_pre($string) {
  echo "<pre>";
  print_r ($string);
  echo "</pre>";
}

function wptag_generate_email_banner() {
  $html = "<br/><table border='0' cellspacing='0' cellpadding='0' width='100%' bgcolor='efefef' style='background-color: #efefef;'>
  <tr>
    <td valign='middle' width='355'>
      <table border='0' cellspacing='0' cellpadding='20' width='100%' bgcolor='efefef' style='background-color: #efefef;'>
        <tr>
          <td valign='middle'>".
            (get_option("_wpatg_banner_email_title") != '' ? "<p><font family='Arial' color='000000' size='3' style='font-size: 25px; line-height: 28px; color: #000000;'><b>".get_option("_wpatg_banner_email_title")."</b></font></p>" : "").
            (get_option("_wpatg_banner_email_subtitle") != '' ? "<p><font family='Arial' color='666666' size='2' style='font-size: 18px; line-height: 21x; color: #666666;'><b>".get_option("_wpatg_banner_email_subtitle")."</b></font></p>" : "").
          "</td>
        </tr>
        <tr>
          <td>
            <table border='0' cellspacing='0' cellpadding='5' width='100%' bgcolor='efefef' style='background-color: #efefef;' >
              <tr>
                <td align='center' valign='middle' bgcolor='ffffff' style='background-color: #ffffff; border: 1px solid #000000;'>
                  <a href='".get_option("_wpatg_banner_email_link")."' style='text-decoration: none;'><font family='Arial' color='000000' size='2' style='font-size: 14px; line-height: 17px; color: #000000; text-decoration: none;'><b>".get_option("_wpatg_banner_email_button")."</b></font></a>
                </td>
                <td></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
    <td align='right' valign='top' width='245'>
      <img src='".get_option("_wpatg_banner_email_image")."' alt='' width='245' />
    </td>
  </tr>
</table><br/>";
return $html;
}