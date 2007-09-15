<?php
header("Content-type: text/xml charset=\"ISO8859-1\"",true);
  /*!\todo add the security here */
require_once ('postgres.php');
require_once ('debug.php');
require_once ('jrn.php');
require_once ('class_operation.php');
$cn=DbConnect($_SESSION['g_dossier']);
$op=new operation($cn);
$op->get_xml_jrn_detail();
?>
