<?php
header('Pragma: public');
header('Content-type: application/csv');
header('Content-Disposition: attachment;filename="anc-grand-livre.csv"',FALSE);

require_once 'class_anc_grandlivre.php';

$cn=Dossier::connect();

$gl=new Anc_GrandLivre($cn);
$gl->get_request();
echo $gl->display_csv();




?>
