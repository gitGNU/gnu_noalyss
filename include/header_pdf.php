<?php
require_once ('postgres.php');
require_once ('debug.php');
require_once ('class.ezpdf.php');
require_once ('class_own.php');
function header_pdf($p_cn,&$p_pdf) {
  $own=new own($p_cn);
  $soc=$own->MY_NAME;
  $date=date('d / m / Y');
  $dossier=" Dossier : ".domaine.$_SESSION['g_dossier']." ".$_SESSION['g_name'];
  $p_pdf->ezText($dossier." ".$soc." ".$date,9);
  }

?>
