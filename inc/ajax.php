<?php

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