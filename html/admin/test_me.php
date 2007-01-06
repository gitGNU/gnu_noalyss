<?
ini_set('include_path','.:../../include');
require_once('ac_common.php');
include_once('postgres.php');
include_once('class_jrn.php');
include_once('class_poste.php');
include_once('class_balance.php');
include_once('impress_inc.php');

$cn=DbConnect(1);
echo '<h1> Test GetRappelSimple </h1>';
$a_Tva=GetArray($cn,"select tva_id,tva_label,tva_poste from tva_rate where tva_rate != 0.0000 order by tva_id");
$per_from=68;$jrn=3;$type='ACH';
$space=0;
foreach($a_Tva as $line_tva)
{
  //initialize Amount TVA
  $tmp1=$line_tva['tva_label'];
  $rap_tva[$tmp1]=0.0;
  if ( $space == 0 )
    $col_tva=str_repeat(" ",6).$line_tva['tva_label'];
  else 
    $col_tva.=str_repeat(" ",$space-strlen($line_tva['tva_label'])).$line_tva['tva_label'];
  $space=9;
} 

$htva=GetRappelSimple($cn,$jrn,$type,$per_from,&$rap_tva);

echo "HTVA = $htva <br>";
var_dump($rap_tva);







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
echo '<h1> $a->GetRowSimple</H1>';
$per_from=67;$per_to=70;$jrn=3;
$c=0;
$ven=new jrn($cn,$jrn);
$b=$ven->GetRowSimple ( $per_from,$per_to,'off',-1,-1);
foreach ($b as $line) {
  $c++;
  print_r ($line);echo '<hr>';}

foreach ($b as $line) {
  echo $line['client']." htva : ".$line['HTVA']." tva ".$line['AMOUNT_TVA']." TVAC ".$line['TVAC']." inline ".$line['TVA_INLINE'];
  $tot=(float) $line['HTVA']+(float)$line['AMOUNT_TVA']-$line['TVAC'];
  if ( round($tot,8) == 0.0 )
    print '      ok';
  else {
    print '      FAILED ';
    echo "Diff = ".$tot." TVAC ".$line['TVAC']; }

echo '<hr>';
}

echo "Il y a $c données trouvées par GetRowSimple  ";
$s=sql_filter_per($cn,$per_from,$per_to,'p_id','jr_tech_per');
$count_rowS=GetArray($cn,"select count(*) as mcount from jrn where $s and jr_def_id=$jrn");
if ( $c != $count_rowS[0]['mcount'] ) {
  echo "<b>ERREUR : GetRowSimple ne renvoie pas le bon nombre de lignes GetRowSimple = $c Jrn = ".$count_rowS[0]['mcount']."</b><hr>";
} else 
echo "ok";
echo "<h1>Computing TVA</h1>";
$a_Tva=GetArray($cn,"select tva_id,tva_label from tva_rate where tva_rate != 0.0000 order by tva_id");
$space=4;$col_tva="";
foreach($a_Tva as $line_tva)
    {
      //initialize Amount TVA
      $tmp1=$line_tva['tva_id'];
      $rap_tva[$tmp1]=0.0;
      if ( $space == 0 )
	$col_tva=str_repeat(" ",6).$line_tva['tva_label'];
	else 
	  $col_tva.=str_repeat(" ",$space-strlen($line_tva['tva_label'])).$line_tva['tva_label'];
      $space=9;
    } 

foreach ($b as $row) 
{
  echo "Line "; var_dump ($row);echo "<hr>";
  foreach ($row['TVA'] as $line)
    {
      var_dump($line);echo "<hr>";
      $tva_id=$line[1][0];
      $rap_tva[$tva_id]+=$line[1][2];
    }
}
var_dump($rap_tva);


echo '<h1>Poste->GetRow</h1>';
$a=new poste($cn,4511); // grand livre
$b=$a->GetRow('1','9999');

echo 'class_poste il y a '.sizeof($b[0]).' données trouvée<br>';

$a=new Balance($cn,4519); // grand livre
$b=$a->GetRow('1','9999');
echo 'class_balance il y a '.sizeof($b).' données trouvée<br>';

echo_debug('Testing echo_debug');
echo_error('Testing echo_error');



?>
