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
require_once ("constant.php");

/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

html_page_start($User->theme,"onLoad='window.focus();'");
require_once('class_dossier.php');
$gDossier=dossier::id();

$cn=DbConnect($gDossier);
$p_jrn=(isset($_REQUEST['p_jrn']))?$_REQUEST['p_jrn']:0;

if ( isset ( $_GET['action'] ) ) {
  $action=$_GET['action'];
}
//$_SESSION["p_jrn"]=$p_jrn;

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
  if ( ($priv=CheckJrn($gDossier,$_SESSION['g_user'],$_GET['p_jrn'],true)) < 1 ) {
      NoAccess();
      exit -1;
    
    }
    // p_id is jrn.jr_id
    $p_id=$_GET["line"];
    echo_debug('modify_op.php',__LINE__," action = update p_id = $p_id");

    echo JS_CONCERNED_OP;





    $view='<h2 class="error">Erreur vue inconnue</h2>';

	// show the detailled operation
    if ( $p_view == 'S' ) 
	  $view='<div id="simple">';
	else 
	  $view='<div id="simple" style="display:none">';

	$view.='<h2 class="info">Vue simple</h2>';
	$view.='<FORM METHOD="POST" enctype="multipart/form-data" ACTION="modify_op.php">';
	$view.=dossier::hidden();
	$readonly=($p_view=='E')?0:1;
	$view.=ShowOperationUser($cn,$p_id,$readonly);
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
	$view.=dossier::hidden();
	$readonly=($p_view=='S')?0:1;
	$view.=ShowOperationExpert($cn,$p_id,$readonly);
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
  if ( ($priv=CheckJrn($gDossier,$_SESSION['g_user'],$p_jrn,true)) !=2 ) {
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
	$own = new Own($cn);

	if ( $own->MY_ANALYTIC != "nu" )
	  {
	    // Check the total only for mandatory
	    //
	    if ( $own->MY_ANALYTIC == "ob") {
	      $tab=0;	   	    $row=1;
	      while (1) {
			if ( !isset ($_POST['nb_t'.$tab])) 
			  break;
			$tot_tab=0;

			for ($i_row=0;$i_row <= MAX_COMPTE;$i_row++) {
			  if ( ! isset($_POST['val'.$tab.'l'.$i_row]))
				continue;
			  $tot_tab+=$_POST['val'.$tab.'l'.$i_row];
			}
			print_r ( "\$tot_tab  $tot_tab \$_POST['amount_t'.$tab ".$_POST['amount_t'.$tab]);
			if ( $tot_tab != $_POST['amount_t'.$tab]) {
			  echo "Erreur montant CA";
			  echo "Op&eacute;ration annul&eacute;e";
			  return;
			}
			$tot_tab=0;
			$tab++;
	      }
	    }

	    // we need first old the j_id and j_poste
	    // so we fetch them all from the db
	    $sql="select j_id,j_poste,to_char(j_date,'DD.MM.YYYY') as j_date,j_debit ".
	      "from jrn join jrnx on (j_grpt=jr_grpt_id) ".
	      "where jr_id=".$_POST['jr_id'];
	    $res=ExecSql($cn,$sql);

	    $array_jid=pg_fetch_all($res);
	    // if j_poste match 6 or 7 we insert them
	    $count=0;
	    $group=NextSequence($cn,"s_oa_group");

	    foreach( $array_jid as $row_ca) {
	      echo_debug(__FILE__.':'.__LINE__,"array is ",$row_ca);
	      if ( ereg("^[6,7]+",$row_ca['j_poste'])) {
			echo_debug(__FILE__.':'.__LINE__,"count is ",$count);
			$op=new operation($cn);
			$op->delete_by_jid($row_ca['j_id']);
			$op->j_id=$row_ca['j_id'];
			$op->oa_debit=$row_ca['j_debit'];
			$op->oa_date=$row_ca['j_date'];
			$op->oa_group=$group;
			$op->oa_description=$_POST['comment'];
			$op->save_form_plan($_POST,$count);
			$count++;
	      } //if ereg
	    }//foreach
	  }//	if ( $own->MY_ANALYTIC != "nu" ) 
  } catch (Exception $e) {
    echo '<span class="error">'.
      'Erreur dans l\'enregistrement '.
      __FILE__.':'.__LINE__.' '.
      $e->getMessage();
	echo_debug(__FILE__,__LINE__,$e->getMessage());
    Rollback($cn);
    exit();
  }

  Commit($cn);
  /*!\todo REMOVE DEBUG HERE */
  /*  echo ' <script> 
 window.close();
 self.opener.RefreshMe();
 </script>';*/
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
