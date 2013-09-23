<?php
if ( !defined ('ALLOWED') )  die('Appel direct ne sont pas permis');

require_once 'class_tag.php';
$tag=new Tag($cn);
$tag->save($_GET);

?>
