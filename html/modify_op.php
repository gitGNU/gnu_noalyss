<?php
/*
 *   This file is part of PhpCompta.
 *
 *   PhpCompta is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   PhpCompta is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with PhpCompta; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision*/
/*! \file
 * \brief show a detailled operation in a popup window
 * Update   : add a document , change the comment, the concerned op....
 *
 */
include_once ("ac_common.php");
include_once ("poste.php");
include_once("preference.php");
include_once("central_inc.php");
include_once("check_priv.php");
include_once("user_common.php");
include_once ("postgres.php");
include_once("jrn.php");
include_once("class_widget.php");

/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

html_page_start($User->theme,"onLoad='window.focus();'");
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}

$cn=DbConnect($_SESSION['g_dossier']);

if ( isset( $_GET['p_jrn'] )) {
  $p_jrn=$_GET['p_jrn'];
 } else {
  if ( ! isset ( $_GET['p_jrn'])  ) 
    $p_jrn=0;
  else 
    $p_jrn=$_GET['p_jrn'];
 }
if ( isset ( $_GET['action'] ) ) {
  $action=$_GET['action'];
}
$_SESSION["p_jrn"]=$p_jrn;

$p_view=(isset($_REQUEST['p_view']))?$_REQUEST['p_view']:"error";

if ( isset ( $_REQUEST['action'] ) ) {
  $action=$_REQUEST['action'];
}
if ( ! isset ( $action )) {
  echo_error("modify_op.php No action asked ");
  exit();
}

// Javascript
echo JS_VIEW_JRN_MODIFY;
?>
<script language="javascript">
  function show (p_div) {
  // show the div
  // hide the div
  if ( document.getElementById(p_div) ) {
	var a=document.getElementById(p_div);
	a.style.display="block";
  }
}
function hide (p_div) {
  // hide the div
  if ( document.getElementById(p_div) ) {
	var a=document.getElementById(p_div);
	a.style.display="none";
  }
}
</script>
<?php
echo_debug(__FILE__,__LINE__,"action is $action");
//-----------------------------------------------------
if ( $action == 'update' ) {
  if ( ($priv=CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_GET['p_jrn'],true)) < 1 ) {
      NoAccess();
      exit -1;
    
    }
    // p_id is jrn.jr_id
    $p_id=$_GET["line"];
    echo_debug('modify_op.php',__LINE__," action = update p_id = $p_id");

    echo JS_CONCERNED_OP;


    echo_debug(__FILE__.":".__LINE__."Selected view is $view");


    $view='<h2 class="error">Erreur vue inconnue</h2>';

	// show the detailled operation
    if ( $p_view == 'S' ) 
	  $view='<div id="simple">';
	else 
	  $view='<div id="simple" style="display:none">';

	$view.='<h2 class="info">Vue simple</h2>';
	$view.='<FORM METHOD="POST" enctype="multipart/form-data" ACTION="modify_op.php">';
	$view.=ShowOperationUser($cn,$p_id);
	$view.='<input type="button" onclick="hide(\'simple\');show(\'expert\');" value="Vue expert">';
	$view.='<INPUT TYPE="Hidden" name="action" value="update_record">';
	$view.="<br>";
	$view.="<br>";
	$view.="<br>";
	$view.='<input type="SUBMIT" name="update_record" value="Enregistre">';
	$view.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$view.='<input type="button" value="Fermer" onClick="window.close();  self.opener.RefreshMe();">';
	$view.='</FORM>';
	$view.='</div>';

	if ( $p_view == 'S' ) 
	  $view.='<div id="expert" style="display:none">';
	else 
	  $view.='<div id="expert" style="display:block">';

	$view.='<h2 class="info">Vue expert</h2>';
	$view.='<FORM METHOD="POST" enctype="multipart/form-data" ACTION="modify_op.php">';
	$view.=ShowOperationExpert($cn,$p_id);
	$view.='<input type="button" onclick="hide(\'expert\');show(\'simple\')"  value="Vue simple">';
	$view.='<INPUT TYPE="Hidden" name="action" value="update_record">';
	$view.="<br>";
	$view.="<br>";
	$view.="<br>";
	$view.='<input type="SUBMIT" name="update_record" value="Enregistre">';
	$view.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$view.='<input type="button" value="Fermer" onClick="window.close();  self.opener.RefreshMe();">';
	$view.='</div>';


    echo $view;

 }  
