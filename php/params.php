<?php

// Data folder and zip download
$config_dir = "data/";
$zip_file = "data.zip";
$zip_file_for_download = date('Y-m-d-His_') . $zip_file;


// Profil
$default_profil = "EIE";
$profil = isset($_GET['profil']) ? $_GET['profil'] : $default_profil;
$profils = str_replace('data/', '', glob('data/*',  GLOB_ONLYDIR));  // List all dir with glob and remove root with str_replace

// Add a profil selector in navbar to change profil
// If set to false, profil are still reachable directly via URL parameters `?profile=MurMur`
$show_profils_selector = false;


// Mail used by contact form
$mail_for_contact = "verif-devis@caracals.org";


?>


<script>
var profil = "<?php echo $profil?>";

var simpleProfil = <?php echo file_exists('data/'.$profil.'/simple') ? 'true' : 'false' ?>;
</script>
