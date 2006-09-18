<?
ini_set('include_path','.:../../include');
require_once('ac_common.php');
include_once('postgres.php');
include_once('class_jrn.php');
include_once('class_poste.php');
include_once('class_balance.php');


echo "<h1>check sql_filter_per</h1>";
$a=sql_filter_per('01.01.1999','01.01.1999');
echo $a."<br>";
$b= sql_filter_per('01.01.2000','01.11.2006');

echo "Received $b <br> ";

echo '<h1>check getRow function</h1>';
echo 'Check Jrn<br>';
$cn=DbConnect(-2,'dossier1');
$a=new jrn($cn,0); // grand livre
$b=$a->GetRow('01.02.2000','01.01.2006','off');
echo 'class_jrn il y a '.sizeof($b).' données trouvée<br>';

$b=$a->GetRow('01.02.2000','01.01.2006','on');
echo 'Class_jrn il y a '.sizeof($b).' données Centralisees trouvée<br>';

$a=new poste($cn,4519); // grand livre
$b=$a->GetRow('01.02.2000','01.01.2006');
echo 'class_poste il y a '.sizeof($b).' données trouvée<br>';

$a=new Balance($cn,4519); // grand livre
$b=$a->GetRow('01.02.2000','01.01.2006');
echo 'class_balance il y a '.sizeof($b).' données trouvée<br>';


?>
