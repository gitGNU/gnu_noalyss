<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../../include/constant.php';
const REPOSITORY_DB=1;
const ACCOUNT_DB=2;

require NOALYSS_INCLUDE."/lib/class_database.php";
require NOALYSS_INCLUDE."/lib/class_iselect.php";

require './table_sql.class.php';

// Show a db connection
$gDossier=HtmlInput::default_value_request('gDossier',-1);
$select = new ISelect('gDossier');
$select->row=20;
$acc=new Database();

$a_dossier=$acc->make_array("select dos_id,dos_id::text||' '||dos_name from ac_dossier order by dos_id");
$a_dossier[]=array('value'=>0,'label'=>'Account_Repository');
$select->value=$a_dossier;
$select->selected = $gDossier;

//////////////////////////////////////////////////////////////////////////////
// If no db is selected then propose one
//////////////////////////////////////////////////////////////////////////////
if ($gDossier==-1) {
?>
<form method="get">
    <?php echo $select->input();?>
    <?php echo HtmlInput::submit("choose_db", "Valider");?>
</form>
<?php
return;
}

//////////////////////////////////////////////////////////////////////////////
// If  db IS selected 
//////////////////////////////////////////////////////////////////////////////
/**
 * Connect to the selected DB
 */
 if ( $gDossier==0) 
  $cn=new Database();
  else 
  $cn=new Database($gDossier,'dos');
   
/**
 * Display list of all tables and view from selected DB
 */
$table_sql = "select schemaname ||','||tablename,tablename||','||schemaname from pg_tables where schemaname not in ('pg_catalog','information_schema') order by 1";

$select_table = new ISelect('table');
$select_table->row=20;
$select_table->value=$cn->make_array($table_sql);
$select_table->selected = HtmlInput::default_value_request("table", "");
?>
  <form method="get">
    Choisissez une table
    <?php echo $select_table->input();?>
    <?php echo HtmlInput::hidden("gDossier", $gDossier);?>,
    <?php echo HtmlInput::submit("choose_table", "Valider")?>
    <a href="?"> Autre</a>
</form>
<?php
///////////////////////////////////////////////////////////////////////////////
// if a table is select , generate file
//////////////////////////////////////////////////////////////////////////////
$table=HtmlInput::default_value_request("table", "");
if ( $table != "") {
    $table_sql=new Table_SQL($cn,$table);
    $table_sql->create_class();
    echo '<pre>';
    $table_sql->send();
    echo '</pre>';
}

?>
