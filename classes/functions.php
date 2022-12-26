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
  $message = str_replace("[LINK]", get_the_permalink()."?wpatg_hash=".$user->hash."&wpatg_contact_id=".$user->id, file_get_contents(dirname(__FILE__)."/../emails/email_es.html"));
  wp_mail ($user->email, __("Aquí puedes actualizar tus preferencias de suscripción a Grupo SPRI", 'wp-a-tu-gusto'), $message, $headers);
}

function print_pre($string) {
  echo "<pre>";
  print_r ($string);
  echo "</pre>";
}