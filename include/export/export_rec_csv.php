<?php
//This file is part of NOALYSS and is under GPL 
//see licence.txt

/**
 * Export to CSV the operations asked in impress_rec.inc.php
 * variable set $g_user,$cn
 * @see impress_rec.inc.php
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');

require_once NOALYSS_INCLUDE.'/class/class_acc_reconciliation.php';
require_once NOALYSS_INCLUDE.'/lib/ac_common.php';
require_once NOALYSS_INCLUDE.'/lib/class_database.php';
require_once NOALYSS_INCLUDE.'/class/class_dossier.php';
require_once NOALYSS_INCLUDE.'/lib/class_noalyss_csv.php';

// --------------------------
// Check if all mandatory arg are passed
foreach (array('choice','p_end','p_start') as $arg)
{
    if ( ! isset ($_GET[$arg])) {
        die ("argument [".$arg."] is missing");
    }
}
extract($_GET);
$r_jrn=(isset($r_jrn))?$r_jrn:'';
// -------------------------
// Create object and export
$acc_reconciliation=new Acc_Reconciliation($cn);
$acc_reconciliation->a_jrn=$r_jrn;
$acc_reconciliation->start_day=$p_start;
$acc_reconciliation->end_day=$p_end;

$array=$acc_reconciliation->export_csv($choice);