//---------------------------------------------------------------------
// action = vue ca
//---------------------------------------------------------------------
if ( $action=="view_ca") {

  /*!\todo add security here */
  $p_id=$_GET["line"];

  $view='<div id="simple">';
  $view.='<h2 class="info">Vue simple</h2>';
  $view.=ShowOperationUser($cn,$p_id,0);
  $view.='<input type="button" onclick="hide(\'simple\');show(\'expert\');" value="Vue Expert">';
  $view.='<input type="button" value="Fermer" onClick="window.close(); ">';
  $view.='</div>';
  

  
  $view.='<div id="expert" style="display:none">';

  $view.='<h2 class="info">Vue expert</h2>';

  $view.=ShowOperationExpert($cn,$p_id,0);
  $view.='<input type="button" onclick="hide(\'expert\');show(\'simple\')"  value="Vue Simple">';
  $view.='<input type="button" value="Fermer" onClick="window.close(); ">';
  $view.='</div>';
  echo $view;
  exit();

 }

//----------------------------------------------------------------------
// update the record and upload files
//----------------------------------------------------------------------
if ( isset($_POST['update_record']) ) {
  if ( ($priv=CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$p_jrn,true)) !=2 ) {
      NoAccess();
      exit -1;
    
    }
  try {
	// NO UPDATE except rapt & comment && upload pj
	StartSql($cn);

	UpdateComment($cn,$_POST['jr_id'],$_POST['comment']);
	InsertRapt($cn,$_POST['jr_id'],$_POST['rapt']);
	if ( isset ($_FILES)) {
	  echo_debug("modify_op.php",__LINE__, "start upload doc.");
	  save_upload_document($cn,$_POST['jr_grpt_id']);
	}
	if ( isset ($_POST['is_paid'] )) 
	  $Res=ExecSql($cn,"update jrn set jr_rapt='paid' where jr_id=".$_POST['jr_id']);
	
	if ( isset ($_POST['to_remove'] )) {
	  /*! \note we don't remove the document file if another
	   * operation needs it.
	   */
	  $ret=ExecSql($cn,"select jr_pj from jrn where jr_id=".$_POST['jr_id']);
	  if (pg_num_rows($ret) != 0) {
		$r=pg_fetch_array($ret,0);
		$old_oid=$r['jr_pj'];
		if (strlen($old_oid) != 0)
		  {
			// check if this pj is used somewhere else
			$c=CountSql($cn,"select * from jrn where jr_pj=".$old_oid);
			if ( $c == 1 )
			  pg_lo_unlink($cn,$old_oid);
		  }
		ExecSql($cn,"update jrn set jr_pj=null, jr_pj_name=null, ".
		"jr_pj_type=null  where jr_id=".$_POST['jr_id']);
	  }

	}
	//-------------------------------------
	// CA
	//------------------------------------
	if ( $own->MY_ANALYTIC != "un" )
	  {
		// for each item, insert into operation_analytique
		$plan=new PlanAnalytic($cn);
		$a_plan=$plan->get_list();
		foreach ($a_plan as $r_plan) 
		  {
			foreach ($_POST as $post_name=>$post_value) {
			  $a="plan_".$r_plan['id']."_";
			  $po=sscanf($post_name,$a."%d");
			  echo_debug(__FILE__.":".__LINE__,"post ",$post_name);
			  echo_debug(__FILE__.":".__LINE__,"post ",$post_value);
			  echo_debug(__FILE__.":".__LINE__,"a = ",$a);
			  echo_debug(__FILE__.":".__LINE__,"po = ".var_export($po,true));

			  if ( $po[0] != null  ) {

				$op=new operation($cn);
				$op->j_id=$po[0];
				$op->pa_id=$r_plan['id'];
				$op->po_id=$post_value;
				$op->update_from_jrnx($post_value);
			  }
			} // foreach ($_POST
		  }// foreach ($a_plan

	  }//	if ( $own->MY_ANALYTIC != "un" ) 
  } catch (Exception $e) {
    echo '<span class="error">'.
      'Erreur dans l\'enregistrement '.
      __FILE__.':'.__LINE__.' '.
      $e->getMessage();
	echo_debug(__FILE__,__LINE__,$e->getMessage());
    Rollback($cn);

  }

  Commit($cn);

  echo ' <script> 
 window.close();
 self.opener.RefreshMe();
 </script>';
} // if update_record
//-----------------------------------------------------
if (  $action  == 'delete' ) {
  echo_debug('modify_op.php',__LINE__," Call   DeleteRapt($cn,".$_GET['line'].",".$_GET['line2'].")");
  DeleteRapt($cn,$_GET['line'],$_GET['line2']);
 echo ' <script> 
  window.close();
 self.opener.RefreshMe();
 </script>';
}
html_page_stop();
?>
