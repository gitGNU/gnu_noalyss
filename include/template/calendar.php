<div class="pc_calendar" id="user_cal">
<?=$month_year?>
<table width="100%">
<tr>
<?php
for ($i=0;$i<=6;$i++){
	echo "<th>";
	echo $week[$i];
	echo "</th>";
}
?>
</tr>
<?php
$ind=1;
$week=0;
$today_month=date('m');
$today_day=date('j');
while ($ind <= $this->day) {
if ( $week == 0 ) echo "<tr>";
$class="workday";
if ( $week == 0 || $week == 6) $class="weekend";
// compute the date
$timestamp_date=mktime(0,0,0,$this->month,$ind,$this->year);
$date_calendar=date('w',$timestamp_date);
$st="";
if ( $today_month==$this->month && $today_day==$ind) 
  $st='  style="border:1px solid red" ';
if ( $date_calendar == $week ) {
	echo '<td class="'.$class.'"'.$st.'>'.'<span class="day">'.$ind."</span>";
	echo $cell[$ind];
	echo '</td>';
	$ind++;$week++;
} else {
   echo "<td></td>";
   $week++;
}
//if ( $ind > $this->day ) exit();
if ( $week == 7 ) { echo "</tr>";$week=0;}
}
if ( $week != 7 ) { echo "</tr>";}
?>

</table>
</div>
