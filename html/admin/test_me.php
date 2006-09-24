<?
ini_set('include_path','.:../../include');
require_once('ac_common.php');
include_once('postgres.php');
include_once('class_jrn.php');
include_once('class_poste.php');
include_once('class_balance.php');


$cn=DbConnect(1);

echo "<h1>check sql_filter_per</h1>";
$a=sql_filter_per($cn,'01.01.1999','01.01.1999','date','jr_tech_per');
echo $a."<br>";
$b= sql_filter_per($cn,'01.01.2000','01.11.2006','date','j_tech_per');

echo "Received $b <br> ";

echo '<h1>check getRow function</h1>';
echo 'Check Jrn<br>';
$a=new jrn($cn,0); // grand livre
$b=$a->GetRow('1','9999','off');



echo 'class_jrn il y a '.count($b[0]).' données trouvée<br>';

$b=$a->GetRow('1','9999','on');
echo 'Class_jrn il y a '.sizeof($b[0]).' données Centralisees trouvée<br>';

$a=new poste($cn,4511); // grand livre
$b=$a->GetRow('1','9999');

echo 'class_poste il y a '.sizeof($b[0]).' données trouvée<br>';

$a=new Balance($cn,4519); // grand livre
$b=$a->GetRow('1','9999');
echo 'class_balance il y a '.sizeof($b).' données trouvée<br>';

echo_debug('Testing echo_debug');
echo_error('Testing echo_error');


?>
