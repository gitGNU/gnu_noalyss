<?php
require_once("class_idate.php");
require_once("class_itext.php");
require_once ('constant.php');
require_once ('ac_common.php');
require_once ('class_user.php');
require_once('class_acc_report.php');
require_once('class_periode.php');
require_once ('user_menu.php');
require_once ('class_dossier.php');
require_once('class_todo_list.php');
require_once("class_itextarea.php");
require_once('class_calendar.php');
require_once('class_acc_ledger.php');
echo '<div class="content">';
global $g_user;
/* others report */
$cal=new Calendar();
$cal->get_preference();
$Ledger=new Acc_Ledger($cn,0);
$last_ledger=array();
if ( $g_user->check_action(GESTION)==1)
  {
    $Operation=new Follow_Up($cn);
    $last_ledger=$Ledger->get_last(10);
    $last_operation=$Operation->get_last(10);
  }
else
  {
    $last_operation=array();
  }
ob_start();
require_once('template/dashboard.php');
$ret=ob_get_contents();
ob_end_clean();
echo $ret;

echo '</div>';
?>
