<?php

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

function wpatg_preview_newsletter() {
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