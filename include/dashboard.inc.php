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
require_once 'class_follow_up.php';

echo '<div class="content">';
global $g_user;
/* others report */
$cal=new Calendar();
$cal->get_preference();
$Operation=new Follow_Up($cn);
$last_operation=$Operation->get_today();
$late_operation=$Operation->get_late();

$Ledger=new Acc_Ledger($cn,0);
$last_ledger=array();
$last_ledger=$Ledger->get_last(20);

// Supplier late and now
$supplier_now=$Ledger->get_supplier_now();
$supplier_late=$Ledger->get_supplier_late();

// Customer late and now
$customer_now=$Ledger->get_customer_now();
$customer_late=$Ledger->get_customer_late();

ob_start();
require_once('template/dashboard.php');
$ret=ob_get_contents();
ob_end_clean();
echo $ret;

echo '</div>';
?>
