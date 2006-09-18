<?

require_once('ac_common.php');

echo "<h1>check sql_filter_per</h1>";
$a=sql_filter_per(1,1);
$ra=($a==" jr_tech_per = 1 ")?'OK':'ERROR';
echo $a." ".$ra."<br>";
$b= FormatString(sql_filter_per('3','7'));
$exp=FormatString('jr_tech_per in (select p_id from parm_periode  where p_start >= 3 and p_end <= 7)');
$rb=(strcmp($b,$exp)==0)?'OK':'ERROR';

echo "Received $b <br> Expected $exp $rb<br>";

?>
